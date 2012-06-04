<?php

class Requests_Form_Request extends Application_Form_Base
{

    public function init()
    {
     $OnlyAlnumFilter = new Zend_Filter_Alnum(true);

       $from = new ZendX_JQuery_Form_Element_AutoComplete('departure', array(
           
                'label' => 'Departure',
                'required' => true
            ));
        $from->setJQueryParams(array('source' =>''));
        $from->addFilter($OnlyAlnumFilter);
        
       $to = new ZendX_JQuery_Form_Element_AutoComplete('destination', array(
           
                'label' => 'Destination',
                'required' => true
            ));
        $to->setJQueryParams(array('source' =>''));

        $to->addFilter($OnlyAlnumFilter);
        
       //Hidden field to determine whether to allow posting of return trips
        $return = new Zend_Form_Element_Hidden('return');
        $return->setValue("true");
        
       $trip_date = new ZendX_JQuery_Form_Element_DatePicker('trip_date', array(
                 'label' => 'Date',
                  'required' => true,
                  'validators' => array($this->getDateValidator()),
                  'jQueryParams'=> array(
		'dateFormat' => 'mm/dd/yy',
		'minDate'	 => '0')));
       
       
        //Phone validator
        $phone_num = new Zend_Form_Element_Text('phone_num');
        $phone_num->setLabel('Cell number')
                  ->setRequired(true)
                  ->setAttrib('placeholder', 'Enter your cellphone number')
                  ->addValidator(new Application_Model_PhoneValidator());
        
     
       $trip_time = new Zend_Form_Element_Text('trip_time');
       $trip_time->setLabel('Pickup time')
                  ->setRequired(true)
                  ->setAttrib('class', 'input')
                  ->setAttrib('id', 'trip_time');
        
        $range = new Zend_Validate_GreaterThan(1);
        $range->setMessage("You must offer greater than $1");
        
        $trip_cost = new Zend_Form_Element_Text('trip_cost');
        $trip_cost->setLabel('Willing to offer')
                  ->setAttrib('placeholder', 'Enter how much you are willing to pay for this ride')
                  ->addValidators(array('NotEmpty', $range))
                  ->setValue(20)
                  ->setRequired(true);
        
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
       
       
       $this->setMethod('post');
    //   $this->setAction('index/search');
          
     $facebook_checkbox = new Zend_Form_Element_Checkbox('facebook',
              array(
                  'Label' => 'Share on facebook',
                  'required' => true
              )
         );
        
        $facebook_checkbox->setCheckedValue('true')
                          ->setUncheckedValue('false')
                          ->setChecked(true);
       
        $other_elems_display_group = array(); // The elements in the other elements display group.
        $this->addElements(array($from, $to, $trip_date, $trip_time,
                                $trip_cost,$luggage, $luggage_size,$trip_msg, $return, $submit));
        
        $this->addDisplayGroup(array($luggage, $luggage_size), 'luggages_display');
        $fb = new Application_Model_FacebookService();
        $fbsession = $fb->loggedIn();
     
        
        
       $user_obj = new Account_Model_UserService();
      $user = $user_obj->getUser(Zend_Auth::getInstance()->getIdentity()->id);
  
     //If user does not have a telephone
     if ($user->telephone == null){
                   $this->addElement($phone_num);
                   array_push($other_elems_display_group, $phone_num);   
               }
        
    array_push($other_elems_display_group, $trip_msg);
    
    if ($fbsession){
               array_push($other_elems_display_group, $facebook_checkbox);
               //Ok We are connected to facebook, add facebook checkbox
               $this->addElement($facebook_checkbox);
      }
      
     //add submit button to the display group array
    array_push($other_elems_display_group, $submit);
    
    //add elements to display group finally
    $this->addDisplayGroup($other_elems_display_group, 'other_elems');
    
    
    //Add Number of luggages and size to a display group
    
    
     $luggages_display = $this->getDisplayGroup('luggages_display');
     
     $luggages_display->setDecorators(array(
                    'FormElements',
                    array('HtmlTag',array('tag'=>'div','class'=>'form_row'))
        ));
     
     $other_elems_display = $this->getDisplayGroup('other_elems');
     $other_elems_display->setDecorators(array(
            'FormElements'
     ));
    
    $from->setAttrib('class', 'input')->setDecorators($this->generateDecoratorsJQuery());
    $to->setAttrib('class', 'input')->setDecorators($this->generateDecoratorsJQuery());
    $trip_cost->setAttrib('class', 'input')->setDecorators($this->generateDecorators());
    $trip_time->setDecorators($this->generateDecoratorsForSelects('form_row'));
    $phone_num->setAttrib('class', 'input')->setDecorators($this->generateDecorators());
    $trip_msg->setDecorators($this->generateDecorators());
    $facebook_checkbox->setAttrib('class', 'radio')->setDecorators($this->generateDecorators('form_row1 last'));
    $trip_date->setAttrib('class', 'input')->setDecorators($this->generateDecoratorsJQuery());
    $submit->setDecorators($this->submitDecorator());
    $luggage->setDecorators($this->generateLuggageDecorators('small_box'));
    $luggage_size->setDecorators($this->generateLuggageDecorators('select_box'));
    
   
    $this->setDecorators($this->getFormDescriptionDecorators());
     $this->setDescription('Request a ride');
       
    }


}

