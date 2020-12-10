<?php
$sub_menu = "800200";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');

$sql_common = " from {$g5['cn_swap_table']} a 
left outer join  {$g5['member_table']} as b on(a.mb_id=b.mb_id) ";
$sql_search = " where (1) ";

if($date_start_stx) {
	$sql_search .= " and a.sw_wdate >= '$date_start_stx 00:00:00' ";
	$qstr.="&date_start_stx=$date_start_stx";
}
if($date_end_stx) {
	$sql_search .= " and a.sw_wdate <= '$date_end_stx 23:59:59' ";
	$qstr.="&date_end_stx=$date_end_stx";
}
if($coin_stx) {
	$sql_search .= " and a.sw_token = '$coin_stx' ";
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
    $sst  = "a.sw_no ";
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

$sql = " select a.*,b.mb_id,b.mb_email,b.mb_name,b.mb_recommend
{$sql_common} {$sql_search} {$sql_order} limit {$from_record}, {$rows} ";
$result = sql_query($sql,1);

$listall = '<a href="'.$_SERVER['SCRIPT_NAME'].'" class="ov_listall">전체목록</a>';

$g5['title'] = '정산-스테이킹 채굴';

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


<select name='coin_stx' class="form-control input-sm  w-auto  mb-1" >
<option value=''>-코인구분-</option>
<?
foreach($g5['cn_cointype'] as $k=>$v){?>
<option value='<?=$k?>' <?=$coin_stx==$k?'selected':''?> ><?=$v?></option>
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
<input type="hidden" name="mb_stx" value="<?php echo $mb_stx ?>">
<input type="hidden" name="local_stx" value="<?php echo $local_stx ?>">

<input type="hidden" name="token" value="<?php echo $token ?>">

<div class="tbl_head01 tbl_wrap">
    <table>
    <caption><?php echo $g5['title']; ?> 목록</caption>
    <thead>
    <tr>
        <th scope="col">번호</a></th>
<th scope="col"><?php echo subject_sort_link('b.mb_email') ?>정산일</th>
<th scope="col"><?php echo subject_sort_link('b.mb_email') ?>구분</th>
<th scope="col"><?php echo subject_sort_link('a.sw_token') ?>해당코인</a></th>
<th scope="col"><?php echo subject_sort_link('a.mb_id') ?>적용회원</th>
    <th scope="col">총스테이킹</th>
<th scope="col">총채굴</th>
<th scope="col">시세</th>
<!--th scope="col">스왑계좌</th-->
        <th scope="col">처리일시</th>
        <th scope="col">내역</th>
<th scope="col">관리</th>
    </tr>
    </thead>
    <tbody>
    <?php
	$list_num = $total_count - ($page - 1) * $rows;
	
    for ($i=0; $row=sql_fetch_array($result); $i++) {
		
		
        $one_delete = '<a href="./fee_staking_list_update.php?w=u&amp;sw_no='.$row['sw_no'].'&amp;'.$qstr.'" class="btn btn_03" onclick="return confirm(\'정말 삭제하시겠습니까?\\n\\n삭제된 내역은 복구가 절대 불가능합니다\');"  >삭제</a>';

        $bg = 'bg'.($i%2);
		
    ?>
	
    <tr class="<?php echo $bg; ?>">
       <td  class="td_num">
       <?=$list_num?>
        </td>
<td class="td_datetime"><?php echo $row['sw_stats']=='3'? $row['sw_set_date']:''?></td>
<td ><?php echo $row['mb_email'] ?></td>
<td ><?=$g5['cn_cointype'][$row['sw_token']]?></td>
<td ><?php echo $row['mb_id'] ?></td>
    <td class="text-right"><?php echo number_format2($row['sw_amt']) ?> <?=$g5['cn_cointype'][$row['sw_token']]?></td>
<td class="text-right"><?php echo number_format2($row['sw_fee']) ?></td>
<td class="td_datetime"><?php echo $row['sw_stats']=='3'? $row['sw_set_date']:''?></td>
<!--td  class="td_num_c3"><?php echo $row['account'] ?></td-->
        <td class="td_datetime">
		<?php echo $row['sw_stats']=='3'? $row['sw_set_date']:''?>
        </td>
        <td class="td_mng td_mng_m"><?php echo $one_update ?></td>
<td class="td_mng td_mng_m"><?php echo $one_delete?></td>
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

<div class="btn_fixed_top"><a href="./fee_settle_form.php" id="settle_add" class="btn_04 btn">정산실행</a></div>

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
 	$('.delete-btn').click(function(){
		if(!confirm("선택한 자료를 정말 수정하시겠습니까?\n\n삭제하시면 복구가 불가능합니다")) {
            return false;
        }
	});
	$('#settle_add').click(function(){
		event.preventDefault();
        window.open(this.href, "win_settle_form", "left=0,top=0,width=1150,height=900");
        return false;
	});
});
</script>

<?php
include_once(G5_ADMIN_PATH.'/admin.tail.php');

?>
