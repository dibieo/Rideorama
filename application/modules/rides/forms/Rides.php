<?php

class Rides_Form_Rides extends Application_Form_Base 
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
        $this->setMethod('post');
        
        $OnlyAlnumFilter = new Zend_Filter_Alnum(true);

        $id = new Zend_Form_Element_Hidden('id');
        
        $from = new Zend_Form_Element_Text('departure'); 
        $from->setRequired('true')
                    ->setAttrib('placeholder', 'Enter where you would be leaving from')
                    ->setLabel('Departure')
                   ->addValidators(array('NotEmpty'))
                   ->addFilters(array('StripTags', 'StringTrim', $OnlyAlnumFilter));
        
       
       $to = new ZendX_JQuery_Form_Element_AutoComplete('destination', array(
            'label' => 'Destination',
             'placeholder' => 'Enter airport name',
            'required' => true
          
           
        ));
       
        $to->setJQueryParams(array('source' =>$this->getAirports()));
        $to->addFilter($OnlyAlnumFilter);
        
        
        $num_seats = new Zend_Form_Element_Text('num_seats');
        $num_seats->setLabel('Enter the number of seats')
                  ->setRequired(true)
                  ->addValidator(new Zend_Validate_Between(array('min' => 1,
                                                                 'max' => 5)));
        
        $luggage = new Zend_Form_Element_Text('luggage');
        $luggage->setLabel('Number of luggages')
                ->setRequired(true)
                ->setAttrib('value', 1);
        
        $luggage_size = new Zend_Form_Element_Select('luggage_size');
        $luggage_size->setLabel('Size of luggages')
                     ->addMultiOptions(array(
        "small" => "small",
         "medium" => "medium",
          "large" => "large"
        ));
        
        $trip_date = new ZendX_JQuery_Form_Element_DatePicker('trip_date', array(
                 'label' => 'Trip date',
                  'required' => true,
                  'validators' => array('Date'),
                  'jQueryParams'=> array(
		'dateFormat' => 'yy-mm-dd',
		'minDate'	 => '0')));
        
        $trip_cost = new Zend_Form_Element_Text('trip_cost');
        $trip_cost->setLabel('Cost per seat')
                  ->setRequired(true);
        
        $trip_time = new Zend_Form_Element_Text('trip_time');
        $trip_time->setLabel('Departure time')
                   ->setRequired(true);
        
        $trip_msg = new Zend_Form_Element_Textarea('trip_msg');
        $trip_msg->setLabel('Trip message')
                  ->setAttrib('cols', '25')
                   ->setAttrib('rows', '7');
        
       $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Post ride')
                  
                ->setAttrib('id', 'submitbutton');
         
        
        $this->addElements(array($id, $from, $to, $num_seats, $trip_cost,
                                    $luggage, $luggage_size,
                                    $trip_msg, $trip_date, 
                                    $trip_time,$submit));
    }

}

