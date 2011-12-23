<?php

class Application_Form_Searchrequest extends Application_Form_Base
{

  protected $_cities ;

    public function init()
    {
        $this->_cities = new Admin_Model_DbTable_City();
        $allCities = $this->_cities->getCityStates();
        /* Form Elements & Other Definitions Here ... */
        
        $where = new Zend_Form_Element_Radio('driver-where');
        $where->setRequired(true)
               ->setMultiOptions(array('toAirport' => "Going to airport", 'fromAirport' => 'Leaving airport'));

         $depart_city = new Zend_Form_Element_Text('driver-departure',
          array('label' => 'From'));
        $depart_city->setRequired(true);
        
        
        $dest_city = new Zend_Form_Element_Text('driver-destination', array(
            'label' => 'To',
            'required' => true
          
        ));
  
       $trip_date = new ZendX_JQuery_Form_Element_DatePicker('driver-date', array(
                 'label' => 'Date',
                  'required' => true,
                  'validators' => array('Date'),
                  'jQueryParams'=> array(
		'dateFormat' => 'yy-mm-dd',
		'minDate'	 => '0')));
       $trip_date->setRequired(true);
       
       $trip_time = new Zend_Form_Element_Select('driver-trip_time', array(
           'required' => true, 
           'label' => 'Time'
       ));
       $trip_time->addMultiOptions($this->getTripTimes());
         
       $submit = new Zend_Form_Element_Submit('submit');
       $submit->setLabel('Find passengers');
       
       $requestRide = new Zend_Form_Element_Button('request');
       $requestRide->setAttrib('onClick', 'gotoPostRidePage()');
       $requestRide->setLabel('Post a ride');
       
       $this->setMethod('GET');
       $this->addElements(array($where, $depart_city, $dest_city, $trip_date, $trip_time, $submit, $requestRide));
    }

}

