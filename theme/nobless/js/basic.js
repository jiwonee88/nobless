$(document).ready(function () {
	

	$('#gnb_mo li.gnb_dp1>div').click(function(){
		if($(this).hasClass('on')){
			$(this).removeClass('on');
			$(this).siblings('.smenu').stop().slideUp(300);
		}else{
			$('#gnb_mo .gnb_dp1>div').removeClass('on');
			$('#gnb_mo .smenu').stop().slideUp(300);
			$(this).addClass('on');
			$(this).siblings('.smenu').stop().slideDown(300);
		}
	});

	$('.hd_full').click(function(){
		if($('.hd_full').hasClass('m_on')){
			$('.hd_full').removeClass('m_on');
			$('#gnb_mo').removeClass('mo_menu_on');
			$('#hd_wrap').removeClass('on');
			$('#gnb_bg').removeClass('on');
		}else{
			$('.hd_full	').addClass('m_on');
			$('#gnb_mo').addClass('mo_menu_on');
			$('#hd_wrap').addClass('on');
			$('#gnb_bg').addClass('on');
		}
	});
	



});  
