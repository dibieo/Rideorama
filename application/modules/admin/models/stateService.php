<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Admin_Model_stateService extends Application_Model_Service  {
    
    
//    protected $doctrineContainer;
//    protected $em;
      protected $state;
    
    public function __construct(){
        
       parent::__construct();

      $this->state = new \Rideorama\Entity\State();
//        $this->doctrineContainer = \Zend_Registry::get('doctrine');
//        $this->em = $this->doctrineContainer->getEntityManager();
    }
    
    
    public function add($name, $abbv){
        
        $this->state->addState($name, $abbv);
        $this->em->persist($this->state);
        $this->em->flush();
    }
    
    /**
     * This function updates a state in the database
     * @param type $id
     * @param type $name
     * @param type $abbv 
     */
    public function updateState($id, $name, $abbv){
        
        $state = $this->em->find('\Rideorama\Entity\State', $id);
        $state->abbv = $abbv;
        $state->name = $name;
        $this->em->persist($state);
        $this->em->flush();
        
    }
    /**
     * Removes a state from the database.
     * @param type $id 
     */
    public function deleteState($id){
        
      $state = $this->em->find('\Rideorama\Entity\State', $id);
      $this->em->remove($state);
      $this->em->flush();
      
    }
    
    /**
     * Gets all states in the database
     * @return type Entity collection
     */
    public function getAllStates(){
        
        return $this->getAll('\Rideorama\Entity\State');
    }
    
    public function getState($id) {
        
       return $this->em->find('\Rideorama\Entity\State', $id);
    }
}
