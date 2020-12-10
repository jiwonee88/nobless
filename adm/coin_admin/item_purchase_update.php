<?php
$sub_menu = "700850";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'w');

check_admin_token();

if($date_start_stx) {
	$sql_search .= " and a.it_wdate >= '$date_start_stx 00:00:00' ";
	$qstr.="&date_start_stx=$date_start_stx";
}
if($date_end_stx) {
	$sql_search .= " and a.it_wdate <= '$date_end_stx 23:59:59' ";
	$qstr.="&date_end_stx=$date_end_stx";
}
if($coin_stx) {
	$sql_search .= " and a.it_token = '$coin_stx' ";
	$qstr.="&coin_stx=$coin_stx";
}

if($item_stx) {
	$sql_search .= " and a.cn_item = '$item_stx' ";
	$qstr.="&item_stx=$item_stx";
}

if ($w == '' || $w == 'u') {

	$it_token=$_POST['it_token'];

	$mb_id=$_POST['mb_id'];
	$smb_id=$_POST['smb_id'];
	
	$mb=get_member($mb_id);
	$smb=get_submember($smb_id);
	if($mb[mb_id]=='') alert('구매회원을 찾을수 없습니다');
	if($smb[mb_id]!=$mb[mb_id]) alert('서브 아이디가 옳바르지 않습니다');

	$rpoint=get_mempoint($mb_id);		
	
	$it_set_token=$_POST['it_set_token'];

	if($it_set_token=='' ) alert('구매 수단이 없습니다');	

	//결제액
	$it_set_amt=only_number($_POST['it_set_amt']);		

	if(!$it_set_amt) alert('구매액을 입력하세요');	

}

if ($w == '' ) {
		
	
	$cn_item=$_POST['cn_item'];

	$item_data=$g5['cn_item'][$cn_item];

	if(!$item_data[price]) alert('구매가 불가능한 상품입니다');

	$it_item_qty=only_number($_POST['qty']);		

	//상품 원가 구매액
	$it_rsv_amt=only_number($item_data[price] * $it_item_qty);		

	if(!$it_rsv_amt) alert('구매 수량이 없습니다');	

	$cn_item_name=$item_data['name_kr'];
	
	if($rpoint[$it_set_token]['_enable']*1 < $it_set_amt) alert($g5['cn_cointype'][$it_set_token].'가 부족합니다');	
		
			
}

if ($w == '') {	
	
    $sql = " insert into {$g5['cn_item_purchase']}
                set
				mb_id		 = '{$mb['mb_id']}',	
			smb_id		 = '{$smb_id}',	
			cn_item='".addslashes($cn_item)."',
			cn_item_name='".addslashes($cn_item_name)."',

			it_item_qty='$it_item_qty',
			it_token='$it_token',

			it_wallet_addr  = '{$it_wallet_addr}',	
			it_rsv_amt  = '{$it_rsv_amt}',	
			it_rsv_date  = now(),

			it_set_amt  = '{$it_set_amt}',	
			it_set_token  = '{$it_set_token}'	,

			it_stats  = '1'	,
			it_balance='$it_balance',
			it_balance_last='$it_balance',
			it_mdate = now(),

			it_wdate = now()
			";
	
	//echo $sql;				
    sql_query($sql,1);	
	$it_no=sql_insert_id();
	
	//구매처리
	$data= sql_fetch("select * from {$g5['cn_item_purchase']} where it_no='$it_no'");			
		
	$return=set_purchase_item($data,$it_stats,1);
	
	
} else if ($w == 'u') {
	
	$data= sql_fetch("select * from {$g5['cn_item_purchase']} where it_no='$it_no'");		
	
    $sql = " update  {$g5['cn_item_purchase']} set 			 
	         it_set_amt  = '{$it_set_amt}',	
			 it_set_token  = '{$it_set_token}'	

              where it_no = '{$it_no}' ";
    sql_query($sql,1);

	//지불방법/금액 변경시 취소후 재처리
	if($data['it_set_amt']!=$it_set_amt || $data['it_set_token']!=$it_set_token) set_purchase_coin($data,1,1);
	
	//입금처리
	$data= sql_fetch("select * from {$g5['cn_item_purchase']} where it_no='$it_no'");
	
	//입금처리
	$return=set_purchase_item($data,$it_stats,1);
	
	if(!$return[0]) alert($return[1]);
	
}
	
goto_url("./item_purchase_list.php?$qstr");
?>