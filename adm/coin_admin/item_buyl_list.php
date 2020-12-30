<?php
$sub_menu = "700500";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');

$sql_common = " from {$g5['cn_purchase_table']} a left outer join  {$g5['member_table']} as b on(a.mb_id=b.mb_id) ";
$sql_search = " where (1) ";

if($date_start_stx) {
	$sql_search .= " and a.in_wdate >= '$date_start_stx 00:00:00' ";
	$qstr.="&date_start_stx=$date_start_stx";
}
if($date_end_stx) {
	$sql_search .= " and a.in_wdate <= '$date_end_stx 23:59:59' ";
	$qstr.="&date_end_stx=$date_end_stx";
}
if($coin_stx) {
	$sql_search .= " and a.in_token = '$coin_stx' ";
	$qstr.="&coin_stx=$coin_stx";
}

if ($stx) {
	if($sfl=='a.mb_id') $sql_search .= "and ($sfl = '$stx') ";
    else $sql_search .= "and ($sfl like '%$stx%') ";
}
if ($mb_stx) {
    $sql_search .= " and b.mb_id='$mb_stx' ";	
	$qstr.="&mb_stx=$mb_stx";
}


if (!$sst) {
    $sst  = "a.in_no ";
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

$sql = " select a.*,b.mb_id,b.mb_email,b.mb_name,b.mb_recommend {$sql_common} {$sql_search} {$sql_order} limit {$from_record}, {$rows} ";
$result = sql_query($sql);

$listall = '<a href="'.$_SERVER['SCRIPT_NAME'].'" class="ov_listall">전체목록</a>';

$g5['title'] =  ($g5['cn_item_name']).'-구매목록';

include_once(G5_ADMIN_PATH.'/admin.head.php');

$colspan = 14;
include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');

?>

<div class="local_ov01 local_ov">
    <?php echo $listall ?>
    <span class="btn_ov01"><span class="ov_txt">등록된 수</span><span class="ov_num"> <?php echo number_format($total_count) ?>개</span></span>
</div>

<form name="fsearch" id="fsearch" class="local_sch01 local_sch" method="get">


<span class='nowrap'>입금일:
  <input type="text" name="date_start_stx" value="<?php echo $date_start_stx?>" id="date_start_stx"  class="frm_input calendar-input" size="12" placeholder="시작일" />
  ~
  <input type="text" name="date_end_stx" value="<?php echo $date_end_stx?>" id="date_end_stx"  class="frm_input calendar-input" size="12" placeholder="종료일" />
  <a href="javascript:void(set_dates('<?=date("Y-m-d")?>','<?=date("Y-m-d")?>'));" class="btn_common" >오늘</a>
  <a href="javascript:void(set_dates('<?=date("Y-m-d",strtotime("-7 days"))?>','<?=date("Y-m-d")?>'));"  class="btn_common" >1주일</a>
  <a href="javascript:void(set_dates('<?=date("Y-m-d",strtotime("-1 month"))?>','<?=date("Y-m-d")?>'));"  class="btn_common" >1개월</a>
  <a href="javascript:void(set_dates('<?=date("Y-m-d",strtotime("-3 month"))?>','<?=date("Y-m-d")?>'));"  class="btn_common" >3개월</a>
  <a href="javascript:void(set_dates('<?=date("Y-m-d",strtotime("-6 month"))?>','<?=date("Y-m-d")?>'));"  class="btn_common" >6개월</a>
  <a href="javascript:void(set_dates('',''));"  class="btn_common" >전체</a>
  </span>


<select name='coin_stx' class="form-control input-sm  w-auto  mb-1" >
<option value=''>-입금수단-</option>
<?
foreach($g5['cn_cointype'] as $k=>$v){?>
<option value='<?=$k?>' <?=$coin_stx==$k?'selected':''?> ><?=$v?></option>
<? }?>
</select>


<select name="sfl" id="sfl">
<option value="a.mb_id"<?php echo get_selected($_GET['sfl'], "a.mb_id"); ?>>아이디</option>
<option value="b.mb_email"<?php echo get_selected($_GET['sfl'], "b.mb_email"); ?>>이메일</option>
<option value="b.mb_recommend"<?php echo get_selected($_GET['sfl'], "b.mb_recommend"); ?>>추천인 코드</option>

</select>

<label for="stx" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
<input type="text" name="stx" value="<?php echo $stx ?>" id="stx" class="frm_input">
<input type="submit" value="검색" class="btn_submit">

</form>



<form name="fboardlist" id="fboardlist" action="./insert_purchase_list_update.php" onsubmit="return fboardlist_submit(this);" method="post">
<input type="hidden" name="sst" value="<?php echo $sst ?>">
<input type="hidden" name="sod" value="<?php echo $sod ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
<input type="hidden" name="stx" value="<?php echo $stx ?>">
<input type="hidden" name="page" value="<?php echo $page ?>">
<input type="hidden" name="mb_stx" value="<?php echo $mb_stx ?>">
<input type="hidden" name="coin_stx" value="<?php echo $coin_stx ?>">

<input type="hidden" name="token" value="<?php echo $token ?>">
<?=help('Submit 상태의 경우 입금신청후 '.$g5[cn_intime_hour].'시간 이내 미입금시 자동 취소처리')?>
<div class="tbl_head01 tbl_wrap">
    <table>
    <caption><?php echo $g5['title']; ?> 목록</caption>
    <thead>
    <tr>
        <th rowspan="2" scope="col">
            <label for="chkall" class="sound_only"> 전체</label>
            <input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all(this.form)">
        </th>
        <th rowspan="2" scope="col">번호</a></th>
<th rowspan="2" scope="col"><?php echo subject_sort_link('a.mb_id') ?>소유아이디</th>
        <th rowspan="2" scope="col"><?php echo subject_sort_link('b.mb_email') ?>상품</a></th>
<th rowspan="2" scope="col"> 추천인</th>
        <th rowspan="2" scope="col"><?php echo subject_sort_link('a.in_coin') ?>지불수단</a></th>
        <th rowspan="2" scope="col">입금수량</th>
        <th rowspan="2" scope="col">지급수량</th>
<th rowspan="2" scope="col">판매자</th>
        <!--th scope="col">입금계좌</th-->
    <th rowspan="2" scope="col">현재단계</th>
<th colspan="4" scope="col">스케쥴</th>
<th colspan="3" scope="col">가격</th>
        <th rowspan="2" scope="col"><?php echo subject_sort_link('a.in_status') ?>상태</a></th>
        <th rowspan="2" scope="col">관리</th>
    </tr>
<tr>
<th scope="col">구매일</th>
<th scope="col">보유마감</th>
<th scope="col">기본보유일</th>
<th scope="col">경과일</th>
<th scope="col">구매가격</th>
<th scope="col">판매가격</th>
<th scope="col">이율</th>
</tr>
    </thead>
    <tbody>
    <?php
	$list_num = $total_count - ($page - 1) * $rows;
	
    for ($i=0; $row=sql_fetch_array($result); $i++) {
		
		//정산여부
		if(get_settlestat($row['st_set_date'])) $disabled = 'disabled';
		else $disabled = '';
		
        $one_update = '<a href="./insert_purchase_form.php?w=u&amp;in_no='.$row['in_no'].'&amp;'.$qstr.'" class="btn btn_03">관리</a>';

        $bg = 'bg'.($i%2);
		
    ?>
	
    <tr class="<?php echo $bg; ?>">
        <td class="td_chk">
            <label for="chk_<?php echo $i; ?>" class="sound_only">선택</label>
            <input type="checkbox" name="chk[]" value="<?php echo $i ?>" id="chk_<?php echo $i ?>" <?=$disabled?> >
            <input type="hidden" name="in_no[<?php echo $i ?>]" value="<?php echo $row['in_no'] ?>">
        </td>
       <td  class="td_num">
       <?=$list_num?>
        </td>
<td ><?php echo $row['mb_id'] ?></td>
<td >&nbsp;</td>
<td ><?php echo $row['mb_recommend'] ?></td>
       <td ><?=$g5['cn_cointype'][$row['in_token']]?></td>
       <td class="text-right"><?php echo number_format2($row['in_rsv_amt']) ?> <?=$g5['cn_cointype'][$row['in_token']]?></td>
       <td class="text-right"><?php echo number_format2($row['in_set_amt']) ?> <?=$g5['cn_cointype'][$row['in_set_token']]?></td>
<td class="td_datetime"><?php echo $row['in_set_date']?></td>
       <!--td  class="td_num_c3"><?php echo $row['account'] ?></td-->
    <td class="td_datetime"><?php echo $row['in_set_date']?></td>
<td class="td_right"><?php echo number_format2($row['in_balance'])?></td>
<td class="td_right"><?php echo number_format2($row['in_balance_last'])?></td>
<td class="td_right"><?php echo number_format2($row['in_balance_last']-$row['in_balance'])?></td>
<td class="td_right">&nbsp;</td>
<td class="td_right"><?php echo number_format2($row['in_balance'])?></td>
<td class="td_right"><?php echo number_format2($row['in_balance_last'])?></td>
<td class="td_right"><?php echo number_format2($row['in_balance_last']-$row['in_balance'])?></td>
        <td class="td_num_c3"><label for="deposit_status<?php echo $i; ?>" class="sound_only">입금상태</label>
          <span class="local_sch01 local_sch">
          <select id="in_stats[<?php echo $i ?>]" name="in_stats[<?php echo $i ?>]" <?=$disabled?> >
            <?
			foreach($g5['purchase_stat'] as $k=>$v) echo "<option value='{$k}' ".($row['in_stats']==$k ? 'selected':'').">{$v}</option>";
			?>
          </select>
          </span></td>
        <td class="td_mng td_mng_m">
            <?php echo $one_update ?>
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
    <input type="submit" name="act_button" value="선택수정" onclick="document.pressed=this.value" class="btn_02 btn">
    <input type="submit" name="act_button" value="선택삭제" onclick="document.pressed=this.value" class="btn_01 btn">
    <a href="./insert_purchase_form.php" id="rt_add" class="btn_04 btn">입금추가</a>
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
    if (!is_checked("chk[]")) {
        alert(document.pressed+" 하실 항목을 하나 이상 선택하세요.");
        return false;
    }

    if(document.pressed == "선택삭제") {
        if(!confirm("선택한 자료를 정말 삭제하시겠습니까?")) {
            return false;
        }
    }
	if(document.pressed == "선택수정") {
        if(!confirm("선택한 자료를 정말 수정하시겠습니까?")) {
            return false;
        }
    }
    return true;
}

$(function(){
  
});
</script>

<?php
include_once(G5_ADMIN_PATH.'/admin.tail.php');

?>
