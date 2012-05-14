<?php

/**
 * This provides low-level CRUD access to the states table
 */
class Admin_Model_DbTable_State extends Zend_Db_Table_Abstract
{

    protected $_name = 'state';

    protected $_dependentTables= array('Admin_Model_DbTable_City');
    /**
     * Function: getStates
     * @return allStates ordered by name 
     * @todo write unit tests
     */
    public function getStates(){
        
       return $this->fetchAll(null, 'name');
    }
    
    
    /**
     * function getState
     * @param type $id
     * @return Zend_Db_Row(A row containing a particular state information)
     */
    public function getState($id){
        
        return $this->fetchRow('id = '. $id)->toArray();
    }
    /**
     *@todo Write unit tests and check for case where state already exists in the database
     * @param type $name
     * @param type $abbv 
     */
    public function addState($name, $abbv){
       
        //Make sure no other state exists in the database
       
        $data = array(
            "name" => $name,
            "abbv" => $abbv
            );
        $this->insert($data);
    
}
    /**
     *@todo write unit tests
     * @param type $id 
     */
    public function deleteState($id){
        $this->delete('id = '. $id);
    }
    
    /**
     *
     * @param type $id
     * @param type $name
     * @param type $abbv 
     * @todo write the unit tests for this case
     */
    public function updateState($id, $name, $abbv){
        
        $data = array (
            'name' => strtolower($name),
            'abbv' => strtoupper($abbv)
        );
        
        $this->update($data, 'id = '. (int) $id);
    }
}

