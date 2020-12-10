<?php
include_once('./_common.php');


//회원 포인트 배치
//if($is_admin!='super') die('권한 없음');

$sql = " select a.* , t.tr_rdate , t.tr_code ttr_code from  {$g5['cn_item_cart']} as a 
left outer join  {$g5['cn_item_trade']} as t on(a.code=t.cart_code) where t.tr_rdate >= '2020-05-27 15:00:00' and t.tr_stats='1' ";

$result = sql_query($sql,1);

$cnt=1;
while($data=sql_fetch_array($result)) {

	echo $cnt.") ". $data[ttr_code] .'/'.$data[mb_id] .'/'.$data[smb_id] .'/'.$data[code] .'/'.$data[cn_item].'/ 최초:'. $data[ct_wdate] .'/유효일 : '. $data[ct_validdate]  .'/거래등록일 : '. $data[tr_rdate] .'/'.
	$g5[cn_item][$data[cn_item]][name_kr] .'/'.$data[ct_class].'등급-----------<br><br>';
		
	$mb_id=explode(".",$data[smb_id]);
	$mb_id=$mb_id[0];
	if($data[mb_id]==''){
		//$sql="update  {$g5['cn_item_cart']} set mb_id='$mb_id' where code='$data[code]'";
		//echo $sql."<br>";
		////sql_fetch($sql,1);
	}
	
	
	$sql="update {$g5['cn_item_trade']}  set
	mb_id='Company3',smb_id='Company3'
	where tr_code='$data[ttr_code]'	
	";
			
	//echo $sql."<br>";
	sql_fetch($sql,1);
	//		
	
	
	
	$cnt++;

}