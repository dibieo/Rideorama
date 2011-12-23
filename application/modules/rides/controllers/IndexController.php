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
        $departure = $this->_getParam('from');
        $destination = $this->_getParam('to');
        $where = $this->_getParam('where');
        $trip_date = $this->_getParam('trip_date');
        
        if ($where == "toAirport"){
        // action body
       $form = new Rides_Form_Rides();
       $form->departure->setValue($departure);
       $form->destination->setValue($destination);
       
       $this->view->form = $form;
       
           if ($this->getRequest()->isPost()){
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)){
           
           
             $ride = new Rides_Model_RidesService($where);
             $ride->addRide($formData, $where);
             
             $this->_forward('success', 'index', 'rides', $formData);
        }else{
            
            $form->populate($formData);
        }
           
    }
    
    
    }else if ($where == "fromAirport"){
        
          // action body
       $form = new Rides_Form_RidesFromAirport();
       $form->departure->setValue($departure);
       $form->destination->setValue($destination);
       $form->trip_date->setValue($trip_date);
       
       $this->view->form = $form;
       
           if ($this->getRequest()->isPost()){
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)){
           
             $ride = new Rides_Model_RidesService($where);
             $ride->addRide($formData, $where);
             $this->view->msg = "Ride posted";
            $this->_forward('success', 'index', 'rides', $formData);

            //$this->_helper->redirector('success');
        }else{
            
            $form->populate($formData);
        }
           
    }
    
    }
    }

    public function validateformAction()
    {
    
      $this->_helper->viewRenderer->setNoRender();
      $this->_helper->getHelper('layout')->disableLayout();

        $f = new Rides_Form_Rides();
        $f->isValid($this->_getAllParams());
        $json = $f->getMessages();
        header('Content-type: application/json');
        echo Zend_Json::encode($json);

    }

    public function successAction()
    {
        $this->view->params = $this->_getAllParams();
        $this->view->msg = "Ride posted";
    }


}





