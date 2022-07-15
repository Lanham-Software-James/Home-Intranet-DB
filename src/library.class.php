<?php
namespace HomeIntranet\Database;

require_once __DIR__.'/../db.class.php';

class Library extends DB {
  public function __construct()
  {
    parent::__construct();
  }

  /**
   *** Calls the stored procedure listBooksAuthors() and returns the results as an array
   **/
   public function getBookAuthors() {
    
     $values = [];
     foreach ($this->db->query('Call home_intranet.`books.countBooks`()') as $number=>$row){
       $values['count']['books'] = $row['count'];
     }
    
     foreach ($this->db->query('Call home_intranet.`books.countAuthors`()') as $number=>$row){
       $values['count']['authors'] = $row['count'];
     }
    
     foreach ($this->db->query('Call home_intranet.`books.listBooksAuthorsAll`()') as $number=>$row){
       $values['data'][$number]['book_id'] = $row['bookID'];
       $values['data'][$number]['book_title'] = $row['bookTitle'];        
       $values['data'][$number]['book_checked_out'] = $row['checkedOut'];
       $values['data'][$number]['book_checked_out_who'] = $row['checkedOutWho'];
       $values['data'][$number]['author_id'] = $row['authorID'];
       $values['data'][$number]['author_first_name'] = $row['firstName'];
       $values['data'][$number]['author_middle_name'] = $row['middleName'];
       $values['data'][$number]['author_last_name'] = $row['lastName'];
     }
    
     return $values;
   }
  
   /**
   *** Calls the stored procedure listAuthors()
   **/
   public function getAuthors() {
    
     $values = [];
     foreach ($this->db->query('Call home_intranet.`books.countAuthors`()') as $number=>$row){
       $values['count'] = $row['count'];
     }
    
     foreach ($this->db->query('Call home_intranet.`books.listAuthorsAll`()') as $number=>$row){
       $values['data'][$number]['first_name'] = $row['firstName'];
       $values['data'][$number]['middle_name'] = $row['middleName'];        
       $values['data'][$number]['last_name'] = $row['lastName'];
     }
    
     return $values;
   }
  
   /** 
   *** Call the stored procedure checkInBook()
   **/
   public function checkInBook($bookID){
     $this->db->query("Call home_intranet.`books.checkInBook`($bookID)");
   }
  
   /** 
   *** Call the stored procedure checkOutBook()
   **/
   public function checkOutBook($bookID, $name){
    $newName = $this->db->quote($name);
    $this->db->query("Call home_intranet.`books.checkOutBook`($bookID, $newName)");
   }
  
   /** 
   *** Call the stored procedure addBookAuthor()
   **/
   public function addBook($bookTitle, $authorFirstName, $authorMiddleName, $authorLastName){
    
    $newBookTitle = $this->db->quote($bookTitle);
    $newAuthorFirstname = $this->db->quote($authorFirstName);
    $newAuthorMiddleName = $this->db->quote($authorMiddleName);
    $newAuthorLastName = $this->db->quote($authorLastName);

     $this->db->query("CALL home_intranet.`books.addBookAuthor`($newBookTitle,$newAuthorFirstname,$newAuthorMiddleName,$newAuthorLastName)");
   }
  
   /** 
   *** Call the stored procedure deleteBookAuthor()
   **/
   public function deleteBook($bookID, $authorID){
     $this->db->query("CALL home_intranet.`books.deleteBookAuthor`($bookID, $authorID)");
   }
}