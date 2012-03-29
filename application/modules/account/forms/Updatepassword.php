<?php

class Account_Form_Updatepassword extends Application_Form_Base
{

  public function init()
    {
        /* Form Elements & Other Definitions Here ... */
        
       $id = new Zend_Form_Element_Hidden('id');
      
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

     
       $savepassword = new Zend_Form_Element_Submit('save');
       $savepassword->setLabel('Update')
                ->setAttribs(array('id' => 'submitbutton', 
                                    'class' =>  'btn',
                                    'onmouseout' => 'this.className=("btn")',
                                    'onmouseover' => 'this.className=("btn_hover")'));
       
       $savepassword->setDecorators($this->submitDecorator('btn_row'));
       $this->setMethod('POST');
       $this->addELements(array($id, $password,$confirm_password,$savepassword));
    }

}

