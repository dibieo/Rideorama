<?php

/**
 * @TODO work on space between field texts
 * @TODO fix img uploader.
 * Add Ajax uploader and make sure max_size is enforced
 * Also check to make sure users can only upload files of type img, png or jpeg
 */
class Admin_Form_Landmark extends ZendX_JQuery_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
        
        $id = new Zend_Form_Element_Hidden('id');
        $name = new Zend_Form_Element_Text('name', array(
            'label' => 'Name:',
            'required' => true
            
        ));
        
        $address = new Zend_Form_Element_Text('address', array(
            'label' =>  'Address',
            'required'=> true
            
        ));
        $category = new Zend_Form_Element_Select('cat_id', array(
            'label' => 'Category',
            'required' => true      
        ));
        $cats = new Admin_Model_DbTable_LandmarkCategory();
        $category->addMultiOptions($cats->getLandMarkCatsArray());
        
        $city = new Zend_Form_Element_Select('city_id', array(
            'label' => 'City',
            'required' => true
            
        ));
        $cities = new Admin_Model_DbTable_City();
        $city->addMultiOptions($cities->getCityStates());
        
         $fileDest = realpath(APPLICATION_PATH . '/../public/img/landmarks');
         $image = new Zend_Form_Element_File('pic');
         $image->setLabel('Picture ')
                 ->setDestination($fileDest);
        
         $submit = new Zend_Form_Element_Submit('submit');
         
         $this->addElements(array($id, $name, $image, $address, $category,$city, $submit));
        
    }


}

