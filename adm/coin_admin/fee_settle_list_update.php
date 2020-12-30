<?php
$sub_menu = "800000";
include_once('./_common.php');

check_demo();

check_admin_token();

if($date_start_stx) {
	$sql_search .= " and a.st_date >= '$date_start_stx 00:00:00' ";
	$qstr.="&date_start_stx=$date_start_stx";
}
if($date_end_stx) {
	$sql_search .= " and a.st_date <= '$date_end_stx 23:59:59' ";
	$qstr.="&date_end_stx=$date_end_stx";
}


//내역 삭제
if ($w=='d') {
	
    auth_check($auth[$sub_menu], 'd');
	
	$sdata=sql_fetch("select * from {$g5['cn_set_table']} where st_no='$st_no'  ",1);

	if(!$sdata['st_no']){
		alert("삭제할 정산내역을 찾을수 없습니다");
	}	
	
	//포인트 내역 삭제
	
	//대상의 하부 회원의 해당일자의 채굴량
	$point_table=$g5['cn_point']."_".date('ym',strtotime($sdata[st_wdate]));
	$point_table2=$g5['cn_point']."_".date('ym',strtotime('-1 days',strtotime($sdata[st_wdate])));
	
	if(chk_table($point_table)){
		$re= sql_query("select * from  {$point_table}  as a where pkind='{$sdata['st_pkind']}' and link_no='{$sdata['st_no']}' ",1);

		while($data=sql_fetch_array($re)){
			set_del_point($data['pkind'],$data['mb_id'],'','',$data['link_no'],$data['wdate']);
		}
	}
	
	if(chk_table($point_table2)){
		$re= sql_query("select * from  {$point_table}  as a where pkind='{$sdata['st_pkind']}' and link_no='{$sdata['st_no']}' ",1);

		while($data=sql_fetch_array($re)){
			set_del_point($data['pkind'],$data['mb_id'],'','',$data['link_no'],$data['wdate']);
		}
	}

	sql_fetch("delete from {$g5['cn_set_table']} where st_no='$st_no'  ",1);
	
}
if($from_menu=='800200') goto_url('./fee_settle_list.php?'.$qstr);
else if($from_menu=='800210') goto_url('./fee_settle2_list.php?'.$qstr);
else if($from_menu=='800220') goto_url('./fee_settle3_list.php?'.$qstr);
?>