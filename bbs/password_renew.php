<?php
include_once('./_common.php');
include_once(G5_LIB_PATH.'/register.lib.php');

// 리퍼러 체크
referer_check();

//alert_json(false,'점검중입니다');


if($is_member)  alert_json(false,'이미 로그인 중입니다');

$mb_id = trim($_POST['mb_id']); //$mb_id=get_mbcode();

$mb_password    = trim($_POST['mb_password']);
$mb_password_re = trim($_POST['mb_password_re']);

$mb_nation      = isset($_POST['mb_nation'])            ? trim($_POST['mb_nation'])          : "";
$mb_hp          = isset($_POST['mb_hp'])            ? only_numeric($_POST['mb_hp'])          : "";

$mb_hp_certi = isset($_POST['mb_hp_certi'])            ? trim($_POST['mb_hp_certi'])          : "";

if(!$mb_id)   alert_json(false,'아이디를 입력하세요');
if(!$mb_hp)   alert_json(false,'휴대전화 번호를 입력하세요');
if(!$mb_hp_certi)   alert_json(false,'인증번호를 입력하세요');

//회원이 있는지 검사
$mb=sql_fetch("select * from  {$g5['member_table']} where mb_id='$mb_id' and mb_hp='$mb_hp'");
if(!$mb[mb_id])   alert_json(false,'회원을 찾을수 없습니다. 아이디 및 휴대전화번호를 확인해 주십시요');

$nation_hp="+".$mb_nation.only_numeric($mb_hp);
$data=sql_fetch("select * from  {$g5['cn_hp_certi']} where  mb_id='$mb_id' and  hp='$nation_hp' and pass='$mb_hp_certi' order by wdate desc limit 1 ",1);
if(!$data['hp']) alert_json(false,'인증번호가 옳바르지 않습니다');


if (!$mb_password)
	alert_json(false,'새로운 암호를 입력하세요.');
if($mb_password != $mb_password_re)
	alert_json(false,'입력한 암호가 일치하지 않습니다.');

$sql = " update {$g5['member_table']}
			set mb_password = '".get_encrypt_string($mb_password)."' 

		  where mb_id = '$mb_id' ";


$result=sql_query($sql);


if($result) alert_json(true,'ok');
