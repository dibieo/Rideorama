<?php

class Rides_IndexController extends Zend_Controller_Action
{

    protected $ride_form = null;

    public function init()
    {
        /* Initialize action controller here */
        $this->ride_form = new Rides_Form_Rides();
//        $contextSwitcher = $this->_helper->getHelper('contextSwitch');
//        $contextSwitcher->addActionContext('book', 'html')->initContext();
    }

    public function indexAction()
    {
        // action body
    }

    public function postAction()
    {
       // print_r(Zend_Auth::getInstance()->getIdentity());
        $departure = $this->_getParam('from');
        $destination = $this->_getParam('to');
        $where = $this->_getParam('where');
        $trip_date = $this->_getParam('trip_date');
        
        $return_trip = "true";
        if ($this->_hasParam('return_trip')){
            $return_trip = $this->_getParam('return_trip');
        }
        
        
        if ($where == "toAirport"){
        // action body
           $this->getRideFormPage($where, $departure, $destination, $trip_date, $return_trip);
         
         }else if ($where == "fromAirport"){
       
           
            $this->getRideFormPage($where, $departure, $destination, $trip_date, $return_trip);
           }else{
               
               throw new Exception("Invalid argument. You need to be either going to or from an airport");
           }
        
    }

    /**
     * Performs ajax validation on the form inputs
     *
     *
     */
    public function validateformAction()
    {
        
        $this->_helper->formvalidate($this->ride_form, $this->_helper, $this->_getAllParams());
    }

    /**
     * Displays the appropriate form for posting a ride and submits the user's post
     * to the Ride_Model_Service
     * @param string $where (fromAirport or toAirport)
     * @param string $departure
     * @param string $destination
     * @param string $trip_date 
     * @param string $return_trip //Should the user be able to post a return trip from
     * this location
     *
     *
     *
     */
    private function getRideFormPage($where, $departure, $destination, $trip_date, $return_trip)
    {
       $this->view->where = $where;
       if ($where == "toAirport"){
       $this->ride_form->departure->setValue($departure)
                                  ->setAttrib('placeholder', 'Enter your departure');
       $this->ride_form->destination->setValue($destination)
                                    ->setAttrib('placeholder', 'Enter airport name')
                                    ->setJQueryParams(array('source' =>$this->ride_form->getAirports()));
       $this->ride_form->trip_date->setValue($trip_date);
       $this->ride_form->return->setValue($return_trip);
       
       }else if ($where == "fromAirport"){
           
       $this->ride_form->departure->setValue($departure)
                                  ->setAttrib('placeholder', 'Enter airport name')
                                  ->setJQueryParams(array('source' =>$this->ride_form->getAirports()));
       $this->ride_form->destination->setValue($destination)
                                   ->setAttrib('placeholder', 'Enter your destination');;
       $this->ride_form->trip_date->setValue($trip_date);
       $this->ride_form->return->setValue($return_trip);
           
       }
       
       $this->view->form = $this->ride_form;
       
         if ($this->getRequest()->isPost()){
            $formData = $this->getRequest()->getPost();
            if ($this->ride_form->isValid($formData)){
      
                $this->processRequest($formData, $where);
             
        }else{
            
            $this->ride_form->populate($formData);
        }
           
         }
    
    }

    public function successAction()
    {
        $this->view->params = $this->_getAllParams();
        $this->view->msg = "Ride posted";
    }

    public function bookAction()
    {
       $this->_helper->viewRenderer->setNoRender();
       $this->_helper->getHelper('layout')->disableLayout();
        // action body
        $params = $this->_getAllParams();
        $ride = new Rides_Model_RidesService($params['where']);
        $ride->requestPersmissionToBuySeat($params);
        
        echo "A request has been sent to this driver for approval";
    }

    public function detailsAction()
    {
        // action body
        $params = $this->_getAllParams();
        $this->view->where = $params['where'];
        $this->view->trip_date = $params['trip_date'];
        $this->view->airport = $params['airport'];
        $trip = new Rides_Model_RidesService($params['where']);
        $params = $trip->tripdetails($params);
        $this->view->data = $params[0];
    }

    /**
     * Adds the form data to the model and redirects to a success page
     * @param Array $formData Contains an array of post data
     * @param string $where toAirport or fromAirport
     *
     *
     */
    private function processRequest($formData, $where)
    {
    
    try{
     $formData['trip_date'] =  date('Y-m-d', strtotime($formData['trip_date']));

      $ride = new Rides_Model_RidesService($where);
      $last_trip_id = $ride->addRide($formData, $where);
      $formData['trip_id'] = $last_trip_id;
      //Share on facebook
      
      if (isset($formData['facebook'])) {
     if ($formData['facebook'] == "true"){
        $ride->postMessageOnFacebook("I am giving a ride..Check Rideorama to see it ");
        }
      }
      
      if (isset ($formData['paypal_email'])){
        $user = new Account_Model_UserService();
        $user->updateUserPaypalEmail(Zend_Auth::getInstance()->getIdentity()->id, $formData['paypal_email']);
      }
      $this->_forward('success', 'index', 'rides', $formData);

    } catch (Exception $ex){
       echo $ex->getMessage();
    }
    
    }

    /**
     * Processing acceptance of a booking
     */
    public function bookingacceptedAction()
    {
        // action body
        $params = $this->_getAllParams();
        $email_service = new Application_Model_EmailService();
        $email_service->bookingRequestAccepted($params);
        $this->view->passenger = $params['passengerName'];
    }

    /**
     * Processes the rejection of a booking
     */
    public function bookingrejectedAction()
    {
        // action body
        $params = $this->_getAllParams();
        $ride = new Rides_Model_RidesService($params['where']);
        $ride->rejectRequestToBookSeat($params);
    }


}













