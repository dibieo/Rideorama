<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Showcar
 *
 * @author ovo
 */
class Zend_View_Helper_Showcar  extends Zend_View_Helper_Abstract{
    //put your code here
    
  
    public function showcar($img){
   
    $baseUrl = new Zend_View_Helper_BaseUrl();
    $baseUrl_path = $baseUrl->baseUrl() . "/img/cars/";
    if ($img != ""){
        
        return $baseUrl_path . $img;
    }else{
        return $baseUrl_path . "car-silhouette.png";
    }
        

      
    }
}

?>
