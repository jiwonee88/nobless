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

if($w == 'u')
    $mb_id = isset($_SESSION['ss_mb_id']) ? trim($_SESSION['ss_mb_id']) : '';
else
    alert('잘못된 접근입니다', G5_URL);

if(!$mb_id)
    alert('회원아이디 값이 없습니다. 올바른 방법으로 이용해 주십시오.');

$mb_password    = trim($_POST['mb_password']);
$new_password = trim($_POST['new_password']);
$new_password_re = trim($_POST['new_password_re']);


if ($msg = empty_mb_id($mb_id))         alert($msg, "", true, true); // alert($msg, $url, $error, $post);


if (!$mb_password)
	alert('비밀번호가 넘어오지 않았습니다.');
if($new_password != $new_password_re)
	alert('새로운 비밀번호가 일치하지 않습니다.');



if (!trim($_SESSION['ss_mb_id']))
	alert('로그인 되어 있지 않습니다.');

if (trim($_POST['mb_id']) != $mb_id)
	alert("로그인된 정보와 수정하려는 정보가 틀리므로 수정할 수 없습니다.\\n만약 올바르지 않은 방법을 사용하신다면 바로 중지하여 주십시오.");

$tmp_password = get_encrypt_string($_POST['mb_password']);
	
$tmp_password_new = get_encrypt_string($_POST['new_password']);
	
if ($member['mb_password'] != $tmp_password)	alert('비밀번호가 틀립니다.');
if ($tmp_password_new == $tmp_password)	alert('현재암호와 새로운 암호가 동일합니다.');
	

$sql = " update {$g5['member_table']}
			set mb_password = '".get_encrypt_string($new_password)."'   where mb_id = '$mb_id' ";
			  
sql_query($sql);

alert('비밀번호가 수정 되었습니다.', G5_URL."/for_partner/mypage_intro.php");
?>
