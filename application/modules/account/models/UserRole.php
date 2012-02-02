<?php

class Account_Model_UserRole implements Zend_Acl_Role_Interface
{
    public  $role = null;
    public $user_id = null;
    
    /**
     * Sets the user id and role
     */
    public function __construct() {
        $this->user_id = Zend_Auth::getInstance()->getIdentity()->id;
        $this->role = Zend_Auth::getInstance()->getIdentity()->role;
    }
    
    /**
     * Returns the user's role
     * @return string the user role 
     */
    public function getRoleId() {
        return $this->role;
    }

}

