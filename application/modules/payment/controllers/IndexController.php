<?php

class Payment_IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
       //$this->_helper->getHelper('layout')->disableLayout();
       //$this->_helper->viewRenderer->setNoRender();
        $url = new Zend_View_Helper_Url();
        
        
        $cancelUrl = $url->url(array(
                                'module' => 'payment',
                                'controller' => 'index',
                                'action' => 'cancel'));
        
        $returnUrl = $url->url(array(
            'module' => 'payment',
            'controller' => 'index', 
            'action' => 'success'
        ));
        
       // echo $returnUrl;
        
       //get the payment parameters
//       
//        $receiverEmail = $this->_getParam('receiverEmail');
//        $amount = $this->_getParam('amount');
        
        // action body
        $baseurl = new Zend_View_Helper_ServerUrl();
        $link = "http://" .$baseurl->getHost();
        $payDetails = array(
            'cancelUrl' => "$link"."$cancelUrl",
            'returnUrl' => "$link". "$returnUrl",
            'rideoramaEmail' => 'test_1326109945_biz@gmail.com',
            'receiverEmail' =>   $this->_getParam('paypalEmail'),
            'memo' => 'Payment from rideorama platform for a ride',
            'amount' => $this->_getParam('tripcost')
        );
        
        $pay = new Payment_Model_PaypalService();
        $pay->pay($payDetails);
    }

    /**
     * Payment is successful
     * Send driver an email of this confirmation
     * Send passenger the driver's phone number
     */
    public function successAction()
    {
    
        // action body
    }

    public function cancelAction()
    {
        // action body
    }


}





