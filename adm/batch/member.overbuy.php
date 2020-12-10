<?php
include_once('./_common.php');

/* 맞는 가격대의 구매회원 풀 생성 */
$sql_common = " from {$g5['cn_sub_account']} as a 
left outer join  {$g5['member_table']} as b on(a.mb_id=b.mb_id)  
left outer join  (select smb_id,count(*) ct_cnt,sum(ct_buy_price) ct_buy_price  from {$g5['cn_item_cart']} where is_soled != '1' group by smb_id)  as c on(a.ac_id=c.smb_id) ";

$sql_search = " where  a.ac_active ='1' 
and exists (select * from {$g5['cn_item_cart']} where ct_wdate=date(now()) and  smb_id = a.ac_id )";


//해당 상품 오토 매칭 on 계정
//$sql_search .=" and ac_auto_".$current_item." = '1' ";

$sql = " select *  ,a.mb_id amb_id, a.ac_id aac_id
		{$sql_common} {$sql_search} group by a.ac_id order by a.ac_mc_priority desc,rand() $limit";
		$result = sql_query($sql,1);
		
	
		
$cnt=1;
while($row=sql_fetch_array($result)) {	
	
	
	$enablem=$row[mb_trade_amtlmt]-($row[ct_buy_price]?$row[ct_buy_price]:0);
	
	if($enablem*1 < 0) {

		echo $cnt.") ";


		if($enablem < 0) echo "<span style='color:red;'>";
		echo "
		구매자 {$row[aac_id]} @ {$row[amb_id]}=>
		가용금액 : <strong>$".number_format2($enablem) ."</strong> = ".  number_format2($row[mb_trade_amtlmt])." - ". number_format2($row[ct_buy_price]?$row[ct_buy_price]:0);
		echo '</span><br>';
		$cnt++;
	}

}