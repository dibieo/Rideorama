<?php

namespace Rideorama\Entity\Proxy;

/**
 * THIS CLASS WAS GENERATED BY THE DOCTRINE ORM. DO NOT EDIT THIS FILE.
 */
class RideoramaEntityRidesfromairportProxy extends \Rideorama\Entity\Ridesfromairport implements \Doctrine\ORM\Proxy\Proxy
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
        return array('__isInitialized__', 'id', 'number_of_seats', 'trip_msg', 'publisher', 'num_luggages', 'airport', 'rides_from_airport_booking_manifest', 'luggage_size', 'post_date', 'departure_date', 'departure_time', 'distance', 'cost', 'emissions', 'drop_off_address', 'arrival_time', 'lattitude', 'longitude', 'city', 'state');
    }
}