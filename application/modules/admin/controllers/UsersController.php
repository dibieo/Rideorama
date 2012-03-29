<?php

class Admin_UsersController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
        $users = new Account_Model_UserService();
        $total_users = $users->getTotalNumberofRegisteredUsers();
        $this->view->total_users = $total_users;
    }


}

