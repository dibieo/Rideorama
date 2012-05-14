<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Showdate
 * This prints out the date in Month/Day/Year format
 *
 * @author ovo
 */
class Zend_View_Helper_Showdate extends Zend_View_Helper_Abstract{
    
    public function showdate($date){
        
        return date("m/d/Y", strtotime($date));
    }
}

?>
