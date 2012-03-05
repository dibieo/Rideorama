<?php

/*
 * City Entity Model
 * This class defines the doctrine ORM model for cities
 */


namespace Rideorama\Entity;
/**
 * 
 * @Table(name="city")
 * @Entity(repositoryClass="Rideorama\Entity\Repository\CityRepository")
 * @author ovo
 */
class City extends \Rideorama\Entity\rideoramaEntity
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
     *
     * @var State
     * @ManyToOne(targetEntity="State")
     * @JoinColumns({
     *  @JoinColumn(name="state_id", referencedColumnName="id")
     * })
     */
    protected $state;
    
    
   /**
     *
     * @param \Doctrine\Common\Collections\Collection $property
     *
     * @OneToMany(targetEntity="Airport",mappedBy="city", cascade={"persist","remove"})
     */
    protected $airports;

}