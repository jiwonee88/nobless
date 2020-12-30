<?php
include_once('./_common.php');

exit;

//회원 포인트 배치
if($is_admin!='super') die('권한 없음');



$sql = " select * from  {$g5['cn_item_trade']} where tr_stats!='9' ";
$result = sql_query($sql,1);

$cnt=1;


while($data=sql_fetch_array($result)) {

	
	$tdata=$data;
	
	echo "$cnt. $data[fmb_id] => $data[mb_id] <br>"; 	

	/*	
	//수수료 회수 - 구매
	set_del_point('mfee',$tdata['mb_id'],'','',$tdata['tr_code'],$tdata[tr_rdate]) ;


	//꿀단지 회수 - 판매
	if($tdata[tr_src]=='honey') set_del_point('itemmat',$tdata['fmb_id'],'','',$tdata['tr_code'],$tdata[tr_rdate]);			
	*/
	
	//변환된 매너 포인트 회수
	set_del_point('transout',$tdata['mb_id'],'','',$tdata['tr_code'],$tdata[tr_rdate]);			
	set_del_point('transin',$tdata['mb_id'],'','',$tdata['tr_code'],$tdata[tr_rdate]);			
	
	$cnt++;
				
}

$sql = " select * from  {$g5['cn_item_trade']} where tr_stats!='9' ";
$result = sql_query($sql,1);

$cnt=01;

while($data=sql_fetch_array($result)) {	
	$cnt++;
	$tdata=$data;
	
	/*
	$rpoint=get_mempoint($data[fmb_id],$data[fsmb_id]);

	echo "$cnt. $data[fmb_id] => $data[mb_id] {$rpoint['b']['_enable']} / $data[ct_buy_price] <br>"; 	
	
		
	if($rpoint['b']['_enable'] < $data[ct_buy_price]) echo "<p> 난리 났네</p>";
	
	//continue;
	
	if($data[tr_fee] > 0 ){
		//구매자 수수료 지출
		$content['link_no']=$data[tr_code];					
		$content['pt_wallet']='free'; //지갑구
		$content['pt_coin']=$g5[cn_fee_coin]; //코인구분
		$content['amount']=$data[tr_fee]  * -1;
		$content['subject']='매칭수수료';

		$mb[mb_id]=$data[mb_id];
		set_add_point('mfee',$mb,$data[smb_id],$member['mb_id'],$content);						

	}

	//꿀딴지 유래 상품의 경우 
	if($data[tr_src]=='honey'){
		
		
		if($rpoint['b']['_enable'] >= $data[ct_buy_price]) $smb_id=$data[fsmb_id];
		else $smb_id='';
		
		
		//판매자 꿀단지 지출			
		$content['link_no']=$data[tr_code];					
		$content['pt_wallet']='free'; //지갑구
		$content['pt_coin']='b'; //코인구분
		$content['amount']=$data[ct_buy_price]  * -1;
		$content['subject']='꿀단지 나비 변환';

		$mb[mb_id]=$data[fmb_id];
		set_add_point('itemmat',$mb,$smb_id,$member['mb_id'],$content);						

	}
	*/
	
	//거래 완료의 경우
	if($data[tr_stats]=='3'){
	

		//매너 포인트 지급
		$paytime=strtotime($tdata[tr_paydate]);
		$trtime1=strtotime($tdata[tr_wdate]);
		$trtime2=strtotime('+1 days',strtotime(substr($tdata[tr_wdate],0,10)));

		if($paytime > $trtime1 && $paytime < $trtime2 && date('H',$paytime) < $g5['cn_bonus_hour1'] && $g5['cn_bonus_hour_r1']){
		
			$manner_point=$tdata[ct_sell_price]*$g5['cn_bonus_hour_r1'];
			
		}
		else if($g5['cn_bonus_hour_r2']) $manner_point=$tdata[ct_sell_price]*$g5['cn_bonus_hour_r2'];
		else $manner_point=0;

		
		$rpoint=get_mempoint($tdata['mb_id'],$tdata['smb_id']);
		$manner_point=min($rpoint['b']['_enable'],floor($manner_point*10)/10 ) ;
		
		
		
		//매너 포인트 지급
		if($manner_point > 0){
			
			echo "$cnt. $data[tr_code] /   $data[mb_id] / $data[smb_id] / {$rpoint['b']['_enable']} / $manner_point <br>"; 						
			
			$mb[mb_id]=$tdata[mb_id];			
			//판매자 꿀단지 지출			
			$content['link_no']=$tdata[tr_code];					
			$content['pt_wallet']='free'; //지갑구
			$content['pt_coin']='b'; //코인구분
			$content['amount']=$manner_point  * -1;
			$content['subject']='꿀단지 매너포인트 변환';

			set_add_point('mtransout',$mb,$tdata[smb_id],$member['mb_id'],$content);						

			//판매자 매너 포인트 지급			
			$content['link_no']=$tdata[tr_code];					
			$content['pt_wallet']='free'; //지갑구
			$content['pt_coin']='e'; //코인구분
			$content['amount']=$manner_point;
			$content['subject']='꿀단지 매너포인트 변환';

			
			set_add_point('mtransin',$mb,'',$member['mb_id'],$content);						
		}

	}
	
	//set_update_point($data[mb_id],$data[smb_id]);
	//set_update_point($data[fmb_id],$data[fsmb_id]);

}
	