<?php
$sub_menu = "700600";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');

$sql_search = " where (1) ";

$sql_common = " from {$g5['cn_item_trade']} as a 
left outer join  {$g5['cn_item_cart']} as c on(a.cart_code=c.code) 
left outer join  {$g5['member_table']} as b on(a.mb_id=b.mb_id) 
";



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


if($mb_name_stx!='') {	
	$sql_search .= " and b.mb_name like '%$mb_name_stx%' ";
	$qstr.="&mb_name_stx=$mb_name_stx";
}
if($fmb_name_stx!='') {	
	$sql_search .= "and a.fmb_id in (select mb_id from {$g5['member_table']} where mb_name like '%$fmb_name_stx%' ) ";
	$qstr.="&fmb_name_stx=$fmb_name_stx";
}

//패널티 대상 검색
if($penalty_stx=='ready'){
	$ldate=date("Y-m-d");
	$sql_search .= " and a.tr_penalty = 0 and tr_wdate <= '$ldate' and tr_stats in ('1','2')";
	$qstr.="&penalty_stx=$penalty_stx";
}
if ($stx) {
	
	if($sfl=='buy_recommend') $sql_search .= "and a.mb_id in (select mb_id from $g5[member_table] where mb_recommend='$stx' ) ";
	else if($sfl=='sell_recommend') $sql_search .= "and a.fmb_id in (select mb_id from $g5[member_table] where mb_recommend='$stx' ) ";
	else if($sfl=='a.mb_id') $sql_search .= "and ($sfl = '$stx') ";
	else if($sfl=='fmb_name') $sql_search .= "and a.fmb_id in (select mb_id from $g5[member_table] where mb_name like '%$stx%' ) ";
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

$sql = " select count(*) as cnt {$sql_common} {$sql_search}";
$row = sql_fetch($sql,1);
$total_count = $row['cnt'];

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = " select a.*,
c.ct_class, c.ct_wdate,c.ct_validdate,c.ct_buy_price,c.ct_sell_price,c.ct_class,c.ct_interest,c.ct_days,c.cn_item ccn_item,
b.mb_id,b.mb_email,b.mb_hp,b.mb_name,b.mb_trade_get_claim,b.mb_trade_put_claim,b.mb_trade_penalty,b.mb_trade_penalty_date {$sql_common} {$sql_search} {$sql_order} limit {$from_record}, {$rows} ";
$result = sql_query($sql);

$listall = '<a href="'.$_SERVER['SCRIPT_NAME'].'" class="ov_listall">전체목록</a>';


//수정긴한
$ltime=strtotime('-23 hours');

$g5['title'] = $g5['cn_item_name'].'-거래관리';

include_once(G5_ADMIN_PATH.'/admin.head.php');

$colspan =22;
include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');

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
  <a href="javascript:void(set_dates('<?=date("Y-m-d")?>','<?=date("Y-m-d")?>'));" class="btn_common" >오늘</a>
  <a href="javascript:void(set_dates('<?=date("Y-m-d",strtotime("-7 days"))?>','<?=date("Y-m-d")?>'));"  class="btn_common" >1주일</a>
  <a href="javascript:void(set_dates('<?=date("Y-m-d",strtotime("-1 month"))?>','<?=date("Y-m-d")?>'));"  class="btn_common" >1개월</a>
  <a href="javascript:void(set_dates('<?=date("Y-m-d",strtotime("-3 month"))?>','<?=date("Y-m-d")?>'));"  class="btn_common" >3개월</a>
  <a href="javascript:void(set_dates('<?=date("Y-m-d",strtotime("-6 month"))?>','<?=date("Y-m-d")?>'));"  class="btn_common" >6개월</a>
  <a href="javascript:void(set_dates('',''));"  class="btn_common" >전체</a>
  </span>


<select name='item_stx' class="form-control input-sm  w-auto  mb-1" id="item_stx" >
<option value=''>-<?=$g5[cn_item_name]?>-</option>
<?
foreach($g5['cn_item'] as $k=>$v){?>
<option value='<?=$k?>' <?=$item_stx==$k?'selected':''?> >
<?=$v[name_kr]?>
</option>
<? }?>
</select>
<select id="penalty_stx" name="penalty_stx" >
<option value=''>-패널티-</option>
<option value='ready' <?=($penalty_stx=='ready' ? 'selected':'')?> >패널티 대상</option>
<option value='has' <?=($penalty_stx=='has' ? 'selected':'')?> >패널티 완료</option>

</select>
<select id="stats_stx" name="stats_stx" >
<option value=''>-거래상태-</option>
<?
	foreach($g5['tr_stat'] as $k=>$v) echo "<option value='{$k}' ".($stats_stx==$k ? 'selected':'').">{$v}</option>";
	?>
</select>
<select id="claim_stx" name="claim_stx" >
<option value=''>-거래신고-</option>
<option value='all' <?=($claim_stx=='all' ? 'selected':'')?> >모든신고</option>
<option value='buyer' <?=($claim_stx=='buyer' ? 'selected':'')?> >구매자의 신고</option>
<option value='seller' <?=($claim_stx=='seller' ? 'selected':'')?> >판매자의 신고</option>
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
foreach(array('p2p','hq') as $k=>$v){?>
<option value='<?=$v?>' <?=$distri_stx==$v?'selected':''?> >
<?=strtoupper($v)?>
</option>
<? }?>
</select>
<input name="mb_name_stx" type="text" class="frm_input" id="mb_name_stx" placeholder="구매자명" value="<?php echo $mb_name_stx ?>" size="10">
<input name="fmb_name_stx" type="text" class="frm_input" id="fmb_name_stx" placeholder="판매자명" value="<?php echo $fmb_name_stx ?>" size="10">
<select name="sfl" id="sfl">
<option value="a.mb_id"<?php echo get_selected($_GET['sfl'], "a.mb_id"); ?>>구매자 아이디</option>
<option value="a.smb_id"<?php echo get_selected($_GET['sfl'], "a.smb_id"); ?>>구매자 서브아이디</option>
<option value="b.mb_name"<?php echo get_selected($_GET['sfl'], "b.mb_name"); ?>>구매자명</option>

<option value="a.fmb_id"<?php echo get_selected($_GET['sfl'], "a.fmb_id"); ?>>판매자 아이디</option>
<option value="a.fsmb_id"<?php echo get_selected($_GET['sfl'], "a.fsmb_id"); ?>>판매자 서브아이디</option>
<option value="fmb_name"<?php echo get_selected($_GET['sfl'], "fmb_name"); ?>>판매자명</option>

<option value="a.tr_code"  <?php echo get_selected($_GET['sfl'], "a.tr_code"); ?>>거래코드</option>
<option value="a.cart_code"  <?php echo get_selected($_GET['sfl'], "a.cart_code"); ?>>매도된 상품코드</option>
<option value="a.to_cart_code"  <?php echo get_selected($_GET['sfl'], "a.to_cart_code"); ?>>지급된 상품코드</option>
 </select>

<label for="fmb_name_stx" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
<input type="text" name="stx" value="<?php echo $stx ?>" id="stx" class="frm_input">
<input type="submit" value="검색" class="btn_submit">

</form>


<?
if(!auth_check($auth[$sub_menu], 'w',1)){?>
	
<form name="fboardlist" id="fboardlist" action="./item_trade_list_update.php" onsubmit="return fboardlist_submit(this);" method="post">
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


<div style='margin:10px 0; border:3px solid #ffdddd;padding:10px;'>
패널티실행
<span class="nowrap">
<select name='select_pt' class="form-control input-sm  w-auto  mb-1" id="select_pt" >
<option value='sel'  >선택된 거래</option>
<!--option value='all'  >검색된 전체 거래</option-->
</select>
</span>

<select name='target_pt' class="form-control input-sm  w-auto  mb-1" id="target_pt" >
<option value='buyer' >구매자에게</option>
<option value='seller'  >판매자에게</option>

</select>

<select name='stats_pt' class="form-control input-sm  w-auto  mb-1" id="stats_pt" >
<option value='cancel'  >거래취소실행</option>
<option value='retain'  >상태유지</option>
</select>

( 피해자 보상
<select name='give_coin' class="form-control input-sm  w-auto  mb-1" id="give_coin" >
<?
foreach($g5['cn_cointype'] as $k=>$v){?>
<option value='<?=$k?>'  >
<?=$v?>
</option>
<? }?>
</select>
<input name="give_coin_amt" type="text" class="frm_input" id="give_coin_amt" value="0" size="6">
부여 / 가해자 벌금
<select name='get_coin' class="form-control input-sm  w-auto  mb-1" id="get_coin" >
<?
foreach($g5['cn_cointype'] as $k=>$v){?>
<option value='<?=$k?>'  >
<?=$v?>
</option>
<? }?>
</select>
<input name="get_coin_amt" type="text" class="frm_input" id="get_coin_amt" value="0" size="6">
차감) </span>
<input type="submit" name="act_button" value="패널티실행" onclick="document.pressed=this.value" class="btn_01 btn">
</div>

<? 
/*
<div style='margin:10px 0; border:3px solid #ffdddd;padding:10px;color:red;'>
검색된 <span class="ov_num fblue"><strong><?php echo number_format($total_count) ?></strong></span>건 전체 Batch 실행
<input name="batch_pass" type="password" class="frm_input" id="batch_pass" placeholder="암호입력" value="" size="9">
|

<select id="tr_stats_batch" name="tr_stats_batch"  >
<?
foreach($g5['tr_stat'] as $k=>$v) echo "<option value='{$k}' >{$v}</option>";
?>
</select>
<select id="batch_div" name="batch_div"  >
<option value='100' >100건 분할</option>
<option value='300' >300건 분할</option>
<option value='500' >500건 분할</option>
<option value='700' >700건 분할</option>
<option value='1000' >1000건 분할</option>
</select>
<input type="submit" name="act_button" value="일괄변경" onclick="document.pressed=this.value" class="btn_01 btn">
| 
<input type="submit" name="act_button" value="거래정보만 일괄삭제" onclick="document.pressed=this.value" class="btn_01 btn">
| 
<input type="submit" name="act_button" value="취소후 일괄삭제" onclick="document.pressed=this.value" class="btn_01 btn">
</div>
*/?>
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
<th rowspan="2" scope="col"><?php echo subject_sort_link('a.mb_id') ?>구매자/<?php echo subject_sort_link('a.mb_id') ?>판매자</th>
<th rowspan="2" scope="col"><?php echo subject_sort_link('a.cn_item') ?><?=$g5[cn_item_name]?></a></th>
<th colspan="7" scope="col">매도 <?=$g5['cn_item_name']?></th>
<th colspan="2" scope="col">거래정보</th>
    <th rowspan="2" scope="col"><?php echo subject_sort_link('a.tr_paytype') ?>결재<br>
방법</a></th>
        <!--th scope="col">입금계좌</th-->
    <th rowspan="2" scope="col">입금완료<br>
최종변경</th>
<th rowspan="2" scope="col"><?php echo subject_sort_link('a.tr_distri') ?>거래구분</th>
<th colspan="3" scope="col">신고정보</th>
<th rowspan="2" scope="col"><?php echo subject_sort_link('a.tr_rdate') ?>생성일</th>
        <th rowspan="2" scope="col"><?php echo subject_sort_link('a.tr_stats') ?>상태</a></th>
    </tr>
<tr>
<th scope="col"><?php echo subject_sort_link('c.ct_class') ?>Class</th>
<th scope="col"><?php echo subject_sort_link('c.ct_buy_price') ?>액면가</th>
<th scope="col">판매가</th>
<th scope="col"><?php echo subject_sort_link('c.ct_interest') ?>이율</th>
<th scope="col"><?php echo subject_sort_link('c.ct_buy_date') ?>구매일</th>
<th scope="col"><?php echo subject_sort_link('c.ct_validdate') ?>보유마감</th>
<th scope="col"><?php echo subject_sort_link('c.ct_days') ?>기본보유</th>
<th scope="col"><?php echo subject_sort_link('c.ct_buy_price') ?>액면가</th>
<th scope="col">실구매가</th>
<th scope="col">구매자</th>
<th scope="col">판매자</th>
<th scope="col">패널티</th>
</tr>
    </thead>
    <tbody>
    <?php
	$list_num = $total_count - ($page - 1) * $rows;
	
    for ($i=0; $row=sql_fetch_array($result); $i++) {
		
        $one_update = '<a href="./item_trade_form.php?w=u&amp;tr_no='.$row['tr_no'].'&amp;'.$qstr.'" class="btn btn_03">상세</a>';

        $bg = 'bg'.($i%2);
		
		$seller=get_member($row[fmb_id]);		
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
		<td ><div class='mb-info-open' data-id='<?php echo $row['mb_id'] ?>'><?php echo $row['smb_id'] ?> @<?php echo $row['mb_id'] ?></div>
		
		<div><span class='fblue'><?php echo $row['mb_name'] ?></span> / <?php echo $row['mb_hp'] ?> <? if(($ltime < strtotime($row[tr_rdate]) && $is_manager)  || $is_admin=='super'){?> <a href='./open_buyer_change.php?tr_code=<?=$row[tr_code]?>' class='btn-change-buyer' style='float:right;'><i class="fa fa-refresh" aria-hidden="true"></i></a><? }?></div>
<!--p><?=$row[mb_trade_put_claim]?'보낸신고:'.$row[mb_trade_put_claim]:''?> <?=$row[mb_trade_get_claim]?'받은신고:'.$row[mb_trade_get_claim]:''?> <?=$row[mb_trade_penalty]?'패널티중'. $row[mb_trade_penalty_date]:''?></p-->
</td>
<td >
<?php echo $row['ccn_item']!=$row['cn_item'] ? ($row['tr_src']=='honey'?'꿀단지<br>→':$g5['cn_item'][$row['ccn_item']][name_kr]."<br>→ "):''?>
<?php echo $g5['cn_item'][$row['cn_item']][name_kr] ?></td>
<td class="td_right"><?php echo $row['ct_class']?$row['ct_class']:'-'?></td>
<td class="td_right"><?php echo $row['ct_buy_price']?number_format2($row['ct_buy_price']):'-'?></td>
<td class="td_right"><?php echo $row['ct_sell_price']?number_format2($row['ct_sell_price']):'-'?></td>
<td class="td_right"><?php echo $row['ct_interest']?></td>
<td class="td_right"><span class="td_datetime"><?php echo str_replace(" ","<br>",$row['ct_wdate'])?></span></td>
<td class="td_right"><span class="td_datetime"><?php echo $row['ct_validdate']?></span></td>
<td class="td_right"><span class="td_datetime"><?php echo $row['ct_days']?></span></td>
<td class="td_right"><?php echo number_format2($row['tr_price_org'])?></td>
<td class="td_right"><?php echo number_format2($row['tr_price'])?></td>
<td ><?=$g5['cn_paytype'][$row['tr_paytype']]?></td>
       <!--td  class="td_num_c3"><?php echo $row['account'] ?></td-->
    <td class="td_datetime"><?php echo !preg_match("/^00/",$row['tr_paydate'])?$row['tr_paydate']:'-'?><br>
<?php echo !preg_match("/^00/",$row['tr_setdate'])?$row['tr_setdate']:'-'?></td>
<td class="td_num"><?php echo strtoupper($row['tr_distri'])?></td>
<td class='fred'><?php echo $row['tr_buyer_claim']? str_replace(" ","<br>",$row['tr_buyer_note']):'-'?>

<?
if(!auth_check($auth[$sub_menu], 'w',1)){?>
<p><a href='./open_penalty_info.php?tr_code=<?=$row[tr_code]?>&target=buyer' class='btn-penalty btn_common btn-sm'>패널티</a></p>
<? }?>
</td>
<td class='fred'><?php echo $row['tr_seller_claim']? str_replace(" ","<br>",$row['tr_seller_note']):'-'?>


<?
if(!auth_check($auth[$sub_menu], 'w',1)){?>
<p><a href='./open_penalty_info.php?tr_code=<?=$row[tr_code]?>&target=seller' class='btn-penalty btn_common btn-sm'>패널티</a>
<? }?>
</p>
</td>
<td class='fred'><?php echo $row['tr_penalty']? str_replace(" ","<br>",$row['tr_penalty_date']):'-'?></td>
<td class="td_date2"><?php echo str_replace(" ","<br>", $row['tr_rdate'])?></td>
        <td class="td_datetime"><label for="deposit_status<?php echo $i; ?>" class="sound_only">상태</label>
          <select id="tr_stats[<?php echo $i ?>]" name="tr_stats[<?php echo $i ?>]" <?=auth_check($auth[$sub_menu], 'w',1) ? 'disabled':''?>  >
            <?
			foreach($g5['tr_stat'] as $k=>$v) echo "<option value='{$k}' ".($row['tr_stats']==$k ? 'selected':'').">{$v}</option>";
			?>
          </select>
          </td>
    </tr>
<tr class="<?php echo $bg; ?>">
<td><div class='mb-info-open' data-id='<?php echo $row['fmb_id'] ?>'><?php echo $row['fsmb_id'] ?> @<?php echo $row['fmb_id'] ?></div>
<span class='fblue'><?php echo $seller['mb_name'] ?></span> / <?php echo $seller['mb_hp'] ?></td>
<td colspan="18" class='td_left'  >
거래번호: <?php echo $row['tr_code'] ?> /
판매<?=$g5[cn_item_name]?>
:
<a href='./item_cart_list.php?code_stx=<?=$row[cart_code]?>' target='_blank'><?=$row[cart_code]?></a>
<?=$row[to_cart_code]?' &gt; 지급'.$g5[cn_item_name].": <a href='./item_cart_list.php?code_stx={$row[to_cart_code]}' target='_blank'>". $row[to_cart_code]."</a>":''?>
<?
echo " / 구매수수료:<span class='fblue'>".$row[tr_fee]."</span>";
echo " / 판매수수료:<span class='fblue'>".$row[tr_seller_fee]."</span>";
?>
<?
if($row[tr_buyer_memo]) echo "<br/>구매자 신고:<span class='fred'>".$row[tr_buyer_memo]."</span>";
if($row[tr_seller_memo]) echo "<br/>판매자 신고:<span class='fred'>".$row[tr_seller_memo]."</span>";
?>
<br>
<p><span class='fblue'>거래계좌 : <?=$row[tr_bank]?> <?=$row[tr_bank_num]?>  <?=$row[tr_bank_user]?> /</span> <span class='fred'>입금자명 : <?=$row[tr_deposit]?$row[tr_deposit]:'-'?></span></p>
<p class='forange'><?=$row[tr_logs]?$row[tr_logs]:''?></p>


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

<div class="btn_fixed_top">


<?
if(!auth_check($auth[$sub_menu], 'w',1)){?>
<select id="tr_stats_all" name="tr_stats_all"  >
<option value=''>-개별설정-</option>
<?
foreach($g5['tr_stat'] as $k=>$v) echo "<option value='{$k}' >{$v}</option>";
?>
</select>
<input type="submit" name="act_button" value="선택수정" onclick="document.pressed=this.value" class="btn_02 btn">
<? }?>

<?
if(!auth_check($auth[$sub_menu], 'd',1)){?>
<input type="submit" name="act_button" value="선택삭제" onclick="document.pressed=this.value" class="btn_01 btn">
<? }?>
</div>

</form>


<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?'.$qstr.'&amp;page='); ?>

<script>
function set_dates(s,e){
	$("input[name='date_start_stx']","#fsearch").val(s);
	$("input[name='date_end_stx']","#fsearch").val(e);
	
	
	
}

function fboardlist_submit(f)
{	
	if(document.pressed == "일괄변경") {
	
		 if ($("input[name=batch_pass]").val()=='') {
			alert("암호를 입력하세요");
			return false;
		}
        if(!confirm("주의\n\n검색된 건에 대해 일괄적용을 실행합니다.\n이용에 주의하세요.\n\n실행합니까?")) {
            return false;
        }
		
		else return true;
    }
	if(document.pressed == "거래정보만 일괄삭제" ) {
	
		 if ($("input[name=batch_pass]").val()=='') {
			alert("암호를 입력하세요");
			return false;
		}
        if(!confirm("주의\n\n 검색된 건에 대해 거래정보만 일괄삭제합니다. \n지급/판매된 상품에 영향이 없으며 수수료도 반환되지 않습니다.\n\n실행합니까?")) {
            return false;
        }
		
		else return true;
    }
	if(document.pressed == "취소후 일괄삭제" ) {
	
		 if ($("input[name=batch_pass]").val()=='') {
			alert("암호를 입력하세요");
			return false;
		}
        if(!confirm("주의\n\n 검색된 건에 대해 거래를 취소후 거래 정보를 삭제합니다. \n지급된 상품은 다시 회수 되며 판매된 상품은 다시 되돌려집니다. 수수료 또한 반화됩니다.\n\n실행합니까?")) {
            return false;
        }
		
		else return true;
    }
	
	if(document.pressed == "패널티실행") {
		var s=$("select[name=select_pt] option:selected").text();
		var sv=$("select[name=select_pt]").val();
		
		if(sv=='sel'){
			 if (!is_checked("chk[]")) {
				alert(document.pressed+" 하실 항목을 하나 이상 선택하세요.");
				return false;
			}
		}
        if(!confirm("주의\n\n"+s+"에 대해서 선택한 조건으로 정말 패널티를 실행합니까?")) {
            return false;
        }
		else return true;
    }
    
	
	
    if (!is_checked("chk[]")) {
        alert(document.pressed+" 하실 항목을 하나 이상 선택하세요.");
        return false;
    }

    if(document.pressed == "선택삭제") {
        if(!confirm("선택한 자료를 정말 삭제하시겠습니까?")) {
            return false;
        }else return true;
    }
	if(document.pressed == "선택수정") {
        if(!confirm("선택한 자료를 정말 수정하시겠습니까?")) {
            return false;
        }else return true;
    }
	
	
}

$(function(){
  
  $('.btn-penalty').click(function(){
  	event.preventDefault();
  	var urls=$(this).attr('href');
  		var pw=window.open(urls,'penaltyw','width=1250,height=800,resizable=yes');
  	pw.focus();
  });
  
    
  $('.btn-change-buyer').click(function(){
  	event.preventDefault();
  	var urls=$(this).attr('href');
  		var pw=window.open(urls,'penaltyw','width=1200,height=800,resizable=yes');
  	pw.focus();
  });
  
  
});
</script>

<?php
include_once(G5_ADMIN_PATH.'/admin.tail.php');

?>
