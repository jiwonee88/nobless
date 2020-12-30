<?php
$sub_menu = "700400";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'w');

$html_title = '계정간이체';

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
	
	$data['tr_set_date1']=date("Y-m-d");
	$data['tr_set_date2']=date("H:i:s");

} else if ($w == 'u') {

    $html_title .= ' 수정';
	
    $data= sql_fetch("select * from {$g5['cn_transfer_table']} where tr_no='$tr_no'");	

    if (!$data['tr_no'])
        alert('존재하지 않은 내역 입니다.');

    $disabled = 'disabled';
	
	
	
}


$g5['title'] = $html_title;
include_once(G5_ADMIN_PATH.'/admin.head.php');

?>
<form name="fcommonform" id="fcommonform" action="./coin_transfer_update.php" onsubmit="return fcommonform_submit(this)" method="post" enctype="multipart/form-data">
<input type="hidden" name="w" value="<?php echo $w ?>">
<input type="hidden" name="tr_no" value="<?php echo $tr_no ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
<input type="hidden" name="stx" value="<?php echo $stx ?>">
<input type="hidden" name="sst" value="<?php echo $sst ?>">
<input type="hidden" name="sod" value="<?php echo $sod ?>">
<input type="hidden" name="page" value="<?php echo $page ?>">
<input type="hidden" name="mb_stx" value="<?php echo $mb_stx ?>">

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
            <th scope="row"><label for="tr_stats">이체상태</label></th>
            <td>           
            <select id="tr_stats" name="tr_stats"  >
              <?
			foreach($g5['cn_instats'] as $k=>$v) echo "<option value='{$k}' ".($data['tr_stats']==$k ? 'selected':'').">{$v}</option>";
			?>
            </select>
            
           </td>
          </tr>
        
       
        <tbody>
        <tr>
            <th scope="row"><label for="tmb_id">보내는 회원</label></th>
            <td>
            <?
			if(!$data['tr_no']){?>
                <input type="text" name="mb_id" value="" id="mb_id" required class="required frm_input" size="30" <?=$disabled?> placeholder='아이디' >
                <input type="button" value="회원검색" id="openMSearchBtn" class="btn btn_03" />
            
            <? }else{
				
				$mb=get_member($data['mb_id']);
				echo $mb['mb_id']." [{$mb['mb_hp']}]";
				
				?>
				<input type="hidden" name="mb_id" value="<?php echo $data[mb_id]?>">

            
            <? }?>
            
            
            </td>
        </tr>
<tr>
<th scope="row"><label for="tmb_id">받는 회원 </label></th>
<td><?
			if(!$data['tr_no']){?>
<input type="text" name="tmb_id" value="" id="tmb_id" required class="required frm_input" size="30" <?=$disabled?> placeholder='아이디' >
<input type="button" value="회원검색" id="openMSearchBtn2" class="btn btn_03" />
<? }else{
				
				$tmb=get_member($data['mb_id']);
				echo $mb['mb_id']." [{$mb['mb_hp']}]";
				
				?>
<input type="hidden" name="tmb_id" value="<?php echo $data[tmb_id]?>">
<? }?></td>
</tr>
<tr>
<th scope="row"><label for="tr_set_amt2">서브계정아이디</label></th>
<td>
<?
			if(!$data['tr_no']){?>
			
			<input name="tr_amt" type="text"  class="required frm_input number-comma" id="tr_amt" required="required" value="<?php echo number_format2($data['tr_amt'])?>" size="20" <?=$disabled?>/>
			<? }else{
							
			 echo $data['stmb_id']? '<b>'.$data['stmb_id'].'</b> @ ':'' ?><?php echo $data['tmb_id'];
				
				}?>
			</td>
</tr>
        
        <tr>
          <th scope="row"><label for="rt_name">이체수단</label></th>
          <td><span class="local_sch01 local_sch">
            <select name='tr_token' class="form-control input-sm  w-auto  mb-1" id="tr_token" <?=$disabled?> >
              <?
		foreach($g5['cn_cointype'] as $k=>$v){
			?>
              <option value='<?=$k?>' <?=$data['tr_token']==$k?'selected':''?> >
                <?=$v?>
                </option>
              <? }?>
            </select>
          </span></td>
        </tr>
<tr>
<th scope="row"><label for="tr_set_amt2">이체수량</label></th>
<td><input name="tr_amt" type="text"  class="required frm_input number-comma" id="tr_amt" required="required" value="<?php echo number_format2($data['tr_amt'])?>" size="20" <?=$disabled?>/></td>
</tr>

 <?
		if($data['tr_no']){?>
        
		
<tr>
<th scope="row"><label for="tr_set_amt2">수수료</label></th>
<td><?php echo number_format2($data['tr_fee'])?></td>
</tr>
         
		
          <tr>
            <th scope="row"><label for="tr_set_amt">이체처리수량</label></th>
            <td><?php echo $data['tr_stats']=='3'?number_format2($data['tr_set_amt']):'-'?></td>
          </tr>
          <tr>
            <th scope="row"><label for="tr_set_date2">이체처리일시</label></th>
            <td><?=$data['tr_stats']=='3'?$data['tr_set_date']:'-'?></td>
          </tr>
         
          <tr>
            <th scope="row">등록정보</th>
            <td><?=$data['tr_wdate']?></td>
          </tr>
        <? }?>
        </tbody>
        </table>
    </div>
</section>

<div class="btn_fixed_top">
	<a href="./coin_transfer_list.php?<?=$qstr?>"  class=" btn_02 btn">전체목록</a>
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
	$('#'+tg).val(datas.mb_id);
	
	$('#member_search').hide();
}

$(document).ready(function(e) {
    $('#openMSearchBtn').click(function(){
	
		$('#myModalLabel').html('회원 검색')
		$("input[name='mb_id']").val('');
		
		search_member_open('mb_id');
	});
	$('#openMSearchBtn2').click(function(){
	
		$('#myModalLabel').html('회원 검색')
		$("input[name='tmb_id']").val('');
		
		search_member_open('tmb_id');
	});

});


function fcommonform_submit(f)
{
	
	 if (f.tmb_id.value.length==0) {
            //alert("이체지갑 주소가 없습니다.");
            //f.tmb_id.focus();
           // return false;
        }
		if (f.tr_amt.value.length==0 || f.tr_amt.value == 0) {
            alert("이체예정액을 입력하세요");
            f.tr_amt.focus();
            return false;
        }
		
		
    return true;
}
</script>

<?php
include_once(G5_ADMIN_PATH.'/admin.tail.php');
?>
