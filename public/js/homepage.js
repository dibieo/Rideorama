    
    /**
     * The following functions sets the 'I am a passenger tab'
     * search area of the homepage
     */
     function setDestinationAutocomplete(){
      var input = document.getElementById('destination');
      var remove = document.getElementById('departure');
      google.maps.event.clearInstanceListeners(remove);
      var autocomplete = new google.maps.places.Autocomplete(input);

     }
     
     function setDepartureAutocomplete(){
         var input = document.getElementById('departure');
         var remove = document.getElementById('destination');
         google.maps.event.clearInstanceListeners(remove);
         new google.maps.places.Autocomplete(input);
     }
     
     /**
      * going to  airport
      * so departure field does auto complete
      */
     function initializeDepartureAutocomplete() {
     var clickedElement = document.getElementById('where-toAirport');
     var dest = document.getElementById("destination");
    // google.maps.event.clearListeners(dest, 'click');
      google.maps.event.addDomListener(clickedElement, 'click', setDepartureAutocomplete);
     }
     
     /**
      * Leaving airport
      * so destination field does autocomplete
      */
     function initializeDestinationAutocomplete() {
     var clickedElement = document.getElementById('where-fromAirport');
     var dept = document.getElementById("departure");
    //  google.maps.event.clearListeners(dept, 'click');
      google.maps.event.addDomListener(clickedElement, 'click', setDestinationAutocomplete);

     }
     google.maps.event.addDomListener(window, 'load', initializeDepartureAutocomplete);
     google.maps.event.addDomListener(window, 'load', initializeDestinationAutocomplete);
     
     /**
      * The following methods set autocomplete for the 'I am a driver tab of the homepage search'
      * 
      */

     function setdriverdestinationAutocomplete(){
      var input = document.getElementById('driverdestination');
      var remove = document.getElementById('driverdeparture');
      google.maps.event.clearInstanceListeners(remove);
      var autocomplete = new google.maps.places.Autocomplete(input);

     }
     
     function setdriverdepartureAutocomplete(){
         var input = document.getElementById('driverdeparture');
         var remove = document.getElementById('driverdestination');
         google.maps.event.clearInstanceListeners(remove);
         new google.maps.places.Autocomplete(input);
     }
     
     /**
      * going to  airport
      * so driverdeparture field does auto complete
      */
     function initializedriverdepartureAutocomplete() {
     var clickedElement = document.getElementById('driverwhere-toAirport');
     var dest = document.getElementById("driverdestination");
    // google.maps.event.clearListeners(dest, 'click');
      google.maps.event.addDomListener(clickedElement, 'click', setdriverdepartureAutocomplete);
     }
     
     /**
      * Leaving airport
      * so driverdestination field does autocomplete
      */
     function initializedriverdestinationAutocomplete() {
     var clickedElement = document.getElementById('driverwhere-fromAirport');
     var dept = document.getElementById("driverdeparture");
    //  google.maps.event.clearListeners(dept, 'click');
      google.maps.event.addDomListener(clickedElement, 'click', setdriverdestinationAutocomplete);

     }
     google.maps.event.addDomListener(window, 'load', initializedriverdepartureAutocomplete);
     google.maps.event.addDomListener(window, 'load', initializedriverdestinationAutocomplete);
 
   function gotoPostRidePage(){
       var departure = $("#driverdeparture").val();
       var destination = $("#driverdestination").val();
       var tripdate = $("#driverdate").val();
       var where = $("#tabs-2 input[type='radio']:checked").val();
       
       window.location = "rides/index/post?where=" + where + "&trip_date=" +
       tripdate + "&from=" + departure + "&to=" + destination ;
       
   }