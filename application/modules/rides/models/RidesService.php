<?php


/**
 * This Service provides an interface to the rides entity
 */
class Rides_Model_RidesService extends Application_Model_Service
{
    
    protected $ridesToAirport; 
    protected $ridesFromAirport;
    
    protected $airport;
    protected $user;
    protected $OnlyAlnumFilter;
    

    public function __construct($where){
        parent::__construct();
       
      if ($where == "toAirport") {
        $this->ridesToAirport = new \Rideorama\Entity\Ridestoairport();
      }
     else  if ($where == "fromAirport"){
        $this->ridesFromAirport = new \Rideorama\Entity\Ridesfromairport();
    }
    
    $this->airport = new Admin_Model_AirportService();
    $this->user = new Account_Model_UserService();
    $this->OnlyAlnumFilter = new Zend_Filter_Alnum(true);

    }
    
    public function addRide(array $trip_data, $where){
        
        if ($where == "toAirport"){
            
            $this->addRideToAirport($trip_data);
            
        }else if ($where == "fromAirport"){
            
            $this->addRideFromAirport($trip_data);
        }
        
    }
    
    
    public function search(array $search_data, $where){
        
        $data = null;
        if ($where == "toAirport"){
            
         $data =  $this->findRidesToAirport($search_data);
        // var_dump($data);
            
        }else if ($where == "fromAirport"){
            
         $data =   $this->findRidesFromAirport($search_data);
        }
        
        return $data;
    }
    
    /**
     *
     * @param type $search_data
     * @return type Entity collection of Ridetoairport 
     */
    protected function findRidesToAirport($search_data){
    $airport = $search_data['destination'];
    $departure = $search_data['departure'];
    $date = $search_data['trip_date'];
    $time = $search_data['trip_time'];
    
     $sorted_trips = array();
     $hwfar = array();
     
    //This returns rides 
    if ($search_data['trip_time'] == "anytime"){
     
      $rides = $this->findAnytimeRidesToAirport($airport, $date);
     
      foreach ($rides as $r){
          
          $departure = $this->OnlyAlnumFilter->filter($departure);
          $destination = $this->OnlyAlnumFilter->filter($r['pick_up_address']);
          $distance = $this->getTripDistance($departure, $destination);
          $distance = $distance['distance'];
          
       
          array_push($sorted_trips, array(
             
            'key' => $r,
              
            'value'  => $distance
              
          ));
                    
      }
      
      foreach ($sorted_trips as $key => $value) {

            $hwfar[$key] = $value['value'];
    }
      //Perform a ranking based on distance to each of this departure locations using google
    }
    
    array_multisort($hwfar, $sorted_trips);
 //   var_dump($sorted_trips);
    return $sorted_trips;
    }
   
    /**
     * Finds all rides that matches the input airport name and date
     * @param type $airport
     * @param type $date
     * @return type Ridestoairport Entity collection
     */
    private function findAnytimeRidesToAirport($airport, $date){
     
    
     $airport = $this->airport->getAirportByName($airport);
     
     $q = $this->em->createQuery("select u.id, u.pick_up_address, u.number_of_seats, u.num_luggages,
             u.trip_msg, u.departure_time, u.cost, u.luggage_size, p.first_name, p.id as user_id,
              p.last_name
              from Rideorama\Entity\Ridestoairport u JOIN u.publisher 
              p where u.airport = $airport->id and u.departure_date = 
             '$date'");

     //$q = $q->setHint(\Doctrine\ORM\Query::HINT_INCLUDE_META_COLUMNS, true);
    // $q->setHydrationMode(Doctrine_Core::HYDRATE_ARRAY);
      $rides = $q->getResult();

   //   Zend_Debug::dump($query);
       
      //die();
       return $rides;
    }
    
    private function findAfternoonRidesToAirport($search_data){
        
    }
    
    private function findMorningRidesToAirport($search_data){
        
    }
    
    private function findAfernoonRidesToAirport($search_data){
        
    }
    
    
    private function findRidesFromAirport($search_data){
        
    }
    

    /**
     * Posts a trip to the airport
     *@todo : work on calculating trip duration;
     * work on trip_distance
     * work on trip emissions
     * work on trip_time
     * @param type $trip_data 
     */
    private function addRideToAirport($trip_data){
        
       
        $this->ridesToAirport->pick_up_address = $trip_data['departure'];
        $this->ridesToAirport->number_of_seats = $trip_data['num_seats'];
        $this->ridesToAirport->trip_msg = $trip_data['trip_msg'];
        $this->ridesToAirport->publisher = $this->user->getUser(Zend_Auth::getInstance()->getIdentity()->id);
        $this->ridesToAirport->airport  = $this->airport->getAirportByName($trip_data['destination']);
        $this->ridesToAirport->departure_date = new \DateTime(date($trip_data['trip_date']));
        $this->ridesToAirport->departure_time = new DateTime(($trip_data['trip_time']));
        $this->ridesToAirport->num_luggages = $trip_data['luggage'];
        $this->ridesToAirport->luggage_size = $trip_data['luggage_size'];
        $this->ridesToAirport->cost = $trip_data['trip_cost'];
        
        //Get the Distance and duration of this trip
        $departure = $this->OnlyAlnumFilter->filter($trip_data['departure']);
        $destination = $this->OnlyAlnumFilter->filter($trip_data['destination']);
     
        $distanceAndDuration = $this->getTripDistance($departure, $destination);
        $this->ridesToAirport->distance = $distanceAndDuration['distance'];
        $this->ridesToAirport->duration = $distanceAndDuration['duration'];
        
        Zend_Registry::get('doctrine')->getEntityManager()->persist($this->ridesToAirport);
        Zend_Registry::get('doctrine')->getEntityManager()->flush();
                
    }
    
    
    /**
     * Calls the Google maps service to get the distance between two locations.
     * @param type $departure
     * @param type $destination
     * @return type array (Distance, Duration)
     */
    protected function getTripDistance($departure, $destination){
       
        $client = new Zend_Http_Client('http://maps.googleapis.com/maps/api/distancematrix/json');
 
        $departure = urlencode($departure);
        $destination = urlencode($destination);
 
        $client->setParameterGet('sensor', 'false'); // Do we have a GPS sensor? Probably not on most servers.
        $client->setParameterGet('origins', $departure);
        $client->setParameterGet('destinations', $destination);
        $client->setParameterGet('units', 'imperial');
 
        $response = $client->request('GET'); // We must send our parameters in GET mode, not POST
 
        $val = Zend_Http_Response::extractBody($response);
        
        $val = json_decode($val);
        
       // var_dump($val);
        
        $duration = $val->rows[0]->elements[0]->duration->text;
        $distance = $val->rows[0]->elements[0]->distance->text;
        
       
        //
        $data = array(
            "duration" => $duration, 
            "distance" => $distance
            );
        
        
        //print_r($val);
        return  $data;    
    }
    
  
   
    private function addRideFromAirport(){
        
    }
    
    private function deleteRideFromAirport(){
        
    }
    
    private function deleteRideToAirport(){
        
    }
    
    private function updateRideToAirport(){
        
    }
    
    private function updateRideFromAirport(){
        
    }
}

