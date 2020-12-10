<?php
include_once('./_common.php');

// 봇의 메일 링크 크롤링을 방지합니다.
if(function_exists('check_mail_bot')){ check_mail_bot($_SERVER['REMOTE_ADDR']); }

$msg='';
$mb_id  = trim($_GET['mb_id']);
$mb_md5 = trim($_GET['mb_md5']);

$sql = " select mb_id, mb_email_certify2, mb_leave_date, mb_intercept_date,mb_email_certify from {$g5['member_table']} where mb_id = '{$mb_id}' ";
$row = sql_fetch($sql);
if (!$row['mb_id']){
   $msg='You are not a member.';
   include  $member_skin_path.'/email_certify.skin.php';   exit;
}
if ( $row['mb_leave_date'] || $row['mb_intercept_date'] ){
    $msg="You are a withdrawn or blocked member.";
	include  $member_skin_path.'/email_certify.skin.php';   exit;
}

//이미 인증 여부
if (substr($row['mb_email_certify'],0,1)!=0) {
    $msg="You have already verified mail.";
	include  $member_skin_path.'/email_certify.skin.php';   exit;
}

// 인증 링크는 한번만 처리가 되게 한다.
sql_query(" update {$g5['member_table']} set mb_email_certify2 = '' where mb_id = '$mb_id' ");

if ($mb_md5)
{
    if ($mb_md5 == $row['mb_email_certify2'])
    {
        sql_query(" update {$g5['member_table']} set mb_email_certify = '".G5_TIME_YMDHIS."' where mb_id = '{$mb_id}' ");

        $msg="E-mail authentication has been completed. <br><br>You can log in with your {$mb_email} e-mail from now on.";
		include  $member_skin_path.'/email_certify.skin.php';   exit;
    }
    else
    {
        $msg="The mail authentication request information is incorrect.";
		include  $member_skin_path.'/email_certify.skin.php';   exit;
    }
}

$msg="The correct value has not been exceeded.";

include  $member_skin_path.'/email_certify.skin.php';
?>