<?php
include_once('./_common.php');

//exit;

//
//기본 계정 추가
//회원 포인트 배치
if($is_admin!='super') die('권한 없음');

$sql_search = " where (1) ";
$sql = " select * from {$g5['member_table']} $sql_search  order by mb_no asc ";
$result = sql_query($sql,1);

$cnt=1;
while($data=sql_fetch_array($result)) {
	
	echo $cnt.") ". $data[mb_id] .'-----------<br>';	
	
	$cnt2=1;
	
	$result2=sql_query("select * from  {$g5['cn_sub_account']} where mb_id='{$data[mb_id]}' ");
	while($data2=sql_fetch_array($result2)) {
	
		echo $cnt2.") ". $data[mb_id] .'/'.$data2[ac_id]. '-----------<br>';	

		set_update_point($data[mb_id],$data2[ac_id]);	
		
		$cnt2++;

	}
	
	$cnt++;
	
}