<?php
$sub_menu = "700755";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');

$sql_common = " from {$g5['cn_item_trade_test']} as a 
left outer join  {$g5['cn_item_cart']} as c on(a.cart_code=c.code) 
left outer join  {$g5['member_table']} as b on(a.mb_id=b.mb_id) 
left outer join  {$g5['cn_sub_account']} as s on(s.ac_id=a.mb_id) 
left outer join  {$g5['cn_sub_account']} as f on(f.ac_id=a.fsmb_id) 

left outer join (select mb_id, count(tr_code) tr_cnt, sum(tr_price) tr_price_sum from  {$g5['cn_item_trade_test']} group by mb_id ) as t on (t.mb_id=a.mb_id) 
left outer join (select fmb_id, count(tr_code) tr_cnt_seller, sum(tr_price) tr_price_sum_seller from  {$g5['cn_item_trade_test']} group by fmb_id ) as tf on (tf.fmb_id=a.fmb_id) ";

$sql_search = " where (1) ";

if($date_start_stx) {
	$sql_search .= " and a.$date_stx >= '$date_start_stx 00:00:00' ";
	$qstr.="&date_start_stx=$date_start_stx";
}
if($date_end_stx) {
	$sql_search .= " and a.$date_stx <= '$date_end_stx 23:59:59' ";
	$qstr.="&date_end_stx=$date_end_stx";
}
if($date_stx) {	
	$qstr.="&date_stx=$date_stx";
}
if($item_stx) {
	$sql_search .= " and a.cn_item = '$item_stx' ";
	$qstr.="&item_stx=$item_stx";
}
if($paytype_stx) {
	$sql_search .= " and a.tr_paytype = '$paytype_stx' ";
	$qstr.="&paytype_stx=$paytype_stx";
}
if($distri_stx) {
	$sql_search .= " and a.tr_distri = '$distri_stx' ";
	$qstr.="&distri_stx=$distri_stx";
}
if($stats_stx) {
	$sql_search .= " and a.tr_stats = '$stats_stx' ";
	$qstr.="&stats_stx=$stats_stx";
}
if($claim_stx=='all') {	
	$sql_search .= " and ( a.tr_buyer_claim = '1' or a.tr_seller_claim = '1' ) ";
	$qstr.="&claim_stx=$claim_stx";
}
if($claim_stx=='buyer') {	
	$sql_search .= " and a.tr_buyer_claim = '1' ";
	$qstr.="&claim_stx=$claim_stx";
}
if($claim_stx=='seller') {	
	$sql_search .= " and a.tr_seller_claim = '1' ";
	$qstr.="&claim_stx=$claim_stx";
}


if ($stx) {
	
	if($sfl=='buy_recommend') $sql_search .= "and a.mb_id in (select mb_id from $g5[member_table] where mb_recommend='$stx' ) ";
	else if($sfl=='sell_recommend') $sql_search .= "and a.fmb_id in (select mb_id from $g5[member_table] where mb_recommend='$stx' ) ";
	else if($sfl=='a.mb_id') $sql_search .= "and ($sfl = '$stx') ";
    else $sql_search .= "and ($sfl like '%$stx%') ";
}
if ($mb_stx) {
    $sql_search .= " and b.mb_id='$mb_stx' ";	
	$qstr.="&mb_stx=$mb_stx";
}


if (!$sst) {
    $sst  = "a.tr_code ";
    $sod = "desc";
}

$sql_order = " order by $sst $sod ";

$sql = " select count(*) cnt from ( select a.tr_code {$sql_common}   {$sql_search} group by a.tr_code ) as A ";
$row = sql_fetch($sql,1);
$total_count = $row['cnt'];

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = " select a.*,
c.cn_item ccn_item,c.ct_class, c.ct_wdate,c.ct_validdate,c.ct_buy_price,c.ct_sell_price,c.ct_class,c.ct_interest,c.ct_days,
b.mb_id,b.mb_email,b.mb_name,b.mb_trade_get_claim,b.mb_trade_put_claim,b.mb_trade_penalty,b.mb_trade_penalty_date,
sum(s.ac_point_b) ac_point_b,sum(s.ac_point_e) ac_point_e,sum(s.ac_point_i) ac_point_i,sum(s.ac_point_u) ac_point_u,
t.*,tf.*,
f.ac_point_b fac_point_b
{$sql_common} {$sql_search} group by a.tr_code {$sql_order} limit {$from_record}, {$rows} ";
$result = sql_query($sql,1);

$listall = '<a href="'.$_SERVER['SCRIPT_NAME'].'" class="ov_listall">전체목록</a>';

$g5['title'] = $g5['cn_item_name'].'-매칭(p2p)-프리뷰'; 

include_once(G5_ADMIN_PATH.'/admin.head.php');

$colspan =22;
include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');
//echo $sql;
?>

<div class="local_ov01 local_ov">
    <?php echo $listall ?>
    <span class="btn_ov01"><span class="ov_txt">검색된수</span><span class="ov_num"> <?php echo number_format($total_count) ?>개</span></span>
</div>

<form name="fsearch" id="fsearch" class="local_sch01 local_sch" method="get">
<select name='item_stx' class="form-control input-sm  w-auto  mb-1" id="item_stx" >
<option value=''>-<?=$g5[cn_item_name]?>-</option>
<?
foreach($g5['cn_item'] as $k=>$v){?>
<option value='<?=$k?>' <?=$item_stx==$k?'selected':''?> >
<?=$v[name_kr]?>
</option>
<? }?>
</select>
<select name='paytype_stx' class="form-control input-sm  w-auto  mb-1" >
<option value=''>-입금수단-</option>
<?
foreach($g5['cn_paytype'] as $k=>$v){?>
<option value='<?=$k?>' <?=$paytype_stx==$k?'selected':''?> ><?=$v?></option>
<? }?>
</select>


<select name='distri_stx' class="form-control input-sm  w-auto  mb-1" >
<option value=''>-거래구분-</option>
<?
foreach(array('p2p','hq','dr') as $k=>$v){?>
<option value='<?=$v?>' <?=$distri_stx==$v?'selected':''?> >
<?=strtoupper($v)?>
</option>
<? }?>
</select>
<select name="sfl" id="sfl">
<option value="a.mb_id"<?php echo get_selected($_GET['sfl'], "a.mb_id"); ?>>구매자 아이디</option>
<option value="a.smb_id"<?php echo get_selected($_GET['sfl'], "a.smb_id"); ?>>구매자 서브아이디</option>
<option value="a.fmb_id"<?php echo get_selected($_GET['sfl'], "a.fmb_id"); ?>>판매자 아이디</option>
<option value="a.fsmb_id"<?php echo get_selected($_GET['sfl'], "a.fsmb_id"); ?>>판매자 서브아이디</option>
<option value="a.tr_code"  <?php echo get_selected($_GET['sfl'], "a.tr_code"); ?>>거래코드</option>
<option value="a.cart_code"  <?php echo get_selected($_GET['sfl'], "a.cart_code"); ?>>매도된 상품코드</option>
<option value="a.to_cart_code"  <?php echo get_selected($_GET['sfl'], "a.to_cart_code"); ?>>지급된 상품코드</option>
<option value="buy_recommend"  <?php echo get_selected($_GET['sfl'], "buy_recommend"); ?>>구매자 지사/지점 아이디</option>
<option value="sell_recommend"  <?php echo get_selected($_GET['sfl'], "sell_recommend"); ?>>판매자 지사/지점 아이디</option>
</select>

<label for="stx" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
<input type="text" name="stx" value="<?php echo $stx ?>" id="stx" class="frm_input">
<input type="submit" value="검색" class="btn_submit">

</form>



<form name="fboardlist" id="fboardlist" action="./item_matching.p2p.list.update.php" onsubmit="return ftradeexe_submit(this);" method="post">

<input type="hidden" name="token" value="<?php echo $token ?>">
<input type="submit" name="act_button" value="실거래적용" onclick="document.pressed=this.value" class="btn_01 btn">
<br><br>


</form>


<?
if(!auth_check($auth[$sub_menu], 'w',1)){?>
	
<form name="fboardlist" id="fboardlist" action="./item_matching.p2p.list.update.php" onsubmit="return fboardlist_submit(this);" method="post">
<input type="hidden" name="sst" value="<?php echo $sst ?>">
<input type="hidden" name="sod" value="<?php echo $sod ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
<input type="hidden" name="stx" value="<?php echo $stx ?>">
<input type="hidden" name="page" value="<?php echo $page ?>">
<input type="hidden" name="mb_stx" value="<?php echo $mb_stx ?>">
<input type="hidden" name="paytype_stx" value="<?php echo $paytype_stx ?>">
<input type="hidden" name="stats_stx" value="<?php echo $stats_stx ?>">
<input type="hidden" name="claim_stx" value="<?php echo $claim_stx ?>">
<input type="hidden" name="distri_stx" value="<?php echo $distri_stx ?>">
<input type="hidden" name="penalty_stx" value="<?php echo $penalty_stx?>">
<input type="hidden" name="date_stx" value="<?php echo $date_stx?>">
<input type="hidden" name="date_start_stx" value="<?php echo $date_start_stx?>">
<input type="hidden" name="date_end_stx" value="<?php echo $date_end_stx?>">


<input type="hidden" name="token" value="<?php echo $token ?>">
<? }?>

<div class="tbl_head01 tbl_wrap">
    <table>
    <caption><?php echo $g5['title']; ?> 목록</caption>
    <thead>
    <tr>
	
<?
if(!auth_check($auth[$sub_menu], 'w',1)){?>
        <th rowspan="2" scope="col">
            <label for="chkall" class="sound_only"> 전체</label>
            <input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all(this.form)">
        </th>
<? }?>		
        <th rowspan="2" scope="col">번호</a></th>
<th rowspan="2" scope="col"><?php echo subject_sort_link('a.mb_id') ?>구매자</th>
<th rowspan="2" scope="col"><?php echo subject_sort_link('a.mb_id') ?>판매자</th>
<th rowspan="2" id="mb_list_id" scope="col"><?php echo subject_sort_link('s.ac_point_'.$g5[cn_fee_coin]) ?>구매자<br><?=$g5[cn_cointype][$g5[cn_fee_coin]]?></th>
<th rowspan="2" id="mb_list_id" scope="col">판매자<br>
<?=$g5[cn_cointype]['b']?></th>
<th colspan="8" scope="col">매도 <?=$g5['cn_item_name']?></th>
<th colspan="3" scope="col">매수
<?=$g5['cn_item_name']?></th>
    <th rowspan="2" scope="col"><?php echo subject_sort_link('a.tr_paytype') ?>결재방법</a></th>
        <!--th scope="col">입금계좌</th-->
    <th rowspan="2" scope="col">입금완료<br>
최종변경</th>
<th rowspan="2" scope="col"><?php echo subject_sort_link('a.tr_distri') ?>거래구분</th>
<th rowspan="2" scope="col"><?php echo subject_sort_link('a.tr_rdate') ?>생성일</th>
<th rowspan="2" scope="col">기준일</a></th>
    </tr>
<tr>
<th scope="col"><?php echo subject_sort_link('c.cn_item') ?><?php echo $g5['cn_item_name'] ?></th>
<th scope="col"><?php echo subject_sort_link('c.ct_class') ?>Class</th>
<th scope="col"><?php echo subject_sort_link('c.ct_buy_price') ?>액면가</th>
<th scope="col">판매가</th>
<th scope="col"><?php echo subject_sort_link('c.ct_interest') ?>이율</th>
<th scope="col"><?php echo subject_sort_link('c.ct_buy_date') ?>구매일</th>
<th scope="col"><?php echo subject_sort_link('c.ct_validdate') ?>보유마감</th>
<th scope="col"><?php echo subject_sort_link('c.ct_days') ?>기본보유일</th>
<th scope="col"><?php echo subject_sort_link('c.ct_buy_price') ?>구분</th>
<th scope="col"><?php echo subject_sort_link('c.ct_buy_price') ?>액면가</th>
<th scope="col">실구매가</th>
</tr>
    </thead>
    <tbody>
    <?php
	$list_num = $total_count - ($page - 1) * $rows;
	
    for ($i=0; $row=sql_fetch_array($result); $i++) {
		
        $one_update = '<a href="./item_trade_form.php?w=u&amp;tr_no='.$row['tr_no'].'&amp;'.$qstr.'" class="btn btn_03">상세</a>';

        $bg = 'bg'.($i%2);
		
    ?>
	
    <tr class="<?php echo $bg; ?>">
	
<?
if(!auth_check($auth[$sub_menu], 'w',1)){?>
        <td rowspan="2" class="td_chk">
            <label for="chk_<?php echo $i; ?>" class="sound_only">선택</label>
            <input type="checkbox" name="chk[]" value="<?php echo $i ?>" id="chk_<?php echo $i ?>" <?=$disabled?> >
            <input type="hidden" name="tr_code[<?php echo $i ?>]" value="<?php echo $row['tr_code'] ?>">
        </td>
<? }?>		
       <td rowspan="2"  class="td_num">
       <?=$list_num?>
    </td>
<td class='mb-info-open' data-id='<?php echo $row['mb_id'] ?>'><?php echo $row['smb_id'] ?> @<?php echo $row['mb_id'] ?><br>
<!--p><?=$row[mb_trade_put_claim]?'보낸신고:'.$row[mb_trade_put_claim]:''?> <?=$row[mb_trade_get_claim]?'받은신고:'.$row[mb_trade_get_claim]:''?> <?=$row[mb_trade_penalty]?'패널티중'. $row[mb_trade_penalty_date]:''?></p-->
</td>
<td class='mb-info-open' data-id='<?php echo $row['fmb_id'] ?>'><?php echo $row['fsmb_id'] ?> @<?php echo $row['fmb_id'] ?><br>
<!--p><?=$row[mb_trade_put_claim]?'보낸신고:'.$row[mb_trade_put_claim]:''?> <?=$row[mb_trade_get_claim]?'받은신고:'.$row[mb_trade_get_claim]:''?> <?=$row[mb_trade_penalty]?'패널티중'. $row[mb_trade_penalty_date]:''?></p--></td>
<td  ><?=number_format($row['ac_point_'.$g5[cn_fee_coin]])?></td>
<td  ><?=number_format($row['fac_point_b'])?></td>
<td ><?php echo $g5['cn_item'][$row['ccn_item']][name_kr] ?></td>
<td ><?php echo $row['ct_class']?$row['ct_class']:'-'?></td>
<td ><?php echo $row['ct_buy_price']?number_format2($row['ct_buy_price']):'-'?></td>
<td ><?php echo $row['ct_sell_price']?number_format2($row['ct_sell_price']):'-'?></td>
<td ><?php echo $row['ct_interest']?></td>
<td ><?php echo str_replace(" ","<br>",$row['ct_wdate'])?></td>
<td ><?php echo $row['ct_validdate']?></td>
<td ><?php echo $row['ct_days']?></td>
<td ><?php echo $g5['cn_item'][$row['cn_item']][name_kr] ?></td>
<td ><?php echo number_format2($row['tr_price_org'])?></td>
<td ><?php echo number_format2($row['tr_price'])?></td>
<td ><?=$g5['cn_paytype'][$row['tr_paytype']]?></td>
       <!--td  class="td_num_c3"><?php echo $row['account'] ?></td-->
    <td class="td_datetime"><?php echo !preg_match("/^00/",$row['tr_paydate'])?$row['tr_paydate']:'-'?><br>
<?php echo !preg_match("/^00/",$row['tr_setdate'])?$row['tr_setdate']:'-'?></td>
<td class="td_num"><?php echo strtoupper($row['tr_distri'])?></td>
<td class='fred'><?php echo str_replace(" ","<br>", $row['tr_rdate'])?></td>
<td class="td_num_c3"><?php echo $row['tr_wdate']?></span></td>
    </tr>
<tr class="<?php echo $bg; ?>">
<td>
<?=number_format($row[tr_price_sum])?>
(
<?=$row[tr_cnt]?>건)</td>
<td>
<?=number_format($row[tr_price_sum_seller])?>
(
<?=$row[tr_cnt_seller]?>건)</td>
<td colspan="18" class='td_left'  >
거래번호: <?php echo $row['tr_code'] ?> /
판매<?=$g5[cn_item_name]?>
:
<a href='./item_cart_list.php?code_stx=<?=$row[cart_code]?>' target='_blank'><?=$row[cart_code]?></a>
<?
echo " / 구매수수료:<span class='fblue'>".$row[tr_fee]."</span>";
echo " / 판매수수료:<span class='fblue'>".$row[tr_seller_fee]."</span>";
?>
<?
if($row[tr_buyer_memo]) echo "<br/>구매자 신고:<span class='fred'>".$row[tr_buyer_memo]."</span>";
if($row[tr_seller_memo]) echo "<br/>판매자 신고:<span class='fred'>".$row[tr_seller_memo]."</span>";
?>
</td>
</tr>
    <?php
	$list_num--;
    }
    if ($i == 0)
        echo '<tr><td colspan="'.$colspan.'" class="empty_table">자료가 없습니다.</td></tr>';
    ?>
    </tbody>
    </table>
</div>

<?
if(!auth_check($auth[$sub_menu], 'd',1)){?>
<div class="btn_fixed_top">
<input type="submit" name="act_button" value="선택삭제" onclick="document.pressed=this.value" class="btn_01 btn">
<input type="submit" name="act_button" value="모두비우기" onclick="document.pressed=this.value" class="btn_01 btn">

</div>

</form>
<? }?>

<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?'.$qstr.'&amp;page='); ?>

<script>
function set_dates(s,e){
	$("input[name='date_start_stx']","#fsearch").val(s);
	$("input[name='date_end_stx']","#fsearch").val(e);
}

function ftradeexe_submit(f)
{	

	if(!confirm("현재 내역을 실제 거래로 적용합니까?")) {
		return false;
	}else return true;

}

function fboardlist_submit(f)
{	
	

    if(document.pressed == "선택삭제") {
        if(!confirm("선택한 자료를 정말 삭제하시겠습니까?")) {
            return false;
        }else return true;
    }
	
	if(document.pressed == "모두비우기") {
        if(!confirm("모든 자료를 정말 삭제하시겠습니까?")) {
            return false;
        }else return true;
    }
	
}

$(function(){
  
});
</script>

<?php
include_once(G5_ADMIN_PATH.'/admin.tail.php');

?>
