<?php
$sub_menu = "700740";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'w');

$html_title = $g5[cn_item_name].'-직접매칭';


$g5['title'] = $html_title;
include_once(G5_ADMIN_PATH.'/admin.head.php');
include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');

?>
<form name="fcommonform" id="fcommonform" action="./item_matching.direct.update.php" onsubmit="return fcommonform_submit(this)" method="post" enctype="multipart/form-data">
<input type="hidden" name="w" value="u">

<input type="hidden" name="token" value="">
<section id="anc_rt_basic">

    <div class="tbl_frm01 tbl_wrap">
        <table>
<tr>
<th scope="row"><label for="in_set_amt2">매칭기준일자</label></th>
<td><input name="start_date" type="text"  class="frm_input calendar-input " id="start_date" value="<?=date("Y-m-d")?>" size="20" /></td>
</tr>
         <colgroup>
            <col class="grid_4">
            <col>
            <col class="grid_3">
        </colgroup>
        
       
        <tbody>
        <tr>
            <th scope="row"><label for="mb_id">회원</label></th>
            <td>
            
                <input type="text" name="mb_id" value="<?php echo get_text($mb['mb_id']) ?>" id="mb_id" required class="required frm_input" size="30" >
                <input type="button" value="회원검색" id="openMSearchBtn" class="btn btn_03" />
            
            
            </td>
        </tr>
<tr>
<th scope="row"><label for="in_set_amt2">서브계정아이디</label></th>
<td><input name="smb_id" type="text"  class="frm_input" id="smb_id" size="20" <?=$disabled?>/></td>
</tr>
         
		
          <tr>
            <th scope="row"><label for="in_set_amt"> 매칭수량</label></th>
            <td>

 <?php
$cn_item_arr=array_reverse($g5['cn_item']);
foreach($cn_item_arr as $k=> $v) {          
?>
<p style='margin-bottom:5px;'><?=$v[name_kr]?> (<?=number_format2($v[price])?>) :
<input type='hidden' name='item_fee[<?=$k?>]'  value='<?=$v[fee]?>'>
<input type='hidden' name='item_price[<?=$k?>]'  value='<?=$v[price]?>'>
<select name='item_qty[<?=$k?>]' class="form-control input-sm  w-auto  mb-1" id="item_qty_<?=$k?>"  >
<option value='0' >-수량-</option>
 <?php
for($i=1;$i <= 50;$i++) {        
?>
<option value='<?=$i?>'><strong><?=$i?></strong></option>
<? }?>
</select>
</p>
<? }?>
</td>
          </tr>
<tr>
<th scope="row">총지급금액</th>
<td>$<strong><span id='tot_price'>0</span></strong></td>
</tr>
<tr>
<th scope="row"><label for="in_set_amt2">판매자 최소 보유금</label></th>
<td><input name="min_money" type="text"  class="frm_input number-comma" id="min_money" value="0" size="15"/>
이상 수수료 금액 보유자만 매도 참여</td>
</tr>
<tr>
<th scope="row"><label for="in_set_amt2">판매자수수료</label></th>
<td><label>
<?=help('지불할 수수료가 부족한 매도자는 지불 가능 수수료 한도내에서만 매칭에 참여 됩니다. 금액이 큰 '.$g5['cn_item_name'].' 매칭부터 우선 사용됩니다')?>
<input name="seller_fee_free" type="radio"  value="free"  >
수수료 없이 매칭 진행</label>
&nbsp;&nbsp;
<label>
<input name="seller_fee_free" type="radio"  value="pay" checked="checked"   >
<?=$g5['cn_item_name']?>
별 지정 수수료 부과</label></td>
</tr>
<tr>
<th scope="row"><label for="in_set_amt2">구매자수수료</label></th>
<td><?=help('지불할 수수료가 부족한 매수자는 지불 가능 수수료 한도내에서만 매칭에 참여 됩니다. 금액이 큰 '.$g5['cn_item_name'].' 매칭부터 우선 사용됩니다')?>

<label>
<input name="fee_free" type="radio"  value="free">
수수료 없이 매칭 진행</label>
&nbsp;&nbsp; 

<label>
<input name="fee_free" type="radio"  value="pay" checked="checked"  >
<?=$g5['cn_item_name']?>
별 지정 수수료 부과</label>
&nbsp;&nbsp;&nbsp;</td>
</tr>
<!--tr>
<th scope="row">수수료</th>
<td><strong><span id='tot_fee'>
<input name="tot_fee" id="tot_fee"  type="text"  class="frm_input"  value="0" size="20" readonly />
</span></strong> <?=$g5[cn_cointype][$g5['cn_fee_coin']]?></td>
</tr-->
    
        </tbody>
        </table>
    </div>
</section>

<div class="btn_fixed_top">
	<a href="./item_cart_list.php?<?=$qstr?>"  class=" btn_02 btn">보유목록</a>
    <input type="submit" value="지급하기" class="btn_submi btn btn_01" accesskey="s">
</div>

</form>
<?
include "./member_search_modal.php";
?>
<script>  
function sum(){
	var tot=0;
	var tfee=0;
	
	$("select[name^=item_qty]").each(function(){
		var qty=parseInt($(this).val());
		var price=parseFloat($(this).prev("input[name^=item_price]").val());
		var fee=parseInt($(this).prev("input").prev("input[name^=item_fee]").val());
		tot+=(qty*price).toFixed(1)*1;
		tfee+=(qty*fee).toFixed(1)*1;;
	});

	$("#tot_price").html(inputNumberFormat(tot));
	//$("input#tot_fee").val(inputNumberFormat(tfee));

}

function member_select(tg,datas){
	var ov=$('#'+tg).val();
	//$('#'+tg).val(ov+(ov?',':'')+datas.mb_id);
	$('#'+tg).val(datas.mb_id);
	
	$('#member_search').hide();
}

$(document).ready(function(e) {
	$("select[name^=item_qty]").change(function(){
		sum();
	})
	
    $('#openMSearchBtn').click(function(){
	
		$('#myModalLabel').html('회원 검색')
		$("input[name='mb_id']").val('');
		
		search_member_open('mb_id');
	});

});


function fcommonform_submit(f)
{
	
	
	var qty=0;
	$("select[name^=item_qty]").each(function(){
		 qty+=parseInt($(this).val());		
	});
	
	 if (qty < 1) {
          alert("최소 한개 이상 지급 하세요.");          
          return false;
      }
		
    return true;
}
</script>	

<?php
include_once(G5_ADMIN_PATH.'/admin.tail.php');
?>
