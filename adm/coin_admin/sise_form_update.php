<?php
$sub_menu = "500300";
include_once('./_common.php');

if ($w == 'u' )
    check_demo();

auth_check($auth[$sub_menu], 'w');

//숫자만..
$datas=array();
foreach($_POST as $k=>$v){	
	if(!preg_match("/^sise_/",$k)) continue;
	$_POST[$k]=only_number(trim($v));
	$datas[$k]=only_number(trim($v));
}

$datas=json_encode($datas);

if ($_POST['w'] == 'u') {	
    
    $sql = " update {$g5['cn_sise_table']} set is_flow='$is_flow', data='".addslashes($datas)."',wdate=now() ";	
    $result=sql_query($sql,1);

}

goto_url("./sise_form.php");
?>