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
    *** Calls the stored procedure listBooksAuthors() and returns the results as an array
    **/
    public function getBookAuthors() {

      $values = [];
      foreach ($this->db->query('Call listBooksAuthors()') as $number=>$row){
        $values['books'][$number]['book_id'] = $row['bookID'];
        $values['books'][$number]['book_title'] = $row['bookTitle'];        
        $values['books'][$number]['book_checked_out'] = $row['checkedOut'];
        $values['books'][$number]['book_checked_out_who'] = $row['checkedOutWho'];
        $values['books'][$number]['author_first_name'] = $row['firstName'];
        $values['books'][$number]['author_middle_name'] = $row['middleName'];
        $values['books'][$number]['author_last_name'] = $row['lastName'];
      }

      return $values;
    }

    /**
    *** Calls the stored procedure listAuthors()
    **/
    public function getAuthors() {

      $values = [];
      foreach ($this->db->query('Call listAuthors()') as $number=>$row){
        $values['authors'][$number]['first_name'] = $row['firstName'];
        $values['authors'][$number]['middle_name'] = $row['middleName'];        
        $values['authors'][$number]['last_name'] = $row['lastName'];
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

    /** 
    *** Call the stored procedure checkInBook()
    **/
    public function checkInBook($bookID){
      $this->db->query("Call checkInBook($bookID)");
    }

    /** 
    *** Call the stored procedure checkOutBook()
    **/
    public function checkOutBook($bookID, $name){
      $this->db->query("Call checkOutBook($bookID, $name)");
    }

    /** 
    *** Call the stored procedure addBookAuthor()
    **/
    public function addBook($bookTitle, $authorFirstname, $authorMiddleName, $authorLastName){
      $this->db->query("CALL home_intranet.addBookAuthor($bookTitle,$authorFirstname,$authorMiddleName,$authorLastName)");
    }

    /** 
    *** Call the stored procedure deleteBookAuthor()
    **/
    public function deleteBook($bookID){
      $this->db->query("CALL home_intranet.deleteBookAuthor($bookID)");
    }
}