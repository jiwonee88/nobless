<?php
$sub_menu = "700400";
include_once('./_common.php');

if($date_start_stx) {
	$sql_search .= " and a.tr_wdate >= '$date_start_stx 00:00:00' ";
	$qstr.="&date_start_stx=$date_start_stx";
}
if($date_end_stx) {
	$sql_search .= " and a.tr_wdate <= '$date_end_stx 23:59:59' ";
	$qstr.="&date_end_stx=$date_end_stx";
}
if($coin_stx) {
	$sql_search .= " and a.tr_token = '$coin_stx' ";
	$qstr.="&coin_stx=$coin_stx";
}


auth_check($auth[$sub_menu], 'w');

check_admin_token();


if ($w == '' || $w == 'u') {
	
	
	
}

if ($w == '') {		
	
	$tr_fee_token='i';
	
	if($_POST[mb_id]) $mb=get_member($_POST[mb_id]);
	else $mb=get_emailmember($_POST[mb_email]);
	
	if(!$mb) alert('회원정보를 찾을수 없습니다');
	
	if($_POST[tmb_id]) $mb=get_member($_POST[tmb_id]);
	else $tmb=get_emailmember($_POST[tmb_email]);
	
	if(!$tmb) alert('받을 회원을 찾을수 없습니다');	
	
	$rpoint=get_mempoint($mb[mb_id]);
	
	$sum=$rpoint[$tr_token]['_enable']*1;
	$sum_fee=$rpoint[$tr_fee_token]['_enable']*1;
		
	if($tr_amt == 0) alert('이체수량을 입력하세요');	
			
	$tr_amt=round(only_number($_POST['tr_amt']),6);	
	
	$tr_fee=$cset['trans_fee_'.$_POST['tr_token']];
	
	$tr_tamt=$tr_amt;	
	
	if($sum < abs($tr_amt))  alert('보유량이 부족합니다');
	if($sum_fee < abs($tr_fee))  alert('수수료가 부족합니다');	
	
    $sql = " insert into {$g5['cn_transfer_table']}
                set					
				mb_id		 = '{$mb['mb_id']}',	 
				tmb_id   = '{$tmb['mb_id']}',	 
				tr_wdate = now(),
				tr_mdate=now(),
				tr_token='$_POST[tr_token]',
				tr_fee_token  = '{$tr_fee_token}',	
				tr_amt  = '{$tr_amt}',
				tr_fee  = '{$tr_fee}',
				tr_tamt  = '{$tr_tamt}'
				
				tr_set_amt  = '{$tr_amt}',	
				tr_set_fee  = '{$tr_fee}',	
				tr_set_tamt  = '{$tr_tamt}'
				
				";
	
	//echo $sql;				
    sql_query($sql,1);	
	$tr_no=sql_insert_id();
	
	//이체처리
	$data= sql_fetch("select * from {$g5['cn_transfer_table']} where tr_no='$tr_no'");			
		
	$return=set_transfer_coin($data,$tr_stats,1);
	
	
} else if ($w == 'u') {
	
	$data= sql_fetch("select * from {$g5['cn_transfer_table']} where tr_no='$tr_no'");		
	
    $sql = " update  {$g5['cn_transfer_table']} set 
              where tr_no = '{$tr_no}' ";
    //sql_query($sql,1);
	
	//echo $sql;
	//토큰/수량 변경시 취소후 재처리
	//if($data['tr_set_amt']!=$tr_amt || $data['tr_token']!=$tr_token) set_transfer_coin($data,$tr_amt,1,1);
	
	//이체처리
	$return=set_transfer_coin($data,$tr_stats,1);
	
}
	
goto_url("./coin_transfer_list.php?$qstr");
?>