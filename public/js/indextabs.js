// JavaScript Document
$(function() {
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
 
        setTimeout("setTicker()", 1000);
               
				return false;
		});
	$('.tab ul li').eq(0).trigger('click');
           
});


function setTicker(){
              console.log(getLocalStorageKey());
              if (localStorage.getItem(getLocalStorageKey()) != null){
                  console.log("getting item from local storage");
                  $("#latest").html(localStorage.getItem(getLocalStorageKey()));
                 setCarousel();

              }else
              {
                  console.log("Getting item from outside localstorage");
            
                $.get("index/homepageticker", {}, function(data){
                    $("#latest").html(data);
                    localStorage.setItem(getLocalStorageKey(), data);
                    console.log("added item to localstorage");
                }, 'html');
                setTimeout("setCarousel()", 400); 

              }
}

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
			speed: 1000,
			scroll: -1
		});
}