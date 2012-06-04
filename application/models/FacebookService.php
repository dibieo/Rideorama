<?php
require_once 'facebook.php'; //Load Facebook Api

/**
 * FacebookService
 * This Service handles interactions with the Facebook SDK
 * 
 */
class Application_Model_FacebookService extends Application_Model_Service
{

 protected $facebook; // stores the facebook API
 protected $baseUrl;
    public function __construct() {
        
     $fboptions = Zend_Registry::get('config')->facebook;

     $this->facebook = new Facebook(array(
            'appId' => $fboptions->applicationID,
            'secret' => $fboptions->appSecret,
            'cookie' => true,
         ));
    
     $this->baseUrl =  new Zend_View_Helper_ServerUrl();
    }
 
 public function loggedIn(){
    
   if ($this->facebook->getUser() == 0){
       
       return false;
   }else{
       
       return true;
   }
 }
 
     public function postMessageOnFacebook($msg, $link = "http://www.rideorama.com"){
         
        $facebook_link = $this->generateTripDetailsFBUrl($link);
        try {
        $this->facebook->api("/me/feed", 'post', array(
                    
                    'message' => $msg, 
                    'link'    => "$facebook_link",
                    'picture' => '',
                    'name'    => '',
                    'description'=> ''
                    )
                );
                //as $_GET['publish'] is set so remove it by redirecting user to the base url 
            } catch (FacebookApiException $e) {
                echo $e->getMessage();
            }
      }
    
    /**
     * Generates a URL that points back to the details of the trip
     * @param string $url
     * @return string  The details URL of the trip
     */
     public function generateTripDetailsFBUrl($url){

        $fbUrl = "http://" . $this->baseUrl->getHost() . $url;
        // $fbUrl = "http://test.rideorama.com" . $url;
         //echo "The url is " . $fbUrl;
         return $this->shortenURL($fbUrl);
    }
 

    public function getFullUrl($url){
                $fbUrl = "http://" . $this->baseUrl->getHost() . $url;

                return $fbUrl;
    }
}



