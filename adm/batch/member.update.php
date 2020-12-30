<?php
include_once('./_common.php');

//exit;
//회원배치

if($is_admin!='super') die('권한 없음');

$sql_search = " where (1) ";

$sql = " select * from {$g5['member_table']} $sql_search  order by mb_no asc ";
$result = sql_query($sql,1);

$cnt=1;
while($data=sql_fetch_array($result)) {	
	
	$rpoint=get_mempoint($data[mb_id]);	
	
	echo " $cnt) $data[mb_id] => $data[mb_point_free_b] /  {$rpoint['b']['_enable']} <br>";
	
	set_update_point($data[mb_id]);
	
	$cnt++;

}