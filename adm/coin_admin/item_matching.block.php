<?php
$sub_menu = "700750";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'w');
//check_admin_token();

//서비스 블럭처리
if($w=='b'){
		
	$result=sql_query("update {$g5['cn_set']} set service_block ='$service_block' ");
	
	
	if($result) alert_json(true,'처리되었습니다');
	else  alert_json(false,'처리불가');
	
}



//구매예약 리셋
if($w=='r'){
		
	$result=sql_query("update {$g5['cn_sub_account']} set ac_auto_a='0',ac_auto_b='0',ac_auto_c='0',ac_auto_d='0',ac_auto_e='0',ac_auto_f='0',ac_auto_g='0',ac_auto_h='0' ");
	
	if($result) alert_json(true,'리셋 처리되었습니다');
	else  alert_json(false,'처리불가');
	
}