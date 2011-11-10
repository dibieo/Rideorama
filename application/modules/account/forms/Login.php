<?php

class Account_Form_Login extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
        
        $email  = new Zend_Form_Element_Text('email', array(
            'label' => 'Email address',
            'required' => true,
             'filters' => array('StringTrim', 'StripTags')
      
        ));
        
        $password = new Zend_Form_Element_Password('password', array(
            'label' => 'Password',
            'required' => true,
            'filters' => array ('StringTrim', 'StripTags')
            
        ));
        
        $frontController = Zend_Controller_Front::getInstance();
        $url = "http://" .$_SERVER['SERVER_NAME'] . $frontController->getRequest()->getRequestUri();
        
        $this->addElement('hidden', 'return', array(
        'value' => $url
        ));
        echo $url;
	
        $submit = new Zend_Form_Element_Submit('login');
        $submit->setLabel('login')
                ->setAttrib('id', 'submitbutton');
        
        $this->addElements(array($email, $password, $submit));
    }


}

