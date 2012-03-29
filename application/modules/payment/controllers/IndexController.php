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
        $user = new Account_Model_UserService();
        $driver = $user->getUserByEmail($this->_getParam('driverEmail'));
        $passenger = $user->getUserByEmail($this->_getParam('passengerEmail'));
        $passengerPhone = $passenger->telephone;
        $driverPaypal = $driver->paypal_email;
        $driverPhone = $driver->telephone;
        
        $cancelUrl = $url->url(array(
                                'module' => 'payment',
                                'controller' => 'index',
                                'action' => 'cancel'));
        
        $returnUrl = $url->url(array(
            'module' => 'payment',
            'controller' => 'index', 
            'action' => 'success',
            "driverEmail" => $this->_getParam('driverEmail'),
            "driverName" => $this->_getParam("driverName"),
            "tripcost" => $this->_getParam('tripcost'),
            "tripdate" => $this->_getParam('trip_date'),
            "driverPhone" => $driverPhone,
            "passengerPhone" => $passengerPhone,
            "passengerName" => $this->_getParam("passengerName"),
            "passengerEmail" => $this->_getParam("passengerEmail")
        ));
        
        
        $baseurl = new Zend_View_Helper_ServerUrl();
        $link = "http://" .$baseurl->getHost();
        
       
        $payDetails = array(
            'cancelUrl' => "$link"."$cancelUrl",
            'returnUrl' => "$link". "$returnUrl",
            'rideoramaEmail' => Zend_Registry::get('config')->paypal->rideoramaEmail,
            'receiverEmail' =>   $driverPaypal,
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
     $email = new Application_Model_EmailService();
     $email->paymentSuccessEmailToPassenger($this->_getAllParams());

     $email_driver = new Application_Model_EmailService();
     $email_driver->paymentSuccessEmailToDriver($this->_getAllParams());
     
        // action body
     $params = $this->_getAllParams();
     $service = new Application_Model_Service();
     $service->addTripNotification($this->_getAllParams());
    }

    public function cancelAction()
    {
        // action body
    }

}





