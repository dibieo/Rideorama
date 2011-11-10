<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AirportTest
 *
 * @author ovo
 */

namespace Rideorama\Entity;
class AirportTest extends \ModelTestCase {
    //put your code here
    
    protected $em;
    
    public function  __construct(){
       
        
        
    }
       public function testCanCreateAirport()
    {
        $this->assertInstanceOf('Rideorama\Entity\Airport', $this->getTestAirport());
    }
    
    

    public function testCanAddAirport(){
        $this->em = $this->doctrineContainer->getEntityManager();
        $airport = $this->getTestAirport();
        $this->em->persist($this->getTestAirport());
        $this->em->flush();
        
        $airports = $this->em->createQuery('select a from Rideorama\Entity\Airport a')->execute();
        $this->assertEquals(1, count($airports));
        
        
        $this->assertEquals('Denver International Airport',$airports[0]->name);
        $this->assertEquals('DEN',$airports[0]->iata); 
    }
      /**
     *
     * @return Airport
     */
    private function getTestAirport()
    {
        $a = new Airport();
        $a->name = "Denver International Airport";
        $a->picture = "picture.jpg";
        $a->city = $this->getTestCity();
        $a->iata = "DEN";
        return $a;
    }
    
    /**
     *
     * @return City 
     */
    private function getTestCity(){
        
        $c = new City();
        $em = $this->doctrineContainer->getEntityManager();
        $em->persist($c);
        $em->flush();
        return $c;
    }
}

?>
