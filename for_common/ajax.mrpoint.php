<?
include_once('./_common.php');
$mrpoint = sql_fetch("select * from  {$g5['cn_sub_account']} where ac_id='$_REQUEST[mb_id]'  order by ac_id asc");
echo json_encode($mrpoint);
?>