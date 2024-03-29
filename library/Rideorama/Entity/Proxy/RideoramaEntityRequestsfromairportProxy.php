<?php

namespace Rideorama\Entity\Proxy;

/**
 * THIS CLASS WAS GENERATED BY THE DOCTRINE ORM. DO NOT EDIT THIS FILE.
 */
class RideoramaEntityRequestsfromairportProxy extends \Rideorama\Entity\Requestsfromairport implements \Doctrine\ORM\Proxy\Proxy
{
    private $_entityPersister;
    private $_identifier;
    public $__isInitialized__ = false;
    public function __construct($entityPersister, $identifier)
    {
        $this->_entityPersister = $entityPersister;
        $this->_identifier = $identifier;
    }
    private function _load()
    {
        if (!$this->__isInitialized__ && $this->_entityPersister) {
            $this->__isInitialized__ = true;
            if ($this->_entityPersister->load($this->_identifier, $this) === null) {
                throw new \Doctrine\ORM\EntityNotFoundException();
            }
            unset($this->_entityPersister, $this->_identifier);
        }
    }

    
    public function __get($property)
    {
        $this->_load();
        return parent::__get($property);
    }

    public function __set($property, $value)
    {
        $this->_load();
        return parent::__set($property, $value);
    }


    public function __sleep()
    {
        return array('__isInitialized__', 'id', 'request_msg', 'publisher', 'request_open', 'airport', 'post_date', 'departure_date', 'departure_time', 'distance', 'num_luggages', 'luggage_size', 'cost', 'drop_off_address', 'duration', 'emissions', 'requests_from_airport_bookmanifest', 'lattitude', 'longitude', 'city', 'state');
    }
}