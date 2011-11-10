<?php

// require_once 'Zend/Cache/Backend/ExtendedInterface.php';

class IndexController extends Zend_Controller_Action
{

    public function init(){
        
       $a = new Application_Model_Service();
       
   
        
    }
    public function indexAction()
    {
        $form = new Application_Form_Searchride();
        $this->view->form = $form;
        
        if ($this->getRequest()->isDispatched()){
        print_r($this->getRequest()->getParam('dest_city'));
    }
    }
    
}

