<?php

class Account_Form_Editcar extends Application_Form_Base
{

    public function init()
    {
          /* Form Elements & Other Definitions Here ... */
        
      $alpha = new Zend_Validate_Alnum(array('allowWhiteSpace' => true));

       $id = new Zend_Form_Element_Hidden('id');
       $make = new Zend_Form_Element_Text('make', array(
           'required' => true,
           'class' => 'input',
           'label' => 'Make',
          'filters'=> array('StringTrim', 'StripTags'),
           'validators' => array($alpha)
       ));
       $make->setAttrib('placeholder', 'Enter car make e.g. Toyota');
       $make->setDecorators($this->generateDecorators());
       
       $model = new Zend_Form_Element_Text('model', array(
           'required' => true,
           'class' => 'input',
           'label' => 'Model',
           'filters'=> array('StringTrim', 'StripTags'),
           'validators' => array($alpha)
       ));
       $model->setAttrib('placeholder', 'Enter car model e.g. Sienna');
       
       $model->setDecorators($this->generateDecorators());
       
      
       $year = new Zend_Form_Element_Text('year', array(
           'required' => true,
           'class' => 'input',
           'label' => 'Year',
           'filters'=> array('StringTrim', 'StripTags'),
           'placeholder' => "Enter year e.g. 2007",
           'validators' => array('Int')
           
       ));
       
       $year->setDecorators($this->generateDecorators());
   
       $save = new Zend_Form_Element_Submit('save');
       $save->setLabel('save')
                ->setAttribs(array('id' => 'submitbutton', 
                                    'class' =>  'btn',
                                    'onmouseout' => 'this.className=("btn")',
                                    'onmouseover' => 'this.className=("btn_hover")'));
       
        $picDest = realpath(APPLICATION_PATH . '/../public/img/cars/'); //Destination for user car images
        $car_profile_pic = new Zend_Form_Element_File('car_profile_pic');
        $this->produceCarImageElement($car_profile_pic, 'Car profile pic', $picDest);
         
         $car_pic1 = new Zend_Form_Element_File('picture1');
         $this->produceCarImageElement($car_pic1, 'Car front', $picDest);
         
         $car_pic2 = new Zend_Form_Element_File('picture2');
         $this->produceCarImageElement($car_pic2, 'Car inside', $picDest);

       $car_profile_pic->setDecorators($this->getFileDecorators("form_row"));
       $this->produceCarImageElement($car_profile_pic, 'Car profile image', $picDest);
       $save->setDecorators($this->submitDecorator('btn_row'));
   
       $this->addELements(array($id, $make, $model, $year, $car_profile_pic, $car_pic1, $car_pic2, $save));

       $this->setDecorators($this->getFormDescriptionDecorators());
       $this->setDescription("Edit car profile");
     }
     
     /**
      *
      * @param Zend_Form_Element_File $elemName
      * @param type $label
      * @param type $picDest 
      */
     private function produceCarImageElement($element, $label, $picDest){
         $element->setLabel($label)
                            ->setDestination($picDest);
         
         $element->addValidator('Extension', false, 'jpg,jpeg,png,gif') 
                ->addValidator('Size', false, '4096kB')
                ->addFilter('Rename', array(
                 'target' => $picDest,
                'overwrite' => true
         ));
        
       $element->setDecorators($this->getFileDecorators("form_row"));
       

     }

}

