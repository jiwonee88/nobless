<?php
include_once('./_common.php');

exit;

//회원 포인트 배치
if($is_admin!='super') die('권한 없음');


$sql = " select * from  {$g5['cn_item_trade']} where tr_distri='p2p'";
$result = sql_query($sql,1);

$cnt=1;
while($data=sql_fetch_array($result)) {

	$mb=get_member($data[fmb_id]);
	
	echo $cnt.") ". $data[mb_id] .'/'.$data[tr_paytype] .'/'. $data[tr_wallet_addr ]. '/'.$data[tr_paytype ].'/'.$mb['mb_wallet_addr_'.$g5['cn_pay_coin']].'-----------<br>';
	$tr_wallet_addr=$mb['mb_wallet_addr_'.$g5['cn_pay_coin']];
	
	if($tr_wallet_addr && $data[tr_wallet_addr]=='') $sqlset=" ,tr_wallet_addr = '$tr_wallet_addr' "; 
	else $sqlset=''; 
	
	$sql="update {$g5['cn_item_trade']}  set
	tr_paytype='$mb[mb_trade_paytype]', tr_bank='$mb[mb_bank]', tr_bank_num='$mb[mb_bank_num]', tr_bank_user='$mb[mb_bank_user]'
	$sqlset
	where tr_code='$data[tr_code]'	
	";
			
	echo $sql."<br>";
	sql_fetch($sql);
			
	
	
	
	$cnt++;

}