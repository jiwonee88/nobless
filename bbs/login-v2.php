<?php
include_once('./_common.php');

/*
if( function_exists('social_check_login_before') ){
    $social_login_html = social_check_login_before();
}
*/

if(preg_match("/\/adm/",$url)){
	include "./login.adm.php";
	return;
}//

$g5['title'] = 'LOGIN';
include_once('./_head.sub-v2.php');

$url = strip_tags($_GET['url']);

// url 체크
check_url_host($url);



// 이미 로그인 중이라면
if ($is_member) {
    if ($url)
        goto_url($url);
    else
        goto_url(G5_URL);
}

$login_url        = login_url($url);
$login_action_url = G5_HTTPS_BBS_URL."/login_check.php";

// 로그인 스킨이 없는 경우 관리자 페이지 접속이 안되는 것을 막기 위하여 기본 스킨으로 대체
$login_file = $member_skin_path.'/login.skin.php';
if (!file_exists($login_file))
    $member_skin_path   = G5_SKIN_PATH.'/member/basic';

include_once($member_skin_path.'/login.skin-v2.php');

include_once('./_tail.sub.php');
?>
