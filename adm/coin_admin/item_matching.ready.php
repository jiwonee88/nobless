 <?php
$sub_menu = "700710";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'w');

$html_title = $g5[cn_item_name].'-매칭대기현황';


$g5['title'] = $html_title;
include_once(G5_ADMIN_PATH.'/admin.head.php');


?>

<section id="anc_rt_basic">


<h2 class="h2_frm">판매/구매 대기 </h2>
 
 <?
 $sell=array();
 $tot_cnt=$tot_buy_price=$tot_sell_price=0;
 $re=sql_query( "select *,sum(ct_buy_price) buy_price,sum(if(cn_item='e',ct_sell_price-trade_amt,ct_sell_price)) sell_price,  count(code) cnt from {$g5['cn_item_cart']} where is_soled!='1' and is_trade!='1'  and ( ct_validdate <= date(now()) or cn_item='e')  group by cn_item ",1);
 while($data=sql_fetch_array($re)){
 	$sell[$data[cn_item]]=$data; 
	
	$tot_cnt+=$data[cnt];
	$tot_buy_price+=$data[buy_price];
	$tot_sell_price+=$data[sell_price];
 }
 
 $tomorrow=date("Y-m-d",strtotime("+1 days"));
 $sell2=array();
 $tot_cnt2=$tot_buy_price2=$tot_sell_price2=0;
 $re=sql_query( "select *,sum(ct_buy_price) buy_price,sum(if(cn_item='e',ct_sell_price-trade_amt,ct_sell_price)) sell_price,  count(code) cnt from {$g5['cn_item_cart']} where is_soled!='1' and is_trade!='1'  and (ct_validdate = '$tomorrow'  or cn_item='e') group by cn_item ",1);
 while($data=sql_fetch_array($re)){
 	$sell2[$data[cn_item]]=$data; 
	
	$tot_cnt2+=$data[cnt];
	$tot_buy_price2+=$data[buy_price];
	$tot_sell_price2+=$data[sell_price];
 }
 
 $third=date("Y-m-d",strtotime("+2 days"));
 $sell3=array();
 $tot_cnt3=$tot_buy_price3=$tot_sell_price3=0;
 $re=sql_query( "select *,sum(ct_buy_price) buy_price,sum(if(cn_item='e',ct_sell_price-trade_amt,ct_sell_price)) sell_price,  count(code) cnt from {$g5['cn_item_cart']} where is_soled!='1' and is_trade!='1'  and (ct_validdate = '$third'  or cn_item='e') group by cn_item ",1);
 while($data=sql_fetch_array($re)){
 	$sell3[$data[cn_item]]=$data; 
	
	$tot_cnt3+=$data[cnt];
	$tot_buy_price3+=$data[buy_price];
	$tot_sell_price3+=$data[sell_price];
 }


 $fourth=date("Y-m-d",strtotime("+3 days"));
 $sell4=array();
 $tot_cnt3=$tot_buy_price4=$tot_sell_price4=0;
 $re=sql_query( "select *,sum(ct_buy_price) buy_price,sum(if(cn_item='e',ct_sell_price-trade_amt,ct_sell_price)) sell_price,  count(code) cnt from {$g5['cn_item_cart']} where is_soled!='1' and is_trade!='1'  and (ct_validdate = '$fourth'  or cn_item='e') group by cn_item ",1);
 while($data=sql_fetch_array($re)){
 	$sell4[$data[cn_item]]=$data; 
	
	$tot_cnt4+=$data[cnt];
	$tot_buy_price4+=$data[buy_price];
	$tot_sell_price4+=$data[sell_price];
 }



 $fifth=date("Y-m-d",strtotime("+5 days"));
 $sell5=array();
 $tot_cnt3=$tot_buy_price5=$tot_sell_price5=0;
 $re=sql_query( "select *,sum(ct_buy_price) buy_price,sum(if(cn_item='e',ct_sell_price-trade_amt,ct_sell_price)) sell_price,  count(code) cnt from {$g5['cn_item_cart']} where is_soled!='1' and is_trade!='1'  and (ct_validdate = '$fifth'  or cn_item='e') group by cn_item ",1);
 while($data=sql_fetch_array($re)){
 	$sell5[$data[cn_item]]=$data; 
	
	$tot_cnt5+=$data[cnt];
	$tot_buy_price5+=$data[buy_price];
	$tot_sell_price5+=$data[sell_price];
 }

 
?>
 <div class="tbl_head01 tbl_wrap">

 <table  >
<thead>
<tr>
<th width="130" rowspan="2" nowrap="nowrap" scope="row">구분</th>

<?php
$cn_item_arr=array_reverse($g5['cn_item']);
foreach($cn_item_arr as $k=> $v) {    

?>
<th colspan="3" nowrap="nowrap" class='text-center' ><?=$v[name_kr]?> [<?=number_format($v[price])?>]</th>
<? }?>
<th rowspan="2" nowrap="nowrap">총수량</th>
<th rowspan="2" nowrap="nowrap">총구매액</th>
<th rowspan="2" nowrap="nowrap">총예정판매액</th>
</tr>

<tr>

<?
foreach($cn_item_arr as $k=> $v) {?>
<th nowrap="nowrap">수량</th>
<th nowrap="nowrap">구매액</th>
<th nowrap="nowrap">예정판매액</th>
<? }?>
</tr>
</thead>

<tbody>
<tr>
<td width="130" scope="row">판매대기(오늘)</td>

<?php
foreach($cn_item_arr as $k=> $v) {    
?>
<td><?=number_format2($sell[$k][cnt]*1.0, 0)?></td>
<td><?=number_format2($sell[$k][buy_price], 0)?></td>
<td><?=number_format2($sell[$k][sell_price], 0)?></td>

<? }?>
<td><?=number_format2($tot_cnt*1.0, 0)?></td>
<td><?=number_format2($tot_buy_price, 0)?></td>
<td><?=number_format2($tot_sell_price, 0)?></td>

</tr>



<tr>
<td scope="row">구매대기<br>
(활성조건)</td>
<?php
$tot_buy_cnt=0;
foreach($cn_item_arr as $k=> $v) {    

//기본 회원 목록
$sql_common = " from {$g5['cn_sub_account']} as a 
left outer join  {$g5['member_table']} as b on(a.mb_id=b.mb_id)  
left outer join  {$g5['cn_sub_account']} as s on(s.ac_id=b.mb_id) 
left outer join  (select mb_id,count(*) ct_cnt,sum(ct_buy_price) ct_buy_price  from {$g5['cn_item_cart']} group by mb_id)  as c on(c.mb_id=b.mb_id) 
left outer join  (select mb_id,count(*) ct_cnt,sum(tr_price_org) tr_price_org  from {$g5['cn_item_trade']} where  tr_stats in ('1','2')  group by mb_id)  as t on(t.mb_id=b.mb_id) ";

$sql_search = " where  a.ac_active ='1' ";

//수수료 수량
//$sql_search .=' and ';

//가용금액이 상품 가격보다 큰 회원
//$sql_search .=" and (b.mb_trade_amtlmt - if(c.ct_buy_price,c.ct_buy_price,0)  - if(t.tr_price_org,t.tr_price_org,0) ) >= $v[price] ";

//수수료가용금액이 상품 가격보다 큰 회원
$sql_search .=" and s.ac_point_".$g5[cn_fee_coin]." >=  $v[fee] ";

//해당 상품 오토 매칭 on 계정
if($k!='e') $sql_search .=" and a.ac_auto_".$k." = '1' ";


$sql = " select sum( if( (b.mb_trade_amtlmt - if(c.ct_buy_price,c.ct_buy_price,0)  - if(t.tr_price_org,t.tr_price_org,0) ) >= $v[price],1,0) ) as  min_cnt, count(distinct a.ac_id) as max_cnt
{$sql_common} {$sql_search} ";
$counts = sql_fetch($sql,1);

//echo $sql."<br>";
$buy_cnt[$k]=$counts['max_cnt'];
$buy_cnt_min[$k]=$counts['min_cnt'];
$tot_buy_cnt+=$buy_cnt[$k];
?>
<td><strong>
<?=number_format2($buy_cnt[$k])?>
</strong></td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<? }?>
<td><strong>
<?=number_format2($tot_buy_cnt)?>
</strong></td>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>
<!--tr>
<td scope="row">구매대기<br>
(활성 및 가용금액)</td>
<?php
$tot_buy_cnt=0;
foreach($cn_item_arr as $k=> $v) {    

?>
<td><strong>
<?=number_format2($buy_cnt_min[$k])?>
</strong></td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<? }?>
<td><strong>
<?=number_format2($tot_buy_cnt)?>
</strong></td>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr-->
<tr>
<td scope="row">최대가능매칭</td>
<?php
foreach($cn_item_arr as $k=> $v) {  ?>
<td><strong>
<?=number_format2(($sell[$k][cnt])/($buy_cnt[$k])*100,0)?>
</strong>%</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<? }?>
<td><strong>
<?=number_format2($tot_cnt/$tot_buy_cnt*100,1)?>
</strong>%</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>


<tr style='border-top:2px solid #aaa;'>
<td width="130" scope="row">판매대기(+1 days)</td>

<?php
foreach($cn_item_arr as $k=> $v) {    
?>
<td><?=number_format2($sell2[$k][cnt], 0)?></td>
<td><?=number_format2($sell2[$k][buy_price])?></td>
<td><?=number_format2($sell2[$k][sell_price])?></td>

<? }?>
<td><?=number_format2($tot_cnt2, 0)?></td>
<td><?=number_format2($tot_buy_price2)?></td>
<td><?=number_format2($tot_sell_price2)?></td>

</tr>


<tr>
<td width="130" scope="row">판매대기(+2 days)</td>

<?php
foreach($cn_item_arr as $k=> $v) {    
?>
<td><?=number_format2($sell3[$k][cnt], 0)?></td>
<td><?=number_format2($sell3[$k][buy_price])?></td>
<td><?=number_format2($sell3[$k][sell_price])?></td>

<? }?>
<td><?=number_format2($tot_cnt, 0)?></td>
<td><?=number_format2($tot_buy_price3)?></td>
<td><?=number_format2($tot_sell_price3)?></td>

</tr>

<tr>
<td width="130" scope="row">판매대기(+3 days)</td>

<?php
foreach($cn_item_arr as $k=> $v) {    
?>
<td><?=number_format2($sell4[$k][cnt], 0)?></td>
<td><?=number_format2($sell4[$k][buy_price])?></td>
<td><?=number_format2($sell4[$k][sell_price])?></td>

<? }?>
<td><?=number_format2($tot_cnt, 0)?></td>
<td><?=number_format2($tot_buy_price4)?></td>
<td><?=number_format2($tot_sell_price4)?></td>

</tr>

<tr>
<td width="130" scope="row">판매대기(+4 days)</td>

<?php
foreach($cn_item_arr as $k=> $v) {    
?>
<td><?=number_format2($sell5[$k][cnt], 0)?></td>
<td><?=number_format2($sell5[$k][buy_price])?></td>
<td><?=number_format2($sell5[$k][sell_price])?></td>

<? }?>
<td><?=number_format2($tot_cnt, 0)?></td>
<td><?=number_format2($tot_buy_price5)?></td>
<td><?=number_format2($tot_sell_price5)?></td>

</tr>



</table>
 
 </div>


    
</section>


<?php
include_once(G5_ADMIN_PATH.'/admin.tail.php');
?>
