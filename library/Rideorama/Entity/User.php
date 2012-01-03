<?php

/*
 *User ORM Entity
 */


namespace Rideorama\Entity;
/**
 * 
 * @Table(name="user")
 * @Entity(repositoryClass="Rideorama\Entity\Repository\UserRepository")
 * @author ovo
 */
class User extends \Rideorama\Entity\rideoramaEntity

{
     /**
     *
     * @var integer $id
     * @Column(name="id", type="integer",nullable=false)
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @Column(type="string",length=60,nullable=true)
     * @var string
     * This stores the user's first name
     */
    protected $first_name;
    
    /**
     * @Column(type="string",length=60,nullable=true)
     * @var string
     * This stores the user's last name
     */
    protected $last_name;
   
    
    /**
     * @Column(type="string",length=60,nullable=true)
     * @var string
     * This stores the user's email
     */
    protected $email;
    
    /**
     * @Column(type="string",length=60,nullable=true)
     * @var string
     * This stores a hash of the user_password
     */
    protected $password_hash;
    
    /**
     * @Column(type="string",length=60,nullable=true)
     * @var string
     * This store the a picture of the aiport
     */
    protected $profile_pic;
    
     /**
     * @Column(type="string",length=10,nullable=true)
     * @var string
     * This store the role of the user
     */
    protected $role = "guest";
    
       /**
     * @Column(type="string",length=10,nullable=true)
     * @var string
     * This sex of the user
     */
    protected $sex;

    
    
     /**
     * @Column(type="string",length=10,nullable=true)
     * @var string
     * This store the profession of the user
     */
    protected $profession;
    
       /**
     * @Column(type="string",length=60,nullable=true)
     * @var string
     * This store the role of the user
     */
    protected $email_hash;
    
    /**
     * @Column(type="string",length=10,nullable=true)
     * @var boolean
     * This stores the registration type of the user
     */
    protected $facebook_login = "false";
    
    /**
     *
     * @var datetime
     * @Column(type="datetime", nullable=false)
     */
    protected $last_login;
    
    /**
     *
     * @var datetime
     * @Column(type="datetime", nullable=false)
     */
    protected $date_registered;
    
    
    
    /**
     *
     * @param \Doctrine\Common\Collections\Collection $property
     *
     * @OneToMany(targetEntity="Ridestoairport",mappedBy="user", cascade={"persist","remove"})
     */
    protected $rides_to_airport;
    
      /**
     *
     * @param \Doctrine\Common\Collections\Collection $property
     *
     * @OneToMany(targetEntity="Ridestolandmark",mappedBy="user", cascade={"persist","remove"})
     */
    protected $rides_to_landmark;
    
      /**
     *
     * @param \Doctrine\Common\Collections\Collection $property
     *
     * @OneToMany(targetEntity="Ridesfromlandmark",mappedBy="user", cascade={"persist","remove"})
     */
    protected $rides_from_landmark;
    
      /**
     *
     * @param \Doctrine\Common\Collections\Collection $property
     *
     * @OneToMany(targetEntity="Ridesfromlandmark",mappedBy="user", cascade={"persist","remove"})
     */
    protected $rides_from_airport;
    
    
      /**
     *
     * @param \Doctrine\Common\Collections\Collection $property
     *
     * @OneToMany(targetEntity="Requeststolandmark",mappedBy="user", cascade={"persist","remove"})
     */
    protected $requests_to_landmark;
    
      /**
     *
     * @param \Doctrine\Common\Collections\Collection $property
     *
     * @OneToMany(targetEntity="Requestsfromlandmark",mappedBy="user", cascade={"persist","remove"})
     */
    protected $requests_from_landmark;
    
      /**
     *
     * @param \Doctrine\Common\Collections\Collection $property
     *
     * @OneToMany(targetEntity="Requestsfromairport",mappedBy="user", cascade={"persist","remove"})
     */
    protected $requests_from_airport;
    
   /**
     *
     * @param \Doctrine\Common\Collections\Collection $property
     *
     * @OneToMany(targetEntity="Requeststoairport",mappedBy="user", cascade={"persist","remove"})
     */
    protected $requests_to_airport;
    
    
    /**
     * This function  adds a new user to the database
     * @param type $email
     * @param type $first_name
     * @param type $last_name
     * @param type $profile_pic
     * @param type $email_hash 
     */
    public function addUserToDatabase($email, $profession,$sex,$first_name, $last_name, $profile_pic, $email_hash, $password, $flogin = false){
        
        $this->email = $email;
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->sex = $sex;
        $this->profile_pic = $profile_pic;
        $this->email_hash = $email_hash;
        $this->date_registered = new \DateTime(date("Y-m-d H:i:s"));
        $this->last_login = new \DateTime(date("Y-m-d H:i:s"));
        $this->profession = $profession;
        $this->password_hash = $password;
        $this->facebook_login = $flogin;
        $this->role = "user";
    }
    
}
