<?php
$sub_menu = "800700";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');

$g5['title'] = '소각금액순위';

include_once(G5_ADMIN_PATH.'/admin.head.php');

$sql = " SELECT mb_id,pt_coin,sum(amount) as tot_sum FROM coin_pointsum where pkind = 'burnin' and pt_coin = 'i' GROUP by mb_id order by amount desc ";
$result = sql_query($sql,1);

$colspan = 16;
?>
<div class="tbl_head01 tbl_wrap">
    <table>
    <caption><?php echo $g5['title']; ?> 목록</caption>
    <thead>
    <tr>
        <th width=10% scope="col">순위</th>
        <th width=40% scope="col">계정명</th>
        <th width=40% scope="col">소각금액</th>
        </tr>
    </thead>
    <tbody>
    <?php
    for ($i=0; $row=sql_fetch_array($result); $i++) {
        $bg = 'bg'.($i%2);
	?>
	<tr class="<?php echo $bg; ?>">
		<td class="td_num"><?=($i+1)?></td>
		<td class="td_num"><?=$row['mb_id']?></td>
		<td class="td_num"><?=number_format($row['tot_sum'])?>원</td>
	</tr>
	<?php
	}
	?>
    </tbody>
    </table>
</div>

<script>
</script>

<?php
include_once(G5_ADMIN_PATH.'/admin.tail.php');
?>
