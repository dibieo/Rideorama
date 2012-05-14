<?php
/**
 * @todo fix editCategory action
 */

class Admin_LandmarkcategoryController extends Zend_Controller_Action
{
    protected $_cats;
    protected $_form;
    
    public function init()
    {
        /* Initialize action controller here */
        $this->_cats = new Admin_Model_landmarkcategoryService();
        $this->_form = new Admin_Form_LandmarkCateogry();
    }

    public function indexAction()
    {
        // action body=
        $this->view->categories = $this->_cats->getLandmarkCategories();
    }

    public function addAction()
    {
        // action body
        
        $this->_form->submit->setLabel("Add Category");
        $this->view->form = $this->_form;
        
        if ($this->getRequest()->isPost()){
            
            $this->_formData = $this->getRequest()->getPost();
            if ($this->_form->isValid($this->_formData)){
                $name = $this->_form->getValue('name');
                $this->_cats->addLandmarkCategory($name);
                $this->_forward('index');
            }else{
                $this->_form->populate($this->_formData);
            }
            
        }
        
    }

    public function deleteAction()
    {
        // action body
        $id = $this->_getParam('id');
        $this->_cats->deleteLandmarkCategory($id);
        $this->_helper->redirector('index');
        
    }

    public function editAction()
    {
        
        // action body
            // action body
        
        $this->_form->submit->setLabel('Save');
        $this->view->form = $this->_form;
        
        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            if ($this->_form->isValid($formData)) {
                $id = (int)$this->_form->getValue('id');
                $name = $this->_form->getValue('name');
                
                $this->_cats->updateLandmarkCategory($id, $name);
                
                $this->_helper->redirector('index');
            } else {
                $this->_form->populate($formData);
            }
        } else {
            $id = $this->_getParam('id', 0);
            if ($id > 0) {
                
                $this->_form->populate($this->_cats->getLandmarkCategory($id));
            }
        }
    }


}







