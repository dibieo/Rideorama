<?php

/*
 *User ORM Entity
 */


namespace Rideorama\Entity;
/**
 * 
 * @Table(name="preuser")
 * @Entity(repositoryClass="Rideorama\Entity\Repository\PreUserRepository")
 * @author ovo
 */
class PreUser extends \Rideorama\Entity\rideoramaEntity
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
     * @Column(type="string",length=10,nullable=true)
     * @var string
     * This sex of the user
     */
    protected $sex;

    
    
       /**
     * @Column(type="string",length=60,nullable=true)
     * @var string
     * This store the role of the user
     */
    protected $email_hash;
    
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
    
    
}
