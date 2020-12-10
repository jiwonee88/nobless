<?php
$sub_menu = "700100";
include_once('./_common.php');

if($dates_stx){
	$qstr.="&dates_stx=$dates_stx";
}else if($datee_stx){
	$qstr.="&datee_stx=$datee_stx";
}

if($coin_stx) {
	$sql_search .= " and a.sk_token = '$coin_stx' ";
	$qstr.="&coin_stx=$coin_stx";
}
if ($stats_stx) {
	$qstr.="&sk_stats=$stats_stx";
}

auth_check($auth[$sub_menu], 'w');

check_admin_token();


if ($w == '' || $w == 'u') {
	
	$sk_amt=only_number($_POST['sk_amt']);	
	
	if($_POST[mb_id]) $mb=get_member($_POST[mb_id]);
	else $mb=get_emailmember($_POST[mb_email]);
	
	if(!$mb) alert('회원정보를 찾을수 없습니다');
	
	$rpoint=get_mempoint($mb['mb_id']);
	
	
	
	$sql_common .= "		
		sk_wallet  = 'free',	
		sk_token='$_POST[sk_token]',
		sk_amt  = '{$sk_amt}'		
		";


	if ($w == '') {	
		
		if($rpoint[$sk_token]['_enable']  < $sk_amt) alert('보유수량이 부족합니다');
		
		$sql = " insert into {$g5['cn_stake_table']}
					set
					mb_id	 = '{$mb['mb_id']}',	 
					sk_wdate = now(),
					$sql_common ";

		//echo $sql;				
		sql_query($sql,1);	
		$sk_no=sql_insert_id();

		$data= sql_fetch("select * from {$g5['cn_stake_table']} where sk_no='$sk_no'");			

		$return=set_staking_add($data,$sk_stats,1);


	} else if ($w == 'u') {
		$data= sql_fetch("select * from {$g5['cn_stake_table']} where sk_no='$sk_no'");		
		
		if(!$data[sk_no]) alert('수정할 스테이킹 내역이 없습니다');
		
		$old_amt=0;
		if($data['sk_token']==$sk_token) $old_amt=$data['sk_amt'];
		if($rpoint[$data['sk_token']]['_enable']  < ($sk_amt-$old_amt) ) alert('보유수량이 부족합니다');
				
		$sql = " update  {$g5['cn_stake_table']} set 			 
				 {$sql_common}
				  where sk_no = '{$sk_no}' ";
		sql_query($sql,1);

		//토큰/수량 변경시 취소후 재처리
		if($data['sk_amt']!=$sk_amt || $data['sk_token']!=$sk_token) set_staking_add($data,1,1);

		//입금처리
		$return=set_staking_add($data,$sk_stats,1);

	}
}
goto_url("./staking_list.php?$qstr");
?>