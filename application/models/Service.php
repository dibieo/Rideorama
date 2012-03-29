<?php

class Application_Model_Service
{
    protected $doctrineContainer;
    protected $em;
    protected $mail;
    protected $loggedInUser;
    
    public function __construct(){
        $this->doctrineContainer = Zend_Registry::get('doctrine');
        $this->em = $this->doctrineContainer->getEntityManager();
        $this->mail = new Zend_Mail();
        $this->loggedInUser = Zend_Auth::getInstance()->getIdentity();
    }
    
    public function __get($property)
    {
        return $this->$property;
    }
    
    public function __set($property,$value)
    {
        $this->$property = $value;
    } 
    

    
    public function getAll($entity){
        
        return $this->em->getRepository($entity)->findAll();
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
//        if (is_null($val)){
//            echo "An error occured with the webservice call";
//        }else{
//           echo "Good process the data"; 
//        }
//        
        $val = json_decode($val);
        
       // var_dump($val);
    
        
        
        $duration = $val->rows[0]->elements[0]->duration->text;
        $distance = $val->rows[0]->elements[0]->distance->text;
        $distValue = $val->rows[0]->elements[0]->distance->value;
        $durValue = $val->rows[0]->elements[0]->duration->value;
       
        //
        $data = array(
            "duration" => $duration, 
            "distance" => $distance,
            "distValue" => $distValue,
            "durValue" => $durValue
            );
        
        
        //print_r($val);
        return  $data;    
    }
    
    
    public function sortTripByParam($requests, $param = null){
        
        $cost = array();
        if ($param == "cost"){
        foreach ($requests as $r){
           array_push($cost, $r['key']['cost']); 
        }
        }
        array_multisort($cost, SORT_ASC, $requests);
        
        return $requests;
    }
    
    
   /**
    *
    * @param array $requests 
    * @param string $departure
    * @param string $where toAirport or fromAirport used to determine field to sort on your address
    * @return array Rides sorted by closest to your departure or destination (depends on whether you are heading to or leaving the airport)
    */
    protected function sortTripsByDistanceToMyLocation($requests, $departure, $where = null){
        
      $hwfar = array();
      $sorted_trips = array();
      
      foreach($requests as $r){
          
          $departure = $this->OnlyAlnumFilter->filter($departure);
         if ($where == "fromAirport"){
          $destination = $this->OnlyAlnumFilter->filter($r['drop_off_address']);
          }else{
         $destination = $this->OnlyAlnumFilter->filter($r['pick_up_address']); 
          }
          $distance_data = $this->getTripDistance($departure, $destination);
          $distance = $distance_data['distance'];
          $distValue = $distance_data['distValue'];
       
          array_push($sorted_trips, array(
             
            'key' => $r,
              
            'value'  => $distance,
              
            'distValue' => $distValue
              
          ));
                    
      }
      
     // print_r($sorted_trips);
     
      foreach ($sorted_trips as $key => $value) {

            $hwfar[$key] = $value['distValue'];
            }
            
      $data = array();
      $data['hwfar'] = $hwfar;
      $data['sorted_trips'] = $sorted_trips;
      return $data;       
    }
    
    /**
     * Compute the longitude, latitude, city and state of input address
     * @param string $address The input address
     * @return array This array contains the longitude, latitude, city and state of the address
     */
    public function calcLongLat($address){
        $data = null;
        try {
            
        $client = new Zend_Http_Client('http://maps.googleapis.com/maps/api/geocode/json');
 
        $address = urlencode($address);
 
        $client->setParameterGet('sensor', 'false'); 
        $client->setParameterGet('address', $address);
 
        $response = $client->request('GET'); // We must send our parameters in GET mode, not POST
        
        
        $val = Zend_Http_Response::extractBody($response);
        
        $val = json_decode($val);
  
          $lat = $val->results[0]->geometry->location->lat;
          $lng = $val->results[0]->geometry->location->lng;
          $city = $val->results[0]->address_components[3]->short_name;
          $state =$val->results[0]->address_components[4]->long_name;

        $data = array(
            'lattitude' => $lat,
            'longitude' => $lng,
            'city' => $city,
            'state' => $state
            
        );
        
        
          }catch(Exception $ex){
            
            echo "COuldn't contact server";
        }
       return $data;

         
    
    }
    
     /**
     * DRY function that adds long, lat, city, and state
     * @param Rideorama Entity $entity
     * @param strings $address 
     */
    public function addAddressDetails($entity, $address){
      
        $address_details = $this->calcLongLat($address);
        //Store city, long and latitude
        $entity->city = $address_details['city'];
        $entity->lattitude = $address_details['lattitude'];
        $entity->longitude = $address_details['longitude'];
        $entity->state = $address_details['state'];
        
    }
    
    
    /**
     * Returns the name of the logged in user
     * @return string 
     */
     public function returnLoggedInUserName(){
        
        $userName = $this->loggedInUser->first_name . " " . $this->loggedInUser->last_name;
        return $userName;      
    }
    
    /**
     * Uses the TinyURL service to return a shortened URL to the user
     * @param string Input URL
     * @return string The shortened URL
     */
    public function shortenURL($url){
        
         $url_shortner = new Zend_Service_ShortUrl_TinyUrlCom();
         return $url_shortner->shorten($url);
        
    }
   
    /**
     * Gets a collection of rides and requests
     * that are to be displayed on the homepage ticker
     */
    public function getHomepageTickerRides(){
        $all_results = array();
        $all_results["toAirport"] = $this->getTenAirportRides('\Rideorama\Entity\Ridestoairport');
        $all_results["fromAirport"] = $this->getTenAirportRides('\Rideorama\Entity\Ridesfromairport');
        return $all_results;
    }
    
    
    /**
     * Gets the rides
     * @param type toAirport or FromAirport Entity
     * @return Array 
     */
    private function getTenAirportRides($entity){
        
       $curr_date = date("Y-m-d");
        //print $curr_date;
        
        $q = $this->em->createQuery("select u.id, p.profile_pic, p.first_name, 
                                      u.departure_date,  a.iata as airport_abbv,
                                      a.name as airport_name,
                                      u.number_of_seats, u.cost, u.city, u.departure_time from
                                     '$entity' u JOIN u.publisher p
                                      JOIN u.airport a
                                      where u.departure_date > '$curr_date'")
                                    ->setMaxResults(10);
       
        $result = $q->getResult();
        return $result;
    }
    
    /**
     * Once, a passenger completes payment via paypal, add the driver and passenger
     * to the Notification entity model
     * This allows a cronjob to send out notifications later on
     * @param array $array Trip params 
     */
    public function addTripNotification(array $array){
        
        //print_r($array);
        $date = str_replace("-", "/", $array['trip_date']);
        
        $notification = new \Rideorama\Entity\Notifications();
        $notification->driver_name = $array['driverName'];
        $notification->driver_email = $array['driverEmail'];
        $notification->passenger_name = $array['passengerName'];
        $notification->passenger_email = $array['passengerEmail'];
        $notification->trip_date = new DateTime($date);
        
        $this->em->persist($notification);
        $this->em->flush();
    }
    
       /**
     *Adds Emissions information to the trip or request
     * @param type $entity 
     */
    public function addEmissionInfo($entity){
        
        $emissions = round((1/20 ) * (intval($entity->distance) * 8.871));
        $entity->emissions = $emissions;
      
    }
}

