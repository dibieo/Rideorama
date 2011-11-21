<?php

// require_once 'Zend/Cache/Backend/ExtendedInterface.php';

class IndexController extends Zend_Controller_Action
{
    protected $form;
    
    public function init()
    {
        
        $this->form = new Application_Form_Searchride();
       
   
        
    }

    public function indexAction()
    {
        $this->view->form = $this->form;
    
    }
    
    public function searchAction(){
        
        
        if ($this->getRequest()->isDispatched()){
           
            $formData = $this->getRequest()->getParams();
            
              if ($this->form->isValid($formData)){
                  
                $ride = new Rides_Model_RidesService($formData['where']);
                $ride_data =  $ride->search($formData, $formData['where']);
                
               // print_r($ride_data);
                
               $this->view->rides = $ride_data;
    
        }
    
    }
    
    }

  


}



