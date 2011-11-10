<?php

class Admin_Form_Airport extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
    
        $id = new Zend_Form_Element_Hidden('id');
        $airport_name = new Zend_Form_Element_Text('name');
        $airport_name->setRequired('true')
                    ->setAttrib('placeholder', 'City name')
                    ->setLabel('Name')
                   ->addValidator('NotEmpty')
                   ->addFilters(array('StripTags', 'StringTrim'));
        
        $city_id = new Zend_Form_Element_Select('city_id');
        $city_id->setLabel('City');
        $city_id->addMultiOptions($this->makeCityArray());
        
        $iata = new Zend_Form_Element_Text('iata');
        $iata->setRequired('true')
             ->setLabel('IATA')
             ->addFilters(array('StripTags', 'StringTrim'));
        
        $path = realpath(APPLICATION_PATH . '/../public/img/airports');

        $pic = new Zend_Form_Element_File('pic');
        $pic->setDestination($path)
            ->setRequired(true)
            ->setLabel('Airport picture')
            ->addValidator('Extension', false, 'jpg,png,gif')
            ->addValidator('Count', false, 1);
            
        
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setAttrib('id', 'submitbutton');
        
        $this->addElements(array($id, $airport_name, $iata, $pic, $city_id, $submit));
    }

    private function getCities(){
        
       $cities = new Admin_Model_CityService();
       return $cities->getAllCities();
    }
    
    private function makeCityArray(){
       $cities_array = array();
       $cities = $this->getCities();
       
       foreach($cities as $city){
          
          array_push($cities_array, array(
              'key'=> $city->id,
              'value' => $city->name . ',  ' . $city->state->abbv
          ));
       }
       return $cities_array;
    }


}

