<?php

class Account_Form_Login extends Application_Form_Base {

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
      
        $email  = new Zend_Form_Element_Text('email', array(
            'label' => 'Email address',
            'required' => true,
            'class' => 'input',
            'title' => 'Enter your email address',
             'filters' => array('StringTrim', 'StripTags')
      
        ));
         $email->setDecorators($this->generateDecorators());
        $password = new Zend_Form_Element_Password('password', array(
            'label' => 'Password',
            'required' => true,
            'class' => 'input',
            'filters' => array ('StringTrim', 'StripTags')
            
        ));
        $password->setDecorators($this->generateDecorators());
        
       $foo = new Zend_Form_Element_Hidden('name');
    $foo->setDescription('<span class="forget"><a href="#">Forgot password?</a></span>')
    ->setDecorators(array(
        'ViewHelper',
        array('Description', array('escape' => false, 'tag' => false))
      
      ));

        $submit = new Zend_Form_Element_Submit('sigin');
        $submit->setLabel('Sign in')
                ->setAttrib('class', 'btn')
                ->setAttrib('onmouseout', 'this.className=("btn")')
                ->setAttrib('onmouseover', 'this.className=("btn_hover")')
                ->setAttrib('id', 'submitbutton');
        
       $submit->setDecorators($this->submitDecorator());
        
        $this->addElements(array($email, $password,$foo, $submit));
    }

}

