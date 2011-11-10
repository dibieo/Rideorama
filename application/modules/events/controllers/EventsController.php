<?php

class Events_EventsController extends Zend_Controller_Action
{

    protected $_form;
    protected $_events;
    
    public function init()
    {
        /* Initialize action controller here */
        $this->_form = new Events_Form_Events();
        $this->_events = new Events_Model_DbTable_Events();
    }

    public function indexAction()
    {
        // action body
        $this->view->events = $this->_events->getEvents();
    }

    public function addAction()
    {
        // action body
        $form = $this->_form;
        $form->submit->setLabel('Add event');
        $this->view->form = $form;
        if ($this->getRequest()->isPost()){
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)){
                $data = $form->getValues();
                Zend_Debug::dump($data);
                $filepath = $form->banner->getFileName();
                echo "File path is " . $filepath;
               $this->_events->addEvent($data['name'], $data['event_date'],
                  $data['event_time'], $data['location_id'], $data['banner']);
                
           }else{
               
               $form->populate($formData);
           } 
        }
        
    }

    public function deleteAction()
    {
        // action body
        $id = $this->_getParam('id');
        $this->_events->deleteEvent($id);
        $this->_forward('index');
    }

    public function editAction()
    {
        // action body
    }


}







