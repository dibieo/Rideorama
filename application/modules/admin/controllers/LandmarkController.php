<?php

class Admin_LandmarkController extends Zend_Controller_Action
{
    
    protected $_form, $_landmark;

    public function init()
    {
        /* Initialize action controller here */
        $this->_form = new Admin_Form_Landmark();
        $this->_landmark = new Admin_Model_DbTable_Landmark();
    }

    public function indexAction()
    {
        // action body
        $this->view->landmarks = $this->_landmark->getAllLandmarks();
    }

    public function addAction()
    {
        // action body
        $form = $this->_form;
        $form->submit->setLabel('Add Landmark');
        $this->view->form = $form;
        
        if ($this->getRequest()->isPost()){
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)){
                $data = $form->getValues();
                $this->_landmark->addLandmark($data['name'], $data['pic'],
                 $data['address'], $data['city_id'], $data['cat_id']);
                
                $this->_helper->redirector('index');
            }else{
                $form->populate($formData);
            }
        }

    }

    public function deleteAction()
    {
        // action body
        $id = $this->_getParam('id');
        $this->_landmark->deleteLandmark($id);
        $this->_helper->redirector('index');
     
        
     }

    public function editAction()
    {
        // action body
    }


}







