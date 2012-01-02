<?php

class Requests_Model_RequestService
{

   
    protected $requestsToAirport; 
    protected $requestsFromAirport;
    
    protected $airport;
    protected $user;
    protected $OnlyAlnumFilter;
    
    protected $sorted_trips = array();  //Gets the sorted trips
    protected $hwfar = array();        // Used for sorting the trips in a multi-dimensional array

    public function __construct($where){
        parent::__construct();
       
      if ($where == "toAirport") {
        $this->requestsToAirport = new \Requestorama\Entity\Requeststoairport();
      }
     else  if ($where == "fromAirport"){
        $this->requestsFromAirport = new \Requestorama\Entity\Requestsfromairport();
    }
    
    $this->airport = new Admin_Model_AirportService();
    $this->user = new Account_Model_UserService();
    $this->OnlyAlnumFilter = new Zend_Filter_Alnum(true);

    }
    
    public function addRequest(array $trip_data, $where){
        
        if ($where == "toAirport"){
            
            $this->addRequestToAirport($trip_data);
            
        }else if ($where == "fromAirport"){
            
            $this->addRequestFromAirport($trip_data);
        }
        
    }
    
    
    /**
     * Performs a search that matches a user's criteria
     * @param array $search_data
     * @param type $where
     * @return type Array of trips
     */
    public function search(array $search_data, $where){
        
        $data = null;
        if ($where == "toAirport"){
           
          $data = $this->findrequestsToAirport($search_data);
            
        }else if ($where == "fromAirport"){
            
         $data =   $this->findrequestsFromAirport($search_data);
        }
        
        return $data;
    }
    
    
    protected function findrequestsFromAirport($search_data){
        
        return null;
    }
    /**
     *
     * @param type $search_data
     * @return type Entity collection of Requesttoairport 
     */
    protected function findrequestsToAirport($search_data){
    $airport = $search_data['destination'];
    $departure = $search_data['departure'];
    $date = $search_data['trip_date'];
    $time = $search_data['trip_time'];
    
    //Get the requests
    // Sort requests by closest to my location
    if ($search_data['trip_time'] == "anytime"){
     
      $requests = $this->findAnytimerequestsToAirport($airport, $date);
      $this->sortTripsByDistanceToMyLocation($requests, $departure);


    }else if ($search_data['trip_time'] == "morning"){
      
      $requests =  $this->findAfernoonrequestsToAirport($date, $airport, '100:00:00', "12:00:00");
      $this->sortTripsByDistanceToMyLocation($requests, $departure);
      
    } else if ($search_data['trip_time'] == "afternoon"){
        
      $requests =  $this->findAfernoonrequestsToAirport($date, $airport, '12:00:00', "18:00:00");
      $this->sortTripsByDistanceToMyLocation($requests, $departure);
        
    }else if ($search_data['trip_time'] == "evening"){
        
      $requests =  $this->findEveningrequestsToAirport($date, $airport, '18:00:00', "23:55:59");
      $this->sortTripsByDistanceToMyLocation($requests, $departure);
      
    }else {
       throw new Exception("Choose a valid time range!");
    }
    
    array_multisort($this->hwfar, $this->sorted_trips);
    return $this->sorted_trips;
    }
    
    /**
     * Sorts the trips by their distance from a user's current location
     * @param type $requests
     * @param type $departure 
     */
    private function sortTripsByDistanceToMyLocation($requests, $departure){
        
        
      foreach($requests as $r){
          
          $departure = $this->OnlyAlnumFilter->filter($departure);
          $destination = $this->OnlyAlnumFilter->filter($r['pick_up_address']);
          $distance = $this->getTripDistance($departure, $destination);
          $distance = $distance['distance'];
          $distValue = $distance['distValue'];
       
          array_push($this->sorted_trips, array(
             
            'key' => $r,
              
            'value'  => $distance,
              
            'distValue' => $distValue
              
          ));
                    
      }
      
      foreach ($this->sorted_trips as $key => $value) {

            $this->hwfar[$key] = $value['value'];
            }
    }
    
    protected function sortTripsByTime(){
        
    }
    
    protected function sortTripsByUser(){
        
    }
   
    protected function sortTripsByPrice(){
        
    }
    /**
     * Finds all requests that matches the input airport name and date
     * @param type $airport
     * @param type $date
     * @return type requeststoairport Entity collection
     */
    private function findAnytimerequestsToAirport($airport, $date){
     
    
     $airport = $this->airport->getAirportByName($airport);
     
     $q = $this->em->createQuery("select u.id, u.pick_up_address, u.number_of_seats, u.num_luggages,
             u.trip_msg, u.departure_time, u.cost, u.luggage_size, p.first_name, p.id as user_id,
              p.last_name
              from Requestorama\Entity\requeststoairport u JOIN u.publisher 
              p where u.airport = $airport->id and u.departure_date = 
             '$date'");

      $requests = $q->getResult();

       return $requests;
    }
    

    
    /**
     * Gets all morning requests (12am - 12pm)
     * @param type $date
     * @param type $airport
     * @param type $time1 After what time
     * @param type $time2 Before what time
     * @return type array
     */
    private function findMorningrequestsToAirport($search_data){
        
        return $this->ReturnrequestsToAirport($date, $airport, $time1, $time2);

     }
    
    /**
     * Get all afternoon requests (12pm - 6pm)
     * @param type $date
     * @param type $airport
     * @param type $time1 After what time
     * @param type $time2 Before what time
     * @return type array
     */
    private function findAfernoonrequestsToAirport($date, $airport, $time1, $time2){
        
        return $this->ReturnrequestsToAirport($date, $airport, $time1, $time2);
        
    }
    
    /**
     * Gets all evening requests to the airport (6pm - 12am)
     * @param type $date
     * @param type $airport
     * @param type $time1 After what time
     * @param type $time2 Before what time
     * @return type array
     */
    private function findEveningrequestsToAirport($date, $airport, $time1, $time2){
        
        return $this->ReturnrequestsToAirport($date, $airport, $time1, $time2);
    }
    
    /**
     * Returns all requests to the airport according to the airport
     * @param type $date
     * @param type $airport
     * @param type $time1
     * @param type $time2
     * @return array of all requests to airport 
     */
    private function ReturnrequestsToAirport($date, $airport, $time1, $time2){
       
       return $this->getAirportrequests($date, $airport, $time1, $time2, 'Requestorama\Entity\requeststoairport');
    }
    
    
    /**
     * Returns all requests from the airport
     * @param type $date
     * @param type $airport
     * @param type $time1
     * @param type $time2
     * @return array of all requests from airport
     */
    private function ReturnrequestsFromAirport($date, $airport,$time1, $time2){
        return $this->getAirportrequests($date, $airport, $time1, $time2, 'Requestorama\Entity\requestsfromairport');
    }
    
    
    /**
     * This function returns all requests bound to a landmark
     * @param type $date
     * @param type $airport
     * @param type $time1
     * @param type $time2
     * @param type $targetEntity 
     */
    private function getLandmarkrequests($date, $airport, $time1, $time2, $targetEntity){
        
    }
    /**
     * This function returns Airport bound requests
     * @param type $date
     * @param type $airport
     * @param type $time1
     * @param type $time2
     * @param type $targetEntity
     * @return type 
     */
    private function getAirportrequests($date, $airport, $time1, $time2, $targetEntity){
        
        $airport = $this->airport->getAirportByName($airport);
        
        $q = $this->em->createQuery("select u.id, u.pick_up_address, u.number_of_seats, u.num_luggages,
             u.trip_msg, u.departure_time, u.cost, u.luggage_size, p.first_name, p.id as user_id,
              p.last_name from '$targetEntity' 
             u JOIN u.publisher p where u.airport = $airport->id and u.departure_date = 
             '$date' and u.departure_time > '$time1' and u.departure_time < '$time2'");
        
        $requests = $q->getResult();
        
        return $requests;
    }
    
    

    /**
     * Posts a trip to the airport
     *@todo : work on calculating trip duration;
     * work on trip_distance
     * work on trip emissions
     * work on trip_time
     * @param type $trip_data 
     */
    private function addRequestToAirport($trip_data){
        
       
        $this->requestsToAirport->pick_up_address = $trip_data['departure'];
        $this->requestsToAirport->trip_msg = $trip_data['trip_msg'];
        $this->requestsToAirport->publisher = $this->user->getUser(Zend_Auth::getInstance()->getIdentity()->id);
        $this->requestsToAirport->airport  = $this->airport->getAirportByName($trip_data['destination']);
        $this->requestsToAirport->departure_date = new \DateTime(date($trip_data['trip_date']));
        $this->requestsToAirport->departure_time = new DateTime(($trip_data['trip_time']));
        $this->requestsToAirport->num_luggages = $trip_data['luggage'];
        $this->requestsToAirport->luggage_size = $trip_data['luggage_size'];
        $this->requestsToAirport->cost = $trip_data['trip_cost'];
        
        //Get the Distance and duration of this trip
        $departure = $this->OnlyAlnumFilter->filter($trip_data['departure']);
        $destination = $this->OnlyAlnumFilter->filter($trip_data['destination']);
     
        $distanceAndDuration = $this->getTripDistance($departure, $destination);
        $this->requestsToAirport->distance = $distanceAndDuration['distance'];
        $this->requestsToAirport->duration = $distanceAndDuration['duration'];
        
        Zend_Registry::get('doctrine')->getEntityManager()->persist($this->requestsToAirport);
        Zend_Registry::get('doctrine')->getEntityManager()->flush();
                
    }
    
    /**
     * Function addRequestFromAirport
     * This function adds a Request from airport to any address
     * 
     * @param type $trip_data 
     */
    private function addRequestFromAirport($trip_data){
        
      //  $this->requestsFromAirport->pick_up_address = $trip_data['departure_spot'];
        $this->requestsFromAirport->drop_off_address = $trip_data['destination'];
        $this->requestsFromAirport->request_msg = $trip_data['trip_msg'];
        $this->requestsFromAirport->publisher = $this->user->getUser(Zend_Auth::getInstance()->getIdentity()->id);
        $this->requestsFromAirport->airport  = $this->airport->getAirportByName($trip_data['departure']);
        $this->requestsFromAirport->departure_date = new \DateTime(date($trip_data['trip_date']));
        $this->requestsFromAirport->departure_time = new DateTime(($trip_data['trip_time']));
        $this->requestsFromAirport->num_luggages = $trip_data['luggage'];
        $this->requestsFromAirport->luggage_size = $trip_data['luggage_size'];
        $this->requestsFromAirport->cost = $trip_data['trip_cost'];
        
        //Get the Distance and duration of this trip
        $departure = $this->OnlyAlnumFilter->filter($trip_data['departure']);
        $destination = $this->OnlyAlnumFilter->filter($trip_data['destination']);
     
        $distanceAndDuration = $this->getTripDistance($departure, $destination);
        $this->requestsFromAirport->distance = $distanceAndDuration['distance'];
        $this->requestsFromAirport->duration = $distanceAndDuration['duration'];
        
        Zend_Registry::get('doctrine')->getEntityManager()->persist($this->requestsFromAirport);
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
        $distValue = $val->rows[0]->elements[0]->distance->value;
       
        //
        $data = array(
            "duration" => $duration, 
            "distance" => $distance,
            "distValue" => $distValue
            );
        
        
        //print_r($val);
        return  $data;    
    }
    
  
    private function deleteRequestFromAirport(){
        
    }
    
    private function deleteRequestToAirport(){
        
    }
    
    private function updateRequestToAirport(){
        
    }
    
    private function updateRequestFromAirport(){
        
    }
    
}

