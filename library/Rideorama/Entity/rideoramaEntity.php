<?php

namespace Rideorama\Entity;

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

 abstract class rideoramaEntity {
     
    public function __get($property)
    {
        return $this->$property;
    }
    
    public function __set($property,$value)
    {
        $this->$property = $value;
    }

 }