<?php

class Admin_AirportController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
       $airport = new Admin_Model_AirportService();
       $this->view->airport = $airport->getAllAirports();
        
    }

    public function addAction()
    {
        // action body
        $form = new Admin_Form_Airport();
        $this->view->form = $form;
        
         //If this form has be submitted, then we should proceed with
         //adding to the database
        
        if ($this->getRequest()->isPost()){
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)){
           //get form vals
            $name = $form->getValue('name');
            $city = $form->getValue('city_id');
            $iata = $form->getValue('iata');
            $pic = $form->getValue('pic');
            
            $airport = new Admin_Model_AirportService();
            $airport->addAirport($name, $iata, $pic, $city);
            $this->_helper->redirector('index');
        }else{
            
            $form->populate($formData);
        }
    
    }
         }

    public function editAction()
    {
        // action body
           // action body
        $form = new Admin_Form_Airport();
        $this->view->form = $form;
        
        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)) {
                $id = (int)$form->getValue('id');
                $name = $form->getValue('name');
                $city = $form->getValue('city_id');
                $iata = $form->getValue('iata');
                $pic = $form->getValue('pic');
                $airport = new Admin_Model_AirportService();
                $airport->updateAirport($id, $name, $iata, $pic, $city);
                
                $this->_helper->redirector('index');
            } else {
                $form->populate($formData);
            }
        } else {
            $id = $this->_getParam('id', 0);
            if ($id > 0) {
                $airport = new Admin_Model_AirportService();
                $airport_data = $airport->getAnAirport($id);
                $data = array(
                    'id'   => $airport_data->id,
                    'name' => $airport_data->name,
                    'city_id'=> $airport_data->city->id,
                    'iata' => $airport_data->iata,
                    'pic' => $airport_data->pic
                );
                $form->populate($data);
            }
        }
    }
    
    public function deleteAction(){
        
        $id = $this->_getParam('id');
        $airport = new Admin_Model_AirportService();
        $airport->deleteAirport($id);
        $this->_helper->redirector('index');
    }


}





