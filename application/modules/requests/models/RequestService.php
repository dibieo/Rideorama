<?php

class Requests_Model_RequestService extends Application_Model_Service
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
        $this->requestsToAirport = new \Rideorama\Entity\Requeststoairport();
      }
     else  if ($where == "fromAirport"){
        $this->requestsFromAirport = new \Rideorama\Entity\Requestsfromairport();
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
     * @param array $search_data search parameters
     * @param string $where Is this to the airport or from the airport 
     * @return Array Array of trips
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
        
      $airport = $search_data['driverdeparture'];
      $destination = $search_data['driverdestination'];
      $date = $search_data['driverdate'];
      $time = $search_data['drivertrip_time'];
    
    //Get the requests
    // Sort requests by closest to my location
    if ($time == "anytime"){
     
      $requests = $this->findAnyTimerequestsFromAirport($airport, $date, "u.drop_off_address", "Rideorama\Entity\Requestsfromairport");
      $results = $this->sortTripsByDistanceToMyLocation($requests, $destination, "fromAirport");
      $this->setSortTripMemberVariables($results);

    }else if ($time == "morning"){
      
      $requests =  $this->findMorningrequestsFromAirport($date, $airport, '00:00:00', "12:00:00");
      $results = $this->sortTripsByDistanceToMyLocation($requests, $destination, "fromAirport");
      $this->setSortTripMemberVariables($results);

      
    } else if ($time == "afternoon"){
        
      $requests =  $this->findAfternoonrequestsFromAirport($date, $airport, '11:59:00', "18:00:00");
      $results = $this->sortTripsByDistanceToMyLocation($requests, $destination, "fromAirport");
      $this->setSortTripMemberVariables($results);

        
    }else if ($time == "evening"){
        
      $requests =  $this->findEveningrequestsFromAirport($date, $airport, '18:00:00', "23:55:59");
      $results = $this->sortTripsByDistanceToMyLocation($requests, $destination, "fromAirport");
      $this->setSortTripMemberVariables($results);

      
    }else {
       throw new Exception("Choose a valid time range!");
    }
    
    array_multisort($this->hwfar, $this->sorted_trips);
    return $this->sorted_trips;
    }
    /**
     * Finds all rides to the airport
     * @param Array $search_data
     * @return Array Entity collection of Requesttoairport 
     */
    protected function findrequestsToAirport($search_data){
    $airport = $search_data['driverdestination'];
    $departure = $search_data['driverdeparture'];
    $date = $search_data['driverdate'];
    $time = $search_data['drivertrip_time'];
    
    //Get the requests
    // Sort requests by closest to my location
    if ($time == "anytime"){
     
      $requests = $this->findAnytimerequestsToAirport($airport, $date, "u.pick_up_address", "Rideorama\Entity\Requeststoairport");
      $results = $this->sortTripsByDistanceToMyLocation($requests, $departure);
      $this->setSortTripMemberVariables($results);

    }else if ($time == "morning"){
      
      $requests =  $this->findMorningrequestsToAirport($date, $airport, '00:00:00', "12:00:00");
      $results = $this->sortTripsByDistanceToMyLocation($requests, $departure);
      $this->setSortTripMemberVariables($results);

      
    } else if ($time == "afternoon"){
        
      $requests =  $this->findAfternoonrequestsToAirport($date, $airport, '12:00:00', "18:00:00");
      $results = $this->sortTripsByDistanceToMyLocation($requests, $departure);
      $this->setSortTripMemberVariables($results);

        
    }else if ($time == "evening"){
        
      $requests =  $this->findEveningrequestsToAirport($date, $airport, '18:00:00', "23:55:59");
      $results = $this->sortTripsByDistanceToMyLocation($requests, $departure);
      $this->setSortTripMemberVariables($results);

      
    }else {
       throw new Exception("Choose a valid time range!");
    }
    
    array_multisort($this->hwfar, $this->sorted_trips);
    return $this->sorted_trips;
    }
    
    /**
     *
     * @param Array This array is returned from the sortTripsByDistanceToMyLocation 
     */
    private function setSortTripMemberVariables($data){
        $this->sorted_trips = $data['sorted_trips'];
        $this->hwfar = $data ['hwfar'];
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
   * @param string $date
   * @param string $dest
   * @param string $targetEntity
   * @return array  all requests to the airport for that data
   */
    private function findAnytimerequestsToAirport($airport, $date, $dest, $targetEntity){
     
    
     return $this->processAnytimeRequests($airport, $date, $dest, $targetEntity);

    }
    
    private function findAnyTimerequestsFromAirport($airport, $date, $dest, $targetEntity){
        
        return $this->processAnytimeRequests($airport, $date, $dest, $targetEntity);
    }
    
    
    /**
     *
     * @param string $airport
     * @param string $date
     * @param string $dest This is either pickup or drop off address
     * @param Doctrine2 $targetEntity
     * @return type 
     */
    private function processAnytimeRequests($airport, $date, $dest, $targetEntity){
            $airport = $this->airport->getAirportByName($airport);
     
     $q = $this->em->createQuery("select u.id, $dest, u.num_luggages,
             u.request_msg, u.departure_time, u.cost, u.duration, u.luggage_size, p.first_name, 
              p.profile_pic, p.id as user_id,
              p.last_name from $targetEntity u JOIN u.publisher 
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
    private function findMorningrequestsToAirport($date, $airport, $time1, $time2){
        
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
    private function findAfternoonrequestsToAirport($date, $airport, $time1, $time2){
        
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
     *
     * @param string $date Date in format YYYY-MM-DD
     * @param string $airport Airport name
     * @param string $time1 start of time range
     * @param string $time2 end of time range
     * @return array All requests from airport that match the criteria 
     */
    private function findMorningrequestsFromAirport($date, $airport, $time1, $time2){
        return $this->ReturnrequestsFromAirport($date, $airport, $time1, $time2);
    }
    
    /**
     *
     * @param string $date Date in format YYYY-MM-DD
     * @param string $airport Airport name
     * @param string $time1 start of time range
     * @param string $time2 end of time range
     * @return array All requests from airport that match the criteria 
     */
    private function findAfternoonrequestsFromAirport($date, $airport, $time1, $time2){
        
        return $this->ReturnrequestsFromAirport($date, $airport, $time1, $time2);
    }
   
    /**
     *
     * @param string $date Date in format YYYY-MM-DD
     * @param string $airport Airport name
     * @param string $time1 start of time range
     * @param string $time2 end of time range
     * @return array All requests from airport that match the criteria 
     */
    private function findEveningrequestsFromAirport($date, $airport, $time1, $time2){
        return $this->ReturnrequestsFromAirport($date, $airport, $time1, $time2);
    }
    /**
     * Returns all requests to the airport according to the airport
     * @param string $date date in format YYYY-MM-DD
     * @param string $airport Airport name
     * @param string $time1 start of time range
     * @param string $time2 end of time range
     * @return array All requests to airport that match the specified parameters
     */
    private function ReturnrequestsToAirport($date, $airport, $time1, $time2){
       
       return $this->getAirportrequests($date, $airport, $time1, $time2, "u.pick_up_address", 'Rideorama\Entity\Requeststoairport');
    }
    
    
    /**
     * Returns all requests from the airport
     * @param string $date date in format YYYY-MM-DD
     * @param string $airport Airport name
     * @param string $time1 start of the time range
     * @param string $time2 end of the time range
     * @return array All requests from the airport that match the specified parameters
     */
    private function ReturnrequestsFromAirport($date, $airport,$time1, $time2){
        return $this->getAirportrequests($date, $airport, $time1, $time2, "u.drop_off_address", 'Rideorama\Entity\Requestsfromairport');
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
     * @param string $date  Date of the trip in YYYY-MM-DD format
     * @param string $airport Name of the airport
     * @param string $time1 start of time range
     * @param string $time2 end of time range
     * @param string $specialField (Pass in drop_off_address if request is from airport or pick_up_address if request is to airport)
     * @param string $targetEntity Full namespace of Doctrine 2 Entity 
     * @return array Rides that match passed parameters 
     */
    private function getAirportrequests($date, $airport, $time1, $time2, $specialField, $targetEntity){
        
        $airport = $this->airport->getAirportByName($airport);
        
        $q = $this->em->createQuery("select u.id, $specialField, u.num_luggages,
             u.request_msg, u.departure_time, u.duration, u.cost, u.luggage_size, p.profile_pic, p.first_name, p.id as user_id,
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
        $this->requestsToAirport->request_msg = $trip_data['trip_msg'];
        $this->requestsToAirport->publisher = $this->user->getUser(Zend_Auth::getInstance()->getIdentity()->id);
        $this->requestsToAirport->airport  = $this->airport->getAirportByName($trip_data['destination']);
        $this->requestsToAirport->departure_date = new DateTime(date($trip_data['trip_date']));
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
        
        $this->addAddressDetails($this->requestToAirport, $trip_data['departure']);

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
        
        $this->addAddressDetails($this->requestFromAirport, $trip_data['destination']);

        Zend_Registry::get('doctrine')->getEntityManager()->persist($this->requestsFromAirport);
        Zend_Registry::get('doctrine')->getEntityManager()->flush();
                
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

