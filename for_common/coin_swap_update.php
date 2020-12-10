<?php
include_once('./_common.php');

if ( $w == 'u') {
	
	$sw_token=trim($_POST['sw_token']);
	$sw_amt=only_number($_POST['sw_amt']);	
	$fee=only_number($cset['swap_fee_'.$sw_token]);	
	
	$sw_fee=round($sw_amt*$fee/100,6);
	
	$sw_tamt=$sw_amt-$sw_fee;	
	
	$set_token=trim($_POST['set_token']);
	
	if($sw_token=='' || $set_token=='') alert_json(false,'Please select a coin to swap');
	
	if( $sw_amt == 0) alert_json(false,'Enter the quantity to swap');		
		
	if($sw_amt <= $sw_fee) alert_json(false,"Please enter a quantity greater than the fee");
	
	//보유수량
	$sum=$rpoint[$sw_token]['_enable']*1;
	
	if($sum < abs($sw_amt))  alert_json(false,'There is not enough quantity to swap');	
	
	//스왑액 계산
	$sw_set_amt=swap_coin($sw_tamt,$sw_token,$set_token,$sise);	
		
	//if ($member['mb_password'] != get_encrypt_string(trim($_POST['pass'])) ) alert_json(false,'Password is wrong');
		
	//스왑내역 기록
	$sql_common .= " 
			sw_token='$sw_token',
			sw_amt  = '{$sw_amt}',	
			sw_fee  = '{$sw_fee}',	
			sw_tamt  = '{$sw_tamt}',
			
			sw_set_token='$set_token',
			sw_set_amt='$sw_set_amt',
			sw_stats  = '1'			
					";
					
    $sql = " insert into {$g5['cn_swap_table']}
                set 				
				mb_id		 = '{$member['mb_id']}',
				sw_wdate = now(),
				$sql_common ";
	
	//echo $sql;				
    sql_query($sql,1);	
	$sw_no=sql_insert_id();	
	
	$data=sql_fetch("select * from {$g5['cn_swap_table']} where sw_no='$sw_no'");				
	$return=set_swap_coin($data,3,1);
	
	//잔액 조회
	$rpoint=get_mempoint($member['mb_id']);
	
	alert_json(true,'',array('sw_no'=>$sw_no,'max_enable'=>number_format2($rpoint[$sw_token]['_enable'],6),'max_enable_usd'=>number_format2(swap_usd($rpoint[$sw_token]['_enable'],$sw_token),2) ));		
}
/* else if ($w == 'u') {
	
    $sql = " update  {$g5['cn_swap_table']} set 
			 
	         {$sql_common}
              where sw_no = '{$sw_no}' ";
   // sql_query($sql,1);
	
}
*/

	
//goto_url("./coin_draw_out_list.php?page=$page&{$qstr}");
?>