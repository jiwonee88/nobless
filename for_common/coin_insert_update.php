<?php
include_once('./_common.php');

//서비스 블럭
service_block();


//재화 구매 신
if ($w == '' ) {	

	//$in_token=$_POST['in_token'];
	$in_token='u';
	$in_set_token='i';
		
	$item_data=$g5['cn_golditem'][$_POST['item']];
	
	$in_rsv_amt=only_number($item_data[price] * $_POST['qty']);		
	if(!$in_rsv_amt) alert_json('false','입금액이 없습니다');
	
	
	$in_set_amt=only_number($item_data[amt] * $_POST['qty']);		
	if(!$in_set_amt) alert_json('false','구매수량이 없습니다');	
	
	$in_wallet_addr  = '';		
	
	//$data=sql_fetch("select * from {$g5['cn_purchase_table']} where mb_id = '{$member['mb_id']}' and in_stats in ('1','2')");
	//if($data) alert_json('false','진행대기중인 구매건이 있습니다t.');
	
	
	//현재 이더지갑 잔액
	if($in_token=='i'){
		$rtn=balance_coin_eth($in_wallet_addr);
	}else{
		$rtn=balance_coin_usdt($in_wallet_addr);	
	}
	
	//if(!$rtn[0]) alert_json('false','지갑 잔액을 알수 없습니다');
	//$in_balance=$rtn[1];
	
	$sql_common .= " 
			in_wallet_addr  = '{$_POST['in_wallet_addr_'.$in_token]}',	
			in_rsv_amt  = '{$in_rsv_amt}',	
			in_rsv_date  = now(),
			in_set_amt  = '{$in_set_amt}',	
			in_set_token  = 'i',	
			in_set_amt  = '{$in_set_amt}',	
			in_stats  = '1'	,
			in_balance='$in_balance',
			in_balance_last='$in_balance',
			in_mdate = now()
					";
					
    $sql = " insert into {$g5['cn_purchase_table']}
                set 
				in_token='$in_token',
				mb_id		 = '{$member['mb_id']}',
				smb_id		 = '{$_POST['smb_id']}',
				in_wdate = now(),
				$sql_common ";
	
	echo $sql;				
    $result=sql_query($sql,1);	
	$in_no=sql_insert_id();
	
	if($result) alert_json(true,'');
	else alert_json(false,'등록할수 없습니다');

//거래 번호 등록
} else if ($w == 'u') {
	
    $sql = " update  {$g5['cn_purchase_table']} set 
			 
	         in_wallet_addr  = '{$_POST['in_wallet_addr_'.$in_token]}',	
			 in_mdate = now()
              where in_no = '{$in_no}' and mb_id='{$member[mb_id]}' ";
   $result= sql_query($sql,1);
   
   if($result) alert_json(true,'');
	else alert_json(false,'등록할수 없습니다');
	
}
	
//goto_url("./item_part_form.php?in_no={$in_no}&{$qstr}");
//goto_url("./insert_reserve_list.php?page=$page&{$qstr}");
?>