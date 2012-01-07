<?php

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
      * emailLink: This creates a link for an email to a user.
      * @param type $module
      * @param type $controller
      * @param type $action
      * @param array $param
      * @return string link
      */
     public function emailLink($module, $controller, $action, array $param){
         $key = array_keys($param);
         $value = array_values($param);
         
         $helper = new Zend_View_Helper_Url() ;
         $baseurl = new Zend_View_Helper_ServerUrl();
         $link = $helper->url(array("module"=>"account", "controller"=>"user",
                                            "action"=>"activate", $key[0] => $value[0]));
              
          $link = "http://" .$baseurl->getHost().$link;
        
           return $link;

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
    
    
   
    
    
    
    
}

