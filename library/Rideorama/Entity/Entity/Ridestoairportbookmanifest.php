<?php

/**
 * This class stores a manifest of everyone who has booked a particular trip
 */


namespace Rideorama\Entity;


/**
 * 
 * @Table(name="rides_to_airport_bookmanifest")
 * @Entity(repositoryClass="Rideorama\Entity\Repository\RidestoairportbookmanifestRepository")
 * @author ovo
 */
class Ridestoairportbookmanifest extends \Rideorama\Entity\rideoramaEntity
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
     * @ManyToOne(targetEntity="Ridestoairport")
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
    
    /**
     
     * @Column(type="datetime", nullable=false)
     */
    protected $date_booked;

}