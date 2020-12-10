<?php
$sub_menu = "700700";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'w');
//check_admin_token();

//print_r($_POST);

$lines='';

//첫 상품 지급
if($current_item=='') alert_json(false,"지급할 {$g5[cn_item_name]}이 없습니다");

//지급 상품의 가격
$item_price=$_POST[item_price][$current_item];

//지급 상품의 할인율
$item_discount=$_POST[item_discount][$current_item];

//지급 상품의 페이백
$item_payback=$_POST[item_payback][$current_item];


//지급 상품의 가격(현금)
$item_price_cash=$_POST[item_price_cash][$current_item];

//지급 상품의 할인율(현금)
$item_discount_cash=$_POST[item_discount_cash][$current_item];

//지급 상품의 페이백(현금)
$item_payback_cash=$_POST[item_payback_cash][$current_item];
//$item_payback_cash=$item_price_cash * $item_payback_cash / 100  /100; //골드로

//지급 상품의 수수료
if($fee_free=='free') $item_fee=0;
else $item_fee=$item_fee[$current_item];

//지급 상품의 수량
$item_qty=$_POST[item_qty][$current_item];

//회원별 현재 가용금액
$enable_pay=array();

//회원별 현재 수수료액
$enable_fee=array();

//회원별 구매액
$enable_paid=array();


//쿠폰을 받을 기본 회원 목록
$sql_common = " from {$g5['cn_sub_account']} as a 
left outer join  {$g5['member_table']} as b on(a.mb_id=b.mb_id)  
left outer join  (select mb_id,count(*) ct_cnt,sum(ct_buy_price) ct_buy_price  from {$g5['cn_item_cart']}   where  is_soled != '1' and  ct_wdate > '$start_date' group by mb_id)  as c on(b.mb_id=b.mb_id) 
left outer join  (select mb_id,count(*) ct_cnt,sum(tr_price_org) tr_price_org  from {$g5['cn_item_trade']} where  tr_stats in ('1','2')  group by mb_id)  as t on(t.mb_id=b.mb_id) 
";

$sql_search = " where  a.ac_active ='1' ";

//패널티 없는 회원
$sql_search .= " and b.mb_trade_penalty=0 ";
		
		
//가용금액이 상품 가격보다 큰 회원
//$sql_search .=" and (b.mb_trade_amtlmt - if(c.ct_buy_price,c.ct_buy_price,0)  - if(t.tr_price_org,t.tr_price_org,0)  ) >= $item_price ";
$sql_search .=" and (b.mb_trade_amtlmt - if(c.ct_buy_price,c.ct_buy_price,0)  ) >= $item_price ";

//수수료가용금액이 상품 가격보다 큰 회원
if($item_fee > 0) $sql_search .=" and a.ac_point_".$g5[cn_fee_coin]." >= $item_fee ";

//해당 상품 오토 매칭 on 계정
$sql_search .=" and ac_auto_".$current_item." = '1' ";


//로그인 시간 제한시
if($login_day !='' && $login_day > 0) {
	$logdate=date("Y-m-d H:i:s",strtotime("- $login_day days"));
	$sql_search.=" and b.mb_today_login >= '$logdate' ";
}

//동일스톤 여부
if($posess_item=='same') $posess_sql=" and cn_item='$current_item'";
else  $posess_sql="";

//입금 계좌 정보가 있는 회원
$sql_search .=" and (
( b.mb_trade_paytype='both' and (	(b.mb_bank!='' and b.mb_bank_num!='' and b.mb_bank_user!='') || b.mb_wallet_addr_".$g5['cn_pay_coin']." !=''  ) )
or (  b.mb_trade_paytype='cash' and b.mb_bank!='' and b.mb_bank_num!='' and b.mb_bank_user!='' )
)";

//보유 회원 제외
if($posess_disable=='member'){	
	$sql_search .=" and  not exists  (select code from {$g5['cn_item_cart']} where mb_id=a.mb_id $posess_sql)";
}
//보유 계정 제외
if($posess_disable=='account'){	
	$sql_search .=" and  not exists  (select code from {$g5['cn_item_cart']} where smb_id=a.ac_id $posess_sql)";
}

//구매대기에서 매칭 제외 여부
$sql_search .=" and ac_mc_except!='1' ";

//제외될 아이디가 있는 경우
$except_id_str=preg_replace("/\n+/",",",$except_id);
$except_id_str=preg_replace("/\s+/","",$except_id_str);
$except_id_str=preg_replace("/,/","','",$except_id_str);
if($except_id_str!='' ) $sql_search .=" and a.mb_id not in ('$except_id_str') ";

//지급 수량이 있는 경우
if(!$item_qty) $limit=" limit 0 ";
else $limit='';

$sql = " select * ,a.mb_id amb_id, a.ac_id aac_id
{$sql_common} {$sql_search} group by a.ac_id order by a.ac_mc_priority desc,rand() $limit" ;
$result = sql_query($sql,1);

//echo $sql;

$tot_cnt=0;

if($_POST['w']=='x') {
sql_query("insert into {$g5['cn_item_log']}  set cn_item='$current_item',cn_item_name='".addslashes($g5[cn_item][$current_item][name_kr])."', mb_id='$member[mb_id]' ,lg_distri='hq',lg_wdate=now() ",1);
$lg_no=sql_insert_id();
}

$lines.='<p>================ '.$g5[cn_item][$current_item][name_kr] ." ".$g5['cn_item_name']." 매칭 시작 ". date('Y-m-d') ."================<p>";		

for ($i=0,$j=1; $row=sql_fetch_array($result); $i++,$j++) {
	
	//가용잔액
	if(!array_key_exists($row[amb_id],$enable_pay)){
		//$enable_pay[$row[amb_id]]=$row[mb_trade_amtlmt]-($row[ct_buy_price]?$row[ct_buy_price]:0) - ($row[tr_price_org]?$row[tr_price_org]:0);	
		//$enable_paid[$row[amb_id]]=($row[ct_buy_price]?$row[ct_buy_price]:0) + ($row[tr_price_org]?$row[tr_price_org]:0);	
		//설정잔
		$enable_pay[$row[amb_id]]=$row[mb_trade_amtlmt]-($row[ct_buy_price]?$row[ct_buy_price]:0);	
		$enable_paid[$row[amb_id]]=($row[ct_buy_price]?$row[ct_buy_price]:0);	
	}
	//수수료 잔
	if(!array_key_exists($row[aac_id],$enable_fee)){	
		$enable_fee[$row[aac_id]]=$row["ac_point_".$g5[cn_fee_coin]];
	}
	
	//가용금액 실검사
	if($enable_pay[$row[amb_id]] < $item_price){
		$lines.="<p> ERR. 구매자 : {$row[aac_id]} @ {$row[amb_id]} => ".$g5['cn_item'][$current_item][name_kr]." 매칭 / 가용금액  : <strong>$".number_format2($enable_pay[$row[amb_id]]) ."</strong> = ".  number_format2($row[mb_trade_amtlmt])." - ". number_format2($enable_paid[$row[amb_id]],2)." 부족 ----- SKIP " ;		
		$lines.="</p>";	
		continue;
	}
	
	//수수료 재검사
	if($enable_fee[$row[aac_id]] < $item_fee ){
		$lines.="<p> ERR. 구매자 : {$row[aac_id]} @ {$row[amb_id]} => ".$g5['cn_item'][$current_item][name_kr]." 매칭 / 매칭수수료 : <strong>{$enable_fee[$row[aac_id]]} {$g5[cn_cointype][$g5['cn_fee_coin']]}</strong> 부족 ----- SKIP " ;		
		$lines.="</p>";	
		continue;
	}
	
		
	//거래코드 생성
	$code= get_itemcode();	

	//1회용 입금 주소 발행
	if($_POST['w']=='x'){
		$tr_wallet_addr=align_mb_wallet($row[amb_id],$g5['cn_pay_coin']);
		if($tr_wallet_addr=='') alert_json(false,"입금지갑 주소를 가져올수 없습니다");
	}
	else  $tr_wallet_addr='';

	$sql="insert into {$g5['cn_item_trade']}
	set  
	tr_code = '$code',
	lg_no='$lg_no',
	cn_item = '$current_item',
	mb_id = '$row[amb_id]',
	smb_id = '$row[aac_id]',
	fmb_id = '$member[mb_id]',
	fsmb_id = '$member[mb_id]',
	tr_buyer_claim = '0',
	tr_seller_claim = '0',
	tr_buyer_dun = '0',
	tr_seller_dun = '0',
	tr_buyer_note = '',
	tr_seller_note = '',

	tr_price_org = '".$g5[cn_item][$current_item][price]."',
	tr_price = '$item_price',
	tr_discount = '$item_discount',
	tr_payback = '$item_payback',

	tr_price_cash = '$item_price_cash',
	tr_discount_cash = '$item_discount_cash',
	tr_payback_cash = '$item_payback_cash',

	tr_class = '1',
	tr_fee = '$item_fee',
	tr_paytype = '{$row[mb_trade_paytype]}',
	tr_wallet_addr = '$tr_wallet_addr',
	tr_balance = '0',
	tr_balance_last = '0',
	tr_txid = '',
	tr_stats = '1',
	tr_paydate = '',
	tr_distri='hq',
	tr_wdate = now(),
	tr_rdate = now()	
	";
	
	//echo $sql.'ee';
	if($_POST['w']=='x'){
	
		$_result=sql_query($sql,1);			
		if(!$_result)  alert_json(false,"작업을 진행 할 수 없습니다");
		
		if($item_fee !=0){
			//구매자 수수료 지출
			$content['link_no']=$code;				
			$content['pt_wallet']='free'; //지갑구
			$content['pt_coin']=$g5[cn_fee_coin]; //코인구분
			$content['amount']=$item_fee*-1;
			$content['subject']='매칭수수료';
			
			$mb[mb_id]=$row[amb_id];
			set_add_point('mfee',$mb,$row[aac_id],$member['mb_id'],$content);		
		}
	}

	
	$lines.="<p> ".($tot_cnt+1). ". 구매자 : {$row[aac_id]} @ {$row[amb_id]} => ".$g5['cn_item'][$current_item][name_kr]." 매칭 / 가용금액 : <strong>$".number_format2($enable_pay[$row[amb_id]],2) ."</strong> = ".  number_format2($row[mb_trade_amtlmt])." - ". number_format2($enable_paid[$row[amb_id]],2)." / 금액 :  <strong>$".number_format2($item_price)."</strong>" ;
	if($item_fee !=0) $lines.=" / 매칭수수료 : $item_fee {$g5[cn_cointype][$g5['cn_fee_coin']]} )";
	$lines.="</p>";	
	
	
	//현재 잔액 차감
	$enable_pay[$row[amb_id]]-=$item_price;
	$enable_paid[$row[amb_id]]+=$item_price;
	$enable_fee[$row[aac_id]]-=$item_fee;
		 	
	
	
	$tot_cnt++;
	$tot_amt+=$item_price;
	$tot_fee+=$item_fee;	
	
	
	//지정 수량 초과시 종
	if($item_qty <= $tot_cnt) break;
	
	
}

  $lines.=$g5['cn_item_name']." 매칭 종료 => 총수량 : ".number_format2($tot_cnt)."개 / 총금액 : ".number_format2($tot_amt).$g5[cn_cointype]['u']." / 총수수료 : ".number_format2($tot_fee).$g5[cn_cointype][$g5['cn_fee_coin']]."<br ><br >" ;	


  if($_POST['w']=='x')  sql_query("update {$g5['cn_item_log']}  set lg_cnt='$tot_cnt',lg_amt='$tot_amt', lg_fee='$tot_fee' ,lg_log='".addslashes($lines)."' where lg_no='$lg_no' ");



alert_json(true,'',array('htmls'=>$lines));
