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
      
     $date = '2012-03-29';
     echo date('m/d/Y', strtotime($date));
//     $date = str_replace("-", "/", $date);
//     $datetime = new DateTime(date($date));
//     print_r($datetime);
//        $arr = array();
//        array_push($arr, array("key" => array("dept" => "boulder", "cost" => 15), 'value' => "200 miles"));
//        array_push($arr, array("key" => array("dept" => "phoenix", "cost" => 9), 'value' => "234 miles"));
//        array_push($arr, array("key" => array("dept" => "phoenix", "cost" => 8), 'value' => "221 miles"));
//        array_push($arr, array("key" => array("dept" => "phoenix", "cost" => 5), 'value' => "221 miles"));
//
//        print_r($arr);
//        echo "sorted vals \n<p></p>";
//        $ride = new Application_Model_Service();
//        print_r($ride->sortTripByParam($arr));
    }
    

    public function calcAction(){
        
    
        
       
//        try {
//            
//        $client = new Zend_Http_Client('http://maps.googleapis.com/maps/api/geocode/json');
// 
//        $address = urlencode('Walmart Supercenter Main Street, Longmont, Boulder, CO');
// 
//        $client->setParameterGet('sensor', 'false'); // Do we have a GPS sensor? Probably not on most servers.
//        $client->setParameterGet('address', $address); // Should now be '1600+Amphitheatre+Parkway,+Mountain+View,+CA'
// 
//        $response = $client->request('GET'); // We must send our parameters in GET mode, not POST
//        
//        
//        $val = Zend_Http_Response::extractBody($response);
//        
//        $val = json_decode($val);
//       // print_r($val);
//        echo "We just printed the value \n\n\n";
//       // print_r($val);
//        
//        
//       
////        echo "<br>";
////          print_r($val->results[0]->geometry->location->lat);
////          print_r($val->results[0]->geometry->location->lng);
////          print_r($val->results[0]->address_components[2]->short_name);
////          print_r($val->results[0]->address_components[4]->long_name);
//
//        print_r($val->results[0]);
//
//        
//        
//          }catch(Exception $ex){
//            
//            echo "COuldn't contact server";
//        }
         
    }

}

