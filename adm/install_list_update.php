<?php
$sub_menu = "800100";
include_once('./_common.php');

check_demo();

if (!count($_POST['chk'])) {
    alert($_POST['act_button']." 하실 항목을 하나 이상 체크하세요.");
}

check_admin_token();

$qstr.="&partner_stx=$partner_stx&cate1_stx=$cate1_stx";

if ($_POST['act_button'] == "선택수정") {

    auth_check($auth[$sub_menu], 'w');

    for ($i=0; $i<count($_POST['chk']); $i++) {

        // 실제 번호를 넘김
        $k = $_POST['chk'][$i];		
		$rt_no=$_POST['rt_no'][$k];		
		
		$data=get_recruit($rt_no);
		//상태변경일	
		if($data['rt_stats']!=$_POST['rt_stats'][$k]) $set.=",rt_cdate=now() ";
	
        $sql = " update {$g5['recruit_table']}
                    set 
						rt_sdate               = '".sql_real_escape_string($_POST['rt_sdate'][$k])."',						
						rt_edate               = '".sql_real_escape_string($_POST['rt_edate'][$k])."',						
						
						rt_stats               = '".sql_real_escape_string($_POST['rt_stats'][$k])."',						
						
                        rt_view          = '".sql_real_escape_string($_POST['rt_view'][$k])."',
						rt_enable          = '".sql_real_escape_string($_POST['rt_enable'][$k])."',
						rt_order          = '".sql_real_escape_string($_POST['rt_order'][$k])."',
						
						rt_maxapp		  = '".sql_real_escape_string($_POST['rt_maxapp'][$k])."',
						
						
						rt_main          = '".sql_real_escape_string($_POST['rt_main'][$k])."'
						
						$set
                  where rt_no            = '".$rt_no."' ";

        sql_query($sql);
    }

} else if ($_POST['act_button'] == "선택삭제") {

    auth_check($auth[$sub_menu], 'd');
   
    for ($i=0; $i<count($_POST['chk']); $i++) {
        // 실제 번호를 넘김
        $k = $_POST['chk'][$i];

        // include 전에 $bo_table 값을 반드시 넘겨야 함
        $tmp_rt_no = trim($_POST['rt_no'][$k]);
		
        delete_recruit($tmp_rt_no);
    }


}

goto_url('./recruit_list.php?'.$qstr);
?>