<?php
include_once('./_common.php');

exit;
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
	
	$temp=sql_fetch("select * from  {$g5['cn_sub_account']} where mb_id='{$data[mb_id]}' and ac_id='{$data[mb_id]}'");
	if($temp[ac_id]) continue;
	
	//현재 기본 계정 잔액
	//$ac_point_i=$data[mb_point_free_i];
	
	//$temp=sql_fetch("select sum(ac_point_i) amt from  {$g5['cn_sub_account']} where mb_id='{$member[mb_id]}'");
	
	//if($temp[amt]) $ac_point_i-=$temp[amt]*1;
	
	//기본 서브 계정 추가
	
	//$srpoint=get_mempoint($data[mb_id],$data[mb_id]);
	
	echo $ac_point_i." / ".$srpoint['i']['_enable'].( $ac_point_i != $srpoint['i']['_enable']?'<span style="color:red;">fail</span>)':'')."<br>";
	
	$sql = " insert into {$g5['cn_sub_account']}
				set
				mb_id='{$data[mb_id]}',
				ac_id='{$data[mb_id]}',
				ac_point_i='0',
				ac_active='$data[mb_active]',
				ac_wdate='$data[mb_datetime]'
				";			
	
	 
	echo $sql ."<br>";
	sql_query($sql,1);	
			
	set_update_point($data[mb_id],$data[mb_id]);	
	echo "<br>";
	

	$cnt++;

}