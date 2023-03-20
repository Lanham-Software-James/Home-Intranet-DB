<?php
namespace HomeIntranet\Database;

require_once __DIR__.'/../db.class.php';

class FitBit extends DB {
  public function __construct()
  {
    parent::__construct();
  }

  /**
   * Call the sotre procedure list()
   */
  public function getStepGoals() {
  
    $values = [];
  
    foreach ($this->db->query('Call home_intranet.`fitbit.getStepGoals`()') as $number=>$row){
      $values['data'][$number]['step_goal_id'] = $row['id'];
      $values['data'][$number]['step_goal_name'] = $row['name'];
      $values['data'][$number]['step_goal_count'] = $row['stepCount'];
    }
  
    return $values;
  }

  /**
  * Call the stored procedure home_intranet.`plants.logActivity`
  */
  public function logActivity($userName, $activity, $itemID = null) {
    // $newUserName = $this->db->quote($userName);

    // $this->db->query("CALL home_intranet.`plants.logActivity`($newUserName, $activity, $itemID)");
  }
}