// JavaScript Document
$(function() {
 $(".tab_section ul li").click(function () {
    var curRel= $('a' , $(this)).attr('href');
	$(".tab_section ul li").removeClass('active');
		 $(this).addClass('active');
		$('.tabing').hide();
		 $(curRel).show();
		Cufon.replace('.my_account .tab_section ul li a', { fontFamily: 'Helvetica', fontWeight: 700 , hover:true});
		 return false;
	});
	 $(".thumb li:first").addClass('active');
	 $(".thumb li").click(function () {
		 var curRel=  $('a',$(this)).attr('href');
		 $(".pic_area img.large").attr({ src: curRel});
		  $(".thumb li").removeClass('active');
		 $(this).addClass('active');
		 return false;
		 });
	 
});
