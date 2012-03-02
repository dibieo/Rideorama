<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    
    protected $auth = null;
    protected $acl = null;
    
    protected function _initActionHelpers(){
        
         Zend_Controller_Action_HelperBroker::addPath(
        APPLICATION_PATH .'/controllers/helpers');
         

    }
  


    /**
     * Used for email configuration
     */
    protected function _initDefaultEmailTransport() {
        
        $emailConfig = $this->getOption('email');

        $mailTransport = new Zend_Mail_Transport_Smtp('smtp.gmail.com', $emailConfig['transportOptionsSmtp']);

        Zend_Mail::setDefaultTransport($mailTransport);
    }

    /**
     * configuration options
     * @return Zend_Config 
     */
      protected function _initConfig()
    {
        $config = new Zend_Config($this->getOptions());
        Zend_Registry::set('config', $config);
        return $config;
    }
    /**
     * Initializes ACL resources
     * @todo: Refactor this function for better performance
     */
    protected function _initAcl() {
       
        $acl = new Application_Model_Acl();
        $this->acl = $acl;
        $this->auth = Zend_Auth::getInstance();
        
        if (Zend_Auth::getInstance()->hasIdentity()){
        
                Zend_Registry::set('role', Zend_Auth::getInstance()->getStorage()->read()->role);      
        }else {
               Zend_Registry::set('role', 'guest');
        }
        
        $fc = Zend_Controller_Front::getInstance();
        $fc->registerPlugin(new Application_Plugin_AccessCheck($acl));
        Zend_Registry::set('acl', $acl);
    }
    
      protected function _initViewHelpers(){

        $this->bootstrap('layout');
        $this->bootstrap('view');
        $layout = $this->getResource('layout');
        $view = $layout->getView();
       $view->addHelperPath('ZendX/JQuery/View/Helper', 'ZendX_JQuery_View_Helper');
        $currency = new Zend_Currency('en_US');
        Zend_Registry::set('Zend_Currency', $currency);
        
        //Navigation config
        $navContainerConfig = new Zend_Config_Xml(APPLICATION_PATH . '/configs/navigation.xml', 'nav');
        $navContainer = new Zend_Navigation($navContainerConfig);
        
        $role = "guest";
        if ($this->auth->hasIdentity()){
            $role = $this->auth->getStorage()->read()->role;
        }
        
        $view->navigation($navContainer)->setAcl($this->acl)->setRole($role);
    }
    
}

