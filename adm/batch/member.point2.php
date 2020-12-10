<?php
include_once('./_common.php');

exit;

//회원 포인트 배치
if($is_admin!='super') die('권한 없음');


//포인트 비우기
sql_query("truncate table {$g5['cn_pointsum']}");
sql_query("truncate table coin_point_2005");

//서브계정 포인트 비우기
sql_query("update coin_sub_account set ac_point_i='0'");

//회원 포인트 비우기
sql_query("update {$g5['member_table']}  set mb_point_free_i='0'");


$sql_search = " where (1) ";

$sql = " select * from {$g5['member_table']} $sql_search  order by mb_no asc ";
$result = sql_query($sql,1);

$cnt=1;
while($data=sql_fetch_array($result)) {
	
	echo $cnt.") ". $data[mb_id] .'-----------<br>';
	
		
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
	
	//메인 아이디 가입 축하 포인트 지급		
	$content['pt_wallet']='free'; //지갑구
	$content['pt_coin']='i'; //화폐구분
	$content['amount']=200;			
	$content['subject']='가입 축하금';

	print_r($content);
	set_add_point('joinb',$data,'',$data[mb_id],$content);		

	echo "<br>";
	
	
	//서브계정 생성
	for($i=1;$i <= 10;$i++){
	
		$ac_id=$data[mb_id].'.'.sprintf("%02d",$i);	
		
		$temp = sql_fetch("select * from {$g5['cn_sub_account']} where	mb_id='{$data[mb_id]}' and	ac_id='$ac_id'	");
		
		if(!$temp[ac_id]){
		
			$sql = " insert into {$g5['cn_sub_account']}
				set
				mb_id='{$data[mb_id]}',
				ac_id='$ac_id',
				ac_point_i='200',
				ac_active='1',
				ac_wdate=now()
				";
					
				echo $sql ."<br>";
				sql_query($sql,1);	

		}else if($i <=10){
			
			$sql="update {$g5['cn_sub_account']} set ac_point_i='200',ac_active='1' where	mb_id='{$data[mb_id]}' and	ac_id='$temp[ac_id]'	";
			
			echo $sql."<br>";
			sql_fetch($sql);
		}
					
	}		
	
	//서브계정 포인트 지급
	for($i=1;$i <= 10;$i++){
	
		$ac_id=$data[mb_id].'.'.sprintf("%02d",$i);	
	
		//메인 아이디 가입 축하 포인트 지급
		foreach($g5['cn_cointype'] as $k=>$k){

			if($cset['join_bonus_'.$k]==0) continue;

			$content['pt_wallet']='free'; //지갑구
			$content['pt_coin']=$k; //화폐구분
			$content['amount']=200;			
			$content['subject']='가입 축하금';

			print_r($content);
			set_add_point('joinb',$data,$ac_id,$data[mb_id],$content);		

			echo "<br>";
		}
	}
	
	
	//회원별 최종 수당 정보 업데이트
	set_update_point($data['mb_id']);
	
	$cnt++;

}