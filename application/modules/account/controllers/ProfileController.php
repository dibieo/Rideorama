<?php

class Account_ProfileController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $userRole = new Account_Model_UserRole();
        $userResource = new Account_Model_UserProfile();
        
        // action body
       $user_id = $this->_getParam('id');
       $userResource->owner_id = $user_id;
       
       if (Zend_Registry::get('acl')->isAllowed($userRole, $userResource, 'index')){
            $user = new Account_Model_UserService();
            print_r($user->getUser($user_id));
       } else{
           echo "You're not allowed to view this page";
       }
    }


}

