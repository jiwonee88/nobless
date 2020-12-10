<?php
include_once('./_common.php');
include_once(G5_CAPTCHA_PATH.'/captcha.lib.php');
include_once(G5_LIB_PATH.'/mailer.lib.php');

$mb_id = substr(clean_xss_tags($_POST['mb_id']), 0, 20);
$mb_email = get_email_address(trim($_POST['mb_email']));

if(!$mb_id || !$mb_email)
    alert_json(false,'Please use it in the right way.', G5_URL);

$sql = " select mb_name from {$g5['member_table']} where mb_id = '{$mb_id}' and substring(mb_email_certify, 1, 1) = '0' ";
$mb = sql_fetch($sql);
if (!$mb) {
    alert_json(false,"Members who have already verified mail.", G5_URL);
}

/*
if (!chk_captcha()) {
    //alert_json(false,'자동등록방지 숫자가 틀렸습니다.');
}
*/

$sql = " select count(*) as cnt from {$g5['member_table']} where mb_id <> '{$mb_id}' and mb_email = '$mb_email' ";
$row = sql_fetch($sql);
if ($row['cnt']) {
    alert_json(false,"{$mb_email} E-mail is an existing e-mail address. \nPlease enter another e-mail address.");
}

// 인증메일 발송
$subject = '['.$config['cf_title'].'] This is the confirmation email.';

$mb_name = $mb['mb_name'];

// 어떠한 회원정보도 포함되지 않은 일회용 난수를 생성하여 인증에 사용
$mb_md5 = md5(pack('V*', rand(), rand(), rand(), rand()));

sql_query(" update {$g5['member_table']} set mb_email_certify2 = '$mb_md5' where mb_id = '$mb_id' ");

$certify_href = G5_BBS_URL.'/email_certify.php?mb_id='.$mb_id.'&amp;mb_md5='.$mb_md5;

ob_start();
include_once ('./register_form_update_mail3.php');
$content = ob_get_contents();
ob_end_clean();

mailer($config['cf_admin_email_name'], $config['cf_admin_email'], $mb_email, $subject, $content, 1);

$sql = " update {$g5['member_table']} set mb_email = '$mb_email' where mb_id = '$mb_id' ";
sql_query($sql);

alert_json(true,"We have sent the verification email to {$mb_email} again. \nPlease check the {$mb_email} email after a while.", G5_URL);
?>