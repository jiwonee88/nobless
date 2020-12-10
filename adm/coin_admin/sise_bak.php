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
        <th scope="col">구매갯수</th>
        <th scope="col">판매갯수</th>
        </tr>
    </thead>
    <tbody>
    <?php
	$mbSql = "select * from {$g5['member_table']}";
	$mbQuery = sql_query($mbSql);
	$totalUpdated = 0;
	while($row = sql_fetch_array($mbQuery)){
		$buyPriceQuery = "select count(*) as cnt from coin_sub_account where mb_id='$row[mb_id]' and ac_auto_a = '1' order by ac_id asc";
		$sellPriceQuery ="select count(*) as cnt from coin_item_cart  where mb_id='$row[mb_id]' and (cn_item='a' or cn_item='b') and is_soled!='1' and date(ct_validdate)=date(date_add(now(), interval +1 day))";//
		$buyRow = sql_fetch($buyPriceQuery);
		$sellRow = sql_fetch($sellPriceQuery);
		$cntBuy = $buyRow['cnt'];
		$cntSell = $sellRow['cnt'];
		if($cntBuy<$cntSell&&$cntSell!=0){
			$bg = 'bg'.($totalUpdated%2);
			?>
			<tr class="<?php echo $bg; ?>">
				<td class="td_num"><input type="checkbox" class="checkbox" name="selected_user[]" value="<?=$row['mb_id']?>"></td>
				<td class="td_num"><?=$row['mb_id']?></td>
				<td class="td_num"><?=$cntBuy?></td>
				<td class="td_num"><?=$cntSell?></td>
			</tr>
			<?
			$totalUpdated++;
		}
	}
	if($totalUpdated==0){
		echo "<tr><td colspan='4'><center>적용대상이 없습니다.</center></td></tr>";
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
