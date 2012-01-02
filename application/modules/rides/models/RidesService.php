r<?php


/**
 * This Service provides an interface to the rides entity
 * It handles the posting  searching of rides in the database
 */
class Rides_Model_RidesService extends Application_Model_Service
{
    
    protected $ridesToAirport; 
    protected $ridesFromAirport;
    
    protected $airport;
    protected $user;
    protected $OnlyAlnumFilter;
    
    protected $sorted_trips = array();  //Gets the sorted trips
    protected $hwfar = array();        // Used for sorting the trips in a multi-dimensional array

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
     *
     * @param type $search_data
     * @return type array of rides sorted by those closest to your destination location
     */
    protected function findRidesFromAirport($search_data){
        
    $airport = $search_data['departure'];
    $departure = $search_data['destination'];
    $date = $search_data['trip_date'];
    $time = $search_data['trip_time'];
    
      //Get the rides
    // Sort rides by closest to my location
    if ($search_data['trip_time'] == "anytime"){
     
      $rides = $this->findAnytimeRidesFromAirport($airport, $date);
      $this->sortTripsByDistanceToMyLocation($rides, $departure);


    }else if ($search_data['trip_time'] == "morning"){
      
      $rides =  $this->findAfernoonRidesFromAirport($date, $airport, '100:00:00', "12:00:00");
      $this->sortTripsByDistanceToMyLocation($rides, $departure);
      
    } else if ($search_data['trip_time'] == "afternoon"){
        
      $rides =  $this->findAfternoonRidesFromAirport($date, $airport, '12:00:00', "18:00:00");
      $this->sortTripsByDistanceToMyLocation($rides, $departure);
        
    }else if ($search_data['trip_time'] == "evening"){
        
      $rides =  $this->findEveningRidesFromAirport($date, $airport, '18:00:00', "23:55:59");
      $this->sortTripsByDistanceToMyLocation($rides, $departure);
      
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
      $this->sortTripsByDistanceToMyLocation($rides, $departure);


    }else if ($search_data['trip_time'] == "morning"){
      
      $rides =  $this->findAfernoonRidesToAirport($date, $airport, '100:00:00', "12:00:00");
      $this->sortTripsByDistanceToMyLocation($rides, $departure);
      
    } else if ($search_data['trip_time'] == "afternoon"){
        
      $rides =  $this->findAfternoonRidesToAirport($date, $airport, '12:00:00', "18:00:00");
      $this->sortTripsByDistanceToMyLocation($rides, $departure);
        
    }else if ($search_data['trip_time'] == "evening"){
        
      $rides =  $this->findEveningRidesToAirport($date, $airport, '18:00:00', "23:55:59");
      $this->sortTripsByDistanceToMyLocation($rides, $departure);
      
    }else {
       throw new Exception("Choose a valid time range!");
    }
    
    array_multisort($this->hwfar, $this->sorted_trips);
    return $this->sorted_trips;
    }
    
    /**
     * Sorts the trips by their distance from a user's current location
     * @param type $rides
     * @param type $departure 
     */
    private function sortTripsByDistanceToMyLocation($rides, $departure){
        
        
      foreach($rides as $r){
          
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
    private function findMorningRidesToAirport($search_data){
        
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
     * @param type $time1
     * @param type $time2
     * @return array of all rides to airport 
     */
    private function ReturnRidesToAirport($date, $airport, $time1, $time2){
       
       return $this->getAirportRides($date, $airport, $time1, $time2, 'Rideorama\Entity\Ridestoairport');
    }
    
    
    /**
     * Returns all rides from the airport
     * @param type $date
     * @param type $airport
     * @param type $time1
     * @param type $time2
     * @return array of all rides from airport
     */
    private function ReturnRidesFromAirport($date, $airport,$time1, $time2){
        return $this->getAirportRides($date, $airport, $time1, $time2, 'Rideorama\Entity\Ridesfromairport');
    }
    
    
    protected function findAnytimeRidesFromAirport($airport, $date){
        
        
     $airport = $this->airport->getAirportByName($airport);
     
     $q = $this->em->createQuery("select u.id, u.drop_off_address, u.number_of_seats, u.num_luggages,
             u.trip_msg, u.departure_time, u.cost, u.luggage_size, p.first_name, p.id as user_id,
              p.last_name
              from Rideorama\Entity\Ridesfromairport u JOIN u.publisher 
              p where u.airport = $airport->id and u.departure_date = 
             '$date'");

      $rides = $q->getResult();

       return $rides;
        
    }
    
    protected function findMorningRidesFromAirport(){
        
    }
    
    protected function findAfternoonnRidesFromAirport(){
        
    }
    
    protected function findEveningRidesFromAirport(){
        
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
     * @return type 
     */
    private function getAirportRides($date, $airport, $time1, $time2, $targetEntity){
        
        $airport = $this->airport->getAirportByName($airport);
        
        $q = $this->em->createQuery("select u.id, u.pick_up_address, u.number_of_seats, u.num_luggages,
             u.trip_msg, u.departure_time, u.cost, u.luggage_size, p.first_name, p.id as user_id,
              p.last_name from '$targetEntity' 
             u JOIN u.publisher p where u.airport = $airport->id and u.departure_date = 
             '$date' and u.departure_time > '$time1' and u.departure_time < '$time2'");
        
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

        Zend_Registry::get('doctrine')->getEntityManager()->persist($this->ridesToAirport);
        Zend_Registry::get('doctrine')->getEntityManager()->flush();
                
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
        
        Zend_Registry::get('doctrine')->getEntityManager()->persist($this->ridesFromAirport);
        Zend_Registry::get('doctrine')->getEntityManager()->flush();
                
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
    
    private function updateRideToAirport(){
        
    }
    
    private function updateRideFromAirport(){
        
    }
}

