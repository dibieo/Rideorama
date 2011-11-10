<?php

class Events_Form_Events extends ZendX_JQuery_Form
{
 public function init()
    {
        /* Form Elements & Other Definitions Here ... */
        
        /* Form Elements & Other Definitions Here ... */
         $id = new Zend_Form_Element_Hidden('id');
         $name = new Zend_Form_Element_Text('name');
         $name->setLabel('Event name:')
               ->setRequired(true);
         
         $date = new ZendX_JQuery_Form_Element_DatePicker('event_date', array(
                 'label' => 'Event Date',
                  'required' => true,
                  'validators' => array('Date'),
                  'jQueryParams'=> array(
		'dateFormat' => 'yy-mm-dd',
		'minDate'	 => '0')));
         
         $time = new Zend_Form_Element_Text('event_time', array(
             'label' => 'Event Time',
             'required' => true
         ));
         
         $cities = new Admin_Model_DbTable_City();
         $city = new Zend_Form_Element_Select('location_id');
         $city->setLabel('Event city');
         $city->addMultiOptions($cities->getCityStates());
         
         $fileDest = realpath(APPLICATION_PATH . '/../public/img');
         $image = new Zend_Form_Element_File('banner');
         $image->setLabel('Event image: ')
                 ->setDestination($fileDest)
                ->setRequired('true');
         
         $submit = new Zend_Form_Element_Submit('submit');
         
         $this->addElements(array($id, $name, $date, $time, $city, $image, $submit));
    }



}

