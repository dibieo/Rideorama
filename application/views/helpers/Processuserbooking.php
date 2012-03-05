<?php


/**
 * This view helper processes the BuyNow link on 
 * the trip details page.
 */
class Zend_View_Helper_Processuserbooking extends Zend_View_Helper_Abstract {
    
    /**
     * This function returns a url that allows passengers proceed with booking a trip.
     * @param string $trip_id
     * @param string $publisher_id
     * @param string $where
     * @param string $driverEmail
     * @param string $driverName
     * @return string The url allowing users book the trip 
     */
    public function processuserbooking(array $array = null){
        
       $url = null;
       
        // We are looking at rides and there are no seats available.
       if ($array['num_seats'] == 0 && $array['module'] == "rides"){
           
           return "Sold out!";
       }
        //User is loggned in
       if (Zend_Auth::getInstance()->hasIdentity()){
         $array['trip_date'] = str_replace("/", "-", $array['trip_date']);

         $logged_in_user = Zend_Auth::getInstance()->getIdentity()->id;
         
         //If the logged in user is viewing his/her trip
         if ( $logged_in_user == $array['user_id']){
             
             $url = "Your trip";
             return $url;
         }
         
         $ride = new Rides_Model_RidesService($array['where']);
         $booked_this_trip = $ride->hasUserBookedThisTrip($array['trip_id'], $logged_in_user, $array['where']);
         
         //If you've booked this trip then don't show buy now button
         if ($booked_this_trip){
             $url = "";
             return $url;
         }
             $baseurl = new Zend_View_Helper_BaseUrl();

            $buy_array = array(
              'driverEmail' => $array['driverEmail'],
              'driverName' => $array['driverName'],
              'trip_date' => $array['trip_date'],
              'where' => $array['where'],
              'tripcost' => $array['tripcost'],
              'paypalEmail' => $array['paypalEmail'],
              'hostname' => $baseurl->baseUrl(),
              'module' => $array['module'],
              'passengerEmail' => Zend_Auth::getInstance()->getIdentity()->email,
              'trip_id' => $array['trip_id'],
              'num_seats' => $array['num_seats'],
              'publisher_id' => $array ['user_id']
                
            );
//            echo "buy array \n";
//            print_r($buy_array);
            $json = Zend_Json::encode($buy_array);
            
            //If we are in the details page
            if ($array['action'] == "details"){
                $url = "<a id=bookseat  data= '$json' class='buy_btn' href='javascript:void(0)'>Buy now</a>";
            }else{
            $url = "<a id=bookseat  data= '$json' href='javascript:void(0)'>Buy now</a>";
            }
       }else{
           $ref = $this->getUrl('details', $array);
           $url = "<a target = '_blank' href=$ref>Buy now</a>";
       }
       
       return $url;
    }
    
    
    private function getUrl($action, $array = null){
        
           $setUrl =  new Zend_View_Helper_Url();
           $ref  = $setUrl->url(array(
               "controller" => 'index',
               'module' => $array['module'],
               'action' => $action,
               'trip_id' => $array['trip_id'],
               'where' => $array['where'],
             'airport' => $array['airport'],
             'trip_date' => $array['trip_date']
               
           ));
           
          
          
        
      return $ref;
    }
}