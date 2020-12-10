<?php
include_once('./_common.php');

exit;

//회원 포인트 배치
if($is_admin!='super') die('권한 없음');


$sql = " select * from  {$g5['cn_item_cart']} as a where is_trade='1' and is_soled!='1' and ct_validdate <= '2020-05-26' and not exists (select cart_code from {$g5['cn_item_trade']} where cart_code = a.code and tr_stats='1' ) ";


$sql = " select * from  {$g5['cn_item_cart']} as a where is_trade!='1' and is_soled!='1' and ct_validdate <= '2020-05-26' and exists (select cart_code from {$g5['cn_item_trade']} where cart_code = a.code and tr_stats='9' and date(tr_setdate)= date(now())) ";

$result = sql_query($sql,1);

$cnt=1;
while($data=sql_fetch_array($result)) {

	echo $cnt.") ". $data[code] .'/'.$data[cn_item] .'/'. $data[ct_validdate] .'-----------<br>';
		
		
	$sql="update {$g5['cn_item_cart']}  set
	is_trade='0',
	trade_cnt=0	
	where code='$data[code]'	
	";
			
	echo $sql."<br>";
	//sql_fetch($sql,1);
	//		
	
	
	
	$cnt++;

}