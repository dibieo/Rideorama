<?php

class Application_Form_Events extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
        
        /* Form Elements & Other Definitions Here ... */
         $id = new Zend_Form_Element_Hidden('id');
         $name = new Zend_Form_Element_Text('name');
         $name->setLabel('Event name:')
               ->setRequired(true);
         
         $date = new Zend_Form_Element_Text('event_date');
         $date->setLabel('Event date: ');
         
         $time = new Zend_Form_Element_Text('event_time', array(
             'label' => 'Event Time',
             'required' => true
         ));
         
         $city = new Zend_Form_Element_Select('location_id');
         $city->setLabel('Event city');
         $city->addMultiOptions($this->getCities());
         
         $fileDest = realpath(APPLICATION_PATH . '/../public/img');
         $image = new Zend_Form_Element_File('banner');
         $image->setLabel('Event image: ')
                 ->setDestination($fileDest)
                ->setRequired('true');
         
         $submit = new Zend_Form_Element_Submit('submit');
         
         $this->addElements(array($id, $name, $date, $time, $city, $image, $submit));
    }

    
    private function getCities(){
        $all_cities = array();
        $city = new Admin_Model_DbTable_City();
        $cities = $city->fetchAll();
        foreach($cities as $c){
            $state_abbv = $c->name . ' ('. $c->findParentRow('Admin_Model_DbTable_State')->abbv . ')';
            array_push($all_cities, array(
              'key'=> $c->id,
              'value' => $state_abbv 
                ));
        }
        return $all_cities;
    }

}

