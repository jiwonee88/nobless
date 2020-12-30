<?php
$sub_menu = "800400";
include_once('./_common.php');

$qstr.="&mb_stx=$mb_stx";

if ($w == 'u')
    check_demo();

auth_check($auth[$sub_menu], 'w');

check_admin_token();

$amount=preg_replace("/[^0-9.]/","",$_POST['amount']);

if ($w==''){
	if(!$_POST['mb_id']) { alert('회원아이디는 반드시 입력하세요.'); }

	$mb=get_member($_POST['mb_id']);
	if (!$mb) { alert('회원을 찾을수 없습니다.'); }
	
	$smb=get_member($_POST['smb_id']);
	if (!$smb) { alert('회원을 찾을수 없습니다.'); }
	
	$point=get_mempoint($_POST['smb_id']);
	if($point['tot_earn'] < $amount ) { alert('지급액이 부족합니다.'); }
}

 
$sql_common = " 
				pt_kind='{$_POST['pt_kind']}',
                coin	= '{$_POST['coin']}',
				account	= '{$_POST['account']}',
				amount          = '{$amount}',
				deposit_date          = '{$_POST['deposit_date']}',
				subject          = '{$_POST['subject']}',
				comment          = '{$_POST['comment']}'
				";
				
if ($w == '') {
    $sql = " insert into {$g5['cn_pt_table']}
                set 					
					mb_id     = '{$_POST['mb_id']}',
					smb_id     = '{$_POST['smb_id']}',
                    wdate = now(),
                    $sql_common ";
					
    $result=sql_query($sql);
	
	if($result){
		$pt_no=sql_insert_id();		
		
		 $data= get_pointtrans($pt_no);


		set_pointtrans($data,'add');
	}

} else if ($w == 'u') {
	
    $data= get_pointtrans($pt_no);
	
    $sql = " update {$g5['cn_pt_table']} set 
	          {$sql_common}
              where pt_no = '{$pt_no}' ";
    $result=sql_query($sql);
	
	//금액 변경시, 구분 변경시
	if($data['amount'] != $amount || $data['pt_kind'] != $pt_kind){
		
		$point=get_mempoint($data['smb_id']);
		if($point['tot_earn'] < ($amount -$data['amount']))   { alert('지급액이 부족합니다.'); }	
		set_pointtrans($data,'delete');
		
		$data['pt_kind']=$pt_kind;
		$data['amount']=$amount;
		set_pointtrans($data,'add');
	}
	
}

if(function_exists('get_admin_captcha_by'))
    get_admin_captcha_by('remove');

goto_url("./point_form.php?w=u&pt_no={$pt_no}&amp;{$qstr}");
?>