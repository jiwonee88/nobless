<?php
$sub_menu = "700500";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'w');

$html_title = '코인스왑';

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
if($stats_stx) {
	$qstr.="&stats_stx=$stats_stx";
	$common_form.="<input type='hidden' name='stats_stx' value='".$stats_stx."'>";	
}


if ($w == '') {

    $html_title .= ' 등록';

    $sound_only = '<strong class="sound_only">필수</strong>';
	
	$data[sw_set_token]='e';

} else if ($w == 'u') {

    $html_title .= ' 수정';
	
    $data= sql_fetch("select * from {$g5['cn_swap_table']} where sw_no='$sw_no'");	

    if (!$data['sw_no'])
        alert('존재하지 않은 내역 입니다.');

    $disabled = 'disabled';
	
	
}


$g5['title'] = $html_title;
include_once(G5_ADMIN_PATH.'/admin.head.php');

?>
<form name="fcommonform" id="fcommonform" action="./coin_swap_update.php" onsubmit="return fcommonform_submit(this)" method="post" >
<input type="hidden" name="w" value="<?php echo $w ?>">
<input type="hidden" name="sw_no" value="<?php echo $sw_no ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
<input type="hidden" name="stx" value="<?php echo $stx ?>">
<input type="hidden" name="sst" value="<?php echo $sst ?>">
<input type="hidden" name="sod" value="<?php echo $sod ?>">
<input type="hidden" name="page" value="<?php echo $page ?>">

<input type="hidden" name="token" value="">
<?=$common_form?>
<section id="anc_rt_basic">

    <div class="tbl_frm01 tbl_wrap">
        <table>
<tr>
<th scope="row"><label for="deposit_date">정산일자</label></th>
<td><input type="text" name="deposit_date" value="<?php echo substr($data['deposit_date'],0,10) ?>" id="deposit_date"  class="frm_input calendar-input" size="25" autocomplete='off' /></td>
</tr>
         <colgroup>
            <col class="grid_4">
            <col>
            <col class="grid_3">
        </colgroup>
         
          <tr>
            <th scope="row"><label for="sw_stats">지급구분</label></th>
            <td>&nbsp;</td>
          </tr>
        
       
        <tbody>
        
        <tr>
          <th scope="row"><label for="sw_token">스왑코인</label></th>
          <td><span class="local_sch01 local_sch">
            <select name='sw_token' class="form-control input-sm  w-auto  mb-1" id="sw_token" >
              <?
		foreach($g5['cn_cointype'] as $k=>$v){
			?>
              <option value='<?=$k?>' <?=$data['sw_token']==$k?'selected':''?> >
                <?=$v?>
                </option>
              <? }?>
            </select>
-&gt;
          <select name='sw_set_token' class="form-control input-sm  w-auto  mb-1" id="sw_set_token"  >
<?
		foreach($g5['cn_cointype'] as $k=>$v){
			?>
<option value='<?=$k?>' <?=$data['sw_set_token']==$k?'selected':''?> >
<?=$v?>
</option>
<? }?>
</select>
</span></td>
        </tr>
<tr>
<th scope="row"><label for="sw_set_amt2">스왑수량</label></th>
<td><input name="sw_amt" type="text"  class="required frm_input number-comma" id="sw_amt" required="required" value="<?php echo number_format2($data['sw_amt'])?>" size="20" /></td>
</tr>

 <?
		if($data['sw_no']){?>
        
		
<tr>
<th scope="row">수수료</th>
<td><?php echo number_format2($data['sw_fee'])?></td>
</tr>
         
		
          <tr>
            <th scope="row"><label for="sw_set_amt">스왑처리수량</label></th>
            <td><?php echo $data['sw_stats']=='3'?number_format2($data['sw_set_amt']):'-'?></td>
          </tr>
          <tr>
            <th scope="row"><label for="sw_set_date2">스왑처리일시</label></th>
            <td><?=$data['sw_stats']=='3'?$data['sw_set_date']:'-'?></td>
          </tr>
         
          <tr>
            <th scope="row">등록정보</th>
            <td><?=$data['sw_wdate']?></td>
          </tr>
        <? }?>
        </tbody>
        </table>
    </div>
</section>

<div class="btn_fixed_top">
	<a href="./coin_swap_list.php?<?=$qstr?>"  class=" btn_02 btn">전체목록</a>
    <input type="submit" value="확인" class="btn_submi btn btn_01" accesskey="s">
</div>

</form>
<?
include "./member_search_modal.php";
?>
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
	$('#'+tg).val(datas.mb_email);
	
	$('#member_search').hide();
}

$(document).ready(function(e) {
    $('#openMSearchBtn').click(function(){
	
		$('#myModalLabel').html('회원 검색')
		$("input[name='mb_email']").val('');
		
		search_member_open('mb_email');
	});
});


function fcommonform_submit(f)
{
	
	if ($('select[name=sw_token]').val() == $('select[name=sw_set_token]').val() ) {
		alert("서로 다른 코인을 선택하세요");
		f.sw_token.focus();
		return false;
	}

	
	if (f.sw_amt.value.length==0 || f.sw_amt.value == 0) {
		alert("스왑예정액을 입력하세요");
		f.sw_amt.focus();
		return false;
	}

		
    return true;
}
</script>

<?php
include_once(G5_ADMIN_PATH.'/admin.tail.php');
?>
