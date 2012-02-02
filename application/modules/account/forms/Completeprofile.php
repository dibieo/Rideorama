<?php

class Account_Form_Completeprofile extends Application_Form_Base
{

    public function init()
    {
        $alpha = new Zend_Validate_Alpha(array('allowWhiteSpace' => true));
        $occupation = new Zend_Form_Element_Text('occupation', array(
           
            'required'  => true,
            'validators' => array($alpha),
            'Label' => 'Occupation',
            'Filters' => array('StripTags', 'StringTrim')
        ));
        
        $age = new Zend_Form_Element_Text('age', array(
           
            'required'  => true,
            'validators' => array('Digits'),
            'Label' => 'Age',
            'Filters' => array('StripTags', 'StringTrim')
        ));
        
        $phone_number = new Zend_Form_Element_Text('phone_number', array(
           
            'required'  => true,
            'validators' => array('Int'),
            'Label' => 'Phone #',
            'Filters' => array('StripTags', 'StringTrim')
        ));
        
        $make = new Zend_Form_Element_Text('make', array(
           
            'required'  => true,
            'validators' => array($alpha),
            'Label' => 'Make',
            'Filters' => array('StripTags', 'StringTrim')
        ));
        
        $model = new Zend_Form_Element_Text('model', array(
           
            'required'  => true,
            'validators' => array($alpha),
            'Label' => 'model',
            'Filters' => array('StripTags', 'StringTrim')
        ));
        
        $year = new Zend_Form_Element_Text('year', array(
           
            'required'  => true,
            'validators' => array('Digits'),
            'Label' => 'year',
            'Filters' => array('StripTags', 'StringTrim')
        ));
        
        //Enter upload button for profile picture
         $profilepicfileDest = realpath(APPLICATION_PATH . '/../public/img/users/'); //Destination for user car images
        $user_profile_pic = new Zend_Form_Element_File('user_profile_pic');
         $user_profile_pic->setLabel('Profile picture')
                 ->setDestination($profilepicfileDest)
                 ->setRequired(true);
         
         $user_profile_pic->addValidator('IsImage', false)
                ->addValidator('Size', false, 40000);
 
         
         $user_profile_pic->addFilter('Rename', array(
             'target' => $profilepicfileDest,
             'overwrite' => true
         ));
         
        //Enter upload buttons For car pictures
          $fileDest = realpath(APPLICATION_PATH . '/../public/img/cars/'); //Destination for user car images
         $car_profile_pic = new Zend_Form_Element_File('car_profile_pic');
         $car_profile_pic->setLabel('Car Profile picture')
                 ->setDestination($fileDest)
                 ->setRequired(true);
         
         $car_profile_pic->addValidator('IsImage', false)
                ->addValidator('Size', false, 40000);
 
         
         $car_profile_pic->addFilter('Rename', array(
             'target' => $fileDest,
             'overwrite' => true
         ));
          
         $car_pic1 = new Zend_Form_Element_File('car_pic1');
         $car_pic1->setLabel('Car picture 1')
                 ->setDestination($fileDest)
                 ->setRequired(true);
         
         $car_pic1->addValidator('IsImage', false)
                ->addValidator('Size', false, 40000);
 
         
         $car_pic1->addFilter('Rename', array(
             'target' => $fileDest,
             'overwrite' => true
         ));
           
        $car_pic2 = new Zend_Form_Element_File('car_pic2');
         $car_pic2->setLabel('Car picture 2')
                 ->setDestination($fileDest)
                 ->setRequired(true);
         
         $car_pic2->addValidator('IsImage', false)
                ->addValidator('Size', false, 40000);
 
         
         $car_pic2->addFilter('Rename', array(
             'target' => $fileDest,
             'overwrite' => true
         ));
         
        $submit = new Zend_Form_Element_Submit('Submit');
        $submit->setLabel('Submit')
                ->setAttrib('class', 'btn')
                ->setAttrib('onmouseout', 'this.className=("btn")')
                ->setAttrib('onmouseover', 'this.className=("btn_hover")')
                ->setAttrib('id', 'submitbutton');
        
       $submit->setDecorators($this->submitDecorator());
         
        $this->addElements(array($occupation, $age, $phone_number, $make, $user_profile_pic,
                              $model, $year, $car_profile_pic, $car_pic1, $car_pic2, $submit));
    }


}

