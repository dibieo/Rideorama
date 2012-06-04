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
        
       $driverName = $this->_getParam("driverName");
       $driverEmail = $this->_getParam("driverEmail");
       $date = $this->_getParam('trip_date');
       $dept_date  = $date;
       
        $payDetails = array(
            'cancelUrl' => "$link"."$cancelUrl",
            'returnUrl' => "$link". "$returnUrl",
            'rideoramaEmail' => Zend_Registry::get('config')->paypal->rideoramaEmail,
            'receiverEmail' =>   $driverPaypal,
            'memo' => "Payment to $driverName [$driverEmail] for a trip on $dept_date",
            'amount' => $this->_getParam('tripcost')
        );
        
        $pay = new Payment_Model_PaypalService();
        $response = $pay->pay($payDetails);
        //print_r($response);
        //Zend_Registry::set('response', $response);
    }
    
    

    /**
     * Payment is successful
     * Send driver an email of this confirmation
     * Send passenger the driver's phone number
     */
    public function successAction()
    {
   //  $rp = Zend_Registry::get('response');
    // print_r($rp);
     $email = new Application_Model_EmailService();
     $email->paymentSuccessEmailToPassenger($this->_getAllParams());

     $email_driver = new Application_Model_EmailService();
     $email_driver->paymentSuccessEmailToDriver($this->_getAllParams());
     
        // action body
     $params = $this->_getAllParams();
     $service = new Application_Model_Service();
     //print_r($this->_getAllParams());
     $service->addTripNotification($this->_getAllParams());
    }

    public function cancelAction()
    {
        // action body
    }

}





