<?php
$sub_menu = "700600";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'd');

check_demo();

//check_admin_token();

//우선권 지정
if ($w=='p') {

	
	if($ac_id=='' && $val=='') alert_json(false,'접근이 옳바르지 않습니다');	
	$result= sql_query("update  {$g5['cn_sub_account']} set ac_mc_priority='$val' where ac_id='$ac_id'");
	
	if($result) alert_json(true,'수정되었습니다');	
	else alert_json(false,'수정할수 없습니다');	

}

//매칭제외
if ($w=='e') {
	
	if($ac_id=='' && $val=='') alert_json(false,'접근이 옳바르지 않습니다');	
	$result= sql_query("update  {$g5['cn_sub_account']} set ac_mc_except='$val' where ac_id='$ac_id'");				
	
	if($result) alert_json(true,'수정되었습니다');	
	else alert_json(false,'수정할수 없습니다');	

}

?>