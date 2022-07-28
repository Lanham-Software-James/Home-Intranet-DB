<?php
namespace HomeIntranet\Database;

require_once __DIR__.'/../db.class.php';

class LitterBox extends DB {
  public function __construct()
  {
    parent::__construct();
  }

  /**
  *** Calls the stored procedure home_intranet.`fosters.listFostersAll`() and returns the results as an array
  **/
  public function getFosters() {
    
    $values = [];
    foreach ($this->db->query('Call home_intranet.`fosters.countFoster`()') as $number=>$row){
      $values['count'] = $row['count'];
    }
   
    foreach ($this->db->query('Call home_intranet.`fosters.listFostersAll`()') as $number=>$row){
      $values['data'][$number]['foster_id'] = $row['fosterID'];
      $values['data'][$number]['foster_name'] = $row['fosterName'];        
      $values['data'][$number]['foster_order'] = $row['fosterOrder'];
    }
   
    return $values;
  }

  /**
  *** Calls the stored procedure home_intranet.`fosters.addFoster`() and returns the results as an array
  **/
  public function addFoster($fosterName) {
    $newFosterName = $this->db->quote($fosterName);
    $this->db->query("Call home_intranet.`fosters.addFoster`($newFosterName)");
  }

  /**
  *** Calls the stored procedure home_intranet.`fosters.deleteFoster`() and returns the results as an array
  **/
  public function deleteFoster($fosterID) {
    $this->db->query("Call home_intranet.`fosters.deleteFoster`($fosterID)");
  }

}