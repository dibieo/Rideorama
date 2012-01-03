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
     * @param type $first_name
     * @param type $last_name
     * @param type $email
     * @param type $email_hash
     * @param type $profession
     * @param type $sex
     * @param type $profile_pic
     * @param type $password
     */
    public function addUser($first_name, $last_name, $email, $email_hash,
                            $profession, $sex, $profile_pic, $password){
        
     $this->puser->addUserToDatabase($email, $profession,$sex,$first_name, $last_name,
                          $profile_pic, $email_hash, $password);
    
        $this->em->persist($this->puser);
        $this->em->flush(); 
        
        $url = $this->emailLink("account", "user", "confirm", array("hash" => $email_hash));
        
        $email_options = array(
            'recipient_name' => $first_name . ' ' . $last_name,
            'recipient_email' => $email,
            'subject' => 'Thank you for registering with Rideorama',
            'body' => "<a href= $url>Please click on this link to activate your regsitration.</a>"
     
        );
        
        
        $this->sendEmail($email_options);
    }
    
    public function activateUserAccount($hash){
        
       $user = $this->em->getRepository('Rideorama\Entity\PreUser')->findOneBy(array('email_hash' => $hash));
     //  Zend_Debug::dump($user);
      $this->em->remove($user);
      
       $this->addConfirmedUser($user->email, $user->profession, $user->sex, $user->first_name,
                               $user->last_name, $user->profile_pic, $user->email_hash, $user->password_hash);
       
    }
    
    
    /**
     * Adds confirmed user to the database
     * @param type $email
     * @param type $profession
     * @param type $sex
     * @param type $firstname
     * @param type $lastname
     * @param type $profile_pic
     * @param type $email_hash
     * @param type $password_hash 
     */
    public function addConfirmedUser($email, $profession, $sex, $firstname, $lastname, $profile_pic,
                                     $email_hash, $password_hash, $flogin)
     {
        $this->user->addUserToDatabase($email, $profession, $sex, $firstname, $lastname, $profile_pic,
                              $email_hash, $password_hash, $flogin);
    
        $this->em->persist($this->user);
        $this->em->flush(); 
    }
    
   
    
    /**
     * Finds an returns and exisiting user 
     * @param type $email
     * @return type User Entity
     */
    public function getUserByEmail($email){
        
        $user = $this->em->getRepository('Rideorama\Entity\User')->findOneBy(array('email' => $email));
        return $user;
    }
    
     public function getUser($id){
        
        return $this->em->find('\Rideorama\Entity\User', $id);
    }
   
}

