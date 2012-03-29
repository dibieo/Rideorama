<?php

/*
 * City Entity Model
 * This class defines the doctrine ORM model for cities
 */


namespace Rideorama\Entity;
/**
 * 
 * @Table(name="notifications")
 * @Entity(repositoryClass="Rideorama\Entity\Repository\NotificationsRepository")
 * @author ovo
 */
class Notifications extends \Rideorama\Entity\rideoramaEntity
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
     */
    protected $passenger_name;
    
    /**
     * @Column(type="string",length=60,nullable=true)
     * @var string
     */
    protected $driver_email;
     
   
    
    /**
     * @Column(type="string",length=60,nullable=true)
     * @var string
     */
    protected $passenger_email;
    
    /**
     * @Column(type="string",length=60,nullable=true)
     * @var string
     */
    protected $driver_name;


    /**
     *
     * @var datetime
     * @Column(type="datetime", nullable=false)
     */
    protected $trip_date;
}