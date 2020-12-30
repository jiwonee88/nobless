<?php
$sub_menu = "700300";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'w');

$html_title = '출금내역';

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
	
	$data['dr_set_date1']=date("Y-m-d");
	$data['dr_set_date2']=date("H:i:s");

} else if ($w == 'u') {

    $html_title .= ' 수정';
	
    $data= sql_fetch("select * from {$g5['cn_draw_table']} where dr_no='$dr_no'");	
	$mb=get_member($data[mb_id]);
	
    if (!$data['dr_no'])
        alert('존재하지 않은 내역 입니다.');

    $disabled = 'disabled';
	
	
	
}


$g5['title'] = $html_title;
include_once(G5_ADMIN_PATH.'/admin.head.php');

?>
<form name="fcommonform" id="fcommonform" action="./coin_draw_update.php" onsubmit="return fcommonform_submit(this)" method="post" enctype="multipart/form-data">
<input type="hidden" name="w" value="<?php echo $w ?>">
<input type="hidden" name="dr_no" value="<?php echo $dr_no ?>">
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
         <colgroup>
            <col class="grid_4">
            <col>
            <col class="grid_3">
        </colgroup>
         
          <tr>
            <th scope="row"><label for="dr_stats">출금상태</label></th>
            <td>
            <?=help('미출금 상태인경우 주기적으로 입급여부 검사후 자동으로 처리됩니다')?>
            <select id="dr_stats" name="dr_stats"  >
              <?
			foreach($g5['cn_instats'] as $k=>$v) echo "<option value='{$k}' ".($data['dr_stats']==$k ? 'selected':'').">{$v}</option>";
			?>
            </select>
            
           </td>
          </tr>
        
       
        <tbody>
       <tr>
            <th scope="row"><label for="mb_id">회원</label></th>
            <td>
            <?
			if(!$data['sk_no']){?>
                <input type="text" name="mb_id" value="<?php echo get_text($mb['mb_id']) ?>" id="mb_id" required class="required frm_input" size="30" <?=$disabled?> >
                <input type="button" value="회원검색" id="openMSearchBtn" class="btn btn_03" />
            
            <? }else{
				
				$mb=get_member($data['mb_id']);
				echo $mb['mb_id']." [{$mb['mb_email']}]";
				
				?>
				<input type="hidden" name="mb_id" value="<?php echo $data[mb_id]?>">

            
            <? }?>
            
            
            </td>
        </tr>
        
        <tr>
          <th scope="row"><label for="rt_name">출금수단</label></th>
          <td><span class="local_sch01 local_sch">
            <select name='dr_token' class="form-control input-sm  w-auto  mb-1" id="dr_token" <?=$disabled?> >
              <?
foreach($g5['cn_cointype'] as $k=>$v){	
	?>
              <option value='<?=$k?>' <?=$data['dr_token']==$k?'selected':''?> >
                <?=$v?>
                </option>
              <? }?>
            </select>
          </span></td>
        </tr>
<tr>
<th scope="row"><label for="dr_set_amt2">출금수량</label></th>
<td><input name="dr_amt" type="text"  class="required frm_input number-comma" id="dr_amt" required="required" value="<?php echo number_format2($data['dr_amt'])?>" size="20" <?=$disabled?>/></td>
</tr>
<tr>
<th scope="row"><label for="dr_set_amt2">수수료</label></th>
<td><input name="dr_fee" type="text"  class="required frm_input number-comma" id="dr_fee" required="required" value="<?php echo number_format2($data['dr_fee'])?>" size="20" <?=$disabled?>/></td>
</tr>
        <tr>
          <th scope="row"><label for="dr_wallet_addr">출금주소</label></th>
          <td>
		  <?=help('공란시 회원 지갑 주소')?>
		  <input name="dr_wallet_addr" type="text"  class=" frm_input" id="dr_wallet_addr"  value="<?php echo $data['dr_wallet_addr']?>" size="80"  /></td>
        </tr>
         
		 <?
		if($data['dr_no']){?>
        
          <tr>
            <th scope="row"><label for="dr_set_amt">출금처리수량</label></th>
            <td><?php echo number_format2($data['dr_set_amt'])?> <?=$g5['cn_cointype'][$data['dr_set_token']]?></td>
          </tr>
          <tr>
            <th scope="row"><label for="dr_set_date2">출금처리일시</label></th>
            <td><?=$data['dr_stats']=='3'?$data['dr_set_date']:'-'?></td>
          </tr>
         
          <tr>
            <th scope="row">등록정보</th>
            <td><?=$data['dr_wdate']?></td>
          </tr>
        <? }?>
        </tbody>
        </table>
    </div>
</section>

<div class="btn_fixed_top">
	<a href="./coin_draw_list.php?<?=$qstr?>"  class=" btn_02 btn">전체목록</a>
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
	//$('#'+tg).val(ov+(ov?',':'')+datas.mb_id);
	$('#'+tg).val(datas.mb_id);
	$('#dr_wallet_addr').val(datas.mb_wallet_addr_e);
	
	$('#member_search').hide();
}

$(document).ready(function(e) {
    $('#openMSearchBtn').click(function(){
	
		$('#myModalLabel').html('회원 검색')
		$("input[name='mb_id']").val('');
		
		search_member_open('mb_id');
	});

});


function fcommonform_submit(f)
{
	
	 if (f.dr_wallet_addr.value.length==0) {
            //alert("출금지갑 주소가 없습니다.");
            //f.dr_wallet_addr.focus();
           // return false;
        }
		if (f.dr_amt.value.length==0 || f.dr_amt.value == 0) {
            alert("출금예정액을 입력하세요");
            f.dr_amt.focus();
            return false;
        }
		if (f.in_rsv_date.value.length==0) {
            alert("출금예정일을 입력하세요.");
            f.in_rsv_date.focus();
            return false;
        }
		
		
    return true;
}
</script>

<?php
include_once(G5_ADMIN_PATH.'/admin.tail.php');
?>
