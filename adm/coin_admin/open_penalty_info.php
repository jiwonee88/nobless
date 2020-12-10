<?php
$sub_menu = "700600";
include_once('./_common.php');

//지사가 접근시
if($is_branch){
	if($target=='buyer') $jisa_sql= " and a.fmb_id in (select smb_id from $g5[cn_tree] where mb_id='$member[mb_id]' )" ;
	else if($target=='seller') $jisa_sql= " and a.mb_id in (select smb_id from $g5[cn_tree] where mb_id='$member[mb_id]' )" ;
}

$row=sql_fetch("select a.*,
c.ct_class, c.ct_wdate,c.ct_validdate,c.ct_buy_price,c.ct_sell_price,c.ct_class,c.ct_interest,c.ct_days,c.cn_item ccn_item,
b.mb_id,b.mb_email,b.mb_hp,b.mb_name,b.mb_trade_get_claim,b.mb_trade_put_claim,b.mb_trade_penalty,b.mb_trade_penalty_date 
from {$g5['cn_item_trade']}  as a 
left outer join  {$g5['cn_item_cart']} as c on(a.cart_code=c.code) 
left outer join  {$g5['member_table']} as b on(a.mb_id=b.mb_id) 

where a.tr_code='$tr_code' $jisa_sql ",1);
$seller=get_member($row[fmb_id]);
$buyer=get_member($row[mb_id]);

if($target=='buyer') $mb=$buyer;
else if($target=='seller') $mb=$seller;

if($row[tr_code]=='' )alert_close('거래정보를 찾을수 없습니다');
if($mb[mb_id]=='' )alert_close('패널티를 부여할 회원을 찾을수 없습니다');

//보유 금액
$isum=get_itemsum($mb[mb_id]);

$g5['title'] = $mb[mb_id]." 회원 패널티 부여 ";


include_once(G5_ADMIN_PATH.'/admin.head.pop.php');

?>
<h2 class="h2_frm"><?=$target=='seller' ? '판매자':''?>
<?=$target=='buyer' ? '구매자':''?>  정보</h2>

<h3>설정금액 : <?=number_format2($mb[mb_trade_amtlmt])?> / 보유금액 : <?=number_format2($isum[tot][price])?> / 가용금액 : <?=number_format2($isum[tot][price]>$mb[mb_trade_amtlmt]?0:$mb[mb_trade_amtlmt]-$isum[tot][price])?></h3>


<div class="tbl_head01 tbl_wrap">
<table>
<thead>
<tr>
<th id="mb_list_id" scope="col">아이디</a></th>
<th id="mb_list_id" scope="col">이름</a></th>
<th scope="col" >이메일</a></th>
<th scope="col" id="mb_list_cert">권한</th>
<th id="mb_list_id" scope="col">지사/지점</th>
<th id="mb_list_id" scope="col">하위지사/지점</th>
<?
		//계정별
		foreach($g5['cn_cointype'] as $k=> $v){ ?>
<th id="mb_list_id" scope="col">총
<?=$v?>
수량</th>
<? }?>
<th scope="col" id="mb_list_cert">연락처</th>
<th class="w-auto" id="mb_list_cert" scope="col">패널티횟수</a></th>
<th class="w-auto" id="mb_list_cert" scope="col">최근패널티 일시</th>
<th id="mb_list_lastcall" scope="col">최종접속</a></th>
<th scope="col" >가입일</th>
<th scope="col" >pass</th>
</tr>
</thead>
<tbody>
<?php

$leave_date = $mb['mb_leave_date'] ? $mb['mb_leave_date'] : date('Ymd', G5_SERVER_TIME);
$intercept_date = $mb['mb_intercept_date'] ? $mb['mb_intercept_date'] : date('Ymd', G5_SERVER_TIME);

$mb_nick = get_sideview($mb['mb_id'], get_text($mb['mb_nick']), $mb['mb_email'], $mb['mb_homepage']);

$mb_id = $mb['mb_id'];
$leave_msg = '';
$intercept_msg = '';
$intercept_title = '';
if ($mb['mb_leave_date']) {
	$mb_id = $mb_id;
	$leave_msg = '<span class="mb_leave_msg">탈퇴함</span>';
}
else if ($mb['mb_intercept_date']) {
	$mb_id = $mb_id;
	$intercept_msg = '<span class="mb_intercept_msg">차단됨</span>';
	$intercept_title = '차단해제';
}
if ($intercept_title == '')
	$intercept_title = '차단하기';

$address = $mb['mb_zip1'] ? print_address($mb['mb_addr1'], $mb['mb_addr2'], $mb['mb_addr3'], $mb['mb_addr_jibeon']) : '';

$bg = 'bg'.($i%2);

$count=sql_fetch("select count(*) cnt  from {$g5['cn_tree']} where mb_id='{$mb[mb_id]}'");			

//서브 계정
$temp=sql_fetch("select count(*) cnt  from  {$g5['cn_sub_account']} where mb_id='{$mb[mb_id]}' and ac_id != '{$mb[mb_id]}'");
 ?>
<tr class="<?php echo $bg; ?>">
<td  ><?php echo $mb_id ?></td>
<td  ><?php echo $mb[mb_name] ?></td>
<td  >
<?=$mb['mb_email']?>
</td>
<td><strong><?php echo $g5['member_level_name'][$mb['mb_level']] ?></strong></td>
<td  ><?php echo $mb['mb_recommend']?$mb['mb_recommend']:'-'?></td>
<td  >
<?php echo $count['cnt']?>
</td>
<?
    //계정별
    foreach($g5['cn_cointype'] as $k=> $v){
	?>
<td  ><?php echo number_format2($mb['mb_point_free_'.$k],8)?></td>
<? }?>
<td><?=$mb['mb_hp']?></td>
<td class='td_date'><?php echo $row[mb_trade_penalty] ?></td>
<td class='td_datetime2'><?php echo $row[mb_trade_penalty_date] ?></td>
<td class="td_datetime fred"><?php echo substr($mb['mb_today_login'],2,14); ?></td>
<td class="td_date"><?php echo substr($mb['mb_datetime'],2,8); ?></td>
<td class="td_date"><?= $is_admin=='super' || $member[mb_level] > $mb[mb_level] ? $mb[mb_15]:'?'?></td>
</tr>

</tbody>
</table>
</div>

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
<th colspan="3" scope="col">신고정보</th>
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
<th scope="col">구매자</th>
<th scope="col">판매자</th>
<th scope="col">패널티</th>
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
<td class='fred'><?php echo $row['tr_buyer_claim']? str_replace(" ","<br>",$row['tr_buyer_note']):'-'?></td>
<td class='fred'><?php echo $row['tr_seller_claim']? str_replace(" ","<br>",$row['tr_seller_note']):'-'?></td>
<td class='fred'><?php echo $row['tr_penalty']? str_replace(" ","<br>",$row['tr_penalty_date']):'-'?></td>
<td class="td_date2"><?php echo str_replace(" ","<br>", $row['tr_rdate'])?></td>
<td class="td_num_c3">
<?=$g5['tr_stat'][$row['tr_stats']]?>
</td>
</tr>
<tr class="<?php echo $bg; ?>">
<td class='mb-info-open' data-id='<?php echo $row['fmb_id'] ?>'><?php echo $row['fsmb_id'] ?> @<?php echo $row['fmb_id'] ?><br>
<span class='fblue'><?php echo $seller['mb_name'] ?></span> / <?php echo $seller['mb_hp'] ?></td>
<td colspan="18" class='td_left'  > 거래번호: <?php echo $row['tr_code'] ?> /
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
<span class='fblue'>
<?=$row[tr_bank]?>
<?=$row[tr_bank_num]?>
<?=$row[tr_bank_user]?>
</span></td>
</tr>

</tbody>
</table>
</div>


<form name="fboardlist" id="fboardlist" action="./item_trade_penalty_update.php" onsubmit="return fboardlist_submit(this);" method="post">
<input type="hidden" name="target" value="<?php echo $target ?>">
<input type="hidden" name="tr_code" value="<?php echo $tr_code ?>">

<input type="hidden" name="token" value="<?php echo $token ?>">


<div style='margin:10px 0; border:3px solid #ffdddd;padding:10px;'>
<strong class='fred'>
<?=$target=='seller' ? '판매자':''?>
<?=$target=='buyer' ? '구매자':''?> <?=$mb[mb_name]?></strong>에게 

<select name='stats_pt' class="form-control input-sm  w-auto  mb-1" id="stats_pt" >
<option value='cancel'  >거래취소실행</option>
<option value='retain'  >상태유지</option>
</select>

( 피해자 보상
<select name='give_coin' class="form-control input-sm  w-auto  mb-1" id="give_coin" >
<?
foreach($g5['cn_cointype'] as $k=>$v){?>
<option value='<?=$k?>'  >
<?=$v?>
</option>
<? }?>
</select>
<input name="give_coin_amt" type="text" class="frm_input" id="give_coin_amt" value="0" size="6">
부여 / 가해자 벌금
<select name='get_coin' class="form-control input-sm  w-auto  mb-1" id="get_coin" >
<?
foreach($g5['cn_cointype'] as $k=>$v){?>
<option value='<?=$k?>'  >
<?=$v?>
</option>
<? }?>
</select>
<input name="get_coin_amt" type="text" class="frm_input" id="get_coin_amt" value="0" size="6">
차감) </span>
<input type="submit" name="act_button" value="패널티실행" onclick="document.pressed=this.value" class="btn_01 btn">
</div>
<script>  
function fboardlist_submit(f)
{	
	
	
	if(document.pressed == "패널티실행") {
		var s=$("select[name=select_pt] option:selected").text();
		var sv=$("select[name=select_pt]").val();
		
		if(sv=='sel'){
			 if (!is_checked("chk[]")) {
				alert(document.pressed+" 하실 항목을 하나 이상 선택하세요.");
				return false;
			}
		}
        if(!confirm("주의\n\n"+s+"에 대해서 선택한 조건으로 정말 패널티를 실행합니까?")) {
            return false;
        }
		else return true;
    }
    
	
	
</script>
<?
include_once(G5_ADMIN_PATH.'/admin.tail.pop.php');

?>
