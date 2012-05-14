<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Showmembers
 * This view helper generates 
 * @author ovo
 */
class Zend_View_Helper_Showmembers  extends Zend_View_Helper_Abstract{
    //put your code here
    
    public function showmembers($status, $trip_date){
        
      $setUrl = new Zend_View_Helper_Url();
      $ref = $setUrl->url(array(
          "module" => "account",
          "controller" => "user",
          "action" => "withwho",
          "status" => $status,
          "trip_date" => $trip_date));
      
      return $ref;
    }
}

?>
