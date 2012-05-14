<?php

class Admin_Model_DbTable_City extends Zend_Db_Table_Abstract
{

    protected $_name = 'city';

    protected $_referenceMap = array(
     
        'State' => array(
            'columns' => array('state_id'),
            'refTableClass' => 'Admin_Model_DbTable_State',
            'refColumns' => array('id')
            
            
        )
           
    );
    
    protected $_dependentTables = array('Events_Model_DbTable_Events');
    
    /**
     * Function: addCity
     * Adds a city to the database.
     * @param type $name
     * @param type $state_id 
     */
   public function addCity($name, $state_id){
       
       $data = array(
           
           'name' => $name,
           'state_id' => $state_id
           
       );
       $this->insert($data);
   }
   
   /**
    *Function: deleteCity
    * @param type $id 
    */
   public function deleteCity($id){
       
       $this->delete('id = '. $id);
   }
   
   /**
    * Function: updateCity
    * @param type $id
    * @param type $name
    * @param type $state_id 
    */
   public function updateCity($id, $name, $state_id){
       
       $data = array(
           'name' => $name,
           'state_id' => $state_id
       );
       
       $this->update($data, 'id ='. $id);
   }
   
   public function getCity($id){
       
      return $this->fetchRow('id ='. $id)->toArray();
   }
   
   /**
    * Function: getAllCities
    * Returns all the cities ordered by the city name
    * getALlCities
    * @return Zend_DbTable_Rowset_Abstract
    */
   public function getAllCities(){
       
       return $this->fetchAll(null, 'name');
       
   }
   
   /**
    * Function: getCitiesOrderedByState
    * Returns all the cities ordered by state
    * @return Zend_DbTable_Rowset_Abstract 
    */
   public function getCitiesOrderedByState(){
       
       return $this->fetchAll(null, $this->ParentRow('Admin_Model_DbTable_State')->name);
   }
   
  
   
   public function getCityStates(){
   
        $all_cities = array();
        $cities = $this->fetchAll();
        foreach($cities as $c){
            $name = $c['name'];
            $abbv = $c->findParentRow('Admin_Model_DbTable_State');
            $abbv = $abbv['abbv'];
            $state_abbv = $name . ' ('. $abbv .')';
            array_push($all_cities, array(
              'key'=> $c->id,
              'value' => $state_abbv 
                ));
        }
        return $all_cities;
    
   }
}

