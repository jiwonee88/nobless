<?php
$sub_menu = "700740";
include_once('./_common.php');

//error_reporting(E_ALL);
//ini_set('display_errors', '1');
	
auth_check($auth[$sub_menu], 'w');
//check_admin_token();

//print_r($_POST);

//exit;

//오늘 이외 매칭은 지우기
sql_query("delete from {$g5['cn_item_trade_test']} where tr_wdate !='$start_date'  ");

if($w=='s') $limit=20;
else  $limit=10;

if(!$page) $page=1;

$start=($page-1) * $limit;
$limit_sql="limit $start ,$limit";

$add_sql='';
$lines='';

$mb=get_member($mb_id);
if($mb[mb_id]=='') alert('지급될 회원을 찾을 수 없습니다');

if($smb_id){
	$smb=get_submember($smb_id);
	if($smb[ac_id]=='' || $smb[mb_id]!=$mb_id) alert('지급될 서브계정이 없거나 다른 회원의 서브계정입니다');

}else{

}


if($smb_id=='' ) $smb_id=$mb_id;

//입금은행 정보 검사
$bank_sql="and b.mb_bank!='' and b.mb_bank_num!=' ' and b.mb_bank_user!='' ";

//로그인 시간 제한시
if($login_day_seller !='' && $login_day_seller > 0) {
	$logdate=date("Y-m-d H:i:s",strtotime("- $login_day_seller days"));
	$login_seller_sql=" and b.mb_today_login >= '$logdate' ";
}else $login_seller_sql='';

//지정 상품이 있는 경우
$target_code=preg_replace("/\n+/",",",$target_code);
$target_code=preg_replace("/\s+/","",$target_code);
$target_code=preg_replace("/,/","','",$target_code);
if($target_code!='' ) $target_code_search =" and a.code in ('$target_code') ";
else  $target_code_search='';


//지정 회원이 있는 경우 품이 있는 경우
$target_seller=preg_replace("/\n+/",",",$target_seller);
$target_seller=preg_replace("/\s+/","",$target_seller);
$target_seller=preg_replace("/,/","','",$target_seller);
if($target_seller!='' ) $target_seller_search =" and a.mb_id in ('$target_seller') ";
else  $target_seller_search='';



//제외 회원이 있는 경우 품이 있는 경우
$except_seller=preg_replace("/\n+/",",",$except_seller);
$except_seller=preg_replace("/\s+/","",$except_seller);
$except_seller=preg_replace("/,/","','",$except_seller);
if($except_seller!='' ) $except_seller_search =" and a.mb_id not in ('$except_seller') ";
else  $except_seller_search='';


//지정일자 미거리 우선
if($miss_date_seller!=''){		
	
	$target_cnt=count(explode(",",$miss_date_seller));
	$miss_date_seller=preg_replace("/\n+/",",",$miss_date_seller);
	$miss_date_seller=preg_replace("/\s+/","",$miss_date_seller);
	$miss_date_seller=preg_replace("/,/","','",$miss_date_seller);
	if($miss_date_seller!='' ) $miss_date_seller_search =" and tr_wdate in ('$miss_date_seller') ";
	else  $miss_date_seller_search='';
	
	$miss_date_seller_orderby =" if((select count(*) cnt from {$g5['cn_item_trade_test']} as a  where fmb_id=b.mb_id and cn_item='$k') = 0 or  (select count(tr_code) from {$g5['cn_item_trade']}  where fmb_id=b.mb_id   $miss_date_seller_search and tr_stats in ('1','2','9') ) > 0,1,0)  desc, ";
	
}else{
	$miss_date_seller_orderby='';
	$miss_date_seller_orderby ='';
}


//미거래 판매 회원		
if($miss_cnt_seller > 0){			
	$miss_date=date("Y-m-d",strtotime("-".($miss_cnt_seller - 1)." days"));
	$miss_seller_search =" and (select count(tr_code) from {$g5['cn_item_trade']}  where cart_code=a.code and tr_wdate >= '$miss_date' and tr_stats in ('1','2','9') ) >= $miss_cnt_seller ";

	//$miss_sql=",(select count(tr_code) from {$g5['cn_item_trade']}  where smb_id=a.ac_id and tr_wdate='$start_date') as miss_cnt";

}else $miss_seller_search='';

//미거래 구매 회원		
if($miss_cnt > 0){			
	$miss_date=date("Y-m-d",strtotime("-".($miss_cnt - 1)." days"));
	$miss_search =" and (select count(tr_code) from {$g5['cn_item_trade']}  where cart_code=a.code and tr_wdate >= '$miss_date' and tr_stats in ('1','2','9') ) >= $miss_cnt ";

	//$miss_sql=",(select count(tr_code) from {$g5['cn_item_trade']}  where smb_id=a.ac_id and tr_wdate='$start_date') as miss_cnt";

}else $miss_search='';


if($login_day !='' && $login_day > 0) {
	$logdate=date("Y-m-d H:i:s",strtotime("- $login_day days"));
	$login_sql=" and b.mb_today_login >= '$logdate' ";
}else $login_sql='';


//수수료 금액 제한
if($min_money > 0){		
	$min_money=only_number($min_money);
	$min_money_sql=" and b.mb_point_free_".$g5[cn_fee_coin]." >= $min_money  ";	
}else $min_money_sql='';



$item_cnt_arr=array();
$tot_cnt=0;

//매칭 상품 선택
foreach($g5['cn_item'] as $k=> $v) { 

	$qty=$item_qty[$k];	
	
	$iteminfo=$g5['cn_item'][$k];
	
	//수량 도달시 스킵
	if($item_cnt_arr[$k] >= $qty) continue;
	
	
	/* 판매 대기중 상품 가격/결제 그룹 */
	$sql = " select  a.*,b.*,c.ac_point_b,c.ac_point_e,c.ac_point_i,c.ac_point_u
	from {$g5['cn_item_cart']} as a 
	left outer join  {$g5['member_table']} as b on(a.mb_id=b.mb_id)  
	left outer join  {$g5['cn_sub_account']} as c on(c.ac_id=a.mb_id)  
	where b.mb_level='5'  and b.mb_10 not in ('1','2')  and a.mb_id!='$mb_id'  and a.cn_item ='$k' and a.is_soled != '1'  and a.is_trade != '1' and a.ct_validdate <= '$start_date 23-59-59' 
	and b.mb_trade_penalty <= 0 $login_seller_sql  $target_code_search $target_seller_search  $except_seller_search $miss_seller_search  $min_money_sql  $bank_sql
	order by  a.ct_priority desc,rand() ";
	
	//echo $sql;
	$re = sql_query($sql,1);

	while($sdata=sql_fetch_array($re)){

		$_sdata=$sdata;

		$multidata=array();

		//오늘 매도액 
		$_traded_amt=0;

		//지금까지 누적 발행액
		$_traded_amt_sum=	$sdata[trade_amt];
		
		$tr_src='cart';

		//최고액 도달후 권종 업그레이트 가능한 경우	 
		if( $sdata[ct_sell_price] > $g5['cn_item'][$sdata[cn_item]][mxprice] &&  $next_item[$sdata[cn_item]]  ){
			
			$osdata=sql_fetch("select count(tr_code) cnt from {$g5['cn_item_trade_test']} where cart_code='$sdata[code]'  ",1);
			if($osdata['cnt'] > 0) continue;
			
			$_sdata[item_price]=$sdata[ct_sell_price];	
			$_sdata[cn_item] = $next_item[$sdata[cn_item]];	

			$multidata[0]=$_sdata;

			$div_cnt=1;	
			$is_traded=0;
			$is_traded_amt=0;


		//일반 최고가 도달 상품 가격쪼개기	
		}else if( $sdata[ct_sell_price] > $g5['cn_item'][$sdata[cn_item]][mxprice]){		

			$remain_amt=$sdata[ct_sell_price];

			//흰나비의 경우
			if($sdata[cn_item]=='c') {

				$osdata=sql_fetch("select sum(if(cn_item='c',1,0)) b_cnt , sum(if(cn_item='b',1,0)) b_cnt ,sum(if(cn_item='a',1,0)) a_cnt from {$g5['cn_item_trade']} where cart_code='$sdata[code]' and tr_stats in ('1','2','3') ",1);
				$osdata2=sql_fetch("select sum(if(cn_item='c',1,0)) b_cnt , sum(if(cn_item='b',1,0)) b_cnt ,sum(if(cn_item='a',1,0)) a_cnt from {$g5['cn_item_trade_test']} where cart_code='$sdata[code]'  ",1);
				
				$osdata['c_cnt']+=($osdata2['c_cnt']?$osdata2['c_cnt']:0);
				$osdata['b_cnt']+=($osdata2['b_cnt']?$osdata2['b_cnt']:0);
				$osdata['a_cnt']+=($osdata2['a_cnt']?$osdata2['a_cnt']:0);
				
				if(!$osdata['c_cnt']) $osdata['c_cnt']=0;
				if(!$osdata['b_cnt']) $osdata['b_cnt']=0;
				if(!$osdata['a_cnt']) $osdata['a_cnt']=0;


				//흰나비2개
				for($x=0; $x < (2 - $osdata['c_cnt']);$x++){

					$_sdata[item_price]=$g5[cn_item]['c']['price'];	
					$_sdata[cn_item] = 'c';					
					$multidata[$x]=$_sdata;

				}			
				$remain_amt-=($g5[cn_item]['c']['price']*2 );
				if($osdata['b_cnt'] == 0){

					//노랑나비				
					$_sdata[item_price]=floor($remain_amt/10)*10;
					$_sdata[cn_item] = 'b';					
					$multidata[]=$_sdata;
				}

				$div_cnt=3;				


			}else{

				$osdata=sql_fetch("select count(tr_code) cnt from {$g5['cn_item_trade']} where cart_code='$sdata[code]' and tr_stats in ('1','2','3') ",1);
				$osdata2=sql_fetch("select count(tr_code) cnt from {$g5['cn_item_trade_test']} where cart_code='$sdata[code]'  ",1);
				$osdata['cnt']+=($osdata2['cnt']?$osdata2['cnt']:0);
				
				$_sdata[item_price]=floor($sdata[ct_sell_price]/3/10)*10;

				for($x=  (3 - $osdata['cnt']); $x > 0;$x--){
					$multidata[]=$_sdata;
					$_traded_amt+=$_sdata[item_price];
				}

				$div_cnt=3;
				$is_traded=$sdata[trade_cnt];
				$is_traded_amt=$sdata[trade_amt]+$_traded_amt;

			}
		
		//단일 상품
		}else{
			
			$osdata=sql_fetch("select count(tr_code) cnt from {$g5['cn_item_trade_test']} where cart_code='$sdata[code]'  ",1);
			if($osdata['cnt'] > 0) continue;
			$_sdata[item_price]=$sdata[ct_sell_price];		


			$multidata[0]=$_sdata;

			$div_cnt=1;	
			$is_traded=$sdata[trade_cnt];
			$is_traded_amt=$sdata[trade_amt]+$_traded_amt;
		}	

		//판매자 수수료 재검사
		if($seller_item_fee > 0 && !array_key_exists($sdata[smb_id],$enable_seller_fee)){

			$temp=sql_fetch("select sum(tr_seller_fee) tr_seller_fee from {$g5['cn_item_trade_test']} where fmb_id='$sdata[mb_id]' ");
			$enable_seller_fee[$sdata[smb_id]]=$sdata["ac_point_".$g5[cn_fee_coin]]-$temp[tr_seller_fee];
		}

		//판매자 수수료 재검사
		if($seller_item_fee > 0 && $enable_seller_fee[$sdata[smb_id]] < $seller_item_fee ){
					
			//continue;
		}

		//분할수량 업데이트
		if($sdata[code]!=''){
			$sql="update {$g5['cn_item_cart']} set div_cnt='$div_cnt' where code = '{$sdata[code]}'";
			sql_query($sql,1);	
		}

		
		//print_r($multidata);
		foreach($multidata as $data){		
			
			if($item_cnt_arr[$k] >= $qty) continue;

			//구매자 수수료
			if($_POST[fee_free]=='free') $item_fee=0;
			else $item_fee=$g5['cn_item'][$data[cn_item]][fee];


			//판매자 수수료
			if($_POST[seller_fee_free]=='free') $seller_item_fee=0;
			else $seller_item_fee=$g5['cn_item'][$data[cn_item]][fee];


			//거래코드 생성
			$code= get_tradecode();	

			//판매자 입금 주소
			$tr_wallet_addr=$data['mb_wallet_addr_'.$g5['cn_pay_coin']];		

			$sql="insert into {$g5['cn_item_trade_test']}
			set  
			tr_code = '$code',
			cart_code = '$sdata[code]',
			lg_no='$lg_no',
			cn_item = '$data[cn_item]',
			mb_id = '$mb_id',
			smb_id = '$smb_id',
			fmb_id = '$sdata[mb_id]',
			fsmb_id = '$sdata[smb_id]',

			ct_buy_price='$data[ct_buy_price]',
			ct_sell_price='$data[item_price]',

			tr_buyer_claim = '0',
			tr_seller_claim = '0',
			tr_buyer_dun = '0',
			tr_seller_dun = '0',
			tr_buyer_note = '',
			tr_seller_note = '',

			tr_price_org = '$data[item_price]',
			tr_price = '$data[item_price]',
			tr_discount = '$item_discount',
			tr_payback = '$item_payback',

			tr_price_cash = '$item_price_cash',
			tr_discount_cash = '$item_discount_cash',
			tr_payback_cash = '$item_payback_cash',

			tr_class = '1',
			tr_fee = '$item_fee',
			tr_seller_fee = '$seller_item_fee',
			tr_paytype = '$data[mb_trade_paytype]',

			tr_bank='$data[mb_bank]',
			tr_bank_num='$data[mb_bank_num]', 
			tr_bank_user='$data[mb_bank_user]',

			tr_wallet_addr = '$tr_wallet_addr',
			tr_balance = '0',
			tr_balance_last = '0',
			tr_txid = '',
			tr_stats = '1',
			tr_paydate = '',
			tr_distri='dr',

			tr_src='$tr_src',
			tr_wdate = now(),
			tr_rdate = now()	
			";
			
			//echo $sql;
			$result=sql_query($sql,1);


			$item_cnt_arr[$data[cn_item]]++;
			
			$is_traded++;	
			
			$tot_cnt++;
			
		}//foreach

	}//foreach	
}//while

alert($tot_cnt."건이 매칭되었습니다","./item_matching.direct.php");

