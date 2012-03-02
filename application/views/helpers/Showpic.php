<?php

class Zend_View_Helper_Showpic extends Zend_View_Helper_Abstract {
    
    public function showpic($pic){
     $img = null;
     if (substr($pic, 0, 4) == "http"){
         
         $pic = $pic . '?type=large';
         $img = $pic;
     }else {
         
       $url =  new Zend_View_Helper_BaseUrl();
       $img = $url->baseUrl() . "/img/users/" . $pic;
     }
     
     return $img;
     
     }
}