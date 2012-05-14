<?php

/*
 * Used temporarily for login 
 * Would need to switch to an interface that wraps around the Doctrine User Entity
 * as soon as possible
 */
class Account_Model_DbTable_Users extends Zend_Db_Table_Abstract
{

    protected $_name = 'user';

    
}

