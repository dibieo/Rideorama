<?php

/*
 * Rating Entity
 */

namespace Rideorama\Entity;
/**
 * 
 * @Table(name="rating")
 * @Entity(repositoryClass="Rideorama\Entity\Repository\RatingRepository")
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
    protected $id;

    
    /**
     *
     * @var integer $rating
     * @Column(name="rating", type="integer",nullable=false)
     * @rating
     */
    protected $rating;
    
    /**
     * @Column(type="text",nullable=true)
     * @var text
     * 
     */
    protected $comment_text;
    
     /**
     * The user doing the rating
     * @var User
     * @ManyToOne(targetEntity="User")
     * @JoinColumns({
     *  @JoinColumn(name="commenter_id", referencedColumnName="id")
     * })
     */
    protected $commenter;
   
    
      /**
     * The user who is being rated
     * @var User
     * @ManyToOne(targetEntity="User")
     * @JoinColumns({
     *  @JoinColumn(name="commentee_id", referencedColumnName="id")
     * })
     */
    protected $commentee;
    
    /**
     * @Column(type="datetime",nullable=false)
     * @var datetime
     * This stores the user's email
     */
    protected $date;

    
}