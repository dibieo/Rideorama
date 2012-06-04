<?php

class Account_EditController extends Zend_Controller_Action
{

    protected $form = null;

    public function init()
    {
        /* Initialize action controller here */
      $this->form = new Account_Form_Editprofile();
    }

    public function indexAction()
    {
        // action body
        
        $form = $this->form;
        $this->view->form = $form;
        $id = Zend_Auth::getInstance()->getIdentity()->id;
        $user = new Account_Model_UserService();

           if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)) {
                $data = $form->getValues();
                $user->updateProfile($id, $data);
                $this->_forward('index', 'user', 'account');
            } else {
                $form->populate($formData);
            }
        } else {
            //$user = new Account_Model_UserService();
            $user_data = $user->getUser($id);
            //print_r($user_data);
            $data = array(
                    'id'   => $user_data->id,
                    'first_name' => $user_data->first_name,
                    'last_name'=> $user_data->last_name,
                    'email' => $user_data->email,
                    'paypal_email' => $user_data->paypal_email,
                    'phone' => $user_data->telephone,
                    'age' => $user_data->age,
                    'profile_pic' => $user_data->profile_pic,
                    'occupation' => $user_data->profession
                );
                $form->populate($data);
            
        }
        
    }

    public function updatecarAction()
    {
        
        $form = new Account_Form_Editcar();
        $form->setDescription("Update car profile");
        $this->view->form = $form;
        $id = Zend_Auth::getInstance()->getIdentity()->id;
        $user = new Account_Model_UserService();
        $user_data = $user->getUser($id);
        
        // action body
        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)) {
                $data = $form->getValues();
                $car = new Account_Model_CarService();
                $car->updateCarProfile($user_data,$data);
                $this->_forward('index', 'user', 'account');
            } else {
                $form->populate($formData);
            }
        } else {
             
            $data = array(
                    'id'   => $user_data->car->id,
                    'model' => $user_data->car->model,
                    'make'=> $user_data->car->make,
                    'year' => $user_data->car->year,
                    'car_profile_pic' => $user_data->car->car_profile_pic,
                    'picture1' => $user_data->car->picture1,
                    'picture2' => $user_data->car->picture2
                );
                $form->populate($data);
            
        }
        
        
        
    }

    public function updatepaypalAction()
    {
        // action body
     
        $form = new Account_Form_Paypal();
        $this->view->form = $form;
        $id = Zend_Auth::getInstance()->getIdentity()->id;
        $user = new Account_Model_UserService();
        $user_data = $user->getUser($id);
        
        // action body
        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)) {
                $data = $form->getValue('paypal_email');
                $user->updatePaypal($user_data, $data);
                $this->_forward('index', 'user', 'account');
            } else {
                $form->populate($formData);
            }
        } 
       
    }

    public function addcarAction()
    {
        // action body
        $form = new Account_Form_Editcar();
        $this->view->form = $form;   
        $form->setDescription("Complete car profile");
        
        $id = Zend_Auth::getInstance()->getIdentity()->id;
        $user = new Account_Model_UserService();
        $user = $user->getUser($id);
        
        if ($this->getRequest()->isPost()){
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)){
           //get form vals
            $data = $form->getValues();
            $car = new Account_Model_CarService();
            $car->setUpCarProfile($data, $user);
            
            //If we were not trying to post a ride, then forward us to the account page.
             if (Zend_Registry::isRegistered('fillcar') == false){
                 $this->_forward('index', 'user', 'account');
             }else{
                 $fillcar = Zend_Registry::get('fillcar');
                 Zend_Registry::set('fillcar', false);
                 $this->_redirect($fillcar);
                 
             }
        }else{
            
            $form->populate($formData);
        }
        
        
    
    }
    }

    public function updatepasswordAction()
    {
      
    }


}









