    $().ready(function(){
	$.ajaxSetup({
		error:function(x,e){
			if(x.status==0){
			alert('You are offline!!\n Please Check Your Network.');
			}else if(x.status==404){
			alert('Requested URL not found.');
			}else if(x.status==500){
			alert('Ouch looks like a server error occured.Sorry about that. \n Please refersh your page and try again. \n If the problem persists, contact us using the chat box below.');
			}else if(e=='timeout'){
			alert('Request Time out.');
			}else {
			alert('Unknow Error.\n'+x.responseText);
			}
		}
	});
});
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
       if ($(".top_row input[type='radio']:checked").val() == undefined){
            
            alert("You must select whether you're going to or leaving an airport to continue");
       }
       else{
       var departure = $("#driverdeparture").val();
       var destination = $("#driverdestination").val();
       var tripdate = $("#driverdate").val();
       var where = $(".top_row input[type='radio']:checked").val();
       
       window.location = "rides/index/post?where=" + where + "&trip_date=" +
       tripdate + "&from=" + departure + "&to=" + destination ;
       }
       
   }
   
   /**
    * This function redirects the user to a request ride page  when the button is clicked
    * on the homepage.
    */
    function gotoRequestRidePage(){
       var radioInput =$(".top_row input[type='radio']:checked").val();
       if (radioInput == undefined){
            
            alert("You must select whether you're going to or leaving an airport to continue");
       }
       else{
       var departure = $("#departure").val();
       var destination = $("#destination").val();
       var tripdate = $("#trip_date").val();
       var where = radioInput;
       
       window.location = "requests/index/post?where=" + where + "&trip_date=" +
       tripdate + "&from=" + departure + "&to=" + destination ;
       }
       
   }
    
   function getPassengers(){
    
       var departure = $("#driverdeparture").val();
       var destination = $("#driverdestination").val();
       var tripdate = $("#driverdate").val();
       var triptime = $('#drivertrip_time').val();
       var where = $(".top_row input[type='radio']:checked").val();
       //alert($("#findpassengers").val());
       disableSubmitbutton("#findpassengers", "Please wait...");
       
       $.get("index/findpassenger?driverwhere=" + where + "&driverdate=" + tripdate 
            + "& driverdeparture=" + departure  + "&driverdestination=" + destination
            + "&drivertrip_time=" + triptime,
            {},
     function(data, textStatus) { 
         $('#results').html(data); 
         enableSubmitbutton("#findpassengers", 'Find passengers');
         $("#results .passenger_content").slideto();
    }, 'html'

    );
    
   }
   
     /**
      * getRides 
      * Performs an ajax call to the search action and returns rides
      */
     function findRides(){
    
       var departure = $("#departure").val();
       var destination = $("#destination").val();
       var tripdate = $("#trip_date").val();
       var triptime = $('#trip_time').val();
       var where = $(".top_row input[type='radio']:checked").val();
       //alert($("#findpassengers").val());
       disableSubmitbutton("#findrides", "Please wait...");
       
       $.get("index/search?where=" + where + "&trip_date=" + tripdate 
            + "&departure=" + departure  + "&destination=" + destination
            + "&trip_time=" + triptime,
            {},
     function(data, textStatus) { 
         $('#results').html(data); 
         enableSubmitbutton("#findrides", 'Find a ride');
         $("#results .passenger_content").slideto();
    }, 'html'

    );
    
   }
   
   function disableSubmitbutton(div_id, text){
       $(div_id).val(text);
       $(div_id).attr('disabled', 'disabled');
   }
   
   function enableSubmitbutton(div_id, text){
       $(div_id).val(text);
       $(div_id).removeAttr('disabled');
   }

    function setAirportNameTripsToAirport (departure, destination){
        $(departure).val('');
        $(destination).val("Denver International airport");
     }
     
     function setAirportNameTripsFromAirport(departure, destination){
         $(departure).val("Denver International airport");
         $(destination).val('');
     }