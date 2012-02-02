<?php


class IndexController extends Zend_Controller_Action
{

    protected $form = null;

    protected $driverform = null;
    
    

    public function init()
    {
        
    
        
        $this->form = new Application_Form_Searchride();
        $this->driverform = new Application_Form_Searchrequest();     
    }

    public function indexAction()
    {
        
        $this->view->form = $this->form;
        $this->view->driverform = $this->driverform;
        $this->view->airportList = $this->getAirportList();
        
        echo $this->view->airportList;
        if ($this->getRequest()->getParam('trip_time')){

                 //$formData = $this->getRequest()->getParams();
                 $formData= $this->_getAllParams();
             
            if ($this->form->isValid($formData)){
                
                $this->_forward('search', 'index', 'default', $formData);
            }

       }
       
       if ($this->getRequest()->getParam('drivertrip_time')){
           
                $formData = $this->_getAllParams();
                
                if ($this->driverform->isValid($formData)){
                    $this->_forward('findpassenger', 'index', 'default', $formData);
                }
       }
            
    }
    

    public function searchAction()
    {
        $formData= $this->_getAllParams();
        $ride = new Rides_Model_RidesService($formData['where']);
        $ride_data = $ride->search($formData, $formData['where']);
        $this->view->date = $formData['trip_date'];
        $this->view->rides = $ride_data;
        $this->view->where = $formData['where'];
    }

    private function getAirportList()
    {
        
        $all_airports = null;
        $airports = $this->form->getAirports();
        foreach($airports as $a){
           $var = "{\"key\" " . ":" . $a["key"] . ", " . "\"value\"" . ":"  . "\""
            . $a["value"]  .
            "\"" . "}," ;
           
           $all_airports.= $var;
        }
        
        return $all_airports;
    }

    /**
     * Ajax validation for form under I am a passenger tab
     */
    public function validateformAction()
    {
        // action body

        $this->_helper->formvalidate($this->form, $this->_helper, $this->_getAllParams());
    }

    /**
     * Ajax validation for form under I am a driver tab
     */
    public function validatesecondformAction()
    {
        
        $this->_helper->formvalidate($this->driverform, $this->_helper, $this->_getAllParams());
    }

    public function findpassengerAction()
    {
        // action body
        $formData= $this->_getAllParams();
        $request = new Requests_Model_RequestService($formData['driverwhere']);
        $request_data = $request->search($formData, $formData['driverwhere']);
       // print_r($request_data);
        $this->view->date = $formData['driverdate'];
        $this->view->requests = $request_data;
        $this->view->where = $formData['driverwhere'];
    }


}










