<?php

require_once 'lib/AdaptivePayments.php'; //Load Facebook Api
require_once 'facebook.php'; //Load Facebook Api


class ShowController extends Zend_Controller_Action
{


    public function init()
    {
        /* Initialize action controller here */
      $this->view->addHelperPath('ZendX/JQuery/View/Helper/', 'ZendX_JQuery_View_Helper');

    }

    public function indexAction()
    {
     //  print( date("g:i a", strtotime("14:10:30")) );
     //   $time = new \DateTime(date("13-12-2012"));
    
     //$this->_helper->getHelper('layout')->disableLayout();
        $date = '03/25/2012'; 
        echo date('Y-m-d');
    }
    

    public function calcAction(){
        
//        $fb = new Facebook(array(
//            'appId' => '239308316101537',
//            'secret' => 'ce008ac5b02c0c21641a38b6acbd9b2b',
//            'cookie' => true,
//         ));
//        
//        $session = $fb->getUser();
//        if ($session){
//            $me = $fb->api('/me');
//            print_r($me);
//            $bday = $me['birthday'];
//           
//           // $birthdate = date('Y-m-d', strtotime($bday));
//            $date1 = new DateTime(date("Y-m-d"));
//            $date2 = new DateTime($me['birthday']);
//            $interval = $date1->diff($date2);
//            echo $interval->y;
//        }
        
       
        try {
            
        $client = new Zend_Http_Client('http://maps.googleapis.com/maps/api/geocode/json');
 
        $address = urlencode('Walmart Supercenter West 136th Avenue Westminster CO');
 
        $client->setParameterGet('sensor', 'false'); // Do we have a GPS sensor? Probably not on most servers.
        $client->setParameterGet('address', $address); // Should now be '1600+Amphitheatre+Parkway,+Mountain+View,+CA'
 
        $response = $client->request('GET'); // We must send our parameters in GET mode, not POST
        
        
        $val = Zend_Http_Response::extractBody($response);
        
        $val = json_decode($val);
       // print_r($val);
        echo "We just printed the value \n\n\n";
       // print_r($val);
        
        
       
//        echo "<br>";
          print_r($val->results[0]->geometry->location->lat);
          print_r($val->results[0]->geometry->location->lng);
//          print_r($val->results[0]);
          print_r($val->results[0]->address_components[1]->short_name);
          print_r($val->results[0]->address_components[3]->long_name);

        
        
          }catch(Exception $ex){
            
            echo "COuldn't contact server";
        }
         
    }

}

