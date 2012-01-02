<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    
    
    protected function _initActionHelpers(){
        
         Zend_Controller_Action_HelperBroker::addPath(
        APPLICATION_PATH .'/controllers/helpers');
    }
    protected function _initViewHelpers(){

        $this->bootstrap('layout');
        $layout = $this->getResource('layout');
        $view = $layout->getView();
        
        $view->addHelperPath('ZendX/JQuery/View/Helper', 
                            'ZendX_JQuery_View_Helper');

        $currency = new Zend_Currency('en_US');
        Zend_Registry::set('Zend_Currency', $currency);
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
     * Initializes ACL resources
     */
    protected function _initAcl() {
       
        $acl = new Application_Model_Acl();
        if (Zend_Auth::getInstance()->hasIdentity()){
        
                Zend_Registry::set('role', Zend_Auth::getInstance()->getStorage()->read()->role);      
        }else {
               Zend_Registry::set('role', 'guest');
        }
        
        $fc = Zend_Controller_Front::getInstance();
        $fc->registerPlugin(new Application_Plugin_AccessCheck($acl));
    }
}

