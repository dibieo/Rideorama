<?php

class ForgotpasswordController extends Zend_Controller_Action
{
    
    protected $form;

    public function init()
    {
        /* Initialize action controller here */
        $this->form = new Application_Form_Enteremail();
    }

    public function indexAction()
    {
        // action body
        $form = $this->form;
        $this->view->form = $form;
        
        if ($this->getRequest()->isPost()){
           
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)){
                $data = $form->getValues();
                $user_service = new Account_Model_UserService();
                $user = $user_service->getUserByEmail($data['email']);
                if ($user == null){
                 $this->view->errorMsg = "User with email " . $data['email'] . " was not found in our databases";
                }else{
                 $email_service = new Application_Model_EmailService();
                 
                 $email_service->resetAccountPassword($user->first_name, $user->email, $user->email_hash);
                 $this->view->successMsg = "An email has been sent to your account with instructions on resetting your password";
                }
            }else{
                $form->populate($formData);
            }
            
            
        }
        
    }
    
    public function validateformAction(){
        
    $this->_helper->formvalidate($this->form, $this->_helper, $this->_getAllParams());

    }


}

