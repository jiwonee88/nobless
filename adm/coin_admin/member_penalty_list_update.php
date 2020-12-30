<?php
$sub_menu = "200400";
include_once('./_common.php');

if ($_POST['act_button'] == "보유일자변경") {
	$validate = $_POST['ct_validdate'];
	$interval = intval($_POST['ct_intervaldate']);
	$member = $_POST['member'];
	if(is_int($interval)&&preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$validate)){
		$member_condition = $member=="전체회원"||$member==""?"":" and mb_id = '{$member}'";
		$query = "UPDATE coin_item_cart SET ct_validdate = DATE_ADD(ct_validdate, INTERVAL {$interval} DAY) WHERE ct_validdate = '{$validate}' {$member_condition}";
		sql_query($query);
		echo "처리완료";
	}else{
		echo "데이터가 형식에 맞지 않습니다.";
	}
}else{

	check_demo();
	if (!count($_POST['chk'])) {
		alert($_POST['act_button']." 하실 항목을 하나 이상 체크하세요.");
	}

	auth_check($auth[$sub_menu], 'w');

	check_admin_token();

	//지사가 접근시
	if($is_branch){
		$branch_search = " and 
			(
			mb_recommend='{$member[mb_id]}' 		
			or mb_recommend in (select mb_id from {$g5[member_table]} where mb_recommend='{$member[mb_id]}' )
			or mb_recommend in (select mb_id from {$g5[member_table]} where mb_recommend in (select mb_id from {$g5[member_table]} where mb_recommend='{$member[mb_id]}' )  )		
			)
			";
	}


	if ($_REQUEST['date_start_stx']) {	
		$qstr.="&date_start_stx=$date_start_stx";		
	}
	if ($_REQUEST['date_end_stx']) {
		$qstr.="&date_end_stx=$date_end_stx";
	}
	if($penalty_stx) {
		$qstr.="&penalty_stx=$penalty_stx";
	}

	if ($_POST['act_button'] == "선택수정") {

		for ($i=0; $i<count($_POST['chk']); $i++)
		{
			// 실제 번호를 넘김
			$k = $_POST['chk'][$i];
			$mb_trade_penalty = $_POST['mb_trade_penalty'][$i];
			$mb_trade_penalty_date = $_POST['mb_trade_penalty_date'][$i];
			
			$sql = " update {$g5['member_table']}
						set 
						mb_trade_penalty = '".sql_real_escape_string($_POST['mb_trade_penalty'][$k])."',
						mb_trade_penalty_date = '".sql_real_escape_string($_POST['mb_trade_penalty_date'][$k])."'
							
						where mb_id = '".sql_real_escape_string($_POST['mb_id'][$k])."'";
			
			//지사가 접근시
			if($is_branch){
				$sql .= $branch_search ;
			}


			//echo $sql;
			sql_query($sql);
		}

	} else if ($_POST['act_button'] == "패널티초기화") {

		for ($i=0; $i<count($_POST['chk']); $i++)
		{
			// 실제 번호를 넘김
			$k = $_POST['chk'][$i];
	   
			$sql = " update {$g5['member_table']}
						set 
						mb_trade_penalty = '0',
						mb_trade_penalty_date = '0000-00-00 00:00:00 '
							
						where mb_id = '".sql_real_escape_string($_POST['mb_id'][$k])."'";
			
			//지사가 접근시
			if($is_branch){
				$sql .= $branch_search;
			}

			//echo $sql;
			sql_query($sql);
		}

	}
	goto_url("./member_penalty_list.php?$qstr");
}

?>
