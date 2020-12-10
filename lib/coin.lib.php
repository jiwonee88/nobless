<?
//지급 설정 정보
function get_coinset(){
	global $g5;	
	$data=sql_fetch("select * from {$g5['cn_set']} ");	
	return $data;
}

//이메일주소로 회원검색
function get_emailmember($mb_email, $fields='*'){
	global $g5;
    
    $mb_email = preg_replace("/[^0-9a-zA-Z\.\-\@]+/i", "", $mb_email);

    static $cache = array();

    $key = md5($fields);

    if( $is_cache && isset($cache[$mb_code]) && isset($cache[$mb_code][$key]) ){
        return $cache[$mb_code][$key];
    }

    $data = sql_fetch(" select $fields from {$g5['member_table']} where mb_email = '$mb_email' ");

    return $data;
}

//추천인 코드로 회원 검
function get_rcdmember($mb_code, $fields='*'){
	global $g5;
    
    $mb_code = preg_replace("/[^0-9]+/i", "", $mb_code);

    static $cache = array();

    $key = md5($fields);

    if( $is_cache && isset($cache[$mb_code]) && isset($cache[$mb_code][$key]) ){
        return $cache[$mb_code][$key];
    }

    $data = sql_fetch(" select $fields from {$g5['member_table']} where mb_code = TRIM('$mb_code') or mb_scode = TRIM('$mb_code')");

    return $data;
}

//기본 서브 계정 추가
function set_basic_account($mb,$reset=0){
	global $g5;	
	
	if($mb[mb_id]=='') return false;
	
	$temp=sql_fetch("select * from  {$g5['cn_sub_account']} where mb_id='$mb[mb_id]' and ac_id='$mb[mb_id]'");
	
	
	if(!$temp[ac_id]){
		
		//기본 서브 계정 추가		
		$sql = " insert into {$g5['cn_sub_account']}
					set
					mb_id='$mb[mb_id]',
					ac_id='$mb[mb_id]',
					ac_active='$mb[mb_active]',
					ac_wdate=now()
					";			
		
		
		$result=sql_query($sql,1);
		
		if(!$reset) set_update_point($mb[mb_id],$mb[mb_id]);	
		
	} else if($reset){
	
		//기본 서브 계정 추가		
		$sql = " update  {$g5['cn_sub_account']}
					set					
					ac_point_i='0',
					ac_active='0',
					ac_wdate=now()
					where mb_id='$mb_id' and  ac_id='$mb_id'
					";			
		$result=sql_query($sql);
	}
	
	return $result;
}
	


//서브 계정 삭제
function del_subaccount($ac_id){

	global $g5;		

	
	$data=sql_fetch("select * from  {$g5['cn_sub_account']} where ac_id='$ac_id' ",1);
	
	if($data[ac_id]=='') return false;
	
	//보유 정보 이전
	sql_query("update {$g5['cn_item_cart']} set smb_id = mb_id  where smb_id='$ac_id' and is_soled!='1' ");
	
	//보유 정보 이전
	sql_query("update {$g5['cn_item_cart']} set fsmb_id = fmb_id  where fsmb_id='$ac_id'  and is_soled!='1' ");
	
	
	//거래 정보  이전
	sql_query("update {$g5['cn_item_trade']} set smb_id = mb_id  where smb_id='$ac_id' and tr_stats in('1','2')  ");
	
	//보유 정보 이전
	sql_query("update {$g5['cn_item_cart']} set fsmb_id = fmb_id  where fsmb_id='$ac_id'  and tr_stats in('1','2')   ");	
	
	//보유포인트 이전
	foreach($g5['cn_cointype'] as $k => $v){
			
		if($data['ac_point_'.$k] <= 0) continue;

		//메인 계정에 이전
		$content['link_no']=$ac_id;				
		$content['pt_wallet']='free'; //지갑구
		$content['pt_coin']=$k; //코인구분
		$content['amount']=$data['ac_point_'.$k];
		$content['subject']='서브 계정 포인트 이전';

		$mb[mb_id]=$data[mb_id];
		set_add_point('Inherit',$mb,$data[mb_id],$member['mb_id'],$content);						
	
	}	
	
	//포인트 삭제
	sql_query("delete from  {$g5['cn_pointsum']} where ac_id='$ac_id' ");
	
	
	//계정삭제
	sql_fetch("delete  from  {$g5['cn_sub_account']} where ac_id='$ac_id' ");
		
	return $result;
}
	
	
	
//서브 계정 정보
function get_submember($ac_id){
	global $g5;	
    
    $data = sql_fetch(" select * from {$g5['cn_sub_account']} where ac_id = '$ac_id' ");

    return $data;
}

//레퍼럴 코드 생성
function get_mbcode($len=6){
		$mb_code=strtoupper(get_randstr($len));
		$tmp=sql_fetch("select * from {$g5['member_table']} where mb_code='{$mb_code}'");
		$cnt=0;
		while($tmp['mb_code']){
			$mb_code=strtoupper(get_randstr($len));
			$tmp=sql_fetch("select * from  {$g5['member_table']} where mb_code='{$mb_code}'");		
			$cnt++;			
			if($cnt > 10){
			$mb_code='';
			break;
			}
		}		
	return $mb_code;
}


//서비스 블럭 알림
function service_block($type='json'){
	global $g5,$cset,$member,$is_admin;
	
	if($is_admin=='super') return;
	
	//백그라운드 실행시
	if($type=='json'){		
		
		if($member[mb_id]!=''){
			if($member[mb_level] < 5)  alert_json(false,"회원님은 서비스 승인을 받지 않은 회원입니다. 승인후 서비스 이용이 가능합니다");
			
			if($member[mb_trade_penalty] > 0 ) alert_json(false,"회원님은 매칭 거래 불량 회원으로 1회 이상 신고되어<br>서비스 이용이 불가능합니다. ");	
		}
		
		if($cset[service_block] =='1') alert_json(false,"지금은 매칭이 진행중이거나 시스템 점검중으로<br>잠시 서비스 이용이 정지 중입니다");
	}
}

// 랜덤문자열
function get_randstr($ln){
	$str="0123456789abcdefghijklmnopqrstuvwxyz";
	$rtn='';
	for($i=0;$i < $ln;$i++) $rtn.=$str[rand(0,35)]; 
	return $rtn;
}

function get_randnum($ln){
	$str="0123456789";
	$rtn='';
	for($i=0;$i < $ln;$i++) $rtn.=$str[rand(0,9)]; 
	return $rtn;
}

// 회원번호로 회원정보를 얻는다.
function get_member2($mb_no, $fields='*')
{
    global $g5;    
    $mb_no = preg_replace("/[^0-9]+/i", "", $mb_no);
    return sql_fetch(" select $fields from {$g5['member_table']} where mb_no = TRIM('$mb_no') ");
}

//구매내역 상태 변경
function set_participation($data,$status,$exec=0){
	global $g5,$cset;	
	
	//지급 수당 등록
	if($data['deposit_status']!='3' && $status=='3'){
		
		//지급 근거
		$content['link_no']=$data['pp_no'];		
					
		//지급 회원정보
		$smb=get_member($data['mb_id']);
		
		//후원인 수당 지급
		$fee_ary=get_fee($smb,$data[buy_amt]);
		
		//후원인 수당 지급 print_r($fee_ary);
		foreach($fee_ary as $mb_no=> $v){		
			$content['pt_wallet']='free'; //지갑구
			$content['pt_coin']=$data['coin']; //화폐구분
			$content['amount']=$v['amount'];			
			$content['usd']=$v['usd'];
			$content['ratio']=$v['ratio'];
			$content['step']=$v['step'];		
			$content['waddr_to']=$v['step'];
			$content['waddr_from']=$v['mb']['mb_wallet_addr_'.$data['coin']];	//수신지갑주소
			$content['waddr_to']=$cset['wallet_out_e'];		//출금지갑			
			$content['subject']='후원인 롤업';
			
			set_add_point('fee',$v['mb'],'',$data['mb_id'],$content);		
		}
		
		//추천인 수당 지급
		$fee_ary2=get_fee2($smb,$data[buy_amt]);
		
		//추천인 수당 지급
		foreach($fee_ary2 as $mb_no=> $v){		
			$content['pt_wallet']='free'; //지갑구
			$content['pt_coin']=$data['coin']; //화폐구분
			$content['amount']=$v['amount'];
			$content['usd']=$v['usd'];
			$content['ratio']=$v['ratio'];
			$content['step']=$v['step'];
			$content['waddr_from']=$v['mb']['mb_wallet_addr_'.$data['coin']];	//수신지갑주소
			$content['waddr_to']=$cset['wallet_out_e'];		//출금지갑			
			$content['subject']='추천인 롤업';
			
			set_add_point('fee2',$v['mb'],'',$data['mb_id'],$content);		
		}		
		
	}
	
	//지급 수수료 회수
	if($data['deposit_status']=='3' && $status!='3'){
		$smb=get_member($data['mb_id']);		
		set_del_point('fee','','',$data['mb_id'],$data['pp_no']);		
		set_del_point('fee2','','',$data['mb_id'],$data['pp_no']);		
	}
	
	//상태 수정인 경우
	if($exec) sql_query("update {$g5['cn_pp_table']} set deposit_status='$status'  where pp_no='{$data['pp_no']}' ");
	
	//레벨 변경
	set_levelupdate($data['mb_id']);
}

//삭제
function del_itemdata($code){
	global $g5,$cset;	
	sql_query("delete from {$g5['cn_item_cart']} where code ='$code' " );	
}

//아이템 구매 처리
function set_purchase_item($data,$status,$exec=0){
	global $g5,$cset;	
	
	$mb=get_member($data['mb_id']);
	$smb_id=$data['smb_id'];
	if($smb_id=='')$smb_id=$data['mb_id'];
	$logs='';
	$set='';
	
	$item_data=$g5['cn_item'][$data[cn_item]];


	if(!$mb && $status!='del'){
		return array(false,'회원정보가 없습니다');
	}
	
	//구매완료 처리
	if($data['it_stats']!='3' && $status=='3'){
				
		//상품 지급
		//지급코드
		$cart_code_str='';
		
		for($i=1;$i <= $data[it_item_qty];$i++){
		
			$code= get_itemcode();	
			
			//다음날 판
			//$days= $item_data[days] ? $item_data[days]:1;
			$days= 1;
			
			$validdate=date("Y-m-d",strtotime("+ {$days} days"));
			
			$ct_logs=date("Y-m-d H:i:s")." ".$i."/".$data[it_item_qty]."개 구매처리 완료\n";
		
			//예정가격
			//$sell_price=floor( ($item_data[price] + ($item_data[price]*$item_data[interest]/100)));
			//원가 판매
			$sell_price=$item_data[price];
			$item_data[interest]=0;
			
			$sql="insert into {$g5['cn_item_cart']}
			set 
			code='$code',
			cn_item='$data[cn_item]',
			mb_id='{$mb[mb_id]}',
			smb_id='$smb_id',
			fmb_id='',
			fsmb_id='',

			ct_buy_price='$item_data[price]',
			ct_sell_price='$sell_price',

			ct_class='1',
			ct_interest='$item_data[interest]',
			ct_days='$days',
			ct_validdate='$validdate',

			ct_wdate=now(),
			
			ct_logs='$ct_logs',
			
			ct_priority='100'
			";

			$item_result=sql_query($sql);

			if(!$item_result) return array(false,'지급처리 할 수 없습니다');
			
			$cart_code_str.=($cart_code_str?',':'').$code;			
		}		
		
		//지급 근거
		$content['link_no']=$data['it_no'];				

		//자유계정 지갑 입력
		$content['pt_wallet']='free'; //지갑구
		$content['pt_coin']=$data['it_set_token']; //코인으로 변환
		$content['amount']=$data['it_set_amt']*-1;		
		$content['subject']=$data[cn_item_name].' 구매처리';

		set_add_point('itembuy',$mb,'','',$content);		

		$logs=date("Y-m-d H:i:s")." ".$data[cn_item_name]." ".$data[it_item_qty] ."개 구매처리 완료\n";		

	}	
	
	//구매 취소
	if(($data['it_stats']=='3' && $status!='3') || $status=='del'){
		
		//삭제 가능 상품인지 검사
		$cart_code_arr=explode(",",trim($data['cart_code']));
		
		//echo "select sum(if(is_soled='1',1,0)) cnt_soled, sum(if(is_trade='1' or is_trade='2',1,0)) cnt_trade, count(*) cnt from {$g5['cn_item_cart']} where code in ('".implode("','",$cart_code_arr)."') ";
		$temp=sql_fetch("select sum(if(is_soled='1',1,0)) cnt_soled, sum(if(is_trade='1' or is_trade='2',1,0)) cnt_trade, count(*) cnt from {$g5['cn_item_cart']} where code in ('".implode("','",$cart_code_arr)."') ");
		
		//if($temp['cnt']==0) return array(false,'지급된 상품을 찾을수 없습니다');
		if($temp['cnt_soled'] > 0 ) return array(false,'이미 판매된 상품이 있습니다');
		if($temp['cnt_trade'] > 0 ) return array(false,'이미 거래중인 상품이 있습니다');
		
		//상품 삭제
		foreach($cart_code_arr as $cart_code) del_itemdata($cart_code);
		
		set_del_point('itembuy',$data[mb_id],'','',$data['it_no'],$data[in_set_date]);
		$logs=date("Y-m-d H:i:s")." 구매취소처리 완료\n";		
		
	}
	
	//거래내역 삭제
	if($status=='del'){
		
		//내역 삭제
		sql_query("delete from {$g5['cn_item_purchase']} where it_no='{$data['it_no']}' ",1);
		
		return;		
	}
		
	
	//상태 수정 처리 포함의 경우
	if($exec) {	
	
		if($it_stats=='1'){
			
			/*
			$in_balance=0;
			if( $data[in_wallet_addr]!=''){
				//현재 이더지갑 잔액
				if($data[in_token]=='e'){			
					$rtn=balance_coin_eth($data[in_wallet_addr]);
				}
				//현재 테더지갑 잔액
				if($data[in_token]=='u'){			
					$rtn=balance_coin_usdt($data[in_wallet_addr]);
				}

				if($rtn[0]) $in_balance=$rtn[1];
			
			}
			
			$sql_common .="
			,in_balance='$in_balance'
			,in_balance_last='$in_balance'
			";
			*/
		}

		if($status=='3'){
			$set .= "
			,cart_code='$cart_code_str'
			,it_set_date  = now()	
			";
		}else{
			$set .= "
			,it_set_date  = ''	
			";		
		}
		
		if($logs) $set.=",it_logs=concat(it_logs,'$logs') ";
		sql_query("update {$g5['cn_item_purchase']} set it_stats='$status' $set  where it_no='{$data['it_no']}' ",1);
	}
	
	return array(true,'처리되었습니다');
}

//상품지급 코드 생성
function get_itemcode(){
	global $g5,$cset;	

	$code=date('ymdhis').str_pad((int)(microtime()*100), 2, "0", STR_PAD_LEFT).strtoupper(get_randstr(16));
	
	$tmp=sql_fetch("select * from {$g5['cn_item_cart']} where code='{$code}'");
	$cnt=0;
	while($tmp['code']){
		$code=date('ymdhis').str_pad((int)(microtime()*100), 2, "0", STR_PAD_LEFT).strtoupper(get_randstr(16));
		$tmp=sql_fetch("select * from   {$g5['cn_item_cart']}  where code='{$code}'");		
		$cnt++;			
		if($cnt > 10){
		$code='';
		break;
		}
	}		
	return $code;
}


//거래 코드 생성
function get_tradecode(){
	global $g5,$cset;	

	$code=date('ymdhis').str_pad((int)(microtime()*100), 2, "0", STR_PAD_LEFT).strtoupper(get_randstr(16));
	
	$tmp=sql_fetch("select * from {$g5['cn_item_trade_test']} where code='{$code}' and tr_wdate >= date(now()) ");
	$cnt=0;
	while($tmp['code']){
		$code=date('ymdhis').str_pad((int)(microtime()*100), 2, "0", STR_PAD_LEFT).strtoupper(get_randstr(16));
		$tmp=sql_fetch("select * from   {$g5['cn_item_trade_test']}  where code='{$code}' and tr_wdate >= date(now()) ");		
		$cnt++;			
		if($cnt > 10){
		$code='';
		break;
		}
	}		
	return $code;
}

//재화구매처리
function set_purchase_coin($data,$status,$exec=0,$in_logs=''){
	global $g5,$cset;	
	
	$mb=get_member($data['mb_id']);
	$logs='';
	$set='';
	
	if(!$mb && $status!='del'){
		return array(false,'회원정보가 없습니다');
	}
	
	//입금완료 처리
	if($data['in_stats']!='3' && $status=='3'){
		
		//지급 근거
		$content['link_no']=$data['in_no'];				
		
		//자유계정 지갑 입력
		$content['pt_wallet']='free'; //지갑구
		$content['pt_coin']=$data['in_set_token']; //코인으로 변환
		$content['amount']=$data['in_set_amt'];		
		$content['subject']='구매처리';

		set_add_point('pin',$mb,'','',$content);		
	
		$logs=$in_logs?$in_logs:date("Y-m-d H:i:s")." ".number_format2($amt)."구매처리 완료\n";		
	}
	
	
	//입금내역 취소
	if($data['in_stats']=='3' && $status!='3'){
		set_del_point('pin',$data[mb_id],'','',$data['in_no'],$data[in_set_date]);
		$logs=$in_logs?$in_logs:date("Y-m-d H:i:s")."입금취소처리 완료\n";		
	}
	
	//입금 내역 삭제
	if($status=='del'){
		set_del_point('pin',$data[mb_id],'','',$data['in_no'],$data[in_set_date]);
		sql_query("delete from {$g5['cn_purchase_table']} where in_no='{$data['in_no']}' ",1);
		
		return;		
	}
	
	//상태 수정 처리 포함의 경우
	if($exec) {		
		if($in_stats=='1'){
			$in_balance=0;
			if( $data[in_wallet_addr]!=''){
				//현재 이더지갑 잔액
				if($data[in_token]=='e'){			
					$rtn=balance_coin_eth($data[in_wallet_addr]);
				}
				//현재 테더지갑 잔액
				if($data[in_token]=='u'){			
					$rtn=balance_coin_usdt($data[in_wallet_addr]);
				}

				if($rtn[0]) $in_balance=$rtn[1];
			
			}
			
			$sql_common .="
			,in_balance='$in_balance'
			,in_balance_last='$in_balance'
			";
		}

		if($status=='3'){
			$set .= "			
			,in_set_date  = now()	
			";
		}else{
			$set .= "
			,in_set_date  = ''	
			";
		
		}
		
		if($logs) $set.=",in_logs=concat(in_logs,'$logs') ";
		sql_query("update {$g5['cn_purchase_table']} set in_stats='$status' $set  where in_no='{$data['in_no']}' ",1);
	}
	
	return;
}



//입금 처리
function set_insert_coin($data,$amt,$status,$exec=0,$in_logs=''){
	global $g5,$cset;	
	
	$mb=get_member($data['mb_id']);
	$logs='';
	$set='';
	
	if(!$mb && $status!='del'){
		return array(false,'회원정보가 없습니다');
	}
	
	//입금완료 처리
	if($data['in_stats']!='3' && $status=='3'){
		
		//지급 근거
		$content['link_no']=$data['in_no'];				
		
		//자유계정 지갑 입력
		$content['pt_wallet']='free'; //지갑구
		$content['pt_coin']=$data['in_token']; //코인으로 변환
		$content['amount']=$amt;		
		$content['waddr_from']=$cset['wallet_out_e'];	//출금지갑
		$content['waddr_to']=$data['in_wallet_addr'];	//입금지갑			
		$content['subject']='입금처리';

		set_add_point('in',$mb,$data[smb_id],'',$content);		
	
		$logs=$in_logs?$in_logs:date("Y-m-d H:i:s")." ".number_format2($amt)."입금처리 완료\n";		
		
		//리워드 지급
		if($data['in_token']=='e' && $cset[deposite_reward_r]>0){
		$ramt=swap_coin($amt*$cset[deposite_reward_r]/100,$data['in_token'],$g5['cn_reward_coin'],$sise);
		$content['pt_wallet']='free'; //지갑구
		$content['pt_coin']=$g5['cn_reward_coin']; 
		$content['amount']=$ramt;		
		$content['subject']='구매리워드지급';

		set_add_point('inreward',$mb,'','',$content);	
		}
	}
	
	
	//입금내역 취소
	if($data['in_stats']=='3' && $status!='3'){
		set_del_point('in',$data[mb_id],'','',$data['in_no'],$data[in_set_date]);
		set_del_point('inreward',$data[mb_id],'',$data['in_no'],$data[in_set_date]);
		$logs=$in_logs?$in_logs:date("Y-m-d H:i:s")."입금취소처리 완료\n";		
	}
	
	//입금 내역 삭제
	if($status=='del'){
		set_del_point('in',$data[mb_id],'','',$data['in_no'],$data[in_set_date]);
		set_del_point('inreward',$data[mb_id],'',$data['in_no'],$data[in_set_date]);
		sql_query("delete from {$g5['cn_reserve_table']} where in_no='{$data['in_no']}' ",1);
		
		return;		
	}
	
	//상태 수정 처리 포함의 경우
	if($exec) {		
		
		
		if($in_stats=='1'){
			$in_balance=0;
			if( $data[in_wallet_addr]!=''){
				//현재 이더지갑 잔액
				if($data[in_token]=='e'){			
					$rtn=balance_coin_eth($data[in_wallet_addr]);
				}
				//현재 테더지갑 잔액
				if($data[in_token]=='u'){			
					$rtn=balance_coin_usdt($data[in_wallet_addr]);
				}

				if($rtn[0]) $in_balance=$rtn[1];
			
			}
			
			$sql_common .="
			,in_balance='$in_balance'
			,in_balance_last='$in_balance'
			";
		}

		if($status=='3'){
			$set .= "
			,in_set_token='{$data['in_token']}'
			,in_set_amt  = '$amt'
			,in_set_date  = now()	
			";
		}else{
			$set .= "
			,in_set_token=''	
			,in_set_amt  = ''
			,in_set_date  = ''	
			";
		
		}
		
		if($logs) $set.=",in_logs=concat(in_logs,'$logs') ";
		sql_query("update {$g5['cn_reserve_table']} set in_stats='$status' $set  where in_no='{$data['in_no']}' ",1);
	}
	
	return;
}


//출금 처리
function set_draw_coin($data,$status,$exec=0){
	global $g5,$cset;	
	
	$mb=get_member($data['mb_id']);

	$set='';
	if(!$mb && $status!='del') return array(false,'회원정보가 없습니다');
		
	//출금 처리
	if($data['dr_stats']!='3' && $status=='3'){
		
		//지급 근거
		$content['link_no']=$data['dr_no'];					
		$content['pt_wallet']='free'; //지갑구
		$content['pt_coin']=$data['dr_token']; //와우코인으로 변환
		$content['amount']=abs($data['dr_amt'])*-1;		
		$content['waddr_from']=$cset['wallet_out_'.$data['dr_token']];	//출금지갑
		$content['waddr_to']=$data['dr_wallet_addr'];	//입금지갑			
		$content['subject']='입금처리';

		set_add_point('out',$mb,'','',$content);
		set_del_point('outing',$data['mb_id'],'','',$data['dr_no'],$data[dr_mdate]);
	}	
	
	//출금처리 전
	if( $data['dr_stats']!='1'  && $data['dr_stats']!='2'  && ($status=='1' || $status=='2') ){

		$content['link_no']=$data['dr_no'];					
		$content['pt_wallet']='free'; //지갑구
		$content['pt_coin']=$data['dr_token']; //와우코인으로 변환
		$content['amount']=abs($data['dr_amt'])*-1;		
		$content['waddr_from']=$cset['wallet_out_'.$data['dr_token']];	//출금지갑
		$content['waddr_to']=$data['dr_wallet_addr'];	//입금지갑			
		$content['subject']='Withdrawing';	

		set_add_point('outing',$mb,'','',$content);
	}
	
	//출급처리 전
	if($data['dr_stats']=='3' && $status!='3'){
		set_del_point('out',$data[mb_id],'','',$data['dr_no'],$data[dr_mdate]);
	}
	
	//출금취소
	if($data['dr_stats']!='4' && $status=='4'){
		set_del_point('outing',$data[mb_id],'','',$data['dr_no'],$data[dr_mdate]);
	}
	
	//출금 내역 삭제
	if($status=='del'){
		set_del_point('outing',$data[mb_id],'','',$data['dr_no'],$data[dr_mdate]);
		set_del_point('out',$data[mb_id],'','',$data['dr_no'],$data[dr_mdate]);
		sql_query("delete from {$g5['cn_draw_table']} where dr_no='{$data['dr_no']}'");		
		return;		
	}
	
	//상태 수정 처리 포함의 경우
	if($exec) {	
			
		if($status=='3'){			
			
			$set .= "
			,dr_set_token='$data[dr_token]'
			,dr_set_amt  = '{$data['dr_amt']}'
			,dr_set_fee  = '{$data['dr_fee']}'
			,dr_set_tamt  = '{$data['dr_tamt']}'
			,dr_set_date  = now()	
			";
		}else{
			$set .= "
			,dr_set_token='$data[dr_token]'	
			,dr_set_amt  = '{$data['dr_amt']}'
			,dr_set_fee  = '{$data['dr_fee']}'
			,dr_set_tamt  = '{$data['dr_tamt']}'
			,dr_set_date  = ''	
			";
		
		}

		sql_query("update {$g5['cn_draw_table']} set dr_stats='$status',dr_mdate=now() $set  where dr_no='{$data['dr_no']}' ",1);
	}
	
	return;
}


//스왑처리
function set_swap_coin($data,$status,$exec=0){
	global $g5,$cset,$sise;	
	
	$mb=get_member($data['mb_id']);

	$set='';
	if(!$mb && $status!='del') return array(false,'회원정보가 없습니다');
		
	//출금 처리
	if($data['sw_stats']!='3' && $status=='3'){
				
		//스왑액 계산
		$sw_set_amt=swap_coin($data['sw_tamt'],$data[sw_token],$data[sw_set_token],$sise);	
		
		//입금처리
		$content['link_no']=$data['sw_no'];					
		$content['pt_wallet']='free'; //지갑구
		$content['pt_coin']=$data['sw_set_token']; //와우코인으로 변환
		$content['amount']=abs($sw_set_amt);		
		$content['subject']='swap in';

		set_add_point('swap_in',$mb,'','',$content);
		
		
		//출금처리
		$content['link_no']=$data['sw_no'];					
		$content['pt_wallet']='free'; //지갑구
		$content['pt_coin']=$data['sw_token']; //와우코인으로 변환
		$content['amount']=abs($data['sw_amt'])*-1;		
		$content['subject']='swap out';
		
		set_add_point('swap_out',$mb,'','',$content);		
	}
			
	//출급처리 전
	if($data['dr_stats']=='3' && $status!='3'){
		set_del_point('swap_out',$data[mb_id],'','',$data['sw_no']);
		set_del_point('swap_in',$data[mb_id],'','',$data['sw_no']);
	}
	
	
	//출금 내역 삭제
	if($status=='del'){
		set_del_point('swap_out',$data[mb_id],'','',$data['sw_no']);
		set_del_point('swap_in',$data[mb_id],'','',$data['sw_no']);
		sql_query("delete from {$g5['cn_swap_table']} where sw_no='{$data['sw_no']}'");		
		return;		
	}
	
	//상태 수정 처리 포함의 경우
	if($exec) {	
			
		if($data['sw_stats']!='3' && $status=='3'){
		
			$set .= "
			,sw_set_amt  = '$sw_set_amt'
			,sw_set_date  = now()	
			";
		}else{
			$set .= "
			,sw_set_amt  = '0'
			,sw_set_date  = ''	
			";
		
		}

		sql_query("update {$g5['cn_swap_table']} set sw_stats='$status',sw_mdate=now() $set  where sw_no='{$data['sw_no']}' ",1);
	}
	
	return;
}

//전송가능금액
function get_eanble_trans($mb_id,$rpoint,$tr_token){
	global $g5,$cset;
	
	if($mb_id=='') return 0;
	//회원 전체 포인트 정보
	$rpoint=get_mempoint($mb_id,$mb_id);

	//전송 가능한 총금액
	$sum=$rpoint[$tr_token]['_enable']*1;	
	
	//보너스 제외 루틴 수수료+이체+잔
	$joinb=$rpoint[$tr_token]['joinb']?$rpoint[$tr_token]['joinb']:0;
	$mfee =$rpoint[$tr_token]['mfee']?$rpoint[$tr_token]['mfee']:0;
	$mfee2=$rpoint[$tr_token]['mfee2']?$rpoint[$tr_token]['mfee2']:0;
	
	//서브 계정 보유 골드
	$subsum=sql_fetch("select sum(ac_point_".$tr_token.") as amt from  {$g5['cn_sub_account']} where mb_id='{$mb_id}' and ac_id!='{$mb_id}' ",1);
	
	//전송 가능 금액
	return $sum - max(0, ($joinb - (abs($mfee) + abs($mfee2) + $subsum[amt])));
	
}

//전송 처리
function set_transfer_coin($data,$status,$exec=0){
	global $g5,$cset;	
	
	$mb=get_submember($data['mb_id']);
	$tmb=get_submember($data['tmb_id']);
	
	if($data['stmb_id']){
		$stmb=get_submember($data['stmb_id']);
		if(!$stmb ) return array(false,'전송받을 회원을 찾을수 없습니다');
	}

	$set='';
	if(!$mb) return array(false,'회원정보가 없습니다');
	if(!$tmb) return array(false,'전송받을 회원을 찾을수 없습니다');
		
	//전송 처리
	if($data['tr_stats']!='3' && $status=='3'){
		//출금 처리
		$content['link_no']=$data['tr_no'];					
		$content['pt_wallet']='free'; //지갑구
		$content['pt_coin']=$data['tr_token']; 
		$content['amount']=abs($data[tr_amt]) * -1;		
		$content['subject']='Transfer';
		set_add_point('transout',$mb,$data['mb_id'],'',$content);		
						
		//입금처리
		$content['amount']=abs($data[tr_tamt]);		
		set_add_point('transin',$tmb,$data['stmb_id'],$data['mb_id'],$content);
		
		if(abs($data[tr_fee]) !=0 ){
			//출금 수수료 처리
			$content['link_no']=$data['tr_no'];					
			$content['pt_wallet']='free'; //지갑구분
			$content['pt_coin']='i'; 
			$content['amount']=abs($data[tr_fee]) * -1;		
			$content['subject']='Transfer Fee';
			set_add_point('transfee',$mb,$data['tmb_id'],'','',$content);		
		}
		
		//전송중 삭제
		set_del_point('sending',$mb['mb_id'],'','',$data['tr_no']);
	}
	
	
	//전송처리전
	if( $data['tr_stats']!='1'  && $data['tr_stats']!='2'  && ($status=='1' || $status=='2') ){
		
		$content['link_no']=$data['tr_no'];					
		$content['pt_wallet']='free'; //지갑구
		$content['pt_coin']=$data['tr_token']; 
		$content['amount']=abs($data['tr_amt'])*-1;		
		$content['subject']='Sending';	
		set_add_point('sending',$mb,$data['tmb_id'],$content);
	}
	//출급처리 전
	if($data['tr_stats']=='3' && $status!='3'){
		set_del_point('transout',$data[mb_id],'','',$data['tr_no']);
		set_del_point('transfee',$data[mb_id],'','',$data['tr_no']);
		set_del_point('transin',$data[tmb_id],'','',$data['tr_no']);
		
	}
	
	//출금취소
	if($data['tr_stats']!='4' && $status=='4'){
		set_del_point('sending',$data[mb_id],'','',$data['tr_no']);
	}
	
	//출금 내역 삭제
	if($status=='del'){
		set_del_point('sending',$data[mb_id],'','',$data['tr_no']);
		set_del_point('transout',$data[mb_id],'','',$data['tr_no']);
		set_del_point('transfee',$data[mb_id],'','',$data['tr_no']);
		set_del_point('transin',$data[tmb_id],'','',$data['tr_no']);
		sql_query("delete from {$g5['cn_transfer_table']} where tr_no='{$data['tr_no']}'");		
		return;		
	}
	
	//상태 수정 처리 포함의 경우
	if($exec) {	
			
		if($status=='3'){
			
			$set .= "
			,tr_set_token='{$data['tr_token']}'
			,tr_set_amt  = '{$data['tr_amt']}'
			,tr_set_fee  = '{$data['tr_fee']}'
			,tr_set_tamt  = '{$data['tr_tamt']}'
			,tr_set_date  = now()	
			";
		}else{
			$set .= "
			,tr_set_token='{$data['tr_token']}'
			,tr_set_amt  = '{$data['tr_amt']}'
			,tr_set_fee  = '{$data['tr_fee']}'
			,tr_set_tamt  = '{$data['tr_tamt']}'
			,tr_set_date  = ''	
			";		
		}
		sql_query("update {$g5['cn_transfer_table']} set tr_stats='$status',tr_mdate=now() $set  where tr_no='{$data['tr_no']}' ",1);
	}
	
	return;
}

//거래 상태변경
function set_trade_stat($tdata,$stats){

	global $g5,$member;		
	
	if(!is_array($tdata)) $tdata=sql_fetch("select * from {$g5['cn_item_trade']} where tr_code='$tdata'  ");
	
	//상태 변경 로그
	$tr_logs=addslashes(" / ".date("Y-m-d H:i")." ".($g5['tr_stat'][$stats])." 상태로 변경  by ".$member[mb_id]);
	
	//상품기본 정보
	$info=$g5[cn_item][$tdata[cn_item]];
	
	//거래 완료 처리
	if($stats=='3'){
		
		//p2p거래
		if($tdata[cart_code]){

			$cdata=sql_fetch("select * from {$g5['cn_item_cart']} where code='$tdata[cart_code]'  ");
			//if(!$cdata ) return array(false,$g4[cn_item_name]."정보가 없습니다");	

			if($tdata[tr_stats]=='3' ) return array(false,"이미 종료된 거래");	

			$code= get_itemcode();	

			$ct_sell_price=$tdata[tr_price_org] + ($tdata[tr_price_org]*$info[interest])/100;
			
			$ct_sell_price=floor($ct_sell_price*10)/10;
			
			$days=$info[days];

			$ct_validdate=date("Y-m-d",strtotime("+ $days days",strtotime($tdata[tr_wdate])));

			//분할 판매였다면 초기클래스로
			if($cdata[div_cnt] > 1 ) $ct_class=1;
			else $ct_class=$cdata[ct_class]+1;
			
			
			//구매자 상품 지급
			$sql="insert into {$g5['cn_item_cart']}
				set 
				code='$code',
				tr_code='$tdata[tr_code]',
				cn_item='$tdata[cn_item]',
				mb_id='".addslashes($tdata[mb_id])."',
				smb_id='".addslashes($tdata[smb_id])."',
				fmb_id='".addslashes($tdata[fmb_id])."',
				fsmb_id='".addslashes($tdata[fsmb_id])."',

				ct_buy_price='$tdata[tr_price_org]',
				ct_sell_price='$ct_sell_price',

				ct_class='$ct_class',		
				ct_interest='$info[interest]',
				ct_days='$days',
				ct_validdate='$ct_validdate',

				ct_wdate='$tdata[tr_wdate]'

				";				
				
			//echo $sql;
			$result= sql_query($sql);	
			if(! $result ) return array(false,"완료할수 없습니다(1)");	
			
			
			 //취소상품 완료시
			 if($tdata['tr_stats']=='9'){
			  	
					$update_set="
					trade_cnt=trade_cnt+1,
					trade_amt=trade_amt + {$tdata['tr_price']},					
					is_trade=1,
					";
			 }else $update_set='';
			 
			 
			//판매된 상품	 		 
			$sql = " update {$g5['cn_item_cart']}
						set 		
						$update_set						
						soled_cnt=soled_cnt + 1 ,
						soled_amt=soled_amt + $tdata[tr_price],
						is_soled= if(cn_item!='e' and soled_cnt >= div_cnt and soled_cnt > 0,1,0) ,						
						soled_date=if(cn_item!='e' and is_soled='1',now(),'')

						where code='$tdata[cart_code]' ";

			//echo $sql."<br>";
			$result= sql_query($sql);	
			if(!$result) return array(false,"처리할수 없습니다");	
									
			
						

		//회사 물량 매칭인 경우
		}else{

			$code= get_itemcode();	

			$ct_class=1;
			$ct_sell_price=$tdata[tr_price_org] + ($tdata[tr_price_org]*$info[interest])/100;

			$ct_sell_price=floor($ct_sell_price*10)/10;

			$days=$info[days];
			$ct_validdate=date("Y-m-d",strtotime("+ $days days",strtotime($tdata[tr_wdate])));	

			//상품 지급
			$sql="insert into {$g5['cn_item_cart']}
				set 
				code='$code',
				tr_code='$tdata[tr_code]',
				cn_item='$tdata[cn_item]',
				mb_id='".addslashes($tdata[mb_id])."',
				smb_id='".addslashes($tdata[smb_id])."',
				fmb_id='".addslashes($tdata[fmb_id])."',
				fsmb_id='".addslashes($tdata[fsmb_id])."',

				ct_buy_price='$tdata[tr_price_org]',
				ct_sell_price='$ct_sell_price',

				ct_class='1',		
				ct_interest='$info[interest]',
				ct_days='$days',
				ct_validdate='$ct_validdate',

				ct_wdate='$tdata[tr_wdate]'

				";

			//echo $sql."<br>";
			$result= sql_query($sql);	
			if(! $result ) alert_json(false,"완료할수 없습니다(2)");	
						

		}
		
		//거래 테이블 업데이트						
		$sql = " update {$g5['cn_item_trade']}
					set 		
					to_cart_code='$code',
					tr_stats='3',				
					tr_setdate=now(),
					tr_logs=concat(tr_logs,'$tr_logs')

					where tr_code='$tdata[tr_code]'";

			 //echo $sql;				
		$result= sql_query($sql);	
		if(! $result ) return array(false,"완료할수 없습니다(3)");	
						
		/*
		//입금처리 즉시 지급으로 변
		//매너 포인트 지급		
		$paytime=strtotime($tdata[tr_paydate]);
		$trtime1=strtotime($tdata[tr_wdate]);
		$trtime2=strtotime('+1 days',strtotime(substr($tdata[tr_wdate],0,10)));

		if($paytime >= $trtime1 && $paytime < $trtime2 && date('H',$paytime) < $g5['cn_bonus_hour1'] && $g5['cn_bonus_hour_r1']) $manner_point=$tdata[ct_sell_price]*$g5['cn_bonus_hour_r1'];		
		else if($paytime >= $trtime1 && $paytime < $trtime2 && date('H',$paytime) < $g5['cn_bonus_hour9'] && $g5['cn_bonus_hour_r2'] ) $manner_point=$tdata[ct_sell_price]*$g5['cn_bonus_hour_r2'];
		else $manner_point=0;
		
		//매너 포인트 지급
		if($manner_point > 0){
		
			$rpoint=get_mempoint($tdata['mb_id'],$tdata['smb_id']);
			$manner_point=min($rpoint['b']['_enable'],floor($manner_point*10)/10 ) ;
			
			if($manner_point > 0){

				$mb[mb_id]=$tdata[mb_id];			
				
				//구매자 꿀단지 지출			
				$content['link_no']=$tdata[tr_code];					
				$content['pt_wallet']='free'; //지갑구
				$content['pt_coin']='b'; //코인구분
				$content['amount']=$manner_point  * -1;
				$content['subject']='꿀단지 매너포인트 변환';

				set_add_point('mtransout',$mb,'',$member['mb_id'],$content);						

				//구매자 매너 포인트 지급			
				$content['link_no']=$tdata[tr_code];					
				$content['pt_wallet']='free'; //지갑구
				$content['pt_coin']='e'; //코인구분
				$content['amount']=$manner_point;
				$content['subject']='꿀단지 매너포인트 변환';

				set_add_point('mtransin',$mb,'',$member['mb_id'],$content);						
			}
		}
		*/
		
		 return array(true,'ok');
	}
	
	$cart_update_set="";
	
	//거래의 완료->비완료
	if( $stats!='3' && $tdata['tr_stats']=='3'  ){
		
		//지급된 상품 회수
		if($tdata['to_cart_code'] ){				 
			 $sql = "delete from {$g5['cn_item_cart']}	where code='$tdata[to_cart_code]'";
			$result= sql_query($sql);	
		}		
		
		$cart_update_set.="
					is_soled='0',	
					soled_cnt= if(soled_cnt >= 1,soled_cnt -1,0) ,
					soled_amt = if(soled_amt >= {$tdata['tr_price']},soled_amt - {$tdata['tr_price']} , 0 ) ,
					";
					
		//변환된 매너 포인트 회수
		//set_del_point('mtransout',$tdata['mb_id'],'','',$tdata['tr_code'],$tdata[tr_rdate]);			
		//set_del_point('mtransin',$tdata['mb_id'],'','',$tdata['tr_code'],$tdata[tr_rdate]);			
					
	}
  	
	//거래의 취소
	if(($stats=='9' || $stats=='del')  && $tdata['tr_stats']!='9'){
						
		$cart_update_set.="									
				trade_cnt=if(trade_cnt >= 1,trade_cnt-1,0),				
				trade_amt=if(trade_amt >=  {$tdata['tr_price']},trade_amt- {$tdata['tr_price']} , 0),				
				is_trade = if(cn_item!='e' and trade_cnt >= 1 and div_cnt > 1,2,if(cn_item!='e' and trade_cnt >= 1,1,0)),
				";
		
		
		//수수료 회수 - 구매
		set_del_point('mfee',$tdata['mb_id'],'','',$tdata['tr_code'],$tdata[tr_rdate]) ;
		//수수료 회수 - 판매
		set_del_point('mfee2',$tdata['fmb_id'],'','',$tdata['tr_code'],$tdata[tr_rdate]);	
		
		
		//꿀단지 회수 - 판매
		if($tdata[tr_src]=='honey') set_del_point('itemmat',$tdata['fmb_id'],'','',$tdata['tr_code'],$tdata[tr_rdate]);			
		
		//변환된 매너 포인트 회수
		set_del_point('mtransout',$tdata['mb_id'],'','',$tdata['tr_code'],$tdata[tr_rdate]);			
		set_del_point('mtransin',$tdata['mb_id'],'','',$tdata['tr_code'],$tdata[tr_rdate]);			
				
	}
	
	
	//취소 -> 미취소의 경우 수수료 재 청구
	if($stats!='9' && $tdata['tr_stats']=='9'){
			
			$cart_update_set.="	
					trade_cnt=trade_cnt+1,	
					trade_amt=trade_amt+{$tdata['tr_price']},				
					is_trade=if(cn_item!='e' and trade_cnt >= 1 and div_cnt > 1,2,if(cn_item!='e' and  trade_cnt >= 1,1,0)),
					";


			//판매자 수수료
			if($tdata[tr_seller_fee] > 0 ){
				
				$content['link_no']=$tdata[tr_code];				
				$content['pt_wallet']='free'; //지갑구
				$content['pt_coin']=$g5[cn_fee_coin]; //코인구분
				$content['amount']=$tdata[tr_seller_fee] * -1;
				$content['subject']='매칭수수료';
				
				$mb[mb_id]=$tdata[fmb_id];
				set_add_point('mfee2',$mb,$tdata[fsmb_id],$member['mb_id'],$content);						
			}
			
			//구매자 수수료
			if($tdata[tr_fee] > 0 ){				
				
				$content['link_no']=$tdata[tr_code];		
				$content['pt_wallet']='free'; //지갑구
				$content['pt_coin']=$g5[cn_fee_coin]; //코인구분
				$content['amount']=$tdata[tr_fee] * -1;
				$content['subject']='매칭수수료';
				
				$mb[mb_id]=$tdata[mb_id];
				set_add_point('mfee',$mb,$tdata[smb_id],$member['mb_id'],$content);
			}	
			
			//꿀딴지 유래 상품의 경우 
			if($tdata[tr_src]=='honey'){

				//판매자 꿀단지 지출			
				$content['link_no']=$tdata[tr_code];					
				$content['pt_wallet']='free'; //지갑구
				$content['pt_coin']='b'; //코인구분
				$content['amount']=$tdata[ct_buy_price]  * -1;
				$content['subject']='꿀단지 나비 변환';

				$mb[mb_id]=$tdata[fmb_id];
				set_add_point('itemmat',$mb,$tdata[fsmb_id],$member['mb_id'],$content);						


			}

	}
	
		
	//거래중 상품 / 팔린 상품 초기화
	if($tdata[cart_code] ){				

		  $sql = "update {$g5['cn_item_cart']}
				set 		
				$cart_update_set					
				ct_logs=concat(ct_logs,'$tr_logs')
				where code='$tdata[cart_code]' ";

		$result= sql_query($sql);	
	}
		


	//거래 정보 삭제
	if($stats=='del') {
		sql_query("delete from {$g5['cn_item_trade']} where tr_code='$tdata[tr_code]'");
	}else{
	
		//거래 테이블 업데이트						
		$sql = " update {$g5['cn_item_trade']}
					set 							
					tr_stats='$stats',				
					tr_setdate=now(),
					tr_logs=concat(tr_logs,'$tr_logs')

					where tr_code='$tdata[tr_code]' ";

		//echo $sql;				
		$result= sql_query($sql);	
	
	}
	
	
	if(! $result ) return array(false,"완료할수 없습니다(3)");	

	 return array(true,'ok');	
   
  
}

//거래 패널치 처리
function set_trade_penalty($data,$target_pt,$stats_pt,$give_coin,$give_coin_amt,$get_coin,$get_coin_amt){
	global $g5,$cset;
	
	
	if(!is_array($data) || $data[tr_code]=='') $data=sql_fetch("select * from  {$g5['cn_item_trade']} where tr_code='$data'");
	
	if($data[tr_code]=='') return array(false,'거래내역을 찾을수 없습니다');
	
	if($data[tr_penalty]=='1') return array(false,'이미 패널티 처리된 거래내역 입니다');	
	
		
	//구매자에게 패널티
	if($target_pt=='buyer'){
		
		//패널티는 1일 1회
		sql_query("update {$g5[member_table]} set mb_trade_penalty=mb_trade_penalty+1, mb_trade_penalty_date=now() where mb_id='$data[mb_id]' and ( mb_trade_penalty= 0 or mb_trade_penalty_date < date(now()) )");
		
		//echo only_number($get_coin_amt)*1 ;
		
		//차감
		if($get_coin && only_number($get_coin_amt)*1 > 0){
		
			//벌금 부과					
			$content['link_no']=$data[tr_code];
			$content['pt_wallet']='free'; //지갑구
			$content['pt_coin']=$get_coin; //화폐구분
			$content['amount']=only_number($get_coin_amt) * -1;			
			$content['subject']='패널티 벌금';

			set_add_point('pnfine',$data[mb_id],'',$member[mb_id],$content);		
			
		}	
		
		//판매자에게 보
		if($give_coin && only_number($give_coin_amt)*1 > 0){
		
			//피해보상금
			$content['link_no']=$data[tr_code];
			$content['pt_wallet']='free'; //지갑구
			$content['pt_coin']=$give_coin; //화폐구분
			$content['amount']=only_number($give_coin_amt) * 1;			
			$content['subject']='패널티 보상금';

			set_add_point('pnreward',$data[fmb_id],'',$member[mb_id],$content);		
			
		}	
		
	
	}
	
	
	//판매자에게 패널티
	if($target_pt=='seller'){
		
		//패널티는 1일 1회
		sql_query("update {$g5[member_table]} set mb_trade_penalty=mb_trade_penalty+1, mb_trade_penalty_date=now() where mb_id='$data[fmb_id]' ( mb_trade_penalty= 0 or mb_trade_penalty_date < date(now()) ) ");
		
		
		//판매자에게 벌금
		if($get_coin!='' && only_number($get_coin_amt)*1 > 0){
		
			//벌금 부과					
			$content['link_no']=$data[tr_code];
			$content['pt_wallet']='free'; //지갑구
			$content['pt_coin']=$get_coin; //화폐구분
			$content['amount']=only_number($get_coin_amt) * -1;			
			$content['subject']='패널티 벌금';

			set_add_point('pnfine',$data[fmb_id],'',$member[mb_id],$content);		
			
		}	
		
		//구매자에게 보상금
		if($give_coin!='' && only_number($give_coin_amt)*1 > 0){
		
			//피해보상금
			$content['link_no']=$data[tr_code];
			$content['pt_wallet']='free'; //지갑구
			$content['pt_coin']=$give_coin; //화폐구분
			$content['amount']=only_number($give_coin_amt) * 1;			
			$content['subject']='패널티 보상금';

			set_add_point('pnreward',$data[mb_id],'',$member[mb_id],$content);					
		}			
	}
	
	sql_query("update {$g5['cn_item_trade']}  set tr_penalty='1', tr_penalty_date=now() where tr_code='$data[tr_code]' ",1);
	//echo "update {$g5['cn_item_trade']}  set tr_penalty='1', tr_penalty_date=now() where tr_code='$data[tr_code]' ";
	
	//해당 거래의 취소
	if($stats_pt=='cancel'){	
		set_trade_stat($data,9);	
	}	
	
	
}


//수당 이전 적용
function set_pointtrans($data,$mode='add'){
	global $g5;
	
	//지급 수당 등록
	if($mode=='add'){				
		$mb=get_member($data['mb_id']);
		$smb=get_member($data['smb_id']);
		
		$content['link_no']=$data['pt_no'];		
		$content['amount']=$data['amount'];
		$content['usd']=$data['amount']*$g5['cn_point_usd'];		
		
		//기타수당으로 이전
		if($data['pt_kind']=='etc') set_add_point('in',$mb,'',$data['smb_id'],$content);		
		else if($data['pt_kind']=='ptc'){
			
			$data['coin']=addslashes($data['coin']);
			$data['account']=addslashes($data['account']);
			 
			$sql = "insert into {$g5['cn_pp_table']} set
							mb_id     = '{$data['mb_id']}',
							coin          = '{$data['coin']}',
							buy_amount          = '{$data['amount']}',
							deposit_amount       = '0',
							deposited_amount       = '0',
							account          = '{$data['account']}',
							
							deposit_status          = '3',
							deposit_date          = '{$data['deposit_date']}',
							
							trans_no	 = '{$data['pt_no']}',
							comment          = '',
							
							reg_date= '{$data['deposit_date']}',
							wdate = now()
							";							
								
			$result=sql_query($sql);
			
			//지급된 수당도 수수료를 줄 경우는 
			/*	
			if($result){
				$pp_no=sql_insert_id();
				
				$data= get_participation($pp_no);
				$data['deposit_status']=1;
				set_participation($data,3);
			}
			*/			
		}
		
		//지출은 항상 적용	
		$content['amount']=$data['amount']*-1;
		$content['usd']=$data['amount']*$g5['cn_point_usd']*-1;		
			
		set_add_point('out',$smb,'',$data['mb_id'],$content);		
		
	}
	
	//지급 수당 회수
	if($mode=='delete'){
		
		//지급포인트회수
		if($data['pt_kind']=='etc') set_del_point('in',$data['mb_id'],'','',$data['pt_no']);		
		//구매내역 회수
		else if($data['pt_kind']=='ptd') {
			sql_query("delete from {$g5['cn_pp_table']} where trans_no='{$data['ptc_no']}'");
		}
		
		set_del_point('out',$data['smb_id'],'','',$data['pt_no']);		
	}
}


//단계별 추천인 수당 지급 
function get_fee($smb,$amt){	
	global $g5,$cset;
	
	$rtn=array();
	
	if($smb['mb_tree']=='') return $rtn;
	
	$ary=explode(",",$smb['mb_tree']);
	$rary=array_reverse($ary);
	
	$step=0;
	foreach($rary as $v){
		$step++	;		
		
		//수당 수급자 등급 및 수당 비율
		$mb=get_member($v);
		
		//스테이킹 여부
		$temp=sql_fetch("select * from {$g5['cn_pointsum']} where   pkind='stake' and mb_id='{$mb['mb_id']}' and amount!='0' ");
		if(!$temp['pt_no']) continue;
		
		$ratio=0;
		for($i=5;$i > 0;$i--){
			$_step=$cset['sp_rup_bs'.$i.'_step'];
			if(!$_step) continue;
			if($step >= $_step){
				$ratio=$cset['sp_rup_bs'.$i.'_r']/100;			
				break;
			}
		}		
		
		if(!$ratio) continue;	

		$rtn[$v]['ratio']=$ratio;	//수당비율
		$rtn[$v]['amount']=$amt * $ratio;	//지급되는 수당
		$rtn[$v]['usd']=0;	//달러환산
		$rtn[$v]['step']=$step;		//단계
		$rtn[$v]['mb']=$mb;		//수급자 정보
	}	
	return $rtn;
}


//회원 등급 조정
function set_levelupdate($mb){
	global $g5;
	
	return;
	if(!is_array($mb)) $mb=get_member($mb);
	
	//4등급 이상은 조정없음
	if($mb['mb_grade'] >= 4) return;
	
	//수량 150개 이상 구매시만 등업 조건
	$pcount_ok=false;
	if($mb['mb_grade'] <  1){
		$pcount=sql_fetch("select sum(buy_amount) cnt from {$g5['cn_pp_table']} where mb_id='{$mb['mb_id']}' and deposit_status='3'  ");		
		if($pcount['cnt'] > $g5['cn_level_min']) $pcount_ok=true;		
	}else{
		$pcount_ok=true;
	}
	//직접 추천인수
	$count=sql_fetch("select count(*) cnt  from {$g5['member_table']} where mb_recommend='{$mb['mb_id']}' ");
	
	if($count['cnt'] >= 3 && $pcount_ok) $new_mb_grade=3;
	else if($pcount_ok) $new_mb_grade=max(1,$count['cnt']);
	else $new_mb_grade=$mb['mb_grade'];
	
	//등급 변경
	sql_query("update {$g5['member_table']} set mb_grade = '$new_mb_grade' , mb_servant_cnt = '{$count['cnt']}' where mb_id = '{$mb['mb_id']}' ");

	return;
}

//회원삭제시 mb_tree 재생성 $mb:삭제된 회원정보
function del_mb_tree($mb,$fd){
	global $g5;
	
	if(!is_array($mb)) $mb=get_member($mb);
	
	//삭제회원 계보도 DB 삭제
	del_mb_treedb($mb['mb_id'],'',$fd); //삭제회원의 하위 구조
	del_mb_treedb('',$mb['mb_id'],$fd);	//삭제회원의 상위 구조
	
	//하위 회원모두 추출
	//echo "select *,find_in_set('{$mb['mb_id']}',mb_".$fd.") as pos from {$g5['member_table']} where find_in_set('{$mb['mb_id']}',mb_".$fd.") ";
	$re=sql_query("select *,find_in_set('{$mb['mb_id']}',mb_".$fd.") as pos from {$g5['member_table']} where find_in_set('{$mb['mb_id']}',mb_".$fd.") ",1);
	while($data=sql_fetch_array($re)){		
	
		$mb_tree='';
		$__mb_tree=implode(",",array_splice(explode(",",$data['mb_'.$fd]),$data['pos']));
		
		//회원 계보도 업데이트
		if($data['mb_'.$fd]!='') $_mb_tree=implode(",",array_splice(explode(",",$mb['mb_'.$fd]),0,$data['pos']-1));
		else $_mb_tree='';
		
		$arr=explode(",",$data['mb_'.$fd]);
		if($data['pos'] >=2) $new_recommend=$arr[$data['pos']-2];
		else $new_recommend='';

		$mb_tree=$_mb_tree;

		if($__mb_tree ) $mb_tree.= ($mb_tree?",":"").$__mb_tree;
		sql_query(" update {$g5['member_table']} set mb_".$fd." = '$mb_tree',  mb_recommend".($fd=='tree2'?'2':'')."='$new_recommend' where mb_id = '{$data['mb_id']}' ",1);
		//echo " update {$g5['member_table']} set mb_".$fd." = '$mb_tree',  mb_recommend".($fd=='tree2'?'2':'')."='$new_recommend' where mb_id = '{$data['mb_id']}' ";
		
		//하위회원 계보도 DB 업데이트
		update_mb_treedb($mb_tree,$data['mb_id'],$fd);
	}	
	
	return;
}

//회원 계보도 업데이트 - 지정회원의 상위
function update_mb_treedb($mb_tree,$smb_id,$fd){
	global $g5;
	
	$ary=explode(",",$mb_tree);
	$rary=array_reverse($ary);
	$step=1;
	
	//기존 계보도 삭제
	del_mb_treedb('',$smb_id,$fd);
	
	//계보도 입력
	foreach($rary as $v){	
	   
	   $mb=get_member($v);
		$sql="INSERT INTO ".$g5['cn_'.$fd]." set mb_no='{$mb['mb_no']}',mb_id='$v',smb_id='$smb_id',step='$step' ";		
		sql_query($sql);
		$step++;
		//echo $sql."<br>";
	}
}

//회원 계보도 삭제
function del_mb_treedb($mb_id,$smb_id,$fd){
	global $g5;
	$sql="";
	if(!$mb_id && !$smb_id) return;
	if($mb_id) $sql.=" and mb_id='$mb_id'";
	if($smb_id) $sql.=" and smb_id='$smb_id'";
	
	$sql="delete from ".$g5['cn_'.$fd]." WHERE 1=1 $sql";
	sql_query($sql);
	//echo $sql;
}


//회원 추천인 변경
function change_mb_treedb($mb,$mb_recommend){
	global $g5;
		
	//붙여넣을 새로운 트리
	$new_mb=get_member($mb_recommend);	
	if(!$new_mb[mb_id])  return '추천인을 찾을수 없습니다';
	
	//동일 추천인 지정시 
	if($mb[mb_recommend]==$mb_recommend) return '현재와 동일한 추천인 입니다';
	
	//동일 추천인 지정시 
	if($mb['mb_recommend']==$mb['mb_id']) return '본인이 추천인이 될수 없습니다1';
	
	
	//새추천인이 이미 하부인 경우 불가
	if(in_array($mb['mb_id'],explode(",",$new_mb['mb_tree']))) return '하부 추천인을 상위 추천인으로 변경할수 없습니다';
	
	$prefix=$new_mb['mb_tree'];
	$prefix.=($prefix?',':'').$mb_recommend;
	
	$mem_arr=array();
	
	$sql="update {$g5['member_table']} set mb_tree='$prefix',mb_recommend='$mb_recommend' where mb_id='{$mb['mb_id']}'";
	sql_query($sql);
	//echo $sql."<br>";
	
	$mem_arr[$mb['mb_id']]=$prefix;
	
	//하위 회원 목록	
	$re=sql_query("select * from {$g5['member_table']} where mb_id in (select smb_id from {$g5['cn_tree']} where mb_id='{$mb['mb_id']}') ");
	while($data=sql_fetch_array($re)){
		
		
		$tree_arr=explode(",",$data['mb_tree']);
		$pos=array_search($mb[mb_id],$tree_arr);
		if($pos==null || $pos==false) continue;
		
		$new_tree=$prefix.",".implode(",",array_slice($tree_arr,$pos));
		
		$mem_arr[$data['mb_id']]=$new_tree;
		
		$sql="update {$g5['member_table']} set mb_tree='$new_tree' where mb_id='{$data['mb_id']}'";
		//echo $sql."<br>";
		sql_query($sql);	
	}
	
	//트리디비 갱신
	foreach($mem_arr as $mb_id => $tree_str){
		 
		update_mb_treedb($tree_str,$mb_id,'tree');

	}
	
	return;

}



//회원 아이디 변경
function change_mb_id($fid,$tid){
	
	global $g5;
	
	$mb=get_member($fid);
	if(!$mb) return array(false,'회원정보를 찾을수 없습니다');
	
	//동일 아이디 검색
	$temp=get_member($tid);
	if($temp) return array(false,'이미 존재하는 아이디 입니다');
	
	
	//트리변경
	$sql="update  {$g5['cn_tree']} set mb_id='{$tid}' where  mb_id='{$fid}'";
	sql_query($sql,1);		
	//echo $sql."<br>";
	
	//트리변경
	$sql="update  {$g5['cn_tree']} set smb_id='{$tid}' where  smb_id='{$fid}'";
	sql_query($sql,1);		
	//echo $sql."<br>";
		
	//회원정보내 트리변경	
	$result=sql_query("select * from {$g5['member_table']}  where  mb_tree like '%{$fid}%' ");
	while($data=sql_fetch_array($result)){
		$new_tree='';
		$ary=explode(",",$data[mb_tree]);
		foreach($ary as $v){
			if($v==$fid) $new_tree.=($new_tree?',':'').$tid;
			else $new_tree.=($new_tree?',':'').$v;		
		}
		
		//변경
		$sql="update {$g5['member_table']} set mb_tree='{$new_tree}' where mb_no='{$data[mb_no]}' ";		
		sql_query($sql,1);		
		//echo $sql."<br>";
	}	
	//서브아이디 변경
	$sql="update {$g5['cn_sub_account'] } set mb_id='{$tid}' where mb_id='{$fid}' ";		
	sql_query($sql,1);	

	$sql="update {$g5['cn_reserve_table']} set mb_id='{$tid}' where mb_id='{$fid}' ";		
	sql_query($sql,1);
	//echo $sql."<br>";
	
	$sql="update {$g5['cn_draw_table']} set mb_id='{$tid}' where mb_id='{$fid}' ";		
	sql_query($sql,1);
	//echo $sql."<br>";
	
	$sql="update {$g5['cn_swap_table']} set mb_id='{$tid}' where mb_id='{$fid}' ";		
	sql_query($sql,1);
	//echo $sql."<br>";

	$sql="update {$g5['cn_transfer_table']} set mb_id='{$tid}' where mb_id='{$fid}' ";		
	sql_query($sql,1);
	//echo $sql."<br>";
	
	$sql="update {$g5['cn_transfer_table']} set tmb_id='{$tid}' where tmb_id='{$fid}' ";		
	sql_query($sql,1);
	//echo $sql."<br>";
	
	$sql="update {$g5['cn_item_cart']} set mb_id='{$tid}' where mb_id='{$fid}' ";		
	sql_query($sql,1);
	
	$sql="update {$g5['cn_item_trade']} set mb_id='{$tid}' where mb_id='{$fid}' ";		
	sql_query($sql);
	$sql="update {$g5['cn_item_trade']} set smb_id='{$tid}' where smb_id='{$fid}' ";		
	sql_query($sql);
	$sql="update {$g5['cn_item_trade']} set fmb_id='{$tid}' where fmb_id='{$fid}' ";		
	sql_query($sql);
	$sql="update {$g5['cn_item_trade']} set fsmb_id='{$tid}' where fsmb_id='{$fid}' ";		
	sql_query($sql);
	
	
	
	//echo $sql."<br>";	
	
	//포인트 테이블
	$result=sql_query("SELECT * FROM information_schema.tables WHERE table_name like  'coin\_point\_%' and TABLE_SCHEMA='".G5_MYSQL_DB."'  ");
	while($data=sql_fetch_array($result)){
				
		$sql="update {$data['TABLE_NAME']} set mb_id='{$tid}' where mb_id='{$fid}' ";		
		sql_query($sql,1);
		echo $sql."<br>";

		$sql="update {$data['TABLE_NAME']} set smb_id='{$tid}' where smb_id='{$fid}' ";		
		sql_query($sql,1);	
		echo $sql."<br>";

	}	
	
	
	$sql="update {$g5['cn_pointsum']} set mb_id='{$tid}' where mb_id='{$fid}' ";		
	sql_query($sql,1);
	//echo $sql."<br>";
	
	//포인트 테이블
	$sql="update {$g5['point_table']} set mb_id='{$tid}' where mb_id='{$fid}' ";		
	sql_query($sql,1);	
	//echo $sql."<br>";
	
	$sql="update {$g5['point_table']} set po_rel_id='{$tid}' where po_rel_id='{$fid}' ";		
	sql_query($sql,1);	
	//echo $sql."<br>";		
	
	//로그인 정보
	$sql="update  g5_login set mb_id='{$tid}' where mb_id='{$fid}'";
	sql_query($sql,1);		
	//echo $sql."<br>";
	
	//후원인 변경
	$sql="update  {$g5['member_table']} set mb_recommend='{$tid}' where mb_recommend='{$fid}'";
	sql_query($sql,1);		
	//echo $sql."<br>";
	
	//아이디 변경
	$sql="update  {$g5['member_table']} set mb_id='{$tid}' where mb_id='{$fid}'";
	sql_query($sql,1);		
	//echo $sql."<br>";
	
	
	$mb_dir = substr($fid,0,2);
    // 회원 아이콘 변
    if (is_file(G5_DATA_PATH.'/member/'.$mb_dir.'/'.$fid.'.gif'))
        copy(G5_DATA_PATH.'/member/'.$mb_dir.'/'.$fid.'.gif',G5_DATA_PATH.'/member/'.$mb_dir.'/'.$tid.'.gif');
	// 회원 이미지 변
    if (is_file(G5_DATA_PATH.'/member_image/'.$mb_dir.'/'.$fid.'.gif'))
        copy(G5_DATA_PATH.'/member_image/'.$mb_dir.'/'.$fid.'.gif',G5_DATA_PATH.'/member_image/'.$mb_dir.'/'.$tid.'.gif');


	return array(true,'ok');
}


//회원의 현재 수당 촣합 업데이트
function set_update_point($mb_id,$smb_id=''){	
	global $g5;
	
	// 포인트 UPDATE
	$p=get_mempoint($mb_id);
	
	sql_query(" update {$g5['member_table']} set 
	
	mb_point_free_b='".$p['free']['b']['_sum']."',
	mb_point_free_e='".$p['free']['e']['_sum']."',
	mb_point_free_i='".$p['free']['i']['_sum']."',
	mb_point_free_u='".$p['free']['u']['_sum']."',
	mb_point_free_s='".$p['free']['s']['_sum']."',
	

	mb_point_stable_b='".$p['stable']['b']['_sum']."',
	mb_point_stable_e='".$p['stable']['e']['_sum']."',
	mb_point_stable_i='".$p['stable']['i']['_sum']."',
	mb_point_stable_u='".$p['stable']['u']['_sum']."',
	mb_point_stable_s='".$p['stable']['s']['_sum']."',

	mb_point_out_b='".$p['out']['b']."',
	mb_point_out_e='".$p['out']['e']."',
	mb_point_out_i='".$p['out']['i']."',
	mb_point_out_u='".$p['out']['u']."',	
	mb_point_out_s='".$p['out']['s']."'		
	
	where mb_id = '$mb_id' ");	  
	
	if($smb_id=='' ) $smb_id=$mb_id;
	//서브아이디
	if($smb_id ){
		$p=get_mempoint($mb_id,$smb_id);
		sql_query(" update {$g5['cn_sub_account']} set 
	
		ac_point_b='".$p['free']['b']['_sum']."',
		ac_point_e='".$p['free']['e']['_sum']."',
		ac_point_i='".$p['free']['i']['_sum']."',
		ac_point_u='".$p['free']['u']['_sum']."',
		ac_point_s='".$p['free']['s']['_sum']."'

		where mb_id = '$mb_id' and ac_id='$smb_id' ");	  	
	
	}
	return;	
}

		
//1회용 입금 주소 발행		
function align_mb_wallet($mb_id,$token){
	global $g5;	
	
	$data=sql_fetch("select * from {$g5['cn_token_table']} where token_name='$token' and  mb_id='' order by token_no asc limit 1");	
	
	$result=sql_query("update {$g5['cn_token_table']} set mb_id='$mb_id',token_wdate=now() where token_no='{$data['token_no']}' ");	
	
	if($result) return $data['token_addr'];
	else return  false;
	
}


//주소 체크
function check_coin_addr($tokentype,$addr){

	//이더주소 검사
	if($tokentype=='e') {
		if(!preg_match("/^0x/",$addr) || strlen($addr)!=42){
			 return array(false,'This is not a valid ETH address.');
		}
		else return array(true,'ok');
	}
	//비트코인 지갑 주소
	else if($tokentype=='b') {
		if(!preg_match("/^(1|3|n|m)/",$addr) || strlen($addr)!=34){
			 return array(false,'This is not a valid BIT address.');		
		}
		else return array(true,'ok');
	}
	//아이텐 지갑 주소
	else if($tokentype=='i') {
		if(!preg_match("/^0x/",$addr) || strlen($addr)!=42){
			return array(false,'This is not a valid ITEN address.');		
		}
		else return array(true,'ok');
	}
	//usdt 지갑 주소
	else if($tokentype=='u') {
		if(!preg_match("/^0x/",$addr) || strlen($addr)!=42){
			 return array(false,'This is not a valid USDT address.');		
		}
		else return array(true,'ok');
	}
	else return array(false,'Token format is incorrect');
	
		
}

//테이블 검사
function chk_table($tbname){
	$data=sql_fetch("SELECT COUNT(*) cnt FROM information_schema.tables WHERE table_name = '$tbname' and TABLE_SCHEMA='".G5_MYSQL_DB."' ");
	
	if($data[cnt] > 0) return true;
	else return false;
}

//포인트 테이블 검사후 생성
function set_point_refresh($point_table){
	
	$sql="
		CREATE TABLE IF NOT EXISTS `$point_table`  (
		  `pt_no` bigint(20) primary key auto_increment NOT NULL COMMENT '고유번호',
		  `pt_wallet` varchar(20) NOT NULL,
		  `pt_coin` varchar(20) NOT NULL,
		  `pkind` varchar(30) NOT NULL COMMENT '수당구분(fee:수당,in:수당이전수입,out:수당이전지출,pay:수당지급)',
		  `mb_id` varchar(30) NOT NULL COMMENT '수급 회원 아이디',
		  `mb_grade` tinyint(2) NOT NULL COMMENT '지급당시 회원 등급',
		  `smb_id` varchar(30) NOT NULL COMMENT '수급 회원 서브 아이디',
		  `fmb_id` varchar(30) NOT NULL COMMENT '지급 회원 아이디(매출발생회원)',
		  `fmb_step` tinyint(2) NOT NULL COMMENT '수급-지급 회원간 단계',
		  `ratio` float(4,2) DEFAULT NULL COMMENT '매출대비 지급수당 율',
		  `amount` double(30,8) DEFAULT NULL COMMENT '지급액',
		  `usd` double(12,2) DEFAULT NULL COMMENT '지급액 USD',
		  `f_coin` varchar(20) NOT NULL,
		  `waddr_from` varchar(300) NOT NULL,
		  `waddr_to` varchar(300) NOT NULL,
		  `link_no` varchar(30) NOT NULL COMMENT '지급 사유 데이터 고유번호(참여테이블, 수당 이전테이블)',
		  `link_data` varchar(255) NOT NULL COMMENT '지급 사유 데이터의 설명',
		  `subject` varchar(500) NOT NULL COMMENT '비고',
		  `pdate` date NOT NULL,
		  `wdate` datetime DEFAULT NULL COMMENT '최초 등록일',
		  `mdate` datetime DEFAULT NULL COMMENT '최근 수정일'
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='수당 내역 테이블'
	";
	
	$result=sql_query($sql);
	
	//echo $sql;
	
	return $result;
}

//아이템 보유액 
function get_itemsum($mb_id,$smb_id=''){
	global $g5;
	
	$rtn=array();
	
	$rtn[tot][price]=$rtn[tot][cnt]=0;
	if($smb_id) $sql=" and smb_id='$smb_id' ";
	
	$re=sql_query("select *,sum(ct_buy_price) price , count(cn_item) cnt from {$g5['cn_item_cart']} where mb_id='$mb_id' and is_soled!='1'  $sql group by cn_item",1);
	while($data=sql_fetch_array($re)){
		$rtn[$data['cn_item']]['cnt']=$data['cnt'];
		$rtn[$data['cn_item']]['price']=$data['price'];	
		
		$rtn[tot][price]+=$data[price]*1;
		$rtn[tot][cnt]+=$data[cnt]*1;
	}
	
	return $rtn;

}

/**************************************************************************************************
	각종 금액 지급
******************************************************************************************************/
function set_add_point($pkind,$mb,$smb_id,$fmb_id,$content,$date=''){	
	global $g5;
	if(!is_array($mb)) $mb=get_member($mb);
	
	//지갑설정
	$pt_wallet=$content['pt_wallet'];
	if($pt_wallet=='') return false;
	
	//코인설정
	$pt_coin=$content['pt_coin'];
	if($pt_coin=='') return false;	
	
	if(!$mb['mb_id']) return;
	
	$smb_id=trim($smb_id);
	
	//기본은 메인계정에 지급
	if($smb_id=='') $smb_id=$mb[mb_id];

	if(!$date ) $date=date("Y-m-d H:i:s");	
	
	$point_table=$g5['cn_point']."_".date('ym',strtotime($date));
	
	//이달 테이블 검사후 갱신
	$table_chk=chk_table($point_table);
	if(!$table_chk) set_point_refresh($point_table);
	
	//동일 내역 있는 경우 스킵
	if(trim($content['link_no'])!=''){
		$temp=sql_fetch("select count(*) cnt from {$point_table} where pt_wallet='$pt_wallet' and  pt_coin='$pt_coin' and  pkind='$pkind' and mb_id='{$mb['mb_id']}' and smb_id='{$smb_id}'  and fmb_id='$fmb_id' and link_no='{$content['link_no']}'");				
		if($temp['cnt'] > 0) return;
	}
	
	//지급 내역 입력
	$query="insert into {$point_table} set
	pt_wallet='$pt_wallet',
	pt_coin='$pt_coin',
	pkind='$pkind',
	mb_id='".addslashes($mb['ac_id'])."',
	mb_grade='{$mb['mb_grade']}',	
	smb_id='$smb_id',
	
	fmb_id='$smb_id',
	fmb_step='{$content['step']}',
	
	ratio='$content[ratio]',
	amount='$content[amount]',
	usd='$content[usd]',
	
	waddr_from='$content[waddr_from]',
	waddr_to='$content[waddr_to]',
	
	link_no='$content[link_no]',
	link_data='$content[link_data]',
	
	subject='$content[subject]',
	
	pdate='$content[pdate]',
	
	wdate=now(),
	mdate=now()
	";  
  
  	//echo $query;
	  $result=sql_query($query);
	  if(!$result) return;
    
	  //총액 업데이트
	  $temp=sql_fetch("select * from {$g5['cn_pointsum']} where pt_wallet='$pt_wallet' and  pt_coin='$pt_coin' and pkind='$pkind' and mb_id='{$mb['mb_id']}' and smb_id='{$smb_id}'");
	  
	  if($temp['mb_id']){
		sql_query("update {$g5['cn_pointsum']} set 
		amount=amount + {$content['amount']},
		cnt=cnt+1,
		mdate=now()
		where pt_wallet='$pt_wallet' and  pt_coin='$pt_coin' and  pkind='$pkind' and mb_id='{$temp['mb_id']}' and smb_id='{$smb_id}' ");
		
	  }else{
		sql_query("insert into {$g5['cn_pointsum']} set
		pt_wallet='$pt_wallet',
		pt_coin='$pt_coin', 
		pkind='$pkind',
		mb_id='".addslashes($mb['mb_id'])."',	
		smb_id='".addslashes($smb_id)."',	
		amount='$content[amount]',
		cnt=1,
		mdate=now()
		");
	  }
	 	 
	 //회원별 최종 수당 정보 업데이트
	 set_update_point($mb['mb_id'],$smb_id);
	  
	 return ;
}

//지급된 수수료 회수
function set_del_point($pkind,$mb_id,$smb_id,$fmb_id,$link_no,$date=''){	
	global $g5;
		
	if(($smb_id=='' && $mb_id=='' && $fmb_id=='') || $link_no=='') return;
	
	if(!$date || preg_match("/^00/",$date) ) $date=date("Y-m-d H:i:s");	
	
	$point_table=$g5['cn_point']."_".date('ym',strtotime($date));
	$point_table2=$g5['cn_point']."_".date('ym',strtotime('-1 days',strtotime($date)));
	
	
	$sql='';
	if($mb_id!='') $sql=" and mb_id='$mb_id'";
	if($smb_id!='') $sql.=" and smb_id='$smb_id'";
	if($fmb_id!='') $sql.=" and fmb_id='$fmb_id'";
	
	$member_ary=array();
	
	$update_cnt=0;
	
	if(chk_table($point_table)){

		$re=sql_query("select pt_wallet,pt_coin,mb_id,smb_id,fmb_id, count(*) cnt,sum(amount) amount from {$point_table} where pkind='$pkind' and link_no='$link_no'  $sql  group by pt_wallet,pt_coin,mb_id,smb_id");

		while($pdata=sql_fetch_array($re)){
			sql_query("update {$g5['cn_pointsum']} set amount=amount - $pdata[amount],cnt=cnt-{$pdata['cnt']} where pt_wallet='{$pdata['pt_wallet']}' and pt_coin='{$pdata['pt_coin']}' and pkind='$pkind' and mb_id='{$pdata['mb_id']}'  and smb_id='{$pdata['smb_id']}'",1);
			if(!in_array($pdata['mb_id'],$member_ary)) $member_ary[]=$pdata['mb_id']."^".$pdata['smb_id'];	
			$update_cnt++;		
		}
		sql_query("delete from {$point_table} where pkind='$pkind' and link_no='$link_no' $sql  ",1);
	
	}
	
	
	//해당월에 데이터가 없는 경우
	if($update_cnt==0 && chk_table($point_table2)){
		$re=sql_query("select pt_wallet,pt_coin,mb_id,smb_id,fmb_id, count(*) cnt,sum(amount) amount from {$point_table2} where pkind='$pkind' and link_no='$link_no'  $sql  group by pt_wallet,pt_coin,mb_id,smb_id",1);
	
		while($pdata=sql_fetch_array($re)){
			sql_query("update {$g5['cn_pointsum']} set amount=amount - $pdata[amount],cnt=cnt-{$pdata['cnt']} where pt_wallet='{$pdata['pt_wallet']}' and pt_coin='{$pdata['pt_coin']}' and pkind='$pkind' and mb_id='{$pdata['mb_id']}'  and smb_id='{$pdata['smb_id']}'",1);
			if(!in_array($pdata['mb_id'],$member_ary)) $member_ary[]=$pdata['mb_id']."^".$pdata['smb_id'];	
			$update_cnt++;
		}
		
		sql_query("delete from {$point_table2} where pkind='$pkind' and link_no='$link_no' $sql  ",1);
	}
	
	//관련 회원 수당 정보 업데이트	
	foreach($member_ary as $v){
		$ary=explode("^",$v);
		set_update_point($ary[0],$ary[1]);
	}
		
}


// 회원의 현재 보유 수당 내역별 정보
function get_mempoint($mb_id,$smb_id='') {
	global $g5;
	
	if(!$mb_id) return;
		
	$rtn=array();
	
	//초기화
	foreach($g5['cn_wallet'] as $k=>$v){
		
		foreach($g5['cn_cointype'] as $k2=>$v2){
			$rtn[$k2]['_enable']=$rtn[$k2]['_hold']=0;
			
			foreach($g5['cn_pkind'] as $k3=>$v3){
				
				$rtn[$k][$k2][$k3]=$rtn[$k][$k2][$k3.'_cnt']=0;		
				$rtn[$k2][$k2]=0;
			}
		}
	}
	
	$smb_id=trim($smb_id);
	
	//서브계정의 포인트
	if($smb_id!=''){
		$sql=" and smb_id='$smb_id' ";	
	} else $sql='';
	
	//지급 종류별 내역
	$re=sql_query("select *,sum(amount) amt, count(*) cnt from {$g5['cn_pointsum']} where mb_id='$mb_id' $sql group by pt_wallet,pt_coin,pkind");		
	while($data=sql_fetch_array($re)){
		
		//지갑-코인-구분별 합
		$rtn[$data['pt_wallet']][$data['pt_coin']][$data['pkind']]+=$data['amt'];
		$rtn[$data['pt_wallet']][$data['pt_coin']][$data['pkind']."_cnt"]+=$data['cnt'];	
		
		//코인-구분별 합
		$rtn[$data['pt_coin']][$data['pkind']]+=$data['amt'];
		$rtn[$data['pt_coin']][$data['pkind']."_cnt"]+=$data['cnt'];	

		if(!preg_match("/^(outing|sending)$/",$data[pkind])){
			
			//지갑내 코인별 합U
			$rtn[$data['pt_wallet']][$data['pt_coin']]['_sum']+=$data['amt'];
			
			//코인별합 
			$rtn[$data['pt_coin']]['_hold']+=$data['amt'];			
		}		
		
	}		
	
	//현재 보유수당
	foreach($g5['cn_cointype'] as $k=>$v){
		$rtn[$k]['_enable']=$rtn[$k]['_hold']+$rtn[$k]['outing']+$rtn[$k]['sending'];
	}
	
	return $rtn;
}



//이더 잔액확인
function balance_coin_eth($addr){
	
	//https://api.etherscan.io/api?module=account&action=balance&address=0x2823459A6B521e503C872901b0452FEc94247931&tag=latest&apikey=
	$apikey='5PUIQYGM9EKB4NHZMKKQNHUT8I74QA9CDG';
	$query="module=account&action=balance&address=$addr&tag=latest&apikey=$apikey";
	
	//데이터 전송
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'https://api.etherscan.io/api');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
	$headers = array();
	$headers[] = 'Content-Type: application/x-www-form-urlencoded';
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	$result = curl_exec($ch);
	if (curl_errno($ch)) {
		return false;
	}
	curl_close($ch);
	
	//{"status":"1","message":"OK","result":"4445000000000000000"}
		
	$ary=json_decode($result, true);
	
	//print_r($ary);
	
	if($ary['status']=='1') return array(true,$ary['result']/1000000000000000000);
	else return array(false,$ary['message']);
	//1000000000000000000;
	
}


//테더 잔액확인
function balance_coin_usdt($addr){
	
	//https://api.etherscan.io/api?module=account&action=tokenbalance&contractaddress=0xdac17f958d2ee523a2206206994597c13d831ec7&address=확인할주소&tag=latest&apikey=5PUIQYGM9EKB4NHZMKKQNHUT8I74QA9CDG
	
	$apikey='5PUIQYGM9EKB4NHZMKKQNHUT8I74QA9CDG';
	$query="module=account&action=tokenbalance&contractaddress=0xdac17f958d2ee523a2206206994597c13d831ec7&address=$addr&tag=latest&apikey=$apikey";
	
	//데이터 전송
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'https://api.etherscan.io/api');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
	$headers = array();
	$headers[] = 'Content-Type: application/x-www-form-urlencoded';
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	$result = curl_exec($ch);
	if (curl_errno($ch)) {
		return false;
	}
	curl_close($ch);
	
	//{"status":"1","message":"OK","result":"4445000000000000000"}
		
	$ary=json_decode($result, true);
	
	//print_r($ary);
	
	if($ary['status']=='1') return array(true,$ary['result']/1000000);
	else return array(false,$ary['message']);
	
}


//코인별 시세 가져오기 - 실시간
function get_sise_realtime($coin,$currency='usd'){
	$amt=0;
	if($coin=='m'){
		$amt=0.1;
	}else if($coin=='t'){
		$amt=0.1;
	}else if($coin=='s'){
		$amt=0.1;
	}else if($coin=='b'){
		$amt=1200;
	}
	
	return $amt;
}

//코인별 시세 가져오기 - DB
function get_sise(){	
	global $g5;
	
	$data=sql_fetch("select * from {$g5['cn_sise_table']}");
	
	return json_decode($data['data'], true);
}


//코인 달러 변환
function swap_usd($amt,$coin,$sise=array()){		
	global $g5;
	
	if($sise['sise_'.$coin]=='') $sise=get_sise();
	
	return $amt*$sise['sise_'.$coin];
}

//btc 변환
function swap_coin($amt,$fc,$tc,$sise=array()){		
	global $g5;
	if($sise['sise_'.$coin]=='') $sise=get_sise();
	
	//달러인 경우
	if($fc=='d'){
		return $amt/$sise['sise_'.$tc];	
	}else{
		return swap_usd($amt,$fc,$sise)/$sise['sise_'.$tc];		
	}
	
}


//지정일 정산 여부 체크
function get_settlestat($date){
	global $g5;
	
	$date=substr($date,0,10);
	
	$data=sql_fetch("select * from {$g5['cn_set_table']} where st_start_date <= '$date' and st_end_date >='$date' ");
	
	if($data['st_no']) return true;
	else return false;	
}




//테이블의 칼럼 정보
function get_dbcols($table) { 
     $result = sql_query("SHOW COLUMNS FROM ". $table); 
      if (!$result) { 
        echo 'Could not run query: ' . mysql_error(); 
      } 
      $fieldnames=array(); 
      if (sqll_num_rows($result) > 0) { 
        while ($row = sql_fetch($result)) { 
          $fieldnames[] = $row; 
        } 
      }
      return $fieldnames; 
} 

//특정 항목의 배열화
function get_fielddata($db,$f,$sql='',$cnt=0){
	$rtn=array();
	if($db=='' || $f=='') return ;
	if($cnt > 0) $limit=" limit $cnt ";
	$result=@sql_query("select `$f` from `$db` where `$f` is not NULL and `$f` !='' $sql  group by `$f` order by `$f` asc $limit ");
	while($data=@sql_fetch_array($result)) $rtn[]=$data[$f];	
	return $rtn;
}
//특정 두 항목의 연관 배열
function get_dbdata($db,$key,$d='',$sql='',$cnt=0){
	$rtn=array();
	if($db=='' || $key=='') return ;
	if($cnt > 0) $limit=" limit $cnt ";
	$result=@sql_query("select * from `$db` where `$key` is not NULL and `$key` !='' $sql  group by `$key` order by `$key` asc $limit ");
	while($data=@sql_fetch_array($result)){
		if($d)	$rtn[$data[$key]]=$data[$d];	
		else 	$rtn[$data[$key]]=$data;	
	}
	return $rtn;
}

// 메세지 출력-json 후 종료
function alert_json($bools,$message,$datas='',$datas2='',$datas3='') {
	$ary=array("result"=>$bools,"message"=>lng($message),"datas"=>$datas,"datas2"=>$datas2,"datas3"=>$datas3);	
	echo json_encode($ary);
	exit;
}

function only_number($n)
{
    return preg_replace('/[^0-9\.\-]/', '', $n);
}
function only_numeric($n)
{
    return preg_replace('/[^0-9]/', '', $n);
}
function number_format2($n,$precision=8)
{	
	$n=number_format($n,$precision);
	if(preg_match("/\./",$n)){
		$n=preg_replace("/0+$/","",$n);	
		$n=preg_replace("/\.$/","", $n);
	}	
	return $n;	
}

//소수점 0 버림
function numberFormatClean($iNumber, $iDecimals = 2)
{
    $sNumber = number_format($iNumber, $iDecimals);
    $sNumber = rtrim($sNumber, 0);
    $sNumber= rtrim($sNumber, '.');

    return $sNumber;
}



// 상품목록 함수호출 페이징 
function get_paging_ajax( $write_pages, $cur_page, $total_page,$func)
{

    $str = '';
    if ($cur_page > 1) {
        $str .= '<a href="javascript:void('.$func.'(\'1\'));" class="pg_page pg_start">처음</a>'.PHP_EOL;
    }

    $start_page = ( ( (int)( ($cur_page - 1 ) / $write_pages ) ) * $write_pages ) + 1;
    $end_page = $start_page + $write_pages - 1;

    if ($end_page >= $total_page) $end_page = $total_page;

    if ($start_page > 1) $str .= '<a href="javascript:void('.$func.'('.($start_page-1).'));" class="pg_page pg_prev">이전</a>'.PHP_EOL;

    if ($total_page > 1) {
        for ($k=$start_page;$k<=$end_page;$k++) {
            if ($cur_page != $k)
                $str .= '<a href="javascript:void('.$func.'('.($k).'));" class="pg_page">'.$k.'<span class="sound_only">페이지</span></a>'.PHP_EOL;
            else
                $str .= '<span class="sound_only">열린</span><strong class="pg_current">'.$k.'</strong><span class="sound_only">페이지</span>'.PHP_EOL;
        }
    }

    if ($total_page > $end_page) $str .= '<a href="javascript:void('.$func.'('.($end_page+1).'));" class="pg_page pg_next">다음</a>'.PHP_EOL;

    if ($cur_page < $total_page) {
        $str .= '<a href="javascript:void('.$func.'('.($total_page).'));" class="pg_page pg_end">맨끝</a>'.PHP_EOL;
    }

    if ($str)
        return "<nav class=\"pg_wrap\"><span class=\"pg\">{$str}</span></nav>";
    else
        return "";
}

//표준 페이지 링크 출력 - 부트스트랩 스타일
function com_pager_print($total_page,$now_page,$view_page_num,$url_query=''){	
	
	
	$url_query=preg_replace("/^\&/","",$url_query);
	
	$pager_str="<ul class='pagination justify-content-center' >";
	$s_page=(int)(($now_page-1)/$view_page_num)*$view_page_num;

	if($now_page > $view_page_num ) {
		$pager_str.="<li class='page-item'><a href='{$_SERVER['PHP_SELF']}?$url_query"."1'  class='page-link' ><span>First</span></a></li>";
	
	}
	
	if($now_page > $view_page_num) {
		$prev_page=$s_page-1;
		$pager_str.="<li  class='page-item'><a href='{$_SERVER['PHP_SELF']}?$url_query$prev_page'  class='page-link'  >&laquo;</a></li>";
	}

	for($i=1; $i+$s_page<=$total_page&&$i<=$view_page_num;$i++){
		$go_page=$i+$s_page;
		if($now_page == $go_page) $class=" active "; else $class=''; 
		$pager_str.="<li  class='page-item $class' ><a href='{$_SERVER['PHP_SELF']}?$url_query$go_page'  class='page-link' >$go_page</a></li>";			
	}
	
	if($total_page>$go_page) {
		$next_page=$go_page+1;
		$pager_str.="<li class='page-item'><a href='{$_SERVER['PHP_SELF']}?$url_query$next_page'  class='page-link' >&raquo;</a></li>";
	}
	
	if($now_page <= ($total_page - $view_page_num) ) {
		$pager_str.="<li class='page-item'><a href='{$_SERVER['PHP_SELF']}?$url_query$total_page'  class='page-link'  ><span>Last</span></a></li>";
	
	}
		
	$pager_str.="</ul>";
	return $pager_str;
}

//트윌로 문
function twilio_sms($params){
	global $g5;
	
	//$EXCLAMATION_MARK='!';
	$ch = curl_init();
	
	
	$params[Body].=$EXCLAMATION_MARK;
	$params[From]=$g5['cn_callback'];	
	/*
	$params[Body]="Hello there$EXCLAMATION_MARK";
	$params[From]="+12058284940";
	$params[MediaUrl]="https://demo.twilio.com/owl.png";
	$params[To]="+821086653051";
	*/
	//echo 'https://api.twilio.com/2010-04-01/Accounts/'.$g5['cn_id'].'/Messages.json';
	//echo  $g5['cn_token'];
	curl_setopt($ch, CURLOPT_URL, 'https://api.twilio.com/2010-04-01/Accounts/'.$g5['cn_id'].'/Messages.json');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
	curl_setopt($ch, CURLOPT_USERPWD, $g5['cn_id']. ':' . $g5['cn_token']);

	$result = curl_exec($ch);
	if (curl_errno($ch)) {
		echo 'Error:' . curl_error($ch);
	}	
	curl_close($ch);
	//echo $result;
	return $result;
}

//지급 설정 
$cset=get_coinset();

//상품정보 수정 적용
$minfo=array();
$re=sql_query("select * from {$g5['cn_item_info']} ",1);
while($data=sql_fetch_array($re)){
	$minfo[$data['cn_item']]=$data;
	$g5['cn_item'][$data[cn_item]][name_kr]=$data[name_kr];
	$g5['cn_item'][$data[cn_item]][days]=$data[days];
	$g5['cn_item'][$data[cn_item]][interest]=$data[interest];
	$g5['cn_item'][$data[cn_item]][price]=$data[price];
	$g5['cn_item'][$data[cn_item]][mxprice]=$data[mxprice];
	$g5['cn_item'][$data[cn_item]][fee]=$data[fee];
}


//제약없는 쿼리문 넣기
function sql_query_ext($sql, $error=G5_DISPLAY_SQL_ERROR, $link=null)
{
    global $g5;

    if(!$link)
        $link = $g5['connect_db'];

    // Blind SQL Injection 취약점 해결
    $sql = trim($sql);
    // union의 사용을 허락하지 않습니다.
    //$sql = preg_replace("#^select.*from.*union.*#i", "select 1", $sql);
    //$sql = preg_replace("#^select.*from.*[\s\(]+union[\s\)]+.*#i ", "select 1", $sql);
    // `information_schema` DB로의 접근을 허락하지 않습니다.
    //$sql = preg_replace("#^select.*from.*where.*`?information_schema`?.*#i", "select 1", $sql);

    if(function_exists('mysqli_query') && G5_MYSQLI_USE) {
        if ($error) {
            $result = @mysqli_query($link, $sql) or die("<p>$sql<p>" . mysqli_errno($link) . " : " .  mysqli_error($link) . "<p>error file : {$_SERVER['SCRIPT_NAME']}");
        } else {
            $result = @mysqli_query($link, $sql);
        }
    } else {
        if ($error) {
            $result = @mysql_query($sql, $link) or die("<p>$sql<p>" . mysql_errno() . " : " .  mysql_error() . "<p>error file : {$_SERVER['SCRIPT_NAME']}");
        } else {
            $result = @mysql_query($sql, $link);
        }
    }

    return $result;
}


//기본 한글팩
$langfile_kr=G5_PATH."/lang/kr.txt";
$g5['now_lang_set']=get_cookie('lang_set');
if($g5['now_lang_set']=='') $g5['now_lang_set']='kr';

$g5['langpack_kr']=array();
if(is_file($langfile_kr)){
	$lines=file($langfile_kr);

	$num=0;
	foreach($lines as $line){			
		$line=trim($line);
		if($line=='' || preg_match("/^#/",$line) )  continue;

		$g5['langpack_kr'][$num]=strtolower(preg_replace("/\.|\s|\t|\n|\,/","",$line));
		
		$num++;
	}		
}

//현재 언어팩 로딩
if($g5['now_lang_set']!='kr' ){
	$langfile=G5_PATH."/lang/".$g5['now_lang_set'].".txt";
	
	$g5['langpack']=array();
	if(is_file($langfile)){
		$lines=file($langfile);
		
		$num=0;
		foreach($lines as $line){			
			$line=trim($line);
			if($line=='' || preg_match("/^#/",$line) )  continue;
			
			$g5['langpack'][$num]=$line;
			
			$num++;
		}
	}	
}


//언어 번역
$g5['lng_history']=array();	//검출용

function lng($str,$part=''){
	global $g5;
	if(!in_array($str,$g5[lng_history])) $g5[lng_history][]=$str;
	
	$trans=$str;
	$keys=$str.($part?"@".$part:'');
	$num=array_search(strtolower(preg_replace("/\.|\s|\t|\n|\,/","",$keys)),$g5['langpack_kr']);
	if($g5['now_lang_set']=='kr'){
		return $str;
	}
	if($num!==false) $trans=$g5['langpack'][$num];
	if($trans=='' ) return $str;
	else if( $trans=='null') return '';
	else return $trans;
}
