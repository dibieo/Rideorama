<?php

class Account_Form_Completeprofile extends Application_Form_Base
{

    public function init()
    {

       
         $this->generateDecorators('span', 'row');
       
        $alpha = new Zend_Validate_Alnum(array('allowWhiteSpace' => true));
        $occupation = new Zend_Form_Element_Text('occupation', array(
           
            'required'  => true,
            'validators' => array($alpha),
            'Label' => 'Occupation',
            'class' => 'field',
            'validators' => array('NotEmpty'),
            'Filters' => array('StripTags', 'StringTrim')
        ));
        
        //Ensures the user is at least 17 years old
        $ageValidator = $this->getAgeValidator();
        
        $age = new Zend_Form_Element_Text('age', array(
           
            'required'  => true,
            'validators' => array('Digits'),
            'Label' => 'Age',
            'class' => 'field',
            'validators' =>array($ageValidator),
           'Filters' => array('StripTags', 'StringTrim', 'Digits')
        ));
        
        $phone_length_validator = new Zend_Validate_StringLength(array(
           'min' => 10,
           'max' => 10
       ));
        
        $phone_number = new Zend_Form_Element_Text('phone_number', array(
           
            'required'  => true,
            'validators' => array('Digits', $phone_length_validator),
            'Label' => 'Phone #',
            'class' => 'field',
              'Filters' => array('StripTags', 'StringTrim')
        ));
        
    
        
        $make = new Zend_Form_Element_Text('make', array(
           
            'validators' => array($alpha),
            'Label' => 'Make',
            'class' => 'field',
            'Filters' => array('StripTags', 'StringTrim')
        ));
        
        $model = new Zend_Form_Element_Text('model', array(
           
            'validators' => array($alpha),
            'Label' => 'Model',
            'class' => 'field',
            'Filters' => array('StripTags', 'StringTrim')
        ));
        
        $year = new Zend_Form_Element_Text('year', array(
           
            'validators' => array('Digits'),
            'Label' => 'Year',
            'class' => 'field',
            'Filters' => array('StripTags', 'StringTrim')
        ));
        
        //Enter upload button for profile picture
         $profilepicfileDest = realpath(APPLICATION_PATH . '/../public/img/users/'); //Destination for user car images
        $user_profile_pic = new Zend_Form_Element_File('user_profile_pic');
         $user_profile_pic->setLabel('Profile picture')
                            ->setDestination($profilepicfileDest);
         
         $user_profile_pic->addValidator('Extension', false, 'jpg,jpeg,png,gif') 
                ->addValidator('Size', false, '300kB');
 
         
         $user_profile_pic->addFilter('Rename', array(
             'target' => $profilepicfileDest,
             'overwrite' => true
         ));
         
        //Enter upload buttons For car pictures
          $fileDest = realpath(APPLICATION_PATH . '/../public/img/cars/'); //Destination for user car images
         $car_profile_pic = new Zend_Form_Element_File('car_profile_pic');
         $car_profile_pic->setLabel('Car Profile')
                 ->setDestination($fileDest);
         
         $car_profile_pic->addValidator('Extension', false, 'jpg,jpeg,png,gif') 
                ->addValidator('Size', false, '300kB');
 
         
         $car_profile_pic->addFilter('Rename', array(
             'target' => $fileDest,
             'overwrite' => true
         ));
          
         $car_pic1 = new Zend_Form_Element_File('picture1');
         $car_pic1->setLabel('Car back')
                 ->setDestination($fileDest);
         
         $car_pic1->addValidator('Extension', false, 'jpg,jpeg,png,gif') 
                ->addValidator('Size', false, '300kB');
 
         
         $car_pic1->addFilter('Rename', array(
             'target' => $fileDest,
             'overwrite' => true
         ));
           
        $car_pic2 = new Zend_Form_Element_File('picture2');
         $car_pic2->setLabel('Car front')
                 ->setDestination($fileDest);
         
         $car_pic2->addValidator('Extension', false, 'jpg,jpeg,png,gif') 
                ->addValidator('Size', false, '300kB');
 
         
         $car_pic2->addFilter('Rename', array(
             'target' => $fileDest,
             'overwrite' => true
         ));
         
        $car_checkbox = new Zend_Form_Element_Checkbox('has_no_car',
              array(
                  'Label' => "I don't own a car",
                  'required' => true
              )
         );
        
        
        $car_checkbox->setCheckedValue('true')
                          ->setUncheckedValue('false')
                          ->setChecked(false)
                          ->setDecorators($this->generateRadioDecorators());
         
        $submit = new Zend_Form_Element_Submit('Submit');
        $submit->setLabel('Submit')
                ->setAttrib('class', 'sign_up_btn')
                ->setAttrib('onmouseout', "this.className=('sign_up_btn')")
                ->setAttrib('onmouseover', "this.className=('sign_up_btn_hover')");
 
        $this->addElements(array($occupation, $age, $phone_number, $make, $user_profile_pic,
                              $car_checkbox, $model, $year, $car_profile_pic, $car_pic1, $car_pic2, $submit));
        
        
        $submit->clearDecorators();
        /**
         * Form Decorators
         */
         $this->addDisplayGroup(array($occupation, $age, $phone_number,$user_profile_pic, $car_checkbox, $submit),
                                "about_you"
                                );
         $this->addDisplayGroup(array($model, $make, $year, $car_profile_pic,
                                      $car_pic1, $car_pic2), "about_car");
         
         
        
        $about_car = $this->getDisplayGroup('about_car');
        $about_car->setDecorators(array(
                 array('Description', array('tag' => 'h2', 'class' => '')),

                'FormElements',
               
                array('HtmlTag', array('tag' => 'div', 'class' => 'right_details'))
        ));
        $about_car->setDescription('About your car');
        $about_you = $this->getDisplayGroup('about_you');
        $about_you->setDecorators(array(
                     array('Description', array('tag' => 'h2', 'class' => '')),
                    'FormElements',
                    array('HtmlTag',array('tag'=>'div','class'=>'left_details'))
        ));
        $about_you->setDescription('About you');
        
        $submit->setDecorators(array('ViewHelper'));

        $occupation->setDecorators($this->activateformDecorators());
        $age->setDecorators($this->activateformDecorators());
        $phone_number->setDecorators($this->activateformDecorators());
        $make->setDecorators($this->activateformDecorators());
        $model->setDecorators($this->activateformDecorators());
        $year->setDecorators($this->activateformDecorators());
       
        $fileDecorators = $this->getFileDecorators();
        // File decorators
        $user_profile_pic->setDecorators($fileDecorators);
        
        $car_profile_pic->setDecorators($fileDecorators);
        $car_pic1->setDecorators($fileDecorators);
        $car_pic2->setDecorators($fileDecorators);
        
    }


}

