<?php
$menu['menu200'] = array (
    array('200000', '회원관리', G5_ADMIN_URL.'/member_list_partner.php', 'member'),	
	
	//array('200140', '본사직원', G5_ADMIN_URL.'/member_list_adm.php', 'mb_list'),
	
	array('200120', '회원', G5_ADMIN_URL.'/member_list_partner.php', 'mb_list'),
	array('200150', '임시회원', G5_ADMIN_URL.'/member_list_ready.php', 'mb_list'),
	array('200130', '회원탈퇴/삭제', G5_ADMIN_URL.'/member_list_leave.php', 'mb_list'),
	
	array('200400', '계정관리', G5_ADMIN_URL.'/member_avatar_list.php', 'member'),		
	
    array('200300', '회원메일발송', G5_ADMIN_URL.'/mail_list.php', 'mb_mail'),
    array('200800', '접속자집계', G5_ADMIN_URL.'/visit_list.php', 'mb_visit', 1),
    array('200810', '접속자검색', G5_ADMIN_URL.'/visit_search.php', 'mb_search', 1),
    array('200820', '접속자로그삭제', G5_ADMIN_URL.'/visit_delete.php', 'mb_delete', 1),
    //array('200200', '포인트관리', G5_ADMIN_URL.'/point_list.php', 'mb_point'),
    array('200900', '투표관리', G5_ADMIN_URL.'/poll_list.php', 'mb_poll')
);
?>