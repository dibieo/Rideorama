<?php

namespace Rideorama\Entity;

/**
 * Stores shared properties between rides to airport and rides from airport
 * 
 * @author ovo
 */
class ridesEntity extends \Rideorama\Entity\rideoramaEntity {
    //put your code here
    
   /**
     *
     * @var float
     * @Column(name="lattitude", type="float", nullable="true")
     * This stores the lattitude of the trips destination or departure
     */
    public $lattitude;
    
       
   /**
     *
     * @var float
     * @Column(name="longitude", type="float", nullable="true")
     * This stores the longitude of the trips destination or departure
     */
    public $longitude;
    
      /**
     *
     * @var string
     * @Column(name="city", type="string", nullable="true")
     * This stores the city of the drop_off or pick_up_address
     */
    public $city;
    
       
      /**
     *
     * @var string
     * @Column(name="state", type="string", nullable="true")
     * This stores the state of the drop_off or pick_up_address
     */
    public $state;
 
    
}

?>
