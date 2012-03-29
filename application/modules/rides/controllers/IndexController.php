<?php

class Rides_IndexController extends Zend_Controller_Action
{

    protected $ride_form = null;

    public function init()
    {
        /* Initialize action controller here */
        $this->ride_form = new Rides_Form_Rides();
    }

    public function indexAction()
    {
        // action body
    }

    public function postAction()
    {
        
        //Does the user have a car 
        $user_obj = new Account_Model_UserService();
        $user = $user_obj->getUser(Zend_Auth::getInstance()->getIdentity()->id);
        if (!$user->car){
            $url = "http://" .$_SERVER['SERVER_NAME'] . $this->getFrontController()->getRequest()->getRequestUri();
            Zend_Registry::set("fillcar", $url);
            $this->_forward('addcar', 'edit', 'account');
        }
       // print_r(Zend_Auth::getInstance()->getIdentity());
        $departure = $this->_getParam('from');
        $destination = $this->_getParam('to');
        $where = $this->_getParam('where');
        $trip_date = $this->_hasParam('trip_date') ?$this->_getParam('trip_date') : "";
        
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
     *
     */
    private function getRideFormPage($where, $departure, $destination, $trip_date, $return_trip)
    {
       $this->view->where = $where;
       if ($where == "toAirport"){
       $this->ride_form->departure->setValue($departure)
                                  ->setAttrib('placeholder', 'Enter your full departure address e.g. 1777 exposition Drive Boulder CO');
       $this->ride_form->destination->setValue($destination)
                                    ->setAttrib('placeholder', 'Enter airport name')
                                    ->setJQueryParams(array('source' =>$this->ride_form->getAirports()))
                                    ->setAttrib('readOnly', true);
       $this->ride_form->trip_date->setValue($trip_date);
       $this->ride_form->return->setValue($return_trip);
       
       }else if ($where == "fromAirport"){
           
       $this->ride_form->departure->setValue($departure)
                                  ->setAttrib('placeholder', 'Enter airport name')
                                  ->setJQueryParams(array('source' =>$this->ride_form->getAirports()))
                                   ->setAttrib('readOnly', true);
       $this->ride_form->destination->setValue($destination)
                                   ->setAttrib('placeholder', 'Enter your full destination e.g. 1777 Exposition Drive Boulder CO');;
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
         
         $trip_date = $formData['trip_date'];
        $helper = new Zend_View_Helper_Url() ;
        $url = $helper->url(array(
           'module' => "rides",
           "controller" => "index",
           "action" => "details",
           'where' => $where,
           'trip_id' => $formData['trip_id'],
           'trip_date' => $trip_date,
           'airport' => ($where == "toAirport") ? $formData['destination'] : $formData['departure']
        ));
        
        $fb = new Application_Model_FacebookService();
        $dest = $formData['dstination'];
        $dept = $formData['departure'];
        
        $tripLocationMsg = ($where == "toAirport") ? "to $dest" : "from $dept"; // Msg for FB status update. to Airport name or from Airport name
        $fb->postMessageOnFacebook("I am giving a ride $tripLocationMsg on $trip_date.Check Rideorama to see it ", $url);
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
     *
     */
    public function bookingacceptedAction()
    {
        // action body
      try{
        $params = $this->_getAllParams();
        $ride = new Rides_Model_RidesService($params['where']);
        $this->view->val = $ride->acceptRequestToBookSeat($params);
        $this->view->passenger = $params['passengerName'];
      }catch (Exception $ex){
        print($ex->getMessage());
      }
    }

    /**
     * Processes the rejection of a booking
     *
     */
    public function bookingrejectedAction()
    {
        // action body
        $params = $this->_getAllParams();
        $ride = new Rides_Model_RidesService($params['where']);
        $val = $ride->rejectRequestToBookSeat($params);
        $this->view->val = $val;
    }

    public function editAction()
    {
        // action body
        $ride_data = $this->_getAllParams();
        $form = new Rides_Form_Rides();
        $form->setDescription("Edit your ride post");
        // Setoptions
        $this->setEditFormOptions($form, $ride_data);
        $this->view->form = $form;
        $this->view->where = $ride_data['where'];
        
       if ($this->getRequest()->isPost()){
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)){
                $formData['where'] = $ride_data['where']; // Enter airport location
                $formData['trip_date'] =  date('Y-m-d', strtotime($formData['trip_date']));
                $formData['trip_id'] = $this->_getParam('trip_id');
                $this->updateRide($formData);
             
        }else{
            
            $form->populate($formData);
        }
           
         }
    }
    
    /**
     * DRY method for setting values of a form field
     * @param Zend_Form $from 
     * @param Array $ride_data
     */
    private function setEditFormOptions($form, $ride_data){
        
        
        $form->departure->setValue($ride_data['from']);
        $form->destination->setValue($ride_data['to']);
        
        if ($ride_data['where'] == "toAirport"){
           $form->destination->setAttrib('readonly', true); 
        }else if ($ride_data['where'] == "fromAirport"){
           $form->departure->setAttrib('readonly', true) ;
        }
        
        $form->trip_date->setValue(date('m/d/Y', strtotime($ride_data['trip_date'])));
        $form->trip_msg->setValue($ride_data['trip_msg']);
        $form->trip_cost->setValue($ride_data['trip_cost']);
        $form->luggage_size->setValue($ride_data['luggage_size']);
        $form->luggage->setValue($ride_data['luggage']);
        $form->num_seats->setValue($ride_data['num_seats']);
        $form->trip_time->setValue($ride_data['trip_time']);
        $form->return->setValue(($this->_hasParam('return_trip') ? $ride_data['return_trip'] : null));
        
    }

    /**
     * Updates the ride by calling appropriate service models
     * @param array $ride_data 
     */
    private function updateRide (array $ride_data){
        
        if ($ride_data['where'] == "toAirport"){
            $rides = new Rides_Model_RidesService($ride_data['where']);
            $rides->updateRideToAirport($ride_data);
        }else if ($ride_data['where'] == "fromAirport"){
            $rides = new Rides_Model_RidesService($ride_data['where']);
            $rides->updateRideFromAirport($ride_data);
        }
     $this->_forward('success', 'index', 'rides', $ride_data);

    }

}















