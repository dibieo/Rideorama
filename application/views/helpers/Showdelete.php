<?php



/**
 * 
 * This view helper displays the offer ride links
 * 
 * @author ovo
 */

class Zend_View_Helper_Showdelete extends Zend_View_Helper_Abstract {
    
    
    /**
     *
     * @param array $array
     * @param string $module
     * @param string $where
     * @return string The Url to delete a trip 
     */
    public function showdelete(array $array, $module, $where){
        
     return $this->getUrl($array, $module, $where);
   
     }
    
    /**
     * Provides the url for editing a trip
     * @param array $array
     * @param string $module
     * @param string $where
     * @return string Edit Url
     */
    private function getUrl($array, $module, $where){
        
           $curr_date = date("Y-m-d");
           $delete_url = null;
           //If this trip hasn't past, allow the trip owner to edit
           //Else show the edit link
           if ($curr_date <= $array['departure_date']){
           $setUrl =  new Zend_View_Helper_Url();
           $id = $array['id'];
           $ref  = $setUrl->url(array(
               "controller" => 'index',
               'module' => $module,
               'action' => "delete",
                "where" => $where,
                "trip_id" => $array['id']
                ));
             $delete_url = "<a onclick=removeTrip('$ref','tr#$id') href=javascript:void(0)>Delete</a>";
           }else{
               $delete_url  = 'Past trip';
               
           }
        
            return $delete_url;
        }
}