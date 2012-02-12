// JavaScript Document
$(function() {
 $(".tab ul li").click(function () {
    var curRel= $('a' , $(this)).attr('href');
	$(".tab ul li").removeClass('active');
		 $(this).addClass('active');
		$.get(curRel, function(data) {
			$('div.tab_con').html(data);
			$(".jCarousel").jCarouselLite({
			vertical: true,
			visible: 2,
			auto: 1500,
			speed: 1000,
			scroll: -1
		});
		$('select').jqTransSelect({imgPath:'public/jqtransformplugin/img/'});
		$('input:radio').jqTransRadio({imgPath:'public/jqtransformplugin/img/'});
	Cufon.replace('#home_container .tab ul li a', { fontFamily: 'Helvetica', fontWeight: 700 , hover:true});	
	Cufon.replace('#home_container .content_detaiils .left_sec label', { fontFamily: 'Helvetica', fontWeight: 700});
	Cufon.replace('#home_container .top_row span', { fontFamily: 'Helvetica', fontWeight: 700});
	Cufon.replace('#home_container .latest_rides h3', { fontFamily: 'Helvetica', fontWeight: 700});
	Cufon.replace('#home_container .bottom_tab ul li a', { fontFamily: 'Helvetica', fontWeight: 700});		
	            });
				return false;
		});
	$('.tab ul li').eq(0).trigger('click');
});
