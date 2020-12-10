<?php

auth_check($auth[$sub_menu], 'r');

$sql_common = " from {$g5['cn_set_table']} a ";
$sql_search = " where (1) ";

if($sub_menu == "800200") $sql_search .= " and st_pkind='stake_re' ";
else if($sub_menu == "800210") $sql_search .= " and st_pkind='fee' ";
else if($sub_menu == "800220") $sql_search .= " and st_pkind='fee2' ";

if($date_start_stx) {
	$sql_search .= " and a.st_date >= '$date_start_stx 00:00:00' ";
	$qstr.="&date_start_stx=$date_start_stx";
}
if($date_end_stx) {
	$sql_search .= " and a.st_date <= '$date_end_stx 23:59:59' ";
	$qstr.="&date_end_stx=$date_end_stx";
}

if ($stx) {
    $sql_search .= "and ($sfl like '%$stx%') ";
}

if (!$sst) {
    $sst  = "a.st_no ";
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

$g5['title'] = '정산-';

if($sub_menu == "800200") $g5['title'].= "스테이킹 채굴";
else if($sub_menu == "800210") $g5['title'].= "추천인 롤업";
else if($sub_menu == "800220") $g5['title'].= "직급보너스";
include_once(G5_ADMIN_PATH.'/admin.head.php');

$colspan = 14;
include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');

?>

<div class="local_ov01 local_ov">
    <?php echo $listall ?>
    <span class="btn_ov01"><span class="ov_txt">등록된 수</span><span class="ov_num"> <?php echo number_format($total_count) ?>개</span></span>
</div>

<form name="fsearch" id="fsearch" class="local_sch01 local_sch" method="get">


<span class='nowrap'>스왑일:
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
<th scope="col"><?php echo subject_sort_link('b.mb_email') ?>정산일</th>
<th scope="col"><?php echo subject_sort_link('b.mb_email') ?>구분</th>
<th scope="col"><?php echo subject_sort_link('a.st_token') ?>해당코인</a></th>
<th scope="col"><?php echo subject_sort_link('a.mb_id') ?>적용회원</th>
    <th scope="col">총지급수량</th>
<th scope="col" >LOG</th>

        <th scope="col">처리일시</th>
<th scope="col">결과</th>
        <th scope="col">내역</th>
<th scope="col">관리</th>
    </tr>
    </thead>
    <tbody>
    <?php
	$list_num = $total_count - ($page - 1) * $rows;
	
    for ($i=0; $row=sql_fetch_array($result); $i++) {
		
		$table_stx=$g5['cn_point']."_".date('ym',strtotime($row[st_wdate]));
        $one_delete = '<a href="./fee_settle_list_update.php?w=d&amp;from_menu='.$sub_menu.'&amp;st_no='.$row['st_no'].'&amp;'.$qstr.'" class="btn btn_01 btn-delete"  >삭제</a>';
		
		if($sub_menu == "800210"){
			$one_history = '<a href="./fee_settle2_history.php?table_stx='.$table_stx.'&pkind_stx='.$row['st_pkind'].'&link_stx='.$row['st_no'].'&amp;'.$qstr.'" class="btn btn_03"  >내역</a>';
		}else if($sub_menu == "800220"){
			$one_history = '<a href="./fee_settle3_history.php?table_stx='.$table_stx.'&pkind_stx='.$row['st_pkind'].'&link_stx='.$row['st_no'].'&amp;'.$qstr.'" class="btn btn_03"  >내역</a>';
		} 


		

        $bg = 	'bg'.($i%2);
		
		if($row[st_pkind]=='stake_re') $kind='스테이킹 채굴';
		else if($row[st_pkind]=='fee') $kind='추천롤업수당';
		else if($row[st_pkind]=='fee2') $kind="서브계정롤업";
    ?>
	
    <tr class="<?php echo $bg; ?>">
       <td  class="td_num">
       <?=$list_num?>
        </td>
<td class="td_datetime"><?php echo $row['st_result']=='1'? $row['st_date']:''?></td>
<td ><?php echo $kind ?></td>
<td ><?=$g5['cn_cointype'][$row['st_token']]?></td>
<td ><strong><?php echo  $row['st_cnt']?  number_format($row['st_cnt']):'-' ?></strong></td>
    <td class="text-right"><?php echo $row['st_cnt']?number_format2($row['st_amt'],6) .($g5['cn_cointype'][$row['st_token']]):'-' ?></td>
<td class='text-left' width=400><?php echo strip_tags($row['st_log']) ?></td>
        <td class="td_datetime">
		<?php echo $row['st_wdate']?>
        </td>
<td class="td_mng td_mng_s text-<?=$g5['cn_stats_css'][$row[st_result]]?>"><strong><?php echo $row[st_result]=='1'?'OK':'FAIL'?></strong></a></td>
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

<div class="btn_fixed_top"><a href="./fee_settle_form.php?f_kind=<?=$sub_menu == "800210"?'fee':'fee2'?>" id="settle_add" class="btn_04 btn">정산실행</a></div>

</form>

<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?'.$qstr.'&amp;page='); ?>

<script>
function set_dates(s,e){
	$("input[name='date_start_stx']","#fsearch").val(s);
	$("input[name='date_end_stx']","#fsearch").val(e);
}

function fboardlist_submit(f)
{
   
}

$(function(){
 	$('.btn-delete').click(function(){		
		
		if(!confirm("지급액을 다시 회수하고 내역을 삭제하시겠습니까?\n\n삭제된 내역은  절대 복구가 불가능합니다")) return false;
		
		$(this).attr('href',$(this).attr('href')+"&token="+get_ajax_token());
		return;
	});
	
	$('#settle_add').click(function(){
		event.preventDefault();
        window.open(this.href, "win_settle_form", "left=0,top=0,width=1150,height=900");
        return false;
	});
});
</script>

