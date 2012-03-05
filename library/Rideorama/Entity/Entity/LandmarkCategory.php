<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


namespace Rideorama\Entity;
/**
 * 
 * @Table(name="landmarkcategory")
 * @Entity(repositoryClass="Rideorama\Entity\Repository\landmarkcategoryRepository")
 * @author ovo
 */
class LandmarkCategory extends \Rideorama\Entity\rideoramaEntity
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
     * @param \Doctrine\Common\Collections\Collection $property
     *
     * @OneToMany(targetEntity="Landmark",mappedBy="LandmarkCategory", cascade={"persist","remove"})
     */
    protected $landmarks;

    }
