<?php
$sub_menu = "700860";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');

if($date_stx=='') $date_stx='tr_setdate';

if($date_start_stx=='')  $date_start_stx=date("Y-m-d",strtotime('-1 months'));
if($date_end_stx=='')  $date_end_stx=date("Y-m-d");

$sql_search='';

if($date_start_stx) {
	$sql_search .= " and $date_stx >= '$date_start_stx 00:00:00' ";
	$qstr.="&date_start_stx=$date_start_stx";
}
if($date_end_stx) {
	$sql_search .= " and $date_stx <= '$date_end_stx 23:59:59' ";
	$qstr.="&date_end_stx=$date_end_stx";
}
if($date_stx) {	
	$qstr.="&date_stx=$date_stx";
}



$g5['title'] = '수익현황';

include_once(G5_ADMIN_PATH.'/admin.head.php');

$colspan =22;
include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');

$ary=array();
$ary1=array();
$ary2=array();
$tary=array();
if($mb_id){
	
	//구매 내역
	$sell=sql_query("select count(*) cnt, date($date_stx) dates, sum(ct_sell_price) ct_buy_price  from coin_item_trade where tr_stats='3' and mb_id='$mb_id' $sql_search group by date($date_stx) " ,1);
	while($data=sql_fetch_array($sell)){
		$ary1[$data[dates]]=$data;			
	}
	//판매 내역
	$sell=sql_query("select count(*) cnt, date($date_stx) dates, sum(ct_sell_price) ct_sell_price,  sum(ct_sell_price - ct_buy_price) amt  from coin_item_trade where tr_stats='3' and fmb_id='$mb_id' $sql_search group by date($date_stx) " ,1);
	while($data=sql_fetch_array($sell)){
		$ary2[$data[dates]]=$data;			
	}
	
	
	 for ($i=strtotime($date_end_stx); $i >= strtotime($date_start_stx); $i-=86400) {
	 	$dates=date("Y-m-d",$i);
	 	$ary[$dates][cnt]=$ary1[$dates][cnt] + $ary2[$dates][cnt] ;
		$ary[$dates][ct_buy_price]=$ary1[$dates][ct_buy_price];
		$ary[$dates][ct_sell_price]=$ary2[$dates][ct_sell_price];
		$ary[$dates][amt]=$ary2[$dates][amt];				
	 
	 }

		
		
	
	//판매 내역
	$tary1=sql_fetch("select count(*) cnt, sum(ct_buy_price) ct_buy_price from coin_item_trade where tr_stats='3' and mb_id='$mb_id' $sql_search  " ,1);	
	//판매 내역
	$tary2=sql_fetch("select count(*) cnt, sum(ct_sell_price) ct_sell_price,  sum(ct_sell_price - ct_buy_price) amt  from coin_item_trade where tr_stats='3' and fmb_id='$mb_id' $sql_search  " ,1);	
	
	$tary[cnt]=$tary1[cnt] + $tary2[cnt] ;
	$tary[ct_buy_price]=$tary1[ct_buy_price];
	$tary[ct_sell_price]=$tary2[ct_sell_price];
	$tary[amt]=$tary2[amt];
	
}else{
	
	//판매 내역
	$sell=sql_query("select count(*) cnt, date($date_stx) dates, sum(ct_sell_price) ct_sell_price, sum(ct_buy_price) ct_buy_price,  sum(ct_sell_price - ct_buy_price) amt  from coin_item_trade where tr_stats='3' $sql_search group by date($date_stx) " ,1);
	while($data=sql_fetch_array($sell)){
		$ary[$data[dates]]=$data;	
	}
	
	//판매 내역
	$tary=sql_fetch("select count(*) cnt, sum(ct_sell_price) ct_sell_price, sum(ct_buy_price) ct_buy_price,  sum(ct_sell_price - ct_buy_price) amt  from coin_item_trade where tr_stats='3' $sql_search  " ,1);	

}
$min=sql_fetch("select min(tr_wdate) dates from coin_item_trade  " ,1);	
?>

<div class="local_ov01 local_ov">
    <?php echo $listall ?>
    <span class="btn_ov01"><span class="ov_txt">검색된수</span><span class="ov_num"> <?php echo number_format($total_count) ?>개</span></span>
</div>

<form name="fsearch" id="fsearch" class="local_sch01 local_sch" method="get">


<span class='nowrap'>
<select name='date_stx' class="form-control input-sm  w-auto  mb-1" id="date_stx" >
<option value='tr_rdate' <?=$date_stx=='tr_rdate'?'selected':''?> >거래생성일</option>
<option value='tr_paydate' <?=$date_stx=='tr_paydate'?'selected':''?> >입금확인일</option>
<option value='tr_setdate' <?=$date_stx=='tr_setdate'?'selected':''?> >거래완료일</option>

</select>
<input type="text" name="date_start_stx" value="<?php echo $date_start_stx?>" id="date_start_stx"  class="frm_input calendar-input" size="12" placeholder="시작일" autocomplete='off'/>
  ~
<input type="text" name="date_end_stx" value="<?php echo $date_end_stx?>" id="date_end_stx"  class="frm_input calendar-input" size="12" placeholder="종료일"  autocomplete='off'/>
<a href="javascript:void(set_dates('<?=date("Y-m-d",strtotime("-7 days"))?>','<?=date("Y-m-d")?>'));"  class="btn_common" >1주일</a>
  <a href="javascript:void(set_dates('<?=date("Y-m-d",strtotime("-1 month"))?>','<?=date("Y-m-d")?>'));"  class="btn_common" >1개월</a>
  <a href="javascript:void(set_dates('<?=date("Y-m-d",strtotime("-3 month"))?>','<?=date("Y-m-d")?>'));"  class="btn_common" >3개월</a>
  <a href="javascript:void(set_dates('<?=date("Y-m-d",strtotime("-6 month"))?>','<?=date("Y-m-d")?>'));"  class="btn_common" >6개월</a>
  <a href="javascript:void(set_dates('<?=$min[dates]?>','<?=date("Y-m-d")?>'));"  class="btn_common" >전체</a>
  </span>
<label for="fmb_name_stx" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
<input name="mb_id" type="text" class="frm_input" id="mb_id" placeholder="회원아이디" value="<?php echo $mb_id ?>">
<input type="submit" value="검색" class="btn_submit">

</form>



<div class="tbl_head01 tbl_wrap">
    <table>
    <caption><?php echo $g5['title']; ?> 목록</caption>
    <thead>
    <tr>
		
        <th scope="col">일자</a></th>
<th scope="col">거래수</th>
<th scope="col">구매</th>
<th scope="col">판매</a></th>
<!--th scope="col">입금계좌</th-->
    <th scope="col">수익율</th>
<th scope="col">순이익</th>
</tr>
    </thead>
    <tbody>
	
	<tr class="<?php echo $bg; ?>">
	
		
       <td   class="td_datetime"><strong>
       합계
        </strong></td>
<td class="td_date2"><strong>
<?=number_format($tary[cnt])?>
</strong></td>
		<td ><strong>
<?=number_format($tary[ct_buy_price])?>
</strong></td>
<td ><strong>
<?=number_format($tary[ct_sell_price])?>
</strong></td>
    <td class="td_date2  fred"><strong>
<?=round($tary[amt]/$tary[ct_buy_price],3)*100?>
%</strong></td>
<td class="td_date2 fred"><strong>
<?=number_format($tary[amt])?>
</strong></td>
</tr>


    <?php
	
	
	
    for ($i=strtotime($date_end_stx); $i >= strtotime($date_start_stx); $i-=86400) {
		
       $dates=date("Y-m-d",$i);
        $bg = 'bg'.($i%2);
	  
	  
    ?>
	
    <tr class="<?php echo $bg; ?>">
	
		
       <td   class="td_datetime">
       <?=$dates?>
        </td>
<td class="td_date2"><?=number_format($ary[$dates][cnt])?></td>
		<td ><?=number_format($ary[$dates][ct_buy_price])?>
</td>
<td ><?=number_format($ary[$dates][ct_sell_price])?></td>
<!--td  class="td_num_c3"><?php echo $row['account'] ?></td-->
    <td class="td_date2"><?=round($ary[$dates][amt]/$ary[$dates][ct_buy_price],3)*100?>%</td>
<td class="td_date2"><?=number_format($ary[$dates][amt])?></td>
</tr>


    <?php
    }
    ?>
    </tbody>
    </table>
</div>


<script>
function set_dates(s,e){
	$("input[name='date_start_stx']","#fsearch").val(s);
	$("input[name='date_end_stx']","#fsearch").val(e);
	
	
	
}

$(function(){

  
  
});
</script>

<?php
include_once(G5_ADMIN_PATH.'/admin.tail.php');

?>
