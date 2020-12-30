<?php
include_once('./_common.php');

exit;

//회원 포인트 배치
if($is_admin!='super') die('권한 없음');

$sql_search = " where (1) ";

$sql = " select * from {$g5['cn_item_cart']} $sql_search  order by code asc ";
$result = sql_query($sql,1);

$cnt=1;
while($data=sql_fetch_array($result)) {
	
	echo $cnt.") ". $data[code] .'-----------<br>';	
	
	$newcode=get_itemcode();
	
	$v=$g5['cn_item'][$data[cn_item]];		
	
	//예정가격
	$sell_price=floor( ($v[price] + ($v[price]*$v[interest]/100))*10 )/10;
	
	
	$sql="update  {$g5['cn_item_cart']}
			set 
			code='$newcode',
			ct_buy_price='$v[price]',
			ct_sell_price='$sell_price'
			
			where code='$data[code]'
			";

			sql_query($sql);
			
	echo $sql .'<br>';	
	$cnt++;

}