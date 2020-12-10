<?php
include_once('./_common.php');
$mbSql = "select * from {$g5['member_table']}";
$mbQuery = sql_query($mbSql);
$totalUpdated = 0;
while($row = sql_fetch_array($mbQuery)){
	$buyPriceQuery = "select count(*) as cnt from coin_sub_account where mb_id='$row[mb_id]' and ac_auto_a = '1' order by ac_id asc";
	$sellPriceQuery ="select count(*) as cnt from coin_item_cart  where mb_id='$row[mb_id]' and cn_item='a'  and is_soled!='1' and curdate()<=ct_validdate";
	$buyRow = sql_fetch($buyPriceQuery);
	$sellRow = sql_fetch($sellPriceQuery);
	echo "<br>".$row['mb_id'];
	echo " :: ". $cntBuy = $buyRow['cnt'];
	echo " :: ". $cntSell = $sellRow['cnt'];
	if($cntBuy<$cntSell&&$cntSell!=0){
		$buyPriceUpdateQuery = "update coin_item_cart set ct_sell_price = ct_buy_price where mb_id='$row[mb_id]' and cn_item='a' and is_soled!='1' and curdate()<=ct_validdate";//
		//sql_query($buyPriceUpdateQuery);
		$totalUpdated++;
	}
}

//echo "<br /><br /><br /><br />totalUpdated :",$totalUpdated;
?>
