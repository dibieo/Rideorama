<?php

class Requests_Form_Request extends Application_Form_Base
{

    public function init()
    {
     $OnlyAlnumFilter = new Zend_Filter_Alnum(true);


       $from = new Zend_Form_Element_Text('departure');
        $from->setRequired(true)
             ->setLabel('Departure')
             ->addFilter($OnlyAlnumFilter);
         
       $to = new Zend_Form_Element_Text('destination');
        $to->setRequired(true)
           ->setLabel('Destination')
            ->addFilter($OnlyAlnumFilter);
        
       $trip_date = new ZendX_JQuery_Form_Element_DatePicker('trip_date', array(
                 'label' => 'Date',
                  'required' => true,
                  'validators' => array('Date'),
                  'jQueryParams'=> array(
		'dateFormat' => 'yy-mm-dd',
		'minDate'	 => '0')));
        
       $trip_time = new Zend_Form_Element_Text('trip_time');
        $trip_time->setLabel('Departure time')
                   ->setRequired(true);
        
        
        $trip_cost = new Zend_Form_Element_Text('trip_cost');
        $trip_cost->setLabel('How much are you offering:')
                  ->addValidators(array('NotEmpty'))
                  ->setRequired(true);
        
        $trip_msg = new Zend_Form_Element_Textarea('trip_msg');
        $trip_msg->setLabel('Trip message')
                  ->setAttrib('cols', '25')
                   ->setAttrib('rows', '7');
        
       $submit = new Zend_Form_Element_Submit('submit');
       $submit->setLabel('Request a ride');
       
       
       $this->setMethod('post');
    //   $this->setAction('index/search');
       
       $this->addElements(array($from, $to, $trip_date, $trip_time,
                                $trip_cost,$trip_msg,  $submit));
    }


}

