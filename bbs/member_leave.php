<?php
include_once('./_common.php');

if (!$member['mb_id'])
    alert('회원만 접근하실 수 있습니다.');

if ($is_admin == 'super')
    alert('최고 관리자는 탈퇴할 수 없습니다');

//if (!($_POST['mb_password'] && check_password($_POST['mb_password'], $member['mb_password'])))
//    alert('비밀번호가 틀립니다.');

$g5['title'] = '회원탈퇴';
include_once(G5_PATH.'/head.php');

// 회원탈퇴일을 저장
$date = date("Ymd");
$sql = " update {$g5['member_table']} set mb_leave_date = '{$date}',mb_10='2' where mb_id = '{$member['mb_id']}' ";
sql_query($sql);

// 3.09 수정 (로그아웃)
unset($_SESSION['ss_mb_id']);

if (!$url)
    $url = G5_URL;

//소셜로그인 해제
if(function_exists('social_member_link_delete')){
    social_member_link_delete($member['mb_id']);
}

//alert(''.$member['mb_nick'].'님께서는 '. date("Y년 m월 d일") .'에 회원에서 탈퇴 하셨습니다.', $url);

include_once($member_skin_path.'/member_leave.skin.php');

//alert_close($email.' 메일로 회원아이디와 비밀번호를 인증할 수 있는 메일이 발송 되었습니다.\\n\\n메일을 확인하여 주십시오.');

include_once(G5_PATH.'/tail.php');
?>
