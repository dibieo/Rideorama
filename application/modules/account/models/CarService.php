<?php

class Account_Model_CarService extends Application_Model_Service
{
    
    /**
     * Stores information about the user's car
     * @var Car Entity 
     */
    private $car;
    private $user;

    public function __construct() {
        parent::__construct();
        $this->car = new \Rideorama\Entity\Car();
        $this->user = new \Rideorama\Entity\User();     
    }
    
    /**
     *
     * @param array $data
     * @param Rideorama\Entity\User $user
     */
    public function setUpCarProfile(array $data, $user){
        
        $this->car->make = $data['make'];
        $this->car->model = $data['model'];
        $this->car->year = $data['year'];
        $this->car->picture1 = $data['car_pic1'];
        $this->car->picture2 = $data['car_pic2'];
        $this->car->user = $user;
        
        $this->em->persist($this->car);
        $this->em->flush();
    }
    
    /**
     * Updates a user's car profile
     * @param array $data 
     */
    public function updateCarProfile(array $data){
        
        $car = $this->em->find('\Rideorama\Entity\Car', $data['id']);
        $car->make = $data['make'];
        $car->model = $data['model'];
        $car->picture1 = $data['picture1'];
        $car->picture2 = $data['picture2'];
        $car->year = $data['year'];
        $this->em->persist($car);
        $this->em->flush();
    }
    
    /**
     * Removes a car from the user's profile
     * @param type $id 
     */
    public function deleteCarProfile($id){
        
      $car = $this->em->find('\Rideorama\Entity\Car', $id);
      $this->em->remove($state);
      $this->em->flush();
    }
    
    
    
}

