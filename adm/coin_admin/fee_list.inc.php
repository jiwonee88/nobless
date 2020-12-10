<?
if($table_stx!='' && preg_match("/$g5[cn_point]/",$table_stx) ) $point_table=$table_stx;
else  $point_table=$g5['cn_point']."_".date('ym');
	
$sql_common = " from {$point_table} a 
left outer join  {$g5['member_table']} as b on(a.mb_id=b.mb_id)
left outer join  {$g5['member_table']} as c on(a.smb_id=c.mb_id)";
$sql_search = " where (1) /* a.pkind in ('fee','fee2') */";
$fields='';

if ($stx) {
	
	if($sfl=='a.mb_id' || $sfl=='a.smb_id') $sql_search .= "and ($sfl = '$stx') ";
    else $sql_search .= "and ($sfl like '%$stx%') ";
}
if ($pkind_stx) {
    $sql_search .= " and a.pkind='$pkind_stx' ";	
	$qstr.="&pkind_stx=$pkind_stx";
}
if ($coin_stx) {	
    $sql_search .= " and a.ot_coin='$coin_stx' ";	
	$qstr.="&coin_stx=$coin_stx";
}

if($dates_stx){
    $sql_search .= " and a.wdate>='$dates_stx' ";	
	$qstr.="&dates_stx=$dates_stx";
}
if($datee_stx){
    $sql_search .= " and a.wdate<='$datee_stx 23:59:59' ";	
	$qstr.="&datee_stx=$datee_stx";
}
if($table_stx){
	$qstr.="&table_stx=$table_stx";
}
if($link_stx){
	$qstr.="&link_no=$link_stx";
}

if (!$sst) {
    $sst  = "a.pt_no ";
    $sod = "desc";
}else{
	if($sst=='a.pkind') $sst  = " field(a.pkind,'".implode("','",array_keys($g5['cn_pkind']))."') ";
}

$sql_order = " order by $sst $sod ";

$sql = " select count(*) as cnt {$sql_common} {$sql_search} {$sql_search_add}";
$row = sql_fetch($sql,1);
$total_count = $row['cnt'];

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = " select a.*,b.mb_id,b.mb_name,b.mb_recommend,c.mb_no cmb_no,c.mb_name cmb_name,c.mb_recommend cmb_recommend  $fields {$sql_common} {$sql_search}  {$sql_search_add} {$sql_order} limit {$from_record}, {$rows} ";
$result = sql_query($sql);

$listall = '<a href="'.$_SERVER['SCRIPT_NAME'].'" class="ov_listall">전체목록</a>';


include_once(G5_ADMIN_PATH.'/admin.head.php');
include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');

//echo $sql;

$colspan = 16;

?>

<div class="local_ov01 local_ov">
    <?php echo $listall ?>
    <span class="btn_ov01"><span class="ov_txt">등록된 수</span><span class="ov_num"> <?php echo number_format($total_count) ?>개</span></span>
</div>

<form name="fsearch" id="fsearch" class="local_sch01 local_sch" method="get">

<select name="table_stx" id="table_stx">
<option value=''>-월별구분-</option>
<?
$re=sql_query("SELECT * FROM information_schema.tables WHERE TABLE_NAME like '{$g5[cn_point]}\_%' and TABLE_NAME!= '{$g5[cn_pointsum]}' and TABLE_SCHEMA='".G5_MYSQL_DB."'   group by TABLE_NAME",1);
while($td=sql_fetch_array($re)) echo "<option value='{$td[TABLE_NAME]}' ".($table_stx==$td[table_name] ? 'selected':'').">{$td[TABLE_NAME]}</option>";
?>
</select>
<input type="text" name="dates_stx" value="<?php echo $dates_stx?>" id="dates_stx"  class="frm_input calendar-input" size="12" placeholder="시작일" />
~
<input type="text" name="datee_stx" value="<?php echo $datee_stx?>" id="datee_stx"  class="frm_input calendar-input" size="12" placeholder="종료일" />
수당구분</label>
<select name="pkind_stx" id="pkind_stx">
<option value=''>-수당구분-</option>
<?
foreach($g5['cn_pkind'] as $k=>$v) echo "<option value='{$k}' ".($pkind_stx==$k ? 'selected':'').">$v</option>";
?>
</select>
  <label for="coin_stx" class="sound_only">지불수단</label>
<select name="coin_stx" id="coin_stx">
<option value=''>-자산구분-</option>
<?
foreach($g5['cn_cointype']as $k=>$v) echo "<option value='{$k}' ".($coin_stx==$k ? 'selected':'').">$v</option>";
?>
</select>

<label for="sfl" class="sound_only">검색대상</label>
<select name="sfl" id="sfl">
<option value="a.mb_id"<?php echo get_selected($_GET['sfl'], "a.mb_id"); ?>>수급자아이디</option>
<option value="b.mb_name"<?php echo get_selected($_GET['sfl'], "b.mb_name"); ?>>수급자명</option>
<option value="a.smb_id"<?php echo get_selected($_GET['sfl'], "a.smb_id"); ?>>지급자아이디</option>
<option value="c.mb_name"<?php echo get_selected($_GET['sfl'], "c.mb_name"); ?>>지급자명</option>
<option value="a.subject"<?php echo get_selected($_GET['sfl'], "a.subject", true); ?>>비고</option>    
</select>
<input name="link_stx" type="text" class="frm_input" id="link_stx" placeholder="관련코드" value="<?php echo $link_stx ?>" size="10">
<label for="link_stx" class="sound_only">

검색어<strong class="sound_only"> 필수</strong></label>
<input type="text" name="stx" value="<?php echo $stx ?>" id="stx" class="frm_input">
<input type="submit" value="검색" class="btn_submit">

</form>


<div class="tbl_head01 tbl_wrap">
    <table>
    <caption><?php echo $g5['title']; ?> 목록</caption>
    <thead>
    <tr>
        <th rowspan="2" scope="col">번호</a></th>
        <th rowspan="2" scope="col"><?php echo subject_sort_link('a.pkind') ?>구분</a></th>
        <th colspan="2" scope="col"><?php echo subject_sort_link('a.mb_id') ?>소유자</a></th>
        <th rowspan="2" scope="col"><?php echo subject_sort_link('a.smb_id') ?>지급자</a>아이디</th>
        <th rowspan="2" scope="col">수량</th>
<th rowspan="2" scope="col">자산구분</th>
<th rowspan="2" scope="col">비고</th>
        <th rowspan="2" scope="col">지급일시</th>
      </tr>
    <tr>
      <th scope="col">아이디</th>
      <th scope="col">추천</th>
    </tr>
    </thead>
    <tbody>
    <?php
	$list_num = $total_count - ($page - 1) * $rows;
	
    for ($i=0; $row=sql_fetch_array($result); $i++) {

        $bg = 'bg'.($i%2);		

    ?>
	
    <tr class="<?php echo $bg; ?>">
        <td  class="td_num">
          <?=$list_num?>
        </td>
       <td ><?php echo $g5['cn_pkind'][$row['pkind']] ?></td>
       <td class='mb-info-open' data-id='<?php echo $row['mb_id'] ?>' ><?php echo $row['mb_id'] ?></td>
       <td ><?php echo $row['mb_recommend2'] ?></td>
       <td class='mb-info-open' data-id='<?php echo $row['smb_id'] ?>'><?php echo $row['smb_id'] ?></td>
    <td  class='text-right'><?php echo number_format2($row['amount'],2) ?></td>
<td class='text-center' ><?php echo $g5['cn_cointype'][$row['pt_coin']] ?></td>
<td class='text-center' ><?php echo $row['subject'] ?></td>       
       <td ><?php echo $row['wdate'] ?></td>
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




<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?'.$qstr.'&amp;page='); ?>

<script>

$(function(){

});
</script>