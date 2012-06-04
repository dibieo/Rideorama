<?php

class Account_Form_Editprofile extends Application_Form_Base
{

    public function init()
    {
          /* Form Elements & Other Definitions Here ... */
        
      $alpha = new Zend_Validate_Alpha(array('allowWhiteSpace' => true));

       $id = new Zend_Form_Element_Hidden('id');
       $firstName = new Zend_Form_Element_Text('first_name', array(
           'required' => true,
           'class' => 'input',
           'label' => 'First Name',
          'filters'=> array('StringTrim', 'StripTags'),
           'validators' => array('Alpha')
       ));
       $firstName->setAttrib('placeholder', 'First name');
       $firstName->setDecorators($this->generateDecorators());
       
       $lastName = new Zend_Form_Element_Text('last_name', array(
           'required' => true,
           'class' => 'input',
           'label' => 'Last Name',
           'filters'=> array('StringTrim', 'StripTags'),
           'validators' => array('Alpha')
       ));
       $lastName->setAttrib('placeholder', 'Last Name');
       
       $lastName->setDecorators($this->generateDecorators());
       
       $emailValidator = new Zend_Validate_EmailAddress();
       $alpahValidator = new Zend_Validate_Alpha();
       
       $email = new Zend_Form_Element_Text('email', array(
           'required' => true,
           'class' => 'input',
           'label' => 'Email Address'
       ));
       $email->addFilters(array('StringTrim', 'StripTags'))
             ->addValidator($emailValidator)
             ->setDecorators($this->generateDecorators());
       
       $paypalemail = new Zend_Form_Element_Text('paypal_email', array(
           'class' => 'input',
           'label' => 'Paypal Email'
       ));
       $paypalemail->addFilters(array('StringTrim', 'StripTags'))
             ->addValidator($emailValidator);
       $paypalemail->setDecorators($this->generateDecorators());

      $occupation = new Zend_Form_Element_Text('occupation', array(
           'required' => true,
           'class' => 'input',
           'label' => 'Occupation',
           'filters'=> array('StringTrim', 'StripTags'),
           'validators' => array($alpha)
       ));
       $occupation->setDecorators($this->generateDecorators());
       $strlength = new Zend_Validate_StringLength(array(
           'min' => 10,
           'max' => 10
       ));
       $phone = new Zend_Form_Element_Text('phone', array(
           'required' => true,
           'class' => 'input',
           'label' => 'Phone number',
           'filters'=> array('StringTrim', 'StripTags'),
           'validators' => array(new Application_Model_PhoneValidator())
       ));
       $phone->setDecorators($this->generateDecorators());
       
       $ageValidator = $this->getAgeValidator();
       
       $age = new Zend_Form_Element_Text('age', array(
           'required' => true,
           'class' => 'input',
           'label' => 'Age',
           'filters'=> array('StringTrim', 'StripTags'),
           'validators' => array('Int', $ageValidator)
       ));
      $age->setDecorators($this->generateDecorators());
       $save = new Zend_Form_Element_Submit('save');
       $save->setLabel('save')
                ->setAttribs(array('id' => 'submitbutton', 
                                    'class' =>  'btn',
                                    'onmouseout' => 'this.className=("btn")',
                                    'onmouseover' => 'this.className=("btn_hover")'));
       
        $profilepicfileDest = realpath(APPLICATION_PATH . '/../public/img/users/'); //Destination for user car images
        $user_profile_pic = new Zend_Form_Element_File('profile_pic');
         $user_profile_pic->setLabel('Profile picture')
                            ->setDestination($profilepicfileDest);
         
         $user_profile_pic->addValidator('Extension', false, 'jpg,png,gif') 
                ->addValidator('Size', false, '4096kB')
                ->addFilter('Rename', array(
             'target' => $profilepicfileDest,
             'overwrite' => true
         ));

       $user_profile_pic->setDecorators($this->getFileDecorators("form_row"));
       
       $save->setDecorators($this->submitDecorator('btn_row'));
   
       $this->addELements(array($id, $firstName, $lastName, $email, $paypalemail, $user_profile_pic, $occupation, $phone, $age, $save));
     
       
    $this->setDecorators($this->getFormDescriptionDecorators());
       $this->setDescription("Edit your profile");
     }


}

