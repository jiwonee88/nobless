<?php
$sub_menu = "700550";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');


$sql_common = " from {$g5['cn_sub_account']} as a 
left outer join  {$g5['member_table']} as b on(a.mb_id=b.mb_id)  
left outer join  (select mb_id,count(*) ct_cnt,sum(ct_buy_price) ct_buy_price  from {$g5['cn_item_cart']} where is_soled!='1' group by mb_id)  as c on(c.mb_id=b.mb_id) 
left outer join  (select mb_id,count(*) ct_cnt,sum(tr_price_org) tr_price_org  from {$g5['cn_item_trade']} where  tr_stats in ('1','2')  group by mb_id)  as t on(t.mb_id=b.mb_id) 
";

$sql_search = " where (1) and a.ac_active ='1' 
and ( b.mb_trade_amtlmt - if(c.ct_buy_price,c.ct_buy_price,0) - if(t.tr_price_org,t.tr_price_org,0)  > 0 )";


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

if ($mb9_stx != '')
    $sql_search .= " and mb_9 = '{$mb9_stx}' ";

if ($sst=='enable_amt1') {
	$sql_order = " order by  (b.mb_trade_amtlmt - if(c.ct_buy_price,c.ct_buy_price,0)) {$sod} ";
	
}else if ($sst=='enable_amt2') {
	$sql_order = " order by  (b.mb_trade_amtlmt - if(c.ct_buy_price,c.ct_buy_price,0) - if(c.tr_price_org,c.tr_price_org,0)) {$sod} ";
	
}else{

	if (!$sst) {
		$sst = "ac_no";
		$sod = "desc";
		
	}
	 $sql_order = " order by $sst {$sod} ";
}


$sql = " select count(*) as cnt {$sql_common} {$sql_search} {$sql_order} ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

// 탈퇴회원수
$sql = " select count(*) as cnt {$sql_common} {$sql_search} and mb_leave_date <> '' {$sql_order} ";
$row = sql_fetch($sql);
$leave_count = $row['cnt'];


// 차단회원수
$sql = " select count(*) as cnt {$sql_common} {$sql_search} and mb_intercept_date <> '' {$sql_order} ";
$row = sql_fetch($sql);
$intercept_count = $row['cnt'];

$listall = '<a href="'.$_SERVER['SCRIPT_NAME'].'" class="ov_listall">전체목록</a>';

$g5['title'] = $g5[cn_item_name].'-구매대기';


include_once('../admin.head.php');
include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');

$sql = " select *
{$sql_common} {$sql_search} {$sql_order} limit {$from_record}, {$rows} ";
$result = sql_query($sql,1);

$colspan = 16;
?>
<div class="local_ov01 local_ov">
    <?php echo $listall ?>
    <span class="btn_ov01"><span class="ov_txt">검색계정수 </span><span class="ov_num"> <?php echo number_format($total_count) ?>명 </span></span>
    <a href="?sst=mb_intercept_date&amp;sod=desc&amp;sfl=<?php echo $sfl ?>&amp;stx=<?php echo $stx ?>" class="btn_ov01"> <span class="ov_txt">차단 </span><span class="ov_num"><?php echo number_format($intercept_count) ?>명</span></a>
    <a href="?sst=mb_leave_date&amp;sod=desc&amp;sfl=<?php echo $sfl ?>&amp;stx=<?php echo $stx ?>" class="btn_ov01"> <span class="ov_txt">탈퇴  </span><span class="ov_num"><?php echo number_format($leave_count) ?>명</span></a>
</div>

<form id="fsearch" name="fsearch" class="local_sch01 local_sch" method="get">
<input type='hidden' name='w' value='u'>
<span class="nowrap">

<select name="sfl" id="sfl">
    
    <option value="a.mb_id"<?php echo get_selected($_GET['sfl'], "a.mb_id"); ?>>아이디</option>
    <option value="mb_hp"<?php echo get_selected($_GET['sfl'], "mb_hp"); ?>>휴대폰번호</option>
	<option value="mb_recommend"<?php echo get_selected($_GET['sfl'], "mb_recommend"); ?>>추천인 Rerferral 코드 </option>
	<option value="mb_name"<?php echo get_selected($_GET['sfl'], "mb_name"); ?>>이름</option>
    <option value="mb_datetime"<?php echo get_selected($_GET['sfl'], "mb_datetime"); ?>>가입일시</option>
</select>
<label for="stx" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
<input type="text" name="stx" value="<?php echo $stx ?>" id="stx" class="frm_input">
<input type="submit" class="btn_submit" value="검색">
</span>
</form>


<form name="fmemberlist" id="fmemberlist" action="./item_buyer_list.update..php" onsubmit="return fmemberlist_submit(this);" method="post">
<input type="hidden" name="sst" value="<?php echo $sst ?>">
<input type="hidden" name="sod" value="<?php echo $sod ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
<input type="hidden" name="stx" value="<?php echo $stx ?>">
<input type="hidden" name="page" value="<?php echo $page ?>">
<input type="hidden" name="return_page" value="<?php echo $return_page ?>">
<input type="hidden" name="date_start_stx" value="<?php echo $date_start_stx ?>">
<input type="hidden" name="date_end_stx" value="<?php echo $date_end_stx ?>">
<input type="hidden" name="mb9_stx" value="<?php echo $mb9_stx ?>">

<input type="hidden" name="token" value="">

<div class="tbl_head01 tbl_wrap">


	<table>
    <caption><?php echo $g5['title']; ?> 목록</caption>
    <thead>
    <tr>
        <th rowspan="2" id="mb_list_chk" scope="col" >
            <label for="priority" class="sound_only">회원 전체</label>
            <input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all(this.form)">
        </th>
<th rowspan="2" scope="col" >No</th>
		<th rowspan="2" id="mb_list_id" scope="col"><?php echo subject_sort_link('a.mb_id') ?>아이디</a></th>
    <th colspan="4" id="mb_list_cert" scope="col">회원별</th>
<th rowspan="2" id="mb_list_id" scope="col"><?php echo subject_sort_link('ac_proint_'.$g5[cn_fee_coin]) ?><?=$g5[cn_cointype][$g5[cn_fee_coin]]?></th>
        <th rowspan="2" id="mb_list_id" scope="col"><?php echo subject_sort_link('ac_active') ?>활성화</th>
       
        <th id="mb_list_id" scope="col" colspan="<?=sizeof($g5['cn_item'])?>">오토매칭</th>
       
        <th rowspan="2" id="mb_list_cert" scope="col">결제</th>
        <th rowspan="2" id="mb_list_lastcall" scope="col"><?php echo subject_sort_link('a.ac_mc_priority') ?>우선매칭</a></th>
        <th rowspan="2" scope="col" ><?php echo subject_sort_link('ac_mc_except') ?>매칭제외</th>
    </tr>
<tr>
<th id="mb_list_cert" scope="col"><?php echo subject_sort_link('mb_trade_amtlmt') ?>설정금액</th>
<th id="mb_list_id" scope="col"><?php echo subject_sort_link('ct_buy_price') ?>보유금액</th>
<th id="mb_list_id" scope="col"><?php echo subject_sort_link('tr_price_org') ?>구매중</th>
<!--th id="mb_list_id" scope="col"><?php echo subject_sort_link('enable_amt1') ?>가용금액</th-->
<!--th id="mb_list_id" scope="col"><?php echo subject_sort_link('enable_amt2') ?>매칭가용</th-->
<th id="mb_list_id" scope="col"><?php echo subject_sort_link('ct_cnt') ?>보유수량</th>
<?
		//상품별 오토매칭
		foreach($g5['cn_item'] as $k=> $v){ ?>
<th id="mb_list_id3" scope="col"><?php echo subject_sort_link('ac_auto_'.$k) ?><?=$v['name_kr']?></th>
<? }?>
</tr>
    </thead>
    <tbody>
    <?php
	$list_num = $total_count - ($page - 1) * $rows;
    for ($i=0; $row=sql_fetch_array($result); $i++) {
 
        if ($row2['cnt'])
            $group = '<a href="./boardgroupmember_form.php?mb_id='.$row['mb_id'].'">'.$row2['cnt'].'</a>';

        if ($is_admin == 'group') {
            $s_mod = '';
        } else {
            $s_mod_href = '<a href="./member_form.php?'.$qstr.'&amp;w=u&amp;mb_id='.$row['mb_id'].'" >';
			$s_mod = '<a href="./member_form.php?'.$qstr.'&amp;w=u&amp;mb_id='.$row['mb_id'].'" class="btn btn_03">수정</a>';
        }

        $leave_date = $row['mb_leave_date'] ? $row['mb_leave_date'] : date('Ymd', G5_SERVER_TIME);
        $intercept_date = $row['mb_intercept_date'] ? $row['mb_intercept_date'] : date('Ymd', G5_SERVER_TIME);

      	//보유금액
		$stone_amt=($row[ct_buy_price]?$row[ct_buy_price]:0) ;
			
		//가용금액
		//$enable_amt1=$row['mb_trade_amtlmt']-($row[ct_buy_price]?$row[ct_buy_price]:0);
		
		//가용금액2
		//$enable_amt2=$row['mb_trade_amtlmt']- ($row[ct_buy_price]?$row[ct_buy_price]:0) - ($row[tr_price_org]?$row[tr_price_org]:0) ;
		
		$bg = 'bg'.($i%2);

		
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
	
	<td class='mb-info-open' data-id='<?php echo $row['mb_id'] ?>' >
    <?php echo $row['ac_id']&&$row['ac_id']!=$row['mb_id']?$row['ac_id'].' @ ':'' ?><?php echo $row['mb_id'] ?></td>
		
    <td><strong><?php echo number_format($row['mb_trade_amtlmt']) ?></strong></td>
<td  ><?php echo $row[ct_buy_price]>0 ?number_format2($row[ct_buy_price]):'0'?></td>
<td  ><?=$row[tr_price_org]?number_format2($row[tr_price_org]):0?></td>
<!--td  ><?php echo $enable_amt1>0 ?number_format2($enable_amt1,1):'0'?></td-->
<!--td  ><?php echo $enable_amt2>0 ?number_format2($enable_amt2,1):'0'?></td-->
<td  ><?php echo $row[ct_cnt]>0 ?number_format($row[ct_cnt]):'-'?></td>
	<td  ><?=number_format($row['ac_point_'.$g5[cn_fee_coin]])?></td>
      <td  ><?=$row[ac_active]?'Y':'-'?></td>
	 <?
		//상품별 오토매칭
		foreach($g5['cn_item'] as $k=> $v){ ?>
      <td  ><?php echo $row['ac_auto_'.$k]?'Y':''?></td>
     <? }?> 
	 
    <td><?=$g5['cn_paytype'][$row['mb_trade_paytype']]?></td>
      <td class="td_date"><input type="checkbox" name="ac_mc_priority[<?=$row[ac_no]?>]" value="1" <?=$row[ac_mc_priority]=='1'?'checked':''?> data-id='<?=$row[ac_id]?>' ></td>
      <td class="td_date"><input type="checkbox" name="ac_mc_except[<?=$row[ac_no]?>]" value="1" <?=$row[ac_mc_except]==1?'checked':''?>  data-id='<?=$row[ac_id]?>' ></td>
    </tr>
    <?php
	
	$list_num--;
    }
    if ($i == 0)
        echo "<tr><td colspan=\"".$colspan."\" class=\"empty_table\">목록이 없습니다.</td></tr>";
    ?>
    </tbody>
    </table>
    
  
</div>

<div class="btn_fixed_top">

</div>


</form>

<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, '?'.$qstr.'&amp;page='); ?>

<script>

 $(document).ready(function () {
 
	$('input[name^=ac_mc_priority]').change(function () {
		var ac_id=$(this).attr('data-id');
		var val=$(this).is(':checked')?1:0;

		event.preventDefault();		

		$.ajax({
			type: "POST",
			url: "./item_buyer_list_update.php",
			data:{w:'p',ac_id:ac_id,val:val},
			cache: false,
			async: false,
			dataType:"json",
			success: function(data) {

				if(data.result==true){					
					alert_loading();
				}
				else alert(data.message);       
			}
		});		
		return;

	});
	
	$('input[name^=ac_mc_except]').change(function () {
		var ac_id=$(this).attr('data-id');
		var val=$(this).is(':checked')?1:0;

		event.preventDefault();		

		$.ajax({
			type: "POST",
			url: "./item_buyer_list_update.php",
			data:{w:'e',ac_id:ac_id,val:val},
			cache: false,
			async: false,
			dataType:"json",
			success: function(data) {

				if(data.result==true){					
					alert_loading();
				}
				else alert(data.message);       
			}
		});		
		return;

	});
	
})
	
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
		
	if(document.pressed == "완전삭제") {
        if(!confirm("선택한 자료를 정말 완전히 삭제하시겠습니까?\n\n삭제된 정보는 복구 할 수 없습니다")) {
            return false;
        }
    }
    return true;
}
</script>

<?php
include_once ('../admin.tail.php');
?>
