<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Interfaces with the landmarkcateogry model
 * Manages the addition, edit and removal of category of landmarks
 * @author ovo
 */
class Admin_Model_landmarkcategoryService extends Application_Model_Service{
    //put your code here
    
    protected $landmarkcategory;
    
    public function __construct() {
        
        parent::__construct();
        $this->landmarkcategory = new Rideorama\Entity\LandmarkCategory();
    }
    
    public function getLandmarkCategories(){
        
     return $this->getAll('\Rideorama\Entity\LandmarkCategory');

    }
    
    public function add($name){
        
        $this->landmarkcategory->name = $name;
        $this->em->persist($this->landmarkcategory);
        $this->flush();
    }
}

?>
