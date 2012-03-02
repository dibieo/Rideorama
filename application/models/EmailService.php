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
                                              'trip_id' => $array['trip_id'],
                                              'tripcost' => $array['tripcost'],
                                             'passengerName' => $array['passengerName'],
                                             'passengerEmail' => $array['passengerEmail'],
                                             'paypalEmail' => $array['paypalEmail']));
              
          $link = "http://" .$baseurl->getHost().$link;
        
           return $link;
             
         }

      
    /**
     * Function: sendEmail.
     * This function is used to send an email
     * @param array $data {includes recipient_email, recipient_name, subject, and body
     */
     public function sendEmail(array $data){
         
       $this->addTo($data['recipient_email'], $data['recipient_name'])
                   ->setSubject($data['subject'])
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
      
      
      $body = "<p> Hi " . $array['driverName'] . ",</p><p></p>" .
                $passengerName . " would like a seat in your car! <p></p>". 
              "<a href=#>Check out $passengerName profile</a> and decide if you would like him/her on your trip".
                "<p><a href=$accept_url>Click this link to confirm $passengerName seat in your car </a></p>
                <p><a href=$reject_url> Click here to decline $passengerName request for a seat on your trip</a></p>
                 <p>Make sure to reply promptly so $passengerName can make other plans.
                <br>Your Friends,</br>
                <br>The Rideorama Team</br></p>";
      
      $email_options = array(
            'recipient_name' => $array['driverName'],
            'recipient_email' => $array['driverEmail'],
            'subject' => 'Someone on wants a seat on your ride',
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
      $accept_url = $this->acceptRejectEmailLink("requests", "index", 'offeraccepted', $array);
      $reject_url = $this->acceptRejectEmailLink('requests', 'index', 'offerrejected', $array);
      
      $passengerName = $array['passengerName'];
      $driverName = $array['driverName'];
      
      $body = "<p> Hi " . $array['passegnerName'] . ",</p>" . 
              "<p> $driverName has just responded to your seat request. <a href=#>Click here to check out his profile</a> </p>" .
              "<p><a href=$accept_url>Click this link to accept $driverName offer</a></p>".
               "<p><a href=$reject_url>Click this link to reject $driverName offer</a></p>".
              "<p>Make sure to reply promptly so $driverName can make alternative plans.
                <br>Your Friends,</br>
                <br>The Rideorama Team</br></p>";
      
      $email_options = array(
            'recipient_name' => $array['driverName'],
            'recipient_email' => $array['driverEmail'],
            'subject' => "$driverName responded to your seat request on " . $array['trip_date'],
            'body' => $body
        );
      
      $this->sendEmail($email_options);
    }
    
    
    public function acceptOfferRequest(array $array){
        
        $passengerName = $array['passengerName'];
        $driverName = $array['driverName'];
        
        $url = $this->acceptRejectEmailLink("payment", "index", "index",  array(
                                             "driverName" => $array['driverName'],    
                                             'where' => $array['where'],
                                             'request_id' => $array['request_id'],
                                             'offer_amount'=> $array['offer_amount'],
                                            ));
          
        $body = "<p> $passengerName has accepeted your ride offer and has booked a seat on your trip!</p><p> You will receive another email once the seat has been paid for. </p>"
                . "<p> Thanks for using Rideorama! </p> <p> The Rideorama Team </p>";
        
        $email_options = array(
            'recipent_name' => $driverName,
            'recipient_email' => $array['driverEmail'],
            'subject' => $passengerName . " has accepted your ride offer"
        );
       $this->sendEmail($email_options);
    }
    
    
    public function rejectOfferRequest(array $array){
        
        $passengerName = $array['passengerName'];
        $driverName = $array['driverName'];
        
        $url = $this->acceptRejectEmailLink("payment", "index", "index",  array(
                                             "driverName" => $array['driverName'],    
                                             'where' => $array['where'],
                                             'request_id' => $array['request_id'],
                                             'offer_amount'=> $array['offer_amount'],
                                            ));
          
        $body = "<p>Sorry $driverName, </p>
                <p>$passengerName has rejected your ride offer.</p>  <p> Please search Rideorama to find other passengers or post to Rideorama to let passengers find you </p>";
        
        $email_options = array(
            'recipent_name' => $driverName,
            'recipient_email' => $array['driverEmail'],
            'subject' => "Your ride offer has been denied"
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
                      "paypalEmail" => $array['paypalEmail'],
                      "passengerEmail" => $array['passengerEmail'],
                      "tripcost" => $array['tripcost'],
                      "passengerName" => $array['passengerName']));
        
        $body = "<p> Hi " . $array['passengerName'] . "</p> <p>Congratulations! " 
                 . $array['driverName'] . " just confirmed your request for a seat.  <a href=$url>Follow this link to pay for your seat and complete the transaction</a>
                </p><p></p>The Rideorama Team";
 
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
     * Sends driver an email notification of the passenger booking this trip. 
     * @param array $array 
     */
    public function seatPaymentConfirmed(array $array){
     
        $subject = $array['passengerName'] . " just paid for a seat on your trip";
        
        $body = "<p> Hi " . $array['driverName'] . " </p> <p> Congratulations, " . $array['passengerName'] .
                "just paid for their seat on your trip. You'll receive this payment in your paypal account approximately 24 hrs after the trip<p></p>".
                "Also, be sure to leave feedback for this trip after it’s completed.  Every trip you review helps other passengers make better decisions about whom to rideshare with.  Safe Travels!";
                
        $email_options = array(
            'recipient_name' => $array['driverName'],
            'recipient_email' => $array['passengerEmail'],
            'subject' => $subject,
            'body' => $body
        );
        
        $this->sendEmail($email_options);
   }
}

