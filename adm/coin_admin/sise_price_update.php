<?php
$sub_menu = "800500";
include_once('./_common.php');
//$mbSql = "select * from {$g5['member_table']}";
//$mbQuery = sql_query($mbSql);
$totalUpdated = 0;
foreach($_REQUEST['selected_user'] as $mb){
	echo $mb;
	$buyPriceQuery = "select count(*) as cnt from coin_sub_account where mb_id='{$mb}' and ac_auto_a = '1' order by ac_id asc";
	$sellPriceQuery ="select count(*) as cnt from coin_item_cart  where mb_id='{$mb}' and cn_item='a'  and is_soled!='1' and curdate()<ct_validdate";
	$buyRow = sql_fetch($buyPriceQuery);
	$sellRow = sql_fetch($sellPriceQuery);
	$cntBuy = $buyRow['cnt'];
	$cntSell = $sellRow['cnt'];
	if($cntBuy<$cntSell&&$cntSell!=0){
		$buyPriceUpdateQuery = "update coin_item_cart set ct_sell_price = ct_buy_price where mb_id='{$mb}' and (cn_item='a' or cn_item='b') and is_soled!='1' and date(ct_validdate)=date(date_add(now(), interval +1 day))";//
		sql_query($buyPriceUpdateQuery);
		$totalUpdated++;
	}
}
/*
while($row = sql_fetch_array($mbQuery)){
	$buyPriceQuery = "select count(*) as cnt from coin_sub_account where mb_id='$row[mb_id]' and ac_auto_a = '1' order by ac_id asc";
	$sellPriceQuery ="select count(*) as cnt from coin_item_cart  where mb_id='$row[mb_id]' and cn_item='a'  and is_soled!='1' and curdate()<ct_validdate";
	$buyRow = sql_fetch($buyPriceQuery);
	$sellRow = sql_fetch($sellPriceQuery);
	$cntBuy = $buyRow['cnt'];
	$cntSell = $sellRow['cnt'];
	if($cntBuy<$cntSell&&$cntSell!=0){
		$buyPriceUpdateQuery = "update coin_item_cart set ct_sell_price = ct_buy_price where mb_id='$row[mb_id]' and cn_item='a' and is_soled!='1' and curdate()<ct_validdate";//
		sql_query($buyPriceUpdateQuery);
		$totalUpdated++;
	}
}
*/
?>
<script>
alert("총 <?=$totalUpdated?>건이 적용되었습니다.");
location.href='./sise_price.php';
</script>