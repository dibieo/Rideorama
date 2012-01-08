<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Rides_Models_ridesServiceTest
 *
 * @author ovo
 */
class Rides_Models_ridesServiceTest extends \ModelTestCase {
    //put your code here
    
    
    public function testConstructorReturnsCorrectRideObject(){
        $ride = $this->getRideToAirportObject();
        $this->assertInstanceOf('\Rideorama\Entity\Ridestoairport', $ride->ridesToAirport);
        $ride1 = $this->getRideFromAirportObject();
        $this->assertInstanceOf('\Rideorama\Entity\Ridesfromairport', $ride1->ridesFromAirport);
   
    }
    
    public function testWrongConstructorArgumentFailsToReturnRideObject(){
        
        $ride = new Rides_Model_RidesService("interesting");
        $this->assertEquals(null, $ride->ridesToAirport);
        $this->assertEquals(null, $ride->ridesFromAirport);
    }
    
    /**
     * @todo WORK ON THIS FUNCTION
     */
    public function testCanPostRideToAirport(){
       
      
       $ride = $this->getRideToAirportObject();
       $ride->addRide($this->getAirportData(), "toAirport");
       
       $rides_to_airport = $this->doctrineContainer->getEntityManager()->createQuery('select r from Rideorama\Entity\Ridestoairport r')->execute();
       $this->assertEquals(1, count($rides_to_airport));
    
    }
    
    private function getRideToAirportObject(){
        
        return new Rides_Model_RidesService("toAirport");
    }
    
    private  function getRideFromAirportObject() {
        
        return new Rides_Model_RidesService("fromAirport");
    }
    
    private function getAirportData(){
        
        Zend_Auth::getInstance()->getInstance()->id = 1;
        $trip_data = array(
            "departure" => "1777 Exposition Drive Boulder CO",
            "destination" => $this->getTestAirport(),
            "num_seats" => 3,
            "luggage" => 2,
            "luggage_size"=> "medium",
            "trip_cost" => "20",
            "trip_msg" => "Test ride",
            "trip_date" => "2011-11-19",
            "trip_time" => "11:00::00",
            "trip_msg" => "This trip leaves at 3:00pm prompt"
            
            );
        
        return $trip_data;
    }
    
     private function loginUser($email, $passwd, $shortLeague)
    {
        $authParams = array(
            'email' => $email,
            'password' => $passwd
        );
        $adapter = new Xmlteam_Auth($authParams);
        $auth = Zend_Auth::getInstance();
        $result = $auth->authenticate($adapter);
        $this->assertTrue($auth->hasIdentity());
    }
}

?>
