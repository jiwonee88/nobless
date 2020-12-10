<?php
$sub_menu = "700100";
include_once('./_common.php');

if($date_start_stx) {
	$sql_search .= " and a.in_wdate >= '$date_start_stx 00:00:00' ";
	$qstr.="&date_start_stx=$date_start_stx";
}
if($date_end_stx) {
	$sql_search .= " and a.in_wdate <= '$date_end_stx 23:59:59' ";
	$qstr.="&date_end_stx=$date_end_stx";
}
if($coin_stx) {
	$sql_search .= " and a.in_token = '$coin_stx' ";
	$qstr.="&coin_stx=$coin_stx";
}


auth_check($auth[$sub_menu], 'w');

check_admin_token();


if ($w == '' || $w == 'u') {
	
	$in_rsv_amt=round(only_number($_POST['in_rsv_amt']),6);	
	$in_set_amt=only_number($_POST['in_set_amt']);	
	
	if($_POST[mb_id]) $mb=get_member($_POST[mb_id]);
	else $mb=get_emailmember($_POST[mb_email]);
	
	if(!$mb) alert('회원정보를 찾을수 없습니다');
	
	if($_POST['in_wallet_addr']=='') $in_wallet_addr=$mb['mb_wallet_addr_'.$_POST[in_token]];
	else $in_wallet_addr=$_POST['in_wallet_addr'];
	
	$sql_common .= "		
		in_wallet_addr  = '{$in_wallet_addr}',	
		in_token='$_POST[in_token]',
		in_rsv_amt  = '{$in_rsv_amt}',	
		in_rsv_date  = '{$in_rsv_date}'
		";
		
}

if ($w == '') {	
	
    $sql = " insert into {$g5['cn_purchase_table']}
                set
				mb_id = '{$mb['mb_id']}',	 	
				
				in_wdate = now(),
				in_mdate = now(),
				$sql_common ";
	
	//echo $sql;				
    sql_query($sql,1);	
	$in_no=sql_insert_id();
	
	//입금처리
	$data= sql_fetch("select * from {$g5['cn_purchase_table']} where in_no='$in_no'");			
		
	$return=set_purchase_coin($data,$in_stats,1,date("Y-m-d H:i:s")."입금처리 완료\n");
	
	
} else if ($w == 'u') {
	
	$data= sql_fetch("select * from {$g5['cn_purchase_table']} where in_no='$in_no'");		
	
    $sql = " update  {$g5['cn_purchase_table']} set 			 
	         {$sql_common}
              where in_no = '{$in_no}' ";
    sql_query($sql,1);

	//토큰/수량 변경시 취소후 재처리
	if($data['in_set_amt']!=$in_rsv_amt || $data['in_token']!=$in_token) set_purchase_coin($data,1,1,date("Y-m-d H:i:s")."입금취소처리 완료\n");
	
	//입금처리
	$data= sql_fetch("select * from {$g5['cn_purchase_table']} where in_no='$in_no'");
	
	//입금처리
	$return=set_purchase_coin($data,$in_stats,1,date("Y-m-d H:i:s")."입금처리 완료\n");
	
}
	
goto_url("./insert_purchase_list.php?$qstr");
?>