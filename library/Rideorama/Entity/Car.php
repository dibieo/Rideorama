<?php

/*
 * Car Entity
 * This entity stores information about a user's car
 * Stores the two other pictures of the ride.
 */

namespace Rideorama\Entity;
/**
 * 
 * @Table(name="car")
 * @Entity(repositoryClass="Rideorama\Entity\Repository\CarRepository")
 * @author ovo
 */
class Car extends \Rideorama\Entity\rideoramaEntity
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
     *
     * @var string $make
     * @Column(type="string",nullable=true)
     * @rating
     */
    protected $make;
    
   /**
     *
     * @var string $model
     * @Column(type="string",nullable=true)
     * @rating
     */
    protected $model;
    
    
     /**
     *
     * @var integer $year
     * @Column(type="integer",nullable=true)
     * @rating
     */
    protected $year;
    
        
    /**
     * @Column(type="string",length=60,nullable=true)
     * @var string
     * This store the profile picture of the user's car
     */
    protected $car_profile_pic;
    
    
    /**
     *
     * @var string $picture1
     * @Column(type="string",nullable=true)
     * @rating
     */
    protected $picture1;
    
    
    /**
     *  @var string $picture2
     *  @Column(type="string",nullable=true)
     */
    protected $picture2;
    
     /**
     * 
     * @OneToOne(targetEntity="User", inversedBy = "car")
     *  @JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;
    
}