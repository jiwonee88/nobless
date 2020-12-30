<?php
include_once('./_common.php');
include_once(G5_CAPTCHA_PATH.'/captcha.lib.php');

$g5['title'] = 'E-mail address change';
include_once('./_head.sub.php');

$mb_id = substr(clean_xss_tags($_GET['mb_id']), 0, 20);
$sql = " select mb_email, mb_datetime, mb_ip, mb_email_certify from {$g5['member_table']} where mb_id = '{$mb_id}' ";
$mb = sql_fetch($sql);
if (substr($mb['mb_email_certify'],0,1)!=0) {
   // alert("Members who have already verified mail.", G5_URL);
}

$ckey = trim($_GET['ckey']);
$key  = md5($mb['mb_ip'].$mb['mb_datetime']);

if(!$ckey || $ckey != $key)
  //  alert('Please use it in the right way.', G5_URL);
	
// 로그인 스킨이 없는 경우 관리자 페이지 접속이 안되는 것을 막기 위하여 기본 스킨으로 대체
include  $member_skin_path.'/register_email.skin.php';

include_once('./_tail.sub.php');
?>
