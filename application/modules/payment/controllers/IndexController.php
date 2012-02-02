<?php

class Payment_IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
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
        
        echo $returnUrl;
        
       //get the payment parameters
//       
//        $receiverEmail = $this->_getParam('receiverEmail');
//        $amount = $this->_getParam('amount');
        
        // action body
        $payDetails = array(
            'cancelUrl' => "http://localhost$cancelUrl",
            'returnUrl' => "http://localhost$returnUrl",
            'rideoramaEmail' => 'test_1326109945_biz@gmail.com',
            'receiverEmail' => 'user9_1326263178_per@gmail.com',
            'memo' => 'Payment from rideorama platform for a ride',
            'amount' => 10
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




