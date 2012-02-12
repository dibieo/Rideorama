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
       $this->_helper->getHelper('layout')->disableLayout();

        $formData= $this->_getAllParams();
        $this->view->date = $formData['trip_date'];

        //print_r($formData);
        $formData['trip_date'] = date('Y-m-d', strtotime($formData['trip_date']));

        $ride = new Rides_Model_RidesService($formData['where']);
        $ride_data = $ride->search($formData, $formData['where']);
        $this->view->rides = $ride_data;
        $this->view->where = $formData['where'];
        $this->setSearchTitle($this->view->where, "destination", "departure");

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
     *
     *
     */
    public function validateformAction()
    {
        // action body

        $this->_helper->formvalidate($this->form, $this->_helper, $this->_getAllParams());
    }

    /**
     * Ajax validation for form under I am a driver tab
     *
     *
     */
    public function validatesecondformAction()
    {
        
        $this->_helper->formvalidate($this->driverform, $this->_helper, $this->_getAllParams());
    }

    public function findpassengerAction()
    {
        $this->_helper->getHelper('layout')->disableLayout();

        // action body
        $formData= $this->_getAllParams();
        #print_r($formData);
        $this->view->date = $formData['driverdate'];
        $formData['driverdate'] = date('Y-m-d', strtotime($formData['driverdate']));
        $request = new Requests_Model_RequestService($formData['driverwhere']);
        $request_data = $request->search($formData, $formData['driverwhere']);
       // print_r($request_data);
        $this->view->requests = $request_data;
        $this->view->where = $formData['driverwhere'];
        $this->setSearchTitle($this->view->where);
    }

    /**
     * Set search title for the sear
     * @param type $where 
     *
     *
     */
    private function setSearchTitle($where, $dest = "driverdestination", $depart = "driverdeparture")
    {
        
        if ($where == "toAirport"){
          $this->view->searchtitle= $this->_getParam($dest);
        }else if ($where == "fromAirport"){
          $this->view->searchtitle = $this->_getParam($depart);
        }   
    }

    public function driversearchAction()
    {
      $this->view->airportList = $this->getAirportList();

        $this->_helper->getHelper('layout')->disableLayout();

        // action body
        $this->view->form = $this->driverform;
    }

    public function passengersearchAction()
    {
      $this->view->airportList = $this->getAirportList();

      $this->_helper->getHelper('layout')->disableLayout();

        // action body
        $this->view->form = $this->form;
    }


}














