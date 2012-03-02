<?php
require_once 'facebook.php'; //Load Facebook Api

class Application_Model_Service
{
    protected $doctrineContainer;
    protected $em;
    protected $mail;
    
    public function __construct(){
        $this->doctrineContainer = Zend_Registry::get('doctrine');
        $this->em = $this->doctrineContainer->getEntityManager();
        $this->mail = new Zend_Mail();
    }
    
    public function __get($property)
    {
        return $this->$property;
    }
    
    public function __set($property,$value)
    {
        $this->$property = $value;
    } 
    
    public function postMessageOnFacebook($msg){
        
      
       
        $facebook = new Facebook(array(
            'appId' => '239308316101537',
            'secret' => 'ce008ac5b02c0c21641a38b6acbd9b2b',
            'cookie' => true,
         ));
        
        try {
        $facebook->api("/me/feed", 'post', array(
              
                    'message' => $msg, 
                    'link'    => 'http://www.rideorama.com',
                    'picture' => '',
                    'name'    => '',
                    'description'=> ''
                    )
                );
                //as $_GET['publish'] is set so remove it by redirecting user to the base url 
            } catch (FacebookApiException $e) {
                echo $e->getMessage();
            }
    }
    
    public function getAll($entity){
        
        return $this->em->getRepository($entity)->findAll();
    }
    
    /**
     * Function: sendEmail.
     * This function is used to send an email
     * @param array $data {includes recipient_email, recipient_name, subject, and body
     */
     public function sendEmail(array $data){
         
       $this->mail->addTo($data['recipient_email'], $data['recipient_name'])
                   ->setSubject($data['subject'])
                   ->setBodyHtml($data['body'])
                   ->send();
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
          $city = $val->results[0]->address_components[1]->short_name;
          $state =$val->results[0]->address_components[3]->long_name;

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
    
    
    
}

