<?php

/**
 * This service layer handles requests for requests made 
 * It handles the creation, deletion and updates made to requests
 * It also handles the process of booking, rejecting and authorizing requests for trips
 */
class Requests_Model_RequestService extends Application_Model_Service
{

   
    protected $requestsToAirport; 
    protected $requestsFromAirport;
    protected $requestsToAirportBookingManifest;
    protected $requestsFromAirportBookingManifest;
    
    protected $airport;
    protected $user;
    protected $OnlyAlnumFilter;
    
    protected $sorted_trips = array();  //Gets the sorted trips
    protected $hwfar = array();        // Used for sorting the trips in a multi-dimensional array

    public function __construct($where){
        parent::__construct();
       
      if ($where == "toAirport") {
        $this->requestsToAirport = new \Rideorama\Entity\Requeststoairport();
        $this->requestsToAirportBookingManifest = new \Rideorama\Entity\Requeststoairportbookmanifest();

      }
     else  if ($where == "fromAirport"){
        $this->requestsFromAirport = new \Rideorama\Entity\Requestsfromairport();
        $this->requestsFromAirportBookingManifest = new \Rideorama\Entity\Requestsfromairportbookmanifest();
    }
    
    $this->airport = new Admin_Model_AirportService();
    $this->user = new Account_Model_UserService();
    $this->OnlyAlnumFilter = new Zend_Filter_Alnum(true);

    }
    
    public function addRequest(array $trip_data, $where){
        
        $last_request_id = null;
        if ($where == "toAirport"){
            
        $last_request_id = $this->addRequestToAirport($trip_data);
            
        }else if ($where == "fromAirport"){
            
         $last_request_id = $this->addRequestFromAirport($trip_data);
        }
        
      return $last_request_id;
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
        
        //print_r($data);
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
             u.request_msg, u.departure_date, u.departure_time, u.city, u.state, u.lattitude, u.longitude,
             u.cost, u.duration, u.request_open, u.luggage_size, p.first_name, 
              p.profile_pic, p.id as user_id, p.email,
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
     * tripdetails This function returns details regarding a particular trip.
     * @param array $params containing trip id and fromAirport || toAirport locator
     * @return array containing the driver, driver's car and additional info on the trip
     */
    public function tripdetails(array $params){
        $data = null;
        $where = $params['where'];
        if ($where == "toAirport"){
            
            $data = $this->getTripDetails($params['trip_id'], "Rideorama\Entity\Requeststoairport",
                                           'u.pick_up_address');
        }
        else if ($where == "fromAirport"){
           
            $data = $this->getTripDetails($params['trip_id'], "Rideorama\Entity\Requestsfromairport",
                                           'u.drop_off_address');
        }else{
            throw new Exception("$where is not a valid argument");
        }
       
       return $data;
    }
    
    /**
     * getTripDetails
     * @param integer $id
     * @param string $targetEntity Pass in the RidestoAirport or RidesfromAirport Doctrine entity
     * @param string $variableAddress This is either the pick_up or drop_off_address
     * @return array of trip details that match the input params 
     */
    private function getTripDetails($id, $targetEntity, $variableAddress){
        
      $q = $this->em->createQuery("select u.id, $variableAddress, u.num_luggages,
               u.request_msg, u.departure_time, u.duration, u.emissions, u.cost, u.request_open, u.luggage_size,
               p.email, p.profile_pic, p.first_name, p.profession, p.age, p.id as user_id,
               p.last_name from $targetEntity u JOIN u.publisher 
               p where u.id = :id")
                    ->setParameters(array('id' => $id));
                
      $trip_details = $q->getResult();

       return $trip_details;
        
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
             u.request_msg, u.departure_date, u.departure_time, u.request_open, u.duration, u.cost, u.city, u.state, u.lattitude, u.longitude,
              u.luggage_size,u.emissions, p.profile_pic, p.first_name, p.email, p.id as user_id,
              p.last_name from '$targetEntity' 
             u JOIN u.publisher p where u.airport = $airport->id and u.departure_date = 
             '$date' and u.departure_time >= '$time1' and u.departure_time <= '$time2'");
        
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
        
        $this->addAddressDetails($this->requestsToAirport, $trip_data['departure']);
        $this->addEmissionInfo($this->requestsToAirport);


        Zend_Registry::get('doctrine')->getEntityManager()->persist($this->requestsToAirport);
        Zend_Registry::get('doctrine')->getEntityManager()->flush();
                
        return $this->requestsToAirport->id;
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
        $this->requestsFromAirport->departure_date = new DateTime(date($trip_data['trip_date']));
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
        
        $this->addAddressDetails($this->requestsFromAirport, $trip_data['destination']);
         $this->addEmissionInfo($this->requestsFromAirport);


        Zend_Registry::get('doctrine')->getEntityManager()->persist($this->requestsFromAirport);
        Zend_Registry::get('doctrine')->getEntityManager()->flush();
                
        return $this->requestsFromAirport->id;
    }
    
    
   /**
     * This function determines if the driver has responded to this passenger request.
     * @param string $trip_id
     * @param string $driver_id
     * @param string $entity 'To or from airport'
     * @return array 
     */
    private function getUserBooking($trip_id, $driver_id, $entity){
        
      $q = $this->em->createQuery("select u.id from $entity u where 
                                   u.driver = :driver_id and
                                   u.trip = :trip_id")
                     ->setParameters(array(
                         'driver_id' => $driver_id,
                         'trip_id' => $trip_id
                     ));
      
      $result = $q->getResult();
      return $result;
        
    }
    
     /**
     * Determines if a driver has responded to ride request.
     * @param integer $trip_id
     * @param integer $driver_id
     * @param string $where
     * @return boolean true or false
     */
    public function hasUserBookedThisTrip($trip_id, $driver_id, $where){
        
        $booking = null;
        if ($where == "toAirport"){
        $booking = $this->getUserBooking($trip_id, $driver_id, "\Rideorama\Entity\Requeststoairportbookmanifest");
        }else if ($where == "fromAirport"){
         $booking = $this->getUserBooking($trip_id, $driver_id, "\Rideorama\Entity\Requestsfromairportbookmanifest");
        }else {
           throw new Exception($where . " is an invalid input parameter");
        }

        //$return = null;
        //print $booking;
        if (empty($booking)){
            $return = false;
        }else{
          $return =   true;
        }
        
        return $return;
    }
    
    
     /**
     * Adds a offer request to the booking manifest of the request 
     * This allows us know if a driver has offered this passenger a ride in the past
     * @param string $request_id
     * @param string $publisher_id
     * @param string $passenger_id
     */
    private function addBookingToManifest($request_id, $publisher_id, $driver_id, $entity, $where){
        $where->trip = $this->em->find($entity, $request_id);
        $where->publisher = $this->user->getUser($publisher_id);
        $where->driver = $this->user->getUser($driver_id);
        $where->date_booked =  new \DateTime(date("Y-m-d H:i:s"));
        $this->em->persist($where);
        //var_dump($this->ridesToAirportBookingManifest);
        $this->em->flush();
    }
    
    
   /**
    * requestPermissionToOfferRide. This method processes the action of successfully offering a ride.
    * @param array requestArray 
    */
    public function requestPersmissionToOfferRide (array $array){
     
     try{
     $whereTo = $array['where'];
    // $array['driverName'] = $this->returnLoggedInUserName();
     $email = new Application_Model_EmailService();
     $email->sendOfferRequest($array);
    
     if ($whereTo == "toAirport"){
        // $trip_id = $array['trip_id'];
        echo "publisher id: " . $array['publisher_id'];
         $this->addBookingToManifest($array['trip_id'], $array['publisher_id'], $this->loggedInUser->id, 
                                     '\Rideorama\Entity\Requeststoairport',
                                       $this->requestsToAirportBookingManifest);
         $this->setOfferStatus($array['trip_id'], "toAirport", "closed");
     }elseif ($whereTo == "fromAirport"){
         $this->addBookingToManifest($array['trip_id'], $array['publisher_id'], $this->loggedInUser->id, 
                                     '\Rideorama\Entity\Requestsfromairport',
                                        $this->requestsFromAirportBookingManifest);
         $this->setOfferStatus($array['trip_id'], "fromAirport", "closed");
     }
     
     }catch(Exception $ex){
         $ex->getMessage();
     }
    }
    
     /**
     * Frees up a request offer so other drivers can respond
     * Sends the requesting party (driver) a notification of this rejection
     * @param array $array params needed to complete this operation
     */
    public function rejectRequestToOfferRide(array $array){
        
    try{
           $user = new Account_Model_UserService();
           $driver_id = $user->getUserByEmail($array['driverEmail']);
           $entity = ($array['where'] ==  "toAirport" ? "\Rideorama\Entity\Requeststoairportbookmainfest" : "\Rideorama\Entity\Requestsfromairportbookmanifest");
           
           if (!$this->hasRequestBeenRespondedTo($entity, $array['trip_id'], $driver_id)){
          //Free up the seat so others can respond to it.
            $this->setOfferStatus($array['trip_id'], $array['where'], null);
            $emailService = new Application_Model_EmailService();
            $emailService->rejectOfferRequest($array);
            $this->updateRequestManifest($entity, $array['trip_id'], $driver_id);
            return true;
       }else{
            throw new Exception("Our records indicate that you've already responded to this driver.
                                    Please wait for a response back");
               return false;
           }
    }catch(Exception $ex){
        print($ex->getMessage());
    }
    }
    
    /**
     * Accepts a driver's ride offer 
     * @param array $array
     * @return $boolean true or false depending on whether the action completed successfully
     */
    public function acceptRequestToOfferRide(array $array){
      
       try{
           $driver_id = $user->getUserByEmail($array['driverEmail']);
           $entity = ($array['where'] ==  "toAirport" ? "\Rideorama\Entity\Requeststoairportbookmainfest" : "\Rideorama\Entity\Requestsfromairportbookmanifest");
           
           if (!$this->hasRequestBeenRespondedTo($entity, $array['trip_id'], $driver_id)){
            $emailService = new Application_Model_EmailService();
            $emailService->acceptOfferRequest($array);
            $user = new Account_Model_UserService();
            if ($array['where'] == "toAirport"){
            $this->updateRequestManifest('\Rideorama\Entity\Requeststoairportbookmanifest', $array['trip_id'], $driver_id);
            return true;
            }else if ($array['where'] == "fromAirport"){
             $this->updateRequestManifest('\Rideorama\Entity\Requestsfromairportbookmanifest', $array['trip_id'], $driver_id);
            return true;
            }
           }else{
               throw new Exception("Our records indicate that you've already responded to this driver.
                                    Please wait for a response back");
               return false;
           }
       }catch(Exception $ex){
        print($ex->getMessage());
     }
    }
    
    /**
     * Updates the request manifest for a particular trip request
     * If the driver's request has been rejected. request open is set to responded
     * Likewise if the request has been accepted. request open is also set to responded
     * @param type $entity
     * @param type $trip_id
     * @param type $driver_id
     */
    protected function updateRequestManifest($entity,$trip_id, $driver_id){
        
      $q = $this->em->createQuery("UPDATE $entity u SET u.response_status = 'responded'
                                    where u.trip = :trip_id AND u.driver = :driver_id")
                      ->setParameters(array('trip_id' => $trip_id,
                                            'driver_id' => $driver_id));
      $q->execute();
        
    }
    
    /**
     * Returns whether a response has been given to this request in the request manifest
     * @param type $entity
     * @param type $trip_id
     * @param type $driver_id
     * @return type String
     */
    protected function hasRequestBeenRespondedTo($entity, $trip_id, $driver_id){
        
     $q = $this->em->createQuery("select u.response_status from $entity u where 
                                   u.driver = :driver_id and
                                   u.trip = :trip_id")
                     ->setParameters(array(
                         'driver_id' => $driver_id,
                         'trip_id' => $trip_id
                     ));
      
      $result = $q->getResult();
      $output =$result[0]['response_status'];
      
      if ($output == null){
          return false;
      }else if ($output == "responded"){
          return true;
      }
    }
    
    /**
     *
     * @param integer $id Id of the request
     * @param string $where toAirport of fromAirport
     * @param string $status 'True or False'
     */
    private function setOfferStatus($id, $where, $status){
     
    if ($where == "toAirport"){
        
        $this->updateOfferStatus($id, '\Rideorama\Entity\Requeststoairport', $status);
        
    }else if ( $where == "fromAirport"){
        
        $this->updateOfferStatus($id, '\Rideorama\Entity\Requestsfromairport', $status);
        
    }else {
        throw new Exception("$where is an invalid parameter");
    }
    
    }
    
    /**
     * Updates the status of a ride request 
     * @param type $id
     * @param type $entity
     * @return type 
     */
    private function updateOfferStatus($id, $entity, $status){
     
      $q = $this->em->createQuery("UPDATE $entity u SET u.request_open = '$status'
                                    where u.id = :id")
                      ->setParameter('id', $id);
      $q->execute();
        
    }
    
    private function deleteRequestFromAirport(){
        
    }
    
    private function deleteRequestToAirport(){
        
    }
    
    public function updateRequestToAirport(array $trip_data){
        
     $this->updateRequest('\Rideorama\Entity\Requeststoairport', 'u.pick_up_address', $trip_data);
    }
    
    public function updateRequestFromAirport(array $trip_data){
        
     $this->updateRequest('\Rideorama\Entity\Requestsfromairport', 'u.drop_off_address', $trip_data);
    }
    
    
    /**
     *
     * Updates a request that has been posted 
     * @param string $entity
     * @param string $variable_address
     * @param array $trip_data 
     */
    protected function updateRequest($entity, $variable_address, array $trip_data){
     
     $address = null;
     if ($trip_data['where'] == "toAirport"){
     $address = $this->OnlyAlnumFilter->filter($trip_data['departure']);
     }else if ($trip_data['where'] == "fromAirport"){
      $address = $this->OnlyAlnumFilter->filter($trip_data['destination']);   
     }
     $num_luggages = $trip_data['luggage'];
     $luggage_size = $trip_data['luggage_size'];
     $departure_date = $trip_data['trip_date'];
     $departure_time = $trip_data['trip_time'];
     $cost = $trip_data['trip_cost'];
     $id = $trip_data['trip_id'];
     $airport_name =  ($trip_data['where'] == "toAirport") ? $trip_data['destination'] : $trip_data['departure'];

     
      $distanceAndDuration = $this->getTripDistance($address, $airport_name);
      $distance = $distanceAndDuration['distance'];
      $duration = $distanceAndDuration['duration'];
     
      $address_details = $this->calcLongLat($address);
      $city = $address_details['city'];
      $state = $address_details['state'];
      $lattitude = $address_details['lattitude'];
      $longitude = $address_details['longitude'];

     $airport_id = $this->airport->getAirportByName($airport_name)->id;
      $query = $this->em->createQuery("UPDATE  $entity u SET 
                              u.airport = $airport_id, $variable_address = '$address',
                              u.num_luggages = '$num_luggages', u.luggage_size = '$luggage_size',
                              u.departure_date = :departure_date, u.request_msg = :trip_msg, 
                              u.departure_time = :departure_time, u.duration = '$duration',
                               u.distance = '$distance', u.city = '$city', u.state = '$state',
                               u.lattitude = '$lattitude', u.longitude = '$longitude',
                              u.cost = '$cost'where u.id = :id")
                              ->setParameters(array(
                                  'departure_date' => $departure_date,
                                  'departure_time' => $departure_time,
                                  'trip_msg'=> $trip_data['trip_msg'],
                                  'id' => $id
                              ));
      
      $query->execute();
        
      }  
}

