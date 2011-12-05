<?php

class Application_Form_Searchride extends Application_Form_Base
{
    protected $_cities ;

    public function init()
    {
        $this->_cities = new Admin_Model_DbTable_City();
        $allCities = $this->_cities->getCityStates();
        /* Form Elements & Other Definitions Here ... */
        
        $depart_city = new ZendX_JQuery_Form_Element_AutoComplete('departure',
          array('label' => 'From'));
        $depart_city->setJQueryParams(array('source'=>$this->getAirports()))
                    
                    ->setRequired(true);
        
        
        
        
        $dest_city = new ZendX_JQuery_Form_Element_AutoComplete('destination', array(
            'label' => "Going to"
        ));
        
        $dest_city->setJQueryParams(array('source' => $this->getAirports()));
  
       $trip_date = new ZendX_JQuery_Form_Element_DatePicker('trip_date', array(
                 'label' => 'Date',
                  'required' => true,
                  'validators' => array('Date'),
                  'jQueryParams'=> array(
		'dateFormat' => 'yy-mm-dd',
		'minDate'	 => '0')));
       
       $trip_time = new Zend_Form_Element_Select('trip_time', array(
           'required' => true, 
           'label' => 'Time'
       ));
       $trip_time->addMultiOptions($this->getTripTimes());
       
        $where = new Zend_Form_Element_Hidden('where');
        $where->setValue('toAirport');
         
       $submit = new Zend_Form_Element_Submit('submit');
       $submit->setLabel('Find a ride');
       
       $requestRide = new Zend_Form_Element_Button('request');
       $requestRide->setAttrib('onClick', gotoPostRidePage());
       $requestRide->setLabel('Request a ride');
       
       $this->setMethod('GET');
       $this->setAction('index/search');
       
       $this->addElements(array($depart_city, $dest_city, $trip_date, $trip_time, $where, $submit, $requestRide));
    }

    /**
     * get all the trip times
     * @return type Array of tripTimes
     */
    
    private function getTripTimes(){
        
       return array(
            'anytime' => 'anytime',
            'morning' => 'morning',
            'afternoon' => 'afternoon',
            'evening' => 'evening'
           
        );
    }
       
//     private function search($term){
//         
//         $filter = function($city) use ($term)
//         {
//             if (ststr($city, $term))
//                     return true;
//             return false;
//         };
//         
//         return array_filter($this->)
//    }

}

