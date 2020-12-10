<?php
include_once('./_common.php');

exit;

//회원 포인트 배치
if($is_admin!='super') die('권한 없음');

/*
$sql = " select mb_id,sum(ct_buy_price) ct_buy_price ,sum(ct_sell_price) ct_sell_price  from {$g5['cn_item_cart']} where is_soled!='1' and is_trade!='1' group by mb_id";
$result = sql_query($sql,1);

$cnt=1;
while($data=sql_fetch_array($result)) {
	
	echo $cnt.") ". $data[mb_id] ."/". $data[ct_buy_price] ."/". $data[ct_sell_price] ."<br>";
		
	
	$content['pt_wallet']='free'; //지갑구
	$content['pt_coin']='e'; //화폐구분
	$content['amount']=$data[ct_buy_price];			
	$content['subject']='보유금액전환';
	
	$mb[mb_id]=$data[mb_id];
	set_add_point('in',$mb,'',$member[mb_id],$content);		
	
	
	$cnt++;

}
*/
/*
$sql = " select mb_id,sum(ct_buy_price) ct_buy_price ,sum(ct_sell_price) ct_sell_price  from coin_item_cart_0831 where is_soled='0' and is_trade='1' group by mb_id";
$result = sql_query($sql,1);

$cnt=1;
while($data=sql_fetch_array($result)) {
	
	echo $cnt.") ". $data[mb_id] ."/". $data[ct_buy_price] ."/". $data[ct_sell_price] ."<br>";
		
	
	$content['pt_wallet']='free'; //지갑구
	$content['pt_coin']='e'; //화폐구분
	$content['amount']=$data[ct_sell_price];			
	$content['subject']='보유금액전환';
	
	$mb[mb_id]=$data[mb_id];
	set_add_point('in',$mb,'',$member[mb_id],$content);		
	
	
	$cnt++;

}

*/

/*추가분*/
$sql = " select mb_id,sum(ct_buy_price) ct_buy_price ,sum(ct_sell_price) ct_sell_price  from  coin_item_cart_0831 where is_soled!='1' and is_trade!='1' group by mb_id";
$result = sql_query($sql,1);

$cnt=1;
while($data=sql_fetch_array($result)) {
	
	echo $cnt.") ". $data[mb_id] ."/". $data[ct_buy_price] ."/". $data[ct_sell_price] ." / ".( $data[ct_sell_price]- $data[ct_buy_price])."<br>";
		
	$amt= $data[ct_sell_price]- $data[ct_buy_price];
	
	if($amt > 0){
		$content['pt_wallet']='free'; //지갑구
		$content['pt_coin']='e'; //화폐구분
		$content['amount']=$amt;			
		$content['subject']='보유금액전환(추가분)';

		$mb[mb_id]=$data[mb_id];
		set_add_point('in',$mb,'',$member[mb_id],$content);		
	}
	
	$cnt++;

}