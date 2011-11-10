<?php

class Admin_Model_DbTable_Landmark extends Zend_Db_Table_Abstract
{

    protected $_name = 'landmark';


    protected $_referenceMap = array(
        
        'city' => array(
            
            'columns' => array('city_id'),
            'refTableClass' => 'Admin_Model_DbTable_City',
            'refColumns' => array('id')
        ),
        
        'category' => array(
            
            'columns' => array('cat_id'),
            'refTableClass' => 'Admin_Model_DbTable_LandmarkCategory',
            'refColumns' => array('id')
        )
        
    );
    
    public function addLandmark($name, $pic, $address, $city, $category){
        
        $data = array (
            'name' => $name,
            'pic' => $pic,
            'address' => $address,
            'city_id' => $city,
            'cat_id' => $category
           
        );
        
        $this->insert($data);
    }
    
    /**
     *
     * @return type 
     */
    public function getAllLandmarks(){
        
        return $this->fetchAll();
    }
    
    /**
     *
     * @param type $id 
     */
    public function deleteLandmark($id){
        
        $this->delete('id = '. $id);
    }
    
    /**
     *
     * @param type $id 
     */
    public function getLandmark($id){
        
        $this->fetchRow('id = '. $id);
    }
    
    public function updateLandmark($id, $name, $pic, $city, $cat, $address){
        
           $data = array (
            'name' => $name,
            'pic' => $pic,
            'address' => $address,
            'city_id' => $city,
            'cat_id' => $category
           
        );
        
      $this->update($data, 'id = '. $id);
    }
    
}

