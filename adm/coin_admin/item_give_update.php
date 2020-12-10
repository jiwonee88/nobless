<?php
$sub_menu = "700250";
include_once('./_common.php');


auth_check($auth[$sub_menu], 'w');

check_admin_token();


if ($w == 'u') {
	
	if($buy_date=='') $buy_date=date("Y-m-d"); 
	else $buy_date.=' '.$buy_time;
	
	$mb=get_member($mb_id);
	if($mb[mb_id]=='') alert('지급될 회원을 찾을 수 없습니다');
	
	if($smb_id){
		$smb=get_submember($smb_id);
		if($smb[ac_id]=='' || $smb[mb_id]!=$mb_id) alert('지급될 서브계정이 없거나 다른 회원의 서브계정입니다');
		
		$npoint=get_mempoint($mb_id,$smb_id);
	}else{
	
		$npoint=get_mempoint($mb_id,$mb_id);
	}
	
	//echo $npoint[$g5['cn_fee_coin']]['_enable'];
	//if(only_number($tot_fee) > $npoint[$g5['cn_fee_coin']]['_enable']) alert('수수료가 부족합니다');
	
	
	if($smb_id=='' ) $smb_id=$mb_id;
	
	//상품지급
	foreach($g5['cn_item'] as $k=> $v) { 
	
		$qty=$item_qty[$k];
		
		for($i=1;$i <= $qty;$i++){
	
			//지급코드
			$code= get_itemcode();	
			$days= $v[days] ? $v[days]:1;
			$validdate=date("Y-m-d",strtotime("+ {$days} days",strtotime($buy_date)));

			//예정가격
			$sell_price=floor( ($v[price] + ($v[price]*$v[interest]/100))*10 )/10;
			
			
			$sql="insert into {$g5['cn_item_cart']}
			set 
			code='$code',
			cn_item='$k',
			mb_id='$mb_id',
			smb_id='$smb_id',
			fmb_id='$member[mb_id]',
			fsmb_id='$member[mb_id]',

			ct_buy_price='$v[price]',
			ct_sell_price='$sell_price',

			ct_class='1',
			ct_interest='$v[interest]',
			ct_days='$days',
			ct_validdate='$validdate',

			ct_wdate='$buy_date'
			";

			sql_query($sql);
		}
	
	}	
	
	if(!$neverend) goto_url("./item_cart_list.php");
	else  goto_url("./item_give_form.php");
	
}
	
?>