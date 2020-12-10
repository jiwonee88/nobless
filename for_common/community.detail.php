<?php
define("IS_COMMUNITY",true) ;
include_once('./_common.php');

add_stylesheet(" <link rel=\"stylesheet\" type=\"text/css\" href='".G5_THEME_URL."/css/community.css?ver=".G5_CSS_VER."' >");
include_once('../_head.php');
?>       
		
<div class='wrapper-community  anima-fade' style="padding-bottom: 0; padding-bottom: 60px;">
<div class='wrapper-community-main' >
	<div style="min-height: 0;" class='wrapper-header wrapper-header-assets'>
		<section class="wrapper-header-top">
			<div class="back"></div>
			<h1 class="title" style="margin-top: 3px;">Partner Detail</h1>
		</section>			
    </div>	
		
	<section class="partner-detail">

		<table class='partner-table '>
		<tbody>
		<tr>
		<th>No</th>
	<th>Partners</th>
	<th>Staking Amount</th>
		</tr>
	<?
	$re= sql_query("select *,count(*) cnt from  {$g5['cn_tree']} as a  where a.mb_id='{$member['mb_id']}' group by step order by step",1);
	while($data=sql_fetch_array($re)){
	if($data[step] <= 3) $step_class='danger';
	else if($data[step] <= 5) $step_class='warning';
	else if($data[step] <= 7) $step_class='success';
	else if($data[step] <= 9) $step_class='info';
	else $step_class='secondary';

	//스테이킹
	$stake= sql_fetch("select sum(if(pkind='stake',amount,0)) amt, sum(if(pkind='stake_out',amount,0)) out_amt from  {$g5['cn_pointsum']} as a  where (pkind='stake' or pkind='stake_out' )  and mb_id in 
	(select smb_id from  {$g5['cn_tree']}  where mb_id='{$member['mb_id']}' and step='{$data['step']}' )",1);
	?>
	<tr>
	<td><div class='btn btn-sm btn-<?=$step_class?> rounded-circle rank-num'><?=$data[step]?></div></td>
	<td><?=$data[cnt]?></td>
	<td class='text-right'>$ <?=number_format2(swap_usd(abs($stake[amt]+$stake[out_amt]),'i'),2)?></td>
	</tr>
	<?

	$tot_cnt+=$data[cnt];
	$tot_amt+=($stake[amt]+$stake[out_amt]);

	}

	if(sql_num_rows($re) < 1) {

	?>

	<tr>
	<td colspan="3" class='text-center py-5'>There is no partner.</td>
	</tr>


	<? }?>
	<tr class='sum-tr'>
	<td>Total</td>
	<td><?=$tot_cnt?></td>
	<td class='text-right'>$ <?=number_format2(swap_usd(abs($tot_amt),'i'),2)?></td>
	</tr>

		</tbody>
		</table>

	</section>
</div>
</div>



<?
include_once('../_tail.php');

