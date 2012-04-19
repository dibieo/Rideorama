<?php


/**
 * This Service provides an interface to the rides entity
 * It handles the posting  searching of rides in the database
 */
class Rides_Model_RidesService extends Application_Model_Service
{
    
    protected $ridesToAirport; 
    protected $ridesToAirportBookingManifest; //Stores info of rides booked to the airport
    protected $ridesFromAirport;
    protected $ridesFromAirportBookingManifest; //Stores info of rides booked from the airport
    protected $loggedInUser = null;
    protected $airport;
    protected $user;
    protected $OnlyAlnumFilter;
    
    protected $sorted_trips = array();  //Gets the sorted trips
    protected $hwfar = array();        // Used for sorting the trips in a multi-dimensional array

    public function __construct($where){
        parent::__construct();
       
      if ($where == "toAirport") {
        $this->ridesToAirport = new \Rideorama\Entity\Ridestoairport();
        $this->ridesToAirportBookingManifest = new \Rideorama\Entity\Ridestoairportbookmanifest();
      }
     else  if ($where == "fromAirport"){
        $this->ridesFromAirport = new \Rideorama\Entity\Ridesfromairport();
        $this->ridesFromAirportBookingManifest = new \Rideorama\Entity\Ridesfromairportbookmanifest();
    }
    
    $this->airport = new Admin_Model_AirportService();
    $this->user = new Account_Model_UserService();
    $this->OnlyAlnumFilter = new Zend_Filter_Alnum(true);
    
        if (Zend_Auth::getInstance()->hasIdentity()){
            $this->loggedInUser = Zend_Auth::getInstance()->getIdentity();
        }
    }
    
    /**
     * Adds rides to or from airport to the database
     * @param array $trip_data
     * @param string $where
     * @return integer $last_trip_id The id of the last trip to/from airport inserted 
     */
    public function addRide(array $trip_data, $where){
        
        $last_trip_id = null;
        if ($where == "toAirport"){
            
         $last_trip_id = $this->addRideToAirport($trip_data);
            
        }else if ($where == "fromAirport"){
            
          $last_trip_id =  $this->addRideFromAirport($trip_data);
        }else {
            throw new Exception("$where is an invalid parameter");
        }
        
        return $last_trip_id;
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
           
          $data = $this->findRidesToAirport($search_data);
            
        }else if ($where == "fromAirport"){
            
         $data =   $this->findRidesFromAirport($search_data);
        }
        
        return $data;
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
            
            $data = $this->getTripDetails($params['trip_id'], "Rideorama\Entity\Ridestoairport",
                                           'u.pick_up_address');
        }
        else if ($where == "fromAirport"){
           
            $data = $this->getTripDetails($params['trip_id'], "Rideorama\Entity\Ridesfromairport",
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
        
          $q = $this->em->createQuery("select u.id, $variableAddress, u.number_of_seats, u.num_luggages,
               u.trip_msg, u.city, u.state, u.departure_time, u.emissions, u.arrival_time, u.cost,  u.luggage_size,
               p.email, p.profile_pic, p.first_name, p.profession, p.age, p.id as user_id, p.email,
               p.last_name, p.paypal_email, c.make, c.model, c.year, c.car_profile_pic, c.picture1,c.picture2 
               from Rideorama\Entity\Car c, $targetEntity u JOIN u.publisher 
               p where u.id = :id and c.user = u.publisher")
                    ->setParameters(array('id' => $id));
                
      $trip_details = $q->getResult();

       return $trip_details;
        
    }
    /**
     *
     * @param type $search_data
     * @return type array of rides sorted by those closest to your destination location
     */
    protected function findRidesFromAirport($search_data){
        
    $airport = $search_data['departure'];    
    $destination = $search_data['destination'];
    $departure = $search_data['destination'];
    $date = $search_data['trip_date'];
    $time = $search_data['trip_time'];
    
      //Get the rides
    // Sort rides by closest to my location
    if ($search_data['trip_time'] == "anytime"){
     
      $rides = $this->findAnytimeRidesFromAirport($airport, $date);
      $ride_data = $this->sortTripsByDistanceToMyLocation($rides, $destination, "fromAirport");
      $this->setSortTripMemberVariables($ride_data);

    }else if ($search_data['trip_time'] == "morning"){
      
      $rides =  $this->findMorningRidesFromAirport($date, $airport, '00:00:00', "12:00:00");
      $ride_data = $this->sortTripsByDistanceToMyLocation($rides, $destination, "fromAirport");
      $this->setSortTripMemberVariables($ride_data);
      
    } else if ($search_data['trip_time'] == "afternoon"){
        
      $rides =  $this->findAfternoonRidesFromAirport($date, $airport, '11:59:00', "18:00:00");
      $ride_data = $this->sortTripsByDistanceToMyLocation($rides, $destination, "fromAirport");
      $this->setSortTripMemberVariables($ride_data);
      
    }else if ($search_data['trip_time'] == "evening"){
        
      $rides =  $this->findEveningRidesFromAirport($date, $airport, '18:00:00', "23:55:59");
      $ride_data = $this->sortTripsByDistanceToMyLocation($rides, $destination, "fromAirport");
      $this->setSortTripMemberVariables($ride_data);
      
    }else {
       throw new Exception("Choose a valid time range!");
    }
    
    array_multisort($this->hwfar, $this->sorted_trips);
    return $this->sorted_trips;
        
    }
    /**
     * Gets all rides to the airport
     * @param type $search_data
     * @return type Entity collection of Ridetoairport sorted by closest ride to user
     */
    protected function findRidesToAirport($search_data){
    $airport = $search_data['destination'];
    $departure = $search_data['departure'];
    $date = $search_data['trip_date'];
    $time = $search_data['trip_time'];
    
    //Get the rides
    // Sort rides by closest to my location
    if ($search_data['trip_time'] == "anytime"){
     
      $rides = $this->findAnytimeRidesToAirport($airport, $date);
      $ride_data = $this->sortTripsByDistanceToMyLocation($rides, $departure);
      $this->setSortTripMemberVariables($ride_data);


    }else if ($search_data['trip_time'] == "morning"){
      
      $rides =  $this->findMorningRidesToAirport($date, $airport, '00:00:00', "12:00:00");
      $ride_data = $this->sortTripsByDistanceToMyLocation($rides, $departure);
      $this->setSortTripMemberVariables($ride_data);
      
    } else if ($search_data['trip_time'] == "afternoon"){
        
      $rides =  $this->findAfternoonRidesToAirport($date, $airport, '12:00:00', "18:00:00");
      $ride_data = $this->sortTripsByDistanceToMyLocation($rides, $departure);
      $this->setSortTripMemberVariables($ride_data);
        
    }else if ($search_data['trip_time'] == "evening"){
        
      $rides =  $this->findEveningRidesToAirport($date, $airport, '18:00:00', "23:55:59");
      $ride_data = $this->sortTripsByDistanceToMyLocation($rides, $departure);
      $this->setSortTripMemberVariables($ride_data);
      
    }else {
       throw new Exception("Choose a valid time range!");
    }
    
    array_multisort($this->hwfar, $this->sorted_trips);
    return $this->sorted_trips;
    }
    
    
    protected function sortTripsByTime(){
        
    }
    
    protected function sortTripsByUser(){
        
    }
   
    protected function sortTripsByPrice(){
        
    }
    
     /**
     *
     * @param Array This array is returned from the sortTripsByDistanceToMyLocation 
     */
    private function setSortTripMemberVariables($data){
        $this->sorted_trips = $data['sorted_trips'];
        $this->hwfar = $data ['hwfar'];
    }
    /**
     * Finds all rides that matches the input airport name and date
     * @param type $airport
     * @param type $date
     * @return type Ridestoairport Entity collection
     */
    protected function findAnytimeRidesToAirport($airport, $date){
     
       return $this->findAnytimeRides($airport, $date, "u.pick_up_address", "Rideorama\Entity\Ridestoairport");
    
    
    }
    
   
     /**
     *
     * @param string $airport The full airport name
     * @param string $date The date of the trip
     * @param string $variableAddress Is this a pick up or drop_off address
     * @param string $targetEntity The Full Entity pathname
     * 
     * @return array of rides 
     */
    private function findAnytimeRides($airport, $date, $variableAddress, $targetEntity){
        
         $airport = $this->airport->getAirportByName($airport);
     
       $q = $this->em->createQuery("select u.id, $variableAddress, u.number_of_seats, u.num_luggages,
             u.trip_msg, u.departure_date, u.departure_time,u.city, u.state, u.lattitude, u.longitude,
              u.arrival_time, u.cost, u.luggage_size, p.email, p.paypal_email, p.profile_pic,
              p.first_name, p.id as user_id,
              p.last_name from $targetEntity u JOIN u.publisher 
              p where u.airport = $airport->id and u.departure_date = :date")
                    ->setParameter('date', $date);
                
      $rides = $q->getResult();

       return $rides;
    }

    
    /**
     * Gets all morning rides (12am - 12pm)
     * @param type $date
     * @param type $airport
     * @param type $time1 After what time
     * @param type $time2 Before what time
     * @return type array
     */
    private function findMorningRidesToAirport($date, $airport, $time1, $time2){
        
        return $this->ReturnRidesToAirport($date, $airport, $time1, $time2);

     }
    
    /**
     * Get all afternoon rides (12pm - 6pm)
     * @param type $date
     * @param type $airport
     * @param type $time1 After what time
     * @param type $time2 Before what time
     * @return type array
     */
    private function findAfternoonRidesToAirport($date, $airport, $time1, $time2){
        
        return $this->ReturnRidesToAirport($date, $airport, $time1, $time2);
        
    }
    
    /**
     * Gets all evening rides to the airport (6pm - 12am)
     * @param type $date
     * @param type $airport
     * @param type $time1 After what time
     * @param type $time2 Before what time
     * @return type array
     */
    private function findEveningRidesToAirport($date, $airport, $time1, $time2){
        
        return $this->ReturnRidesToAirport($date, $airport, $time1, $time2);
    }
    
    /**
     * Returns all rides to the airport according to the airport
     * @param type $date
     * @param type $airport
     * @param string $time1
     * @param string $time2
     * @return array of all rides to airport 
     */
    private function ReturnRidesToAirport($date, $airport, $time1, $time2){
       
       return $this->getAirportRides($date, $airport, $time1, $time2, 'Rideorama\Entity\Ridestoairport',  "u.pick_up_address");
    }
    
    
    /**
     * Returns a list of rides from the airport that fall between the range of times
     * defined by time1 and time2
     * @param string $date Departure Date
     * @param string $airport Departure Airport
     * @param string $time1  Start time
     * @param string $time2  ENd time
     * @param string $variableAddress Pass pick up or drop off address depending on whether heading to or leaving airport
     * @return Array Collection of rides
     */
    private function ReturnRidesFromAirport($date, $airport,$time1, $time2, $variableAddress){
        return $this->getAirportRides($date, $airport, $time1, $time2, 'Rideorama\Entity\Ridesfromairport', "u.drop_off_address");
    }
    
    
    protected function findAnytimeRidesFromAirport($airport, $date){
        
     return $this->findAnytimeRides($airport, $date, "u.drop_off_address", "Rideorama\Entity\Ridesfromairport");
    
    }
    
    protected function findMorningRidesFromAirport($date, $airport, $time1, $time2){
        
        return $this->ReturnRidesFromAirport($date, $airport, $time1, $time2, "u.drop_off_address");
    }
    
    protected function findAfternoonRidesFromAirport($date, $airport, $time1, $time2){
        
        return $this->ReturnRidesFromAirport($date, $airport, $time1, $time2, "u.drop_off_address");
    }
    
    protected function findEveningRidesFromAirport($date, $airport, $time1, $time2){
        
        return $this->ReturnRidesFromAirport($date, $airport, $time1, $time2, "u.drop_off_address");
    }
    /**
     * This function returns all rides bound to a landmark
     * @param type $date
     * @param type $airport
     * @param type $time1
     * @param type $time2
     * @param type $targetEntity 
     */
    private function getLandmarkRides($date, $airport, $time1, $time2, $targetEntity){
        
    }
    /**
     * This function returns Airport bound rides
     * @param type $date
     * @param type $airport
     * @param type $time1
     * @param type $time2
     * @param type $targetEntity
     * @return array An array of rides that match the passed in arguments 
     */
    private function getAirportRides($date, $airport, $time1, $time2, $targetEntity, $variableAddress){
        
        $airport = $this->airport->getAirportByName($airport);
        
        $q = $this->em->createQuery("select u.id, $variableAddress, u.number_of_seats, u.num_luggages,
             u.trip_msg, u.departure_date, u.departure_time, u.arrival_time,
              u.city, u.state, u.longitude, u.lattitude,
              u.cost, u.luggage_size, p.paypal_email, p.first_name, p.id as user_id,
              p.email, p.profile_pic, p.last_name from $targetEntity
             u JOIN u.publisher p where u.airport = $airport->id and u.departure_date = 
             :date and u.departure_time >= :time1 and u.departure_time < :time2")
                       ->setParameters(array(
                           'time1'=> $time1,
                           'time2' => $time2,
                           'date' => $date
                       ));
        
        $rides = $q->getResult();
        
        return $rides;
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
        
        //Get the city, lat and long of the address
       
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
        $this->ridesToAirport->arrival_time = new DateTime($this->getArrivalTime($trip_data['trip_date'], $trip_data['trip_time'],$distanceAndDuration['durValue']));
        
        $this->addEmissionInfo($this->ridesToAirport);
        $this->addAddressDetails($this->ridesToAirport, $trip_data['departure']);
        Zend_Registry::get('doctrine')->getEntityManager()->persist($this->ridesToAirport);
        Zend_Registry::get('doctrine')->getEntityManager()->flush();
                
        return $this->ridesToAirport->id;
    }
    
   
    /**
     * Function addRideFromAirport
     * This function adds a ride from airport to any address
     * 
     * @param type $trip_data 
     */
    private function addRideFromAirport($trip_data){
        
      //  $this->ridesFromAirport->pick_up_address = $trip_data['departure_spot'];
        $this->ridesFromAirport->number_of_seats = $trip_data['num_seats'];
        $this->ridesFromAirport->drop_off_address = $trip_data['destination'];
        $this->ridesFromAirport->trip_msg = $trip_data['trip_msg'];
        $this->ridesFromAirport->publisher = $this->user->getUser(Zend_Auth::getInstance()->getIdentity()->id);
        $this->ridesFromAirport->airport  = $this->airport->getAirportByName($trip_data['departure']);
        $this->ridesFromAirport->departure_date = new \DateTime(date($trip_data['trip_date']));
        $this->ridesFromAirport->departure_time = new DateTime(($trip_data['trip_time']));
        $this->ridesFromAirport->num_luggages = $trip_data['luggage'];
        $this->ridesFromAirport->luggage_size = $trip_data['luggage_size'];
        $this->ridesFromAirport->cost = $trip_data['trip_cost'];
        
        //Get the Distance and duration of this trip
        $departure = $this->OnlyAlnumFilter->filter($trip_data['departure']);
        $destination = $this->OnlyAlnumFilter->filter($trip_data['destination']);
     
        $distanceAndDuration = $this->getTripDistance($departure, $destination);
        $this->ridesFromAirport->distance = $distanceAndDuration['distance'];
        $this->ridesFromAirport->duration = $distanceAndDuration['duration'];
        $this->ridesFromAirport->arrival_time = new DateTime($this->getArrivalTime($trip_data['trip_date'], $trip_data['trip_time'],$distanceAndDuration['durValue']));
        
        //Get longitude, lattitude, city and state information
        $this->addAddressDetails($this->ridesFromAirport, $trip_data['destination']);
        $this->addEmissionInfo($this->ridesFromAirport);
     
        Zend_Registry::get('doctrine')->getEntityManager()->persist($this->ridesFromAirport);
        Zend_Registry::get('doctrine')->getEntityManager()->flush();
        
        return $this->ridesFromAirport->id;
                
    }
    
    /**
     * Adds duration to current time to get the
     * return time of the trip
     * @param String $trip_date
     * @param String $departure_time
     * @param String $add_time (in seconds)
     * @return String Arrival time
     */
    private function getArrivalTime($trip_date,$departure_time, $add_time){
        
        $time = new Zend_Date();
        $time->setDate($trip_date, "YYYY-MM-DD");
        $time->setTime($departure_time, "HH:mm:ss");
        $time->addSecond($add_time);
        return $time->get("H:m:s");
    }
   
    private function deleteRideFromAirport(){
        
    }
    
    private function deleteRideToAirport(){
        
    }
    
    /**
     * Updates ride to airport
     * @param type $array  trip array
     */
    public function updateRideToAirport($array){
        
      $this->updateRide('\Rideorama\Entity\Ridestoairport', 'u.pick_up_address', $array);
    }
    
    /**
     * Updates Rides from airport
     * @param type $array Trip Array
     */
    public function updateRideFromAirport($array){
        
        $this->updateRide('\Rideorama\Entity\Ridesfromairport','u.drop_off_address', $array);
    }
    
   /**
    * requestPermissionToBuySeat. This method processes the action of successfully buying a seat.
    * @param string $id
    * @param string $whereTo
    * @param string $driverEmail
    * @param string $passengerEmail 
    */
    public function requestPersmissionToBuySeat(array $array){
     
     try{
     $this->decreaseNumberOfSeats($array['trip_id'], $array['where']);
     $whereTo = $array['where'];
     $array['passengerName'] = $this->returnLoggedInUserName();
     $email = new Application_Model_EmailService();
     $email->sendBookingRequest($array);
    
     if ($whereTo == "toAirport"){
         $trip_id = $array['trip_id'];
         $this->addBookingToManifest($trip_id, $array['publisher_id'], $this->loggedInUser->id, 
                                     '\Rideorama\Entity\Ridestoairport',
                                       $this->ridesToAirportBookingManifest);
     }else if ($whereTo == "fromAirport"){
         $this->addBookingToManifest($array['trip_id'], $array['publisher_id'], $this->loggedInUser->id, 
                                     '\Rideorama\Entity\Ridestoairport',
                                        $this->ridesFromAirportBookingManifest);
     }
     
     }catch(Exception $ex){
         $ex->getMessage();
     }
    }
    
    /**
     * Processes the driver's acceptance of a passenger's request
     * Sends email to the passenger with a link to make payments
     * @param array $array 
     * @return boolean True or false to determine if the operation was completed
     */
    public function acceptRequestToBookSeat(array $array){
        
        try{
            $passenger = new Account_Model_UserService();
            $passenger_id = $passenger->getUserByEmail($array['passengerEmail'])->id;
            
            if (!$this->hasDriverResponded($array['where'], $passenger_id, $array['trip_id'])){
                $email_service = new Application_Model_EmailService();
                $email_service->bookingRequestAccepted($array);
                if ($array['where'] == "toAirport"){
                $this->updateTripManifest('\Rideorama\Entity\Ridestoairportbookmanifest', $passenger_id, $array['trip_id']);
                return true;
                }else{
                    $this->updateTripManifest('\Rideorama\Entity\Ridesfromairportbookmanifest', $passenger_id, $array['trip_id']);
                    return true;
                }
            }else{
                
                throw new Exception("Our records indicate that you have already responded to this request");
                return false;
            }
            
    }catch (Exception $ex){
        print($ex->getMessage());
    }
    }
    /**
     * Frees up the seat so others can book it
     * Sends the requesting party (passenger) a notification of this rejection
     * @param array $array 
     * @return boolean True or False to determine if the action was completed
     */
    public function rejectRequestToBookSeat(array $array){
        
        try{
             $passenger = new Account_Model_UserService();
             $passenger_id = $passenger->getUserByEmail($array['passengerEmail'])->id;

             if (!$this->hasDriverResponded($array['where'], $passenger_id, $array['trip_id'])){
             //If driver has not responded then
            //Free up the seat so others can book it.
            $this->increaseNumberOfSeats($array['trip_id'], $array['where']);
            if ($array['where'] == "toAirport"){
                $this->updateTripManifest("\Rideorama\Entity\Ridestoairportbookmanifest", $passenger_id, $array['trip_id'], "true");
                return true;
                
            }else if ($array['where'] == "fromAirport"){
                
                $this->updateTripManifest("\Rideorama\Entity\Ridesfromairportbookmanifest", $passenger_id, $array['trip_id'], "true");
                return true;
                
            }else{
                
                throw new Exception($array['where'] . " is not a valid parameter");
            }
            $emailService = new Application_Model_EmailService();
            $emailService->bookingRequestRejected($array);
          }else{
              throw new Exception("You have already responded to this request in the past and the passenger
                                   has been notified");
              return false;
          }
       
        }catch(Exception $ex){
        print($ex->getMessage());
    }
    
    }
    
    
    /**
     * Updates the trip manifest to indicate that a driver has already responded to this request
     * @param string $entity Entity to or from airport
     * @param integer $passenger_id The passenger's id
     * @param integer $trip_id The id of the trip
     * @param string $val The status of the booking(optional)
     */
    protected function updateTripManifest($entity, $passenger_id, $trip_id, $val = null){
        
        $q = $this->em->createQuery("UPDATE $entity u SET u.response_status='true',
                                     u.book_status = '$val'
                                    where u.passenger = :passenger_id and
                                   u.trip = :trip_id")
                      ->setParameters(array(
                         'trip_id' => $trip_id,
                         'passenger_id' => $passenger_id
                     ));
        
        $q->execute();
  
    }
    /**
     * Adds a booking request to the booking manifest of the trip 
     * This allows us know if a passenger has booked this seat in the past
     * @param string $trip_id
     * @param string $publisher_id
     * @param string $passenger_id
     */
    private function addBookingToManifest($trip_id, $publisher_id, $passenger_id, $entity, $where){
        $where->trip = $this->em->find($entity, $trip_id);
        $where->passenger = $this->user->getUser($passenger_id);
        $where->publisher = $this->user->getUser($publisher_id);
        $where->date_booked =  new \DateTime(date("Y-m-d H:i:s"));
        $this->em->persist($where);
        //var_dump($this->ridesToAirportBookingManifest);
        $this->em->flush();
    }
    
    
    /**
     *
     * @param string $entity
     * @param string $where
     * @param integer $passenger_id
     * @param integer $trip_id
     * @return boolean True/or false if the driver has responded to this request 
     */
    protected function hasDriverResponded($where, $passenger_id, $trip_id){
     
     $entity = null;
     if ($where == "toAirport"){
      $entity ="\Rideorama\Entity\Ridestoairportbookmanifest";
     }else if($where == "fromAirport"){
      $entity = "\Rideorama\Entity\Ridesfromairportbookmanifest";
     }
  
     $q = $this->em->createQuery("select u.response_status from $entity u where 
                                   u.passenger = :passenger_id and
                                   u.trip = :trip_id")
                     ->setParameters(array(
                         'passenger_id' => $passenger_id,
                         'trip_id' => $trip_id
                     ));
      
      $result = $q->getResult();
      $output = $result[0]['response_status'];
      
      if ($output == null){
          return false;
      }else{
          return true;
      }
      
    } 
   
    protected function sendBuyNotificationForApproval($data){
        
        $this->sendEmail($data);
    }
    
    private function createEmailMessage($senderEmail, $recieverName, $recieverEmail, $messageBody){
        $data = array (
            'recipient_email' => $recieverEmail,
            'recipient_name' => $recieverName,
            'body' => $messageBody,
            'subject' => 'Someone wants a seat on your ride'
        );
        return $data;
    }
    /**
     * Decrease the number of seats by 1 
     * @param string $id  Id of the record either toAirport or fromAirport
     * @param string $whereTo toAirport or from airport
       @throws Exception string, trip not found.
     */
    protected function decreaseNumberOfSeats($id, $whereTo){
        
        if ($whereTo == "toAirport"){
            $this->checkSeatNumberAndThenDecrease("Rideorama\Entity\Ridestoairport", $id);
         
        } else if ($whereTo == "fromAirport"){
            $this->checkSeatNumberAndThenDecrease("Rideorama\Entity\Ridesfromairport", $id);
            
        }else {
            
            throw new Exception("You must indicate whether this trip is to or from an airport");
        }
    }
   
    /**
     * Ch
     * @param string $id trip id
     * @param string $whereTo 
     * @return boolean true or false to determine if the driver has responded
     */
    protected function checkDriverResponseStatus($id, $passenger_id, $whereTo){
        
       $value = null;  
        if ($whereTo == "toAirport"){
         
        $value = $this->responseStatus('\Rideorama\Entity\Ridestoairportbookmanifest', $id, $passenger_id);
            
        }else if ($whereTo == "fromAirport"){
         
        $value = $this->responseStatus('\Rideorama\Entity\Ridestoairportbookmanifest', $id, $passenger_id);
        }else{
            throw new Exception("$whereTo is an invalid expression");
        }
        
        if ($value == null)
            return false;
        else
            return false;
    }
    
    /**
     * Gets the response status from the bookmanifest record
     * @param type $entity
     * @param type $id
     * @param type $passenger_id
     * @return type 
     */
     protected function responseStatus($entity, $id, $passenger_id){
       $q = $this->em->createQuery("select u.response_status from $entity u where 
                                   u.passenger = :passenger_id AND 
                                   u.trip = :id")
                     ->setParameters(array(
                         'trip' => $id,
                         'passenger' => $passenger_id
                     ));
      
      $result = $q->getResult();
      return $result;
        
     }
    
    /**
     * Checks the number of seats on the ride
     * If number of seats is greater than 0 then decrement the number of seats
     * else throw an Exception
     * @param type $entity
     * @param type $id 
     */
    private function checkSeatNumberAndThenDecrease($entity, $id){
        
          $seats = $this->getNumberOfSeats($entity, $id);
            if ($seats > 0){
            $this->subtractASeat($entity, $id);
            }else {
                throw new Exception("There are no more seats avaliable on this ride");
            }
    }
    
    /**
     * This function determines if the user has booked this trip earlier.
     * @param string $trip_id
     * @param string $passenger_id
     * @param string $entity 'To or from airport'
     * @return array 
     */
    private function getUserBooking($trip_id, $passenger_id, $entity){
        
      $q = $this->em->createQuery("select u.id from $entity u where 
                                   u.passenger = :passenger_id and
                                   u.trip = :trip_id")
                     ->setParameters(array(
                         'passenger_id' => $passenger_id,
                         'trip_id' => $trip_id
                     ));
      
      $result = $q->getResult();
      return $result;
        
    }
    
    /**
     * Determines if a user has booked a trip earlier.
     * @param integer $trip_id
     * @param integer $passenger_id
     * @param string $where
     * @return boolean true or false
     */
    public function hasUserBookedThisTrip($trip_id, $passenger_id, $where){
        
        $booking = null;
        if ($where == "toAirport"){
        $booking = $this->getUserBooking($trip_id, $passenger_id, "\Rideorama\Entity\Ridestoairportbookmanifest");
        }else if ($where == "fromAirport"){
         $booking = $this->getUserBooking($trip_id, $passenger_id, "\Rideorama\Entity\Ridesfromairportbookmanifest");
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
     *
     * @param type $entity
     * @param type $id
     * @return integer number of seats available on the ride
     */
    private function getNumberOfSeats($entity, $id){
        
      $q = $this->em->createQuery("select u.number_of_seats from '$entity' 
                                    u where u.id = :id")
                       ->setParameters(array(
                           'id'=> $id
                             ));
        
        $seats = $q->getResult();
        
        $seat_count = $seats[0]['number_of_seats'];
        
        return $seat_count;
    }
    /**
     * Increases the number of seats by 1
     * @param string $id Trip id
     * @param string $whereTo toAirport or fromAirport
     * throws Exception string trip not found
     */
    protected function increaseNumberOfSeats($id, $whereTo){
        
        if ($whereTo == "toAirport"){
            
            $this->addASeat("\Rideorama\Entity\Ridestoairport", $id);
        }
        else if ($whereTo == "fromAirport"){
            
            $this->addASeat("Rideorama\Entity\Ridesfromairport", $id);
        }
        
        else{
           throw new Exception("You must indicate whether this trip is to or from airport");
        }
    }
   
    /**
     *
     * @param type $entity
     * @param type $id
     * @return type 
     */
    private function subtractASeat($entity, $id){
    
      $q = $this->em->createQuery("UPDATE $entity u SET
                                   u.number_of_seats =  u.number_of_seats - 1
                                   where u.id = :id")
                      ->setParameter('id', $id);
         $q->execute();
    }
    
    /**
     *
     * @param type $entity
     * @param type $id
     * @return type 
     */
    private function addASeat($entity, $id){
        $q = $this->em->createQuery("UPDATE $entity u SET u.number_of_seats = u.number_of_seats + 1
                                    where u.id = :id")
                       ->setParameter('id', $id);
        return $q->execute();
    }
    
    /**
     *
     * Updates a ride that has been posted 
     * @param string $entity
     * @param string $variable_address
     * @param array $trip_data 
     */
    protected function updateRide($entity, $variable_address, array $trip_data){
     
     $num_seats = $trip_data['num_seats'];
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
      $arrival_time = $this->getArrivalTime($trip_data['trip_date'], $trip_data['trip_time'],$distanceAndDuration['durValue']);
      
      $address_details = $this->calcLongLat($address);
      $city = $address_details['city'];
      $state = $address_details['state'];
      $lattitude = $address_details['lattitude'];
      $longitude = $address_details['longitude'];
        
//     $variable_address =  ($trip_data['where'] == "toAirport") ? 'pick_up_address' : 'drop_off_address';
     // echo $airport_name;
     $airport_id = $this->airport->getAirportByName($airport_name)->id;
      $query = $this->em->createQuery("UPDATE  $entity u SET u.number_of_seats = '$num_seats', 
                              u.airport = $airport_id, $variable_address = '$address', u.trip_msg = :trip_msg,
                              u.num_luggages = '$num_luggages', u.luggage_size = '$luggage_size',
                              u.city = '$city', u.state = '$state', u.lattitude = '$lattitude', u.longitude = '$longitude',
                              u.departure_date = :departure_date, u.departure_time = :departure_time,
                              u.arrival_time = :arrival_time, u.duration = '$duration', u.distance = '$distance',
                              u.cost = '$cost'where u.id = :id")
                              ->setParameters(array(
                                  'departure_date' => $departure_date,
                                  'departure_time' => $departure_time,
                                  'arrival_time' => $arrival_time,
                                  'trip_msg' => $trip_data['trip_msg'],
                                  'id' => $id
                              ));
      
      $query->execute();
        
      }  
    

}

