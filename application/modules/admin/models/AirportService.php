<?php

class Admin_Model_AirportService extends Application_Model_Service
{

    protected  $airport;
    
    public function __construct() {
        parent::__construct();
        $this->airport = new \Rideorama\Entity\Airport();
    }
    
    /**
     * Adds an airport to the database
     * @param type $name
     * @param type $iata
     * @param type $pic
     * @param type $city 
     */
    public function addAirport($name, $iata, $pic, $city){
        
        $this->airport->name = $name;
        $this->airport->pic = $pic;
        $this->airport->iata = $iata;
        $this->airport->city = $this->getCity($city);
        $this->em->persist($this->airport);
        $this->em->flush();
    }

    /**
     *
     * @param type $id 
     */
    public function deleteAirport($id) {
        
        $airport = $this->getAirport($id);
        $this->em->remove($airport);
        $this->em->flush();
    }

    /**
     * Updates an airport
     * @param type $id
     * @param type $name
     * @param type $iata
     * @param type $pic
     * @param type $city 
     */
    public function updateAirport($id, $name, $iata, $pic, $city){
        
        $airport = $this->getAirport($id);
        $city = $this->getCity($city);
        
        $airport->name = $name;
        $airport->iata = $iata;
        $airport->pic = $pic;
        $airport->city = $city;
        
        $this->em->persist($airport);
        $this->em->flush();
    }

    
 
    
    /**
     * Gets a specific airport instance
     * @param type $id
     * @return type Airport Entity
     */
    public function getAnAirport($id){
        
        return $this->getAirport($id);
    }
    
    public function getAllAirports(){
        
        return $this->getAll('Rideorama\Entity\Airport');
    }
    
     /**
     * Gets an airport instance
     * @param type $id
     * @return type 
     */
    public function getAirport($id){
        
        return $this->em->find('\Rideorama\Entity\Airport', $id);
    }
    
   /**
     * RFinds and returns an airport when given name.s
     * @param type $name
     * @return Doctrine Entity Airport 
     */
    public function getAirportByName($name){
        
     return   $this->em->getRepository('\Rideorama\Entity\Airport')->findOneBy(array("name" =>$name));
    }
    
 
}

