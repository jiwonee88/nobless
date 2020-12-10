<?php
include_once('./_common.php');
$mb= get_member($mb_id);

$rpoint=get_mempoint($member['mb_id']);
$isum = get_itemsum($mb['mb_id']);

$tot_usd=0;
foreach($g5['cn_cointype'] as $k=> $v) $tot_usd+=swap_usd($rpoint[$k]['_enable'],$k,$sise);
$tot_btc=swap_coin($tot_usd,'d','b',$sise);

$stake_usd=0;
foreach($g5['cn_cointype'] as $k=> $v) $stake_usd+=swap_usd($rpoint[$k]['stake']+$rpoint[$k]['stake_out'],$k,$sise);
$stake_btc=swap_coin($stake_usd,'d','b',$sise);

if(abs($stake_usd) >= $cset['staking_amt']){
	$mine_usd=$stake_usd * $cset['staking_reward']/100;
	$mine_btc=swap_coin($mine_usd,'d','b',$sise);
}
/*
$price_query = "select sum(b.ct_buy_price) as price from coin_tree as a left join coin_item_cart as b on a.smb_id = b.mb_id where a.mb_id = '{$member['mb_id']}' and b.is_soled!='1' group by b.cn_item";
$price_result = sql_fetch($price_query);
$price_label = $price_result['price']+$isum['tot']['price'];

$flower_query = "select sum(b.amount) as price from coin_tree as a left join coin_pointsum as b on a.smb_id = b.mb_id where a.mb_id = '{$member['mb_id']}' and b.pt_coin = 'i'";
$flower_result = sql_fetch($flower_query);
$flower_label = $flower_result['price']+$rpoint['i']['_enable'];
*/

$mb[mb_hp]=$mb[mb_10]=$mb[mb_pass]='';
$mb[tot_btc]=number_format2($tot_btc);
$mb[stake_btc]=number_format2(abs($stake_btc));
$mb[mine_btc]=number_format2(abs($mine_btc));
$mb[stake_usd]=number_format2(abs($stake_usd));
//$mb[price_label]=number_format2(abs($price_label));
//$mb[flower_label]=number_format2(abs($flower_label));
alert_json(true,'ok',$mb);