<?php
$sub_menu = "700400";
include_once('./_common.php');

check_demo();

if (!count($_POST['chk'])) {
    alert($_POST['act_button']." 하실 항목을 하나 이상 체크하세요.");
}

check_admin_token();

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


if ($_POST['act_button'] == "선택수정") {

    auth_check($auth[$sub_menu], 'w');

    for ($i=0; $i<count($_POST['chk']); $i++) {

        // 실제 번호를 넘김
        $k = $_POST['chk'][$i];		
		$tr_no=$_POST['tr_no'][$k];		
		$tr_stats          =  $_POST['tr_stats'][$k];
		
		
		$data= sql_fetch("select * from {$g5['cn_transfer_table']} where tr_no='$tr_no'");		
	
        //상태의 변경
		set_transfer_coin($data,$tr_stats,1);	
    }

} else if ($_POST['act_button'] == "선택삭제") {

    auth_check($auth[$sub_menu], 'd');
   
    for ($i=0; $i<count($_POST['chk']); $i++) {
        // 실제 번호를 넘김
        $k = $_POST['chk'][$i];		
		$tr_no=$_POST['tr_no'][$k];		
		$tr_stats         =  $_POST['tr_stats'][$k];
		
		$data= sql_fetch("select * from {$g5['cn_transfer_table']} where tr_no='$tr_no'");		
		
		//취소처리
        set_transfer_coin($data,'del');
	
    }

}

goto_url('./coin_transfer_list.php?'.$qstr);
?>