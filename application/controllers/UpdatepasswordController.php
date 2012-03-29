<?php

class UpdatepasswordController extends Zend_Controller_Action
{

    protected $form;
    public function init()
    {
        /* Initialize action controller here */
     $this->form = new Account_Form_Updatepassword();
    }

    public function indexAction()
    {
        // action body
        $form = $this->form;
        $this->view->form = $this->form;
        $email_hash = $this->_getParam('email_hash');
        
        if ($this->getRequest()->isPost()){
        
        $formData = $this->getRequest()->getPost();
        if ($form->isValid($formData)){
            $data = $form->getValues();
            $pass = new Account_Model_UserService();
            $pass->updateUserPassword($email_hash, $data['password']);
            $this->_helper->flashMessenger->addMessage('Success! Your password has been successfully updated'); 
            $this->_redirect('account/user/login');
        }else{
           $form->populate($formData); 
        }
        
        }
    }
    
    public function validateformAction(){
  
        $this->_helper->formvalidate($this->form, $this->_helper, $this->_getAllParams());

    }


}

