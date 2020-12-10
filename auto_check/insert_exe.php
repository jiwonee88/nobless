<?php
/* 입금 처리 */
$exe_path=dirname(__FILE__);
include $exe_path."/../common.php";

//지연시간 제한
$ldate=strtotime("-".$g5['cn_intime_hour']." hours");

//지연시간 경과된 내역 취소 처리
sql_query("update {$g5['cn_reserve_table']} set in_stats='4' where in_stats='1' and in_wdate < '$ldate' ");

$sql =  "select * from {$g5['cn_reserve_table']} where in_wdate >= '$ldate' and in_stats='1' order by in_no asc limit 5";
$result = sql_query($sql,1);

//입금여부 확인
while($data=sql_fetch_array($result)){	
	
	//입금 확인 체크//	
	$rtn=balance_coin_eth($data['in_wallet_addr']);
	
	if($rtn[0]){
		
		$added=$rtn[1]-$data['in_balance'];
		
		//잔액 증가시 입금처리
		if($added*1 > 0 && $added*1 >= $data[in_rsv_amt]*1){	
			//입금처리
			set_insert_coin($data,$data['in_rsv_amt'],3,1);	
		}
		
		//최종잔액 입력
		sql_query("update {$g5['cn_reserve_table']} set in_balance_last='$rtn[1]', in_set_date=now() where in_no='{$data['in_no']}' ");
	}
		
	
}

