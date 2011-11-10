<?php

class Admin_CityController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body'
        
        $city = new Admin_Model_CityService();
        $this->view->cities = $city->getAllCities();
        
    }

    public function editCityAction()
    {
        // action body
        $form = new Admin_Form_City();
        $form->submit->setLabel('Save');
        $this->view->form = $form;
        
        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)) {
                $id = (int)$form->getValue('id');
                $city_name = $form->getValue('name');
                $state_id = $form->getValue('state_id');
                $city = new Admin_Model_CityService();
                $city->updateCity($id, $city_name, $state_id);
                
                $this->_helper->redirector('index');
            } else {
                $form->populate($formData);
            }
        } else {
            $id = $this->_getParam('id', 0);
            if ($id > 0) {
                $city = new Admin_Model_CityService();
                $city_data = $city->getACity($id);
                $data = array(
                    'id'   => $city_data->id,
                    'name' => $city_data->name,
                    'state'=> $city_data->state,
                    'state_id' => $city_data->state->id
                );
                $form->populate($data);
            }
        }
    }

    public function addCityAction()
    {
        $form = new Admin_Form_City();
        $form->submit->setLabel('Add City');
        
        $this->view->form = $form;
                
         //If this form has be submitted, then we should proceed with
         //adding to the database
        
        if ($this->getRequest()->isPost()){
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)){
           //get form vals
            $city_name = $form->getValue('name');
            $state_id = $form->getValue('state_id');            
            $city = new Admin_Model_CityService();
            $city->addCity($city_name, $state_id);
            $this->_helper->redirector('index');
        }else{
            
            $form->populate($formData);
        }
    }
    }

    /**
     * Function: deleteCityAction
     * Deletes the city and redirects to the index page that contains all the cities
     */
    public function deleteCityAction()
    {
        // action body
        $id = $this->_getParam('id');
        $city = new Admin_Model_CityService();
        $city->deleteCity($id);
        $this->_helper->redirector('index');
    }


}







