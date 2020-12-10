<?php
$sub_menu = "800400";
include_once('./_common.php');

check_demo();

if (!count($_POST['chk'])) {
    alert($_POST['act_button']." 하실 항목을 하나 이상 체크하세요.");
}

check_admin_token();

$qstr.="&mb_stx=$mb_stx";

if ($_POST['act_button'] == "선택수정") {

    auth_check($auth[$sub_menu], 'w');

    for ($i=0; $i<count($_POST['chk']); $i++) {

        // 실제 번호를 넘김
        $k = $_POST['chk'][$i];		
		$pt_no=$_POST['pt_no'][$k];		
		$deposit_date          =  $_POST['deposit_date'][$k];

		
		$data=get_participation($pt_no);
	
        $sql = " update {$g5['cn_pt_table']}
                    set 
						deposit_date          = '{$deposit_date}'
                  where pt_no            = '".$pt_no."' ";
		
        sql_query($sql);
    }

} else if ($_POST['act_button'] == "선택삭제") {

    auth_check($auth[$sub_menu], 'd');
   
    for ($i=0; $i<count($_POST['chk']); $i++) {
        // 실제 번호를 넘김
        $k = $_POST['chk'][$i];

        // include 전에 $bo_table 값을 반드시 넘겨야 함
        $tmp_pt_no = trim($_POST['pt_no'][$k]);
		
        delete_pointtrans($tmp_pt_no);
    }


}

goto_url('./point_list.php?'.$qstr);
?>