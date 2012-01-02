<?php

class Requests_IndexController extends Zend_Controller_Action
{

    protected $form = null;

    public function init()
    {
        /* Initialize action controller here */
        
        $this->form = new Requests_Form_Request();
    }

    public function indexAction()
    {
        // action body
    }

    public function postAction()
    {
        // action body
        $where = $this->_getParam('where');
        $from = $this->_getParam('from');
        $to = $this->_getParam('to');
        $trip_date = $this->_getParam('trip_date');
        
        
        if ($where == "toAirport"){
          //Set placeholders on text fields
          $this->form->trip_date->setValue($trip_date);
          $this->form->departure->setAttrib('placeholder', 'Enter the address you would like to be picked up from')
                          ->setValue($from);
          $this->form->destination->setAttrib('placeholder', 'Enter airport name')
                            ->setValue($to);
          $this->form->trip_msg->setAttrib('placeholder', 'Enter additional information regarding this request such as what terminal you would like to be dropped off at');
          $this->view->form = $this->form;
          
         if ($this->getRequest()->isPost()){
            $this->formData = $this->getRequest()->getPost();
            if ($this->form->isValid($this->formData)){
           
            $this->processRequest($this->formData, $where);
        }
         }
            
        }else if ($where == "fromAirport"){
            
          $this->form->trip_date->setValue($trip_date);
          $this->form->departure->setAttrib('placeholder', 'Enter airport name')
                          ->setValue($from);
          $this->form->destination->setAttrib('placeholder', 'Enter the address you would like to be picked up from')
                            ->setValue($to);
          $this->form->trip_msg->setAttrib('placeholder', 'Enter additional information regarding this request such as what terminal you would like to be picked up from');
          $this->view->form = $this->form; 
          
            if ($this->getRequest()->isPost()){
            $this->formData = $this->getRequest()->getPost();
            if ($this->form->isValid($this->formData)){
           
            $this->processRequest($this->formData, $where);
        }
         }
          
        }else{
            $this->view->errorMsg = "Oops, an error occured. Please go back and try again!";
        
    }
    }

    
    public function successAction()
    {
        // action body
    }
    
    public function validateformAction(){
        
     $this->_helper->formvalidate($this->form, $this->_helper, $this->_getAllParams());

        
    }
    private function processRequest($formData, $where)
    {
    
      $ride = new Requests_Model_RequestService($where);
      $ride->addRequest($this->formData, $where);
      $this->view->msg = "Your request for a ride has been posted";
      $this->_forward('success', 'index', 'requests', $this->formData);
        
    }



}





