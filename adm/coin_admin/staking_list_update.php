<?php
$sub_menu = "700100";
include_once('./_common.php');

check_demo();

if (!count($_POST['chk'])) {
    alert($_POST['act_button']." 하실 항목을 하나 이상 체크하세요.");
}

check_admin_token();

if($dates_stx){
	$qstr.="&dates_stx=$dates_stx";
}else if($datee_stx){
	$qstr.="&datee_stx=$datee_stx";
}

if($coin_stx) {
	$sql_search .= " and a.in_token = '$coin_stx' ";
	$qstr.="&coin_stx=$coin_stx";
}
if ($stats_stx) {
	$qstr.="&sk_stats=$stats_stx";
}

if ($_POST['act_button'] == "선택수정") {

    auth_check($auth[$sub_menu], 'w');

    for ($i=0; $i<count($_POST['chk']); $i++) {

        // 실제 번호를 넘김
        $k = $_POST['chk'][$i];		
		$sk_no=$_POST['sk_no'][$k];		
		$sk_stats  =  $_POST['sk_stats'][$k];
		
		$data= sql_fetch("select * from {$g5['cn_stake_table']} where sk_no='$sk_no'");		
	
        //상태의 변경
		set_staking_add($data,$sk_stats,1);	
    }

} else if ($_POST['act_button'] == "선택삭제") {

    auth_check($auth[$sub_menu], 'd');
   
    for ($i=0; $i<count($_POST['chk']); $i++) {
        // 실제 번호를 넘김
        $k = $_POST['chk'][$i];		
		$sk_no=$_POST['sk_no'][$k];		
		$sk_stats         =  $_POST['sk_stats'][$k];
		
		$data= sql_fetch("select * from {$g5['cn_stake_table']} where sk_no='$sk_no'");		
		
		//삭제처리
        set_staking_add($data,'del');
	
    }

}

goto_url('./staking_list.php?'.$qstr);
?>