<?php

class Application_Form_Searchride extends Application_Form_Base
{
    protected $_cities ;

    public function init()
    {
        
        $where = new Zend_Form_Element_Radio('where');
        $where->setRequired(true)
               ->setMultiOptions(array('toAirport' => "Going to airport", 'fromAirport' => 'Leaving airport'));

        /* Form Elements & Other Definitions Here ... */
        
        $depart_city = new Zend_Form_Element_Text('departure',
          array('label' => 'From'));
        $depart_city->setRequired(true)
                    ->setAttrib('class', 'field');
        
        
        $dest_city = new Zend_Form_Element_Text('destination', array(
            'label' => 'To',
            'required' => true,
            'class' => 'field'
          
        ));
      //  $dest_city->setJQueryParams(array('source' => $this->getAirports()));
  
       $trip_date = new ZendX_JQuery_Form_Element_DatePicker('trip_date', array(
                 'label' => 'Date',
                  'required' => true,
                  'class' => 'field',
                  'validators' => array('Date'),
                  'jQueryParams'=> array(
		'dateFormat' => 'mm/dd/yy',
		'minDate'	 => '0')));
       
       
       $trip_time = new Zend_Form_Element_Select('trip_time', array(
           'required' => true, 
           'label' => 'Time'
       ));
       $trip_time->addMultiOptions($this->getTripTimes())
                 ->setAttrib('class', 'field');
;
       
       $submit = new Zend_Form_Element_Button('findrides');
       $submit->setAttrib('onClick', "findRides()");
       $submit->setLabel('Find a ride');
       
       $requestRide = new Zend_Form_Element_Button('request');
       $requestRide->setAttrib('onClick', 'gotoRequestRidePage()');
       $requestRide->setLabel('Request a ride');
       
       $this->setMethod('GET');
    //   $this->setAction('index/search');
       
       $this->addElements(array($where, $depart_city, $dest_city, $trip_date, $trip_time, $submit, $requestRide));
       
       //set decorators
       $depart_city->setDecorators($this->generateDecorators('span', 'row'));
       $dest_city->setDecorators($this->generateDecorators('span', 'row'));
       $trip_time->setDecorators($this->generateDecorators('span', 'row'));
       $trip_date->setDecorators($this->generateDecoratorsJQuery('span', 'row'));
    }
   
       
}

