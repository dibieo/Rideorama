<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Plugin_AccessCheck
 *
 * @author ovo
 */

class Application_Plugin_AccessCheck extends Zend_Controller_Plugin_Abstract {

                
 private $_acl = null;
                
  public function __construct(Zend_Acl $acl){
                        
                        $this->_acl = $acl;
       }
                
   public function preDispatch(Zend_Controller_Request_Abstract $request)
   {
    //   echo "predispatch working";
                        
   //Now get the Module, Controller, and Actions for that specific resource.
       $module = strtolower($request->getModuleName());
       $resource = strtolower($request->getControllerName());
       $action = strtolower($request->getActionName());
                
        if (!$this->_acl->isAllowed(Zend_Registry::get('role'), $module. ":" .$resource, $action)){
            $request->setModuleName('account')
                    ->setControllerName('user')
                    ->setActionName('login');
            
            $frontController = Zend_Controller_Front::getInstance();
            $url = "http://" .$_SERVER['SERVER_NAME'] . $frontController->getRequest()->getRequestUri();
            Zend_Registry::set('return', $url);
                                }
                }
  }
   
   
