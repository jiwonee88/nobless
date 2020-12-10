<?php
include_once('./_common.php');

exit;
//회원배치

if($is_admin!='super') die('권한 없음');

$sql_search = " where (1) ";

$sql = " select * from {$g5['member_table']} $sql_search  order by mb_no asc ";
$result = sql_query($sql,1);

$cnt=1;
while($data=sql_fetch_array($result)) {
	
	/*
	$new_hp=only_numeric($data[mb_hp]);
	
	$sql="update {$g5['member_table']} set mb_hp='$new_hp' where mb_id='$data[mb_id]'";
	echo $sql. '/'. $data[mb_hp].' <br>';
	sql_query($sql);
	
	continue;
	*/
	
	//지급이 있는 경우 스킵
	$temp=sql_fetch("select * from {$g5['cn_pointsum']} where mb_id='$data[mb_id]' and pkind='joinb'");
	if($temp[pt_no]){
		echo $data[mb_id]." 이미 지급됨 " .$temp[amount] ."<br>";
		continue;
	}
	
	//이전 휴대 전화 검색
	if($data[mb_hp]!=''){
		$temp=sql_fetch("select * from {$g5['member_table']} where mb_hp='$data[mb_hp]' and mb_no < '{$data['mb_no']}'");
		if($temp[mb_no]){
			echo $data[mb_id]." 이전 중복 휴대전화 " .$temp[mb_id] ." / ",$temp[mb_hp]."<br>";
			continue;
		}
	}
	
	
	echo $cnt.") ". $data[mb_id] .'-----------<br>';
	//가입 축하 포인트 지급
	foreach($g5['cn_cointype'] as $k=>$k){
		if($cset['join_bonus_'.$k]==0) continue;
		$content['pt_wallet']='free'; //지갑구
		$content['pt_coin']=$k; //화폐구분
		$content['amount']=$cset['join_bonus_'.$k];			
		$content['subject']='가입 축하금';

		print_r($content);
		//set_add_point('joinb',$data,'',$data[mb_id],$content);		
		
		echo "<br>";
	}
	
	$cnt++;


}