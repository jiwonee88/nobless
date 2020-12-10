<?php
include_once('./_common.php');

exit;

//회원 포인트 배치
if($is_admin!='super') die('권한 없음');
//회원 포인트 비우기
//sql_query("update {$g5['member_table']}  set mb_point_free_b='0'");

$sql_search = " where (1) ";

//sql_query("delete from coin_point_2005  where pt_coin='b' ");
//sql_query("delete from coin_pointsum  where pt_coin='b' ");

$sql = " select * from  {$g5['member_table']}   mb_point_free_i =0 order by mb_no asc ";
$result = sql_query($sql,1);

$cnt=1;
while($data=sql_fetch_array($result)) {	
	
	echo $cnt.") ". $data[mb_id] .'-----------<br>';
	
	//메인 아이디 가입 축하 포인트 지급		
	$content['pt_wallet']='free'; //지갑구
	$content['pt_coin']='i'; //화폐구분
	$content['amount']=3000*2;
	$content['subject']='가입 축하금';

	print_r($content);
	//set_add_point('joinb',$data,'',$data[mb_id],$content);		

	echo "<br>";	
	
	//회원별 최종 수당 정보 업데이트
	//set_update_point($data['mb_id']);
	
	$cnt++;

}