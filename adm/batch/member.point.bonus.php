<?php
include_once('./_common.php');

exit;
//현금 거래 골드 10% 환급

if($is_admin!='super') die('권한 없음');

$sql = " select * from {$g5['cn_item_trade']} where tr_wdate <= '2020-05-24' and tr_stats='3' and tr_distri='hq' ";

$result = sql_query($sql,1);

$cnt=1;
$member_arr=array();
while($data=sql_fetch_array($result)) {

	echo $cnt.") ". $data[tr_code] .'/구매자 : '.$data[mb_id] .'/구매자 서브계정 : '.$data[smb_id] .'/'.$data[cn_item].'/거래등록일 : '. $data[tr_rdate] .'/'.
	$g5[cn_item][$data[cn_item]][name_kr] .'/ txid : '.$data[tr_txid].'/ 입급자 정보 : '.$data[tr_deposit] .'-----------<br>';
		
	
	if(preg_match("/^0x/",$data[tr_txid]) ) {
		echo "<span style='color:red;'>테더거래 skip </span><br>";
		continue;
	}
	if($data[tr_deposit]=='') {
		echo "<span style='color:red;'>입금자 정보 없음 skip </span><br>";
		continue;
	}
	
	if($data[tr_price]==0) {
		echo "<span style='color:red;'>환급금액이 없음 skip </span><br>";
		continue;	
	}
	
	echo "<span style='color:blue;'>골드지급 ".($data[tr_price])." </span>";
	
	//메인 아이디 가입 축하 포인트 지급		
	$content['pt_wallet']='free'; //지갑구pt_coin
	$content['pt_coin']='i'; //화폐구분
	$content['amount']=$data[tr_price];
	$content['subject']='오픈기념 현금구매 10% 환급';

	$mb[mb_id]=$data[mb_id];
	//print_r($content);
	set_add_point('cashbonus',$mb,'',$data[mb_id],$content);		
	
	echo "<br>";	
	
	if(array_search($data['mb_id'],$member_arr)===false)  $member_arr[]=$data['mb_id'];
	
	$cnt++;
	
	$tot+=$data[tr_price];

}

foreach($member_arr as $mb_id ) echo $mb_id."<br>";

foreach($member_arr as $mb_id ) set_update_point($mb_id);



echo "<strong><span style='color:blue;'>총 골드 ".number_format($tot)." / ".number_format($cnt)."회 </span></strong><br>
<br>
<br>
<br>
";








