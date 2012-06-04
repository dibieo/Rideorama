// JavaScript Document
$(document).ready(function() {
 $(".tab ul li").click(function () {
    var curRel= $('a' , $(this)).attr('href');
	$(".tab ul li").removeClass('active');
		 $(this).addClass('active');
                 
              
		$.get(curRel, function(data) {
			$('div.tab_con').html(data);

		$('select').jqTransSelect({imgPath:'public/jqtransformplugin/img/'});
		$('input:radio').jqTransRadio({imgPath:'public/jqtransformplugin/img/'});
	Cufon.replace('#home_container .tab ul li a', {fontFamily: 'Helvetica', fontWeight: 700 , hover:true});	
	Cufon.replace('#home_container .content_detaiils .left_sec label', {fontFamily: 'Helvetica', fontWeight: 700});
	Cufon.replace('#home_container .top_row span', {fontFamily: 'Helvetica', fontWeight: 700});
	Cufon.replace('#home_container .latest_rides h3', {fontFamily: 'Helvetica', fontWeight: 700});
	Cufon.replace('#home_container .bottom_tab ul li a', {fontFamily: 'Helvetica', fontWeight: 700});		
	            });
        $("#latest").html("Loading");
         setTimeout("setTicker()", 500);

        return false;

		});
	$('.tab ul li').eq(0).trigger('click');
           
});


function setTicker(){
              var key = getLocalStorageKey();
              console.log(key);
              if (localStorage.getItem(key) != null){
                getItemFromLocalStorageAndSetUpcomingRides(key);
              }else
              {
                console.log("Getting item from outside localstorage");
                addItemToLocalStorage(key);
              }
}

/**
 * This function gets an item from the browser's local storage
 * and sets the latest rides div
 */
function getItemFromLocalStorageAndSetUpcomingRides(key){
      console.log("getting item from local storage");
      $("#latest").html(localStorage.getItem(key));
      setCarousel(); //Sets the carousel
    
}

/**
 * Adds the upcoming rides item to local storage
 *
 */
function addItemToLocalStorage(key){
      $.get("index/homepageticker", {}, function(data){
                  //  $("#hidden-list").html(data);
                    localStorage.setItem(key, data);
                    console.log("added item to localstorage");
                    getItemFromLocalStorageAndSetUpcomingRides(key);
                }, 'html');
                
}
/**
 * Generates a key for storing items in localStorage
 * Right now this uses the date stamp
 */
function getLocalStorageKey(){
    var date = new Date();
    var homepageTickerKey = "homepageTickers" +  date.toLocaleDateString() ;
    return homepageTickerKey;
}
function setCarousel(){
         $(".jCarousel").jCarouselLite({
			vertical: true,
			visible: 2,
			auto: 1500,
			speed: 4000,
			scroll: -1
		});
}