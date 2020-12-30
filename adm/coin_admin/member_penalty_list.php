<?php
$sub_menu = "700900";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');

//지사가 접근시
if($is_branch){
	$sql_jisa = " and mb_id in (select smb_id from $g5[cn_tree] where mb_id='$member[mb_id]' ) ";
	
	$sql_jisa = " and 
		(
		mb_recommend='{$member[mb_id]}' 		
		or mb_recommend in (select mb_id from {$g5[member_table]} where mb_recommend='{$member[mb_id]}' )
		or mb_recommend in (select mb_id from {$g5[member_table]} where mb_recommend in (select mb_id from {$g5[member_table]} where mb_recommend='{$member[mb_id]}' )  )		
		)
		";
		
}else $sql_jisa='';


$sql_common = " from {$g5['member_table']} ";

$sql_search = " where mb_trade_penalty > 0 $sql_jisa";



if ($stx) {
    $sql_search .= " and ( ";
    switch ($sfl) {
        case 'mb_point' :
            $sql_search .= " ({$sfl} >= '{$stx}') ";
            break;
        case 'mb_level' :
            $sql_search .= " ({$sfl} = '{$stx}') ";
            break;
        case 'mb_tel' :
        case 'mb_hp' :
            $sql_search .= " ({$sfl} like '%{$stx}') ";
            break;
        default :
            $sql_search .= " ({$sfl} like '{$stx}%') ";
            break;
    }
    $sql_search .= " ) ";
}

if ($_REQUEST['date_start_stx']) {	
    $sql_search .= " and mb_trade_penalty_date >= '".str_replace("-","",$date_start_stx)."'";	
	$qstr.="&date_start_stx=$date_start_stx";		
}
if ($_REQUEST['date_end_stx']) {
    $sql_search .= " and mb_trade_penalty_date <= '".str_replace("-","",$date_end_stx)."'";	
	$qstr.="&date_end_stx=$date_end_stx";
}

if($penalty_stx) {
    $sql_search .= " and mb_trade_penalty  >= '$penalty_stx' ";	
	$qstr.="&penalty_stx=$penalty_stx";
}

if (!$sst) {
    $sst = "mb_datetime";
    $sod = "desc";
}

$sql_order = " order by {$sst} {$sod} ";

$sql = " select count(*) as cnt {$sql_common} {$sql_search} {$sql_order} ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$sql = " select count(*) as cnt {$sql_common} where mb_trade_penalty ='1' $sql_jisa ";
$row = sql_fetch($sql,1);
$total_1 = $row['cnt'];
$sql = " select count(*) as cnt {$sql_common} where mb_trade_penalty ='2' $sql_jisa";
$row = sql_fetch($sql);
$total_2 = $row['cnt'];
$sql = " select count(*) as cnt {$sql_common} where mb_trade_penalty ='3' $sql_jisa";
$row = sql_fetch($sql);
$total_3 = $row['cnt'];
$sql = " select count(*) as cnt {$sql_common} where mb_trade_penalty >='4' $sql_jisa";
$row = sql_fetch($sql);
$total_4 = $row['cnt'];

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$listall = '<a href="'.$_SERVER['SCRIPT_NAME'].'" class="ov_listall">전체목록</a>';

$g5['title'] = '패널티관리';

include_once('../admin.head.php');
include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');

$sql = " select * {$sql_common} {$sql_search} {$sql_order} limit {$from_record}, {$rows} ";
$result = sql_query($sql,1);

$colspan = 9;
?>
<div class="local_ov01 local_ov">
    <?php echo $listall ?>
    <span class="btn_ov01"><span class="ov_txt">총검색수 </span><span class="ov_num"> <?php echo number_format($total_count) ?>명 </span></span>
	
    <a href="?panalty_stx=1" class="btn_ov01"> <span class="ov_txt">1회 </span><span class="ov_num"><?php echo number_format($total_1) ?>명</span></a>
	<a href="?panalty_stx=2" class="btn_ov01"> <span class="ov_txt">2회 </span><span class="ov_num"><?php echo number_format($total_2) ?>명</span></a>
	<a href="?panalty_stx=3" class="btn_ov01"> <span class="ov_txt">3회 </span><span class="ov_num"><?php echo number_format($total_3) ?>명</span></a>
	<a href="?panalty_stx=4ov" class="btn_ov01"> <span class="ov_txt">4회 이상 </span><span class="ov_num"><?php echo number_format($total_4) ?>명</span></a>
	
</div>

<form id="fsearch" name="fsearch" class="local_sch01 local_sch" method="get">


패널티일시:
<input type="text" name="date_start_stx" value="<?php echo $date_start_stx?>" id="date_start_stx"  class="frm_input calendar-input" size="12" placeholder="시작일" autocomplete='off'/>
~
<input type="text" name="date_end_stx" value="<?php echo $date_end_stx?>" id="date_end_stx"  class="frm_input calendar-input" size="12" placeholder="종료일" autocomplete='off' />
&nbsp;
<input name="penalty_stx" type="text" class="frm_input" id="penalty_stx" value="<?php echo $penalty_stx ?>" size="5">
회 이상&nbsp;
<select name="sfl" id="sfl">
    
<option value="mb_id"<?php echo get_selected($_GET['sfl'], "mb_id"); ?>>아이디</option>
    <option value="mb_tel"<?php echo get_selected($_GET['sfl'], "mb_tel"); ?>>전화번호</option>
    <option value="mb_hp"<?php echo get_selected($_GET['sfl'], "mb_hp"); ?>>휴대폰번호</option>
	<option value="mb_recommend"<?php echo get_selected($_GET['sfl'], "mb_recommend"); ?>>지사/지점 코드 </option>	    
</select>
<label for="penalty_stx" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
<input type="text" name="stx" value="<?php echo $stx ?>" id="stx" class="frm_input">
<input type="submit" class="btn_submit" value="검색">

</form>


<form name="fmemberlist" id="fmemberlist" action="./member_penalty_list_update.php" onsubmit="return fmemberlist_submit(this);" method="post">
<input type="hidden" name="sst" value="<?php echo $sst ?>">
<input type="hidden" name="sod" value="<?php echo $sod ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
<input type="hidden" name="stx" value="<?php echo $stx ?>">
<input type="hidden" name="page" value="<?php echo $page ?>">
<input type="hidden" name="date_start_stx" value="<?php echo $date_start_stx ?>">
<input type="hidden" name="date_end_stx" value="<?php echo $date_end_stx ?>">
<input type="hidden" name="penalty_stx" value="<?php echo $penalty_stx ?>">

<input type="hidden" name="token" value="">

<div class="tbl_head01 tbl_wrap">

    <table class='w-auto'>
    <caption><?php echo $g5['title']; ?> 목록</caption>
    <thead>
    <tr>
        <th scope="col" id="mb_list_chk" >
            <label for="chkall" class="sound_only">회원 전체</label>
            <input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all(this.form)">
        </th>
<th id="mb_list_id" scope="col">No</a></th>
<th id="mb_list_id" scope="col"><?php echo subject_sort_link('mb_id') ?>아이디</a></th>
        <th scope="col" ><?php echo subject_sort_link('mb_hp') ?>연락처</a></th>
<th scope="col" >보유
<?=$g5[cn_item_name]?></th>
<th id="mb_list_lastcall" scope="col"><?php echo subject_sort_link('mb_today_login') ?>최종접속</a></th>
<th scope="col" ><?php echo subject_sort_link('mb_datetime', '', 'desc') ?>가입일</th>
       
        <th scope="col" id="mb_list_cert"><?php echo subject_sort_link('mb_trade_penalty') ?>패널티횟수</a></th>
        
        <th scope="col" id="mb_list_cert"><?php echo subject_sort_link('mb_trade_penalty_date') ?>최근패널티 일시</th>
    </tr>
    </thead>
    <tbody>
    <?php
	
	$list_num = $total_count - ($page - 1) * $rows;
    for ($i=0; $row=sql_fetch_array($result); $i++) {
            
       
        $bg = 'bg'.($i%2);

		//스톤 물량
		$temp=sql_fetch("select count(*) cnt  from  {$g5['cn_item_cart']} where mb_id='{$row[mb_id]}' and is_soled!='1' ",1);
    ?>

    <tr class="<?php echo $bg; ?>">
      <td headers="mb_list_chk" class="td_chk">
        <input type="hidden" name="mb_id[<?php echo $i ?>]" value="<?php echo $row['mb_id'] ?>" id="mb_id_<?php echo $i ?>">
        <label for="chk_<?php echo $i; ?>" class="sound_only"><?php echo get_text($row['mb_name']); ?> <?php echo get_text($row['mb_nick']); ?>님</label>
        <input type="checkbox" name="chk[]" value="<?php echo $i ?>" id="chk_<?php echo $i ?>">
        </td>
<td class="td_num">
<?=$list_num?>
</td>
<td class='mb-info-open' data-id='<?php echo $row['mb_id'] ?>' ><?php echo $row[mb_id] ?>
</td>
      <td  ><?= $s_mod_href ?><?=$row['mb_hp']?></a></td>
<td class="td_datetime"><?php echo $temp[cnt]; ?></td>
<td class="td_date"><?php echo substr($row['mb_today_login'],2,8); ?></td>
<td class="td_date"><?php echo substr($row['mb_datetime'],2,8); ?></td>
      
<td class='td_date'>
<input name="mb_trade_penalty[<?=$i?>]" type="text" class="frm_input text-center" id="mb_trade_penalty[<?=$i?>]" value="<?php echo $row[mb_trade_penalty] ?>" size="5">
</td>
    <td class='td_datetime2'>
<input name="mb_trade_penalty_date[<?=$i?>]" type="text" class="frm_input text-center" id="mb_trade_penalty_date[<?=$i?>]" value="<?php echo $row[mb_trade_penalty_date] ?>" size="25">
</td>
    </tr>
    <?php
	$list_num--;
    }
    if ($i == 0)
        echo "<tr><td colspan=\"".$colspan."\" class=\"empty_table\">자료가 없습니다.</td></tr>";
    ?>
    </tbody>
    </table>
    
</div>

<div class="btn_fixed_top">
<?
if(!auth_check($auth[$sub_menu], 'w',1)){?>
    <input type="submit" name="act_button" value="선택수정" onclick="document.pressed=this.value" class="btn btn_02">
<? }?>
<?
if(!auth_check($auth[$sub_menu], 'd',1)){?>
    <input type="submit" name="act_button" value="패널티초기화" onclick="document.pressed=this.value" class="btn btn_01">
<? }?>	
</div>


</form>
<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, '?'.$qstr.'&amp;page='); ?>

<script>
function fmemberlist_submit(f)
{
    if (!is_checked("chk[]")) {
        alert(document.pressed+" 하실 항목을 하나 이상 선택하세요.");
        return false;
    }

    if(document.pressed == "선택수정") {
        if(!confirm("선택한 회원의 패널티 내역을 수정하시겠습니까?")) {
            return false;
        }
    }
		
	if(document.pressed == "패널티초기화") {
        if(!confirm("선택한 회원의 패널티 내역을 초기화 하시겠습니까?")) {
            return false;
        }
    }
    return true;
}
</script>

<?php
include_once ('../admin.tail.php');
?>
