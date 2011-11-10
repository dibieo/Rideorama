<?php

/*
 * Rating Entity
 */

namespace Rideorama\Entity;
/**
 * 
 * @Table(name="rating")
 * @Entity
 * @author ovo
 */
class Rating extends \Rideorama\Entity\rideoramaEntity
{
    /**
     *
     * @var integer $id
     * @Column(name="id", type="integer",nullable=false)
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    
    /**
     *
     * @var integer $rating
     * @Column(name="rating", type="integer",nullable=false)
     * @rating
     */
    private $rating;
    
    /**
     * @Column(type="text",nullable=true)
     * @var text
     * 
     */
    private $comment_text;
    
     /**
     *
     * @var User
     * @ManyToOne(targetEntity="User")
     * @JoinColumns({
     *  @JoinColumn(name="commenter_id", referencedColumnName="id")
     * })
     */
    private $commenter;
   
    
      /**
     *
     * @var User
     * @ManyToOne(targetEntity="User")
     * @JoinColumns({
     *  @JoinColumn(name="commentee_id", referencedColumnName="id")
     * })
     */
    private $commentee;
    
    /**
     * @Column(type="datetime",nullable=false)
     * @var datetime
     * This stores the user's email
     */
    private $date;

    
}