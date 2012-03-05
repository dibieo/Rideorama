/* 
 This class processes ajax requests for handling bookings and requests
 Ajax calls are made to functions in the rides and requests modules
 */


/**
 * Handles booking the seat
 */
$("#bookseat").click(function(){
    
   
    var data = $("#bookseat").attr("data");
    var book_data = jQuery.parseJSON(data);
    //alert(book_data.hostname);
    var url =  book_data.hostname + "/rides/index/book?where=" + book_data.where + "&trip_id=" + book_data.trip_id
              + "&publisher_id=" + book_data.publisher_id + "&driverEmail=" + book_data.driverEmail +
              "&passengerEmail=" + book_data.passengerEmail + "&paypalEmail=" + book_data.paypalEmail +
              "&driverName=" + book_data.driverName + "&tripcost=" + book_data.tripcost +
              "&module=" + book_data.module;
    
    $("#bookseat").html("Booking..");
    //alert(url);
    $.post(url,function(data){
       // alert("The url is: " + url);

        var new_seat_count = book_data.num_seats - 1 ;
        $("#seat_num").html(new_seat_count + " seats");
        $("#bookseat").hide();
        alert(data);
    }
    ); 
    
})

$("#offerride").click(function(){
    var data = $("#offerride").attr("data");
    var book_data = jQuery.parseJSON(data);
  //  alert(book_data);
    var url = book_data.hostname + "/requests/index/offer?where=" + book_data.where + "&trip_id=" + book_data.trip_id
            + "&publisher_id=" + book_data.publisher_id + "&driverEmail=" + book_data.driverEmail 
            + "&passengerEmail=" + book_data.passengerEmail + "&offering=" + book_data.offering
            + "&trip_date=" + book_data.trip_date 
            + "&passengerName=" + book_data.passengerName + "&driverName=" + book_data.driverName;
        
    $("#offerride").html("Hold on..");
    $.post(url, function (data){
        $("#offerride").hide();
        alert(data);
    })
            
    
})