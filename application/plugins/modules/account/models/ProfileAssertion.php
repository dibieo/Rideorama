<?php

class Account_Model_ProfileAssertion implements Zend_Acl_Assert_Interface
{

    public function assert(Zend_Acl $acl, Zend_Acl_Role_Interface $user = null,
                           Zend_Acl_Resource_Interface $profile = null, $privilege = null){
        
   
        if ($user->user_id == $profile->owner_id){
            return true;
        }
        else {
            return false;
        }
   }

}

