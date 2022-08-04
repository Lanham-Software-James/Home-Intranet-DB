<?php
namespace HomeIntranet\Database;

require_once __DIR__.'/../db.class.php';

class Greenhouse extends DB {
  public function __construct()
  {
    parent::__construct();
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
      $values['data'][$number]['last_watered'] = $row['lastWatered'];
      $values['data'][$number]['water_frequency'] = $row['waterFrequency'];
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
   * Call the stored procedure home_intranet.`plants.listWaterFrequencies`
   */
  public function getWaterFrequencies() {
  
    $values = [];
  
    foreach ($this->db->query('Call home_intranet.`plants.listWaterFrequencies`()') as $number=>$row){
      $values['data'][$number]['frequency_id'] = $row['frequencyID'];
      $values['data'][$number]['frequency_name'] = $row['frequencyName'];
    }
  
    return $values;
  }

  /**   
  *** Call the stored procedure home_intranet.`plants.waterPlant`()
  **/
  public function waterPlant($plantID){

    $this->db->query("CALL home_intranet.`plants.waterPlant`($plantID)");
  }

  /**   
  *** Call the stored procedure home_intranet.`plants.addPlant`()
  **/
  public function addPlant($plantName, $plantSpecies, $plantLocation, $lastWater, $waterFrequency){

    $newPlantName = $this->db->quote($plantName);
    $newPlantSpecies = $this->db->quote($plantSpecies);
    $newPlantLocation = $this->db->quote($plantLocation);
    $newLastWater = $this->db->quote($lastWater);

    foreach ($this->db->query("CALL home_intranet.`plants.addPlant`($newPlantName, $newPlantSpecies, $newPlantLocation, $newLastWater, $waterFrequency)") as $number => $row) {
      $values['data']['new_plant_id'] = $row['newPlantID'];
    }

    return $values;
  }
  
  /** 
  *** Call the stored procedure home_intranet.`plants.deletePlant`()
  **/
  public function deletePlant($plantID){
    $this->db->query("CALL home_intranet.`plants.deletePlant`($plantID)");
  }

  /**
   * Call the stored procedure home_intranet.`plants.getDisabledPlants`()
   */
  public function getDisabledPlants() {
    foreach($this->db->query("Call home_intranet.`plants.getDisabledPlants`()") as $number => $row) {
      $values['data'][$number]['plant_id'] = $row['plantID'];
      $values['data'][$number]['plant_date_disabled'] = $row['dateDisabled'];
    }

    return $values;
  }
  
  /** 
  *** Call the stored procedure home_intranet.`plants.removePlant`()
  **/
  public function removePlant($plantID){

    $this->db->query("Call home_intranet.`plants.removePlant`($plantID)");
  }

  /**
   * Call the stored procedure home_intranet.`plants.getDisabledSpecies`()
   */
  public function getDisabledSpecies() {
    foreach($this->db->query("Call home_intranet.`plants.getDisabledSpecies`()") as $number => $row) {
      $values['data'][$number]['species_id'] = $row['speciesID'];
      $values['data'][$number]['species_date_disabled'] = $row['dateDisabled'];
    }

    return $values;
  }
  
  /** 
  *** Call the stored procedure home_intranet.`plants.removeSpecies`()
  **/
  public function removeSpecies($speciesID){

    $this->db->query("Call home_intranet.`plants.removeSpecies`($speciesID)");
  }

  /**
   * Call the stored procedure home_intranet.`plants.getDisabledLocations`()
   */
  public function getDisabledLocations() {
    foreach($this->db->query("Call home_intranet.`plants.getDisabledLocations`()") as $number => $row) {
      $values['data'][$number]['location_id'] = $row['locationID'];
      $values['data'][$number]['location_date_disabled'] = $row['dateDisabled'];
    }

    return $values;
  }

  /** 
  *** Call the stored procedure home_intranet.`plants.removeLocation`()
  **/
  public function removeLocation($locationID){

    $this->db->query("Call home_intranet.`plants.removeLocation`($locationID)");
  }

  /**
  * Call the stored procedure home_intranet.`plants.logActivity`
  */
  public function logActivity($userName, $activity, $itemID = null) {
    $newUserName = $this->db->quote($userName);

    $this->db->query("CALL home_intranet.`plants.logActivity`($newUserName, $activity, $itemID)");
  }
}