<?php

class Rides_IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
    }

    public function postAction()
    {
        // action body
       $form = new Rides_Form_Rides();
       $this->view->form = $form;
       
           if ($this->getRequest()->isPost()){
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)){
           //get form vals
           
              //  var_dump($formData);
           
             $ride = new Rides_Model_RidesService($formData['where']);
             $ride->addRide($formData, $formData['where']);

            //$this->_helper->redirector('index');
        }else{
            
            $form->populate($formData);
        }
    
    }
    }
    
    public function validateformAction(){
    
      $this->_helper->viewRenderer->setNoRender();
      $this->_helper->getHelper('layout')->disableLayout();

        $f = new Rides_Form_Rides();
        $f->isValid($this->_getAllParams());
        $json = $f->getMessages();
        header('Content-type: application/json');
        echo Zend_Json::encode($json);

    }
    
}



