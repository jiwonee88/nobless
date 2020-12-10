<?php
$sub_menu = "800400";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');

$sql_common = " from {$g5['cn_pt_table']} a left outer join  {$g5['member_table']} as b on(a.mb_id=b.mb_id)  left outer join  {$g5['member_table']} as c on(a.smb_id=c.mb_id) ";
$sql_search = " where 1=1 ";


if ($stx) {
	if($sfl=='a.mb_id' || $sfl=='a.smb_id') $sql_search .= "and ($sfl = '$stx') ";
    else $sql_search .= "and ($sfl like '%$stx%') ";
}
if ($mb_stx) {
    $sql_search .= " and a.mb_id='$mb_stx' ";	
	$qstr.="&mb_stx=$mb_stx";
}

if (!$sst) {
    $sst  = "a.pt_no ";
    $sod = "desc";
}

$sql_order = " order by $sst $sod ";

$sql = " select count(a.pt_no) as cnt {$sql_common} {$sql_search}";
$row = sql_fetch($sql,1);
$total_count = $row['cnt'];

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = " select a.*,b.mb_name,b.mb_email,b.mb_grade,b.mb_recommend 
,c.mb_name cmb_name, c.mb_grade cmb_grade,c.mb_recommend cmb_recommend {$sql_common} {$sql_search} {$sql_order} limit {$from_record}, {$rows} ";
$result = sql_query($sql);

$listall = '<a href="'.$_SERVER['SCRIPT_NAME'].'" class="ov_listall">전체목록</a>';

$g5['title'] = '수당이전';

include_once(G5_ADMIN_PATH.'/admin.head.php');

$colspan = 14;

?>

<div class="local_ov01 local_ov">
    <?php echo $listall ?>
    <span class="btn_ov01"><span class="ov_txt">등록된 수</span><span class="ov_num"> <?php echo number_format($total_count) ?>개</span></span>
</div>

<form name="fsearch" id="fsearch" class="local_sch01 local_sch" method="get">
  <label for="sfl" class="sound_only">검색대상</label>
<select name="sfl" id="sfl">
<option value="a.mb_id"<?php echo get_selected($_GET['sfl'], "a.mb_id"); ?>>수급자아이디</option>
<option value="b.mb_name"<?php echo get_selected($_GET['sfl'], "b.mb_name"); ?>>수급자명</option>
<option value="a.smb_id"<?php echo get_selected($_GET['sfl'], "a.mb_id"); ?>>지급자아이디</option>
<option value="c.mb_name"<?php echo get_selected($_GET['sfl'], "c.mb_name"); ?>>지급자명</option>
<option value="a.subject"<?php echo get_selected($_GET['sfl'], "a.subject"); ?>>설명</option>
<option value="a.comment"<?php echo get_selected($_GET['sfl'], "a.comment", true); ?>>비고</option>    
</select>

<label for="stx" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
<input type="text" name="stx" value="<?php echo $stx ?>" id="stx" class="frm_input">
<input type="submit" value="검색" class="btn_submit">

</form>



<form name="fboardlist" id="fboardlist" action="./point_list_update.php" onsubmit="return fboardlist_submit(this);" method="post">
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
        <th scope="col">
            <label for="chkall" class="sound_only"> 전체</label>
            <input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all(this.form)">
        </th>
        <th scope="col">번호</a></th>
        <th scope="col"><?php echo subject_sort_link('a.kind') ?>구분</th>
        <th scope="col"><?php echo subject_sort_link('a.mb_id') ?>수급자</th>
        <th scope="col"><?php echo subject_sort_link('b.mb_grade') ?>수급자등급</a></th>
        <th scope="col"><?php echo subject_sort_link('a.smb_id') ?>지급자</th>
        <th scope="col"><?php echo subject_sort_link('b.mb_grade') ?>지급자등급</a></th>
        <th scope="col">지급액</th>
        <th scope="col">설명</th>
        <th scope="col">이전일자</th>
        <th scope="col">관리</th>
    </tr>
    </thead>
    <tbody>
    <?php
	$list_num = $total_count - ($page - 1) * $rows;
	
    for ($i=0; $row=sql_fetch_array($result); $i++) {
        $one_update = '<a href="./point_form.php?w=u&amp;pt_no='.$row['pt_no'].'&amp;'.$qstr.'" class="btn btn_03">수정</a>';

        $bg = 'bg'.($i%2);
		
		//추천된 DB 수량
		//$app=sql_fetch("select count(*)  cnt,sum(if(app_stats='3',1,0)) as cnt1 from {$g5['participationapp_table']} where app_rt='{$row['pt_no']}'",1);
    ?>
	
    <tr class="<?php echo $bg; ?>">
        <td class="td_chk">
            <label for="chk_<?php echo $i; ?>" class="sound_only"><?php echo get_text($row['rt_subject']) ?></label>
            <input type="checkbox" name="chk[]" value="<?php echo $i ?>" id="chk_<?php echo $i ?>">
            <input type="hidden" name="pt_no[<?php echo $i ?>]" value="<?php echo $row['pt_no'] ?>">
        </td>
       <td  class="td_num">
       <?=$list_num?>
        </td>
       <td class="td_num_c3"><?php echo $g5['pointtrans_kind'][$row['pt_kind']] ?></td>
       <td class="td_left"><?php echo $row['mb_name'] ?> [<?php echo $row['mb_id'] ?>]</td>
       <td ><?php echo $g5['member_grade'][$row['mb_grade']] ?></td>
       <td ><?php echo $row['cmb_name'] ?> [<?php echo $row['smb_id'] ?>]</td>
       <td ><?php echo $g5['member_grade'][$row['cmb_grade']] ?></td>
       <td class="text-right"><?php echo number_format($row['amount']) ?></td>
       <td ><?php echo $row['subject'] ?></td>
       <td class="td_num_c3">
            <label for="desposit_date<?php echo $i; ?>" class="sound_only">이전일자</label>
            <input type="text" name="deposit_date[<?php echo $i ?>]" value="<?php echo substr($row['deposit_date'],0,10) ?>" id="deposit_date<?php echo $i; ?>" class="tbl_input calendar-input" size="10">
        </td>
        <td class="td_mng td_mng_m">
            <?php echo $one_update ?>
            <?php echo $one_copy ?>
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
    <input type="submit" name="act_button" value="선택삭제" onclick="document.pressed=this.value" class="btn_02 btn">
    <a href="./point_form.php" id="rt_add" class="btn_01 btn">내역추가</a>
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

    return true;
}

$(function(){
    $(".point_copy").click(function(){
        window.open(this.href, "win_point_copy", "left=100,top=100,width=550,height=450");
        return false;
    });
});
</script>

<?php
include_once(G5_ADMIN_PATH.'/admin.tail.php');

?>
