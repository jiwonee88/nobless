<?php
$sub_menu = "700600";
include_once('./_common.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($w == "u") {
	
	$ltime=strtotime('-23 hours');
		
	auth_check($auth[$sub_menu], 'w'); 	
	
	if(!$is_manager  &&  $is_admin!='super'){
		alert_close('권한이 없습니다'); 
	}
		
	$mb_id=trim($mb_id);
	
	if($mb_id!=''){
		$mb=get_member($mb_id);

		if(!$mb) alert_close('회원을 찾을수 없습니다'); 
	}
	
	$smb_id=trim($smb_id);
	if($smb_id!=''){
		$smb=get_submember($smb_id);		
		if($smb[mb_id]!=$mb_id) alert_close('서브계정이 옳바르지 않습니다'); 
	}else{
		$smb_id=$mb_id;	
	}
	
	$row=sql_fetch("select * from {$g5['cn_item_trade']}  as a where a.tr_code='$tr_code' ",1);
	
	if(!$row) alert_close('거래정보를 찾을수 없습니다'); 
	
		
	if( $ltime > strtotime($row[tr_rdate]) &&   $is_admin!='super'){
		alert_close('수정 기한이 경과한 거래입니다'); 
	}
		
		
	$logs=date("Y-m-d H:i:s");
	$update_set='';
	
	$logs.= " 정보변경 실행 "; 		
	
	
	if($row[tr_bank]!=$tr_bank || $row[tr_bank_num]!=$tr_bank_num || $row[tr_bank_user]!=$tr_bank_user ){
		$logs.= " 입금통장 정보변경  "; 		
	}
	
	if($mb_id!='' && $mb_id!=$row[mb_id]){
		set_trade_stat($row ,9);
		
		$update_set=" mb_id='$mb_id',  smb_id='$smb_id',";
		
		$logs.= $row[mb_id]." → ".$mb_id." 아이디 변경 ";
	}
	
	$logs=addslashes($logs." by $member[mb_id] \n");
	
	//입금대기
	sql_query("update {$g5['cn_item_trade']} set  $update_set tr_bank='$tr_bank',tr_bank_num='$tr_bank_num',tr_bank_user='$tr_bank_user',tr_logs=concat(tr_logs,'$logs') where tr_code='$tr_code' ",1) ;
	
	if($mb_id!='' &&  $mb_id!=$row[mb_id]){
		$row=sql_fetch("select * from {$g5['cn_item_trade']} as a where a.tr_code='$tr_code' ",1);
		set_trade_stat($row ,1);
	}
	
 
	goto_url('./open_buyer_change.php?tr_code='.$tr_code);
}
?>