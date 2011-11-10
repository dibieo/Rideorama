<?php

class ShowController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $this->_helper->layout->disableLayout();
        // action body
        
        $client = new Zend_Http_Client('http://maps.googleapis.com/maps/api/distancematrix/json');
 
        $departure = urlencode('1777 Exposition Avenue Boulder');
        $destination = urlencode('Denver International Airport');
 
        $client->setParameterGet('sensor', 'false'); // Do we have a GPS sensor? Probably not on most servers.
        $client->setParameterGet('origins', $departure); // Should now be '1600+Amphitheatre+Parkway,+Mountain+View,+CA'
        $client->setParameterGet('destinations', $destination);
        $client->setParameterGet('units', 'imperial');
 
        $response = $client->request('GET'); // We must send our parameters in GET mode, not POST
 
        $val = Zend_Http_Response::extractBody($response);
        
        $val = json_decode($val);
        
        //print_r($val);
         print_r($val->rows[0]->elements[0]->distance->text);
    }


}

