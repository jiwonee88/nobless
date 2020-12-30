<?php
$sub_menu = "500200";
include_once('./_common.php');

check_demo();

if (!count($_POST['chk'])) {
    alert($_POST['act_button']." 하실 항목을 하나 이상 체크하세요.");
}

check_admin_token();

if ($iss_stx=='n' ) {
	$qstr.="&iss_stx=$iss_stx";
}
if ($coin_stx) {
	$qstr.="&coin_stx=$coin_stx";
}

if ($_POST['act_button'] == "선택수정") {

    auth_check($auth[$sub_menu], 'w');

    for ($i=0; $i<count($_POST['chk']); $i++) {

        // 실제 번호를 넘김
        $k = $_POST['chk'][$i];		
		$token_no=$_POST['token_no'][$k];		
		$token_addr          =  $_POST['token_addr'][$k];

		
		$data=get_participation($token_no);
	
        $sql = " update {$g5['cn_token_table']}
                    set 
						token_addr          = '{$token_addr}'
                  where token_no            = '".$token_no."' ";
		
        sql_query($sql);
    }

} else if ($_POST['act_button'] == "선택삭제") {

    auth_check($auth[$sub_menu], 'd');
   
    for ($i=0; $i<count($_POST['chk']); $i++) {
        // 실제 번호를 넘김
        $k = $_POST['chk'][$i];

        // include 전에 $bo_table 값을 반드시 넘겨야 함
        $tmp_token_no = trim($_POST['token_no'][$k]);
		
        $sql = " delete from {$g5['cn_token_table']}     where token_no          = '".$tmp_token_no."' ";
		sql_query($sql,1);
    }

}

goto_url('./token_addr_list.php?'.$qstr);
?>