/**
 * This file contains a collection of scripts that make 
 * Ajax calls from within a user's account
 */

$("#myrides").click(function(){
    
    
    //Get host path
    var data = jQuery.parseJSON($("#myrides").attr("data"));
    var host = data.hostname;
    $.get(host + "/account/user/myrides", function(data){
    $(".rides_section").html(data);
    }
)});

//Show whowith

function getUsers(url){
    $.get(url, function(data){
           $("#userlist").html(data);
           $("#userlist").dialog({
               modal: true,
               buttons: {
                   Ok: function(){
                       $(this).dialog("close");
                   }
               }
           });
    });
}

function removeTrip(url, id){
    $.get(url, function(data){
        $("#userlist").html(data);
        $("#userlist").dialog({
               modal: true,
               buttons: {
                   Ok: function(){
                       $(this).dialog("close");
                   }
               }
           });
        $(id).hide();
    });   
}