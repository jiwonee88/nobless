<?php
include_once('./_common.php');

if ( $w == 'u') {
	
	$tr_token=trim($_POST['tr_token']);
	
	if($_POST['tr_amt'] <= 0) alert_json(false,'변환 수량이 옳바르지 않습니다');
	if($_POST['tr_token'] =='' ) alert_json(false,'변환할 포인트를 선택하세요');
	if($_POST['tr_set_token'] == '') alert_json(false,'변환될 포인트를 선택하세요');
	
	$tr_amt=only_number($_POST['tr_amt']);	
		
	$tr_set_token=trim($_POST['tr_set_token']);			
		
	//보유수량
	$sum=$rpoint[$tr_token]['_enable']*1;
	
	if($sum < abs($tr_amt))  alert_json(false,'변환할 수량이 부족합니다');	
	
	//변환액 계산
	$tr_set_amt=floor(swap_coin($tr_amt,$tr_token,$tr_set_token,$sise)*10)/10;	
	
	//포인트 차감

	if($tr_amt > 0){

		$content['link_no']='';					
		$content['pt_wallet']='free'; //지갑구
		$content['pt_coin']=$tr_token; //코인구분
		$content['amount']=$tr_amt * -1;
		$content['subject']='꿀단지 포인트 변환'	;

		set_add_point('change_out',$member,'',$member['mb_id'],$content);										

		
		$content['link_no']='';					
		$content['pt_wallet']='free'; //지갑구
		$content['pt_coin']=$tr_set_token; //코인구분
		$content['amount']=$tr_set_amt;
		$content['subject']='꿀단지 포인트 변환'	;

		set_add_point('change_in',$member,'',$member['mb_id'],$content);										

	}

	//잔액 조회
	$rpoint=get_mempoint($member['mb_id']);
	
	alert_json(true,'',array('enable_amt_b'=>number_format2($rpoint[$tr_token]['_enable'],6),'enable_amt_i'=>number_format2($rpoint['i']['_enable'],6),'enable_amt_s'=>number_format2($rpoint['s']['_enable'],6)));		
}