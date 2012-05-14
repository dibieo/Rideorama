<?php

class Admin_Form_State extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
      $id = new Zend_Form_Element_Hidden('id');
      $name = new Zend_Form_Element_Text('name');
      $name->setLabel('State')
           ->setRequired('true')
           ->addFilters(array('StripTags', 'StringTrim', 'StringToLower'))
           ->addValidator('NotEmpty');
      
      $abbv = new Zend_Form_Element_Text('abbv');
      $abbv->setLabel('State abbreviation')
           ->setRequired('true')
           ->addFilters(array('StripTags', 'StringTrim', 'StringToUpper'))
           ->addValidator('NotEmpty');
      
      $submit = new Zend_Form_Element_Submit('submit');
      $submit->setLabel('Add State');
      $submit->setAttrib('id', 'submitbutton');
      
      $this->setMethod('POST');
      $this->addElements(array($id, $name, $abbv, $submit));
    }


}

