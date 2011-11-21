<?php

class Admin_StateController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
        $states = new Admin_Model_stateService();
        //Zend_Debug::dump($states->getAllStates());
       $this->view->state_list = $states->getAllStates();
    }

    
    
    /**
     * This adds a new state
     * to the application
     *
     */
    public function addStateAction()
    {
        $form = new Admin_Form_State();
        $this->view->form = $form;
        
        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)) {
                $name = $form->getValue('name');
                $abbv = $form->getValue('abbv');
                $states = new Admin_Model_stateService();
                $states->add($name, $abbv);
                $this->_helper->flashMessenger->addMessage("State saved");
                
                $this->_forward('index');
            } else {
                $form->populate($formData);
                }

        }
    
    }
    
//    public function previewAction($name, $abbv, $states){
//        
//        $this->view->name = $name;
//        $this->view->abbv = $abbv;
//        
//    }
//   
//    public function goBack(){}
//    public function submitAction($name, $abbv, $states){
//        $states->add($name, $abbv);
//        $this->_forward('index');
//
//    }
        
    
    public function deleteStateAction()
    {
        // action body
        $id = $this->_getParam('id');
        $state = new Admin_Model_stateService();
        $state->deleteState($id);
        $this->_helper->redirector('index');
        
    }

    public function editStateAction()
    {
        // action body
        $form = new Admin_Form_State();
        $form->submit->setLabel('Save');
        $this->view->form = $form;
        
        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)) {
                $id = (int)$form->getValue('id');
                $state = $form->getValue('name');
                $abbv = $form->getValue('abbv');
                $states = new Admin_Model_stateService();
                $states->updateState($id, $state, $abbv);
                
                $this->_forward('index');
            } else {
                $form->populate($formData);
            }
        } else {
            $id = $this->_getParam('id', 0);
            if ($id > 0) {
                $state = new Admin_Model_stateService();
                $state = $state->getState($id);
                $state_data = array(
                    'id' => $state->id,
                    'name' => $state->name,
                    'abbv' => $state->abbv
                );
                $form->populate($state_data);
            }
        }
    }


}







