<?php
include_once('./_common.php');

exit;

//회원 포인트 배치
if($is_admin!='super') die('권한 없음');


$sql = " select * from {$g5['cn_item_cart']} where is_soled!='1' and is_trade!='1' order by cn_item ,ct_buy_price asc ";
$result = sql_query($sql,1);

$cnt=1;
while($data=sql_fetch_array($result)) {
	
	echo $cnt.") ". $data[code] ."/". $data[ct_buy_price] ."/". $data[ct_sell_price] ."<br>";
	
	$interest=$g5[cn_item][$data[cn_item]][interest];
	
	//예정가격
	$ct_sell_price=floor( ($data[ct_buy_price] + ($data[ct_buy_price]*$interest/100)) * 10 )/10;	
	
	$sql="update  {$g5['cn_item_cart']}
			set 
			ct_sell_price='$ct_sell_price',
			ct_interest='$interest'
			where code='$data[code]'
			";

			
			
	echo $sql .'<br>';	
	
	sql_query($sql);
	
	$cnt++;

}