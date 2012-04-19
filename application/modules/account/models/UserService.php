<?php

/**
 * This Service layer handles the user and preuser entity models.
 * It provides an interface for performing CRUD operations on both entities.
 */
class Account_Model_UserService extends Application_Model_Service
{

    protected $user;   //User, this is an actual user that exists
    protected $puser; //Pre User. This user is stored in a database prior to verification
    
    public function __construct() {
        parent::__construct();
        $this->user = new \Rideorama\Entity\User();
        $this->puser = new \Rideorama\Entity\PreUser();
    }
    
    /**
     * This adds a user to the database and sends them an email.
     * @param string $first_name
     * @param string $last_name
     * @param string $email
     * @param string $email_hash
     * @param string $profession
     * @param string $sex
     * @param string $profile_pic
     * @param string $password
     */
    public function addUser($first_name, $last_name, $email, $email_hash,
                            $profession, $sex, $profile_pic, $password){
        
     $this->puser->addUserToDatabase($email, $profession,$sex,$first_name, $last_name,
                          $profile_pic, $email_hash, $password);
    
        $this->em->persist($this->puser);
        $this->em->flush(); 
        
        $url = $this->emailLink("account", "user", "activate", array("hash" => $email_hash));
        
        $email_options = array(
            'recipient_name' => $first_name . ' ' . $last_name,
            'recipient_email' => $email,
            'subject' => 'Thank you for registering with Rideorama',
            'body' => "<a href= $url>Please click on this link to activate your regsitration.</a>"
     
        );
        
        
        $this->sendEmail($email_options);
    }
    
    /**
     * Update the user's profile
     * @param int $id
     * @param array $data 
     */
    public function updateProfile($id, array $data){
        
        //print_r($data); 
        $user = $this->getUser($id);
        $user->first_name = $data['first_name'];
        $user->last_name = $data['last_name'];
        $user->age = $data['age'];
        $user->profession = $data['occupation'];
        $user->email = $data['email'];
        $user->paypal_email = $data['paypal_email'];
        $user->telephone = $data['phone'];
        if (isset($data['profile_pic'])){
          $user->profile_pic = $data['profile_pic'];
        }
        $this->em->persist($user);
        $this->em->flush();
    }
    
    /**
     * Updates a user's paypal email address
     * @param \Rideorama\Entity $user entity
     * @param string $paypal User's paypal email address
     */
    public function updatePaypal($user, $paypal){
        
        $user->paypal_email = $paypal;
        $this->em->persist($user);
        $this->em->flush();
    }
    
    
    /**
     * This function registers a new user on the system.
     * @param array $data Collection of information for first time registered user
     */
    public function registerUser(array $data){
         $this->puser->first_name = $data['first_name'];
         $this->puser->last_name = $data['last_name'];
         $this->puser->email = $data['email'];
         $this->puser->email_hash = $data['email_hash'];
         $this->puser->password_hash = $data['password'];
         $this->puser->date_registered = new \DateTime(date("Y-m-d H:i:s"));
         $this->puser->last_login = new \DateTime(date("Y-m-d H:i:s"));
         $this->puser->sex = $data['sex'];
         
         $this->em->persist($this->puser);
         
         $this->em->flush();
         
         $email = new Application_Model_EmailService();
         $email->sendRegistrationEMail($data['first_name'], $data['last_name'], 
                                      $data['email_hash'], $data['email']);
          
    }
    
    /**
     * Activates a user account
     * Begins by removing the user from the PreUser table and adding him/her to the user table
     * @param string $hash
     * @param array $data 
     */
    public function activateUserAccount($hash, array $data){
       
       $user = $this->em->getRepository('Rideorama\Entity\PreUser')->findOneBy(array('email_hash' => $hash));
     // Remove the user from preuser model;
     // $this->em->remove($user);
      $user_data = array('email' => $user->email, 'profession' => $data['occupation'], 'sex' => $user->sex,
                    'first_name' => $user->first_name, 'last_name' => $user->last_name,
                    'user_profile_pic' => $data['user_profile_pic'], 
                    'password_hash' => $user->password_hash, 'email_hash' => $user->email_hash, 
                    'age' => $data['age'], 'phone_number' => $data['phone_number']
                    );
      
      $user = $this->addConfirmedUser($user_data);
      
      //If the  user says they have a car then skip
      if ($data['has_no_car'] == "false" ){
      //  echo "User did not check i dont have a car box, so value is: " . $data['has_no_car'];
        $this->addCarProfile($data, $user);
      }
    }
    
    /**
     * Intially sets up the car profile
     * @param array $data
     * @param User Entity $user
     */
    public function addCarProfile($data, $user){
        
        $car = new Account_Model_CarService();
        $car->setUpCarProfile($data, $user);
    }
    
    /**
     * Adds confirmed user to the database
     * @param string $email
     * @param string $profession
     * @param string $sex
     * @param string $firstname
     * @param string $lastname
     * @param string $profile_pic
     * @param string $email_hash
     * @param string $password_hash 
     * return the user object
     */
    public function addConfirmedUser(array $data, $facebook = null)
     {
        $this->user->email = $data['email'];
        $this->user->first_name = $data['first_name'];
        $this->user->last_name = $data['last_name'];
        $this->user->sex = $data['sex'];
        $this->user->age = $data['age'];
        $this->user->telephone = $data['phone_number'];
        $this->user->profile_pic = $data['user_profile_pic'];
        $this->user->email_hash = $data['email_hash'];
        $this->user->date_registered = new \DateTime(date("Y-m-d H:i:s"));
        $this->user->last_login = new \DateTime(date("Y-m-d H:i:s"));
        $this->user->profession = $data['profession'];
        $this->user->password_hash = $data['password_hash'];
        $this->user->facebook_login = is_null($facebook) ? "false" : "true";
        $this->user->role = "user";
        $this->em->persist($this->user);
        $this->em->flush(); 

        return $this->user;
        
    }
    
   
    
    /**
     * Finds and returns and exisiting user by passing in email
     * @param string $email
     * @param entity (User entity passed in by default. Can also pass in the PreUser entity)
     * @return \Rideorama\Entity\User User Entity
     */
    public function getUserByEmail($email, $entity = '\Rideorama\Entity\User'){
        
        $user = $this->em->getRepository($entity)->findOneBy(array('email' => $email));
        return $user;
    }
    
    /**
     * Returns a user object when given the password_hash
     * @param string $password_hash
     * @param string $entity
     * @return \Rideorama\Entity\User User Entity 
     */
    public function getUserByPasswordHash($password_hash, $entity = '\Rideorama\Entity\User'){
        
        $user = $this->em->getRepository($entity)->findOneBy(array('password_hash' => $password_hash));
        return $user;
    }
    
    /**
     * Get's a user when given the user's email hash
     * @param string $email_hash
     * @param string $entity
     * @return \Rideorama\Entity\User The User Entity 
     */
    public function getUserByEmailHash($email_hash, $entity='\Rideorama\Entity\User'){
        
        $user = $this->em->getRepository($entity)->findOneBy(array('email_hash' => $email_hash));
        return $user;
    }
    
     public function getUser($id){
        
        return $this->em->find('\Rideorama\Entity\User', $id);
    }
   
 
    /**
     * Updates the user's paypal email address.
     * Usually called from the post ride form if the user hasn't submitted a paypal address
     * @param integer $user_id
     * @param string $paypal_address 
     */
    public function updateUserPaypalEmail($user_id, $paypal_address){
        $user = $this->getUser($user_id);
        $user->paypal_email = $paypal_address;
        $this->em->persist($user);
        $this->em->flush();
    }
   
    /**
     * Updates a user's password
     * @param string $email_hash
     * @param string $password 
     */
    public function updateUserPassword($email_hash, $password){
      
      $user = $this->getUserByEmailHash($email_hash);
      $user->password_hash = md5($password);
      $user->email_hash = md5($email_hash);
      
      $this->em->persist($user);
      $this->em->flush();
     }
     
     /**
      * Gets rides/requests from the database that the user published
      * @param type $user_id
      * @param type $entity
      * @return array 
      */
     public function getRides($user_id, $entity){
      
      $q = $this->em->createQuery("select u.id, a.iata, u.city, u.cost, u.departure_date
                                   from '$entity' u JOIN u.airport a
                                    where u.publisher = $user_id");
      $result = $q->execute();
      
      return $result;
     }
     
     
     public function getPassengerBookedRides($user_id, $entity, $manifest_entity){
         
      $q = $this->em->createQuery("select t.id from '$manifest_entity' t where t.passenger = $user_id");
      $result = $q->execute();
      return $result;
     }
     /**
      * Gets the total number of registered users
      * @return int 
      */
     public function getTotalNumberofRegisteredUsers(){
         
        $q = $this->em->createQuery("SELECT count(u.id) AS t_count from \Rideorama\Entity\User u");
        $result = $q->execute();
        $result = $result[0];
        return $result['t_count'];
     }
}

