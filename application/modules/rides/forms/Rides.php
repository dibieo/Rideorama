<?php

class Rides_Form_Rides extends ZendX_JQuery_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
        
        $id = new Zend_Form_Element_Hidden('id');
        
        $from = new Zend_Form_Element_Text('departure'); 
        $from->setRequired('true')
                    ->setAttrib('placeholder', 'Enter where you would be leaving from')
                    ->setLabel('Departure location')
                   ->addValidator('NotEmpty')
                   ->addFilters(array('StripTags', 'StringTrim'));
        
       
       $to = new ZendX_JQuery_Form_Element_AutoComplete('destination', array(
            'label' => 'Destination location',
             'placeholder' => 'Enter airport name',
            'required' => true
          
           
        ));
        $to->setJQueryParams(array('source' =>$this->getAirports()));
        
        
        $num_seats = new Zend_Form_Element_Text('num_seats');
        $num_seats->setLabel('Enter the number of seats')
                  ->setRequired(true)
                  ->addValidator(new Zend_Validate_Between(array('min' => 1,
                                                                 'max' => 5)));
       $submit = new Zend_Form_Element_Submit('submit');
           $submit->setLabel('Post ride')
                ->setAttrib('id', 'submitbutton');
         
        
        $this->addElements(array($id, $from, $to, $num_seats, $submit));
    }


     private function getAirports(){
         
        $airport = new Admin_Model_AirportService();
        $all_airports = array();
        $aiports = $airport->getAllAirports();
        foreach($aiports as $a){
            array_push($all_airports, array(
              'key'=> $a->id,
              'value' => $a->name
                ));
        }
        return $all_airports;
    
   }
}

