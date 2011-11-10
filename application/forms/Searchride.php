<?php

class Application_Form_Searchride extends ZendX_JQuery_Form
{
    protected $_cities ;

    public function init()
    {
        $this->_cities = new Admin_Model_DbTable_City();
        $allCities = $this->_cities->getCityStates();
        /* Form Elements & Other Definitions Here ... */
        
        $depart_city = new ZendX_JQuery_Form_Element_AutoComplete('depart_city', array(
            'label' => 'Leaving from',
            'required' => true
           
        ));
        $depart_city->setJQueryParams(array('source' =>$allCities));
        
        
        $dest_city = new ZendX_JQuery_Form_Element_AutoComplete('dest_city', array(
            'label' => "Going to"
        ));
        
        $dest_city->setJQueryParams(array('source' => $allCities));
  
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
       
       $submit = new Zend_Form_Element_Submit('submit');
       
       $this->setMethod('GET');
       
       $this->addElements(array($depart_city, $dest_city, $trip_date, $trip_time, $submit));
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

