<?php

/*
 * Landmark Entity Model
 * This class defines the doctrine ORM model for landmarks
 */


namespace Rideorama\Entity;
/**
 * 
 * @Table(name="landmark")
 * @Entity(repositoryClass="Rideorama\Entity\Repository\LandmarkRepository")
 * @author ovo
 */
class Landmark extends \Rideorama\Entity\rideoramaEntity
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
    protected $pic;
   
    /**
     *
     * @var State
     * @ManyToOne(targetEntity="LandmarkCategory")
     * @JoinColumns({
     *  @JoinColumn(name="LandmarkCategory_id", referencedColumnName="id")
     * })
     */
    protected $landmark_category;
    
    
     
   /**
     *
     * @param \Doctrine\Common\Collections\Collection $property
     *
     * @OneToMany(targetEntity="Ridestolandmark",mappedBy="landmark", cascade={"persist","remove"})
     */
    protected $ridestolandmark;
    
    
   /**
     *
     * @param \Doctrine\Common\Collections\Collection $property
     *
     * @OneToMany(targetEntity="Ridesfromlandmark",mappedBy="landmark", cascade={"persist","remove"})
     */
    protected $ridesfromlandmark;



     /**
     *
     * @param \Doctrine\Common\Collections\Collection $property
     *
     * @OneToMany(targetEntity="Requeststolandmark",mappedBy="landmark", cascade={"persist","remove"})
     */
    protected $requeststolandmark;
    
     /**
     *
     * @param \Doctrine\Common\Collections\Collection $property
     *
     * @OneToMany(targetEntity="Requestsfromlandmark",mappedBy="landmark", cascade={"persist","remove"})
     */
    protected $requestsfromlandmark;
}

