<?php
$sub_menu = "700300";
include_once('./_common.php');

if($date_start_stx) {
	$sql_search .= " and a.dr_wdate >= '$date_start_stx 00:00:00' ";
	$qstr.="&date_start_stx=$date_start_stx";
}
if($date_end_stx) {
	$sql_search .= " and a.dr_wdate <= '$date_end_stx 23:59:59' ";
	$qstr.="&date_end_stx=$date_end_stx";
}
if($coin_stx) {
	$sql_search .= " and a.dr_token = '$coin_stx' ";
	$qstr.="&coin_stx=$coin_stx";
}


auth_check($auth[$sub_menu], 'w');

check_admin_token();


if ($w == '' || $w == 'u') {
	
	
	
}

if ($w == '') {		
	
	if($_POST[mb_id]) $mb=get_member($_POST[mb_id]);
	else $mb=get_emailmember($_POST[mb_email]);
	
	if(!$mb) alert('회원정보를 찾을수 없습니다');
	
	$rpoint=get_mempoint($_POST[mb_id]);
	
	$sum=$rpoint[$dr_token]['_enable']*1;
	
	if($sum < abs($dr_amt))  alert('보유량이 부족합니다');
	
	if( $dr_amt == 0) alert('출금수량을 입력하세요');	
		
	
	$dr_amt=round(only_number($_POST['dr_amt']),6);	
	
	$dr_fee=round($dr_amt*$cset['out_fee_'.$_POST['dr_token']]/100,6);
	
	$dr_tamt=$dr_amt-$dr_fee;	
	
	if($dr_amt <= $dr_fee)  alert('출금수량이 수수료보다 커야 합니다.');	
	
	//지갑 주소 검사
	$rtn=check_coin_addr($dr_token,$dr_wallet_addr);
	if($rtn[0]==false) alert('출금주소가 옳바르지 않습니다');
	
    $sql = " insert into {$g5['cn_draw_table']}
                set					
				mb_id		= '{$mb['mb_id']}',	 
				dr_wdate = now(),
				dr_token='$_POST[dr_token]',
				dr_amt  = '{$dr_amt}',
				dr_fee  = '{$dr_fee}',
				dr_tamt  = '{$dr_tamt}',
				dr_set_amt  = '{$dr_tamt}',
				dr_wallet_addr  = '{$_POST[dr_wallet_addr]}'
				";
	
	//echo $sql;				
    sql_query($sql,1);	
	$dr_no=sql_insert_id();
	
	//출금처리
	$data= sql_fetch("select * from {$g5['cn_draw_table']} where dr_no='$dr_no'");			
		
	$return=set_draw_coin($data,$dr_stats,1);
	
	
} else if ($w == 'u') {
	
	$data= sql_fetch("select * from {$g5['cn_draw_table']} where dr_no='$dr_no'");		
	
    $sql = " update  {$g5['cn_draw_table']} set 			 
	         dr_wallet_addr  = '{$_POST[dr_wallet_addr]}'
              where dr_no = '{$dr_no}' ";
    sql_query($sql,1);
	
	//echo $sql;
	//토큰/수량 변경시 취소후 재처리
	//if($data['dr_set_amt']!=$dr_amt || $data['dr_token']!=$dr_token) set_draw_coin($data,$dr_amt,1,1);
	
	//출금처리
	$return=set_draw_coin($data,$dr_stats,1);
	
}
	
goto_url("./coin_draw_list.php?$qstr");
?>