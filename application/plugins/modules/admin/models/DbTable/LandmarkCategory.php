<?php

class Admin_Model_DbTable_LandmarkCategory extends Zend_Db_Table_Abstract
{

    protected $_name = 'landmark_category';


    
    /**
     * 
     * @return type Zend_Db_Table_Rowset_Abstract
     */
    public function getLandmarkCategories(){
        
        return $this->fetchAll();
    }
    
    
    
    /**
     * Returns a key, value pair of 
     * category id as id and name as value
     */
    public function getLandMarkCatsArray(){
       
        $all_cats = array();
        $cats = $this->fetchAll();
        foreach($cats as $c){
            array_push($all_cats, array(
              'key'=> $c->id,
              'value' => $c->name
                ));
        }
        return $all_cats;
    
   }
        
    
    /**
     *
     * @param type $id
     * @return type 
     */
    public function getLandmarkCategory($id){
        
        return $this->fetchRow('id = '. $id)->toArray();
    }
    
    /**
     *
     * @param type $name 
     */
    public function addLandmarkCategory($name){
        
        $data = array (
            'name' => $name
        );
        $this->insert($data);
    }
    
    public function deleteLandmarkCategory($id){
        
        $this->delete('id = '. $id);
    }
    
    public function updateLandmarkCategory($id, $name){
        
        $data = array (
            
            'name' => $name
        );
        
        $this->update($data, 'id ='. $id);
    }
}

