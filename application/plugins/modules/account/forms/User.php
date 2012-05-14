<?php

/**
 *  This form handles user registration
 * 
 */
class Account_Form_User extends Application_Form_Base
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
        
       $id = new Zend_Form_Element_Hidden('id');
       $firstName = new Zend_Form_Element_Text('first_name', array(
           'required' => true,
           'class' => 'input',
           'label' => 'First Name',
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
           'placeholder' => 'Enter your email address',
           'label' => 'Email Address'
       ));
       $email->addFilters(array('StringTrim', 'StripTags'))
             ->addValidator($emailValidator);
       $email->setDecorators($this->generateDecorators());
       
       $sexValidator = new Zend_Validate_InArray(array("Male", "Female"));
      
       $sex =  new Zend_Form_Element_Radio('sex', array(
      'separator' => "</div><div class='box1'>",
    
            ));
       
       
       $sex->setRequired(true)
           ->setAttrib('class', 'radio')
           ->setAttrib('name', 'gen')
           
           ->setDecorators($this->generateRadioDecorators())
           ->setMultiOptions(array(
               "Male" => "Male",
               "Female" => "Female"
           ))
           ->addValidator($sexValidator);
       
       $password = new Zend_Form_Element_Password('password', array(
           'required' => true,
           'class' => 'input',
           'label' => 'Password'
       ));
       $password->setDecorators($this->generateDecorators('form_row'));
       
        $confirm_password = new Zend_Form_Element_Password('confirm_password', array(
        'required' => true,
        'class' => 'input',
        'label' => 'Re-enter Password'
       ));
        $validate_password = new Zend_Validate_Identical('password');
        $validate_password->setMessages(array(
            'notSame' => 'Both passwords do not match',
            'missingToken' => 'Please reenter your password here'
            
        ));
        
        $confirm_password->addValidators(array($validate_password));
        $confirm_password->setDecorators($this->generateDecorators('form_row'));

        $terms = new Zend_Form_Element_Checkbox('terms', array(
  'uncheckedValue'=> '',
  'checkedValue' => 'I Agree',
  'validators' => array(
    array('notEmpty', true, array(
      'messages' => array(
        'isEmpty'=>'You must agree to the terms'
             )
            ))
              ),
         'required'=>true,
            ));
      
      $urlService = new Zend_View_Helper_Url();
      $url = $urlService->url(array(
        'module' => 'default',
        'controller'=> 'terms',
        'action' => 'index'
       ));
      $terms->setDescription("<label><a href='$url' target='_blank'>Terms and Conditions</a></label>")
            ->setDecorators($this->generateInputBeforeDecorators("form_row1 last"));
         
       $register = new Zend_Form_Element_Submit('register');
       $register->setLabel('Register')
                ->setAttribs(array('id' => 'submitbutton', 
                                    'class' =>  'btn',
                                    'onmouseout' => 'this.className=("btn")',
                                    'onmouseover' => 'this.className=("btn_hover")'));
       
       $register->setDecorators($this->submitDecorator('btn_row'));
       $this->addELements(array($id, $firstName, $lastName, $email, $password,$confirm_password, $sex, $terms, $register));
    }


    /**
     * This decorator outputs the input element before the label.
     * @param type $class
     * @return array 
     */
     protected function generateInputBeforeDecorators($class = 'form_row'){
        
        return array(
                   'ViewHelper',

                    array('Description', array('escape' => false, 'tag' => false)),

                   'Errors',
                  
                   array(array('data'=>'HtmlTag'), array('tag'=>'div', 'class' => $class)),

                   'Label',

            );
    }
    

}

