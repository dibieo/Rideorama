/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


/**
 * 
 */
$("#has_no_car").click(function(){
   
   var checked = $('#has_no_car').is(':checked');
   
   if (checked){
       $(".right_details").show();
   }else{
       $(".right_details").hide();
   }
 // alert("Hi, I was clicked");
 
 //alert($("label[for='model']").val());
//  $("label[for='make']").removeClass("required");
//  $("label[for='year']").removeClass("required");
  
});