<?php

class Zend_View_Helper_Footer extends Zend_View_Helper_Abstract {
    
    public function footer(){
        
        $year = date('Y');
        if ($year != "2011"){
            
           $year = "2011, $year";
        }
        return <<<EOT
    <p>Copyright &copy $year Rideorama LLC .


EOT;
    }
}