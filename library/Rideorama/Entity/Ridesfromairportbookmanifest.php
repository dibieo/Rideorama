<?php

/**
 * This class stores a manifest of everyone who has booked a particular trip
 */


namespace Rideorama\Entity;


/**
 * 
 * @Table(name="rides_from_airport_bookmanifest")
 * @Entity(repositoryClass="Rideorama\Entity\Repository\RidesfromairportbookmanifestRepository")
 * @author ovo
 */
class Ridesfromairportbookmanifest extends \Rideorama\Entity\rideoramaEntity
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
     * @var integer
     * @ManyToOne(targetEntity="Ridesfromairport")
     * @JoinColumns({
     *  @JoinColumn(name="trip_id", referencedColumnName="id")
     * })
     */
    protected $trip;

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
     * @var integer
     * @ManyToOne(targetEntity="User")
     * @JoinColumns({
     *  @JoinColumn(name="passenger_id", referencedColumnName="id")
     * })
     */
    protected $passenger;

}