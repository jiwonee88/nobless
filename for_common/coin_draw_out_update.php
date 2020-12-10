<?php
include_once('./_common.php');

//서비스 블럭
service_block();

if ( $w == 'u') {
	
	//이체 비밀번호
	if ($member['mb_password'] != get_encrypt_string(trim($_POST['pass'])) ) alert_json(false,'Password is wrong');
	
	
	$dr_wallet_addr=trim($_POST['dr_wallet_addr']);
	$dr_token=trim($_POST['dr_token']);
	$dr_amt=round(only_number($_POST['dr_amt']),6);	
	
	$fee=only_number($cset['out_fee_'.$dr_token]);	
	$min_out=only_number($cset['min_out_'.$dr_token]);	
	
	$dr_fee=round($dr_amt*$fee/100,6);
	$dr_tamt=$dr_amt-$dr_fee;
	
	if($dr_wallet_addr=='' || $dr_token=='') alert_json(false,'ERROR');
	
	if($dr_amt==0 ) alert_json(false,"Please enter a quantity to withdraw.");	
	
	if($dr_amt <= $dr_fee) alert_json(false,"Please enter a quantity greater than the fee.");
	
	$sum=$rpoint[$dr_token]['_enable']*1;
	
	//허용액
	if($sum < abs($dr_amt))  alert_json(false,'There is not enough quantity to withdraw');
	
	if( $dr_amt == 0) alert_json(false,'Enter the quantity to withdraw');
	
	//최소금액
	if($cset['min_out_'.$dr_token] > swap_usd($dr_amt,$dr_token)) alert_json(false,"Minimum amount of withdrawals : $".number_format2($cset['min_out_'.$dr_token]));
	

	//지갑 주소 검사
	 $rtn=check_coin_addr($dr_token,$dr_wallet_addr);
	 if($rtn[0]==false) alert_json(false,$rtn[1]);
 
	
	//출금내역 기록
	$sql_common .= " 
			dr_token='$dr_token',
			dr_wallet_addr  = '$dr_wallet_addr',	
			dr_amt  = '{$dr_amt}',	
			dr_fee  = '{$dr_fee}',	
			dr_tamt  = '{$dr_tamt}',
			
			dr_set_token='$dr_token',
			dr_set_amt  = '{$dr_tamt}',			
			dr_set_fee  = '{$dr_fee}',
			dr_set_tamt  = '{$dr_tamt}',
			
			dr_stats  = '1'			
					";
					
    $sql = " insert into {$g5['cn_draw_table']}
                set 
				
				mb_id		 = '{$member['mb_id']}',
				dr_wdate = now(),
				$sql_common ";
	
	//echo $sql;				
    sql_query($sql,1);	
	$dr_no=sql_insert_id();	
	
	$data=sql_fetch("select * from {$g5['cn_draw_table']} where dr_no='$dr_no'");				
	$data['dr_stats']='0';
	set_draw_coin($data,1);
	
	//잔액 조회
	$rpoint=get_mempoint($member['mb_id']);
	
	alert_json(true,'',array('dr_no'=>$dr_no,'max_enable'=>number_format2($rpoint[$dr_token]['_enable'],6),'max_enable_usd'=>number_format2(swap_usd($rpoint[$dr_token]['_enable'],$dr_token),2) ));		
}
/* else if ($w == 'u') {
	
    $sql = " update  {$g5['cn_draw_table']} set 
			 
	         {$sql_common}
              where dr_no = '{$dr_no}' ";
   // sql_query($sql,1);
	
}
*/


	
//goto_url("./coin_draw_out_list.php?page=$page&{$qstr}");
?>