<?php

class ShowController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
       
        $time = new Zend_Date();
        $time->setTime('17:00:00', 'HH:mm:ss'); 
        $time->addSecond(3950);
      //  echo $time;
        //echo $time->getTimestamp();
      
      // echo date("H:i:s", $time->getTimestamp());
        echo $time->get("H:m:s");
       // $time= $time->get(Zend_Date::TIME_SHORT);
       // echo $time;
    }
    

    public function calcAction(){
        
     
        try {
            
        $client = new Zend_Http_Client('http://maps.googleapis.com/maps/api/distancematrix/json');
 
        $departure = urlencode('Phoenix Sky Harbor Airport');
        $destination = urlencode('Denver International Airport');
 
        $client->setParameterGet('sensor', 'false'); // Do we have a GPS sensor? Probably not on most servers.
        $client->setParameterGet('origins', $departure); // Should now be '1600+Amphitheatre+Parkway,+Mountain+View,+CA'
        $client->setParameterGet('destinations', $destination);
        $client->setParameterGet('units', 'imperial');
 
        $response = $client->request('GET'); // We must send our parameters in GET mode, not POST
        
        
        $val = Zend_Http_Response::extractBody($response);
        
        $val = json_decode($val);
        
        print_r($val);
        
       
//        echo $val->rows[0]->elements[0]->distance->text;
//        echo "<br>";
//        echo $val->rows[0]->elements[0]->duration->text;
        
        
          }catch(Exception $ex){
            
            echo "COuldn't contact server";
        }
         
    }

}

