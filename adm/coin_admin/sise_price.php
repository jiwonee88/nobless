<?php
$sub_menu = "800600";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');

$g5['title'] = '금액조정';

include_once(G5_ADMIN_PATH.'/admin.head.php');


$colspan = 16;
?>
<form action="./sise_price_update.php" method="POST">
<div class="tbl_head01 tbl_wrap">
    <table>
    <caption><?php echo $g5['title']; ?> 목록</caption>
	<colgroup>
		<col width=5%>
		<col>
		<col>
		<col>
	</colgroup>
    <thead>
    <tr>
        <th scope="col"><input type="checkbox" onclick="if($(this).is(':checked')==true){$('.checkbox').prop('checked',true);}else{$('.checkbox').prop('checked',false);}"></th>
        <th scope="col">계정명</th>
        <th scope="col">전일구매예약수</th>
        <th scope="col">금일구매예약수</th>
        <th scope="col">금일판매아이템수</th>
        </tr>
    </thead>
    <tbody>
    <?php
	$mbSql = "select * from {$g5['member_table']}";
	$mbQuery = sql_query($mbSql);
	$totalUpdated = 0;
	while($row = sql_fetch_array($mbQuery)){
		$buyPriceQuery = "select count(*) as cnt from coin_sub_account where mb_id='$row[mb_id]' and (ac_auto_a = '1' or ac_auto_b = '1') order by ac_id asc";
		$buyPriceYSQuery = "select count(*) as cnt from coin_item_matching_log where mb_id = '{$row[mb_id]}' and date(log_wdate) = date(DATE_ADD(now(), INTERVAL -1 DAY))";
		$buyRow = sql_fetch($buyPriceQuery);
		$buyYSRow = sql_fetch($buyPriceYSQuery);
		$cntBuy = $buyRow['cnt'];
		$cntBuyYS = $buyYSRow['cnt'];
		//if($cntBuy<(ceil($cntBuyYS*0.8))){//오늘보다 어제판매량이 더 많을경우 보여주기
		//if($cntBuyYS>0){
			
			$sellCountQuery ="select count(*) as cnt from coin_item_cart  where mb_id='$row[mb_id]' and (cn_item='a' or cn_item='b') and is_soled!='1'  and		date(ct_validdate)=date(date_add(now(), interval 0 day))";// and date(ct_validdate)=date(date_add(now(), interval +1 day))
			$sellCount = sql_fetch($sellCountQuery);
			$cntSell = $sellCount['cnt'];
			if($cntSell>0){

			$bg = 'bg'.($totalUpdated%2);
			?>
			<tr class="<?php echo $bg; ?>">
				<td class="td_num"><input type="checkbox" class="checkbox" name="selected_user[]" value="<?=$row['mb_id']?>"></td>
				<td class="td_num"><?=$row['mb_id']?></td>
				<td class="td_num"><?=$cntBuyYS?></td>
				<td class="td_num"><?=$cntBuy?></td>
				<td class="td_num"><?=$cntSell?></td>
			</tr>
			<?
			$totalUpdated++;
		}
	}
	if($totalUpdated==0){
		echo "<tr><td colspan='5'><center>적용대상이 없습니다.</center></td></tr>";
	}
	?>
    </tbody>
    </table>
</div>
<div class="btn_fixed_top">
	<?if($totalUpdated>0){?><button type="submit" class="btn_04 btn">판매금액조정</button><?}?>
</div>
</form>
<script>
</script>

<?php
include_once(G5_ADMIN_PATH.'/admin.tail.php');
?>
