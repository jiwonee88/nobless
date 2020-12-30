<?php
$sub_menu = "700600";
include_once('./_common.php');
		
if ($_POST['act_button'] == "패널티실행") {

    auth_check($auth[$sub_menu], 'w');
 	
	$row=sql_fetch("select * from {$g5['cn_item_trade']}  as a where a.tr_code='$tr_code' ",1);
	
	if($is_branch){		
		
		$sql = " and 
		(
		b.mb_recommend='{$member[mb_id]}' 		
		or b.mb_recommend in (select mb_id from {$g5[member_table]} where mb_recommend='{$member[mb_id]}' )
		or b.mb_recommend in (select mb_id from {$g5[member_table]} where mb_recommend in (select mb_id from {$g5[member_table]} where mb_recommend='{$member[mb_id]}' )  )		
		)
		";
		
		if($target=='buyer') $sql.=" and  mb_id='$row[fmb_id]'";		
		else if($target=='seller')  $sql.=" and  mb_id='$row[mb_id]'";
		
		$mb=sql_fetch("select * from  {$g5['member_table']} where (1) $sql  ");
		
		if(!$mb) alert('권한이 없는 회원입니다');
	}
	
	set_trade_penalty($row,$target,$stats_pt,$give_coin,$give_coin_amt,$get_coin,$get_coin_amt);	


	goto_url('./open_penalty_info.php?tr_code='.$qstr);
}
?>