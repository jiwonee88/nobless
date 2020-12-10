<?php
include_once('./_common.php');


if($sw_token=='' || $set_token=='') alert_json(false,'Insufficient argument' );		

$amt=round(only_number($_POST[sw_amt]),6);
$fee=$cset['swap_fee_'.$sw_token];	
$sw_fee=round($amt*$fee/100,6);

$tamt=swap_coin($amt-$sw_fee,$sw_token,$set_token,$sise);	
$usd=swap_usd($amt-$sw_fee,$set_token,$sise);	

alert_json(true,'',array('amt'=>number_format2($tamt,6),'usd'=>number_format2($usd,2) ));		


