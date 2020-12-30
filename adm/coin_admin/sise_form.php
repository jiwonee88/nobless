<?php
$sub_menu = "500300";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'w');

$data= get_coinset();
$g5['title'] = "코인시셋";

$data=sql_fetch("select * from {$g5['cn_sise_table']} ");

$datas=json_decode($data[data],true);

include_once ('../admin.head.php');
	 
?>
<form name="set_form" id="set_form" action="./sise_form_update.php" onsubmit="return item_form_submit(this)" method="post" enctype="multipart/form-data">
<input type="hidden" name="w" value="u">
<input type="hidden" name="token" value="">

<?=$common_form?>

<section id="anc_1">
    <h2 class="h2_frm">현재시세</h2>
    <?php// echo $pg_anchor ?>

    <div class="tbl_frm01 tbl_wrap">
        <table class='w-auto' style='min-width:800px;'>

          
        <caption>기본정보</caption>
        <colgroup>
            <col class="grid_2">
			<col class="grid_2">
            <col>
        </colgroup>
        <tbody>

<tr>
<th scope="row"  class="grid_2">시세연동</th>
<td>
<label><input name='is_flow' type='radio' value="1" <?=$data[is_flow]=='1'?'checked':''?> > 실시간 연동</label>&nbsp;&nbsp;&nbsp;
<label><input name='is_flow' type='radio' value="2" <?=$data[is_flow]=='2'?'checked':''?> > 시세 고정</label>
</td>
</tr>

<?
$row_prnt=0;
foreach($g5['cn_cointype'] as $k=>$v){?>       		
<tr>
<th width="100" scope="row"  class="grid_2"><?=$v?></th>
<td width="100"><input type="text" name="sise_<?=$k?>" value="<?php echo number_format2($datas['sise_'.$k]) ?>" id="sise_<?=$k?>"  class=" frm_input number-comma" required size="20"  /> USD</td>
</tr>
<? }?>
         
        </tbody>
        </table>
    </div>
</section>



<div class="btn_fixed_top">
    <input type="submit" value="확인" class="btn_submit btn btn_01" accesskey="s">
</div>

</form>
<script>  

function item_form_submit(f)
{

    return true;
}
</script>
<?php
include_once ('../admin.tail.php');
?>
