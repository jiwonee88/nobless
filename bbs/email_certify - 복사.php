<?php
include_once('./_common.php');

// 봇의 메일 링크 크롤링을 방지합니다.
if(function_exists('check_mail_bot')){ check_mail_bot($_SERVER['REMOTE_ADDR']); }

$mb_id  = trim($_GET['mb_id']);
$mb_md5 = trim($_GET['mb_md5']);

$sql = " select mb_id, mb_email_certify2, mb_leave_date, mb_intercept_date from {$g5['member_table']} where mb_id = '{$mb_id}' ";
$row = sql_fetch($sql);
if (!$row['mb_id'])
    alert('You are not a member.', G5_URL);

if ( $row['mb_leave_date'] || $row['mb_intercept_date'] ){
    alert('You are a withdrawn or blocked member.', G5_URL);
}

// 인증 링크는 한번만 처리가 되게 한다.
sql_query(" update {$g5['member_table']} set mb_email_certify2 = '' where mb_id = '$mb_id' ");

if ($mb_md5)
{
    if ($mb_md5 == $row['mb_email_certify2'])
    {
        sql_query(" update {$g5['member_table']} set mb_email_certify = '".G5_TIME_YMDHIS."' where mb_id = '{$mb_id}' ");

        alert("E-mail authentication has been completed. \\n\\nYou can log in with your {$mb_email} e-mail from now on.", G5_URL);
    }
    else
    {
        alert('The mail authentication request information is incorrect.', G5_URL);
    }
}

alert('The correct value has not been exceeded.', G5_URL);

include  $member_skin_path.'/email_certify.skin.php';
?>