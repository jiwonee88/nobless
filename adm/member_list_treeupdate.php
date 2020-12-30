<?php
$sub_menu = "200100";
include_once('./_common.php');

check_demo();


//데이터 비우기
sql_query("TRUNCATE TABLE $g5[cn_tree]");
sql_query("TRUNCATE TABLE $g5[cn_tree2]");

$sql_common = " from {$g5['member_table']}  where mb_10 not in ('1','2') ";

$sql = " select * {$sql_common} order by mb_no asc ";
$result = sql_query($sql);

 for ($i=0; $row=sql_fetch_array($result); $i++) {
 
 	update_mb_treedb($row[mb_tree],$row[mb_id],'tree');
	update_mb_treedb($row[mb_tree2],$row[mb_id],'tree2');
 }
 