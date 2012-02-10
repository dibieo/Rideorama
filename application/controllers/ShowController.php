<?php

require_once 'lib/AdaptivePayments.php'; //Load Facebook Api

class ShowController extends Zend_Controller_Action
{


    public function init()
    {
        /* Initialize action controller here */
      $this->view->addHelperPath('ZendX/JQuery/View/Helper/', 'ZendX_JQuery_View_Helper');

    }

    public function indexAction()
    {
        print( date("g:i a", strtotime("14:10:30")) );

     //$this->_helper->getHelper('layout')->disableLayout();
    }
    

    public function calcAction(){
        
       
        try {
            
        $client = new Zend_Http_Client('http://maps.googleapis.com/maps/api/distancematrix/json');
 
        $departure = urlencode('1777 Exposition drive Boulder CO');
        $destination = urlencode('Mc Donalds 28th Street Boulder CO');
 
        $client->setParameterGet('sensor', 'false'); // Do we have a GPS sensor? Probably not on most servers.
        $client->setParameterGet('origins', $departure); // Should now be '1600+Amphitheatre+Parkway,+Mountain+View,+CA'
        $client->setParameterGet('destinations', $destination);
        $client->setParameterGet('units', 'imperial');
 
        $response = $client->request('GET'); // We must send our parameters in GET mode, not POST
        
        
        $val = Zend_Http_Response::extractBody($response);
        
        $val = json_decode($val);
        print_r($val);
        echo "We just printed the value \n";
       // print_r($val);
        
        
       
//        echo "<br>";
 //     echo $val->rows[0]->elements[0]->duration->text;
        
        
          }catch(Exception $ex){
            
            echo "COuldn't contact server";
        }
         
    }

}

