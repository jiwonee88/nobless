<?
$sql_common = " from {$g5['member_table']} as a 

left outer join (select mb_id bmb_id,

sum(ac_point_b) ac_point_b,sum(ac_point_e) ac_point_e,sum(ac_point_i) ac_point_i,sum(ac_point_u) ac_point_u,
count(*) sc_cnt,
sum(if(ac_active='1',1,0)) active_cnt,
sum(if(ac_active='1' && ac_auto_a='1',1,0)) a_auto_cnt,
sum(if(ac_active='1' && ac_auto_b='1',1,0)) b_auto_cnt,
sum(if(ac_active='1' && ac_auto_c='1',1,0)) c_auto_cnt,
sum(if(ac_active='1' && ac_auto_d='1',1,0)) d_auto_cnt,
sum(if(ac_active='1' && ac_auto_e='1',1,0)) e_auto_cnt,
sum(if(ac_active='1' && ac_auto_f='1',1,0)) f_auto_cnt,
sum(if(ac_active='1' && ac_auto_g='1',1,0)) g_auto_cnt,
sum(if(ac_active='1' && ac_auto_h='1',1,0)) h_auto_cnt,
sum(if(ac_active='1' && ac_auto_a='1',1,0) + if(ac_active='1' && ac_auto_b='1',1,0) + if(ac_active='1' && ac_auto_c='1',1,0) + if(ac_active='1' && ac_auto_d='1',1,0) + if(ac_active='1' && ac_auto_e='1',1,0) + if(ac_active='1' && ac_auto_f='1',1,0) + if(ac_active='1' && ac_auto_g='1',1,0) + if(ac_active='1' && ac_auto_h='1',1,0)  ) all_auto_cnt

from {$g5['cn_sub_account']} group by mb_id) as b on(b.bmb_id=a.mb_id)

left outer join  (select mb_id cmb_id,count(*) ct_cnt,sum(if(cn_item='e',ct_buy_price-soled_amt,ct_buy_price)) ct_buy_price  from {$g5['cn_item_cart']} where  is_soled != '1' group by mb_id)  as c on(c.cmb_id=a.mb_id) 

left outer join  (select mb_id nmb_id,count(*) ntr_cnt,sum(tr_price_org) tr_price_org  from {$g5['cn_item_trade']} where  tr_stats in ('1','2')  group by mb_id)  as n on(n.nmb_id=a.mb_id) 	

left outer join  (select mb_id tmb_id, count(*) tr_cnt,
sum(if(cn_item='a',1,0)) a_cnt,
sum(if(cn_item='b',1,0)) b_cnt,
sum(if(cn_item='c',1,0)) c_cnt,
sum(if(cn_item='d',1,0)) d_cnt,
sum(if(cn_item='e',1,0)) e_cnt,
sum(if(cn_item='f',1,0)) f_cnt,
sum(if(cn_item='g',1,0)) g_cnt,
sum(if(cn_item='h',1,0)) h_cnt,

sum(if(cn_item='a',tr_price,0)) a_price,
sum(if(cn_item='b',tr_price,0)) b_price,
sum(if(cn_item='c',tr_price,0)) c_price,
sum(if(cn_item='d',tr_price,0)) d_price,
sum(if(cn_item='e',tr_price,0)) e_price,
sum(if(cn_item='f',tr_price,0)) f_price,
sum(if(cn_item='g',tr_price,0)) g_price,
sum(if(cn_item='h',tr_price,0)) h_price,

sum(tr_price) tr_price

from $matching_table where  tr_wdate='$date_stx'  group by mb_id)  as t on(t.tmb_id=a.mb_id)

left outer join  (select fmb_id,count(*) ftr_cnt,
sum(if(cn_item='a',1,0)) fa_cnt,
sum(if(cn_item='b',1,0)) fb_cnt,
sum(if(cn_item='c',1,0)) fc_cnt,
sum(if(cn_item='d',1,0)) fd_cnt,
sum(if(cn_item='e',1,0)) fe_cnt,
sum(if(cn_item='f',1,0)) ff_cnt,
sum(if(cn_item='g',1,0)) fg_cnt,
sum(if(cn_item='h',1,0)) fh_cnt,

sum(if(cn_item='a',tr_price,0)) fa_price,
sum(if(cn_item='b',tr_price,0)) fb_price,
sum(if(cn_item='c',tr_price,0)) fc_price,
sum(if(cn_item='d',tr_price,0)) fd_price,
sum(if(cn_item='e',tr_price,0)) fe_price,
sum(if(cn_item='f',tr_price,0)) ff_price,
sum(if(cn_item='g',tr_price,0)) fg_price,
sum(if(cn_item='h',tr_price,0)) fh_price,

sum(tr_price) ftr_price

from $matching_table where  tr_wdate='$date_stx'  group by fmb_id)  as f on(f.fmb_id=a.mb_id)

";

$sql_search = " where (1) ";


if ($stx) {
    $sql_search .= " and ( ";
    switch ($sfl) {
        case 'mb_point' :
            $sql_search .= " ({$sfl} >= '{$stx}') ";
            break;
        case 'mb_level' :
            $sql_search .= " ({$sfl} = '{$stx}') ";
            break;
        case 'mb_tel' :
        case 'mb_hp' :
            $sql_search .= " ({$sfl} like '%{$stx}') ";
            break;
        default :
            $sql_search .= " ({$sfl} like '{$stx}%') ";
            break;
    }
    $sql_search .= " ) ";
}

$qstr.="&date_stx=$date_stx";

if($sst=='item_cnt'){
	$sql_order=" order by (select count(*) cnt  from  {$g5['cn_item_cart']} where smb_id=a.ac_id and is_soled!='1' ) $sod";


}else if($sst=='open_r'){

	$sql_order=" order by b.active_cnt/if(b.sc_cnt=0,1,b.sc_cnt) $sod ";
}else if($sst=='m_ratio'){

	$sql_order=" order by ( t.tr_cnt / ( if(all_auto_cnt > 0,all_auto_cnt,1) ) ) $sod ";
}else{
	if (!$sst) {
		$sst = "t.tr_cnt";
		$sod = "desc";
	}	
	$sql_order = " order by {$sst} {$sod} ";
}


$sql = " select count(a.mb_id) as cnt {$sql_common} {$sql_search} {$sql_order} ";
$row = sql_fetch($sql,1);
$total_count = $row['cnt'];

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$listall = '<a href="'.$_SERVER['SCRIPT_NAME'].'" class="ov_listall">전체목록</a>';

include_once('../admin.head.php');
include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');

$sql = " select *,
( tr_cnt / ( if(all_auto_cnt > 0,all_auto_cnt,1)  )) m_ratio,
active_cnt/if(sc_cnt=0,1,sc_cnt) open_r
{$sql_common} {$sql_search}  {$sql_order} limit {$from_record}, {$rows}";
$result = sql_query($sql,1);

$colspan = 174+ sizeof($g5['cn_item'])*3+sizeof($g5['cn_cointype']);



$tot=sql_fetch("select 
count(mb_id) mb_cnt ,
sum(all_auto_cnt) all_auto_cnt,
sum(active_cnt) active_cnt,
sum(sc_cnt) sc_cnt,
sum(tr_cnt) tr_cnt, sum(tr_price) tr_price,
sum(ftr_cnt) ftr_cnt,sum(ftr_price) ftr_price,
sum(ac_point_b) ac_point_b,sum(ac_point_e) ac_point_e,sum(ac_point_i) ac_point_i,sum(ac_point_u) ac_point_u

from {$g5['member_table']} as a 

left outer join (select mb_id bmb_id, sum(ac_point_b) ac_point_b,sum(ac_point_e) ac_point_e,sum(ac_point_i) ac_point_i,sum(ac_point_u) ac_point_u, count(*) sc_cnt, sum(if(ac_active='1',1,0)) active_cnt,
sum(if(ac_active='1' && ac_auto_a='1',1,0) + if(ac_active='1' && ac_auto_b='1',1,0) + if(ac_active='1' && ac_auto_c='1',1,0) + if(ac_active='1' && ac_auto_d='1',1,0) + if(ac_active='1' && ac_auto_e='1',1,0) + if(ac_active='1' && ac_auto_f='1',1,0) + if(ac_active='1' && ac_auto_g='1',1,0) + if(ac_active='1' && ac_auto_h='1',1,0)  ) all_auto_cnt
from {$g5['cn_sub_account']} group by mb_id) as b on(b.bmb_id=a.mb_id)

left outer join  (select mb_id tmb_id, count(*) tr_cnt, sum(ct_sell_price) tr_price from $matching_table where  tr_wdate='$date_stx'  group by mb_id)  as t on(t.tmb_id=a.mb_id)
left outer join  (select mb_id fmb_id, count(*) ftr_cnt, sum(ct_buy_price) ftr_price from $matching_table where  tr_wdate='$date_stx'  group by mb_id)  as f on(f.fmb_id=a.mb_id)",1);

$tot2=array();
$tot2_cnt=$tot2_amt=0;
$re=sql_query("select cn_item, count(tr_code) cnt, sum(ct_sell_price) ct_sell_price from  $matching_table where  tr_wdate='$date_stx' group by cn_item",1);
while($data=sql_fetch_array($re)){	
	$tot2[$data['cn_item']]=$data;
	$tot2_cnt+=$data[cnt];
	$tot2_amt+=$data[ct_sell_price];
}

?>
<div class="local_ov01 local_ov">
    <?php echo $listall ?>
    <span class="btn_ov01"><span class="ov_txt">총계정수 </span><span class="ov_num"> <?php echo number_format($total_count) ?>명 </span></span>
</div>

<form id="fsearch" name="fsearch" class="local_sch01 local_sch" method="get">

매칭일
<input type="text" name="date_stx" value="<?php echo $date_stx?>" id="date_stx"  class="frm_input calendar-input" size="12" placeholder="시작일" />
<span class="nowrap">

<select name="sfl" id="sfl">
    
    <option value="a.mb_id" <?php echo get_selected($_GET['sfl'], "a.mb_id"); ?>>아이디</option>
<option value="b.mb_tel" <?php echo get_selected($_GET['sfl'], "b.mb_tel"); ?>>전화번호</option>
    <option value="b.mb_hp"<?php echo get_selected($_GET['sfl'], "b.mb_hp"); ?>>휴대폰번호</option>
	<option value="b.mb_recommend"<?php echo get_selected($_GET['sfl'], "b.mb_recommend"); ?>>추천인 Rerferral 코드 </option>
</select>

<label for="stx" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
<input type="text" name="stx" value="<?php echo $stx ?>" id="stx" class="frm_input">
<input type="submit" class="btn_submit" value="검색">
</span>
</form>


<div class="tbl_head01 tbl_wrap">
<table class='w-auto' style='font-size:1.2em;'>
    <thead>
    <tr>

<th rowspan="2"  scope="col" >총계정</a></th>
<th rowspan="2" scope="col" >활성계정</a></th>
        <th rowspan="2"  scope="col" >Open%</a></th>
        
   
		<th rowspan="2"  scope="col" >총매칭</th>
		
		<?
		//상품별
		foreach($g5['cn_item'] as $k=> $v){ 
		
		?>
        <th colspan="2"  scope="col"><?=$v[name_kr]?>&nbsp;</th>

        <? }?>
	<th rowspan="2" scope="col" >총수량</th>
<th rowspan="2" scope="col" >총금액</th>
<th rowspan="2" scope="col" >총auto</th>
	<th rowspan="2" scope="col" >매칭률</th>
		
        <?
		//계정별
		foreach($g5['cn_cointype'] as $k=> $v){ ?>
        <th rowspan="2" id="mb_list_id" scope="col">총<?=$v?>수량</th>
        <? }?>
	</tr>
<tr>
<?
		//상품별
		foreach($g5['cn_item'] as $k=> $v){ 
		
		?>
<th  scope="col">수량</th>
<th  scope="col">판매액</th>
<? }?>
</tr>
<tr>
 
</tr>
    </thead>
    <tbody>
   
    <tr class="<?php echo $bg; ?>">
<td class='td_center'><?=number_format($tot['sc_cnt'])?></td>
<td class='td_center'><?=number_format($tot['active_cnt'])?></td>
		
      <td class='td_center'><?=round($tot['active_cnt']/$tot['sc_cnt']*100,1)?>%</td>
      
   
<td ><?php echo number_format($tot[ftr_cnt]); ?></td>	

<?
//상품별
foreach($g5['cn_item'] as $k=> $v){ 
?>
<td><?=$tot2[$k][cnt]?number_format($tot2[$k][cnt]):'-'?></td>
<td><?=$tot2[$k][ct_sell_price]?number_format($tot2[$k][ct_sell_price]):'-'?></td>

<? }?>		

<td ><strong>
<?=$tot2_cnt?number_format($tot2_cnt):'-'?>
</strong></td>
<td ><strong>
<?=$tot2_amt?number_format($tot2_amt):'-'?>
</strong></td>
<td ><?php echo number_format($tot[all_auto_cnt]); ?></td>
<td ><?php echo round($tot[tr_cnt]/$tot[all_auto_cnt]*100,2) ?>%</td>

	 <?
    //계정별
    foreach($g5['cn_cointype'] as $k=> $v){ ?>     
	<td  ><?php echo number_format2($tot['ac_point_'.$k],8)?></td>


     <? }?> 
    </tr>
    
    </tbody>
    </table>
</div>	
	


<div class="tbl_head01 tbl_wrap">
<table>
<caption><?php echo $g5['title']; ?> 목록</caption>
    <thead>
    <tr>
<th rowspan="2" scope="col">번호</a></th>

<th rowspan="2" id="mb_list_id" scope="col"><?php echo subject_sort_link('a.mb_id',"&date_stx=$date_stx") ?>아이디</a></th>
<th rowspan="2"  scope="col">설정금액</th>
<th rowspan="2" scope="col">보유금액<br>
가용급액</th>
<th rowspan="2"  scope="col">거래<br>
(구매중)</th>
<th rowspan="2" scope="col" ><?php echo subject_sort_link('sc_cnt',"&date_stx=$date_stx") ?>총계정</a><br>

<?php echo subject_sort_link('active_cnt',"&date_stx=$date_stx") ?>활성계정</a></th>
<th rowspan="2" scope="col" ><?php echo subject_sort_link('open_r',"&date_stx=$date_stx") ?>Open%</a></th>
        
    <?
		//계정별
		foreach($g5['cn_item'] as $k=> $v){ 
		
		?>
        <th colspan="3"  scope="col"><?php echo subject_sort_link($k."_cnt","&date_stx=$date_stx",'desc') ?><?=$v[name_kr]?></th>

        <? }?>
		<th rowspan="2" scope="col" ><?php echo subject_sort_link('t.tr_cnt',"&date_stx=$date_stx", 'desc') ?>총매수</th>
<th rowspan="2" scope="col" ><?php echo subject_sort_link('f.ftr_cnt',"&date_stx=$date_stx", 'desc') ?>총매도</th>
<th rowspan="2" scope="col" ><?php echo subject_sort_link('all_auto_cnt',"&date_stx=$date_stx", 'desc') ?>총auto</th>
<th rowspan="2" scope="col" ><?php echo subject_sort_link('m_ratio',"&date_stx=$date_stx", 'desc') ?>매칭률</th>
		
        <?
		//계정별
		foreach($g5['cn_cointype'] as $k=> $v){ ?>
        <th rowspan="2" id="mb_list_id" scope="col"><?php echo subject_sort_link('b.ac_point_'.$k) ?>총<?=$v?>수량</th>
        <? }?>
		
		<th rowspan="2" scope="col" ><?php echo subject_sort_link('item_cnt', '', 'desc') ?>보유<?=$g5[cn_item_name]?></th>
</tr>
<tr>
 <?
		//계정별
		foreach($g5['cn_item'] as $k=> $v){ 
		?>
<th  scope="col">auto</th>
<th  scope="col">매수</th>
<th  scope="col">매도</th>
<? }?>
</tr>
    </thead>
    <tbody>
    <?php
	$list_num = $total_count - ($page - 1) * $rows;
    for ($i=0; $row=sql_fetch_array($result); $i++) {
           
        $bg = 'bg'.($i%2);
		
    ?>

    <tr class="<?php echo $bg; ?>">
<td rowspan="2"  class="td_num"><?=$list_num?></td>

	<td rowspan="2" class='mb-info-open' data-id='<?php echo $row['mb_id'] ?>' > <?php echo $row[mb_id] ?><br>
<?php echo $row[mb_name] ?></td>
<td rowspan="2"><?=number_format2($row['mb_trade_amtlmt'])?></td>
<td><strong>
<?=number_format2($row['ct_buy_price'])?>
</strong></td>
<td rowspan="2"><?=number_format2($row['tr_price_org'])?></td>
<td class='td_center'><?=$row['sc_cnt']?></td>
<td rowspan="2" class='td_center'><?=round($row['open_r']*100,1)?>%</td>
      
    <?
    //오토매칭
    foreach($g5['cn_item'] as $k=> $v){ 
	?>
    <td  >
<?=$row[$k.'_auto_cnt']?>
</td>
<td  ><?=$row[$k.'_cnt']?></td>
<td  ><?=$row['f'.$k.'_cnt']?></td>


     <? }?> 
<td><?php echo $row[tr_cnt]; ?></td>
<td><?php echo $row[ftr_cnt]; ?></td>
<td rowspan="2"><?php echo $row[all_auto_cnt]; ?></td>
<td rowspan="2"><?php echo round($row[m_ratio]*100,2) ?>%</td>

	 <?
    //계정별
    foreach($g5['cn_cointype'] as $k=> $v){ ?>     
	<td rowspan="2"  ><?php echo number_format2($row['ac_point_'.$k],8)?></td>


     <? }?> 
	 <td rowspan="2"><?php echo $row[ct_cnt]; ?></td>
</tr>
<tr class="<?php echo $bg; ?>">
<td><?=number_format2($row['mb_trade_amtlmt']-$row['ct_buy_price']-$row['tr_price_org'])?></td>
<td class='td_center'><strong>
<?=$row['active_cnt']?>
</strong></td>
<?
//오토매칭
foreach($g5['cn_item'] as $k=> $v){ 
?>
<td  >&nbsp;</td>
<td  ><strong>
<?=number_format2($row[$k.'_price'])?>
</strong></td>
<td  ><strong>
<?=number_format2($row['f'.$k.'_price'])?>
</strong></td>
<?} ?>
<td class='fblue'><strong>
<?=number_format2($row['tr_price'])?>
</strong></td>
<td  class='fred'><strong>
<?=number_format2($row['ftr_price'])?>
</strong></td>
</tr>
    <?php
	$list_num--;
    }
    if ($i == 0)
        echo "<tr><td colspan=\"".$colspan."\" class=\"empty_table\">자료가 없습니다.</td></tr>";
    ?>
    </tbody>
</table>
    
</div>


<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, '?'.$qstr.'&amp;page='); ?>

<script>
function fmemberlist_submit(f)
{
    if (!is_checked("chk[]")) {
        alert(document.pressed+" 하실 항목을 하나 이상 선택하세요.");
        return false;
    }

    if(document.pressed == "선택삭제") {
        if(!confirm("선택한 자료를 정말 삭제하시겠습니까?")) {
            return false;
        }
    }
		
    return true;
}
</script>

