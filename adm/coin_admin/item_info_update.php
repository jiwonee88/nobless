<?php
$sub_menu = "500400";
include_once('./_common.php');

check_demo();

if ($w=='u') {
	
    auth_check($auth[$sub_menu], 'w');

	sql_query("truncate table {$g5['cn_item_info']}");		

    foreach ($g5['cn_item_org'] as $k=> $data) {
				
		$name_kr_v=(trim($_POST['name_kr'][$k])!=''?trim($_POST['name_kr'][$k]):$data['name_kr']);
		$days_v=$_POST['days'][$k];
		$price_v=only_number($_POST['price'][$k]);
		$mxprice_v=only_number($_POST['mxprice'][$k]);
		$fee_v=only_number($_POST['fee'][$k]);		
		$interest_v=only_number($_POST['interest'][$k]);		
		
   		$sql="insert into {$g5['cn_item_info']} set 
		cn_item='$k',
		name_kr='$name_kr_v',
		days='$days_v',
		price='$price_v',
		mxprice='$mxprice_v',
		interest='$interest_v',
		
		fee='$fee_v'
		";		
		
		echo $sql;
		
		sql_query($sql,1);
    }
}


goto_url('./item_info.php');
?>