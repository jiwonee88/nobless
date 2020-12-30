<?php
$sub_menu = "500200";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');

$sql_common = " from {$g5['cn_token_table']} a left outer join  {$g5['member_table']} as b on(a.mb_id=b.mb_id) ";
$sql_search = " where 1=1 ";


if ($stx) {
  $sql_search .= "and ($sfl like '%$stx%') ";
}
if ($coin_stx) {
    $sql_search .= " and a.token_name='$coin_stx' ";	
	$qstr.="&coin_stx=$coin_stx";
}
if ($mb_stx) {
    $sql_search .= " and a.mb_id='$mb_stx' ";	
	$qstr.="&mb_stx=$mb_stx";
}

if ($iss_stx=='y' ) {
    $sql_search .= " and a.mb_id!='' ";	
	$qstr.="&iss_stx=$iss_stx";
}
if ($iss_stx=='n' ) {
    $sql_search .= " and a.mb_id='' ";	
	$qstr.="&iss_stx=$iss_stx";
}

if (!$sst) {
    $sst  = "a.token_no ";
    $sod = "desc";
}

$sql_order = " order by $sst $sod ";

$sql = " select count(a.token_no) as cnt {$sql_common} {$sql_search}";
$row = sql_fetch($sql,1);
$total_count = $row['cnt'];

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = " select * {$sql_common} {$sql_search} {$sql_order} limit {$from_record}, {$rows} ";
$result = sql_query($sql);

$listall = '<a href="'.$_SERVER['SCRIPT_NAME'].'" class="ov_listall">전체목록</a>';

$g5['title'] = '입금전용주소 목록';

include_once(G5_ADMIN_PATH.'/admin.head.php');

$colspan = 14;

?>

<div class="local_ov01 local_ov">
    <?php echo $listall ?>
    <span class="btn_ov01"><span class="ov_txt">등록된 수</span><span class="ov_num"> <?php echo number_format($total_count) ?>개</span></span>
</div>

<form name="fsearch" id="fsearch" class="local_sch01 local_sch" method="get">

                  
                
                
    <select name='coin_stx' class="form-control input-sm  w-auto  mb-1" id="coin_stx" >
      <?
foreach($g5['cn_coin_in'] as $k){?>
      <option value='<?=$k?>' <?=$coin_stx==$k?'selected':''?> >
        <?=$g5['cn_cointype'][$k]?>
      </option>
      <? }?>
    </select>
<select name="iss_stx" id="iss_stx">
<option value="">-발급여부-</option>
<option value="y" <?php echo get_selected($_GET['iss_stx'], "y"); ?> >발급완료</option>
<option value="n" <?php echo get_selected($_GET['iss_stx'], "n"); ?>>미발급</option>
</select>
<select name="sfl" id="sfl">
<option value="a.token_addr"<?php echo get_selected($_GET['sfl'], "a.token_addr"); ?>>토큰주소</option>
<option value="b.mb_name"<?php echo get_selected($_GET['sfl'], "b.mb_name"); ?>>발급자명</option>
<option value="b.mb_id"<?php echo get_selected($_GET['sfl'], "b.mb_id"); ?>>발급자아이디</option>
</select>

<label for="stx" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
<input type="text" name="stx" value="<?php echo $stx ?>" id="stx" class="frm_input">
<input type="submit" value="검색" class="btn_submit">

</form>



<form name="fboardlist" id="fboardlist" action="./token_addr_list_update.php" onsubmit="return fboardlist_submit(this);" method="post">
<input type="hidden" name="sst" value="<?php echo $sst ?>">
<input type="hidden" name="sod" value="<?php echo $sod ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
<input type="hidden" name="stx" value="<?php echo $stx ?>">
<input type="hidden" name="page" value="<?php echo $page ?>">
<input type="hidden" name="mb_stx" value="<?php echo $mb_stx ?>">
<input type="hidden" name="coin_stx" value="<?php echo $coin_stx ?>">
<input type="hidden" name="iss_stx" value="<?php echo $iss_stx ?>">

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
        <th scope="col">구분</th>
        <th scope="col">토큰주소</th>
        <th scope="col">발급회원</th>
        <th scope="col">발급일</th>
        </tr>
    </thead>
    <tbody>
    <?php
	$list_num = $total_count - ($page - 1) * $rows;
	
    for ($i=0; $row=sql_fetch_array($result); $i++) {
        $one_update = '<a href="./point_form.php?w=u&amp;token_no='.$row['token_no'].'&amp;'.$qstr.'" class="btn btn_03">수정</a>';

        $bg = 'bg'.($i%2);
    ?>
	
    <tr class="<?php echo $bg; ?>">
        <td class="td_chk">
            <input type="checkbox" name="chk[]" value="<?php echo $i ?>" id="chk_<?php echo $i ?>">
            <input type="hidden" name="token_no[<?php echo $i ?>]" value="<?php echo $row['token_no'] ?>">
        </td>
       <td  class="td_num">
         <?=$list_num?>
       </td>
       <td ><?php echo $g5['cn_cointype'][$row['token_name']] ?></td>
       <td>
            <input type="text" name="token_addr[<?=$i?>]" value="<?php echo $row['token_addr'] ?>" id="token_addr<?=$i?>" class="tbl_input" size="80">
        </td>
       <td ><?php echo $row['mb_name'] ?><?php echo $row['mb_id']?"[".$row['mb_id']."]":"-" ?></td>
       <td ><?php echo $row['mb_id']?$row['token_wdate']:"-" ?></td>
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
    <a href="./token_addr_form.php" id="rt_add" class="btn_01 btn">추가</a>
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
        if(confirm("선택한 자료를 정말 삭제하시겠습니까?")) {
            return true;
        }else return false;
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
