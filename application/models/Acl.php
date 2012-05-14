<?php

class Application_Model_Acl extends Zend_Acl
{

    public function __construct(){
        
		
		//Roles
		$this->addRole(new Zend_Acl_Role ('guest'));	
		$this->addRole(new Zend_Acl_Role('user'), 'guest');
		$this->addRole(new Zend_Acl_Role('admin'), 'user');
               

		 //Default Module Resources	--All resources inherit from the module default
		$this->add(new Zend_Acl_Resource('default'))
			 ->add(new Zend_Acl_Resource('default:index'), 'default')
                          ->add(new Zend_Acl_Resource('default:aboutus'), 'default')
                          ->add(new Zend_Acl_Resource('default:privacy'), 'default')
                          ->add(new Zend_Acl_Resource('default:terms'), 'default')
                          ->add(new Zend_Acl_Resource('default:howitworks'), 'default')
                        ->add(new Zend_Acl_Resource('default:faqs'), 'default')
                          ->add(new Zend_Acl_Resource('default:updatepassword'), 'default')
                          ->add(new Zend_Acl_Resource('default:forgotpassword'), 'default')
                         ->add(new Zend_Acl_Resource('default:show'), 'default')
			->add(new Zend_Acl_Resource('default:error'), 'default')
			->add(new Zend_Acl_Resource('default:account'), 'default');
			
		//Account module resources -- All resources inherit from the account module
		$this->add(new Zend_Acl_Resource('account'))
			 ->add(new Zend_Acl_Resource('account:user'), 'account')
                         ->add(new Zend_Acl_Resource('account:edit'), 'account')
                         ->add(new Zend_Acl_Resource('account:profile'), 'account');
                
		
                //Rides module resources -- All resources inherit from the rides module
		$this->add(new Zend_Acl_Resource('rides'))
			 ->add(new Zend_Acl_Resource('rides:index'), 'rides');
                
                
                $this->add(new Zend_Acl_Resource('requests'))
                      ->add(new Zend_Acl_Resource('requests:index'), 'requests');
			 
		//Admin Module Resources --All resources inherid from the module admin
		$this->add(new Zend_Acl_Resource('admin'))
			 ->add(new Zend_Acl_Resource('admin:index'), 'admin')
                         ->add(new Zend_Acl_Resource('admin:users'), 'admin')
                         ->add(new Zend_Acl_Resource('admin:state'),  'admin')
            		 ->add(new Zend_Acl_Resource('admin:city'), 'admin')
                         ->add(new Zend_Acl_Resource('admin:landmarkcategory'), 'admin')
                         ->add(new Zend_Acl_Resource('admin:airport'), 'admin');
			 
		
                //Payment Module resources
                $this->add(new Zend_Acl_Resource('payment'))
			 ->add(new Zend_Acl_Resource('payment:index'), 'payment');
                
		/**
		 * Resources for the student Module
		 * 
		
		
		/**
		 *Application Permissions
		 *A guest is able to login, register and view the about pages of the system.
		 *A guest is also able to view all options in the Community module except for rating a course.
		 */
                
		$this->allow('guest', 'default:index', array('validateform', 'validatesecondform', 'homepageticker', 'findpassenger', 'index', 'search', 'passengersearch', 'driversearch'))
			 ->allow('guest', 'default:error', 'error')
                         ->allow('guest', 'default:howitworks', 'index')
                         ->allow('guest', 'default:faqs', 'index')
                         ->allow('guest', 'default:show', array('index', 'calc'))
                         ->allow('guest', 'default:privacy', array('index'))
                         ->allow('guest', 'default:terms', array('index'))
                         ->allow('guest', 'default:updatepassword', array('index', 'validateform'))
                         ->allow('guest', 'default:forgotpassword', array('index', 'validateform'))
                         ->allow('guest', 'default:aboutus', array('index', 'cgeorge', 'ksabi', 'agobitaka', 'odibie'))
			 ->allow('guest', 'account:user', array('login', 'register', 'recover', 'thanks','validateform', 'activate', 'regcomplete', 'validateactivationform'))
                         ->deny('user', 'account:user', array('login', 'register'));
			 
			 
		/**
		 * Users have all permissions as guests with the additional permission of being able to rate 
		 * a particular course
		 */
                
		$this->allow('user', 'account:user', array('logout', 'edit', 'index','viewprofile', 'myrides', 'withwho'))
                     ->allow('user', 'account:profile', array('index'))
		     ->allow('user', 'account:edit', array('index', 'car', 'validateform', 'updatecar', 'updatepaypal', 'addcar'));
		
                $this->allow('user', 'rides:index', array('index', 'post', 'book', 'delete', 'edit', 'details','validateform', 'success', 'bookingaccepted', 'bookingrejected'))
                      ->allow('user', 'requests:index', array('index', 'post', 'edit', 'delete', 'success', 'offer', 'validateform', 'details', 'offerrejected'))
                      ->allow('user', 'payment:index', array('index', 'success'));
                
                
//		$this->allow('admin', 'admin:usermanagement', array('listusers','deluser', 'avgratings', 'viewcomments'))
                $this->allow('admin', 'admin:index', array('index'));
                $this->allow('admin', 'admin:airport', array('index', 'add', 'edit', 'delete'));
                $this->allow('admin', 'admin:city', array('index', 'add-city', 'edit-city', 'delete-city'));
                $this->allow('admin', 'admin:landmarkcategory', array('index', 'add'));
                $this->allow('admin', 'admin:users', array('index', 'view'));
                $this->allow('admin', 'admin:state', array('index', 'delete-state', 'edit-state', 'add-state'));

		
              
                        		/*
		$this->allow('admin', 'admin');
		$this->allow('user', 'index');
		$this->allow('user', 'error');
		$this->allow('user', 'community', 'index');
		$this->allow('admin', 'community', 'rate');*/
		
	
    }
    
    public function setDynamicPermissions(){
        
//        $this->addResource('account:profile');
//        $this->allow('user', 'account:profile', 'index', new Account_Model_ProfileAssertion());
    }

}

