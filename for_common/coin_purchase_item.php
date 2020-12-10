<?php
include_once('./_common.php');

//서비스 블럭
service_block();

$it_token=$_POST['it_token'];

$smb_id=$_POST['smb_id'];


$cn_item=$_POST['cn_item'];

//구매조건 검사
/*
빨강- 노랑-흰나비 순차구매만 가능
하루에 한개씩만 구매 가능
*/

function prev_item($cn_item){
	if($cn_item=='c') return 'b';
	else if($cn_item=='b') return  'a';
	else if($cn_item=='a') return  'c';
	else  return '';	
}
function _next_item($cn_item){
	if($cn_item=='a') return 'b';
	else if($cn_item=='b') return  'c';
	else if($cn_item=='c') return  'a';
	else  return 'a';	
}

if($cn_item!=''){	

	//마지막 구매상품 
	$temp = sql_fetch("select * from {$g5['cn_item_purchase']} where mb_id='$member[mb_id]' and it_stats in ('1','2','3') order by it_no  desc limit 1");
	
	//다음 구매 가능 상품
	if($temp['cn_item']) $t_item=_next_item($temp['cn_item']);
	else $t_item=_next_item('');
	
	if($t_item != $cn_item) alert_json(false,$g5[cn_item][$t_item][name_kr].' 상품을 먼저 구매하셔야 합니다');
}



$temp = sql_fetch("select * from {$g5['cn_item_purchase']} where mb_id='$member[mb_id]' and  date(it_set_date)=date(now()) and it_stats in ('1','2','3') and cn_item='$cn_item' ");
if($temp[it_no]) alert_json(false,'나비 종류별 1일 1개만 구매 가능합니다');

//서브 계정 최소 오픈 여부 확인
$open=sql_fetch("select count(ac_id) cnt from {$g5['cn_sub_account']} where ac_active='1' and ac_auto_{$cn_item}='1' and mb_id='$member[mb_id]'",1);
if($open[cnt] < 10 ) alert_json(false,$g5[cn_item][$cn_item][name_kr].' 구매 예약 계정이 최소 10개 이상이어야 합니다');


$item_data=$g5['cn_item'][$cn_item];

if(!$item_data[price]) alert_json(false,'구매가 불가능한 상품입니다');

$it_item_qty=only_number($_POST['qty']);		

//상품 원가 구매액
$it_rsv_amt=only_number($item_data[price] * $it_item_qty);		

if(!$it_rsv_amt) alert_json(false,'구매 수량이 없습니다');	

$cn_item_name=$item_data['name_kr'];

$it_set_token=$_POST['it_set_token'];

if($it_set_token=='' ) alert_json(false,'구매 수단이 없습니다');	

//결제액
$it_set_amt=swap_coin($it_rsv_amt,$it_token,$it_set_token,$sise);

if(!$it_set_amt) alert_json(false,'구매액을 설정 할 수 없습니다');	

if($rpoint[$it_set_token]['_enable']*1 < $it_set_amt) alert_json(false,$g5['cn_cointype'][$it_set_token].'가 부족합니다');	

//입금 계좌 생성
//$it_wallet_addr  =  align_mb_wallet($mb_id,$it_token);		
//if($it_wallet_addr=='') alert_json(false,'입금 계좌를 발급 할 수 없습니다');	

//$data=sql_fetch("select * from {$g5['cn_item_purchase']} where mb_id = '{$member['mb_id']}' and it_stats in ('1','2')");
//if($data) alert_json('false','진행대기중인 구매건이 있습니다t.');


//현재 이더지갑 잔액
/*
if($it_token=='e'){
	$rtn=balance_coin_eth($it_wallet_addr);
}else if($it_token=='u'){
	$rtn=balance_coin_usdt($it_wallet_addr);	
}
*/

$it_balance=0;

//if(!$rtn[0]) alert_json('false','지갑 잔액을 알수 없습니다');
//$in_balance=$rtn[1];


$sql = " insert into {$g5['cn_item_purchase']}
	set 
	
	mb_id		 = '{$member['mb_id']}',	
	smb_id		 = '{$smb_id}',	
	cn_item='".addslashes($cn_item)."',
	cn_item_name='".addslashes($cn_item_name)."',
	
	it_item_qty='$it_item_qty',
	it_token='$it_token',
	
	it_wallet_addr  = '{$it_wallet_addr}',	
	it_rsv_amt  = '{$it_rsv_amt}',	
	it_rsv_date  = now(),

	it_set_amt  = '{$it_set_amt}',	
	it_set_token  = '{$it_set_token}'	,

	it_stats  = '1'	,
	it_balance='$it_balance',
	it_balance_last='$it_balance',
	it_mdate = now(),

	it_wdate = now()
	
	";

//echo $sql;				
$result=sql_query($sql,1);	
$it_no=sql_insert_id();

if(!$result) alert_json(true,'등록 할 수 없습니다');

//잔액이 맞는 경우 바로 처리
$data=sql_fetch("select * from {$g5['cn_item_purchase']}  where it_no='$it_no' ") ;

$result=set_purchase_item($data,3,1);

$rpoint=get_mempoint($member['mb_id']);
 
if(!$result[0]){

	sql_query("delete  from {$g5['cn_item_purchase']}  where it_no='$it_no' ") ;
	alert_json(false,$result[1]);
	 
}else alert_json(true,'구매가 완료 되었습니다',array('remainAmt'=>$rpoint[$it_set_token]['_enable']) );



?>