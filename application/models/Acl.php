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
                         ->add(new Zend_Acl_Resource('default:show'), 'default')
			->add(new Zend_Acl_Resource('default:error'), 'default')
			->add(new Zend_Acl_Resource('default:account'), 'default');
			
		//Account module resources -- All resources inherit from the account module
		$this->add(new Zend_Acl_Resource('account'))
			 ->add(new Zend_Acl_Resource('account:user'), 'account');
                
		
                //Rides module resources -- All resources inherit from the rides module
		$this->add(new Zend_Acl_Resource('rides'))
			 ->add(new Zend_Acl_Resource('rides:index'), 'rides');
                
                
                $this->add(new Zend_Acl_Resource('requests'))
                      ->add(new Zend_Acl_Resource('requests:index'), 'requests');
			 
		//Admin Module Resources --All resources inherid from the module admin
		$this->add(new Zend_Acl_Resource('admin'))
			 ->add(new Zend_Acl_Resource('admin:index'), 'admin')
			 ->add(new Zend_Acl_Resource('admin:state'), 'admin')
			 ->add(new Zend_Acl_Resource('admin:city'), 'admin')
                         ->add(new Zend_Acl_Resource('admin:landmarkcategory'), 'admin')
                         ->add(new Zend_Acl_Resource('admin:airport'), 'admin');
			 
		
		/**
		 * Resources for the student Module
		 * 
		
		
		/**
		 *Application Permissions
		 *A guest is able to login, register and view the about pages of the system.
		 *A guest is also able to view all options in the Community module except for rating a course.
		 */
		$this->allow('guest', 'default:index', array('validateform', 'validatedriverform', 'index', 'search'))
			 ->allow('guest', 'default:error', 'error')
                         ->allow('guest', 'default:show', array('index', 'calc'))
			 ->allow('guest', 'account:user', array('login', 'register', 'recover', 'thanks', 'activate'));
	
			 
			 
		/**
		 * Users have all permissions as guests with the additional permission of being able to rate 
		 * a particular course
		 */
		$this->allow('user', 'account:user', array('logout', 'edit', 'index', 'viewprofile'))										 
		     ->deny('user', 'account:user', array('login', 'register'));
		
                $this->allow('user', 'rides:index', array('index', 'post', 'validateform', 'success'))
                      ->allow('user', 'requests:index', array('index', 'post', 'validateform'));
                
//		$this->allow('admin', 'admin:usermanagement', array('listusers','deluser', 'avgratings', 'viewcomments'))
                $this->allow('admin', 'admin:index', array('index'));
                $this->allow('admin', 'admin:airport', array('index', 'add', 'edit', 'delete'));
                $this->allow('admin', 'admin:city', array('index', 'add-city', 'edit-city', 'delete-city'));
                $this->allow('admin', 'admin:landmarkcategory', array('index', 'add'));
                $this->allow('admin', 'admin:state', array('index', 'delete-state', 'edit-state', 'add-state'));
                		//->allow('admin', 'admin:courses', array('index', 'viewschools', 'getdepts', 'getactions', 'getcourses', 'edit', 'delete'));

		
                        
                        		/*
		$this->allow('admin', 'admin');
		$this->allow('user', 'index');
		$this->allow('user', 'error');
		$this->allow('user', 'community', 'index');
		$this->allow('admin', 'community', 'rate');*/
		
	
    }

}

