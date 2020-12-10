<?php
include_once('./_common.php');

//서비스 블럭
service_block();

//입금 처리
if ( $_POST['w'] == 'txin') {
	
	$tr_code=trim($_POST['tr_code']);		

	$tr_txid=trim($_POST['tr_txid']);	
	
	$tr_deposit=trim($_POST['tr_deposit']);	
	
	$tdata=sql_fetch("select * from {$g5['cn_item_trade']} where tr_code='$tr_code'  and mb_id='{$member[mb_id]}' ");
	
	if($tdata[tr_code]=='' ) alert_json(false,"거래정보를 찾을수 없습니다");
	
	if($tdata[tr_stats]=='3' ) alert_json(false,"이미 완료된 거래입니다");
	if($tdata[tr_stats]=='9' ) alert_json(false,"이미 취소된 거래입니다");
	if($tdata[tr_paytype]=='cash' && $tr_deposit=='' ) alert_json(false,'입금자명을 입력하세요');	
	
	if($tdata[tr_paytype]=='both' && $tr_txid==''  && $tr_deposit=='' ) alert_json(false,'테더 입금시 Transaction hash 을, 계좌이체시 입금자명을 입력하세요');	
							
    $sql = " update {$g5['cn_item_trade']}
                set 		
				tr_paydate=now(),
				tr_txid	 = '{$tr_txid}',
				tr_deposit='$tr_deposit',
				tr_stats='2'
				where tr_code='$tr_code' and mb_id='{$member[mb_id]}'";
				
	
	//echo $sql;				
   $result= sql_query($sql);	
	
	if($result){
		
		//매너 포인트 지급		
		$paytime=time();
		$trtime1=strtotime($tdata[tr_wdate]);
		$trtime2=strtotime('+1 days',strtotime(substr($tdata[tr_wdate],0,10)));

		if($paytime > $trtime1 && $paytime < $trtime2 && date('H',$paytime) < $g5['cn_bonus_hour1'] && $g5['cn_bonus_hour_r1'] > 0) $manner_point=$tdata[ct_sell_price]*$g5['cn_bonus_hour_r1'];		
		else if($paytime > $trtime1 && $paytime < $trtime2 && date('H',$paytime) < $g5['cn_bonus_hour9'] && $g5['cn_bonus_hour_r2'] > 0 ) $manner_point=$tdata[ct_sell_price]*$g5['cn_bonus_hour_r2'];
		else $manner_point=0;
		
		//매너 포인트 지급
		if($manner_point > 0){
		
			$rpoint=get_mempoint($tdata['mb_id'],$tdata['smb_id']);
			$manner_point=min($rpoint['b']['_enable'],floor($manner_point*10)/10 ) ;
			
			if($manner_point > 0){

				$mb[mb_id]=$tdata[mb_id];			
				//구매자		
				$content['link_no']=$tdata[tr_code];					
				$content['pt_wallet']='free'; //지갑구
				$content['pt_coin']='b'; //코인구분
				$content['amount']=$manner_point  * -1;
				$content['subject']='꿀단지 매너포인트 변환';

				set_add_point('mtransout',$mb,'',$member['mb_id'],$content);						

				//판매자 매너 포인트 지급			
				$content['link_no']=$tdata[tr_code];					
				$content['pt_wallet']='free'; //지갑구
				$content['pt_coin']='e'; //코인구분
				$content['amount']=$manner_point;
				$content['subject']='꿀단지 매너포인트 변환';

				set_add_point('mtransin',$mb,'',$member['mb_id'],$content);						
			}
		}
		
		//꽃송이 추가 지급
		if($paytime > $trtime1 && $paytime < $trtime2 && date('H',$paytime) < $g5['cn_bonusf_hour'] && $g5['cn_bonusf_hour_amt'] > 0 ) $manner_flower=$g5['cn_bonusf_hour_amt'];	
		else $manner_flower=0;		
		

		if($manner_flower > 0){

			$mb[mb_id]=$tdata[mb_id];			
			//구매자에게 지급	
			$content['link_no']=$tdata[tr_code];					
			$content['pt_wallet']='free'; //지갑구
			$content['pt_coin']='i'; //꽃송이
			$content['amount']=$manner_flower;
			$content['subject']='빠른 입금 꽃송이 지급';

			set_add_point('in',$mb,'',$member['mb_id'],$content);						

		}			
	
		alert_json(true,'');	
	}
	else alert_json(false,'처리할수 없습니다');	
}

//신고 진행
if ( $_POST['w'] == 'claim') {
	
	$tr_code=trim($_POST['tr_code']);		

	$from=trim($_POST['from']);	
	
	$tdata=sql_fetch("select * from {$g5['cn_item_trade']} where tr_code='$tr_code'  ");
	
	if($tdata[tr_code]=='' ) alert_json(false,"거래정보를 찾을수 없습니다2");
	
	if($tdata[tr_stats]=='3' ) alert_json(false,"이미 완료된 거래입니다");
	if($tdata[tr_stats]=='9' ) alert_json(false,"이미 취소된 거래입니다");	
		
						
	if($from=='buyer'){
		
		if($tdata[mb_id]!=$member[mb_id] )  alert_json(false,'구매중인 거래가 아닙니다');		
		
		
		if($tdata[tr_stats]!='2' )  alert_json(false,'신고가 가능한 상태가 아닙니다');		
		
		if($tdata[tr_buyer_claim]=='1' ) alert_json(false,"이미 신고가 접수되었습니다");	
		
		
		$sql = " update {$g5['cn_item_trade']}
                set 		
				tr_buyer_claim='1',tr_buyer_note=now() ,tr_buyer_memo='".addslashes($_POST[memo])."'
				where tr_code='$tr_code'";	
	
		//echo $sql;				
		$result= sql_query($sql,1);			
		
		sql_query("update $g5[member_table] set mb_trade_put_claim=mb_trade_put_claim+1 where mb_id='$member[mb_id]'",1);
		sql_query("update $g5[member_table] set mb_trade_get_claim=mb_trade_get_claim+1 where mb_id='$data[fmb_id]'",1);
		
		if($result) alert_json(true,'',array('tr_note'=>date("Y-m-d H:i:s")) );	
		else alert_json(false,'처리 할 수 없습니다');	
	
		
	}				
	if($from=='seller'){
		
		if($tdata[fmb_id]!=$member[mb_id] )  alert_json(false,'판매중인 거래가 아닙니다');		
		
		if($tdata[tr_seller_claim]=='1' ) alert_json(false,"이미 신고가 접수되었습니다");
			
		$sql = " update {$g5['cn_item_trade']}
                set 		
				tr_seller_claim='1',tr_seller_note=now() ,tr_seller_memo='".addslashes($_POST[memo])."'
				where tr_code='$tr_code'";	
	
		//echo $sql;				
		$result= sql_query($sql);			
		
		sql_query("update $g5[member_table] set mb_trade_put_claim=mb_trade_put_claim+1 where mb_id='$member[mb_id]'");
		sql_query("update $g5[member_table] set mb_trade_get_claim=mb_trade_get_claim+1 where mb_id='$data[mb_id]'");		
		
		if($result) alert_json(true,'',array('tr_note'=>date("Y-m-d H:i:s")) );	
		else alert_json(false,'처리 할 수 없습니다');	
			
	}			
	
   
	
	if($result) alert_json(true,'',array('tr_note'=>date("Y-m-d H:i:s")) );	
	else alert_json(false,'처리 할 수 없습니다');	
}

//거래의 종료
if ( $_POST['w'] == 'complete') {
		
	//alert_json(false,'준비중입니다');	
	
	//거래정보
	$tdata=sql_fetch("select * from {$g5['cn_item_trade']} where tr_code='$tr_code' and fmb_id='{$member[mb_id]}' ",1);
	if($tdata[tr_code]=='' ) alert_json(false,"거래정보를 찾을수 없습니다");
	
	//if($tdata[tr_stats]!='2' ) alert_json(false,"입금확인이 되지 않은 거래입니다");
	if($tdata[tr_stats]=='3' ) alert_json(false,"이미 완료된 거래입니다");	
	if($tdata[tr_stats]=='9' ) alert_json(false,"이미 취소된 거래입니다");
	$result=set_trade_stat($tdata,3);
	
	
	if($result[0]==false) alert_json(false,$result[1]);	
	else alert_json(true,'처리되었습니다',array('stats'=>'거래완료','setdate'=>date("Y-m-d H:i:s")));	
}