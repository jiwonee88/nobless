<?php
$sub_menu = "500400";
include_once('./_common.php');

$g5['title'] = $g5[cn_item_name].'상품정보수정';

include_once(G5_ADMIN_PATH.'/admin.head.php');



$minfo=array();
$re=sql_query("select * from {$g5['cn_item_info']} limit 1",1);
while($data=sql_fetch_array($re)){
	$minfo[$data['cn_item']]=$data;
}
	

?>

<div class="local_ov01 local_ov">
    <?php echo $listall ?>
    <span class="btn_ov01"><span class="ov_txt">등록된 상품</span><span class="ov_num"><?php echo count($g5[cn_item]) ?>개</span></span>
</div>
<form name="fitemlist" id="fitemlist" action="./item_info_update.php" onsubmit="return fboardlist_submit(this);" method="post">
<input type="hidden" name="w" value="u">
<input type="hidden" name="token" value="<?php echo $token ?>">

<div class="tbl_head01 tbl_wrap">
<?//print_r($g5['cn_item']);?>
    <table>
    <caption><?php echo $g5['title']; ?> 목록</caption>
    <thead>
    <tr>
        <th scope="col">상품코드</th>
<th scope="col">상품명</th>
<th scope="col">보유일</th>
<th scope="col">이율</th>
<th scope="col">가격</th>
<th scope="col">최대가격</th>
<th scope="col">수수료</th>
        </tr>
    </thead>
    <tbody>
    <?php
	$i=0;
    foreach ($g5['cn_item'] as $k=> $data) {
        $bg = 'bg'.($i%2);
		
		if(array_keys($k,$minfo)) $data=$minfo[$k];
    ?>
	
    <tr class="<?php echo $bg; ?>">

       <td ><?php echo $k ?></td>
<td><input type="text" name="name_kr[<?=$k?>]" value="<?php echo $data['name_kr'] ?>"  class="frm_input" size="20" ></td>
<td><input type="text" name="days[<?=$k?>]" value="<?php echo $data['days'] ?>"  class="frm_input" size="20"></td>
<td><input type="text" name="interest[<?=$k?>]" value="<?php echo $data['interest'] ?>"  class="frm_input  w-auto" size="20">
%</td>
<td><input type="text" name="price[<?=$k?>]" value="<?php echo $data['price'] ?>"  class="frm_input" size="20"></td>
<td><input type="text" name="mxprice[<?=$k?>]" value="<?php echo $data['mxprice'] ?>"  class="frm_input" size="20"></td>
<td><input type="text" name="fee[<?=$k?>]" value="<?php echo $data['fee'] ?>" class="frm_input w-auto" size="20"><?=$g5[cn_cointype][$g5[cn_fee_coin]]?></td>
    </tr>
    <?php
	$i++;
    }
    if ($i == 0)
        echo '<tr><td colspan="7" class="empty_table">설정된 상품이 없습니다.</td></tr>';
    ?>
    </tbody>
    </table>
</div>

<div class="btn_fixed_top">
<input type="submit" value="일괄수정" class="btn_submi btn btn_01" accesskey="s">
</div>
</form>

<script>
function fitemlist_submit(f)
{
	if(confirm("일괄 변경 하시겠습니까?")) {
		return true;
	}else return false;
    
}

</script>

<?php
include_once(G5_ADMIN_PATH.'/admin.tail.php');

?>
