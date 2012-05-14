<?php

class Account_ProfileController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $userResource = new Account_Model_UserService();
        
        // action body
        $user_id = $this->_getParam('id');
        $user = $userResource->getUser($user_id);
        
        $this->view->user = $user;
   
    }


}

