<?php

/**
 * This service layer handles the sending of email
 */
class Application_Model_EmailService extends Zend_Mail
{
    
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
     * Function: sendEmail.
     * This function is used to send an email
     * @param array $data {includes recipient_email, recipient_name, subject, and body
     */
     public function sendEmail(array $data){
         
       $this->addTo($data['recipient_email'], $data['recipient_name'])
                   ->setSubject($data['subject'])
                   ->setBodyHtml($data['body'])
                   ->send();
     }  
     
    public function sendRegistrationEMail($first_name, $last_name, $email_hash, $email){
               
        $url = $this->emailLink("account", "user", "activate", array("hash" => $email_hash));
        
        $email_options = array(
            'recipient_name' => $first_name . ' ' . $last_name,
            'recipient_email' => $email,
            'subject' => 'Thank you for registering with Rideorama',
            'body' => "<a href= $url>Please click on this link to activate your regsitration.</a>"
     
        );
        
        
        $this->sendEmail($email_options);
    }

}

