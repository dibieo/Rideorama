<?php

/**
 * @todo Change the getStates and makeStates methods to work
 * with Doctrine Entity instead
 */
class Admin_Form_City extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
        
        $id = new Zend_Form_Element_Hidden('id');
        $city_name = new Zend_Form_Element_Text('name');
        $city_name->setRequired('true')
                    ->setAttrib('placeholder', 'City name')
                   ->addValidator('NotEmpty')
                   ->addFilters(array('StripTags', 'StringTrim'));
        
        $state_id = new Zend_Form_Element_Select('state_id');
        $state_id->setLabel('State');
        $state_id->addMultiOptions($this->makeStatesArray());
        
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setAttrib('id', 'submitbutton');
        
        $this->addElements(array($id, $city_name, $state_id, $submit));
    }

    private function getStates(){
        
        $states = new Admin_Model_DbTable_State();
       return $states->getStates();
    }
    
    private function makeStatesArray(){
       $states_array = array();
       $states = $this->getStates();
       
       foreach($states as $state){
          
          array_push($states_array, array(
              'key'=> $state->id,
              'value' => $state->name
          ));
       }
       return $states_array;
    }

}

