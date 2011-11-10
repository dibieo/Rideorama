<?php

class Admin_Model_CityService extends Application_Model_Service
{
 
    protected $city;
    
    public function __construct(){
        
        parent::__construct();
        $this->city = new \Rideorama\Entity\City();
        
    }

    
        
    public function addCity($name, $state){
        
        $this->city->name = $name;
        $state = $this->em->find('\Rideorama\Entity\State', $state);
        $this->city->state = $state;
 
        $this->em->persist($this->city);
        $this->em->flush();
    }
    
    /**
     *
     * @param type $id 
     */
    public function deleteCity($id){
        
       $city = $this->getCity($id);
       $this->em->remove($city);
       $this->em->flush();
    }
    
    
    public function updateCity($id, $name, $state){
        
        $state = $this->getState($state);
        $city = $this->getCity($id);
        $city->name = $name;
        $city->state = $state;
        $this->em->persist($city);
        $this->em->flush();
          
    }
    
    
    /**
     *
     * @param type $id
     * @return City Entity 
     */
    private function getCity($id){
        
        return $this->em->find('\Rideorama\Entity\City', $id);
    }
    
    
    public function getACity($id){
        
        return $this->getCity($id);
    }
      /**
     *
     * @param type $id
     * @return State Entity 
     */
    private function getState($id){
       
        return $this->em->find('\Rideorama\Entity\State', $id);
        
    }
    
    /**
     * Gets all Cities in the database
     * @return type Entity collection
     */
    public function getAllCities(){
        
        return $this->getAll('\Rideorama\Entity\City');
    }
    
    public function toArray ()
    {
        return get_object_vars($this);
    }
}

