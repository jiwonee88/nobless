<?php
$sub_menu = "700990";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');

$sql_common = " from {$g5['cn_item_cart']} a left outer join  {$g5['member_table']} as b on(a.mb_id=b.mb_id) ";
$sql_search = " where a.is_soled='1'  ";

//지사가 접근시
if($is_branch){
	$sql_search .= " and 
		(
		b.mb_recommend='{$member[mb_id]}' 		
		or b.mb_recommend in (select mb_id from {$g5[member_table]} where mb_recommend='{$member[mb_id]}' )
		or b.mb_recommend in (select mb_id from {$g5[member_table]} where mb_recommend in (select mb_id from {$g5[member_table]} where mb_recommend='{$member[mb_id]}' )  )		
		)
		";
}

if($date_start_stx) {
	$sql_search .= " and a.ct_wdate >= '$date_start_stx 00:00:00' ";
	$qstr.="&date_start_stx=$date_start_stx";
}
if($date_end_stx) {
	$sql_search .= " and a.ct_wdate <= '$date_end_stx 23:59:59' ";
	$qstr.="&date_end_stx=$date_end_stx";
}
if($item_stx) {
	$sql_search .= " and a.cn_item = '$item_stx' ";
	$qstr.="&item_stx=$item_stx";
}
if($code_stx) {
	$sql_search .= " and a.code like '%$code_stx%' ";
	$qstr.="&code_stx=$code_stx";
}
if($trade_stx!='') {
	$sql_search .= " and a.is_trade = '$trade_stx' ";
	$qstr.="&trade_stx=$trade_stx";
}
if ($stx) {
	if($sfl=='a.mb_id') $sql_search .= "and ($sfl = '$stx') ";
    else $sql_search .= "and ($sfl like '%$stx%') ";
}

if ($mb_id_stx) {
    $sql_search .= " and a.mb_id='$mb_id_stx' ";	
	$qstr.="&mb_id_stx=$mb_id_stx";
}

if ($fmb_id_stx) {
    $sql_search .= " and a.fmb_id='$fmb_id_stx' ";	
	$qstr.="&fmb_id_stx=$fmb_id_stx";
}
if ($give_stx) {
    $sql_search .= " and a.ct_logs like '%직접지급%'";	
	$qstr.="&give_stx=$give_stx";
}
if (!$sst) {
    $sst  = "a.code ";
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

$sql = " select a.* {$sql_common} {$sql_search} {$sql_order} limit {$from_record}, {$rows} ";
$result = sql_query($sql);

$listall = '<a href="'.$_SERVER['SCRIPT_NAME'].'" class="ov_listall">전체목록</a>';

$g5['title'] =  ($g5['cn_item_name']).'-판매목록';

include_once(G5_ADMIN_PATH.'/admin.head.php');

$colspan = 16;
include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');

$temp1=sql_fetch("select count(*) cnt from {$g5['cn_item_cart']} where date(ct_validdate)=date(now())",1);
$temp2=sql_fetch("select count(*) cnt  from {$g5['cn_item_cart']} where date(ct_validdate) = ".(date("Y-m-d",time()+86400) ));
?>

<div class="local_ov01 local_ov">
    <?php echo $listall ?>
    <span class="btn_ov01"><span class="ov_txt">등록된 수</span><span class="ov_num"> <?php echo number_format($total_count) ?>개</span></span>
	<span class="btn_ov01"><span class="ov_txt">오늘보유마감</span><span class="ov_num"> <?php echo number_format($temp1[cnt]) ?>개</span></span>
	<span class="btn_ov01"><span class="ov_txt">내일보유마감</span><span class="ov_num"> <?php echo number_format($temp2[cnt]) ?>개</span></span>
</div>

<form name="fsearch" id="fsearch" class="local_sch01 local_sch" method="get">

<span class='nowrap'>구매일:
<input type="text" name="date_start_stx" value="<?php echo $date_start_stx?>" id="date_start_stx"  class="frm_input calendar-input" size="12" placeholder="시작일" />
~
<input type="text" name="date_end_stx" value="<?php echo $date_end_stx?>" id="date_end_stx"  class="frm_input calendar-input" size="12" placeholder="종료일" />
</span>


<input name="code_stx" type="text" class="frm_input" id="code_stx" placeholder="코드검색" value="<?php echo $code_stx ?>">

<input name="mb_id_stx" type="text" class="frm_input" id="mb_id_stx" placeholder="소유자아이디" value="<?php echo $mb_id_stx ?>" size="15">
<input name="fmb_id_stx" type="text" class="frm_input" id="fmb_id_stx" placeholder="판매자/지급자아이디" value="<?php echo $fmb_id_stx ?>" size="20">
(<label>
<input type="checkbox" name="give_stx" value="1" id="give_stx" <?=$give_stx=='1'?'checked':''?> >
직접지급</label>)
<select name='trade_stx' class="form-control input-sm  w-auto  mb-1" >
<option value=''>-거래상태-</option>
<option value='1' <?=$trade_stx==1?'selected':''?> >거래중</option>
<option value='0' <?=$trade_stx==1?'selected':''?> >미거래중</option>

</select>
<select name='item_stx' class="form-control input-sm  w-auto  mb-1" >
<option value=''>-상품검색-</option>
<?
foreach($g5['cn_item'] as $k=>$v){?>
<option value='<?=$k?>' <?=$item_stx==$k?'selected':''?> ><?=$v[name_kr]?></option>
<? }?>
</select>

<select name="sfl" id="sfl">
<option value="a.mb_id"<?php echo get_selected($_GET['sfl'], "a.mb_id"); ?>>소유아이디</option>
<option value="a.smb_id"<?php echo get_selected($_GET['sfl'], "a.smb_id"); ?>>소유서브아이디</option>
<option value="a.fmb_id"<?php echo get_selected($_GET['sfl'], "a.fmb_id"); ?>>판매자아이디</option>
<option value="a.fsmb_id"<?php echo get_selected($_GET['sfl'], "a.fsmb_id"); ?>>판매자서브아이디</option>
<option value="b.mb_recommend"<?php echo get_selected($_GET['sfl'], "b.mb_recommend"); ?>>지사/지점코드</option>
<option value="a.ct_logs"<?php echo get_selected($_GET['sfl'], "a.ct_logs"); ?> >로그검색</option>
</select>

<label for="fmb_id_stx" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
<input type="text" name="stx" value="<?php echo $stx ?>" id="stx" class="frm_input">
<input type="submit" value="검색" class="btn_submit">

</form>


<form name="fboardlist" id="fboardlist" action="./item_cart_list_update.php" onsubmit="return fboardlist_submit(this);" method="post">
<input type="hidden" name="sst" value="<?php echo $sst ?>">
<input type="hidden" name="sod" value="<?php echo $sod ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
<input type="hidden" name="stx" value="<?php echo $stx ?>">
<input type="hidden" name="page" value="<?php echo $page ?>">
<input type="hidden" name="mb_stx" value="<?php echo $mb_stx ?>">
<input type="hidden" name="coin_stx" value="<?php echo $coin_stx ?>">
<input type="hidden" name="code_stx" value="<?php echo $code_stx ?>">


<input type="hidden" name="token" value="<?php echo $token ?>">
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
<th width="210" rowspan="2" scope="col">코드</th>
<th rowspan="2" scope="col"><?php echo subject_sort_link('a.mb_id',$qstr) ?>소유아이디</th>
        <th rowspan="2" scope="col"><?php echo subject_sort_link('a.cn_item',$qstr) ?>상품</a></th>
<th rowspan="2" scope="col">판매/지급</th>
        <!--th scope="col">입금계좌</th-->
    <th rowspan="2" scope="col"><?php echo subject_sort_link('a.ct_class',$qstr) ?>현재단계</th>
<th colspan="4" scope="col">스케쥴</th>
<th colspan="3" scope="col">가격</th>
<th rowspan="2" scope="col"><?php echo subject_sort_link('a.is_trade',$qstr) ?>상태</th>
<th rowspan="2" scope="col"><?php echo subject_sort_link('a.trade_cnt',$qstr) ?>거래중/판매/잔여</th>
<th rowspan="2" scope="col"><?php echo subject_sort_link('a.trade_cnt',$qstr) ?>분할<br>
판매</th>
    </tr>
<tr>
<th scope="col"><?php echo subject_sort_link('a.ct_buy_date',$qstr) ?>구매일</th>
<th scope="col"><?php echo subject_sort_link('a.ct_validdate',$qstr) ?>보유마감</th>
<th scope="col">기본보유일</th>
<th scope="col">잔여일</th>
<th scope="col"><?php echo subject_sort_link('a.ct_buy_price',$qstr) ?>구매가격</th>
<th scope="col"><?php echo subject_sort_link('a.ct_sell_price',$qstr) ?>예정가격</th>
<th scope="col"><?php echo subject_sort_link('a.ct_interest',$qstr) ?>이율</th>
</tr>
    </thead>
    <tbody>
    <?php
	
	$list_num = $total_count - ($page - 1) * $rows;
	
    for ($i=0; $row=sql_fetch_array($result); $i++) {
        $bg = 'bg'.($i%2);
		
		
		$past_day=ceil( (strtotime($row['ct_validdate'])-time() ) /86400 );
		
    ?>
	
<tr class="<?php echo $bg; ?>">
<?
	if(!auth_check($auth[$sub_menu], 'w',1)){?>
<td class="td_chk">
	<label for="chk_<?php echo $i; ?>" class="sound_only">선택</label>
	<input type="checkbox" name="chk[]" value="<?php echo $i ?>" id="chk_<?php echo $i ?>" <?=$disabled?> >
	<input type="hidden" name="code[<?php echo $i ?>]" value="<?php echo $row['code'] ?>">
</td>
<? }?>
<td  class="td_num">
<?=$list_num?>
</td>
<td ><?php echo $row['code'] ?>
<?=$row['ct_logs']!=''?"<p class='fred'>{$row['ct_logs']}</p>":''?></td>
<td class='mb-info-open' data-id='<?php echo $row['mb_id'] ?>'><?php echo $row['smb_id']?$row['smb_id'].' @ ':'' ?><?php echo $row['mb_id'] ?> </td>
<td ><?php echo $g5['cn_item'][$row['cn_item']][name_kr] ?></td>
<td><?php echo $row['fsmb_id']&& $row['fsmb_id']!=$row['fmb_id']?$row['fsmb_id'].' @ ':'' ?> <?php echo $row['fmb_id']?> </td>
    <td><?php echo $row['ct_class']?></td>
<td class="td_right"><span class="td_datetime"><?php echo substr($row['ct_wdate'],0,16)?></span></td>
<td class="td_right"><span class="td_datetime"><?php echo $row['ct_validdate']?></span></td>
<td class="td_right"><span class="td_datetime"><?php echo $row['ct_days']?></span></td>
<td class="td_right"><?php echo !$past_day?'0':$past_day?> day</td>
<td class="td_right"><?php echo number_format2($row['ct_buy_price'])?></td>
<td class="td_right"><?php echo number_format2($row['ct_sell_price'])?></td>
<td class="td_right"><?php echo $row['ct_interest']?>%</td>
<td class="td_right"><?php echo $g5['cn_cartstat'][$row['is_trade']]?></td>
<td class="td_right"><?php echo "<span>".number_format2($row['trade_amt'])."</span>"?>/<?php echo "<span class='fblue'>".number_format2($row['soled_amt'])."</span>"?>/<?php echo "<span class='fred'>".number_format2($row['ct_sell_price']-$row['soled_amt'])."</span>"?></td>
<td class="td_right"><?php echo $row['trade_cnt']?>/<?php echo $row['div_cnt']?></td>
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
	
    return true;
}

$(function(){
  
});
</script>

<?php
include_once(G5_ADMIN_PATH.'/admin.tail.php');

?>
