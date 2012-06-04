<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Completecar
 *
 * @author ovo
 */
class Account_Form_Completecar extends Application_Form_Base{
    //put your code here
  public function init()
    {
       $row_decorators = array(
            'ViewHelper',

                   'Description',

                   'Errors',
                  
                   'Label',
          
            array('htmlTag', array ('tag' => 'span', 'class' => 'row'))
           
       );
       
         $row_msg_decorators = array(
           'ViewHelper',

                   'Description',

                   'Errors',
                  
                   'Label',
            array('htmlTag', array ('tag' => 'span', 'class' => 'row'))
             
       );
       
       
        $alpha = new Zend_Validate_Alpha(array('allowWhiteSpace' => true));
        
        $make = new Zend_Form_Element_Text('make', array(
           
            'required'  => true,
            'validators' => array($alpha),
            'Label' => 'Make',
            'class' => 'field',
            'Filters' => array('StripTags', 'StringTrim')
        ));
        
        $model = new Zend_Form_Element_Text('model', array(
           
            'required'  => true,
            'validators' => array($alpha),
            'Label' => 'Model',
            'class' => 'field',
            'Filters' => array('StripTags', 'StringTrim')
        ));
        
        $year = new Zend_Form_Element_Text('year', array(
           
            'required'  => true,
            'validators' => array('Digits'),
            'Label' => 'Year',
            'class' => 'field',
            'Filters' => array('StripTags', 'StringTrim')
        ));
        
       
        //Enter upload buttons For car pictures
          $fileDest = realpath(APPLICATION_PATH . '/../public/img/cars/'); //Destination for user car images
         $car_profile_pic = new Zend_Form_Element_File('car_profile_pic');
         $car_profile_pic->setLabel('Car Profile')
                 ->setDestination($fileDest)
                 ->setRequired(true);
         
         $car_profile_pic->addValidator('IsImage', false)
                ->addValidator('Size', false, '1MB');
 
         
         $car_profile_pic->addFilter('Rename', array(
             'target' => $fileDest,
             'overwrite' => true
         ));
          
         $car_pic1 = new Zend_Form_Element_File('car_pic1');
         $car_pic1->setLabel('Car back')
                 ->setDestination($fileDest)
                 ->setRequired(true);
         
         $car_pic1->addValidator('IsImage', false)
                ->addValidator('Size', false, '1MB');
 
         
         $car_pic1->addFilter('Rename', array(
             'target' => $fileDest,
             'overwrite' => true
         ));
           
        $car_pic2 = new Zend_Form_Element_File('car_pic2');
         $car_pic2->setLabel('Car front')
                 ->setDestination($fileDest)
                 ->setRequired(true);
         
         $car_pic2->addValidator('IsImage', false)
                ->addValidator('Size', false, '1MB');
 
         
         $car_pic2->addFilter('Rename', array(
             'target' => $fileDest,
             'overwrite' => true
         ));
         
        $submit = new Zend_Form_Element_Submit('Submit');
        $submit->setLabel('Submit')
                ->setAttrib('class', 'sign_up_btn')
                ->setAttrib('onmouseout', "this.className=('sign_up_btn')")
                ->setAttrib('onmouseover', "this.className=('sign_up_btn_hover')");
 
        $this->addElements(array($make, 
                              $model, $year, $car_profile_pic, $car_pic1, $car_pic2, $submit));
        
        
        $submit->clearDecorators();
        /**
         * Form Decorators
         */
       
         $this->addDisplayGroup(array($model, $make, $year, $car_profile_pic,
                                      $car_pic1, $car_pic2), "about_car");
         
         
        
        $about_car = $this->getDisplayGroup('about_car');
        $about_car->setDecorators(array(
                 array('Description', array('tag' => 'h2', 'class' => '')),

                'FormElements',
               
                array('HtmlTag', array('tag' => 'div'))
        ));
        $about_car->setDescription('Please complete your car information');
        
        $submit->setDecorators(array('ViewHelper'));

        $make->setDecorators($row_decorators);
        $model->setDecorators($row_decorators);
        $year->setDecorators($row_decorators);
       
        $fileDecorators = array(
            'File',
            'Label',
            'Errors',
           array('htmlTag', array ('tag' => 'span', 'class' => 'row'))

        );
        // File decorators
        
        $car_profile_pic->setDecorators($fileDecorators);
        $car_pic1->setDecorators($fileDecorators);
        $car_pic2->setDecorators($fileDecorators);
        
       $this->setDecorators($this->getFormDescriptionDecorators());
       $this->setDescription("Add car profile");
    }



}


