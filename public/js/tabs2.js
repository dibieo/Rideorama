// JavaScript Document
$(function() {
 $(".bottom_tab_area ul.tab li").click(function () {
    var curRel= $('a' , $(this)).attr('href');
	$(".bottom_tab_area ul.tab li").removeClass('active');
		 $(this).addClass('active');
		$('.tabing').hide();
		 $(curRel).show();
		Cufon.replace('.details_section .bottom_tab_area ul.tab li a', { fontFamily: 'Helvetica', fontWeight: 700 , hover:true});
		 return false;
	});
	$(".car li:first").addClass('active');
	 $(".car li").click(function () {
		 var curRel=  $('a',$(this)).attr('href');
		 $(".common_text img.large").attr({ src: curRel});
		 $(".car li").removeClass('active');
		 $(this).addClass('active');
		 return false;
		 });
	 
});
