<?php
include_once('./_common.php');

//서비스 블럭
service_block();


$code=trim($_POST['code']);


$cdata = sql_fetch("select * from {$g5['cn_item_cart']} where mb_id='$member[mb_id]' and code='$code' and is_soled='0' and is_trade='0'	 ");
if(!$cdata[code]) alert_json(false,"변환할 {g5[cn_itemname]}를 찾을수 없습니다");



$amt_i=floor($_POST['amt']);
$amt_s=floor($_POST['point']);


$ct_logs=date("Y-m-d H:i:s")." 소각 및 포인트 변환 by {$member[mb_id]}";
$sql = "update {$g5['cn_item_cart']} set is_soled='1',	ct_logs=concat(ct_logs,'$ct_logs') 	where code='$cdata[code]' ";
$result= sql_query($sql,1);
		
if($amt_i > 0){

	$content['link_no']=$cdata[code];					
	$content['pt_wallet']='free'; //지갑구
	$content['pt_coin']='i'; //코인구분
	$content['amount']=$amt_i;
	$content['subject']='나비 포인트 변환';

	set_add_point('burnin',$member,'',$member['mb_id'],$content);						

}


if($amt_s > 0){

	$content['link_no']=$cdata[code];					
	$content['pt_wallet']='free'; //지갑구
	$content['pt_coin']='s'; //코인구분
	$content['amount']=$amt_s;
	$content['subject']='나비 포인트 변환';

	set_add_point('burnin',$member,'',$member['mb_id'],$content);						

}		



alert_json(true,'포인트 변환이 완료 되었습니다');



?>