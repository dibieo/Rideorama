<?php

/**
 * Payment_Model_PaypalService
 * This model service handles the chained payment that occurs between passengers and drivers
 * It uses the PayPal Adaptive payment API
 */
require_once 'lib/AdaptivePayments.php'; //Load Paypal Adapative payments Api
class Payment_Model_PaypalService
{
   
    protected $paypal_redirect_url;
    protected $developer_portal = 'https://developer.paypal.com';
    protected $device_id;
    protected $application_id;
    
    public function __construct(){
      
      $this->device_id = 'PayPal_Platform_PHP_SDK';
      $this->paypal_redirect_url = Zend_Registry::get('config')->paypal->redirect_url;
      $this->application_id = Zend_Registry::get('config')->paypal->application_id;
    }
    
    public function pay(array $payDetails){
      
    try {
       
	           	          	         	
	           /* Make the call to PayPal to get the Pay token
	            If the API call succeded, then redirect the buyer to PayPal
	            to begin to authorize payment.  If an error occured, show the
	            resulting errors
	            */
        $payRequest = $this->createPayRequest($payDetails);
         $ap = new AdaptivePayments();
	$response=$ap->Pay($payRequest);
	           
	if(strtoupper($ap->isSuccess) == 'FAILURE')
				{
                                
                                  return $ap->getErrorMessage();
                               //     throw new Exception($ap->getLastError());
				// print_r($ap->);
				}
				else
				{
					$_SESSION['payKey'] = $response->payKey;
					if($response->paymentExecStatus == "COMPLETED")
					{
//						$location = "PaymentDetails.php";
//						header("Location: $location");
                                            return "Payment successful";
					}
				
					else
					{
						$token = $response->payKey;
						$payPalURL = $this->paypal_redirect_url .'_ap-payment&paykey='.$token;
                                                 header("Location: ".$payPalURL);
					}
				}
			}
			catch(Exception $ex) {
                                echo $ex->getFile();
				echo $ex->getMessage();
				echo "an error occured";
			}	

       return $response;
    }
    
    private function createPayRequest(array $payDetails) {
        
         $payRequest = new PayRequest();
        $payRequest->actionType = "PAY_PRIMARY";
       $payRequest->cancelUrl = $payDetails['cancelUrl'];
        $payRequest->returnUrl = $payDetails['returnUrl'];     
        $payRequest->clientDetails = new ClientDetailsType();
        $payRequest->clientDetails->applicationId = $this->application_id;
	$payRequest->clientDetails->deviceId = $this->device_id;
	$payRequest->clientDetails->ipAddress = "127.0.0.1";
	$payRequest->currencyCode = "USD";
	//$payRequest->senderEmail = $payDetails['senderEmail'];
        $payRequest->requestEnvelope = new RequestEnvelope();
	$payRequest->requestEnvelope->errorLanguage = "en_US";
        
        //Rideorama is the primary receiver of the transfer amount
        $payment = $payDetails['amount'];
        $rideoramaCommission = $payment *  0.2 ;      //20 % commission
        $driverPay = $payment - $rideoramaCommission;
        
        //primary receiver Rideorama
        $rideorama = new receiver();
	$rideorama->email = $payDetails['rideoramaEmail'];
	$rideorama->amount = $payment ;
	$rideorama->primary = true;
	
        //secondary email
        $driver = new receiver();
	$driver->email = $payDetails['receiverEmail'];
	$driver->amount = $driverPay;
	$driver->primary = false;
	           	
	$payRequest->receiverList = array($rideorama,$driver);
	           	
	$payRequest->feesPayer = 'PRIMARYRECEIVER';
	$payRequest->memo = $payDetails['memo'];
        
        return $payRequest;
    }
    
    
    
}

