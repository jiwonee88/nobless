<?php
include_once('./_common.php');

exit;


//회원 포인트 배치
if($is_admin!='super') die('권한 없음');




//대상의 하부 회원의 해당일자의 채굴량
$point_table=$g5['cn_point']."_".date('ym');

$re= sql_query("select * from  {$point_table}  as a where date(wdate)='2020-06-01' and pkind='mfee2' ",1);

$cnt=0;
while($data=sql_fetch_array($re)){
	
	$cnt++;
	echo $cnt.") ". $data[mb_id]."/".$data[pkind]."/".$data[amount]."/".$data[wdate]."/".$data['link_no']."<Br>";
	
	//set_del_point($data['pkind'],$data['mb_id'],'','',$data['link_no'],$data['wdate']);
}
