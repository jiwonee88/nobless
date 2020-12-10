<?php
$sub_menu = "200400";

include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');

$sql_common = " from {$g5['cn_sub_account']} as a 
left outer join {$g5['member_table']} as b on(a.mb_id=b.mb_id)
left outer join  (select mb_id,count(*) ct_cnt,sum(ct_buy_price) ct_buy_price  from {$g5['cn_item_cart']} where  is_soled != '1' group by mb_id)  as c on(c.mb_id=b.mb_id) 
left outer join  (select mb_id,count(*) tr_cnt,sum(tr_price_org) tr_price_org  from {$g5['cn_item_trade']} where  tr_stats in ('1','2')  group by mb_id)  as t on(t.mb_id=b.mb_id) 	
		";

$sql_search = " where (1) ";


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
//생성일
if ($_REQUEST['date_start_stx']) {	
    $sql_search .= " and ac_wdate >= '".str_replace("-","",$date_start_stx)."'";	
	$qstr.="&date_start_stx=$date_start_stx";		
}
if ($_REQUEST['date_end_stx']) {
    $sql_search .= " and ac_wdate <= '".str_replace("-","",$date_end_stx)."'";	
	$qstr.="&date_end_stx=$date_end_stx";
}

if($sst=='item_cnt'){
	$sql_order=" order by (select count(*) cnt  from  {$g5['cn_item_cart']} where smb_id=a.ac_id and is_soled!='1' ) $sod";
}else{
	if (!$sst) {
		$sst = "a.ac_no";
		$sod = "desc";
	}	
	$sql_order = " order by {$sst} {$sod} ";

}


$sql = " select count(a.ac_no) as cnt {$sql_common} {$sql_search} {$sql_order} ";
$row = sql_fetch($sql,1);
$total_count = $row['cnt'];

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함


$listall = '<a href="'.$_SERVER['SCRIPT_NAME'].'" class="ov_listall">전체목록</a>';

$g5['title'] = '계정관리';

include_once('./admin.head.php');
include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');

$sql = " select * {$sql_common} {$sql_search} {$sql_order} limit {$from_record}, {$rows} ";
$result = sql_query($sql);

$colspan = 14 + sizeof($g5['cn_item'])+sizeof($g5['cn_cointype']);
?>
<div class="local_ov01 local_ov">
    <?php echo $listall ?>
    <span class="btn_ov01"><span class="ov_txt">총계정수 </span><span class="ov_num"> <?php echo number_format($total_count) ?>명 </span></span>
</div>

<form id="fsearch" name="fsearch" class="local_sch01 local_sch" method="get">

생성일
<input type="text" name="date_start_stx" value="<?php echo $date_start_stx?>" id="date_start_stx"  class="frm_input calendar-input" size="12" placeholder="시작일" />
~
<input type="text" name="date_end_stx" value="<?php echo $date_end_stx?>" id="date_end_stx"  class="frm_input calendar-input" size="12" placeholder="종료일" />
<span class="nowrap">

<select name="sfl" id="sfl">
    
    <option value="a.mb_id" <?php echo get_selected($_GET['sfl'], "a.mb_id"); ?>>아이디</option>
	<option value="a.ac_id" <?php echo get_selected($_GET['sfl'], "a.ac_id"); ?>>서브아이디</option>
    <option value="b.mb_tel" <?php echo get_selected($_GET['sfl'], "b.mb_tel"); ?>>전화번호</option>
    <option value="b.mb_hp"<?php echo get_selected($_GET['sfl'], "b.mb_hp"); ?>>휴대폰번호</option>
	<option value="b.mb_recommend"<?php echo get_selected($_GET['sfl'], "b.mb_recommend"); ?>>추천인 Rerferral 코드 </option>
    <option value="b.mb_datetime"<?php echo get_selected($_GET['sfl'], "b.mb_datetime"); ?>>가입일시</option>
</select>

<label for="stx" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
<input type="text" name="stx" value="<?php echo $stx ?>" id="stx" class="frm_input">
<input type="submit" class="btn_submit" value="검색">
</span>
</form>

<form name="fmemberlist" id="fmemberlist" action="./member_avatar_list_update.php" onsubmit="return fmemberlist_submit(this);" method="post">
<input type="hidden" name="sst" value="<?php echo $sst ?>">
<input type="hidden" name="sod" value="<?php echo $sod ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
<input type="hidden" name="stx" value="<?php echo $stx ?>">
<input type="hidden" name="page" value="<?php echo $page ?>">
<input type="hidden" name="date_start_stx" value="<?php echo $date_start_stx ?>">
<input type="hidden" name="date_end_stx" value="<?php echo $date_end_stx ?>">

<input type="hidden" name="token" value="">

<div class="tbl_head01 tbl_wrap">
<?=help('서브계정 삭제시 현재 소유한 포인트,아이템,진행중인 거래는 모계정으로 이전됩니다. 내역정보는 이전되지 않습니다');?>

	<table>
    <caption><?php echo $g5['title']; ?> 목록</caption>
    <thead>
    <tr>
        <th scope="col" id="mb_list_chk" >
            <label for="chkall" class="sound_only">회원 전체</label>
            <input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all(this.form)">
        </th>
<th id="mb_list_id" scope="col"><?php echo subject_sort_link('ac_id') ?>서브아이디</a></th>
		<th id="mb_list_id" scope="col"><?php echo subject_sort_link('a.mb_id') ?>아이디</a></th>
<th scope="col" id="mb_list_cert">설정금액</th>
<th scope="col" id="mb_list_cert">보유금액</th>
<th scope="col" id="mb_list_cert">거래(구매중)</th>
<th scope="col" id="mb_list_cert">가용금액</th>
<th scope="col" id="mb_list_cert">연락처</th>
        <th scope="col" ><?php echo subject_sort_link('mb_email') ?>활성화</a></th>
        
    <?
		//계정별
		foreach($g5['cn_item'] as $k=> $v){ ?>
        <th  scope="col"><?php echo subject_sort_link('ac_auto_'.$k) ?><?=$v[name_kr]?> auto</th>
        <? }?>
		
        <?
		//계정별
		foreach($g5['cn_cointype'] as $k=> $v){ ?>
        <th id="mb_list_id" scope="col"><?php echo subject_sort_link('ac_point_'.$k) ?>총<?=$v?>수량</th>
        <? }?>
		<th scope="col" ><?php echo subject_sort_link('item_cnt', '', 'desc') ?>보유<?=$g5[cn_item_name]?></th>
<th scope="col" ><?php echo subject_sort_link('b.mb_trade_paytype') ?>결제</th>
<th scope="col" ><?php echo subject_sort_link('a.ac_mc_priority') ?>우선매칭</th>
<th scope="col" ><?php echo subject_sort_link('ac_mc_except') ?>매칭제외</th>

        <th scope="col" ><?php echo subject_sort_link('ac_wdate', '', 'desc') ?>생성일</th>
    </tr>
    </thead>
    <tbody>
    <?php
    for ($i=0; $row=sql_fetch_array($result); $i++) {
           
        $bg = 'bg'.($i%2);
		
		//스톤 물량
		$temp=sql_fetch("select count(*) cnt  from  {$g5['cn_item_cart']} where smb_id='{$row[ac_id]}' and is_soled!='1' ",1);
    ?>

    <tr class="<?php echo $bg; ?>">
      <td headers="mb_list_chk" class="td_chk">
        <input type="hidden" name="ac_id[<?php echo $i ?>]" value="<?php echo $row['ac_id'] ?>" id="ac_id_<?php echo $i ?>">
        <label for="chk_<?php echo $i; ?>" class="sound_only"><?php echo get_text($row['mb_name']); ?> <?php echo get_text($row['mb_nick']); ?>님</label>
        <input type="checkbox" name="chk[]" value="<?php echo $i ?>" id="chk_<?php echo $i ?>">
        </td>
<td  ><?php echo $row[ac_id] ?></td>
	
	<td  >
    <?php echo $row[mb_id] ?></td>
<td><?=number_format2($row['mb_trade_amtlmt'])?></td>
<td><?=number_format2($row['ct_buy_price'])?></td>
<td><?=number_format2($row['mtr_price_org'])?></td>
<td><?=number_format2($row['mb_trade_amtlmt']-$row['ct_buy_price']-$row['mtr_price_org'])?></td>
<td><?=$row['mb_hp']?></td>
		
      <td class='td_center'>
<input type="checkbox" name="ac_active[<?=$i?>]" value="1" id="chk_<?php echo $i ?>" <?=$row['ac_active']?'checked':''?>>
</td>
      
    <?
    //오토매칭
    foreach($g5['cn_item'] as $k=> $v){ ?>
    <td  >
	<input type="checkbox" name="ac_auto_<?=$k?>[<?=$i?>]" value="1" <?=$row['ac_auto_'.$k]?'checked':''?> >
	</td>
     <? }?> 
	 
	 <?
    //계정별
    foreach($g5['cn_cointype'] as $k=> $v){ ?>     
	<td  ><?php echo number_format2($row['ac_point_'.$k],8)?></td>

     <? }?> 
	 <td class="td_datetime"><?php echo $temp[cnt]; ?></td>
<td class="td_date"><?=$row[mb_trade_paytype]?></td>
<td class="td_date"><input type="checkbox" name="ac_mc_priority[<?=$i?>]" value="1" <?=$row[ac_mc_priority]=='1'?'checked':''?>  ></td>
<td class="td_date"><input type="checkbox" name="ac_mc_except[<?=$i?>]" value="1" <?=$row[ac_mc_except]==1?'checked':''?>  ></td>
<td class="td_datetime"><?php echo $row['ac_wdate']; ?></td>
    </tr>
    <?php
    }
    if ($i == 0)
        echo "<tr><td colspan=\"".$colspan."\" class=\"empty_table\">자료가 없습니다.</td></tr>";
    ?>
    </tbody>
    </table>
    
</div>

<div class="btn_fixed_top">
    <input type="submit" name="act_button" value="선택수정" onclick="document.pressed=this.value" class="btn btn_02">
    <input type="submit" name="act_button" value="선택삭제" onclick="document.pressed=this.value" class="btn btn_01">


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

    if(document.pressed == "선택삭제") {
        if(!confirm("선택한 자료를 정말 삭제하시겠습니까?")) {
            return false;
        }
    }
		
    return true;
}
</script>

<?php
include_once ('./admin.tail.php');
?>
