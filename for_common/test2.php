<?php
include_once('./_common.php');
$start_date = '2020-10-01';
			
			/* 맞는 가격대의 매수 회원 찾기 */
			$sql_common = " from {$g5['cn_sub_account']} as a 
			left outer join  {$g5['member_table']} as b on(a.mb_id=b.mb_id)  
			left outer join  {$g5['cn_sub_account']} as s on (s.ac_id=b.mb_id)  
			left outer join  (select mb_id,count(*) ct_cnt,sum(ct_buy_price) ct_buy_price, sum(if(ct_validdate <= '$start_date' and cn_item='a' ,1,0)) ct_sell_cnt from {$g5['cn_item_cart']} 
			where  is_soled != '1' group by mb_id)  as c on(c.mb_id=b.mb_id) 
			left outer join  (select mb_id,count(*) tr_cnt,sum(tr_price_org) tr_price_org ,sum(tr_fee) tr_fee from {$g5['cn_item_trade_test']} where tr_wdate  >= '$start_date' group by mb_id )  as t on(t.mb_id=b.mb_id)
			left outer join  (select mb_id,count(*) as cnt from {$g5['cn_sub_account']} where ac_auto_a = '1' group by mb_id) as z on a.mb_id = z.mb_id
			";							

			$sql_search = " where b.mb_id='admin'";
			

echo "select z.cnt,a.* ,a.mb_id amb_id, a.ac_id aac_id, t.tr_cnt,t.tr_fee,
			s.ac_point_b sac_point_b,s.ac_point_e sac_point_e,s.ac_point_i sac_point_i,s.ac_point_u sac_point_u,
			(if(c.ct_buy_price,c.ct_buy_price,0) - if(t.tr_price_org,t.tr_price_org,0) ) enable_paid 

			{$sql_common} {$sql_search} {$login_sql} group by a.ac_id order by 			
			a.ac_mc_priority desc,
			if((select count(*) cnt from {$g5['cn_item_trade_test']} where smb_id=a.ac_id and cn_item='a') = 0,0,1) asc,
			rand() limit 1";


$sql = " select a.* ,a.mb_id amb_id, a.ac_id aac_id, t.tr_cnt,
			s.ac_point_b sac_point_b,s.ac_point_e sac_point_e,s.ac_point_i sac_point_i,s.ac_point_u sac_point_u,
			(if(c.ct_buy_price,c.ct_buy_price,0) - if(t.tr_price_org,t.tr_price_org,0) ) enable_paid 

			{$sql_common} {$sql_search} {$login_sql} group by a.ac_id 
			
			order by 
			if(
			if(z.cnt between '$amt_lmt_start1' and '$amt_lmt_end1',$amt_lmt_max1,
			if(z.cnt between '$amt_lmt_start2' and '$amt_lmt_end2',$amt_lmt_max2,
			if(z.cnt between '$amt_lmt_start3' and '$amt_lmt_end3',$amt_lmt_max3,			
			if(z.cnt between '$amt_lmt_start4' and '$amt_lmt_end4',$amt_lmt_max4,0)			
			)
			)
			) >  (if(t.tr_price_org,t.tr_price_org,0) + $data[item_price] )
			,1,0) desc,
			
			
			/*if((select count(*) cnt from {$g5['cn_item_trade_test']} where mb_id=a.mb_id ) = 0,0,1) asc*/
			(select count(*) cnt from {$g5['cn_item_trade_test']} where mb_id=a.mb_id ) asc
			, rand() limit 1";

?>
