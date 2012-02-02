<?php

class Rides_IndexController extends Zend_Controller_Action
{

    protected $ride_form = null;

    public function init()
    {
        /* Initialize action controller here */
        $this->ride_form = new Rides_Form_Rides();
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
            
       $this->getRideFormPage($where, $departure, $destination, $trip_date);
    
         }else if ($where == "fromAirport"){
       
            $this->getRideFormPage($where, $departure, $destination, $trip_date);
           
         }
        
    
    }

    public function validateformAction()
    {
        
        $this->_helper->formvalidate($this->ride_form, $this->_helper, $this->_getAllParams());
    }

    /**
     * Displays the appropriate form for posting a ride and submits the user's post
     * to the Ride_Model_Service
     * @param string $where (fromAirport or toAirport)
     * @param string $departure
     * @param string $destination
     * @param string $trip_date 
     *
     *
     */
    private function getRideFormPage($where, $departure, $destination, $trip_date)
    {
       $this->view->where = $where;
       $this->ride_form->departure->setValue($departure);
       $this->ride_form->destination->setValue($destination);
       $this->ride_form->trip_date->setValue($trip_date);
       $this->view->form = $this->ride_form;
       
         if ($this->getRequest()->isPost()){
            $formData = $this->getRequest()->getPost();
            if ($this->ride_form->isValid($formData)){
           
           
             $ride = new Rides_Model_RidesService($where);
             $ride->addRide($formData, $where);
             
             $this->_forward('success', 'index', 'rides', $formData);
        }else{
            
            $this->ride_form->populate($formData);
        }
           
         }
    
    }

    public function successAction()
    {
        $this->view->params = $this->_getAllParams();
        $this->view->msg = "Ride posted";
    }

    public function bookAction()
    {
       $this->_helper->viewRenderer->setNoRender();
       $this->_helper->getHelper('layout')->disableLayout();
        // action body
        $params = $this->_getAllParams();
        $ride = new Rides_Model_RidesService($params['where']);
        $ride->requestPersmissionToBuySeat($params['trip_id'], $params['publisher_id'], $params['where'],
                                           $params['driverEmail'], $params['driverName']);
        echo "A request has been sent to this driver for approval";
    }

    public function detailsAction()
    {
        // action body
    }


}









