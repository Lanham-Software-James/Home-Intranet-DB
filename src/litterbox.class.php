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
  *** Calls the stored procedure home_intranet.`fosters.addFoster`() and returns the new foster ID
  **/
  public function addFoster($fosterName) {
    $newFosterName = $this->db->quote($fosterName);

    foreach($this->db->query("Call home_intranet.`fosters.addFoster`($newFosterName)") as $number => $row){
      $values['data']['new_foster_id'] = $row['newFosterID'];
    }

    return $values;
  }

  /**
  *** Calls the stored procedure home_intranet.`fosters.editFoster`()
  **/
  public function editFoster($fosterID, $fosterName, $fosterOrder) {
    $newFosterName = $this->db->quote($fosterName);

    $this->db->query("Call home_intranet.`fosters.editFoster`($fosterID, $newFosterName, $fosterOrder)");
  }

  /**
  *** Calls the stored procedure home_intranet.`fosters.deleteFoster`()
  **/
  public function deleteFoster($fosterID) {
    $this->db->query("Call home_intranet.`fosters.deleteFoster`($fosterID)");
  }

  /**
   * Call the stored procedure home_intranet.`fosters.getDisabledFosters`()
   */
  public function getDisabledFosters() {
    foreach($this->db->query("Call home_intranet.`fosters.getDisabledFosters`()") as $number => $row) {
      $values['data'][$number]['foster_id'] = $row['fosterID'];
      $values['data'][$number]['foster_date_disabled'] = $row['dateDisabled'];
    }

    return $values;
  }

  /** 
  *** Call the stored procedure home_intranet.`fosters.removeFoster`()
  **/
  public function removeFoster($fosterID){

    $this->db->query("Call home_intranet.`fosters.removeFoster`($fosterID)");
  }

  /**
  * Call the stored procedure home_intranet.`fosters.logActivity`
  */
  public function logActivity($userName, $activity, $itemID = null) {
    $newUserName = $this->db->quote($userName);

    $this->db->query("CALL home_intranet.`fosters.logActivity`($newUserName, $activity, $itemID)");
  }

}