<?php

class Zend_Controller_Action_Helper_Formvalidate extends
                Zend_Controller_Action_Helper_Abstract
{
    
    /**
     *
     * @param type $form
     * @param type $helper Action helper
     * @param type $params Params from the form
     */
    function direct($form, $helper, $params)
    {
        
      $helper->viewRenderer->setNoRender();
      $helper->getHelper('layout')->disableLayout();

        $form->isValid($params);
        $json = $form->getMessages();
        header('Content-type: application/json');
        echo Zend_Json::encode($json);
    }
}