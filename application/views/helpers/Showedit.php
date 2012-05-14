<?php



/**
 * 
 * This view helper displays the offer ride links
 * 
 * @author ovo
 */

class Zend_View_Helper_Showedit extends Zend_View_Helper_Abstract {
    
    
    public function showedit(array $array, $module, $where){
        
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
           $edit_url = null;
           //If this trip hasn't past, allow the trip owner to edit
           //Else show the edit link
           if ($curr_date <= $array['departure_date']){
           $setUrl =  new Zend_View_Helper_Url();
           $ref  = $setUrl->url(array(
               "controller" => 'index',
               'module' => $module,
               'action' => "edit",
                "where" => $where,
                 "trip_time" => $array['trip_time'],
                 "trip_id" => $array['id'],
                 "trip_msg" => $array['trip_msg'],
                 "trip_date" => $array['departure_date'],
                 "from" => ($where == "toAirport") ? $array['address'] : $array['name'],
                 "to" => ($where == "toAirport") ? $array['name'] : $array['address'],
                 "trip_cost" => $array['cost'],
                 "num_seats" => $array['seat_num'],
                 "luggage" => $array['num_luggages'],
                 "luggage_size" => $array['luggage_size']
                ));
             $edit_url = "<a href='$ref'>Edit</a>";
           }else{
               $edit_url  = 'Past trip';
               
           }
        
            return $edit_url;
        }
}