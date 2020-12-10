<?php
/* 출금 처리 */
$exe_path=dirname(__FILE__);
include $exe_path."/../common.php";

//오늘 입금 예정중 미처리 된 
// 최대 3일 마감 자료 까지 검토
$ldate=strtotime("Y-m-d","-3 days");
$sql =  "select * from {$g5['cn_reserve_table']} where in_rsv_date >= '$ldate' and in_stats=='1' order by in_no asc limit 3";
$result = sql_query($sql);



//입금여부 확인
while($data=sql_fetch_array($result)){
	
	
	//입금 확인 체크//
	
	
	
	
	
	////////////////
	
	//
		
	
}

