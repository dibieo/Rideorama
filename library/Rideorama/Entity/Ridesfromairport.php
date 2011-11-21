<?php

/*
 * Rides_to_airport Entity
 */

namespace Rideorama\Entity;


/**
 * 
 * @Table(name="rides_from_airport")
 * @Entity(repositoryClass="Rideorama\Entity\Repository\RidesfromairportRepository")
 * @author ovo
 */
class Ridesfromairport extends \Rideorama\Entity\rideoramaEntity {
    
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
     * @var integer $number_of_seats
     * @Column(name="number_of_seats", type="integer",nullable=false)
     */
    protected $number_of_seats;
    
    /**
     * @Column(type="text",nullable=true)
     * @var text
     * 
     */
    protected $trip_msg;
    
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
     * @var Airport
     * @ManyToOne(targetEntity="Airport")
     * @JoinColumns({
     *  @JoinColumn(name="airport_id", referencedColumnName="id")
     * })
     */
   protected $airport;
    
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
     * @Column(type="time", nullable=false)
     * @var time
     * This stores the arrival time of the trip
     */
    protected $arrival_time;
    
    
    /**
     * @Column(type="float",nullable=false)
     * @var float
     * This stores the distance of trip.
     */
    protected $distance;

      /**
     * @Column(type="string", length = 60, nullable=true)
     * @var string
     * This provides a brief message about how much luggage the user 
     * is able to carry per passenger
     */
    protected $luggages;
    
    /**
     *
     * @var integer
     * @Column(name="cost", type="integer",nullable=false)
     */
    protected $cost = 0;
    
    /**
     *
     * @var string
     * @Column(name="pick_up_address", type="string")
     * This stores the pick up address of the user.
     * In this case it would be where in the airport to be picked up from
     * i.e. the terminal
     */
    protected $pick_up_address;
    
    
      /**
     *
     * @var string
     * @Column(name="drop_off_address", type="string")
     * This stores the drop off address
     */
    protected $drop_off_address;
    
    
    /**
     *
     * @var integer
     * @ManyToOne(targetEntity="City")
     * @JoinColumns({
     *  @JoinColumn(name="city_id", referencedColumnName="id")
     * })
     */
    protected $city;
    
    /**
      @Column(type="float",nullable=false)
     * @var float
     * This stores the duraation of trip.
     */
    protected $trip_duration;
}
