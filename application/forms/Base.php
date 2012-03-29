<?php

class Application_Form_Base extends Zend_Form
{
    


    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
    }


     /**
      *
      * @return type 
      */
    protected function getCities(){
       $cities = new Admin_Model_CityService();
       return $cities->getAllCities();
    }
    
    /**
     *
     * @return array of all cities
     */
    protected function makeCityArray(){
       $cities_array = array();
       $cities = $this->getCities();
       
       foreach($cities as $city){
          
          array_push($cities_array, array(
              'key'=> $city->id,
              'value' => $city->name . ',  ' . $city->state->abbv
          ));
       }
       return $cities_array;
    }
    
    
    protected function getStates(){
        
        $states = new Admin_Model_DbTable_State();
       return $states->getStates();
    }
    
    protected function makeStatesArray(){
       $states_array = array();
       $states = $this->getStates();
       
       foreach($states as $state){
          
          array_push($states_array, array(
              'key'=> $state->id,
              'value' => $state->name
          ));
       }
       return $states_array;
    }
    
    /**
     * Fetches all the airports currently in the database
     * @return array of airports
     */
     public function getAirports(){
         
        $airport = new Admin_Model_AirportService();
        $all_airports = array();
        $aiports = $airport->getAllAirports();
        foreach($aiports as $a){
            array_push($all_airports, array(
              'key'=> $a->id,
              'value' => $a->name
                ));
        }
        return $all_airports;
    
   }
   
   //gets string version
   public function getStringAirports(){
       
       
   }
    /**
     * get all the trip times
     * @return type Array of tripTimes
     */
    
    protected function getTripTimes(){
        
       return array(
            'anytime' => 'anytime',
            'morning' => 'morning',
            'afternoon' => 'afternoon',
            'evening' => 'evening'
           
        );
    }
    
    
    protected function submitDecorator($class = 'btn_row'){
        
         return array(
            'ViewHelper',

                   'Description',

                   'Errors',

                   array(array('data'=>'HtmlTag'), array('tag' => 'td')),

                   array(array('row'=>'HtmlTag'),array('tag'=>'div', 'class' => $class)
        )
            );   
    }

    protected function generateDecorators($tag = 'div', $class = 'form_row'){
        
        return array(
                   'ViewHelper',

                   'Description',

                   'Errors',
                  
                   'Label',

                   array(array('data'=>'HtmlTag'), array('tag'=>$tag, 'class' => $class)),


                 // array(array('row'=>'HtmlTag'),array('tag'=>'div', 'class' => $class)
        //)
            );
    }
    
    
    public function activateformDecorators(){
        
        return array(
           'ViewHelper',

                   'Description',

                   'Errors',
                  
                   'Label',
            array('htmlTag', array ('tag' => 'span', 'class' => 'row'))
             
       );
       
    }
    
    protected function generateDecoratorsForSelects($class = 'form_row'){
        
        return array(
                   
                    'ViewHelper', array(array('elementDiv' => 'HtmlTag'), array('tag' => 'div', 'class' => "ride")),


                   'Description',

                   'Errors',
                  
                   'Label',

                   array(array('data'=>'HtmlTag'), array('tag'=>'div', 'class' => $class)),


                 // array(array('row'=>'HtmlTag'),array('tag'=>'div', 'class' => $class)
        //)
            );
    }
    
     protected function generateLuggageDecorators($class = 'form_row'){
        
        return array(
                   'ViewHelper',

                   'Description',

                   'Errors',
                  
                   'Label',

                   array(array('data'=>'HtmlTag'), array('tag'=>'div', 'class' => $class)),


                 // array(array('row'=>'HtmlTag'),array('tag'=>'div', 'class' => $class)
        //)
            );
    }
    
    protected function generateDecoratorsJQuery($tag ='div', $class = 'form_row'){
  
           return array(
               
                   'UiWidgetElement',
                   
               
                   'Description',

                   'Errors',
                  
                   'Label',

                   array(array('data'=>'HtmlTag'), array('tag'=>$tag, 'class' => $class)),
   

            );
           
    }
    
    protected function generateJQueryElementTextDecorator($class = 'form_row'){
           return array(
               
                   'UiWidgetElement',
                   
               
                   'Description',

                   'Errors',
                  
                   'Label',

                   array(array('data'=>'HtmlTag'), array('tag'=>'div', 'class' => $class)),
               
                   array(array('txt'=>'HtmlTag'), array('tag'=>'h3', 'content' => 'Post a ride', 'placement' => 'PREPEND'))


            );
    
    }
    
       protected function generateDecoratorsForHomepageSelects($class = 'radio_area'){
        
        return array(
            
                    'Label',

             
                    'ViewHelper',
     

                   'Description',

                   'Errors'


                 // array(array('row'=>'HtmlTag'),array('tag'=>'div', 'class' => $class)
        //)
            );
    }
    
    protected function homepagebuttonsdecorators() {
       $button_decors = array(
           'FormElements',

            'ViewHelper',
           array ('HtmlTag', array ('tag' => 'li'))
       );
       
       return $button_decors;
    }
    
    //Gets the date validator for us timezone
     public function getDateValidator(){
         
         $date_validator = new Zend_Validate_Date(array('locale' => 'en_us'));
         
         return $date_validator;
     
  }
  
  
  /**
   * An associate array of time slots in 30 mins intervals
   * @return array 
   */
  public function getTimes(){
     return array(
                       
                       "08:00:00" => "08:00 am",
                       "08:30:00" => "08:30 am",
                       "09:00:00" => "09:00 am",
                       "09:30:00" => "09:30 am",
                       "10:00:00" => "10:00 am",
                       "10:30:00" => "10:30 am",
                       "11:00:00" => "11:00 am",
                       "11:30:00" => "11:30 am",
                       "12:00:00" => "12:00 pm",
                       "12:30:00" => "12:30 pm",
                       "13:00:00" => "01:00 pm",
                       "13:30:00" => "01:30 pm",
                       "14:00:00" => "02:00 pm",
                       "14:30:00" => "02:30 pm",
                       "15:00:00" => "03:00 pm",
                       "15:30:00" => "03:30 pm",
                       "16:00:00" => "04:00 pm",
                       "16:30:00" => "04:30 pm",
                       "17:00:00" => "05:00 pm",
                       "17:30:00" => "05:30 pm",
                       "18:00:00" => "06:00 pm",
                       "18:30:00" => "06:30 am",
                       "19:00:00" => "07:00 pm",
                       "19:30:00" => "07:30 pm",
                       "20:00:00" => "08:00 pm",
                       "20:30:00" => "08:30 pm",
                       "21:00:00" => "09:00 pm",
                       "21:30:00" => "09:30 pm",
                       "22:00:00" => "10:00 pm",
                       "22:30:00" => "10:30 pm",
                       "23:00:00" => "11:00 pm",
                       "23:30:00" => "11:30 pm",
                       "00:00:00" => "12:00 am",
                       "00:30:00" => "12:30 am",
                       "01:00:00" => "01:00 am",
                       "01:30:00" => "01:30 am",
                       "02:00:00" => "02:00 am",
                       "02:30:00" => "02:30 am",
                       "03:00:00" => "03:00 am",
                       "03:30:00" => "03:30 am",
                       "04:00:00" => "04:00 am",
                       "04:30:00" => "04:30 am",
                       "05:00:00" => "05:00 am",
                       "05:30:00" => "05:30 am",
                       "06:00:00" => "06:00 am",
                       "06:30:00" => "06:30 am",
                       "07:00:00" => "07:00 am",
                       "07:30:00" => "07:30 am"

          
                   );
      
          
  }
  
  /**
   * Returns an array of file decorators
   * @return array file decorators 
   */
  public function getFileDecorators($class = 'row'){
      
             $fileDecorators = array(
            'File',
            'Label',
            'Errors',
           array('htmlTag', array ('tag' => 'span', 'class' => $class))

        );
             
             return $fileDecorators;
  }
  
  
 public function generateRadioDecorators(){
          return
        array(
        //array('Label', array('row' => 'div', 'id' => 'box1')),
       'Label',
        array('Description', array('escape' => false, 'tag' => false)),
        'ViewHelper',
        array(array('data'=>'HtmlTag'), array('tag'=>'div', 'class' => 'box1')),
       array('HtmlTag', array('tag' => 'div', 'class' => 'form_row1')),

        'Errors'
      );
    }
    
   /**
    * Returns a validator that ensures the min age is 17
    * @return Zend_Validate_GreaterThan $ageValidator
    */
    public function getAgeValidator(){
        
      $ageValidator = new Zend_Validate_GreaterThan(array('min' => 17));
      $ageValidator->setMessage("You must be at least 18 year old to use Rideorama. See our terms and conditions for more details");
       return $ageValidator;
    }
}
