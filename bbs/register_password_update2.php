<?php
include_once('./_common.php');
include_once(G5_CAPTCHA_PATH.'/captcha.lib.php');
include_once(G5_LIB_PATH.'/register.lib.php');
include_once(G5_LIB_PATH.'/mailer.lib.php');
include_once(G5_LIB_PATH.'/thumbnail.lib.php');

// 리퍼러 체크
referer_check();

if (!($w == 'u')) {
    alert('w 값이 제대로 넘어오지 않았습니다.');
}


//if (!chk_captcha()) {
//    alert('자동등록방지 숫자가 틀렸습니다.');
//}


if(!$mb_id)
    alert('회원아이디 값이 없습니다. 올바른 방법으로 이용해 주십시오.');
if(!$mb_name)
    alert('회원 이름이 없습니다. 올바른 방법으로 이용해 주십시오.');

if($mb_email=='' && $mb_hp=='')
    alert('이메일 또는 휴대전화 번호가 입력되어야 합니다.');

if($findpw_method=='mb_email' && $mb_email!='') $sql=" and mb_email = '$mb_email' ";
else if($findpw_method=='mb_hp' && $mb_hp!='') $sql=" and mb_hp = '$mb_hp'  ";
else  alert('이메일 또는 휴대전화 번호가 입력되어야 합니다1.');


$new_password = trim($_POST['new_password']);
$new_password_re = trim($_POST['new_password_re']);


if ($msg = empty_mb_id($mb_id))     alert($msg, "", true, true); // alert($msg, $url, $error, $post);


if($new_password != $new_password_re)
	alert('새로운 비밀번호가 일치하지 않습니다.');


$tmp_password = get_encrypt_string($_POST['mb_password']);
	
$tmp_password_new = get_encrypt_string($_POST['new_password']);

	
$sql = " update {$g5['member_table']}
			set mb_password = '".get_encrypt_string($new_password)."'   where mb_id = '$mb_id' and mb_name='$mb_name' $sql ";
			  
sql_query($sql);

//alert('비밀번호가 수정 되었습니다.', G5_URL."/for_partner/mypage_intro.php");
$g5['title'] = '회원정보 찾기';
include_once(G5_PATH.'/head.php');

include_once($member_skin_path.'/password_lost3.skin.php');

include_once(G5_PATH.'/tail.php');
?>
