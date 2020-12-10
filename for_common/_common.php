<?php
include_once ('../common.php');

if(!$is_member) goto_url("/bbs/login.php");

//회원 전체 포인트 정보
if($member) $rpoint=get_mempoint($member['mb_id']);

//시세 정보
$sise=get_sise();

?>