<?php

class Application_Form_Base extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
    }


     /**
      *
      * @return type 
      */
    protected function getCities(){
       $cities = new Admin_Model_CityService();
       return $cities->getAllCities();
    }
    
    /**
     *
     * @return array of all cities
     */
    protected function makeCityArray(){
       $cities_array = array();
       $cities = $this->getCities();
       
       foreach($cities as $city){
          
          array_push($cities_array, array(
              'key'=> $city->id,
              'value' => $city->name . ',  ' . $city->state->abbv
          ));
       }
       return $cities_array;
    }
    
    
    protected function getStates(){
        
        $states = new Admin_Model_DbTable_State();
       return $states->getStates();
    }
    
    protected function makeStatesArray(){
       $states_array = array();
       $states = $this->getStates();
       
       foreach($states as $state){
          
          array_push($states_array, array(
              'key'=> $state->id,
              'value' => $state->name
          ));
       }
       return $states_array;
    }
    
    /**
     * Fetches all the airports currently in the database
     * @return array of airports
     */
     public function getAirports(){
         
        $airport = new Admin_Model_AirportService();
        $all_airports = array();
        $aiports = $airport->getAllAirports();
        foreach($aiports as $a){
            array_push($all_airports, array(
              'key'=> $a->id,
              'value' => $a->name
                ));
        }
        return $all_airports;
    
   }
   
   //gets string version
   public function getStringAirports(){
       
       
   }
    /**
     * get all the trip times
     * @return type Array of tripTimes
     */
    
    protected function getTripTimes(){
        
       return array(
            'anytime' => 'anytime',
            'morning' => 'morning',
            'afternoon' => 'afternoon',
            'evening' => 'evening'
           
        );
    }
    
  }

