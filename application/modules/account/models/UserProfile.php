<?php

class Account_Model_UserProfile implements Zend_Acl_Resource_Interface
{
  public $owner_id = null;
  public $resource_id = "profile";
  
  public function getResourceId() {
      
      return $this->resource_id;
  }

}

