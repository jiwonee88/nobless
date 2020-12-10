<?php
include_once('./_common.php');

exit;

//회원 포인트 배치
if($is_admin!='super') die('권한 없음');

$sql_search = " where (1) ";

$sql = " select * from {$g5['member_table']}  order by mb_no asc ";
$result = sql_query($sql,1);

$cnt=1;
$tot=0;

while($data=sql_fetch_array($result)) {
		
	//총 꿀단지 포인트
	$rpoint=get_mempoint($data[mb_id]);
		
	echo $cnt.") ". $data[mb_id] ." / ".number_format($rpoint[b][_enable]). "-----------<br>";
	
	$tot+=$rpoint[b][_enable];
		
	//비우기
	$query1="delete from  coin_point_2007 where mb_id='$data[mb_id]' and pt_coin='b' ";	
	$query2="delete from  coin_point_2008 where mb_id='$data[mb_id]' and pt_coin='b' ";
	$query3="delete from  coin_pointsum where mb_id='$data[mb_id]' and pt_coin='b' ";
	
	$query4="update coin_sub_account set ac_point_b=0  where mb_id != ac_id";
	
		
	//sql_query($query1,1);
	//sql_query($query2,1);
	//sql_query($query3,1);
	
	if($rpoint[b][_enable] > 0){

		//메인  꿀단지 지급
		$content['pt_wallet']='free'; //지갑구
		$content['pt_coin']='b'; //화폐구분
		$content['amount']=$rpoint[b][_enable];			
		$content['subject']='꿀단지 통합';

		//set_add_point('in',$data,'',$member[mb_id],$content);		

		echo "<br>";	

		//회원별 최종 수당 정보 업데이트
		set_update_point($data['mb_id']);

	}
		
	$cnt++;
	
}

echo "sum) ".number_format($tot). "-----------<br>";

//delete from  coin_point_2007 where  pt_coin='b' and  pkind='in' and amount*1=0 