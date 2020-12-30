<?php
$sub_menu = "800000";
include_once('./_common.php');

check_demo();

check_admin_token();

if($date_start_stx) {
	$qstr.="&date_start_stx=$date_start_stx";
}
if($date_end_stx) {
	$qstr.="&date_end_stx=$date_end_stx";
}
if ($item_stx) {
   $qstr.="&item_stx=$item_stx";
}

//내역 삭제
if ($w=='d') {
	
    auth_check($auth[$sub_menu], 'd');
	
	sql_query("delete from {$g5['cn_item_log']}  where lg_no='$lg_no'  ",1);
	
}

goto_url('./item_matching_history.php?'.$qstr);
?>