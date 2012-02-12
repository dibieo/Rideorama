<?php

class Application_Form_Searchrequest extends Application_Form_Base
{

  protected $_cities ;

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
        
        $where = new Zend_Form_Element_Radio('driver-where');
        $where->setRequired(true)
               ->setMultiOptions(array('toAirport' => "Going to airport", 'fromAirport' => 'Leaving airport'))
               ->setAttrib('class', 'radio');

       
         $depart_city = new Zend_Form_Element_Text('driver-departure',
          array('label' => 'From'));
        $depart_city->setRequired(true)
                    ->setAttrib('class', 'field');
        
        
        $dest_city = new Zend_Form_Element_Text('driver-destination', array(
            'label' => 'To',
            'required' => true
          
        ));
        $dest_city->setAttrib('class', 'field');
  
       $trip_date = new ZendX_JQuery_Form_Element_DatePicker('driver-date', array(
                 'label' => 'Date',
                  'required' => true,
                  'validators' => array('Date'),
                  'jQueryParams'=> array(
		'dateFormat' => 'mm/dd/yy',
		'minDate'	 => '0')));
       $trip_date->setRequired(true)
                 ->setAttrib('class', 'field');
       
       $trip_time = new Zend_Form_Element_Select('driver-trip_time', array(
           'required' => true, 
           'label' => 'Time'
       ));
       $trip_time->addMultiOptions($this->getTripTimes())
                  ->setAttrib('class', 'field');
         
       $submit = new Zend_Form_Element_Button('findpassengers');
       $submit->setAttrib('onClick', "getPassengers()");
       $submit->setValue('Find passengers');
       
       $requestRide = new Zend_Form_Element_Button('postride');
       $requestRide->setAttrib('onClick', "gotoPostRidePage()");
       $requestRide->setValue('Post a ride');
       
       $this->setMethod('GET');
       $this->addElements(array($where, $depart_city, $dest_city, $trip_date, $trip_time, $submit, $requestRide));
       
       $this->addDisplayGroup(array($where, $depart_city, $dest_city,$trip_date,$trip_time), 'trip_details');
       
       $trip_display_group = $this->getDisplayGroup('trip_details');
       $trip_display_group->setDecorators((array(
                    'FormElements',
                    array('HtmlTag',array('tag'=>'div','class'=>'left_sec'))
        )));
       
//        $this->addDisplayGroup(array($where), 'radio_btns');
//        $wheredisplay = $this->getDisplayGroup('radio_btns');
//        $wheredisplay->setDecorators(array(
//            'FormElements',
//            array ('HtmlTag', array(
//                'tag' => 'div', 
//                'class' => 'top_row'
//            ))
//        ));
        $this->addDisplayGroup(array($submit, $requestRide), 'buttons');
        $submit_buttons_display_group = $this->getDisplayGroup('buttons');
        $submit_buttons_display_group->setDecorators(array (
            
            'FormElements',
            array('HtmlTag', array('tag' => 'ul')),
           array(array('bottom_tab' => 'HtmlTag'), array('tag' => 'div', 'class' => 'bottom_tab')),
           array(array('bottom' => 'HtmlTag'), array('tag' => 'div', 'class' => 'bottom'))
        )
                );
        
        
       //Add decorators
       $submit->setDecorators($this->homepagebuttonsdecorators());
       $requestRide->setDecorators($this->homepagebuttonsdecorators());
       $where->setSeparator("<div class='radio_area right'>");
       $where->setDecorators($this->generateDecoratorsForHomepageSelects());
       $depart_city->setDecorators($this->generateDecorators('span', 'row'));
       $dest_city->setDecorators($this->generateDecorators('span', 'row'));
       $trip_time->setDecorators($this->generateDecorators('span', 'row'));
       $trip_date->setDecorators($this->generateDecoratorsJQuery('span', 'row'));
       
       $this->setDecorators(array(
           'FormElements'
       ));
       
    }

}

