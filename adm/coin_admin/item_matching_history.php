<?php
$sub_menu = "700760";

include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');

$sql_common = " from {$g5['cn_item_log']} as a ";
$sql_search = " where (1) ";

if($date_start_stx) {
	$sql_search .= " and a.lg_wdate >= '$date_start_stx 00:00:00' ";
	$qstr.="&date_start_stx=$date_start_stx";
}
if($date_end_stx) {
	$sql_search .= " and a.lg_wdate <= '$date_end_stx 23:59:59' ";
	$qstr.="&date_end_stx=$date_end_stx";
}

if ($stx) {
    $sql_search .= "and ($sfl like '%$stx%') ";
}

if ($item_stx) {
    $sql_search .= "and cn_item ='$item_stx' ";
	$qstr.="&item_stx=$item_stx";
}

if (!$sst) {
    $sst  = "a.lg_no ";
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

$sql = " select *
{$sql_common} {$sql_search} {$sql_order} limit {$from_record}, {$rows} ";
$result = sql_query($sql,1);

$listall = '<a href="'.$_SERVER['SCRIPT_NAME'].'" class="ov_listall">전체목록</a>';

$g5['title'] = $g5['cn_item_name'].'-매칭내역';

include_once(G5_ADMIN_PATH.'/admin.head.php');

$colspan = 11;
include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');

?>

<div class="local_ov01 local_ov">
    <?php echo $listall ?>
    <span class="btn_ov01"><span class="ov_txt">등록된 수</span><span class="ov_num"> <?php echo number_format($total_count) ?>개</span></span>
</div>

<form name="fsearch" id="fsearch" class="local_sch01 local_sch" method="get">


<span class='nowrap'>실행일:
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
<select name='item_stx' class="form-control input-sm  w-auto  mb-1" >
<option value=''>-상품검색-</option>
<?
foreach($g5['cn_item'] as $k=>$v){?>
<option value='<?=$k?>' <?=$item_stx==$k?'selected':''?> >
<?=$v[name_kr]?>
</option>
<? }?>
</select>
<input type="submit" value="검색" class="btn_submit">

</form>



<form name="fboardlist" id="fboardlist" action="./coin_swap_list_update.php" onsubmit="return fboardlist_submit(this);" method="post">
<input type="hidden" name="sst" value="<?php echo $sst ?>">
<input type="hidden" name="sod" value="<?php echo $sod ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
<input type="hidden" name="stx" value="<?php echo $stx ?>">
<input type="hidden" name="page" value="<?php echo $page ?>">

<input type="hidden" name="token" value="<?php echo $token ?>">

<div class="tbl_head01 tbl_wrap">
    <table>
    <caption><?php echo $g5['title']; ?> 목록</caption>
    <thead>
    <tr>
        <th scope="col">번호</a></th>
<th scope="col"><?php echo subject_sort_link('a.lg_wdate') ?>실행일</th>
<th scope="col"><?php echo subject_sort_link('a.lg_distri') ?>구분</th>
<th scope="col"><?php echo subject_sort_link('a.cn_item') ?>해당코인</a></th>
<th scope="col"><?php echo subject_sort_link('a.mb_id') ?>실행자</th>
    <th scope="col">총지급수량</th>
<th scope="col">총지급액</th>
<th scope="col">총수수료</th>
<th scope="col" >LOG</th>

    <th scope="col">내역</th>
<th scope="col">관리</th>
    </tr>
    </thead>
    <tbody>
    <?php
	$list_num = $total_count - ($page - 1) * $rows;
	
    for ($i=0; $row=sql_fetch_array($result); $i++) {		
		
        $one_delete = '<a href="./item_matching_history_update.php?w=d&amp;lg_no='.$row['lg_no'].'&amp;'.$qstr.'" class="btn btn_01 btn-delete"  >삭제</a>';
		
		$one_history = '<a href="./item_trade_list.php?lg_no='.$row['lg_no'].'" class="btn btn_03"  >내역</a>';

        $bg = 	'bg'.($i%2);
		
		if($row[lg_distri]=='p2p') $kind='P2P';
		else if($row[lg_distri]=='hq') $kind='HQ사';
    ?>
	
    <tr class="<?php echo $bg; ?>">
       <td  class="td_num">
       <?=$list_num?>
        </td>
<td class="td_datetime"><?php echo $row['lg_wdate']?></td>
<td ><?php echo strtoupper($row[lg_distri])?></td>
<td ><?=$row['cn_item_name']?></td>
<td ><strong><?php echo  $row['mb_id'] ?></strong></td>
    <td class="text-right"><?php echo $row['lg_cnt']?number_format2($row['lg_cnt']):'-' ?></td>
<td class="text-right"><?php echo $row['lg_amt']?  number_format2($row['lg_amt']):'-' ?></td>
<td class="text-right"><?php echo $row['lg_fee']?  number_format2($row['lg_fee']):'-' ?></td>
<td class='text-left' width=400><?php echo cut_str(strip_tags($row['lg_log']),100) ?></td>
    <td class="td_mng td_mng_s"><?php echo $one_history ?></td>
<td class="td_mng td_mng_s"><?php echo $one_delete?></td>
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

</form>

<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?'.$qstr.'&amp;page='); ?>

<script>
function set_dates(s,e){
	$("input[name='date_start_stx']","#fsearch").val(s);
	$("input[name='date_end_stx']","#fsearch").val(e);
}

$(function(){
 
 	$('.btn-delete').click(function(){		
		
		if(!confirm("로그내역만 삭제됩니다.")) return false;
		
		$(this).attr('href',$(this).attr('href')+"&token="+get_ajax_token());
	
	});
	
});
</script>

<?

include_once(G5_ADMIN_PATH.'/admin.tail.php');
