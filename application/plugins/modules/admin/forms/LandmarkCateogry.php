<?php

class Admin_Form_LandmarkCateogry extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
        
        $id = new Zend_Form_Element_Hidden('id');
        $name = new Zend_Form_Element_Text('name');
        $name->addValidator('NotEmpty')
              ->setLabel('Category name:')
             ->setRequired(true)
             ->addFilters(array('StripTags', 'StringTrim'));
        
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setAttrib('id', 'submitbutton');
        
        $this->addElements(array($id, $name, $submit));
    }


}

