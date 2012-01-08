<?php

require_once 'facebook.php'; //Load Facebook Api

class Account_UserController extends Zend_Controller_Action
{

    protected $_user = null;

    protected $_fb = null;
    
    protected $fbsession = null;

    public function init()
    {
        /* Initialize action controller here */
        $this->_user = new Account_Model_UserService();
        
       // echo Zend_Registry::get('role');
        $this->_fb = new Facebook(array(
            'appId' => '239308316101537',
            'secret' => 'ce008ac5b02c0c21641a38b6acbd9b2b',
            'cookie' => true,
         ));
    }

    public function indexAction()
    {
         if (Zend_Auth::getInstance()->hasIdentity()){
           
           echo "Session still valid!";
       }else{
           
           echo "Session invalid";
       
    }
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
        echo "Outside session: " . $session;
    //If User is logged into facebook get profile info we need
    if ($session) {
        echo "Session : " . $session;
        try {
        
        $me = $fb->api('/me');
        $email= $me['email'];
        $findUser = $this->_user->getUserByEmail($email);
        
        } catch(FacebookApiException $e){
            error_log($e);
            $user = null;  
        }
        //First time facebook user,then add them to the database
        if ($findUser == null){
      
            $this->firstTimeFacebookUser($me);
            $this->processFacebookLoginAndRedirect($email, $me['id']);
          //  $this->_redirect(Zend_Registry::get('return')); // Send the user to where he's coming from

            
        }else {
          ///This user already exists and we didn't just logout with facebook so we proceed to regular login.
          $this->processFacebookLoginAndRedirect($email, $me['id']);

        }

    
    }
           
         $loginUrl = $fb->getLoginUrl(array(
             'scope' => 'email,offline_access,publish_stream,user_birthday,user_work_history,user_about_me'
             
             )); // Gets Facebook LoginURL
         
        //Assign login & logout urls to the view
        $this->view->fblogin = $loginUrl;
        
        $loginForm = new Account_Form_Login();

       $this->view->loginForm = $loginForm;
       
       if ($this->getRequest()->isPost()){
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
          
        //echo "we got here";
      
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
       
        $form = new Account_Form_User();
        $this->view->form = $form;
        
        if ($this->getRequest()->isPost()){
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)){
                $data = $form->getValues();
                $email_hash = md5($data['email']);
                $password = md5($data['password']);
               
                $this->_user->addUser($data['first_name'],
                $data['last_name'], $data['email'], $email_hash,
                $data['profession'], $data['sex'],
                $data['profile_pic'],$password);
                
                $this->_helper->redirector('index');
                        
            }else{
                
                $form->populate($formData);
            }
            
        
    }
    }

    
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
        // action body
        $this->_user->activateUserAccount($this->_getParam('hash'));
        
    }

    /**
* This function adds a first time facebook user to the database
* and logs them into the application also.
* @param $me type JSON object.
*/
    private function firstTimeFacebookUser($me){
           $firstName = $me['first_name'];
            $lastName = $me['last_name'];
            $profile_pic = "https://graph.facebook.com/ " . $me['id'] . "/picture";
            $password = $me['id'];
            $sex = $me['gender'];
            $email = $me['email'];
            $email_hash = md5($email);
            $password_hash = md5($password);
            $profession = "other";
            
            //Add to the database
            $u = new Account_Model_UserService();
            $u->addConfirmedUser($email, $profession, $sex, $firstName, $lastName, $profile_pic, $email_hash, $password_hash, "true");
           
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
                $this->_redirect($redirect);
            }
    }
}


    






