<?php

require_once 'facebook.php'; //Load Facebook Api

/**
 * 
 */
class Account_UserController extends Zend_Controller_Action
{

    protected $_user = null;

    protected $_fb = null;
    
    protected $fbsession = null;
    
    protected $registration_form = null;
    
    protected $account_completion_form = null;

    public function init()
    {
        /* Initialize action controller here */
        $this->_user = new Account_Model_UserService();
        $fboptions = Zend_Registry::get('config')->facebook;

        $this->_fb =  new Facebook(array(
            'appId' => $fboptions->applicationID,
            'secret' => $fboptions->appSecret,
            'cookie' => true
         ));
        
        $this->registration_form = new Account_Form_User();
        $this->account_completion_form = new Account_Form_Completeprofile();
    }

    public function indexAction()
    {
        
        $current_user = $this->_user->getUser(Zend_Auth::getInstance()->getIdentity()->id);
        $this->view->user = $current_user;
    }

    public function loginAction()
    {
       //If the user is logged in, redirect them back to their login page.
        if (Zend_Auth::getInstance()->hasIdentity()){
            
           return $this->_forward('index', 'user', 'account');
        }
       
       $session = null;
        //Gets instance of facebook
        $fb = $this->_fb;
        $session = $this->_fb->getUser(); //Stores the facebook user session
        $this->fbsession = $session;
        $me = null;

       //If User is logged into facebook get profile info we need
    if ($session) {
         // Set facebook_status of the fb
         
        try {
        
        $me = $fb->api('/me');
        $email= $me['email'];
        $findUser = $this->_user->getUserByPasswordHash(md5($me['id']));
        
        
        } catch(FacebookApiException $e){
            error_log($e);
            $user = null;  
        }
        //First time facebook user,then add them to the database and log in
        try{
        if ($findUser == null){
            
            $already_registered = $this->isFacebookUserAlreadyRegistered($email);
            if ($already_registered){
                throw new Exception("We're sorry but it appears you already registered an account with us
                 .Please log in with that account. If you need further assistance pls talk to an operator with the chat box below");
            }else{
            $this->firstTimeFacebookUser($me);
            $this->processFacebookLoginAndRedirect($email, $me['id']);
            }
            
        }else {
          ///This user already exists and we didn't just logout with facebook so we proceed to regular login.
          $email = $findUser->email;
          $this->processFacebookLoginAndRedirect($email, $me['id']);

        }
        }catch(Exception $ex){
            $this->view->errMsg = $ex->getMessage();
        }
    
    }
           
         $loginUrl = $fb->getLoginUrl(array(
             'scope' => 'email,offline_access,publish_stream,user_birthday,user_work_history,user_about_me'
             
             )); // Gets Facebook LoginURL
         
        //Assign login & logout urls to the view
        $this->view->fblogin = $loginUrl;
        
        $loginForm = new Account_Form_Login();

       $this->view->loginForm = $loginForm;
       
       if ($this->_helper->FlashMessenger->hasMessages()) { 
              // echo "messages dey oh!";
               $this->view->messages = $this->_helper->FlashMessenger->getMessages(); 
         }
       
       if ($this->getRequest()->isPost()){
           
           //Get flashmessages from previous request if it exist
           

           $formData = $this->getRequest()->getPost();
    
           if ($loginForm->isValid($formData)){
               
               
               $authAdapter = $this->getAuthAdapter();
               $email = $loginForm->getValue('email');
               $password = $loginForm->getValue('password');
               $return = Zend_Registry::isRegistered('return') ? Zend_Registry::get('return') : 'account/user/index';
               $result = $this->checkAuthCred($authAdapter, $email, $password);
               
               if ($result->isValid()){
             
                   $this->writeAuthStorage($authAdapter, Zend_Auth::getInstance(), $return);
                   
             }else{
                 
                 $this->view->errorMessage = "Email or password is incorrect";
             }
                 
           }
           
       }
  
        
       
    }
 /**
* Checks the authentication credentials of a user;
* @param type $authAdapter
* @return type
*
*/
    private function checkAuthCred($authAdapter, $email, $password)
    {
        
         $authAdapter->setIdentity($email)
                           ->setCredentialTreatment('md5(?)')
                           ->setCredential($password);
           $auth = Zend_Auth::getInstance();
         $result = $auth->authenticate($authAdapter);
         return $result;
        
    }

   
    
    /**
* Sets the login credentials of current user and forwards to the requested action
* before login
* @param type $authAdapter
* @param type $auth
* @param type $forward
* @return type void
*/
    private function writeAuthStorage($authAdapter, $auth, $forward = null)
    {
      
         $identity = $authAdapter->getResultRowObject();
          $authStorage = $auth->getStorage();
          $authStorage->write($identity);
      
        if ($this->fbsession == null){
        
        if ($forward != null){
              return $this->_redirect($forward);
}
    else{
            return $this->_forward('index', 'user', 'account');
        }
        }
    }

    public function logoutAction()
    {
        // action body
        $this->_fb->destroySession(); //Clears facebook session
        Zend_Auth::getInstance()->clearIdentity();
        $this->_helper->redirector('index');
    }

    public function registerAction()
    {
        // action body
        $form = $this->registration_form;
        $this->view->form = $form;
        
        if ($this->getRequest()->isPost()){
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)){
                $data = $form->getValues();
                $user = $this->_user->getUserByEmail($data['email'], '\Rideorama\Entity\PreUser');
                if ($user == null){
                $data['email_hash'] = md5($data['email']);
                //$email_hash = md5($data['email']);
                $data['password'] = md5($data['password']);
                //print_r($data);
                $this->_user->registerUser($data);
                $this->_forward('regcomplete');
                }else{
                    
                    $this->view->msg = "Our records indicate you're already registered. If you signed in facebook then 
                                        continue using that account. If you're yet to activate your account, check your email to activate it";
                }
                        
            }else{
                
                $form->populate($formData);
                }
       }
    }
    
    public function regcompleteAction(){
        
        
    }

    /**
     * Gets the Auth Adapter
     * @return Zend_Auth_Adapter_DbTable 
     */
    private function getAuthAdapter()
    {
         
        $authAdapter = new Zend_Auth_Adapter_DbTable(Zend_Db_Table::getDefaultAdapter());
        $authAdapter->setTableName('user')
                    ->setIdentityColumn('email')
                    ->setCredentialColumn('password_hash');
        
        return $authAdapter;
        
    }

    public function activateAction()
    {
         $user = $this->_user->getUserByEmailHash($this->_getParam('hash'));
         //This user hasn't activated their account
        if ($user == null){
    
        $form = new Account_Form_Completeprofile();
      
        $this->view->form = $form;
        if ($this->getRequest()->isPost()){
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)){
                $data = $form->getValues();
                 //User has not activated their account.
                $this->_user->activateUserAccount($this->_getParam('hash'), $data);
                $this->_forward('thanks');
                }
        else{
            //echo "got here";
            //print_r($formData);
            $form->populate($formData);
        }

    }
    
        }   else{
                    $this->view->msg = "Looks like this account has been activated in the past or you 
                                        already use Rideorama via Facebook. Pls contact a Rideorama
                                        representative if none of these apply to you";
                }
     //$this->flashMessenger->addMessage("Your account has been activated");
    
    }
    
    
    public function thanksAction(){
        
    }
    /**
* This function adds a first time facebook user to the database
* and logs them into the application also.
* @param $me type JSON object.
*/
    private function firstTimeFacebookUser($me){
           //get user's age
            $date1 = new DateTime(date("Y-m-d"));
            $date2 = new DateTime($me['birthday']);
            $interval = $date1->diff($date2);
            $age =  $interval->y;
            
        $data = array(
            'first_name' => $me['first_name'],
            'last_name'  => $me['last_name'],
            'user_profile_pic' => "https://graph.facebook.com/ " . $me['id'] . "/picture",
            'password_hash' => md5($me['id']),
            'sex' => $me['gender'],
            'email' => $me['email'],
            'email_hash' => md5($email),
            'profession' => "other",
            'age' => $age,
            'facebook_login' => 'true'
            );
            
            //Add to the database
            $u = new Account_Model_UserService();
            $u->addConfirmedUser($data, "true");
           
    }

    
    /**
     * Does AJAX validation on form inputs
     */
     public function validateformAction()
    {
        $this->_helper->formvalidate($this->registration_form, $this->_helper, $this->_getAllParams());

    }
    
    /**
     * Perform Ajax validation on form inputs
     */
    public function validateactivationformAction(){
        
      $this->_helper->formvalidate($this->account_completion_form, $this->_helper, $this->_getAllParams());

    }
    /**
     * This function writes the logged in Facebook account to Authstorage
     * 
     * @param type $email
     * @param type $id 
     */
    private function processFacebookLoginAndRedirect($email, $id){
          
        
           $authAdapter = $this->getAuthAdapter();
            $result = $this->checkAuthCred($authAdapter, $email, $id);
            $redirect = Zend_Registry::isRegistered('return') ? Zend_Registry::get('return') : 'account/user/index';
            if ($result->isValid()){
                $this->writeAuthStorage($authAdapter, Zend_Auth::getInstance());
                return $this->_redirect($redirect);
            }
    }
    
    /**
     * Determines if a user attempting to connect via facebook
     * has already registered with the system. If so, reject the connection
     * via facebook, else had the new user to our records
     * @param string $email
     * @return boolean true or false 
     */
    private function isFacebookUserAlreadyRegistered($email){
        
        $user = $this->_user->getUserByEmail($email);
        if ($user == null){  
            return false;
        }else{
            return true;
        }
            
    }
    
    public function myridesAction(){
     $ride = new Account_Model_UserService();
     $rides_array = array();
     $rides_from_airport = array("ridesfromairport" => $ride->getRides(Zend_Auth::getInstance()->getIdentity()->id, '\Rideorama\Entity\Ridesfromairport'));
     $rides_to_airport = array("ridestoairport" => $ride->getRides(Zend_Auth::getInstance()->getIdentity()->id, '\Rideorama\Entity\Ridestoairport'));
     $request_from_airport = array("requestsfromairport" => $ride->getRides(Zend_Auth::getInstance()->getIdentity()->id, '\Rideorama\Entity\Requestsfromairport'));
     $request_to_airport = array("requeststoairport" => $ride->getRides(Zend_Auth::getInstance()->getIdentity()->id, '\Rideorama\Entity\Requeststoairport'));
     array_push($rides_array, $rides_from_airport);
     array_push($rides_array, $rides_to_airport);
     array_push($rides_array, $request_from_airport);
     array_push($rides_array, $request_to_airport);
     $this->view->myrides = $rides_array;
    }
}


    






