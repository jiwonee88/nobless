<?php
$sub_menu = "700750";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'w');
//check_admin_token();

//print_r($_POST);

//exit;

$lines='';

//첫 상품 지급
if($current_item=='') alert_json(false,"매칭 할 {$g5[cn_item_name]}이 없습니다");

//완료구매자 배열
$_result=sql_query("CREATE TEMPORARY TABLE done_buyer(
       ac_id varchar(50),
       PRIMARY KEY ( ac_id )
    	) DEFAULT CHARSET=utf8	
	");

if(!$_result) alert_json(false,"작업을 진행 할 수 없습니다");

//구매자 수수료
if($_POST[fee_free]=='free') $item_fee=0;
else $item_fee=$g5['cn_item'][$current_item][fee];

//판매자 수수료
if($_POST[fee_free2]=='free') $item_fee2=0;
else $item_fee2=$g5['cn_item'][$current_item][fee];

$lines.='<p>================ '.$g5[cn_item][$current_item][name_kr] ." ".$g5['cn_item_name']." 매칭 시작  ".date('Y-m-d H:i:s')." ================<p>";		
//수수료 지급 가능 금액이 상품구매 수수료 보다 큰 회원

//if($item_fee > 0) $fee_sql_search =" and c.ac_point_".$g5[cn_fee_coin]." >= $item_fee ";
//else  $fee_sql_search='';


//if($item_fee2 > 0) $fee_sql_search2 =" and c.ac_point_".$g5[cn_fee_coin]." >= $item_fee2 ";
//else  $fee_sql_search2='';

//단일 회원 매칭시
if($target_mb_id){
	$target_mb=get_member($target_mb_id);
	$target_mb[ac_id]=$target_mb_id;
	$target_mb[aac_id]=$target_mb_id;
	$target_mb[amb_id]=$target_mb_id;
	if(!$target_mb[mb_id]) alert_json(false,"매칭할 단일회원 정보를 찾을수 없습니다");
}

//로그인 시간 제한시
if($login_day !='' && $login_day > 0) {
	$logdate=date("Y-m-d H:i:s",strtotime("- $login_day days"));
	$login_sql=" and b.mb_today_login >= '$logdate' ";
}else $login_sql='';

//지정 상품이 있는 경우
$target_code=preg_replace("/\n+/",",",$target_code);
$target_code=preg_replace("/\s+/","",$target_code);
$target_code=preg_replace("/,/","','",$target_code);
if($target_code!='' ) $target_code_search =" and a.code in ('$target_code') ";
else  $target_code_search='';


//지정일자 미거리
if($miss_date!=''){		
	
	$target_cnt=count(explode(",",$miss_date));
	$miss_date=preg_replace("/\n+/",",",$miss_date);
	$miss_date=preg_replace("/\s+/","",$miss_date);
	$miss_date=preg_replace("/,/","','",$miss_date);
	if($miss_date!='' ) $miss_date_search =" and tr_wdate in ('$miss_date') ";
	else  $miss_date_search='';
	
	$miss_date_search =" and (select count(tr_code) from {$g5['cn_item_trade']}  where cart_code=a.code  $miss_date_search and tr_stats in ('1','2','9') ) >= $target_cnt ";

}else $miss_date_search ='';


//미거래 판매 회원		
if($miss_cnt > 0){			
	$miss_date=date("Y-m-d",strtotime("-".($miss_cnt - 1)." days"));
	$miss_search =" and (select count(tr_code) from {$g5['cn_item_trade']}  where cart_code=a.code and tr_wdate >= '$miss_date' and tr_stats in ('1','2','9') ) >= $miss_cnt ";

	//$miss_sql=",(select count(tr_code) from {$g5['cn_item_trade']}  where smb_id=a.ac_id and tr_wdate='$start_date') as miss_cnt";

}else $miss_search='';

if($buyer_cnt) $sell_limit=" limit $buyer_cnt";
else $sell_limit ='';
/* 판매 대기중 상품 가격/결제 그룹 */
$sql = " select *,count(code) cnt
from {$g5['cn_item_cart']} as a 
left outer join  {$g5['member_table']} as b on(a.mb_id=b.mb_id)  
left outer join  {$g5['cn_sub_account']} as c on(c.ac_id=a.smb_id)  
where a.cn_item ='$current_item' and a.is_soled != '1'  and a.is_trade != '1' and a.ct_validdate <= '$start_date' and b.mb_trade_penalty=0 $login_sql  $target_code_search $fee_sql_search2 $miss_search $miss_date_search group by b.mb_trade_paytype, a.ct_sell_price order by ct_sell_price desc";
$re = sql_query($sql,1);

$tot_cnt=0;

if($_POST['w']=='x') {
sql_query("insert into {$g5['cn_item_log']}  set cn_item='$current_item',cn_item_name='".addslashes($g5[cn_item][$current_item][name_kr])."', mb_id='$member[mb_id]' ,lg_distri='p2p',lg_wdate=now() ",1);
$lg_no=sql_insert_id();
}

//구매 회원별 현재 가용금액
$enable_pay=array();

//구매 회원별 현재 수수료액
$enable_fee=array();

//회원별 구매액
$enable_paid=array();

//판매 회원별 현재 수수료액
$enable_fee2=array();

$member_match=array();

while($_d=sql_fetch_array($re)){

	$member_ary=array();
	
	//지급 상품의 가격
	$item_price=$_d[ct_sell_price];		
	
	//최대 가격 초과시 상품 가격쪼개기	
	if($_d[ct_sell_price] > $g5['cn_item'][$current_item][mxprice]){
	
		$item_price=floor($_d[ct_sell_price]/3*10)/10;
	
	}
	
	
	if(!$target_mb[mb_id]){
	
		/* 맞는 가격대의 구매회원 풀 생성 */
		$sql_common = " from {$g5['cn_sub_account']} as a 
		left outer join  {$g5['member_table']} as b on(a.mb_id=b.mb_id)  
		left outer join  (select mb_id,count(*) ct_cnt,sum(ct_buy_price) ct_buy_price  from {$g5['cn_item_cart']} where  is_soled != '1' group by mb_id)  as c on(c.mb_id=b.mb_id) 
		left outer join  (select mb_id,count(*) tr_cnt,sum(tr_price_org) tr_price_org  from {$g5['cn_item_trade']} where  tr_stats in ('1','2')  group by mb_id)  as t on(t.mb_id=b.mb_id) 				
		";		

		$sql_search = " where  a.ac_active ='1' ";
		
		//패널티 없는 회원
		$sql_search .= " and b.mb_trade_penalty=0 ";		
		
		//해당 상품 오토 매칭 on 계정
		$sql_search .=" and ac_auto_".$current_item." = '1' ";
		
		//로그인 시간 제한시
		if($login_day !='' && $login_day > 0) {
			$logdate=date("Y-m-d H:i:s",strtotime("- $login_day days"));
			$sql_search.=" and b.mb_today_login >= '$logdate' ";
		}

		//구매대기에서 매칭 제외 여부
		$sql_search .=" and ac_mc_except!='1' ";

		//매칭 완료자 제외
		$sql_search .=" and  not exists  (select ac_id from  done_buyer where ac_id=a.ac_id )";

		//결제 방식의 차이 - 현금일 경우만 구분
		if($_d[mb_trade_paytype]=='cash'){
			$sql_search .=" and (b.mb_trade_paytype='cash' or b.mb_trade_paytype='both') ";
		}
		if($_d[mb_trade_paytype]=='usdt'){
			$sql_search .=" and (b.mb_trade_paytype='usdt'  or b.mb_trade_paytype='both')  ";
		}
		//매칭 횟수 제한이 있는경우
		if($match_cnt > 0){
			$sql_search .=" and (select count(tr_code) cnt from {$g5['cn_item_trade']}  where smb_id=a.ac_id and tr_wdate='$start_date' ) < $match_cnt";
			
			$select_sql=",(select count(tr_code) from {$g5['cn_item_trade']}  where smb_id=a.ac_id and tr_wdate='$start_date') as  match_cnt";

		}else $select_sql='';

		//가용금액이 상품 가격보다 큰 회원
		$sql_search .=" and (b.mb_trade_amtlmt - if(c.ct_buy_price,c.ct_buy_price,0)  - if(t.tr_price_org,t.tr_price_org,0)  ) >= $item_price ";

		//구매자 수수료 지급 가능 금액이 상품구매 수수료 보다 큰 회원
		if($item_fee > 0) $sql_search .=" and a.ac_point_".$g5[cn_fee_coin]." >= $item_fee ";
	
		//입금 계좌 정보가 있는 회원
		$sql_search .=" and (
		( b.mb_trade_paytype='both' and (	(b.mb_bank!='' and b.mb_bank_num!='' and b.mb_bank_user!='') or b.mb_wallet_addr_".$g5['cn_pay_coin']." !=''  ) )
		or ( b.mb_trade_paytype='usdt' and b.mb_wallet_addr_".$g5['cn_pay_coin']." !=''  )
		or (  b.mb_trade_paytype='cash' and b.mb_bank!='' and b.mb_bank_num!='' and b.mb_bank_user!='' )
		)";

		//동일스톤 여부
		if($posess_item=='same') $posess_sql=" and cn_item='$current_item'";
		else  $posess_sql="";

		//보유 회원 제외
		if($posess_disable=='member'){	
			$sql_search .=" and  not exists  (select code from {$g5['cn_item_cart']} where is_soled!='1' and mb_id=a.mb_id $posess_sql)";
		}
		//보유 계정 제외
		if($posess_disable=='account'){	
			$sql_search .=" and  not exists  (select code from {$g5['cn_item_cart']} where  is_soled!='1' and smb_id=a.ac_id $posess_sql)";
		}

		//제외될 아이디가 있는 경우
		$except_id_str=preg_replace("/\n+/",",",$except_id);
		$except_id_str=preg_replace("/\s+/","",$except_id_str);
		$except_id_str=preg_replace("/,/","','",$except_id_str);
		if($except_id_str!='' ) $sql_search .=" and a.mb_id not in ('$except_id_str') ";

		//지급 수량이 없는 경우
		if(!$_d[cnt]) $limit=" limit 0";
		
		//한번에 가져올 구매자목록수
		else if($buyer_cnt) $limit=" limit $buyer_cnt";
		else $limit='';

		$sql = " select *  ,a.mb_id amb_id, a.ac_id aac_id
		
		{$sql_common} {$sql_search} {$login_sql} group by a.ac_id order by a.ac_mc_priority desc,rand() $limit";
		$result = sql_query($sql,1);
		
		for ($i=0,$j=1; $row=sql_fetch_array($result); $i++,$j++) {
			$member_ary[]=$row;			
			
			//가용잔액
			if(!array_key_exists($row[amb_id],$enable_pay)){
				$enable_pay[$row[amb_id]]=$row[mb_trade_amtlmt]-($row[ct_buy_price]?$row[ct_buy_price]:0) - ($row[tr_price_org]?$row[tr_price_org]:0);	
				$enable_paid[$row[amb_id]]=($row[ct_buy_price]?$row[ct_buy_price]:0) + ($row[tr_price_org]?$row[tr_price_org]:0);	
			}
			//수수료 잔
			if(!array_key_exists($row[aac_id],$enable_fee)){	
				$enable_fee[$row[aac_id]]=$row["ac_point_".$g5[cn_fee_coin]];
			}
			
			//계정당 매칭 횟수
			if(!array_key_exists($row[aac_id],$member_match)){	
				$member_match[$row[aac_id]]=$row[match_cnt]?$row[match_cnt]:0;
			}

		}		
		
	}//if(!$target_mb[mb_id]){
	else{
		
		$smb= get_submember($target_mb[mb_id]);
		$temp=sql_fetch("select count(*) ct_cnt,sum(ct_buy_price) ct_buy_price  from {$g5['cn_item_cart']} where   mb_id='{$target_mb['mb_id']}' and is_soled != '1' ",1); 
		$temp2=sql_fetch("select mb_id,count(*) tr_cnt,sum(tr_price_org) tr_price_org  from {$g5['cn_item_trade']} where   mb_id='{$target_mb['mb_id']}' and tr_stats in ('1','2')  ",1); 
		$temp3=sql_fetch("select count(tr_code) match_cnt from {$g5['cn_item_trade']}  where smb_id='{$target_mb[mb_id]}'  and tr_wdate='$start_date' ",1); 
		
		//보유 회원 제외
		if($posess_disable=='member'){	
			$temp3=sql_fetch("select count(tr_code) match_cnt from {$g5['cn_item_trade']}  where mb_id='{$target_mb[mb_id]}'  and tr_wdate='$start_date' ",1); 
		}
		//보유 계정 제외
		if($posess_disable=='account'){	
			$temp3=sql_fetch("select count(tr_code) match_cnt from {$g5['cn_item_trade']}  where smb_id='{$target_mb[mb_id]}'  and tr_wdate='$start_date' ",1); 
		}
		
		//가용잔액		
		$enable_pay[$target_mb[mb_id]]=$target_mb[mb_trade_amtlmt] - ($temp[ct_buy_price]?$temp[ct_buy_price]:0) - ($temp2[tr_price_org]?$temp2[tr_price_org]:0);	
		$enable_paid[$target_mb[mb_id]]=($temp[ct_buy_price]?$temp[ct_buy_price]:0) + ($temp2[tr_price_org]?$temp2[tr_price_org]:0);	
		
		//수수료 잔액
		$enable_fee[$target_mb[mb_id]]=$smb["ac_point_".$g5[cn_fee_coin]];

		//계정당 매칭 횟수
		$member_match[$target_mb[mb_id]]=$temp3[match_cnt]?$temp3[match_cnt]:0;

	}

/* 판매 대기중 상품 가격 그룹 */
$sql = " select a.*,b.*,c.ac_point_b,c.ac_point_e,c.ac_point_i,c.ac_point_u
from {$g5['cn_item_cart']} as a 
left outer join  {$g5['member_table']} as b on(a.mb_id=b.mb_id)  
left outer join  {$g5['cn_sub_account']} as c on(c.ac_id=a.smb_id) 
where cn_item ='$current_item' and is_soled != '1' and is_trade!='1' and ct_validdate <= '$start_date' $target_code_search $fee_sql_search2 $miss_search $miss_date_search and b.mb_trade_paytype='{$_d[mb_trade_paytype]}' and ct_sell_price='{$_d['ct_sell_price']}' order by  ct_validdate asc  $sell_limit";

//echo $sql;
$re2 = sql_query($sql,1);


$total=sql_num_rows($re2);

//echo $sql;		
$lines.='<p>================ '.$g5[cn_item][$current_item][name_kr] ." ".$g5['cn_item_name']." 매칭 시작 / 상품가격 : {$_d[ct_sell_price]}  / 결제방식 :  {$_d[mb_trade_paytype]} ($total) ================<p>";		

while($sdata=sql_fetch_array($re2)){	

	$multidata=array();
	
	//상품 가격쪼개기	
	if($sdata[ct_sell_price] > $g5['cn_item'][$current_item][mxprice]){
		
		for($x=$sdata[div_cnt]-$sdata[trade_cnt]; $x > 0;$x--){
			$multidata[]=$sdata;
		}	
		
		$div_cnt=3;
		$is_traded=$sdata[trade_cnt];
				
	}else{
		$multidata[0]=$sdata;
		$div_cnt=1;	
		$is_traded=0;
	}
	
	$sql="update {$g5['cn_item_cart']} set div_cnt='$div_cnt' where code = '{$sdata[code]}'";
	sql_query($sql);
				
	foreach($multidata as $data){			
		
		//판매자 수수료 재검사
		if(!array_key_exists($data[smb_id],$enable_fee2)){	
			$enable_fee2[$data[smb_id]]=$data["ac_point_".$g5[cn_fee_coin]];
		}
			
		//판매자 수수료 재검사
		if($item_fee2 && $enable_fee2[$data[smb_id]] < $item_fee2 ){
			$lines.="<p class='fred'>".($tot_cnt+1)."  ERR. 판매자 : {$data[smb_id]} @ {$data[mb_id]} => ".$g5['cn_item'][$current_item][name_kr]." 매칭 / 매칭수수료 : <strong>".number_format2($enable_fee2[$data[smb_id]])." {$g5[cn_cointype][$g5['cn_fee_coin']]}</strong> 부족  ----- SKIP " ;		
			$lines.="</p>";	
			continue;
		}

		//물건 사는 사람 정보
		if($target_mb[mb_id]) $row=$target_mb;
		else $row=array_shift($member_ary);						
		
		while(1){		
		
			//구매자 가용금액 실검사
			if($enable_pay[$row[amb_id]] < $item_price){
				$lines.="<p class='fred'>".($tot_cnt+1)."  ERR. 구매자 : {$row[aac_id]} @ {$row[amb_id]} =>  판매자 : {$data[smb_id]} @ {$data[mb_id]} =>  ".$g5['cn_item'][$current_item][name_kr]." 매칭 / 가용금액  : <strong>$".number_format2($enable_pay[$row[amb_id]]) ."</strong> = ".  number_format2($row[mb_trade_amtlmt])." - ". number_format2($enable_paid[$row[amb_id]],2)." 부족 ----- SKIP " ;		
				$lines.="</p>";	
				
				//구매자 지정의 경우
				if($target_mb[mb_id]) {
					$lines.="<p> ERR. 구매자가 더이상 없습니다 ----- SKIP " ;		
					$lines.="</p>";	
					break 2;
				}
				
				//다음 구매자 시프트
				$row=array_shift($member_ary);	

				if($row[amb_id]=='') break;
			}else break;
		}

		while(1){
			//구매자 수수료 재검사
			if($item_fee && $enable_fee[$row[aac_id]] < $item_fee ){
				$lines.="<p  class='fred'>".($tot_cnt+1)."  ERR. 구매자 : {$row[aac_id]} @ {$row[amb_id]} => ".$g5['cn_item'][$current_item][name_kr]." 매칭 / 매칭수수료 : <strong>{$enable_fee[$row[aac_id]]} {$g5[cn_cointype][$g5['cn_fee_coin']]}</strong> 부족 ----- SKIP " ;		
				$lines.="</p>";	
					
				//구매자 지정의 경우
				if($target_mb[mb_id]) {
					$lines.="<p> ERR. 구매자가 더이상 없습니다 ----- SKIP " ;		
					$lines.="</p>";	
					break 2;
				}				
				
				//다음 구매자 시프트
				$row=array_shift($member_ary);	

				if($row[amb_id]=='') break;
			}else break;
		}
		
		//판매자 구매자 동일할 경우 구매자 재매칭		
		while($data[mb_id] && $data[mb_id]==$row[amb_id]){
			$lines.="<p  class='fred'>".($tot_cnt+1)."  ERR. 구매자 : {$row[aac_id]} @ {$row[amb_id]} / 판매자 :{$data[smb_id]} @ {$data[mb_id]} 판매자 구매자 동일 ----- SKIP " ;		
			$lines.="</p>";	
			
			//구매자 지정의 경우
			if($target_mb[mb_id]) {
				$lines.="<p> ERR. 구매자가 더이상 없습니다 ----- SKIP " ;		
				$lines.="</p>";	
				break 2;
			}				

			//다음 구매자 시프트
			$row=array_shift($member_ary);	
			if($row[amb_id]=='') break;
		}
		
		
		//매칭 횟수 초과
		while($match_cnt > 0){		
			//구매자 수수료 재검사
			if($member_match[$row[aac_id]]  >= $match_cnt ){
				$lines.="<p  class='fred'>".($tot_cnt+1)."  ERR. 구매자 : {$row[aac_id]} @ {$row[amb_id]} / 판매자 :{$data[smb_id]} @ {$data[mb_id]} {$member_match[$row[aac_id]]} 매칭횟수 도달 ----- SKIP " ;		
				$lines.="</p>";

				//구매자 지정의 경우
				if($target_mb[mb_id]) {
					$lines.="<p> ERR. 구매자가 더이상 없습니다 ----- SKIP " ;		
					$lines.="</p>";	
					break 2;
				}				

				//다음 구매자 시프트
				$row=array_shift($member_ary);	

				if($row[amb_id]=='') break;
				
			}else break;
		}
		
		
		
		//구매자 리스팅 종료시
		if($row[amb_id]=='') {
			$lines.="<p> ERR. 구매자가 더이상 없습니다 ----- SKIP " ;		
			$lines.="</p>";	
			break 2;
		}
		
		//거래코드 생성
		$code= get_itemcode();	

		//판매자 입금 주소
		$tr_wallet_addr=$data['mb_wallet_addr_'.$g5['cn_pay_coin']];		

		$sql="insert into {$g5['cn_item_trade']}
		set  
		tr_code = '$code',
		cart_code = '$data[code]',
		lg_no='$lg_no',
		cn_item = '$current_item',
		mb_id = '$row[amb_id]',
		smb_id = '$row[aac_id]',
		fmb_id = '$data[mb_id]',
		fsmb_id = '$data[smb_id]',
		tr_buyer_claim = '0',
		tr_seller_claim = '0',
		tr_buyer_dun = '0',
		tr_seller_dun = '0',
		tr_buyer_note = '',
		tr_seller_note = '',
		
		tr_price_org = '$item_price',
		tr_price = '$item_price',
		tr_discount = '$item_discount',
		tr_payback = '$item_payback',

		tr_price_cash = '$item_price_cash',
		tr_discount_cash = '$item_discount_cash',
		tr_payback_cash = '$item_payback_cash',

		tr_class = '1',
		tr_fee = '$item_fee',
		tr_seller_fee = '$item_fee2',
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
		tr_distri='p2p',
		tr_wdate = now(),
		tr_rdate = now()	
		";
		
		//판매 카운터
		$is_traded++;

		
		//echo $sql.'ee';
		if($_POST['w']=='x'){

			$_result=sql_query($sql,1);	

			if(!$_result)  alert_json(false,"작업을 진행 할 수 없습니다");

			if($item_fee2 !=0 ){
				
				//판매자 수수료 지출
				$content['link_no']=$code;				
				$content['pt_wallet']='free'; //지갑구
				$content['pt_coin']=$g5[cn_fee_coin]; //코인구분
				$content['amount']=$item_fee2 * -1;
				$content['subject']='매칭수수료';
				
				$mb[mb_id]=$data[mb_id];
				set_add_point('mfee2',$data,$data[ac_id],$member['mb_id'],$content);		
				
			}
			
			if($item_fee !=0 ){			
				//구매자 수수료 지출
				$content['link_no']=$code;				
				$content['pt_wallet']='free'; //지갑구
				$content['pt_coin']=$g5[cn_fee_coin]; //코인구분
				$content['amount']=$item_fee * -1;
				$content['subject']='매칭수수료';
				
				$mb[mb_id]=$row[amb_id];
				set_add_point('mfee',$mb,$row[aac_id],$member['mb_id'],$content);
			}
			
			$sql="update {$g5['cn_item_cart']} set trade_cnt=trade_cnt+1 ,is_trade=if(trade_cnt >= div_cnt,1,if(trade_cnt > 0,2,is_trade))  where code='{$data[code]}'" ;
			$_result=sql_query($sql,1);	
			
			if(!$_result)  alert_json(false,"작업을 진행 할 수 없습니다");
			
		}//if($_POST['w']=='x'){
		
		if(!$target_mb[mb_id]){
			//매치완료 저장
			$_result=sql_query("insert into done_buyer set ac_id='{$row[aac_id]}' ");
			if(!$_result) alert_json(false,"작업을 진행 할 수 없습니다");
		}
		
		
		$lines.="<p>".($tot_cnt+1)."구매자 (".($member_match[$row[aac_id]]+1).") 회차 : {$row[aac_id]} @ {$row[amb_id]}  => ".$g5['cn_item'][$current_item][name_kr]." /  판매자 : {$data[smb_id]} @ {$data[mb_id]} ".($div_cnt>1?"<span class='fblue'>({$is_traded}/{$div_cnt} 등분)</span>":"")." / 가용금액 : <strong>$".number_format2($enable_pay[$row[amb_id]]) ."</strong> = ".  number_format2($row[mb_trade_amtlmt])." - ". number_format2($enable_paid[$row[amb_id]])." /  상품금액 :  <strong>$".number_format2($item_price)."</strong>";
		$lines.="/ $data[code] &gt; $code";
		if($tr_wallet_addr) $lines.=" / 지갑주소 : $tr_wallet_addr";		
		if($data[mb_bank_num]) $lines.=" / 계좌정보 : $data[mb_bank] $data[mb_bank_num] $data[mb_bank_user]";		
		
		$lines.=" / 매칭수수료 : $item_fee {$g5[cn_cointype][$g5['cn_fee_coin']]} ";
		$lines.=" / {$g5[cn_cointype][$g5['cn_fee_coin']]}잔액 :  ".number_format2($row["ac_point_".$g5[cn_fee_coin]]-$item_fee,2);
		$lines.="</p>";
		
		
		//구매자 현재 잔액 차감
		$enable_pay[$row[amb_id]]-=$item_price;
		$enable_paid[$row[amb_id]]+=$item_price;
		$enable_fee[$row[aac_id]]-=$item_fee;
		
		//판매자 현재 잔액 차감
		$enable_fee2[$data[smb_id]]-=$item_fee;
		
		//맷칭 횟수
		$member_match[$row[aac_id]]++;
		
		$tot_cnt++;
		$tot_amt+=$item_price;
		$tot_fee+=$item_fee;	

		//지정 수량 초과시 종
		//if($_d[cnt] <= $tot_cnt) break;

		//usleep(1);
	
	}//foreach
	
}//while

}//while

  $lines.= $g5[cn_item][$current_item][name_kr] ." ".$g5['cn_item_name']." 매칭 종료 => 총수량 : ".number_format2($tot_cnt)."개 / 총금액 : ".number_format2($tot_amt).$g5[cn_cointype]['u']." / 총수수료 : ".number_format2($tot_fee).$g5[cn_cointype][$g5['cn_fee_coin']]."<br ><br >" ;	


 if($_POST['w']=='x')  sql_query("update {$g5['cn_item_log']}  set lg_cnt='$tot_cnt',lg_amt='$tot_amt', lg_fee='$tot_fee' ,lg_log='".addslashes($lines)."' where lg_no='$lg_no' ");

	

alert_json(true,'',array('htmls'=>$lines));
