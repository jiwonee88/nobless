<?php
include_once('./_common.php');


//회원 포인트 배치
//if($is_admin!='super') die('권한 없음');

$sql = " select a.* from  {$g5['cn_item_cart']} as a where is_trade!='1' and is_soled!='1' and ct_validdate <= '2020-05-27' 
and not exists (select * from  {$g5['cn_item_trade']} where  fmb_id=a.mb_id and tr_wdate >= '2020-05-26')";

$result = sql_query($sql,1);

$cnt=1;
while($data=sql_fetch_array($result)) {

	echo $cnt.") ". $data[mb_id] .'/'.$data[smb_id] .'/'.$data[code] .'/'.$data[cn_item].'/ 최초:'. $data[ct_wdate] .'/유효일 : '. $data[ct_validdate] .'/'.
	$g5[cn_item][$data[cn_item]][name_kr] .'/'.$data[ct_class].'등급-----------<br><br>';
		
	$mb_id=explode(".",$data[smb_id]);
	$mb_id=$mb_id[0];
	if($data[mb_id]==''){
		//$sql="update  {$g5['cn_item_cart']} set mb_id='$mb_id' where code='$data[code]'";
		//echo $sql."<br>";
		////sql_fetch($sql,1);
	}
	
	
	$sql="update {$g5['cn_item_cart']}  set
	is_trade='0',
	trade_cnt=0	
	where code='$data[code]'	
	";
			
	//echo $sql."<br>";
	//sql_fetch($sql,1);
	//		
	
	
	
	$cnt++;

}