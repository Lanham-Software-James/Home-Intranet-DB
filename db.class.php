<?php

class DB
{
  private $db;

    /**
    * Create an instance of the database layer and connection
    */
    public function __construct()
    {
      $conn = NULL;
      $ini = parse_ini_file('app.ini');

      try{
            
        if(isset($ini['ssh_host']) && isset($ini['ssh_user']) && isset($ini['ssh_key'])){
          shell_exec( "ssh -i {$ini['ssh_key']} -o StrictHostKeyChecking=no -fN -L 3306:127.0.0.1:3306 {$ini['ssh_user']}@{$ini['ssh_host']}" );
        }
    
        $conn = new PDO("mysql:host={$ini['db_host']};dbname={$ini['db_name']}", $ini['db_user'], $ini['db_password']);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      } catch(PDOException $e) {
        echo 'ERROR: ' . $e->getMessage();
      }   
    
      $this->db = $conn;
    }

    /**
    *** Calls the stored procedure selectBookAuthor() and returns the results as an array
    **/
    public function getBookAuthors() {

      $values = [];
      foreach ($this->db->query('Call selectBookAuthor()') as $number=>$row){
        $values['books'][$number]['number'] = $row['number'];
        $values['books'][$number]['book_id'] = $row['bookID'];
        $values['books'][$number]['book_title'] = $row['bookTitle'];
        $values['books'][$number]['author_first_name'] = $row['firstName'];
        $values['books'][$number]['author_middle_name'] = $row['middleName'];
        $values['books'][$number]['author_last_name'] = $row['lastName'];
        $values['books'][$number]['book_checked_out'] = $row['bookCheckOut'];
        $values['books'][$number]['book_checked_out_who'] = $row['bookCheckedOutWho'];
      }

      return $values;
    }

    /** 
    *** Call the stored procedure countBooks() and returns the result as a number
    **/
    public function countBooks() {
      foreach($this->db->query('Call countBooks()') as $row){
        $value['count'] = $row[0];
      }
      return $value;
    }
}