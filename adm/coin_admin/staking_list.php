<?php
$sub_menu = "700100";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');

$sql_common = " from {$g5['cn_stake_table']} a 
left outer join  {$g5['member_table']} as b on(a.mb_id=b.mb_id)
";
$sql_search = " where (1)  ";
$fields='';

if ($stx) {	
	if($sfl=='a.mb_id' || $sfl=='a.smb_id') $sql_search .= "and ($sfl = '$stx') ";
    else $sql_search .= "and ($sfl like '%$stx%') ";
}
if ($stats_stx) {
    $sql_search .= " and a.sk_stats='$stats_stx' ";	
	$qstr.="&sk_stats=$stats_stx";
}
if ($coin_stx) {	
    $sql_search .= " and a.sk_token='$coin_stx' ";	
	$qstr.="&coin_stx=$coin_stx";
}
if($dates_stx && $datee_stx){	
	$sql_search .= " and 
	( ( a.sk_sdate between '$dates_stx' and '$datee_stx 23:59:59')   or ( a.sk_edate between '$dates_stx' and '$datee_stx 23:59:59') 
	or (a.sk_sdate<='$dates_stx' and a.sk_edate>= '$datee_stx') )
	";
	$qstr.="&dates_stx=$dates_stx&datee_stx=$datee_stx";
}else if($dates_stx){
    $sql_search .= " and ( a.sk_sdate>='$dates_stx' or  a.sk_edate>='$dates_stx' ) ";	
	$qstr.="&dates_stx=$dates_stx";
}else if($datee_stx){
    $sql_search .= " and ( a.sk_sdate <= '$datee_stx 23:59:59' or a.sk_edate <= '$datee_stx 23:59:59')";	
	$qstr.="&datee_stx=$datee_stx";
}

if (!$sst) {
    $sst  = "a.sk_no ";
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
$result = sql_query($sql,1);

$listall = '<a href="'.$_SERVER['SCRIPT_NAME'].'" class="ov_listall">전체목록</a>';

$g5['title'] = "스테이킹";

include_once(G5_ADMIN_PATH.'/admin.head.php');
include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');

//echo $sql;

$colspan = 13;

?>

<div class="local_ov01 local_ov">
    <?php echo $listall ?>
    <span class="btn_ov01"><span class="ov_txt">등록된 수</span><span class="ov_num"> <?php echo number_format($total_count) ?>개</span></span>
</div>

<form name="fsearch" id="fsearch" class="local_sch01 local_sch" method="get">

<input type="text" name="dates_stx" value="<?php echo $dates_stx?>" id="dates_stx"  class="frm_input calendar-input" size="12" placeholder="시작일" autocomplete='off'/>
~
<input type="text" name="datee_stx" value="<?php echo $datee_stx?>" id="datee_stx"  class="frm_input calendar-input" size="12" placeholder="종료일"  autocomplete='off'/>
<select name="stats_stx" id="stats_stx">
<option value="">-상태-</option>
<?
foreach($g5['stake_stat'] as $k => $v){?>
<option value="<?=$k?>" <?php echo get_selected($stats_stx, $k); ?> ><?=$v?></option>
<? }?>
</select>
<select name='coin_stx' class="form-control input-sm  w-auto  mb-1" >
<option value=''>-코인구분-</option>
<?
foreach($g5['cn_cointype'] as $k=>$v){?>
<option value='<?=$k?>' <?=$coin_stx==$k?'selected':''?> >
<?=$v?>
</option>
<? }?>
</select>
<label for="sfl" class="sound_only">

검색대상</label>
<select name="sfl" id="sfl">
<option value="a.mb_id"<?php echo get_selected($_GET['sfl'], "a.mb_id"); ?>>아이디</option>

<option value="b.mb_email"<?php echo get_selected($_GET['sfl'], "b.mb_email"); ?>>이메일</option>
<option value="b.mb_recommend"<?php echo get_selected($_GET['sfl'], "b.mb_recommend"); ?>>추천인 코드</option>
</select>

<label for="stx" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
<input type="text" name="stx" value="<?php echo $stx ?>" id="stx" class="frm_input">
<input type="submit" value="검색" class="btn_submit">

</form>

<form name="fboardlist" id="fboardlist" action="./staking_list_update.php" onsubmit="return fboardlist_submit(this);" method="post">
<input type="hidden" name="sst" value="<?php echo $sst ?>">
<input type="hidden" name="sod" value="<?php echo $sod ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
<input type="hidden" name="stx" value="<?php echo $stx ?>">
<input type="hidden" name="page" value="<?php echo $page ?>">
<input type="hidden" name="stats_stx" value="<?php echo $stats_stx ?>">
<input type="hidden" name="coin_stx" value="<?php echo $coin_stx ?>">
<input type="hidden" name="dates_stx" value="<?php echo $dates_stx ?>">
<input type="hidden" name="datee_stx" value="<?php echo $datee_stx ?>">
<?=help('마이닝 수량 = USD총액 * '.number_format2($cset['staking_reward']).'% / '.$sise['sise_i'].'USD')?>
<div class="tbl_head01 tbl_wrap">
    <table>
    <thead>
		<tr>
		<th  scope="col"> <label for="chkall" class="sound_only"> 전체</label>
            <input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all(this.form)"></th>
<th  scope="col">번호</a></th>
<th scope="col"><?php echo subject_sort_link('a.sk_token') ?>코인</a></th>
<th scope="col"><?php echo subject_sort_link('a.mb_id') ?>아이디</th>
		<th scope="col"><?php echo subject_sort_link('b.mb_email') ?>이메일</th>
<th  scope="col">수량</th>
		<th  scope="col">USD</th>
		<th  scope="col">마이닝 <?php echo $g5['cn_cointype'][$g5['cn_reward_coin']] ?>/day
 </th>
<th  scope="col">시작일</th>
		<th  scope="col">종료일</th>
		<th  scope="col">신청일</th>
		<th  scope="col">상태</th>
		<th  scope="col">관리</th>
		</tr>
    </thead>
	
	
    <tbody>
    <?php
	$list_num = $total_count - ($page - 1) * $rows;
	
    for ($i=0; $row=sql_fetch_array($result); $i++) {
			
		 $one_update = '<a href="./staking_form.php?w=u&amp;sk_no='.$row['sk_no'].'&amp;'.$qstr.'" class="btn btn_03">관리</a>';
        $bg = 'bg'.($i%2);		
			
    ?>

	<tr class="<?php echo $bg; ?>">
<td class="td_chk">
            <label for="chk_<?php echo $i; ?>" class="sound_only">선택</label>
            <input type="checkbox" name="chk[]" value="<?php echo $i ?>" id="chk_<?php echo $i ?>" <?=$disabled?> >
            <input type="hidden" name="sk_no[<?php echo $i ?>]" value="<?php echo $row['sk_no'] ?>">
        </td>
<td  class="td_num"><?=$list_num?></td>
<td ><?php echo $g5['cn_cointype'][$row['sk_token']] ?></td>
<td ><?php echo $row['mb_id'] ?></td>
	<td ><?php echo $row['mb_email'] ?></td>
<td  class='text-right'><?php echo number_format2($row['sk_amt'],2) ?></td>
	<td  class='text-right'>≈ <?php echo number_format2(swap_usd($row['sk_amt'],$row['sk_token'],$sise),2) ?></td>
	<td class='text-right' >≈  <?php echo number_format2(swap_coin($row['sk_amt']*$cset['staking_reward']/100,$row['sk_token'],$g5['cn_reward_coin'],$sise),2) ?> </td>
	<td class='td_datetime text-center' ><?php echo $row['sk_sdate'] ?></td>
	<td class='td_datetime text-center' ><?php echo $row['sk_stats']=='3'?$row['sk_edate']:'-' ?></td>
	<td class='td_datetime text-center' ><?php echo $row['sk_wdate'] ?></td>
<td class="td_num_c3"><span class="local_sch01 local_sch">
<select id="sk_stats[<?php echo $i ?>]" name="sk_stats[<?php echo $i ?>]"  >
<?
foreach($g5['stake_stat'] as $k => $v){?>
<option value="<?=$k?>" <?php echo get_selected($row[sk_stats], $k); ?> ><?=$v?></option>
<? }?>
</select>
</span></td>
<td class="td_mng td_mng_m"><?php echo $one_update ?></td>
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
<a href="./staking_form.php" id="rt_add" class="btn_04 btn">스테이킹추가</a>
</div>

</form>

<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?'.$qstr.'&amp;page='); ?>

<script>
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
