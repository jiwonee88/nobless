<?php
$sub_menu = "700600";
include_once('./_common.php');

//check_admin_token();

if ( ($_POST['act_button'] == "패널티실행" && $select_pt=='sel' )  || $_POST['act_button'] == "선택수정" || $_POST['act_button'] == "선택삭제" ){
	if (!count($_POST['chk'])) {
		alert($_POST['act_button']." 하실 항목을 하나 이상 체크하세요.");
	}
}

$sql_search = " where (1) ";

if($date_start_stx) {
	$sql_search .= " and a.$date_stx >= '$date_start_stx 00:00:00' ";
	$qstr.="&date_start_stx=$date_start_stx";
}
if($date_end_stx) {
	$sql_search .= " and a.$date_stx <= '$date_end_stx 23:59:59' ";
	$qstr.="&date_end_stx=$date_end_stx";
}
if($date_stx) {	
	$qstr.="&date_stx=$date_stx";
}
if($item_stx) {
	$sql_search .= " and a.cn_item = '$item_stx' ";
	$qstr.="&item_stx=$item_stx";
}
if($paytype_stx) {
	$sql_search .= " and a.tr_paytype = '$paytype_stx' ";
	$qstr.="&paytype_stx=$paytype_stx";
}
if($distri_stx) {
	$sql_search .= " and a.tr_distri = '$distri_stx' ";
	$qstr.="&distri_stx=$distri_stx";
}
if($stats_stx) {
	$sql_search .= " and a.tr_stats = '$stats_stx' ";
	$qstr.="&stats_stx=$stats_stx";
}
if($claim_stx=='all') {	
	$sql_search .= " and ( a.tr_buyer_claim = '1' or a.tr_seller_claim = '1' ) ";
	$qstr.="&claim_stx=$claim_stx";
}
if($claim_stx=='buyer') {	
	$sql_search .= " and a.tr_buyer_claim = '1' ";
	$qstr.="&claim_stx=$claim_stx";
}
if($claim_stx=='seller') {	
	$sql_search .= " and a.tr_seller_claim = '1' ";
	$qstr.="&claim_stx=$claim_stx";
}


if($mb_name_stx!='') {	
	$sql_search .= " and b.mb_name like '%$mb_name_stx%' ";
	$qstr.="&mb_name_stx=$mb_name_stx";
}
if($fmb_name_stx!='') {	
	$sql_search .= "and a.fmb_id in (select mb_id from {$g5['member_table']} where mb_name like '%$fmb_name_stx%' ) ";
	$qstr.="&fmb_name_stx=$fmb_name_stx";
}

//패널티 대상 검색
if($penalty_stx=='ready'){
	$ldate=date("Y-m-d");
	$sql_search .= " and a.tr_penalty = 0 and tr_wdate <= '$ldate' and tr_stats in ('1','2')";
	$qstr.="&penalty_stx=$penalty_stx";
}

if ($stx) {
	if($sfl=='a.mb_id') $sql_search .= "and ($sfl = '$stx') ";
    else $sql_search .= "and ($sfl like '%$stx%') ";
}
if ($mb_stx) {
    $sql_search .= " and b.mb_id='$mb_stx' ";	
	$qstr.="&mb_stx=$mb_stx";
}

//print_r($_POST);

if ($_POST['act_button'] == "일괄변경" || $_POST['act_button'] == "거래정보만 일괄삭제" || $_POST['act_button'] == "취소후 일괄삭제") {

    auth_check($auth[$sub_menu], 'w'); 	
	
	$batch_pass=trim($_POST[batch_pass]);
	$batch_div=trim($_POST[batch_div]);
	
	if(!check_password($batch_pass, $member['mb_password'])) {
		alert("암호가 옳바르지 않습니다");
	}
	
	if($tr_stats_batch=='') alert("변경할 상태를 선택하세요");
	
	$sql_common = " from {$g5['cn_item_trade']} as a 
	left outer join  {$g5['cn_item_cart']} as c on(a.cart_code=c.code) 
	left outer join  {$g5['member_table']} as b on(a.mb_id=b.mb_id) ";

	$sql = " select a.* {$sql_common} {$sql_search} limit $batch_div";
	$result = sql_query($sql);

	 for ($i=0; $row=sql_fetch_array($result); $i++) {
		 
		if ($_POST['act_button'] == "일괄변경" ){
			set_trade_stat($row ,$tr_stats_batch);	
			
		}else if ($_POST['act_button'] == "거래정보만 일괄삭제" ){
			sql_query("delete from {$g5['cn_item_trade']} where tr_code='$row[tr_code]'");
			
		}else if ($_POST['act_button'] == "취소후 일괄삭제" ){
			set_trade_stat($row ,'del');	
		}
	}


		
}else if ($_POST['act_button'] == "패널티실행") {

    auth_check($auth[$sub_menu], 'w');
 	
	
	if($select_pt=='all'){
		/*
		$sql_common = " from {$g5['cn_item_trade']} as a 
		left outer join  {$g5['cn_item_cart']} as c on(a.cart_code=c.code) 
		left outer join  {$g5['member_table']} as b on(a.mb_id=b.mb_id) ";

		$sql = " select a.* {$sql_common} {$sql_search} ";
		$result = sql_query($sql);

		 for ($i=0; $row=sql_fetch_array($result); $i++) {
					
			set_trade_penalty($row,$target_pt,$stats_pt,$give_coin,$give_coin_amt,$get_coin,$get_coin_amtt);	
		}
		*/
		
	}else{
	
		for ($i=0; $i<count($_POST['chk']); $i++) {
			// 실제 번호를 넘김
			$k = $_POST['chk'][$i];

			// include 전에 $bo_table 값을 반드시 넘겨야 함
			$tmp_tr_code = trim($_POST['tr_code'][$k]);
						
			$re=set_trade_penalty($tmp_tr_code,$target_pt,$stats_pt,$give_coin,$give_coin_amt,$get_coin,$get_coin_amt);	
			
		}
	}

}else if ($_POST['act_button'] == "선택수정") {

    auth_check($auth[$sub_menu], 'w');

    for ($i=0; $i<count($_POST['chk']); $i++) {

        // 실제 번호를 넘김
        $k = $_POST['chk'][$i];		
		$pt_no=$_POST['pt_no'][$k];		
		$tr_code        =  $_POST['tr_code'][$k];
		$tr_stats        =  $_POST['tr_stats'][$k];
			
        set_trade_stat($tr_code ,$tr_stats_all?$tr_stats_all:$tr_stats);
        
    }

} else if ($_POST['act_button'] == "선택삭제") {

    auth_check($auth[$sub_menu], 'd');
   
    for ($i=0; $i<count($_POST['chk']); $i++) {
        // 실제 번호를 넘김
        $k = $_POST['chk'][$i];

        // include 전에 $bo_table 값을 반드시 넘겨야 함
        $tmp_tr_code = trim($_POST['tr_code'][$k]);
		
		set_trade_stat($tmp_tr_code,'del');	
		
       
    }


}

goto_url('./item_trade_list.php?'.$qstr);
?>