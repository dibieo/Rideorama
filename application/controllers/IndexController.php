<?php


class IndexController extends Zend_Controller_Action
{
    protected $form;
    
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
            
            $formData = $this->getRequest()->getParams();
            
            if ($this->form->isValid($formData)){
                
                $this->_forward('search', 'index', 'default', $formData);
            }

          }
            
       }
        
    
    
    
    public function searchAction(){
        $formData= $this->_getAllParams();
        $ride = new Rides_Model_RidesService($formData['where']);
        $ride_data = $ride->search($formData, $formData['where']);
        $this->view->date = $formData['trip_date'];
        $this->view->rides = $ride_data;
    }

    private function getAirportList(){
        
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
  




}




