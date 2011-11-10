<?php

class Account_Form_User extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
        
       $id = new Zend_Form_Element_Hidden('id');
       $firstName = new Zend_Form_Element_Text('first_name', array(
           'required' => true,
           'label' => 'First Name'
       ));
       $firstName->setAttrib('placeholder', 'First name');
       $lastName = new Zend_Form_Element_Text('last_name', array(
           'required' => true,
           'label' => 'Last Name',
           'filters'=> array('StringTrim', 'StripTags')
       ));
       
       $emailValidator = new Zend_Validate_EmailAddress();
       
       $email = new Zend_Form_Element_Text('email');
       $email->setRequired(true)
               ->setLabel('Email')
               ->addFilters(array('StringTrim', 'StripTags'))
             ->addValidator($emailValidator);
       
       $profession = new Zend_Form_Element_Text('profession', array(
           'label' => 'Profession',
           'required' => true,
           'filters' => array ('StringTrim', 'StripTags')
       ));
       $profession->addFilters(array('StringTrim', 'StripTags'));
       
       $sex = new Zend_Form_Element_Select('sex');
       $sex->setRequired(true)
           ->setLabel('Sex')
           ->addMultiOptions(array(
               "Male" => "Male",
               "Female" => "Female"
           ));
       $password = new Zend_Form_Element_Password('password', array(
           'required' => true,
           'label' => 'Password'
       ));
       
        $confirm_password = new Zend_Form_Element_Password('confirm_password', array(
        'required' => true,
        'label' => 'Confirm Password'
       ));
        
        $fileDest = realpath(APPLICATION_PATH . '/../public/img/users'); //Destination for user images
         $image = new Zend_Form_Element_File('profile_pic');
         $image->setLabel('Profile picture')
                 ->setDestination($fileDest);
         
       $register = new Zend_Form_Element_Submit('register');
       $register->setLabel('Register')
                ->setAttrib('id', 'submitbutton');
       
       $this->addELements(array($id, $firstName, $lastName, $sex, $email, $password, $profession,$confirm_password, $image, $register));
    }


}

