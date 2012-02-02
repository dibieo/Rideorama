<?php

/**
 * This view helper processes the BuyNow link on 
 * the trip details page.
 */
class Zend_View_Helper_Processuserbooking extends Zend_View_Helper_Abstract {
    
    /**
     * This function returns a url that allows passengers proceed with booking a trip.
     * @param string $trip_id
     * @param string $publisher_id
     * @param string $where
     * @param string $driverEmail
     * @param string $driverName
     * @return string The url allowing users book the trip 
     */
    public function processuserbooking(array $array = null){
        

        //User is loggned in
       if (Zend_Auth::getInstance()->hasIdentity()){
           
          $ref  = $this->getUrl('book', $array);
          
          $jqueryViewHelper = new ZendX_JQuery_View_Helper_AjaxLink();
          $url = $jqueryViewHelper->ajaxLink("Buy Seat!",
                    "$ref",
                    array(
                          'update' => '#buyseat',
                          'beforeSend' => 'slideup',
                           'complete' => 'reduceSeatByOne()'
                        ));
       }else{
           $ref = $this->getUrl('details');
           $url = "<a href=$ref>Book now</a>";
       }
       
       return $url;
    }
    
    
    private function getUrl($action, $array = null){
        
           $setUrl =  new Zend_View_Helper_Url();
           $ref  = $setUrl->url(array(
               "controller" => 'index',
               'module' => 'default',
               'action' => $action,
               'trip_id' => $array['trip_id'],
             'publisher_id' => $array['publisher_id'],
             'where' => $array['where'],
             'driverEmail' => $array['driverEmail'],
             'driverName' => $array['driverName']
               
           ));
           
          
          $url = "<a href=$ref>Book now</a>";
        
      return $url;
    }
}