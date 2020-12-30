<?php
include_once('./_common.php');
include_once(G5_CAPTCHA_PATH.'/captcha.lib.php');
include_once(G5_LIB_PATH.'/mailer.lib.php');
include_once(G5_LIB_PATH.'/register.lib.php');

if ($is_member) {
    alert('이미 로그인중입니다.', G5_URL);
}


if (!chk_captcha()) {
    //alert('자동등록방지 숫자가 틀렸습니다.');
}

$g5['title'] = '회원정보 찾기';
include_once(G5_PATH.'/head.php');


$msg='';

//아이디 찾기
if($find_type=='id'){

	$return_url=G5_BBS_URL."/password_lost.php?find_type=id";
	$return_title="다시 찾기";
	
	//이메일을 통한 검색
	if($findid_method=='mb_email' && !$msg){
		
		$mb_name=trim($mb_name2);
	
	
		if (!$mb_name) $msg='이름을 입력하세요.';
	
		 if(!$msg){
			$email = get_email_address(trim($_POST['mb_email']));
			if (!$email) $msg='메일주소 오류입니다.';
		 }
		 if(!$msg){
			$sql = " select count(*) as cnt from {$g5['member_table']} where mb_email = '$email' ";
			$row = sql_fetch($sql);
			if ($row['cnt'] > 1)
				$msg='동일한 메일주소가 2개 이상 존재합니다.<br/><br/>관리자에게 문의하여 주십시오.';
		}
		$sql=" and mb_email = '$email' ";
		
	//휴대전화를 통한 검색
	}else if($findid_method=='mb_hp' && !$msg) {
		$mb_name=trim($mb_name);
	
		if (!$mb_name) $msg='이름을 입력하세요.';
	
	
		$mb_hp=only_number($mb_hp1).only_number($mb_hp2).only_number($mb_hp3);		
	
		$sql = " select count(*) as cnt from {$g5['member_table']} where replace(mb_hp,'-','') = '$mb_hp' ";
		$row = sql_fetch($sql);
		if ($row['cnt'] > 1)
			$msg='동일한 휴대전화 번호가  2개 이상 존재합니다.<br/><br/>관리자에게 문의하여 주십시오.';
				
		$sql=" and replace(mb_hp,'-','') = '$mb_hp'  ";		
		
	}else if(!$msg){
		$msg='아이디를 찾는 방법을 선택하세요.';	
	}
	
	if(!$msg){
		
		if($sql) {
			$sql = " select * from {$g5['member_table']} where  mb_name='$mb_name' $sql ";
			$mb = sql_fetch($sql);
		}

		if (!$mb['mb_id']){
			$msg='존재하지 않는 회원입니다.';
		}else if (is_admin($mb['mb_id'])){
			$msg='관리자 아이디는 접근 불가합니다.';
		}else{ 
			$msg="회원님의 아이디는 <span>".substr($mb['mb_id'],0,-3)."***</span> 입니다.";	
			
			$return_url=G5_BBS_URL."/login.php";
			$return_title="로그인";
		}
	}
		
	
}

//암호 찾기
if($find_type=='pass'){
	
	$repassword_enable=false;
	
	$msg = empty_mb_id($mb_id);
   	
	if(!$msg) $msg = valid_mb_id($mb_id);

	
	$return_url=G5_BBS_URL."/password_lost.php?find_type=pass";
	$return_title="다시 찾기";
	
	//이메일을 통한 검색
	if($findpw_method=='mb_email' && !$msg){
		
		$mb_id=trim($mb_id2);		
		if (!$mb_id) $msg='아이디를 입력하세요.';
	
		
		 if(!$msg){
			$mb_name=trim($mb_name2);	
			if (!$mb_name) $msg='이름을 입력하세요.';
		}	
	
		 if(!$msg){
			$email = get_email_address(trim($_POST['mb_email']));
			if (!$email)	$msg='메일주소 오류입니다.';			
		 }
		
		 if(!$msg){
			$sql = " select count(*) as cnt from {$g5['member_table']} where mb_email = '$email' ";
			$row = sql_fetch($sql);
			if ($row['cnt'] > 1)
				$msg='동일한 메일주소가 2개 이상 존재합니다.<br/><br/>관리자에게 문의하여 주십시오.';
		}
			
		$sql=" and mb_email = '$email' ";
		
	//휴대전화를 통한 검색
	}else if($findpw_method=='mb_hp' && !$msg) {
		
		$mb_id=trim($mb_id);		
		if (!$mb_id) $msg='아이디를 입력하세요.';
		
		 if(!$msg){
			$mb_name=trim($mb_name);	
			if (!$mb_name) $msg='이름을 입력하세요.';
		 }
		if(!$msg){
			$mb_hp=only_number($mb_hp1).only_number($mb_hp2).only_number($mb_hp3);		
		
			$sql = " select count(*) as cnt from {$g5['member_table']} where replace(mb_hp,'-','') = '$mb_hp' ";
			$row = sql_fetch($sql);
			if ($row['cnt'] > 1)
				$msg='동일한 휴대전화 번호가  2개 이상 존재합니다.<br/><br/>관리자에게 문의하여 주십시오.';
		}
				
		$sql=" and replace(mb_hp,'-','') = '$mb_hp'  ";		
		
	}else if(!$msg){
		
		$msg='암호를 찾는 방법을 선택하세요.';	
	}
	
	if(!$msg){
		
		if($sql) {
			$sql = " select * from {$g5['member_table']} where  mb_id='$mb_id'  and  mb_name='$mb_name'  $sql ";
			$mb = sql_fetch($sql);
		}

		if (!$mb['mb_id']){
			$msg='존재하지 않는 회원입니다.';
		}else if (is_admin($mb['mb_id'])){
			$msg='관리자 아이디는 접근 불가합니다.';
		}else{ 
			$msg="회원님의 아이디는 <span>".substr($mb['mb_id'],0,-3)."***</span> 입니다.";	
			
			$repassword_enable=true;
			$return_url=G5_BBS_URL."/login.php";
			$return_title="다음";
		}
	}
	
}




include_once($member_skin_path.'/password_lost2.skin.php');

//alert_close($email.' 메일로 회원아이디와 비밀번호를 인증할 수 있는 메일이 발송 되었습니다.\\n\\n메일을 확인하여 주십시오.');

include_once(G5_PATH.'/tail.php');

?>
