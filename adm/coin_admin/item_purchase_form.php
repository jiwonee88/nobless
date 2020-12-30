<?php
$sub_menu = "700850";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'w');

$html_title = ($g5[cn_item_name]).'구매내역';

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

if($item_stx) {
	$qstr.="&item_stx=$item_stx";
	$common_form.="<input type='hidden' name='item_stx' value='".$coin_stx."'>";	
	
}

if ($w == '') {

    $html_title .= ' 등록';

    $sound_only = '<strong class="sound_only">필수</strong>';
	
	$data['it_set_date1']=date("Y-m-d");
	$data['it_set_date2']=date("H:i:s");

} else if ($w == 'u') {

    $html_title .= ' 수정';
	
    $data= sql_fetch("select * from {$g5['cn_item_purchase']} where it_no='$it_no'");	
	$mb=get_member($data[mb_id]);
    if (!$data['it_no'])
        alert('존재하지 않은 내역 입니다.');

    if($data[it_stats]=='2') $readonly = 'readonly';
	
	
}


$g5['title'] = $html_title;
include_once(G5_ADMIN_PATH.'/admin.head.php');
include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');

?>
<form name="fcommonform" id="fcommonform" action="./item_purchase_update.php" onsubmit="return fcommonform_submit(this)" method="post" enctype="multipart/form-data">
<input type="hidden" name="w" value="<?php echo $w ?>">
<input type="hidden" name="it_no" value="<?php echo $it_no ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
<input type="hidden" name="stx" value="<?php echo $stx ?>">
<input type="hidden" name="sst" value="<?php echo $sst ?>">
<input type="hidden" name="sod" value="<?php echo $sod ?>">
<input type="hidden" name="page" value="<?php echo $page ?>">
<input type="hidden" name="mb_stx" value="<?php echo $mb_stx ?>">
<input type='hidden' name='it_token' value='u' >

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
            <th scope="row"><label for="it_stats">구매상태</label></th>
            <td>
            <?=help('미입금 상태인경우 입급주소를 주기적으로 검사후 자동으로 처리됩니다 (토큰 입금 등록시)')?>
            <select id="it_stats" name="it_stats"  >
              <?
			foreach($g5['item_purchase_stat'] as $k=>$v) echo "<option value='{$k}' ".($data['it_stats']==$k ? 'selected':'').">{$v}</option>";
			?>
            </select>
            
           </td>
          </tr>
         <?
			if(!$data['it_no']){?>
        <tr>
            <th scope="row"><label for="mb_id">회원</label></th>
            <td>
           
                <input type="text" name="mb_id" value="<?php echo get_text($mb['mb_id']) ?>" id="mb_id" required class="required frm_input" size="30" >
                <input type="button" value="회원검색" id="openMSearchBtn" class="btn btn_03" />
            
            </td>
        </tr>
		<tr>
		<th scope="row"><label for="mb_id">서브아이디</label></th>
		<td><input name="smb_id" type="text"  class="required frm_input" id="smb_id" required="required" value="" size="20" <?=$disabled?>/></td>
		</tr>



		<tr>
		<th scope="row"><label for="it_set_amt2">상품선택</label></th>
		<td><span class="local_sch01 local_sch">
		<select name='cn_item' class="form-control input-sm  w-auto  mb-1" id="cn_item" >
		<?
		foreach($g5['cn_item'] as $k=>$v){?>
		<option value='<?=$k?>' <?=$data[cn_item]==$k?'selected':''?> >
		<?=$v[name_kr]?>
		</option>
		<? }?>
		</select>
		</span></td>
		</tr>
<tr>
<th scope="row"><label for="it_set_amt2">상품수량</label></th>
<td><input name="qty" type="text"  class="required frm_input  number-comma" id="qty" required="required"  size="20" value='<?=$data[it_item_qty]?>' <?=$disabled?>/></td>
</tr>
		
         
<? }?>
<tr>
<th scope="row"><label for="rt_name">지불수단</label></th>
<td><span class="local_sch01 local_sch">
<select name='it_set_token' class="form-control input-sm  w-auto  mb-1" id="it_set_token" <?=$readonly?> <?=$disabled?> >
<?
foreach($g5['cn_cointype'] as $k=>$v){	
	?>
<option value='<?=$k?>' <?=$data['it_set_token']==$k?'selected':''?> >
<?=$v?>
</option>
<? }?>
</select>
</span></td>
</tr>


<tr>
<th scope="row"><label for="it_set_amt2">지불수량</label></th>
<td><input name="it_set_amt" type="text"  class="required frm_input number-comma" id="it_set_amt" required="required" value="<?php echo number_format2($data['it_set_amt'])?>" size="20" <?=$disabled?>/></td>
</tr>


<?
	if($data['it_no']){?>
	<tr>
            <th scope="row"><label for="mb_id">회원</label></th>
            <td>
           <?
				
				$mb=get_member($data['mb_id']);
				echo $data[smb_id].'@'.$data['mb_id']." [{$mb['mb_hp']}]";
				
			?>	
				<input type="hidden" name="mb_id" value="<?php echo $data[mb_id]?>">
				<input type="hidden" name="smb_id" value="<?php echo $data[smb_id]?>">

            
            
            </td>
        </tr>
		

<tr>
<th scope="row"><label for="it_set_amt2">신청상품</label></th>
<td><?php echo $data['cn_item_name'] ?> (<strong>×<?php echo $data['it_item_qty'] ?></strong>)</td>
</tr>

<tr>
<th scope="row"><label for="it_set_amt2">상품의가격</label></th>
<td><?php echo number_format2($data['it_rsv_amt'])?> <?=$g5[cn_cointype][$data[it_token]]?></td>
</tr>
<?

	if($data['it_stats']==3){?>	

	  <tr>
		<th scope="row"><label for="it_set_amt">입금처리수량</label></th>
		<td><?php echo $data['it_stats']=='3'?number_format2($data['it_set_amt']):'-'?></td>
	  </tr>
	  <tr>
		<th scope="row"><label for="it_set_date2">입금처리일시</label></th>
		<td><?=$data['it_stats']=='3'?$data['it_set_date']:'-'?></td>
	  </tr>

	  <tr>
		<th scope="row">등록정보</th>
		<td><?=$data['it_wdate']?></td>
	  </tr>

	<?}
		
        ?>
        <tr>
          <th scope="row">LOG</th>
          <td>
		  등록일 : <?=$data[it_wdate]?><br>
<?=nl2br($data['in_log'])?></td>
        </tr>
        <? }?>
        </tbody>
        </table>
    </div>
</section>

<div class="btn_fixed_top">
	<a href="./item_purchase_list.php?<?=$qstr?>"  class=" btn_02 btn">전체목록</a>
    <input type="submit" value="확인" class="btn_submi btn btn_01" accesskey="s">
</div>

</form>
<?
include "./member_search_modal.php";
?>
<script>  


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
	
		
    return ttrue;
}
</script>

<?php
include_once(G5_ADMIN_PATH.'/admin.tail.php');
?>
