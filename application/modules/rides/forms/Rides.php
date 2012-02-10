<?php

class Rides_Form_Rides extends Application_Form_Base 
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
        $this->setMethod('post');
        
        $OnlyAlnumFilter = new Zend_Filter_Alnum(true);

        $id = new Zend_Form_Element_Hidden('id');
        
        $from = new ZendX_JQuery_Form_Element_AutoComplete('departure', array(
           
                'label' => 'Departure',
                'required' => true
            ));
        $from->setJQueryParams(array('source' =>''))
            ->addFilter($OnlyAlnumFilter)
            ->addValidators(array('NotEmpty'));
        
        $to = new ZendX_JQuery_Form_Element_AutoComplete('destination', array(
           
                'label' => 'Destination',
                'required' => true
            ));
        $to->setJQueryParams(array('source' =>''))
            ->addFilter($OnlyAlnumFilter)
            ->addValidators(array('NotEmpty'));
        
        
        $num_seats = new Zend_Form_Element_Text('num_seats');
        $num_seats->setLabel('Number of seats')
                  ->setRequired(true)
                  ->addValidator(new Zend_Validate_Between(array('min' => 1,
                                                                 'max' => 5)));
        
        $luggage = new Zend_Form_Element_Select('luggage');
        $luggage->setLabel('Luggage')
                ->setRequired(true)
                ->setAttrib('class', 'select')
                ->addMultiOptions(array(
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5'
                ));
        
        $luggage_size = new Zend_Form_Element_Select('luggage_size');
        $luggage_size ->setAttrib('class', 'select right')
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
                  ->setRequired(true)
                  ->setValue(10);
        
        
        $trip_time = new Zend_Form_Element_Select('trip_time');
        $trip_time->setLabel('Departure time')
                   ->setRequired(true)
                    ->setAttrib('class', 'select1')
                   ->addMultiOptions(array(
                       "10:00:00" => "10:00 am",
                       "10:30:00" => "10:30 am"
                   ));
        
        $trip_msg = new Zend_Form_Element_Textarea('trip_msg');
        $trip_msg->setLabel('Trip message')
                  ->setAttrib('cols', '25')
                  ->setAttrib('class', 'textarea')
                   ->setAttrib('rows', '7');
        
       $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Submit')
                ->setAttrib('class', 'btn')
                ->setAttrib('onmouseout', "this.className=('btn')")
                ->setAttrib('onmouseover', "this.className=('btn_hover')");
         
        $facebook_checkbox = new Zend_Form_Element_Checkbox('facebook',
              array(
                  'Label' => 'Share on facebook',
                  'required' => true
              )
         );
        
        
        $facebook_checkbox->setCheckedValue('true')
                          ->setUncheckedValue('false')
                          ->setChecked(true);
        //Hidden field to determine whether to allow posting of return trips
        $return = new Zend_Form_Element_Hidden('return');
        $return->setValue("true");
        
    //Add Number of luggages and size to a display group
        
        if (Zend_Auth::getInstance()->getIdentity()->facebook_login == "true"){
            
               $this->addElements(array($id, $from, $to, $trip_date,$trip_time, $num_seats, $trip_cost,
                                    $luggage, $luggage_size,
                                    $trip_msg, $return, $facebook_checkbox, $submit));
        } else{
                 $this->addElements(array($id, $from, $to,
                                    $trip_date, $trip_time, $num_seats, $trip_cost,
                                    $luggage, $luggage_size,
                                    $trip_msg, $return, $submit));
    }
    
    
    //Add Number of luggages and size to a display group
    $this->addDisplayGroup(array($luggage, $luggage_size), 'luggages_display');
    
    $this->addDisplayGroup(array($trip_msg, $facebook_checkbox, $submit), 'other_elems');
    
    
     $luggages_display = $this->getDisplayGroup('luggages_display');
     $luggages_display->setDecorators(array(
                    'FormElements',
                    array('HtmlTag',array('tag'=>'div','class'=>'form_row'))
        ));
     
     $other_elems_display = $this->getDisplayGroup('other_elems');
     $other_elems_display->setDecorators(array(
            'FormElements'
     ));
    // Work on adding decorators to the form now
    
    $from->setAttrib('class', 'input')->setDecorators($this->generateDecoratorsJQuery());
    $to->setAttrib('class', 'input')->setDecorators($this->generateDecoratorsJQuery());
    $trip_cost->setAttrib('class', 'input')->setDecorators($this->generateDecorators());
    $num_seats->setAttrib('class', 'input')->setDecorators($this->generateDecorators());
    $trip_msg->setDecorators($this->generateDecorators());
    $facebook_checkbox->setAttrib('class', 'radio')->setDecorators($this->generateDecorators('form_row1 last'));
    $trip_date->setAttrib('class', 'input')->setDecorators($this->generateDecoratorsJQuery());
    $submit->setDecorators($this->submitDecorator());
    $luggage->setDecorators($this->generateDecorators('small_box'));
    $luggage_size->setDecorators($this->generateDecorators('select_box'));
    $trip_time->setDecorators($this->generateDecoratorsForSelects('form_row'));
    
   
    $this->setDecorators(array(
             array('Description', array('tag' => 'h3', 'class' => '')),
             'FormElements' ,
             array(array('elementDiv' => 'HtmlTag'), array('tag' => 'section', 'class' => "post_box")),
              array(array('td' => 'HtmlTag'), array('tag' => 'fieldset', 'placement' => 'REPLACE')),
             'Form'
        ));
     $this->setDescription('Post a ride');
}
}

