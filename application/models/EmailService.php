<?php

/**
 * This service layer handles the sending of email
 */
class Application_Model_EmailService extends Zend_Mail
{
    
      /**
      * emailLink: This creates a link for an email to a user.
      * @param string $module
      * @param string $controller
      * @param string $action
      * @param array $param
      * @return string link
      */
     public function emailLink($module, $controller, $action, array $param){
         $key = array_keys($param);
         $value = array_values($param);
         
         $helper = new Zend_View_Helper_Url() ;
         $baseurl = new Zend_View_Helper_ServerUrl();
         $link = $helper->url(array("module"=>$module, "controller"=>$controller,
                                            "action"=>$action, $key[0] => $value[0]));
              
          $link = "http://" .$baseurl->getHost().$link;
        
           return $link;

     }
    
     /**
      * Sends the trip accept or reject email
      * @param type $module
      * @param type $controller
      * @param type $action
      * @param array $param
      * @return string 
      */
     public function acceptRejectEmailLink($module, $controller, $action, array $array) {
         
         $helper = new Zend_View_Helper_Url() ;
         $baseurl = new Zend_View_Helper_ServerUrl();
         $link = $helper->url(array("module"=>$module, "controller"=>$controller,
                                            "action"=>$action,
                                             'driverName' => $array['driverName'],
                                             'driverEmail' => $array['driverEmail'],
                                              'where' => $array['where'],
                                              'trip_date' => $array['trip_date'],
                                              'trip_id' => $array['trip_id'],
                                              'tripcost' => $array['tripcost'],
                                             'passengerName' => $array['passengerName'],
                                             'passengerEmail' => $array['passengerEmail']));
              
          $link = "http://" .$baseurl->getHost().$link;
        
           return $link;
             
         }
         
      
      /**
       * Generates a URL for viewing a user's profile
       * @param string $module
       * @param string $controller
       * @param string $action
       * @param integer $id
       * @return string  The URL for the user's public profile
       */
      public function generateUserProfileURL($module, $controller, $action, $id) {
         
         $helper = new Zend_View_Helper_Url() ;
         $baseurl = new Zend_View_Helper_ServerUrl();
         $link = $helper->url(array("module"=>$module, "controller"=>$controller,
                                            "action"=>$action,
                                             "id" => $id));
              
          $link = "http://" .$baseurl->getHost().$link;
        
           return $link;
             
         }

      
    /**
     * Function: sendEmail.
     * This function is used to send an email
     * @param array $data {includes recipient_email, recipient_name, subject, and body
     */
     public function sendEmail(array $data){
      $replyTo = Zend_Registry::get('config')->email->transportOptionsSmtp->username;
        
       $this->addTo($data['recipient_email'], $data['recipient_name'])
                   ->setSubject($data['subject'])
                   ->setReplyTo($replyTo, "Rideorama")
                   ->setBodyHtml($data['body'], "utf-8")
                   ->setFrom("no-reply@rideorama.com", "Rideorama")
                   ->send();
     }  
     
    /**
     *
     * @param string $first_name
     * @param string $last_name
     * @param string $email_hash
     * @param string $email 
     */
    public function sendRegistrationEMail($first_name, $last_name, $email_hash, $email){
               
        $url = $this->emailLink("account", "user", "activate", array("hash" => $email_hash));
        
        $body = "<p>Hey $first_name,</p>
                <p>Welcome to Rideorama, the best way to rideshare to the airport </p>.
                <p>Thanks for signing up as one of our earliest users!<a href=$url> Click here to activate your account and complete your profile. </a> </p>
                <p>With Rideorama,  It’s easy to post ride offers and request rides.  
                Let us know if you have any issues or if you have any suggestions on how to improve Rideorama.</p><p>  We’d love to hear from you and can be reached at team@rideorama.com. 
                We want to make sure you and your friends have a great time using Rideorama.</p>
                <p>Thanks for your support!</p>
                <p>Your Friends,
                <br></br>
                The Rideorama Team</p>";
        
        $email_options = array(
            'recipient_name' => $first_name . ' ' . $last_name,
            'recipient_email' => $email,
            'subject' => 'Thank you for registering with Rideorama',
            'body' => $body
     
        );
        
        
        $this->sendEmail($email_options);
    }

    
    /**
     * This function sends the driver an email notifying him that a passenger
     * would like a seat on his trip
     * @param array $array 
     */
    public function sendBookingRequest(array $array){
    
      $accept_url = $this->acceptRejectEmailLink("rides", "index", 'bookingaccepted', $array);
      $reject_url = $this->acceptRejectEmailLink('rides', 'index', 'bookingrejected', $array);
      
      $passengerName = $array['passengerName'];
      $id = Zend_Auth::getInstance()->getIdentity()->id;
      $email = Zend_Auth::getInstance()->getIdentity()->email;
      $telephone = Zend_Auth::getInstance()->getIdentity()->telephone;
      $location = null;
      if ($array['where'] == "toAirport"){
          $location = "to " . $array['airport'] . rawurldecode($array['trip_date']);
      }else if ($array['where'] == "fromAirport"){
          $location = "from " . $array['airport'] . rawurldecode($array['trip_date']);
      }
      $user_profile_url = $this->generateUserProfileURL('account', 'profile', 'index', $id);
      
      $body = "<p> Hi " . $array['driverName'] . ",</p><p></p>" .
                $passengerName . " would like a seat on your trip $location <p></p>". 
              "<a href=$user_profile_url>Check out $passengerName profile</a> and decide if you would like him/her on your trip".
                "<p>You can reach $passengerName via email at $email or on the phone at $telephone </p>" .
                "<p><a href=$accept_url>Click this link to confirm $passengerName seat in your car </a></p>
                <p><a href=$reject_url> Click here to decline $passengerName request for a seat on your trip</a></p>
                 <p>Make sure to reply promptly so $passengerName can make other plans.
                <p>Keep rocking!</p>
                <p>The Rideorama Team</br></p>";
      
      $email_options = array(
            'recipient_name' => $array['driverName'],
            'recipient_email' => $array['driverEmail'],
            'subject' => 'Someone wants a seat on your ride',
            'body' => $body
        );
      
      $this->sendEmail($email_options);
    }
    
    /**
     * This function sends the passenger an email notifying him/her that a driver
     * is offering a seat in his car
     * @param array $array of information needed to email the passenger
     */
    public function sendOfferRequest(array $array){
      //print_r($array);
      
      $accept_url = $this->requestAcceptRejectEmailLink("payment", "index", 'index', $array);
      $reject_url = $this->requestAcceptRejectEmailLink('requests', 'index', 'offerrejected', $array);
      
      $passengerName = $array['passengerName'];
      $driverName = $array['driverName'];
      $id = Zend_Auth::getInstance()->getIdentity()->id;
      $email = Zend_Auth::getInstance()->getIdentity()->email;
      $telephone = Zend_Auth::getInstance()->getIdentity()->telephone;
      
      $user_profile_url = $this->generateUserProfileURL('account', 'profile', 'index', $id);
             
      $body = "<p> Hi " . $array['passengerName'] . ",</p>" . 
              "<p> $driverName has just responded to your seat request. <a href=$user_profile_url>Click here to check out his profile</a> </p>" .
              "<p>You can also contact $driverName via email at $email or on the phone at $telephone</p>" .
              "<p><a href=$accept_url>Click this link to accept $driverName offer and complete payment</a></p>".
               "<p><a href=$reject_url>Click this link to reject $driverName offer</a></p>".
              "<p>Make sure to reply promptly so $driverName can plan accordingly.
                <br>Your Friends,</br>
                <br>The Rideorama Team</br></p>";
      
      $email_options = array(
            'recipient_name' => $array['passengerName'],
            'recipient_email' => $array['passengerEmail'],
            'subject' => "$driverName responded to your seat request for " . rawurldecode($array['trip_date']),
            'body' => $body
        );
      
      $this->sendEmail($email_options);
    }
    
    
    /**
     *
     * @param array $array 
     */
    public function acceptOfferRequest(array $array){
        
        $passengerName = $array['passengerName'];
        $driverName = $array['driverName'];
        
        $url = $this->acceptRejectEmailLink("payment", "index", "index",  array(
                                             "driverName" => $array['driverName'],    
                                             'where' => $array['where'],
                                             'request_id' => $array['request_id'],
                                             'offer_amount'=> $array['offer_amount'],
                                             'trip_date' => $array['trip_date']
                                            ));
          
        $body = "<p> $passengerName has accepeted your ride offer!</p><p> You will receive another email once the seat has been paid for. </p>"
                . "<p> Thanks for using Rideorama! </p> <p> The Rideorama Team </p>";
        
        $email_options = array(
            'recipient_name' => $driverName,
            'recipient_email' => $array['driverEmail'],
            'subject' => $passengerName . " has accepted your ride offer"
        );
       $this->sendEmail($email_options);
    }
    
    
    /**
     * Sends the driver an email notifying him that the passenger has rejected his request
     * @param array $array 
     */
    public function rejectOfferRequest(array $array){
        
        $passengerName = $array['passengerName'];
        $driverName = $array['driverName'];
       
        $body = "<p>Sorry $driverName, </p>
                <p>$passengerName has rejected your ride offer.</p>  <p> Please search Rideorama to find other passengers or post to Rideorama to let passengers find you </p>
                <p>We know riding alone sucks and hope you find someone </p> <p>Rideorama team</p>";
        
        $email_options = array(
            'recipient_name' => $driverName,
            'recipient_email' => $array['driverEmail'],
            'subject' => "Your ride offer has been denied",
            'body' => $body
        );
       $this->sendEmail($email_options);
    }
    
        /**
         * This creates a link needed to accept/reject a ride request 
         * @param string $module
         * @param string $controller
         * @param string $action
         * @param array $array
         * @return string The URL to be embedded in the email
         */
         public function requestAcceptRejectEmailLink($module, $controller, $action, array $array) {
         
         $helper = new Zend_View_Helper_Url() ;
         $baseurl = new Zend_View_Helper_ServerUrl();
         $link = $helper->url(array("module"=>$module, "controller"=>$controller,
                                            "action"=>$action,
                                             'driverName' => $array['driverName'],
                                             'driverEmail' => $array['driverEmail'],
                                             'where' => $array['where'],
                                             'trip_date' => $array['trip_date'],
                                             'trip_id' => $array['trip_id'],
                                             'tripcost' => $array['offering'],
                                             'module' => $module,
                                             'passengerName' => $array['passengerName'],
                                             'passengerEmail' => $array['passengerEmail']));
              
          $link = "http://" .$baseurl->getHost().$link;
        
           return $link;
             
         }
         
         
         /**
          * Notifies the driver that a passenger has completed payment for his/her seat
          * @param type $array of options
          */
         public function paymentSuccessEmailToDriver($array){
           
             $receiverName = $array['driverName'];
             $receiverEmail = $array['driverEmail'];
             $senderName = $array['passengerName'];
             $senderEmail = $array['passengerEmail'];
             $senderPhone = $array['passengerPhone'];
             
             $amount = $array['tripcost'];
             
             $body = "<p>Hi $receiverName, </p>
            <p>$senderName just made a payment of $$amount for your trip together.</p> 
             <p>We'll transfer this amount less 20% for our processing fee to your Paypal account about a day after your trip </p>
             <p> Here are $senderName's contact details if you need to make further arrangements before your trip: </p>
             <p>
             <p> Email address:  $senderEmail </p>
             <p> Telephone number: $senderPhone </p>
             </p>
             <p>Safe travels</p><p>Rideorama team</p>";
        
            $email_options = array(
            'recipient_name' => $receiverName,
            'recipient_email' => $receiverEmail,
            'subject' => "$senderName just completed payment for your trip together",
            'body' => $body
             );
            $this->sendEmail($email_options);
         }
         
         
         /**
          * Notifies the paying passenger of the driver's contact details
          * @param type $array 
          */
         public function paymentSuccessEmailToPassenger($array){
             
             $receiverName = $array['passengerName'];
             $receiverEmail = $array['passengerEmail'];
             $driverName = $array['driverName'];
             $driverEmail = $array['driverEmail'];
             $driverPhone = $array['driverPhone'];
       
             $body = "<p>Hi $receiverName, </p>
            <p>Thanks for completing payment for your trip with $driverName .</p> 
             <p>Here are $driverName's phone number and email if you need to make further arrangements before your trip</p>
             <p>Phone number: $driverPhone</p> <p>Email: $driverEmail</p>
             <p>Enjoy your travels</p>
             <p>Also, be sure to leave feedback for this trip after it’s completed.<br> Every trip you review helps other passengers make better decisions about whom to rideshare with.
             </p>
             <p>You rock!</p>
             <p>Rideorama team</p>";
        
             $email_options = array(
            'recipient_name' => $receiverName,
            'recipient_email' => $receiverEmail,
            'subject' => "Trip confirmed with $driverName",
             'body' => $body
             );
            $this->sendEmail($email_options);
             
         }
         
         
         

    /**
     * Sends the passenger booking a seat an email
     * notifying him/her that their seat request has been accepted
     * @param array $array 
     */
    public function bookingRequestAccepted(array $array){
        
        $url = $this->acceptRejectEmailLink("payment", "index", "index", 
                array("driverEmail" => $array['driverEmail'],
                      "driverName" => $array['driverName'],
                       'where' => $array['where'],
                       'trip_id' => $array['trip_id'],
                       'trip_date' => $array['trip_date'],
                      "passengerEmail" => $array['passengerEmail'],
                      "tripcost" => $array['tripcost'],
                       "module" => $array['module'],
                      "passengerName" => $array['passengerName']));
        
        $body = "<p> Hi " . $array['passengerName'] . "</p> <p>Congratulations! " 
                 . $array['driverName'] . " just confirmed your request for a seat. <p> <a href=$url>Follow this link to pay for your seat and complete the transaction</a></p>
                 <p> You're the best! </p>
                 <p>The Rideorama Team</p>";
 
        $email_options = array(
            'recipient_name' => $array['passengerName'],
            'recipient_email' => $array['passengerEmail'],
            'subject' => 'Your Seat Request Has Been Confirmed!',
            'body' => $body
            );
        
        $this->sendEmail($email_options);
     
    }
    
    
    /**
     * Notifies the passenger booking a seat that the
     * driver has declined his/her request.
     * @param array $array Array containing ncessary trip information for sending this notification
     */
    public function bookingRequestRejected(array $array){
        
        $url = $this->emailLink("default", "index", "index", array("return" => 'yes'));
        
        
        $subject = "Your Seat Request Has Been Denied";

        $body = "<p>Sorry " . $array['passengerName'] . ",</p>" .
                 "<p> ". $array['driverName'] . " has denied your seat request. Please search Rideorama for another driver or post to the Rideorama to let drivers find you.
                 <a href=$url>Click this link to go to Rideorama</a>
                 The Rideorama Team </p>";
        
      $email_options = array(
            'recipient_name' => $array['passengerName'],
            'recipient_email' => $array['passengerEmail'],
            'subject' => $subject,
            'body' => $body
            );
        
        $this->sendEmail($email_options);
    }
    
    /**
     * Resests a user's account
     * @param type $first_name
     * @param type $email
     * @param type $email_hash 
     */
    public function resetAccountPassword($first_name, $email, $email_hash){
        
       $url = $this->emailLink('default', 'updatepassword', 'index', array(
                'email_hash'=> $email_hash
                )); 
       
       $subject = "Reset your Rideorama account password";
       
       $body = "<p>Hi $first_name, </p>" .
               "<p>We're sorry you're having troubles logging into your account. </p>".
               "<p><a href=$url>Click here to reset your password</a></p>" .
               "<p>Have a great day!</p>".
               "<p>Rideorama team</p>";
       
       $email_options = array(
           'recipient_name' => $first_name,
           'recipient_email'=> $email,
           'subject' => $subject,
           'body' => $body
       );
       $this->sendEmail($email_options);
    }
    /**
     * Sends driver an email notification of the passenger booking this trip. 
     * @param array $array 
     */
    public function seatPaymentConfirmed(array $array){
     
        $subject = $array['passengerName'] . " just paid for a seat on your trip";
        
        $body = "<p> Hi " . $array['driverName'] . " </p> <p> Congratulations, " . $array['passengerName'] .
                "just paid for their seat on your trip. You'll receive this payment in your paypal account approximately 24 hrs after the trip<p></p>".
                "Also, be sure to leave feedback for this trip after it’s completed.  Every trip you review helps other passengers make better decisions about whom to rideshare with. 
                <p>You rock! </p>";
                
        $email_options = array(
            'recipient_name' => $array['driverName'],
            'recipient_email' => $array['passengerEmail'],
            'subject' => $subject,
            'body' => $body
        );
        
        $this->sendEmail($email_options);
   }
}

