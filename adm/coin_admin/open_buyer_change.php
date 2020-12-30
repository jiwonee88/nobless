<?php
$sub_menu = "700600";
include_once('./_common.php');

 if(!$is_manager  &&  $is_admin!='super') alert_close('권한이 없습니다');
 
$row=sql_fetch("select a.*,
c.ct_class, c.ct_wdate,c.ct_validdate,c.ct_buy_price,c.ct_sell_price,c.ct_class,c.ct_interest,c.ct_days,c.cn_item ccn_item,
b.mb_id,b.mb_email,b.mb_hp,b.mb_name,b.mb_trade_get_claim,b.mb_trade_put_claim,b.mb_trade_penalty,b.mb_trade_penalty_date from {$g5['cn_item_trade']}  as a 
left outer join  {$g5['cn_item_cart']} as c on(a.cart_code=c.code) 
left outer join  {$g5['member_table']} as b on(a.mb_id=b.mb_id) 

where a.tr_code='$tr_code' $jisa_sql ",1);

if($row[tr_code]=='' )alert_close('거래정보를 찾을수 없습니다');

$g5['title'] = $mb[mb_id]."거래정보변경";


include_once(G5_ADMIN_PATH.'/admin.head.pop.php');

?>

<form name="tradeform" id="tradeform" action="./open_buyer_change_update.php" onsubmit="return tradeform_submit(this);" method="post" enctype="multipart/form-data">
<input type="hidden" name="w" value="u">
<input type="hidden" name="tr_code" value="<?php echo $tr_code ?>">
<input type="hidden" name="token" value="">
<h2 class="h2_frm">거래정보변경</h2>
<div class="tbl_frm01 tbl_wrap">
<?=help('구매자 변경시 기존 거래는 취소됩니다. 구매자는 매칭 수수료가 회수 되며 새로운 구매자에 매칭 수수료가 부과됩니다')?>
<table>
<caption>
<?php echo $g5['title']; ?>
</caption>
<colgroup>
<col class="grid_4">
<col>

</colgroup>
<tbody>
<tr>
<th scope="row">구매자변경</th>
<td><input type="text" name="mb_id" value="<?php echo get_text($row['mb_id']) ?>" id="mb_id" required class="required frm_input" size="30">
<input type="button" value="회원찾기" id="openMSearchBtn" class="btn btn_02" /></td>
</tr>
<tr>
<th scope="row" >서브계정</th>
<td ><input type="text" name="smb_id" value="<?php echo get_text($row['smb_id']) ?>" id="smb_id" maxlength="100"  class=" frm_input" size="20" />
필요시 지정</td>
</tr>
<!--tr>
        <th scope="row">이체비밀번호</th>
        <td colspan="3"><input type="password" name="mb_deposite_pass" id="mb_deposite_pass" <?php echo $required_mb_password ?> class="frm_input <?php echo $required_mb_password ?>" size="15" maxlength="20"></td>
    </tr-->
<tr>
<th scope="row" >입급계좌정보</th>
<td >은행명
<input type="text" name="tr_bank" value="<?php echo $row['tr_bank'] ?>" id="tr_bank" maxlength="100"  class=" frm_input" size="20" />
/ 계좌번호
<input type="text" name="tr_bank_num" value="<?php echo $row['tr_bank_num'] ?>" id="tr_bank_num" maxlength="100"  class=" frm_input " size="30" />
/ 예금주
<input type="text" name="tr_bank_user" value="<?php echo $row['tr_bank_user'] ?>" id="tr_bank_user" maxlength="100"  class=" frm_input " size="30" /></td>
</tr>

</tbody>
</table>
<br>
<center>
<input type="submit" name="act_button" value="정보변경" onclick="document.pressed=this.value" class="btn_01 btn">
</center>
</div>
</form>

<h2 class="h2_frm">거래정보 </h2>
<div class="tbl_head01 tbl_wrap">
<table>
<caption>&nbsp;
</caption>
<thead>
<tr>

<th rowspan="2" scope="col"><?php echo subject_sort_link('a.mb_id') ?>구매자/<?php echo subject_sort_link('a.mb_id') ?>판매자</th>
<th rowspan="2" scope="col"><?php echo subject_sort_link('a.cn_item') ?>
<?=$g5[cn_item_name]?>
</a></th>
<th colspan="7" scope="col">매도
<?=$g5['cn_item_name']?></th>
<th colspan="2" scope="col">거래정보</th>
<th rowspan="2" scope="col"><?php echo subject_sort_link('a.tr_paytype') ?>결재방법</a></th>
<!--th scope="col">입금계좌</th-->
<th rowspan="2" scope="col">입금완료<br>
최종변경</th>
<th rowspan="2" scope="col"><?php echo subject_sort_link('a.tr_distri') ?>거래구분</th>
<th rowspan="2" scope="col"><?php echo subject_sort_link('a.tr_rdate') ?>생성일</th>
<th rowspan="2" scope="col"><?php echo subject_sort_link('a.tr_stats') ?>상태</a></th>
</tr>
<tr>
<th scope="col"><?php echo subject_sort_link('c.ct_class') ?>Class</th>
<th scope="col"><?php echo subject_sort_link('c.ct_buy_price') ?>액면가</th>
<th scope="col">판매가</th>
<th scope="col"><?php echo subject_sort_link('c.ct_interest') ?>이율</th>
<th scope="col"><?php echo subject_sort_link('c.ct_buy_date') ?>구매일</th>
<th scope="col"><?php echo subject_sort_link('c.ct_validdate') ?>보유마감</th>
<th scope="col"><?php echo subject_sort_link('c.ct_days') ?>기본보유일</th>
<th scope="col"><?php echo subject_sort_link('c.ct_buy_price') ?>액면가</th>
<th scope="col">실구매가</th>
</tr>
</thead>
<tbody>

<tr class="<?php echo $bg; ?>">
<?
if(!auth_check($auth[$sub_menu], 'w',1)){?>
<? }?>
<td class='mb-info-open' data-id='<?php echo $row['mb_id'] ?>'><?php echo $row['smb_id'] ?> @<?php echo $row['mb_id'] ?><br>
<span class='fblue'><?php echo $row['mb_name'] ?></span> / <?php echo $row['mb_hp'] ?>
<!--p><?=$row[mb_trade_put_claim]?'보낸신고:'.$row[mb_trade_put_claim]:''?> <?=$row[mb_trade_get_claim]?'받은신고:'.$row[mb_trade_get_claim]:''?> <?=$row[mb_trade_penalty]?'패널티중'. $row[mb_trade_penalty_date]:''?></p--></td>
<td >
<?php echo $row['ccn_item']!=$row['cn_item'] ? $g5['cn_item'][$row['ccn_item']][name_kr]."<br>→ ":'' ?>
<?php echo $g5['cn_item'][$row['cn_item']][name_kr] ?><br></td>
<td class="td_right"><?php echo $row['ct_class']?$row['ct_class']:'-'?></td>
<td class="td_right"><?php echo $row['ct_buy_price']?number_format2($row['ct_buy_price']):'-'?></td>
<td class="td_right"><?php echo $row['ct_sell_price']?number_format2($row['ct_sell_price']):'-'?></td>
<td class="td_right"><?php echo $row['ct_interest']?></td>
<td class="td_right"><span class="td_datetime"><?php echo str_replace(" ","<br>",$row['ct_wdate'])?></span></td>
<td class="td_right"><span class="td_datetime"><?php echo $row['ct_validdate']?></span></td>
<td class="td_right"><span class="td_datetime"><?php echo $row['ct_days']?><br>
<?php echo !$past_day?'0':$past_day?>day</span></td>
<td class="td_right"><?php echo number_format2($row['tr_price_org'])?></td>
<td class="td_right"><?php echo number_format2($row['tr_price'])?></td>
<td ><?=$g5['cn_paytype'][$row['tr_paytype']]?></td>
<!--td  class="td_num_c3"><?php echo $row['account'] ?></td-->
<td class="td_datetime"><?php echo !preg_match("/^00/",$row['tr_paydate'])?$row['tr_paydate']:'-'?><br>
<?php echo !preg_match("/^00/",$row['tr_setdate'])?$row['tr_setdate']:'-'?></td>
<td class="td_num"><?php echo strtoupper($row['tr_distri'])?></td>
<td colspan="2" class='fred'><?php echo str_replace(" ","<br>", $row['tr_rdate'])?>
<?=$g5['tr_stat'][$row['tr_stats']]?></td>
</tr>
<tr class="<?php echo $bg; ?>">
<td class='mb-info-open' data-id='<?php echo $row['fmb_id'] ?>'><?php echo $row['fsmb_id'] ?> @<?php echo $row['fmb_id'] ?><br>
<span class='fblue'><?php echo $seller['mb_name'] ?></span> / <?php echo $seller['mb_hp'] ?></td>
<td colspan="15" class='td_left'  > 거래번호: <?php echo $row['tr_code'] ?> /
판매
<?=$g5[cn_item_name]?>
: <a href='./item_cart_list.php?code_stx=<?=$row[cart_code]?>' target='_blank'>
<?=$row[cart_code]?>
</a>
<?=$row[to_cart_code]?' &gt; 지급'.$g5[cn_item_name].": <a href='./item_cart_list.php?code_stx={$row[to_cart_code]}' target='_blank'>". $row[to_cart_code]."</a>":''?>
<?
echo " / 구매수수료:<span class='fblue'>".$row[tr_fee]."</span>";
echo " / 판매수수료:<span class='fblue'>".$row[tr_seller_fee]."</span>";
?>
<?
if($row[tr_buyer_memo]) echo "<br/>구매자 신고:<span class='fred'>".$row[tr_buyer_memo]."</span>";
if($row[tr_seller_memo]) echo "<br/>판매자 신고:<span class='fred'>".$row[tr_seller_memo]."</span>";
?>
<br>
<span class='fblue'><?=$row[tr_bank]?> <?=$row[tr_bank_num]?> <?=$row[tr_bank_user]?> </span>
<span class='forange'><?=$row[tr_logs]?'<br>'.$row[tr_logs]:''?></span>
</td>
</tr>

</tbody>
</table>
</div>
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

function tradeform_submit(f)
{	
	
	
	if(document.pressed == "정보변경") {
		
        if(!confirm("주의\n\n 정말 거래 정보를 변경하시겠습니까")) {
            return false;
        }
		else return true;
    }
}
	
	
</script>
<?
include_once(G5_ADMIN_PATH.'/admin.tail.pop.php');

?>
