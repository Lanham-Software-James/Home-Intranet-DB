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
      $this->db->query("Call home_intranet.`books.checkOutBook`($bookID, $name)");
    }

    /** 
    *** Call the stored procedure addBookAuthor()
    **/
    public function addBook($bookTitle, $authorFirstname, $authorMiddleName, $authorLastName){
      $this->db->query("CALL home_intranet.`books.addBookAuthor`($bookTitle,$authorFirstname,$authorMiddleName,$authorLastName)");
    }

    /** 
    *** Call the stored procedure deleteBookAuthor()
    **/
    public function deleteBook($bookID){
      $this->db->query("CALL home_intranet.`books.deleteBookAuthor`($bookID)");
    }

    /**
     * Call the sotre procedure listPlants()
     */
    public function getPlants() {

      $values = [];
      foreach ($this->db->query("Call home_intranet.`plants.countPlants`()") as $number=>$row){
        $values['count']['plants'] = $row['count'];
      }

      foreach ($this->db->query("Call home_intranet.`plants.countPlantSpecies`()") as $number=>$row){
        $values['count']['species'] = $row['count'];
      }

      foreach ($this->db->query('Call home_intranet.`plants.listPlantsAll`()') as $number=>$row){
        $values['data'][$number]['plant_id'] = $row['plantID'];
        $values['data'][$number]['plant_name'] = $row['plantName'];        
        $values['data'][$number]['plant_species'] = $row['plantSpecies'];
        $values['data'][$number]['plant_location'] = $row['plantLocation'];
      }

      return $values;
    }

    /**
     * Call the sotre procedure list()
     */
    public function getPlantSpecies() {

      $values = [];

      foreach ($this->db->query('Call home_intranet.`plants.listPlantSpeciesAll`()') as $number=>$row){
        $values['data'][$number]['species_id'] = $row['speciesID'];
        $values['data'][$number]['species_name'] = $row['speciesName'];
      }

      return $values;
    }

    /**
     * Call the stored procedure home_intranet.`plants.listPlantLocationsAll`
     */
    public function getPlantLocations() {

      $values = [];

      foreach ($this->db->query('Call home_intranet.`plants.listPlantLocationsAll`()') as $number=>$row){
        $values['data'][$number]['location_id'] = $row['locationID'];
        $values['data'][$number]['location_name'] = $row['locationName'];
      }

      return $values;
    }

    /**   
    *** Call the stored procedure home_intranet.`plants.addPlant`()
    **/
    public function addPlant($plantName, $plantSpecies, $plantLocation){
      $this->db->query("CALL home_intranet.`plants.addPlant`($plantName, $plantSpecies, $plantLocation)");
    }
    
    /** 
    *** Call the stored procedure home_intranet.`plants.deletePlant`()
    **/
    public function deletePlant($plantID){
      $this->db->query("CALL home_intranet.`plants.deletePlant`($plantID)");
    }
}