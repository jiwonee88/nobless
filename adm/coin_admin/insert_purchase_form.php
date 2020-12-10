<?php
$sub_menu = "700100";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'w');

$html_title = ($g5[cn_cointype]['i']).'구매내역';

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



if ($w == '') {

    $html_title .= ' 등록';

    $sound_only = '<strong class="sound_only">필수</strong>';
	
	$data['in_set_date1']=date("Y-m-d");
	$data['in_set_date2']=date("H:i:s");

} else if ($w == 'u') {

    $html_title .= ' 수정';
	
    $data= sql_fetch("select * from {$g5['cn_purchase_table']} where in_no='$in_no'");	
	$mb=get_member($data[mb_id]);
    if (!$data['in_no'])
        alert('존재하지 않은 내역 입니다.');

    if($data[in_stats]=='2') $readonly = 'readonly';
	
	
}


$g5['title'] = $html_title;
include_once(G5_ADMIN_PATH.'/admin.head.php');
include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');

?>
<form name="fcommonform" id="fcommonform" action="./insert_purchase_update.php" onsubmit="return fcommonform_submit(this)" method="post" enctype="multipart/form-data">
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

    <div class="tbl_frm01 tbl_wrap">
        <table>
         <colgroup>
            <col class="grid_4">
            <col>
            <col class="grid_3">
        </colgroup>
         
          <tr>
            <th scope="row"><label for="in_stats">입금상태</label></th>
            <td>
            <?=help('미입금 상태인경우 입급주소를 주기적으로 검사후 자동으로 처리됩니다 (토큰 입금 등록시)')?>
            <select id="in_stats" name="in_stats"  >
              <?
			foreach($g5['purchase_stat'] as $k=>$v) echo "<option value='{$k}' ".($data['in_stats']==$k ? 'selected':'').">{$v}</option>";
			?>
            </select>
            
           </td>
          </tr>
        
       
        <tbody>
        <tr>
            <th scope="row"><label for="mb_id">회원</label></th>
            <td>
            <?
			if(!$data['in_no']){?>
                <input type="text" name="mb_id" value="<?php echo get_text($mb['mb_id']) ?>" id="mb_id" required class="required frm_input" size="30" >
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
<th scope="row"><label for="in_set_amt2">신청상품</label></th>
<td><?php echo $data['in_item'] ?> (<strong>×<?php echo $data['in_item_qty'] ?></strong>)</td>
</tr>
        
        <tr>
          <th scope="row"><label for="rt_name">입금수단</label></th>
          <td><span class="local_sch01 local_sch">
            <select name='in_token' class="form-control input-sm  w-auto  mb-1" id="in_token" <?=$readonly?> <?=$disabled?> >
              <?
foreach($g5['cn_cointype'] as $k=>$v){	
	?>
              <option value='<?=$k?>' <?=$data['in_token']==$k?'selected':''?> >
                <?=$v?>
                </option>
              <? }?>
            </select>
          </span></td>
        </tr>
<tr>
<th scope="row"><label for="in_set_amt2">입금수량</label></th>
<td><input name="in_rsv_amt" type="text"  class="required frm_input number-comma" id="in_rsv_amt" required="required" value="<?php echo number_format2($data['in_rsv_amt'])?>" size="20" <?=$disabled?>/></td>
</tr>
        <tr>
          <th scope="row"><label for="in_txn_id">입금주소</label></th>
          <td>	
		  <?=help('입금을 확인할 주소 (토큰 입금 등록시)')?>
		  <input name="in_wallet_addr" type="text"  class=" frm_input" id="in_wallet_addr"  value="<?php echo $data['in_wallet_addr']?>" size="80" <?=$readonly?> <?=$disabled?>/></td>
        </tr>
<tr>
<th scope="row"><label for="in_txn_id">입금자/Txid</label></th>
<td><?=help('입금을 확인할 주소 (토큰 입금 등록시)')?>
<input name="in_txn_id" type="text"  class=" frm_input" id="in_txn_id"  value="<?php echo $data['in_txn_id']?>" size="80" <?=$readonly?> <?=$disabled?>/></td>
</tr>
	 <?
		if($data['in_stats']=='1'){?>		
<tr>
<th scope="row"><label for="rt_name">지급수단</label></th>
<td><span class="local_sch01 local_sch">
<select name='in_token' class="form-control input-sm  w-auto  mb-1" id="in_token" <?=$readonly?> <?=$disabled?> >
<?
foreach($g5['cn_cointype'] as $k=>$v){	
	?>
<option value='<?=$k?>' <?=$data['in_set_token']==$k?'selected':''?> >
<?=$v?>
</option>
<? }?>
</select>
</span></td>
</tr>


<tr>
<th scope="row"><label for="in_set_amt2">지급수량</label></th>
<td><input name="in_set_amt" type="text"  class="required frm_input number-comma" id="in_set_amt" required="required" value="<?php echo number_format2($data['in_set_amt'])?>" size="20" <?=$disabled?>/></td>
</tr>
         
	<?
	}
		if($data['in_stats']==3){?>	
        
          <tr>
            <th scope="row"><label for="in_set_amt">입금처리수량</label></th>
            <td><?php echo $data['in_stats']=='3'?number_format2($data['in_set_amt']):'-'?></td>
          </tr>
          <tr>
            <th scope="row"><label for="in_set_date2">입금처리일시</label></th>
            <td><?=$data['in_stats']=='3'?$data['in_set_date']:'-'?></td>
          </tr>
         
          <tr>
            <th scope="row">등록정보</th>
            <td><?=$data['in_wdate']?></td>
          </tr>
       
        <?}
		
		
		if($data['in_no']){?>	
        
        <tr>
          <th scope="row">LOG</th>
          <td>
		  등록일 : <?=$data[in_wdate]?><br>
<?=nl2br($data['in_log'])?></td>
        </tr>
        <? }?>
        </tbody>
        </table>
    </div>
</section>

<div class="btn_fixed_top">
	<a href="./insert_purchase_list.php?<?=$qstr?>"  class=" btn_02 btn">전체목록</a>
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
	
	 if (f.in_wallet_addr.value.length==0) {
            //alert("입금지갑 주소가 없습니다.");
            //f.in_wallet_addr.focus();
           // return false;
        }
		if (f.in_rsv_amt.value.length==0 || f.in_rsv_amt.value == 0) {			
            alert("입금예정액을 입력하세요");
            f.in_rsv_amt.focus();
            return false;
        }
	
		
		
    return true;
}
</script>

<?php
include_once(G5_ADMIN_PATH.'/admin.tail.php');
?>
