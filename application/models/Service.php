<?php

class Application_Model_Service
{
    protected $doctrineContainer;
    protected $em;
    protected $mail;
    
    public function __construct(){
        $this->doctrineContainer = \Zend_Registry::get('doctrine');
        $this->em = $this->doctrineContainer->getEntityManager();
        $this->mail = new Zend_Mail();
    }
    
    public function __get($property)
    {
        return $this->$property;
    }
    
    public function __set($property,$value)
    {
        $this->$property = $value;
    } 
    
    public function getAll($entity){
        
        return $this->em->getRepository($entity)->findAll();
    }
    
     public function sendEmail(array $data){
         
       $this->mail->addTo($data['recipient_email'], $data['recipient_name'])
                   ->setSubject($data['subject'])
                   ->setBodyHtml($data['body'])
                   ->send();
     }  
    
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
}

