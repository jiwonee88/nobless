<?php
$sub_menu = "700500";
include_once('./_common.php');

check_demo();

if (!count($_POST['chk'])) {
    alert($_POST['act_button']." 하실 항목을 하나 이상 체크하세요.");
}

//check_admin_token();

if($date_start_stx) {
	$qstr.="&date_start_stx=$date_start_stx";
}
if($date_end_stx) {
	$qstr.="&date_end_stx=$date_end_stx";
}
if($item_stx) {
	$qstr.="&item_stx=$coin_stx";
}

if ($_POST['act_button'] == "선택삭제") {
		
    auth_check($auth[$sub_menu], 'd');
   
    for ($i=0; $i<count($_POST['chk']); $i++) {
        // 실제 번호를 넘김
        $k = $_POST['chk'][$i];		
		$code=$_POST['code'][$k];				
		
		$data= sql_fetch("delete from {$g5['cn_item_cart']} where code='$code'");				
	
    }

}

goto_url('./item_cart_list.php?'.$qstr);
?>