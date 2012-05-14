<?php

class Account_Form_Paypal extends Application_Form_Base
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
        
    $emailValidator = new Zend_Validate_EmailAddress();

           $paypalemail = new Zend_Form_Element_Text('paypal_email', array(
           'class' => 'input',
           'label' => 'Paypal Email'
       ));
       $paypalemail->addFilters(array('StringTrim', 'StripTags'))
             ->addValidator($emailValidator);
       $paypalemail->setDecorators($this->generateDecorators());

        $save = new Zend_Form_Element_Submit('save');
       $save->setLabel('save')
                ->setAttribs(array('id' => 'submitbutton', 
                                    'class' =>  'btn',
                                    'onmouseout' => 'this.className=("btn")',
                                    'onmouseover' => 'this.className=("btn_hover")'));
       
       
    $save->setDecorators($this->submitDecorator('btn_row'));

       $this->addElements(array($paypalemail, $save));
    }


}

