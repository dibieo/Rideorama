<?php

class Events_Model_DbTable_Events extends Zend_Db_Table_Abstract
{

    protected $_name = 'events';

    protected $_referenceMap = array(
      'city' => array(
            'columns' => array('location_id'),
            'refTableClass' => 'Admin_Model_DbTable_City',
            'refColumns' => array('id')
      )  
        
        
    );
    
    
    /**
     * Adds a new Event to the database
     * @param type $name
     * @param type $date
     * @param type $time
     * @param type $city 
     * @param type $banner
     */
    
    public function addEvent($name, $date, $time, $city, $banner ){
        
       $data = array(
           'name' => $name,
           'event_date' => $date,
           'event_time'=> $time,
           'banner' => $banner,
           'location_id' => $city  
       );
       $this->insert($data);
    }
    
    
    public function getEvents(){
        
        return $this->fetchAll();
    }
    
    public function deleteEvent($id){
        
        $this->delete('id ='. $id);
    }
}

