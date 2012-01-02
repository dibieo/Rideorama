<?php

/*
 * Requests_from_landmark Entity
 */

namespace Rideorama\Entity;


/**
 * 
 * @Table(name="requests_from_landmark")
 * @Entity(repositoryClass="Rideorama\Entity\Repository\RequestsfromlandmarkRepository")
 * @author ovo
 */
class Requestsfromlandmark extends \Rideorama\Entity\rideoramaEntity {
    
     /**
     *
     * @var integer $id
     * @Column(name="id", type="integer",nullable=false)
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    
    
    /**
     * @Column(type="text",nullable=true)
     * @var text
     * 
     */
    protected $request_msg;
    
     /**
     *
     * @var integer
     * @ManyToOne(targetEntity="User")
     * @JoinColumns({
     *  @JoinColumn(name="publisher_id", referencedColumnName="id")
     * })
     */
    protected $publisher;
   
    
      /**
     *
     * @var Landmark
     * @ManyToOne(targetEntity="Landmark")
     * @JoinColumns({
     *  @JoinColumn(name="landmark_id", referencedColumnName="id")
     * })
     */
   protected $landmark;
    
    /**
     * @Column(type="date",nullable=false)
     * @var datetime
     * This stores the date trip was posted.
     */
    protected $post_date;
     
    /**
     * @Column(type="date",nullable=false)
     * @var datetime
     * This stores the departure date of the trip.
     */
    protected $departure_date;
     
   /**
     * @Column(type="time",nullable=false)
     * @var time
     * This stores the departure time of the trip.
     */
    protected $departure_time;
    
  
    
    /**
     * @Column(type="string",nullable=true)
     * @var float
     * This stores the distance of trip.
     */
    protected $distance = null;

    
    /**
     *
     * @var integer
     * @Column(name="luggages", type="integer",nullable=false)
     */
    protected $num_luggages = 0;
    
      /**
     * @Column(type="string", length = 60, nullable=true)
     * @var string
     * This provides a brief description on the size of the luggage
     */
    protected $luggage_size;
    
    /**
     * This is the cost per trip
     * @var integer
     * @Column(name="cost", type="integer",nullable=false)
     */
    protected $cost = 0;
    
    /**
     * Departure location
     * @var string
     * @Column(name="destination_address", type="string")
     * This stores the pick up address of the user
     */
    protected $destination_address;
    
    
    /**
     *
     * @var string
     * @Column(name="pick_up_address", type="string")
     * This stores the pick up address of the user.
     * In this case it would be where at the landmark they'll like to be picked up from
     */
    protected $pick_up_address;
    
    /**
      @Column(type="string", nullable=true)
     * @var float
     * This stores the duration of trip.
     */
    protected $duration;
    
    public function __construct(){
     
      $this->post_date = new \DateTime(date("Y-m-d"));
    }
}