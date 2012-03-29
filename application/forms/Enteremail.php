<?php

class Application_Form_Enteremail extends Application_Form_Base
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
    
     $email_validator = new Zend_Validate_EmailAddress();
     $email  = new Zend_Form_Element_Text('email', array(
            'label' => 'Email address',
            'required' => true,
            'class' => 'input',
            'validators' => array($email_validator),
            'title' => 'Enter your email address',
             'filters' => array('StringTrim', 'StripTags')
      
        ));
     
     $email->setDecorators($this->generateDecorators());  
     
     $submit = new Zend_Form_Element_Submit('enter');
        $submit->setLabel('Enter')
                ->setAttrib('class', 'btn')
                ->setAttrib('onmouseout', 'this.className=("btn")')
                ->setAttrib('onmouseover', 'this.className=("btn_hover")')
                ->setAttrib('id', 'submitbutton');
        
       $submit->setDecorators($this->submitDecorator());
     
     $this->setMethod('POST');
     $this->addElements(array($email, $submit));
    }


}

