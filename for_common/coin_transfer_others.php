<?php
include_once('./_common.php');

//서비스 블럭
service_block();



if ( $w == 'u') {
	
	$tr_fee_token='i';
	
	$tmb_id=trim($_POST[tmb_id]);
	
	if($tmb_id=='') alert_json(false,'전송받을 회원 아이디를 입력하세요');	
	
	if($member[mb_id]==$tmb_id) alert_json(false,"자신에게 전송할수 없습니다");
	
	$mb=get_member($tmb_id);
	if(!$mb) alert_json(false,"전송받을 회원을 찾을수 없습니다");
	
	//하부 회원에게만 전송 가능
	$temp=sql_fetch("select * from {$g5['cn_tree']} where mb_id='{$member['mb_id']}' and smb_id='$tmb_id' ");
	if(!$temp[mb_id]) alert_json(false,"하부회원에게만 전송이 가능합니다");
	
	
	//전송 가능금액
	$sum=get_eanble_trans($member[mb_id],$rpoint,$tr_token);
	
	$tr_token=trim($_POST['tr_token']);
	$tr_amt=round(only_number($_POST['tr_amt']),6);	
	
	$tr_fee=$cset['trans_fee_'.$tr_token];	
	
	//실 전송 금액
	$tr_tamt=$tr_amt-$tr_fee;	
	
	if($tmb_id=='' || $tr_token=='') alert_json(false,'ERROR');	
		
	if( $tr_amt == 0) alert_json(false,'전송할 수량을 입력하세요');
	
	if($sum < abs($tr_amt))  alert_json(false,'전송 가능한 수량이 부족합니다');
	
	if($tr_amt< $tr_fee) alert_json(false,"전송 수수료 보다 큰 금액을 전송해야 합니다 (".$tr_fee." ".($g5['cn_cointype'][$tr_fee_token]).") ");	
	
	//최소금액
	if($cset['min_trans_'.$tr_token] &&  $cset['min_trans_'.$tr_token] > $tr_amt ) alert_json(false,"전송 최소 금액은 : $".number_format2($cset['min_trans_'.$tr_token]) ."입니다");
	
	//if($member['mb_password'] != get_encrypt_string(trim($_POST['pass'])) ) alert_json(false,'이체 암호가 옳바르지 않습니다');
	
	
	//출금내역 기록
	$sql_common .= " 
			tr_token='$tr_token',
			tr_site  = '$tr_site',	
			tmb_id  = '{$mb['mb_id']}',	
			stmb_id  = '{$mb['mb_id']}',
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
				mb_id		 = '{$member['mb_id']}',
				tr_wdate = now(),
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
	
	//전송 가능금액
	$sum=get_eanble_trans($member['mb_id'],$rpoint,$tr_token);
	
	alert_json(true,'',array('tr_no'=>$tr_no,'enable_amt'=>number_format2($rpoint[$tr_token]['_enable']),'trans_enable_amt'=>number_format2($sum) ));	
}

//goto_url("./coin_transfer_list.php?page=$page&{$qstr}");
?>