<?php
include_once('./_common.php');

exit;

//회원 포인트 배치
if($is_admin!='super') die('권한 없음');


$sql = " select * from  {$g5['cn_item_trade']} where mb_id='' ";
$result = sql_query($sql,1);

$cnt=1;
while($data=sql_fetch_array($result)) {

	$smb=get_submember($data[smb_id]);
	
	echo $cnt.") ". $data[mb_id] .'/'.$data[smb_id] .'/'. $smb[mb_id] .'-----------<br>';
		
	$sql="update {$g5['cn_item_trade']}  set
	mb_id='$smb[mb_id]'
	where tr_code='$data[tr_code]'	
	";
			
	echo $sql."<br>";
	sql_fetch($sql);
	//		
	
	
	
	$cnt++;

}