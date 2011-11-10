<?php

/*
 *Airport ORM Entity
 */


namespace Rideorama\Entity;
/**
 * 
 * @Table(name="airport")
 * @Entity
 * @author ovo
 */
class Airport extends \Rideorama\Entity\rideoramaEntity
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
    protected $name;
    
    /**
     * @Column(type="string",length=60,nullable=true)
     * @var string
     */
    protected $iata;
   
    
    /**
     * @Column(type="string",length=60,nullable=true)
     * @var string
     * This store the a picture of the aiport
     */
    protected $pic;
    
    /**
     *
     * @var City
     * @ManyToOne(targetEntity="City")
     * @JoinColumns({
     *  @JoinColumn(name="city_id", referencedColumnName="id")
     * })
     */
    protected $city;

    
    

    
}