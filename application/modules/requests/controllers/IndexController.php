<?php

class Requests_IndexController extends Zend_Controller_Action
{

    protected $form = null;
    protected $formData;

    public function init()
    {
        /* Initialize action controller here */
        
        $this->form = new Requests_Form_Request();
    }

    public function indexAction()
    {
        // action body
    }
    
   /**
     * Processing acceptance of an offer
     */
    public function offeracceptedAction()
    {
        // action body
        $params = $this->_getAllParams();
        $request = new Requests_Model_RequestService($params['where']);
        $request->acceptRequestToOfferRide($params);
    }

    /**
     * Processes the rejection of an offer
     */
    public function offerrejectedAction()
    {
        // action body
        $params = $this->_getAllParams();
        $request = new Requests_Model_RequestService($params['where']);
        $request->rejectRequestToOfferRide($params);
    }


    public function detailsAction(){
           // action body
        $params = $this->_getAllParams();
        $this->view->where = $params['where'];
        $this->view->trip_date = $params['trip_date'];
        $this->view->airport = $params['airport'];
        $trip = new Requests_Model_RequestService($params['where']);
        $params = $trip->tripdetails($params);
        $this->view->data = $params[0];
        
    }
    public function postAction()
    {
        // action body
        $where = $this->_getParam('where');
        $from = $this->_getParam('from');
        $to = $this->_getParam('to');
        $trip_date = $this->_getParam('trip_date');
        $return_trip = "true";
        if ($this->_hasParam('return_trip')){
            $return_trip = $this->_getParam('return_trip');
        }
        
        if ($where == "toAirport"){
          //Set placeholders on text fields
          
          $this->form->trip_date->setValue($trip_date);
          $this->form->departure->setAttrib('placeholder', 'Enter the address you would like to be picked up from')
                          ->setValue($from);
          $this->form->destination->setAttrib('placeholder', 'Enter airport name')
                                   ->setJQueryParams(array('source' =>$this->form->getAirports()))
                                  ->setValue($to);
          $this->form->trip_msg->setAttrib('placeholder', 'Enter additional information regarding this request such as what terminal you would like to be dropped off at');
          $this->form->return->setValue($return_trip);
          $this->view->form = $this->form;
          $this->view->where = $where;
          
         if ($this->getRequest()->isPost()){
            $this->formData = $this->getRequest()->getPost();
            if ($this->form->isValid($this->formData)){
            $this->processRequest($this->formData, $where);
             //Share on facebook checkbox was clicked
           
        }else{
            $this->form->populate($this->formData);
        }
         }
            
        }else if ($where == "fromAirport"){
            
          $this->form->trip_date->setValue($trip_date);
          $this->form->departure->setAttrib('placeholder', 'Enter airport name')
                                 ->setJQueryParams(array('source' =>$this->form->getAirports()))
                                 ->setValue($from);
          $this->form->destination->setAttrib('placeholder', 'Enter the address you would like to be picked up from')
                            ->setValue($to);
          $this->form->trip_msg->setAttrib('placeholder', 'Enter additional information regarding this request such as what terminal you would like to be picked up from');
          $this->form->return->setValue($return_trip);
          $this->view->form = $this->form; 
          $this->view->where = $where;
            if ($this->getRequest()->isPost()){
            $this->formData = $this->getRequest()->getPost();
            if ($this->form->isValid($this->formData)){
           
            $this->processRequest($this->formData, $where);
                     
        }else {
            $this->form->populate($this->formData);
        }
         }
          
        }else{
            $this->view->errorMsg = "Oops, an error occured. Please go back and try again!";
        
    }
    }

    
    public function successAction()
    {
        // action body
        $this->view->params = $this->_getAllParams(); 
        $this->view->msg = "Request posted";
    }
    
    public function validateformAction(){
        
     $this->_helper->formvalidate($this->form, $this->_helper, $this->_getAllParams());
        
    }
    /**
     * Adds the form data to the model and redirects to a success page
     * @param Array $formData Contains an array of post data
     * @param string $where toAirport or fromAirport
     */
    private function processRequest($formData, $where)
    {
      $formData['trip_date'] =  date('Y-m-d', strtotime($formData['trip_date']));

      $ride = new Requests_Model_RequestService($where);
      $ride->addRequest($formData, $where);
      //Share on facebook
      
   if (isset ($formData['facebook'])){
     if ($this->formData['facebook'] == "true"){
        $ride->postMessageOnFacebook("I need a ride! Check Rideorama to view my request!=> $where");
        }
      }
      $this->_forward('success', 'index', 'requests', $this->formData);
              
    }



}




