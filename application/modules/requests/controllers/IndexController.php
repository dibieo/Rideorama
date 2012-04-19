<?php

class Requests_IndexController extends Zend_Controller_Action
{

    protected $form = null;

    protected $formData = null;

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
     * Processes the action of responding to a ride request
     *
     */
    public function offerAction()
    {
       $this->_helper->viewRenderer->setNoRender();
       $this->_helper->getHelper('layout')->disableLayout();
        // action body
        $params = $this->_getAllParams();
        $request = new Requests_Model_RequestService($params['where']);
        $request->requestPersmissionToOfferRide($params);
        echo "The passenger has been notified of your offer. You'll be notified when a response is made";
        
    }

    /**
     * Processing acceptance of an offer
     *
     */
    public function offeracceptedAction()
    {
        // action body
        $params = $this->_getAllParams();
        $request = new Requests_Model_RequestService($params['where']);
        $this->view->val = $request->acceptRequestToOfferRide($params);
    }

    /**
     * Processes the rejection of an offer
     *
     */
    public function offerrejectedAction()
    {
        try{
        // action body
        $params = $this->_getAllParams();
        $request = new Requests_Model_RequestService($params['where']);
        $this->view->val = $request->rejectRequestToOfferRide($params);
    }catch (Exception $ex){
        print($ex->getMessage());
    }
    }

    public function detailsAction()
    {
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
        $trip_date = $this->_hasParam('trip_date') ? $this->_getParam('trip_date') : "";
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
                                  ->setValue($to)
                                  ->setAttrib('readOnly', true);
          
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
                                 ->setValue($from)
                                 ->setAttrib('readOnly', true);
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

    public function validateformAction()
    {
        
     $this->_helper->formvalidate($this->form, $this->_helper, $this->_getAllParams());
        
    }

    /**
     * Adds the form data to the model and redirects to a success page
     * @param Array $formData Contains an array of post data
     * @param string $where toAirport or fromAirport
     *
     */
    private function processRequest($formData, $where)
    {
      $formData['trip_date'] =  date('Y-m-d', strtotime($formData['trip_date']));

      $request = new Requests_Model_RequestService($where);
      $trip_id = $request->addRequest($formData, $where);
      $formData['trip_id'] = $trip_id;
      //Share on facebook
      
   if (isset ($formData['facebook'])){
     if ($this->formData['facebook'] == "true"){
         $dest = $formData['destination'];
         $dept = $formData['departure'];
         $date = $formData['trip_date'];
         
         $msg = ($where == "toAirport") ? "I need a ride to $dest on $date" : "I need a ride from $dept on $date";
         
         
         $setUrl =  new Zend_View_Helper_Url();
         $link  = $setUrl->url(array(
               "controller" => 'index',
               'module' => 'requests',
               'action' => 'details',
               'trip_id' => $trip_id,
               'where' => $where,
              'airport' => ($where == "toAirport") ? $dest : $dept,
             'trip_date' => $date
               
           ));
         $fb = new Application_Model_FacebookService();
         $fb->postMessageOnFacebook($msg, $link);
        }
      }
      $this->_forward('success', 'index', 'requests', $formData);
              
    }

    public function editAction()
    {
        // action body
        $request_data = $this->_getAllParams();
        $form = new Requests_Form_Request();
        $form->setDescription('Edit your request');
        $this->setEditFormOptions($form, $request_data);
        $request = new Requests_Model_RequestService($request_data['where']);
       
        $this->view->form = $form;
        $this->view->where = $request_data['where'];
        
       if ($this->getRequest()->isPost()){
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)){
                $formData['where'] = $request_data['where']; // Enter airport location
                $formData['trip_date'] =  date('Y-m-d', strtotime($formData['trip_date']));
                $formData['trip_id'] = $this->_getParam('trip_id');
                $this->updateRequest($formData);
             
        }else{
            
            $form->populate($formData);
        }
           
         }
        
    }
    
    
      /**
     * DRY method for setting values of a form field
     * @param Zend_Form $from 
     * @param Array $ride_data
     */
    private function setEditFormOptions($form, $ride_data){
        
        
        $form->departure->setValue($ride_data['from']);
        $form->destination->setValue($ride_data['to']);
        
        if ($ride_data['where'] == "toAirport"){
           $form->destination->setAttrib('readonly', true); 
        }else if ($ride_data['where'] == "fromAirport"){
           $form->departure->setAttrib('readonly', true) ;
        }
        
        $form->trip_date->setValue(date('m/d/Y', strtotime($ride_data['trip_date'])));
        $form->trip_msg->setValue($ride_data['trip_msg']);
        $form->trip_cost->setValue($ride_data['trip_cost']);
        $form->luggage_size->setValue($ride_data['luggage_size']);
        $form->luggage->setValue($ride_data['luggage']);
        $form->trip_time->setValue($ride_data['trip_time']);
        $form->return->setValue(($this->_hasParam($ride_data['return']) ? $this->_getParam($ride_data['return']) : null));
        
    }

    /**
     * Updates the ride by calling appropriate service models
     * @param array $ride_data 
     */
    private function updateRequest (array $ride_data){
        
        if ($ride_data['where'] == "toAirport"){
            $requests = new Requests_Model_RequestService($ride_data['where']);
            $requests->updateRequestToAirport($ride_data);
        }else if ($ride_data['where'] == "fromAirport"){
            $requests = new Requests_Model_RequestService($ride_data['where']);
            $requests->updateRequestFromAirport($ride_data);
        }
     $this->_forward('success', 'index', 'requests', $ride_data);

    }


}






