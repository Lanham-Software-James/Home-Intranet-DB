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