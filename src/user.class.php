<?php
namespace HomeIntranet\Database;

require_once __DIR__.'/../db.class.php';

class User extends DB {

  /** 
  *** Call the stored procedure home_intranet.`users.getUserByUserName`()
  **/
  public function getUserForAuth($userName){
    $newUserName = $this->db->quote($userName);

    $values = [];
    foreach ($this->db->query("Call home_intranet.`users.getUserForAuth`($newUserName)") as $row){
      $values['user_name'] = $row['userName'];
      $values['password'] = $row['password'];
      $values['user_role'] = $row['userRole'];
    }
    return $values;
  }

  /** 
  *** Call the stored procedure home_intranet.`users.getPermissionsByRoleID`()
  **/
  public function getPermissionsByRole($role){

    $values = [];
    $newRole = $this->db->quote($role);
    
    foreach ($this->db->query("Call home_intranet.`users.getPermissionsByRole`($newRole)") as $row){
      $values['permissions'] = $row['permissions'];
    }
    return $values;
  }

  /** 
  *** Call the stored procedure home_intranet.`users.listUsersAll`()
  **/
  public function getUsers(){

    $values = [];
    foreach ($this->db->query("Call home_intranet.`users.countUsers`()") as $row){
      $values['count'] = $row['count'];
    }

    foreach ($this->db->query("Call home_intranet.`users.listUsersAll`()") as $number=>$row){
      $values['data'][$number]['user_id'] = $row['userID'];
      $values['data'][$number]['first_name'] = $row['firstName'];
      $values['data'][$number]['last_name'] = $row['lastName'];
      $values['data'][$number]['user_name'] = $row['userName'];
      $values['data'][$number]['user_role'] = $row['userRole'];
    }
    return $values;
  }

  /** 
  *** Call the stored procedure home_intranet.`users.listRolesAll`()
  **/
  public function getRoles(){

    $values = [];
    foreach ($this->db->query("Call home_intranet.`users.listRolesAll`()") as $number=>$row){
      $values['data'][$number]['role_id'] = $row['roleID'];
      $values['data'][$number]['role_name'] = $row['roleName'];
    }
    return $values;
  }

  /** 
  *** Call the stored procedure home_intranet.`users.addUser`()
  **/
  public function addUser($userName, $password, $firstName, $lastName, $userRole){
    $newUserName = $this->db->quote($userName);
    $newPassword = $this->db->quote($password);
    $newFirstName = $this->db->quote($firstName);
    $newLastName = $this->db->quote($lastName);
    $newUserRole = $this->db->quote($userRole);

    foreach($this->db->query("Call home_intranet.`users.addUser`($newUserName, $newPassword, $newFirstName, $newLastName, $newUserRole)") as $row) {
      $values['data']['new_user_id'] = $row['newUserID'];
    }

    return $values;
  }

  /** 
  *** Call the stored procedure home_intranet.`users.deleteUser`()
  **/
  public function deleteUser($userID){

    $this->db->query("Call home_intranet.`users.deleteUser`($userID)");
  }

  /**
   * Call the stored procedure home_intranet.`users.getDisabledUsers`()
   */
  public function getDisabledUsers() {
    foreach($this->db->query("Call home_intranet.`users.getDisabledUsers`()") as $number => $row) {
      $values['data'][$number]['user_id'] = $row['userID'];
      $values['data'][$number]['user_date_disabled'] = $row['dateDisabled'];
    }

    return $values;
  }

  /** 
  *** Call the stored procedure home_intranet.`users.removeUser`(IN inputLogID INT)
  **/
  public function removeUser($userID){

    $this->db->query("Call home_intranet.`users.removeUser`($userID)");
  }

  /**
   * Call the stored procedure home_intranet.`users.getLogsAllCron`()
   */
  public function getLogsAllCron() {
    foreach($this->db->query("Call home_intranet.`users.getLogsAllCron`()") as $number => $row) {
      $values['data'][$number]['log_id'] = $row['logID'];
      $values['data'][$number]['log_date_time'] = $row['logDateTime'];
    }

    return $values;
  }

  /** 
  *** Call the stored procedure home_intranet.`users.removeLogEntry`(IN inputLogID INT)
  **/
  public function removeLogEntry($logID){

    $this->db->query("Call home_intranet.`users.removeLogEntry`($logID)");
  }

  /**
  * Call the stored procedure home_intranet.`users.logActivity`
  */
  public function logActivity($userName, $activity, $itemID = 'NULL') {
    $newUserName = $this->db->quote($userName);

    $this->db->query("CALL home_intranet.`users.logActivity`($newUserName, $activity, $itemID)");
  }
}