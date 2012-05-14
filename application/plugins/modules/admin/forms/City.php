<?php

/**
 * @todo Change the getStates and makeStates methods to work
 * with Doctrine Entity instead
 */
class Admin_Form_City extends Application_Form_Base 
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



}

