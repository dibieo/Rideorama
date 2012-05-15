<?php

class SplashController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
      $this->getHelper('layout')->disableLayout();

    }


}

