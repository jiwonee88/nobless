<?php
$sub_menu = "700100";
include_once('./_common.php');

if ($w == 'u' )
    check_demo();

//auth_check($auth[$sub_menu], 'w');

//숫자만..
foreach($_POST as $k=>$v){
	if(preg_match("/^(wallet_out_|wallet_in_|bank_)/",$k)) continue;
	
	${$k}=only_number(trim($v));
}

if ($_POST['w'] == 'u') {	
    
    $sql = " update {$g5['cn_set']} set 
	
	deposite_reward_r='$deposite_reward_r',
	
	join_bonus_b='$join_bonus_b',
	join_bonus_e='$join_bonus_e',
	join_bonus_i='$join_bonus_i',
	join_bonus_u='$join_bonus_u',
	
	promote_bonus_b='$promote_bonus_b',
	promote_bonus_e='$promote_bonus_e',
	promote_bonus_i='$promote_bonus_i',
	promote_bonus_u='$promote_bonus_u',
	
	bank_name='$bank_name',
	bank_num='$bank_num',
	bank_user='$bank_user',
	
	
	max_account_lmt ='$max_account_lmt',
	in_div_r1 ='$in_div_r1',
	in_div_r2 ='$in_div_r2',
	trans_r ='$trans_r',
	
	daily_bs_r ='$daily_bs_r',
	daily_bs_min ='$daily_bs_min',
	
	pr_bs_r ='$pr_bs_r',
	max_bs_r='$max_bs_r',
	
	max_out_b='$max_out_b',
	max_out_e='$max_out_e',
	max_out_i='$max_out_i',
	max_out_u='$max_out_u',
	max_out_s='$max_out_s',
	
	min_out_b='$min_out_b',
	min_out_e='$min_out_e',
	min_out_i='$min_out_i',
	min_out_u='$min_out_u',
	
	min_trans_b='$min_trans_b',
	min_trans_e='$min_trans_e',
	min_trans_i='$min_trans_i',
	min_trans_u='$min_trans_u',
	
	
	out_r ='$out_r',
	
	out_fee_b='$out_fee_b',
	out_fee_e='$out_fee_e',
	out_fee_i='$out_fee_i',
	out_fee_u='$out_fee_u',
	
	trans_fee_b='$trans_fee_b',
	trans_fee_e='$trans_fee_e',	
	trans_fee_i='$trans_fee_i',	
	trans_fee_u='$trans_fee_u',		
	
	swap_fee_b='$swap_fee_b',
	swap_fee_e='$swap_fee_e',	
	swap_fee_i='$swap_fee_i',	
	swap_fee_u='$swap_fee_u',		
	
	staking_amt='$staking_amt',
	staking_reward='$staking_reward',
	
	staking_ref_amt='$staking_ref_amt',
	";	
	for($i=1;$i <=8;$i++){
		$sql .= " 
		staking_fee{$i} ='".${'staking_fee'.$i}."',
		";
	}
	
	$sql.="	
	min_sp_num='$min_sp_num',
	max_sp_num='$max_sp_num',
	
	mtoken_min_r='$mtoken_min_r',
	
	wallet_in_b='$wallet_in_b',
	wallet_out_b='$wallet_out_b',
	wallet_in_e='$wallet_in_e',
	wallet_out_e='$wallet_out_e',	
	wallet_in_i='$wallet_in_i',
	wallet_out_i='$wallet_out_i',	
	wallet_in_u='$wallet_in_u',
	wallet_out_u='$wallet_out_u',			
	";	
	
	
	for($i=1;$i <=15;$i++){
		$sql .= " 
		pr_rup_bs{$i}_step ='".${'pr_rup_bs'.$i.'_step'}."',
		pr_rup_bs{$i}_r  ='".${'pr_rup_bs'.$i.'_r'}."',
		";
	}
	
	$sql.="	
	pr_rup_bs_cls1_step ='$pr_rup_bs_cls1_step',
	pr_rup_bs_cls1_r ='$pr_rup_bs_cls1_r',
	pr_rup_bs_cls2_step ='$pr_rup_bs_cls2_step',
	pr_rup_bs_cls2_r ='$pr_rup_bs_cls2_r',
	pr_rup_bs_cls3_step ='$pr_rup_bs_cls3_step',
	pr_rup_bs_cls3_r ='$pr_rup_bs_cls3_r',
	pr_rup_bs_cls4_step ='$pr_rup_bs_cls4_step',
	pr_rup_bs_cls4_r ='$pr_rup_bs_cls4_r',
	pr_rup_bs_cls5_step ='$pr_rup_bs_cls5_step',
	pr_rup_bs_cls5_r ='$pr_rup_bs_cls5_r',
	pr_rup_bs_cls6_step ='$pr_rup_bs_cls6_step',
	pr_rup_bs_cls6_r ='$pr_rup_bs_cls6_r',
	pr_rup_bs_cls7_step ='$pr_rup_bs_cls7_step',
	pr_rup_bs_cls7_r ='$pr_rup_bs_cls7_r',
	pr_rup_bs_cls8_step ='$pr_rup_bs_cls8_step',
	pr_rup_bs_cls8_r ='$pr_rup_bs_cls8_r',
	pr_rup_bs_cls9_step ='$pr_rup_bs_cls9_step',
	pr_rup_bs_cls9_r ='$pr_rup_bs_cls9_r',
	
	sp_rup_bs1_step ='$sp_rup_bs1_step',
	sp_rup_bs1_r  ='$sp_rup_bs1_r',
	sp_rup_bs2_step ='$sp_rup_bs2_step',
	sp_rup_bs2_r  ='$sp_rup_bs2_r',
	sp_rup_bs3_step ='$sp_rup_bs3_step',
	sp_rup_bs3_r  ='$sp_rup_bs3_r',
	sp_rup_bs4_step ='$sp_rup_bs4_step',
	sp_rup_bs4_r  ='$sp_rup_bs4_r',
	sp_rup_bs5_step ='$sp_rup_bs5_step',
	sp_rup_bs5_r  ='$sp_rup_bs5_r',
	
	
	sp_bs_cls1_r ='$sp_bs_cls1_r',
	sp_bs_cls2_r  ='$sp_bs_cls2_r',
	sp_bs_cls3_r  ='$sp_bs_cls3_r',
	sp_bs_cls4_r  ='$sp_bs_cls4_r',
	sp_bs_cls5_r  ='$sp_bs_cls5_r',
	sp_bs_cls6_r  ='$sp_bs_cls6_r',
	sp_bs_cls7_r  ='$sp_bs_cls7_r',
	sp_bs_cls8_r  ='$sp_bs_cls8_r',
	sp_bs_cls9_r  ='$sp_bs_cls9_r',

	cls_bs_cls1_r ='$cls_bs_cls1_r',
	cls_bs_cls2_r  ='$cls_bs_cls2_r',
	cls_bs_cls3_r  ='$cls_bs_cls3_r',
	cls_bs_cls4_r  ='$cls_bs_cls4_r',
	cls_bs_cls5_r  ='$cls_bs_cls5_r',
	cls_bs_cls6_r  ='$cls_bs_cls6_r',
	cls_bs_cls7_r  ='$cls_bs_cls7_r',
	cls_bs_cls8_r  ='$cls_bs_cls8_r',
	cls_bs_cls9_r  ='$cls_bs_cls9_r',
	
	lvup_cls1_sales1 ='$lvup_cls1_sales1',
	lvup_cls1_sales2 ='$lvup_cls1_sales2',
	lvup_cls1_sales3 ='$lvup_cls1_sales3',
	lvup_cls1_subor ='$lvup_cls1_subor',
	lvup_cls2_sales1 ='$lvup_cls2_sales1',
	lvup_cls2_sales2 ='$lvup_cls2_sales2',
	lvup_cls2_sales3 ='$lvup_cls2_sales3',
	lvup_cls2_subor ='$lvup_cls2_subor',
	lvup_cls3_sales1 ='$lvup_cls3_sales1',
	lvup_cls3_sales2 ='$lvup_cls3_sales2',
	lvup_cls3_sales3 ='$lvup_cls3_sales3',
	lvup_cls3_subor ='$lvup_cls3_subor',
	lvup_cls4_sales1 ='$lvup_cls4_sales1',
	lvup_cls4_sales2 ='$lvup_cls4_sales2',
	lvup_cls4_sales3 ='$lvup_cls4_sales3',	
	lvup_cls4_subor ='$lvup_cls4_subor',
	lvup_cls5_sales1 ='$lvup_cls5_sales1',
	lvup_cls5_sales2 ='$lvup_cls5_sales2',
	lvup_cls5_sales3 ='$lvup_cls5_sales3',	
	lvup_cls5_subor ='$lvup_cls5_subor',
	lvup_cls6_sales1 ='$lvup_cls6_sales1',
	lvup_cls6_sales2 ='$lvup_cls6_sales2',
	lvup_cls6_sales3 ='$lvup_cls6_sales3',	
	lvup_cls6_subor ='$lvup_cls6_subor',
	lvup_cls7_sales1 ='$lvup_cls7_sales1',
	lvup_cls7_sales2 ='$lvup_cls7_sales2',
	lvup_cls7_sales3 ='$lvup_cls7_sales3',	
	lvup_cls7_subor ='$lvup_cls7_subor',
	lvup_cls8_sales1 ='$lvup_cls8_sales1',
	lvup_cls8_sales2 ='$lvup_cls8_sales2',
	lvup_cls8_sales3 ='$lvup_cls8_sales3',	
	lvup_cls8_subor ='$lvup_cls8_subor',
	lvup_cls9_sales1 ='$lvup_cls9_sales1',
	lvup_cls9_sales2 ='$lvup_cls9_sales2',
	lvup_cls9_sales3 ='$lvup_cls9_sales3',	
	lvup_cls9_subor ='$lvup_cls9_subor',
	
	ed_bs_jijum_sales ='$ed_bs_jijum_sales',
	ed_bs_jijum_r ='$ed_bs_jijum_r',

	ed_bs_jisa_sales ='$ed_bs_jisa_sales',
	ed_bs_jisa_r ='$ed_bs_jisa_r'	
	
	";
	
     $result=sql_query($sql,1);	 
	
}

goto_url("./setting_form.php");
?>