<?php
require_once 'facebook.php'; //Load Facebook Api

class Application_Model_FacebookService
{

 protected $facebook; // stores the facebook API
 
    public function __construct() {
        
     $fboptions = Zend_Registry::get('config')->facebook;

     $this->facebook = new Facebook(array(
            'appId' => $fboptions->applicationID,
            'secret' => $fboptions->appSecret,
            'cookie' => true,
         ));
    
    }
 
 public function loggedIn(){
    
   if ($this->facebook->getUser() == 0){
       
       return false;
   }else{
       
       return true;
   }
 }
 

}



