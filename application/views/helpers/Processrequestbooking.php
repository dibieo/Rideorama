<?php



/**
 * 
 * This view helper displays the offer ride links
 * 
 * @author ovo
 */

class Zend_View_Helper_Processrequestbooking extends Zend_View_Helper_Abstract {
    
    
    public function processrequestbooking(array $array = null){
        
     $url = null;
       
       if ($array['request_open'] != null){
           
           return "Sold Out!";
       }
        //User is loggned in
       if (Zend_Auth::getInstance()->hasIdentity()){
         

         $user = Zend_Auth::getInstance()->getIdentity();
         $logged_in_user = $user->id;
         
         //If the logged in user is viewing his/her request
         if ( $logged_in_user == $array['publisher_id']){
             
             $url = "Your request";
             return $url;
         }
        
         $user = Zend_Auth::getInstance()->getIdentity();
         
         if ($user->paypal_email == null){
             $user_model = new Account_Model_UserService();
             $user = $user_model->getUserByEmail(Zend_Auth::getInstance()->getIdentity()->email);
         }
         
         //If the responding driver doesn't have a Paypal address, take them where they would feel it in
         if ($user->paypal_email == null){
           
           
            $baseurl_obj = new Zend_View_Helper_BaseUrl(); 
            $baseurl = $baseurl_obj->baseUrl();
            $request_id = $array['request_id'];
            $passengerName = $array['passengerName'];
            $passengerEmail = $array['passengerEmail'];
            $where = $array['where'];
            $url_options = $baseurl . "/account/edit/updatepaypal/request_id/$request_id/where/$where/passengername/$passengerName/passengeremail/$passengerEmail";
            $url = "<a href=$url_options>Offer ride</a>";
            return $url;
           
         }
         
         $request = new Requests_Model_RequestService($array['where']);
         $booked_this_trip = $request->hasUserBookedThisTrip($array['request_id'], $logged_in_user, $array['where']);
         
         //If you've responded to this request then don't show offer ride button
         if ($booked_this_trip){
             $url = "Sold Out!";
             return $url;
         }
             $baseurl = new Zend_View_Helper_BaseUrl();
             $firstname = Zend_Auth::getInstance()->getIdentity()->first_name;
             $lastname = Zend_Auth::getInstance()->getIdentity()->last_name;
             //print_r($array);
             

            $buy_array = array(

              'passengerEmail' => $array['passengerEmail'],
              'passengerName' => $array['passengerName'],
              'trip_date' => $array['date'],
              'where' => $array['where'],
              'offering' => $array['tripcost'],
              'hostname' => $baseurl->baseUrl(),
              'driverEmail' => Zend_Auth::getInstance()->getIdentity()->email,
              'driverName' => $firstname . " " . $lastname,
              'trip_id' => $array['request_id'],
              'trip_date' => $array['date'],
              'publisher_id' => $array ['publisher_id']
                
            );
//            echo "buy array \n";
//            print_r($buy_array);
            $json = Zend_Json::encode($buy_array);
            
            //If we are in the details page
            if ($array['action'] == "details"){
               // echo "i am in the details page";
                $url = "<a id=offerride  data= '$json' class='buy_btn' href='javascript:void(0)'>Offer ride</a>";
            }else{
            $url = "<a id=offerride  data= '$json' href='javascript:void(0)'>Offer ride</a>";
            }
       }else{
           $ref = $this->getUrl('details', $array);
           $url = "<a target = '_blank' href=$ref>Offer ride</a>";
       }
       
       return $url;
    }
    
    
    private function getUrl($action, $array = null){
        
           $setUrl =  new Zend_View_Helper_Url();
           $ref  = $setUrl->url(array(
               "controller" => 'index',
               'module' => $array['module'],
               'action' => $action,
               'trip_id' => $array['request_id'],
               'where' => $array['where'],
             'airport' => $array['airport'],
             'trip_date' => $array['date']
               
           ));
           
          
          
        
      return $ref;
    }
}
