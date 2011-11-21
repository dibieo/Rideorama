<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Rideorama\Entity;
/**
 * 
 * @Table(name="state")
 * @Entity(repositoryClass="Rideorama\Entity\Repository\StateRepository")
 * @author ovo
 */
class State extends \Rideorama\Entity\rideoramaEntity
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
     * @var \Bisna\Application\Container\DoctrineContainer
     */
    
    /**
    public function __set($property,$value)
    {
        $this->$property = $value;
    }

}


     * @Column(type="string",length=60,nullable=true)
     * @var string
     */
    protected $abbv;
    
    public function __construct() {
        
        
    }

 
   /**
     *
     * @param \Doctrine\Common\Collections\Collection $property
     *
     * @OneToMany(targetEntity="City",mappedBy="state", cascade={"persist","remove"})
     */
    protected $cities;
    
    public function addState($name, $abbv){
        
        $this->name = $name;
        $this->abbv = $abbv;
        
    }
}