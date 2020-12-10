<?php
$sub_menu = "200400";
include_once('./_common.php');

check_demo();

if (!count($_POST['chk'])) {
    alert($_POST['act_button']." 하실 항목을 하나 이상 체크하세요.");
}

auth_check($auth[$sub_menu], 'w');

check_admin_token();

if ($_POST['act_button'] == "선택수정") {

    for ($i=0; $i<count($_POST['chk']); $i++)
    {
        // 실제 번호를 넘김
        $k = $_POST['chk'][$i];
		 
		$sql = " update {$g5['cn_sub_account']}
					set ac_active = '".sql_real_escape_string($_POST['ac_active'][$k])."',
						ac_auto_a = '".sql_real_escape_string($_POST['ac_auto_a'][$k])."',
						ac_auto_b = '".sql_real_escape_string($_POST['ac_auto_b'][$k])."',
						ac_auto_c = '".sql_real_escape_string($_POST['ac_auto_c'][$k])."',
						ac_auto_d = '".sql_real_escape_string($_POST['ac_auto_d'][$k])."',
						ac_auto_e = '".sql_real_escape_string($_POST['ac_auto_e'][$k])."',
						ac_auto_f = '".sql_real_escape_string($_POST['ac_auto_f'][$k])."',
						ac_auto_g = '".sql_real_escape_string($_POST['ac_auto_g'][$k])."',
						ac_auto_h = '".sql_real_escape_string($_POST['ac_auto_h'][$k])."',
						ac_mc_priority = '".sql_real_escape_string($_POST['ac_mc_priority'][$k])."',
						ac_mc_except = '".sql_real_escape_string($_POST['ac_mc_except'][$k])."'
						
					where ac_id = '".sql_real_escape_string($_POST['ac_id'][$k])."'";
		
		//echo $sql;
		sql_query($sql,1);
    }

} else if ($_POST['act_button'] == "선택삭제") {

    for ($i=0; $i<count($_POST['chk']); $i++)
    {
        // 실제 번호를 넘김
        $k = $_POST['chk'][$i];
        
         // 서브계정 삭제
		 del_subaccount($_POST['ac_id'][$k]);
       
    }
}



if ($msg)
    //echo '<script> alert("'.$msg.'"); </script>';
    alert($msg);

goto_url("./member_avatar_list.php?$qstr");
?>
