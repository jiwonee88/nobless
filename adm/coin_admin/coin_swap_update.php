<?php
$sub_menu = "700500";
include_once('./_common.php');

if($date_start_stx) {
	$sql_search .= " and a.sw_wdate >= '$date_start_stx 00:00:00' ";
	$qstr.="&date_start_stx=$date_start_stx";
}
if($date_end_stx) {
	$sql_search .= " and a.sw_wdate <= '$date_end_stx 23:59:59' ";
	$qstr.="&date_end_stx=$date_end_stx";
}
if($coin_stx) {
	$sql_search .= " and a.sw_token = '$coin_stx' ";
	$qstr.="&coin_stx=$coin_stx";
}

auth_check($auth[$sub_menu], 'w');

check_admin_token();


if ($w == '' || $w == 'u') {

	
	$sw_amt=round(only_number($_POST['sw_amt']),6);	
	$fee=only_number($cset['swap_fee_'.$sw_token]);	
	
	if($_POST[mb_id]) $mb=get_member($_POST[mb_id]);
	else $mb=get_emailmember($_POST[mb_email]);
	
	if(!$mb) alert('회원정보를 찾을수 없습니다');
		
	$rpoint=get_mempoint($mb[mb_id]);
	
	//보유수량
	$sum=$rpoint[$sw_token]['_enable']*1;
	
	if($sw_amt == 0) alert($_POST['sw_amt'].'스왑수량을 입력하세요');	
	
	$sw_fee=round($sw_amt*$fee/100,6);
	
	$sw_tamt=$sw_amt-$sw_fee;	
	
	
}

if ($w == '') {		
		
	if($sum < abs($sw_amt))  alert('보유량이 부족합니다');		
	
    $sql = " insert into {$g5['cn_swap_table']}
                set					
				mb_id		 = '{$mb['mb_id']}',	 
				sw_wdate = now(),
				sw_token='$_POST[sw_token]',
				sw_amt  = '{$sw_amt}',
				sw_fee  = '{$sw_fee}',
				sw_tamt  = '{$sw_tamt}',
				sw_set_token='$_POST[sw_set_token]',
				sw_set_amt='$sw_tamt'
				";
	
	//echo $sql;				
    sql_query($sql,1);	
	$sw_no=sql_insert_id();
	
	//스왑처리
	$data= sql_fetch("select * from {$g5['cn_swap_table']} where sw_no='$sw_no'");			
		
	$return=set_swap_coin($data,$sw_stats,1);
	
	
} else if ($w == 'u') {
	
	$data= sql_fetch("select * from {$g5['cn_swap_table']} where sw_no='$sw_no'");		
	
	$old_amt=0;
	if($data['sw_token']==$sw_token ) $old_amt=$data['sw_amt'];
	
	if($sum < ($sw_amt-$old_amt))  alert('보유량이 부족합니다');
	
   	$sql = " update  {$g5['cn_swap_table']} set 
				sw_amt  = '{$sw_amt}',
				sw_fee  = '{$sw_fee}',
				sw_tamt  = '{$sw_tamt}',
				sw_set_token='$_POST[sw_set_token]',
				sw_set_amt='$sw_tamt'
              where sw_no = '{$sw_no}' ";
    //sql_query($sql,1);
	
	//echo $sql;
	//수량 변경시 취소후 재처리
	if($data['sw_amt']!=$sw_amt ||  $data['sw_token']!=$sw_token || $data['sw_set_token']!=$sw_set_token  ) set_swap_coin($data,1,1);
	
	//스왑처리
	$return=set_swap_coin($data,$sw_stats,1);
	
}
	
goto_url("./coin_swap_list.php?$qstr");
?>