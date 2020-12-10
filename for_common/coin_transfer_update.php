<?php
include_once('./_common.php');

//서비스 블럭
service_block();


//서브 계정에 골드 이체
if ( $w == 'u') {
	
	//alert_json(false,'점검중입니다');	
	
	$tr_fee_token=$_POST['tr_token'];
	
	if($mb_id=='' || $tr_token=='') alert_json(false,'ERROR');	
	
	if($mb_id=='') alert_json(false,'이체할 아이디를 입력하세요');	
	
	if($mb_id==$stmb_id) alert_json(false,"동일한 아이디에 전송할수 없습니다");
	
	$stmb=get_submember($stmb_id);
	if(!$stmb) alert_json(false,"입력한 아이디의 계정을 찾을수 없습니다");
	
	if($stmb[mb_id]!=$member[mb_id]) alert_json(false,"회원님의 계정이 아닙니다");	
	
	$mrpoint=get_mempoint($member[mb_id],$mb_id);
	
	$sum=$mrpoint[$tr_token]['_enable']*1;	
	
	$tr_token=trim($_POST['tr_token']);
	
	$tr_amt=round(only_number($_POST['tr_amt']),6);	
	
	//수수료
	$tr_fee=$cset['trans_fee_'.$tr_token];	
	
	//지급 총액
	$tr_tamt=$tr_amt-$tr_fee;	
				
	if( $tr_amt == 0) alert_json(false,'전송할 수량을 입력하세요');
	
	if($sum < abs($tr_amt))  alert_json(false,'전송할 수량이 부족합니다');
		
	//활성화 비용 이하는 출금 불가
	if( $tr_token =='i' && $cset[staking_amt] > ($sum - $tr_amt) )  alert_json(false,'전송할 잔액이 활성화 최소 보유량 미만입니다');
		
	if( $tr_amt < $tr_fee) alert_json(false,"전송할 수량이 수수료 보다 커야 합니다.");	
	
	//최소금액
	//if($cset['min_trans_'.$tr_token] > swap_usd($tr_amt,$tr_token)) alert_json(false,"Minimum amount of transfer : $".number_format2($cset['min_trans_'.$tr_token]));
	if($cset['min_trans_'.$tr_token] > $tr_amt ) alert_json(false,"최소 전송 수량보다 적습니다 : ".number_format2($cset['min_trans_'.$tr_token]));
	
	//if($member['mb_password'] != get_encrypt_string(trim($_POST['pass'])) ) alert_json(false,'이체 암호가 옳바르지 않습니다');
	
	
	//이체내역 기록
	$sql_common .= " 
			tr_token='$tr_token',
			tmb_id  = '{$mb_id}',	
			stmb_id  = '{$stmb['ac_id']}',	
			tr_amt  = '{$tr_amt}',	
			tr_fee  = '{$tr_fee}',	
			tr_fee_token  = '{$tr_fee_token}',	
			tr_tamt  = '{$tr_tamt}',
			
			tr_set_amt  = '{$tr_amt}',	
			tr_set_fee  = '{$tr_fee}',	
			tr_set_tamt  = '{$tr_tamt}',
			
			tr_stats  = '1'			
					";
					
    $sql = " insert into {$g5['cn_transfer_table']}
                set 				
				mb_id		 = '{$mb_id}',
				tr_wdate = now(),
				tr_mdate=now(),
				$sql_common ";
	
	//echo $sql;				
    sql_query($sql,1);	
	$tr_no=sql_insert_id();	
	
	$data=sql_fetch("select * from {$g5['cn_transfer_table']} where tr_no='$tr_no'");				
	$data['tr_stats']='0';

	
	//즉시 실행
	$result=set_transfer_coin($data,3,1);
	
	//잔액 조회
	$rpoint=get_mempoint($member['mb_id']);
	$mrpoint=get_mempoint($member['mb_id'],$mb_id);
	$srpoint=get_mempoint($member['mb_id'],$stmb_id);
	
	//alert_json(true,'',array('tr_no'=>$tr_no,'max_enable'=>number_format2($rpoint[$tr_token]['_enable'],6),'max_enable_usd'=>number_format2(swap_usd($rpoint[$tr_token]['_enable'],$tr_token),2) ));	
	alert_json(true,'',$rpoint,$mrpoint,$srpoint);	
}
/* else if ($w == 'u') {
	
    $sql = " update  {$g5['cn_transfer_table']} set 
			 
	         {$sql_common}
              where tr_no = '{$tr_no}' ";
   // sql_query($sql,1);
	
}
*/


	
//goto_url("./coin_transfer_list.php?page=$page&{$qstr}");
?>