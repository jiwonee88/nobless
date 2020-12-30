<?php
$sub_menu = "800100";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'w');

$html_title = '수당계산';


$g5['title'] = $html_title;
include_once(G5_ADMIN_PATH.'/admin.head.php');
include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');

?>
<form name="fcommonform" id="fcommonform" action="./insert_reserve_update.php" onsubmit="return fcommonform_submit(this)" method="post" enctype="multipart/form-data">
<input type="hidden" name="w" value="<?php echo $w ?>">
<input type="hidden" name="in_no" value="<?php echo $in_no ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
<input type="hidden" name="stx" value="<?php echo $stx ?>">
<input type="hidden" name="sst" value="<?php echo $sst ?>">
<input type="hidden" name="sod" value="<?php echo $sod ?>">
<input type="hidden" name="page" value="<?php echo $page ?>">
<input type="hidden" name="mb_stx" value="<?php echo $mb_stx ?>">

<input type="hidden" name="token" value="">
<?=$common_form?>
<section id="anc_rt_basic">
   <div class="position-relative w-100 overflow-hidden" style='position:relative;' >
    <div class="tbl_frm01 tbl_wrap" style='position:absolute;top:0;left:0;width:400px;' >
        <table>        
       
        <tbody>
          <tr>
            <th class='w-100p'  scope="row"><label for="in_wallet_addr">계산기간</label></th>
            <td><input name="in_set_date1" type="text" required=required class=" required frm_input  calendar-input" id="in_set_date1" value="<?php echo date("Y-m-d",strtotime('-1 days'))?>" size="20" <?=$readonly?> <?=$disabled?>/>
            <input name="in_set_date1" type="text"  required=required class=" required frm_input  calendar-input" id="in_set_date1" value="<?php echo date("Y-m-d",strtotime('-1 days'))?>" size="20" <?=$readonly?> <?=$disabled?>/></td>
          </tr>
          <tr>
            <th class='w-100p'  scope="row"><label for="in_set_amt">지급일</label></th>
            <td><input name="in_rsv_amt" type="text"  class="required frm_input calendar-input" id="in_rsv_amt" required="required" value="<?php echo date("Y-m-d")?>" size="20" <?=$disabled?>/></td>
          </tr>
          <tr>
            <th class='w-100p'  scope="row">작업선택</th>
            <td>
<a href="./insert_reserve_list.php?<?=$qstr?>"  class=" btn_02 btn mb-10">판매계산</a><br />
<a href="./insert_reserve_list.php?<?=$qstr?>"  class=" btn_02 btn  mb-10">직급계산</a><br />
<a href="./insert_reserve_list.php?<?=$qstr?>"  class=" btn_02 btn">수당계산</a>

            
            </td>
          </tr>
          <?
		if($data['in_no']){?>
          <? }?>
        </tbody>
        </table>
    </div>
    
    
    <div  style='width:100%;padding-left:420px;height:350px;' >
	<iframe src='./settle_result.php' name='resultf' id='resultf' width='100%' height='100%' style='border:1px solid #dddddd;' ></iframe>
    
    </span>

    </div>
    
    </div>
  </section>
    
</section>





<div class="btn_fixed_top">
	<a href="./insert_reserve_list.php?<?=$qstr?>"  class=" btn_02 btn">전체목록</a>
    <input type="submit" value="확인" class="btn_submi btn btn_01" accesskey="s">
</div>

</form>


<?

if($date_start_stx) {
	$qstr.="&date_start_stx=$date_start_stx";
	$common_form.="<input type='hidden' name='date_start_stx' value='".$date_start_stx."'>";
}
if($date_end_stx) {
	$qstr.="&date_end_stx=$date_end_stx";
	$common_form.="<input type='hidden' name='date_end_stx' value='".$date_end_stx."'>";
}
if($coin_stx) {
	$qstr.="&coin_stx=$coin_stx";
	$common_form.="<input type='hidden' name='coin_stx' value='".$coin_stx."'>";	
}




$sql_common = " from {$g5['cn_reserve_table']} a left outer join  {$g5['member_table']} as b on(a.mb_id=b.mb_id) ";
$sql_search = " where (1) ";

if($date_start_stx) {
	$sql_search .= " and a.in_wdate >= '$date_start_stx 00:00:00' ";
	$qstr.="&date_start_stx=$date_start_stx";
}
if($date_end_stx) {
	$sql_search .= " and a.in_wdate <= '$date_end_stx 23:59:59' ";
	$qstr.="&date_end_stx=$date_end_stx";
}
if($coin_stx) {
	$sql_search .= " and a.in_token = '$coin_stx' ";
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
    $sst  = "a.in_no ";
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

$sql = " select * {$sql_common} {$sql_search} {$sql_order} limit {$from_record}, {$rows} ";
$result = sql_query($sql);

$listall = '<a href="'.$_SERVER['SCRIPT_NAME'].'" class="ov_listall">전체목록</a>';

$g5['title'] = '입금내역';

include_once(G5_ADMIN_PATH.'/admin.head.php');

$colspan = 14;
include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');

?>

<div class="local_ov01 local_ov">
    <?php echo $listall ?>
    <span class="btn_ov01"><span class="ov_txt">등록된 수</span><span class="ov_num"> <?php echo number_format($total_count) ?>개</span></span>
</div>

<form name="fsearch" id="fsearch" class="local_sch01 local_sch" method="get">


<span class='nowrap'>기간:
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
<option value=''>-입금수단-</option>
<?
foreach($g5['cn_cointype'] as $k=>$v){?>
<option value='<?=$k?>' <?=$coin_stx==$k?'selected':''?> ><?=$v?></option>
<? }?>
</select>


<select name="sfl" id="sfl">
<option value="a.mb_id"<?php echo get_selected($_GET['sfl'], "a.mb_id"); ?>>회원아이디</option>
<option value="b.mb_name"<?php echo get_selected($_GET['sfl'], "b.mb_name"); ?>>회원명</option>
</select>

<label for="stx" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
<input type="text" name="stx" value="<?php echo $stx ?>" id="stx" class="frm_input">
<input type="submit" value="검색" class="btn_submit">

</form>


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
        <th scope="col"><?php echo subject_sort_link('a.mb_id') ?>아이디</a></th>
        <th scope="col"><?php echo subject_sort_link('b.mb_grade') ?>등급</a></th>
        <th scope="col">후원인</th>
        <th scope="col">추천인</th>
        <th scope="col"><?php echo subject_sort_link('a.in_coin') ?>지불수단</a></th>
        <th scope="col">구매수량</th>
        <th scope="col">입금수량</th>
        <!--th scope="col">입금계좌</th-->
        <th scope="col">입금처리일</th>
        <th scope="col"><?php echo subject_sort_link('a.in_status') ?>입금상태</a></th>
        <th scope="col">관리</th>
    </tr>
    </thead>
    <tbody>
    <?php
	$list_num = $total_count - ($page - 1) * $rows;
	
    for ($i=0; $row=sql_fetch_array($result); $i++) {
		
		//정산여부
		if(get_settlestat($row['st_set_date'])) $disabled = 'disabled';
		else $disabled = '';
		
        $one_update = '<a href="./insert_reserve_form.php?w=u&amp;in_no='.$row['in_no'].'&amp;'.$qstr.'" class="btn btn_03">관리</a>';

        $bg = 'bg'.($i%2);
		
    ?>
	
    <tr class="<?php echo $bg; ?>">
        <td class="td_chk">
            <label for="chk_<?php echo $i; ?>" class="sound_only">선택</label>
            <input type="checkbox" name="chk[]" value="<?php echo $i ?>" id="chk_<?php echo $i ?>" <?=$disabled?> >
            <input type="hidden" name="in_no[<?php echo $i ?>]" value="<?php echo $row['in_no'] ?>">
        </td>
       <td  class="td_num">
       <?=$list_num?>
        </td>
       <td class="td_left"><?php echo $row['mb_name'] ?></td>
       <td ><?php echo $g5['member_grade'][$row['mb_grade']] ?></td>
       <td ><?php echo $row['mb_recommend'] ?></td>
       <td ><?php echo $row['mb_recommend2'] ?></td>
       <td ><?=$g5['cn_cointype'][$row['in_token']]?></td>
       <td class="text-right"><?php echo number_format($row['in_rsv_amt']) ?></td>
       <td class="text-right"><?php echo number_format($row['in_set_amt']) ?></td>
       <!--td  class="td_num_c3"><?php echo $row['account'] ?></td-->
        <td class="td_datetime">
		<?php echo $row['in_set_date']?>
        </td>
        <td class="td_num_c3"><label for="deposit_status<?php echo $i; ?>" class="sound_only">입금상태</label>
          <span class="local_sch01 local_sch">
          <select id="in_stats[<?php echo $i ?>]" name="in_stats[<?php echo $i ?>]" <?=$disabled?> >
            <?
			foreach($g5['cn_instats'] as $k=>$v) echo "<option value='{$k}' ".($row['in_stats']==$k ? 'selected':'').">{$v}</option>";
			?>
          </select>
          </span></td>
        <td class="td_mng td_mng_m">
            <?php echo $one_update ?>
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


<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?'.$qstr.'&amp;page='); ?>
<script>  
var reg_mb_exist_check = function() {
    var result = "";
    $.ajax({
        type: "POST",
        url: g5_bbs_url+"/ajax.mb_exist.php",
        data: {
            "reg_mb_exist": encodeURIComponent($("#reg_mb_exist").val())
        },
        cache: false,
        async: false,
        success: function(data) {
            result = data;
        }
    });
    return result;
}

function member_select(tg,datas){
	var ov=$('#'+tg).val();
	//$('#'+tg).val(ov+(ov?',':'')+datas.mb_id);
	$('#'+tg).val(datas.mb_id);
	$('#in_wallet_addr').val(datas.mb_wallet_addr);
	
	$('#member_search').hide();
}

$(document).ready(function(e) {
    $('#openMSearchBtn').click(function(){
	
		$('#myModalLabel').html('회원 검색')
		$('#mblevle_stx').val('5')
		$("input[name='mb_id']").val('');
		
		search_member_open('mb_id');
	});

});


function fcommonform_submit(f)
{
	
	 if (f.in_wallet_addr.value.length==0) {
            alert("입금지갑 주소가 없습니다.");
            f.in_wallet_addr.focus();
            return false;
        }
		if (f.in_rsv_amt.value.length==0 || f.in_rsv_amt.value == 0) {
            alert("입금예정액을 입력하세요");
            f.in_rsv_amt.focus();
            return false;
        }
		if (f.in_rsv_date.value.length==0) {
            alert("입금예정일을 입력하세요.");
            f.in_rsv_date.focus();
            return false;
        }
		if ($("select[name='in_stats']").val()=='3' && f.in_set_date1.value.length==0) {
            alert("입급완료 설정시  입금처리일을 입력하세요.");
            f.in_set_date.focus();
            return false;
        }
		if ($("select[name='in_stats']").val()=='3' && parseFloat(f.in_set_amt.value)==0) {
            alert("입급완료 설정시 입금처리수량을 입력하세요.");
            f.in_set_amt.focus();
            return false;
        }
		
    return true;
}
</script>

<?php
include_once(G5_ADMIN_PATH.'/admin.tail.php');
?>
