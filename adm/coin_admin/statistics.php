<?php
$sub_menu = "800500";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');

$g5['title'] = '계정구간별통계';

include_once(G5_ADMIN_PATH.'/admin.head.php');

$sql = " select accnt,count(accnt) as cnt_accnt from (select count(ac_no) accnt  from coin_sub_account where ac_auto_a = 1 group by mb_id) as total  group by accnt";
$result = sql_query($sql,1);

$colspan = 16;
?>
<div class="tbl_head01 tbl_wrap">
    <table>
    <caption><?php echo $g5['title']; ?> 목록</caption>
    <thead>
    <tr>
        <th width=33% scope="col">구매예약 계정수</a></th>
        <th width=33% scope="col">회원수</th>
        <th width=33% scope="col">회원리스트</th>
        </tr>
    </thead>
    <tbody>
    <?php
    for ($i=0; $row=sql_fetch_array($result); $i++) {
        $bg = 'bg'.($i%2);
		$subquery = "select count(ac_no) accnt,mb_id from coin_sub_account where ac_auto_a = 1 group by mb_id having count(ac_no) = {$row['accnt']}";
		$subresult = sql_query($subquery,1);
	?>
	<tr class="<?php echo $bg; ?>">
		<td class="td_num"><?=$row['accnt']?></td>
		<td class="td_num"><?=$row['cnt_accnt']?>명</td>
		<td class="td_num">
		<?php
		for ($ii=0; $subrow=sql_fetch_array($subresult); $ii++) {
			echo "<p>{$subrow['mb_id']}</p>";
		}
		?>	
		</td>
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
