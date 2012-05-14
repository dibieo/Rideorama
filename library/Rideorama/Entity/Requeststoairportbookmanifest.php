<?php

/**
 * This class stores a manifest of everyone who has booked a particular trip
 */


namespace Rideorama\Entity;


/**
 * 
 * @Table(name="requests_to_airport_bookmanifest")
 * @Entity(repositoryClass="Rideorama\Entity\Repository\RequeststoairportbookmanifestRepository")
 * @author ovo
 */
class Requeststoairportbookmanifest extends \Rideorama\Entity\rideoramaEntity
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
     * @ManyToOne(targetEntity="Requeststoairport")
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
     
     * @Column(type="datetime", nullable=false)
     */
    protected $date_booked;
    
   /**
     * @Column(type="string",length=60,nullable=true)
     * @var string
     */
    protected $response_status;
 
     /**
     *
     * @var integer
     * @ManyToOne(targetEntity="User")
     * @JoinColumns({
     *  @JoinColumn(name="driver_id", referencedColumnName="id")
     * })
     */
    protected $driver;
    

}