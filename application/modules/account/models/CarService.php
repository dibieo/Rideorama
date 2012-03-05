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
        $this->car->car_profile_pic = $data['car_profile_pic'];
        $this->car->picture1 = $data['picture1'];
        $this->car->picture2 = $data['picture2'];
        $this->car->user = $user;
        
        $this->em->persist($this->car);
        $this->em->flush();
    }
    
    
 
    
    /**
     * Updates a user's car profile
     * @param Doctrine\User\Entity $user
     * @param array $data 
     */
    public function updateCarProfile($user, array $data){
        
        $user->car->make = $data['make'];
        $user->car->model = $data['model'];

        // If these values were set, update pictures
        // else skip
       if (!is_null($data['car_profile_pic'])){
       $user->car->car_profile_pic =  $data['car_profile_pic'];
       }
       if (!is_null($data['picture1'])){
        $user->car->picture1 = $data['picture1'];
       }
       if (!is_null($data['picture2'])){
        $user->car->picture2 = $data['picture2'];
       }
        $user->car->year = $data['year'];
        $this->em->persist($user);
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

