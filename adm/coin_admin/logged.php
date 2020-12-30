<?php
$sub_menu = "800800";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');

$g5['title'] = '소각내역';

include_once(G5_ADMIN_PATH.'/admin.head.php');

$sql_search = $s_item!=""?" and a.cn_item = '{$s_item}'":"";
if ($stx) {
	$sql_search .= " and ( ";
	switch ($sfl) {
		default :
			$sql_search .= " ({$sfl} like '{$stx}%') ";
			break;
	}
	$sql_search .= " ) ";
}
$sql = "SELECT * FROM `coin_item_cart` as a
left join `coin_item_info` as b on a.cn_item = b.cn_item
where is_soled = 1 and is_trade = 0 and ct_logs like '%소각%' {$sql_search}
ORDER BY a.`ct_wdate` DESC";
$result = sql_query($sql,1);
$total_count = sql_num_rows($result);
$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함


$sql = "{$sql} limit {$from_record}, {$rows} ";
$result = sql_query($sql);
?>
<form id="fsearch" name="fsearch" class="local_sch01 local_sch" method="get">

	<span class="nowrap">
	<select name="s_item" >
		<option value="">전체</option>
		<?
		$item_query = "select cn_item,name_kr from `coin_item_info`";
		$item_result = sql_query($item_query,1);
		for ($i=0; $item_row=sql_fetch_array($item_result); $i++) {
			$selected = $s_item==$item_row[cn_item]?"selected=true":"";
			echo "<option value='{$item_row[cn_item]}' {$selected}>{$item_row[name_kr]}</option>";
		}
		?>
	</select>
	</span>

	<span class="nowrap">

	<select name="sfl" id="sfl">
		<option value="mb_id"<?php echo get_selected($_GET['sfl'], "mb_id"); ?>>아이디</option>
	</select>
	<label for="stx" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
	<input type="text" name="stx" value="<?php echo $stx ?>" id="stx" class="frm_input">
	<input type="submit" class="btn_submit" value="검색">
	</span>
</form>
<div class="tbl_head01 tbl_wrap">
    <table>
    <caption><?php echo $g5['title']; ?> 목록</caption>
    <thead>
    <tr>
        <th width=10% scope="col">회원아이디</th>
        <th width=10% scope="col">아이템</th>
        <th width=10% scope="col">아이템 가격</th>
        <th width=70% scope="col">기록</th>
        </tr>
    </thead>
    <tbody>
    <?php
    for ($i=0; $row=sql_fetch_array($result); $i++) {
        $bg = 'bg'.($i%2);
	?>
	<tr class="<?php echo $bg; ?>">
		<td class="td_num"><?=$row['mb_id']?></td>
		<td class="td_num"><?=$row['name_kr']?></td>
		<td class="td_num"><?=number_format($row['ct_sell_price'])?>원</td>
		<td class="td_num"><?=$row['ct_logs']?></td>
	</tr>
	<?php
	}
	?>
    </tbody>
    </table>
<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, '?'.$qstr.'&amp;page='); ?>

</div>

<script>
</script>

<?php
include_once(G5_ADMIN_PATH.'/admin.tail.php');
?>
