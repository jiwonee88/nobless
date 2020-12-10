<?php
include_once('./_common.php');
include_once(G5_LIB_PATH.'/register.lib.php');

$mb_id   = trim($_POST['mb_id']);
$mb_hp   = only_numeric($_POST['hp']);
$mb_nation   = trim($_POST['nation']);
$pass_val   = trim($_POST['pass']);

//if ($msg = valid_mb_hp($mb_hp)) die($msg);

//가입인증
if($mode=='create') {	
	if($is_member) alert_json(false,'이미 회원으로 로그인 중입니다');
	if ($msg = exist_mb_hp($mb_hp, $mb_id)) alert_json(false,$msg);
}

if($mode=='findpass') {	
	//회원이 있는지 검사
	$mb=sql_fetch("select * from  {$g5['member_table']} where mb_id='$mb_id' and mb_hp='$mb_hp'");
	if(!$mb[mb_id])   alert_json(false,'회원을 찾을수 없습니다. 아이디 및 휴대전화번호를 확인해 주십시요');
}

if($mb_id=='') alert_json(false,'회원 아이디를 입력하세요');
if($mb_hp=='') alert_json(false,'휴대전화를 입력하세요');

//$nation_hp="+".$mb_nation.preg_replace("/^0/","",$mb_hp);
$nation_hp="+".$mb_nation.$mb_hp;

if($mode=='create'||$mode=='findpass'){
	
	//하루 경과 삭제
	$deldate=date("Y-m-d H:i:s",strtotime("-1 days"));
	$sdate=date("Y-m-d H:i:s",strtotime("-60 seconds"));
	
	sql_query("delete from  {$g5['cn_hp_certi']} where wdate < '$deldate' ");	
	
	$data=sql_fetch("select * from  {$g5['cn_hp_certi']} where  mb_id='$mb_id' and hp='$nation_hp' and  wdate >= '$sdate' ");
	if($data[hp]) alert_json(false,'1분이내 전송된 인증번호가 있습니다');
	
	$pass=get_randnum(6);		
	
	$params[Body]="인증코드는 $pass 입니다\n".$config[cf_admin_email_name];
	$params[To]=$nation_hp;
	
	//$params[MediaUrl]="https://demo.twilio.com/owl.png";

	$rtn=twilio_sms($params);	
	
	print_r($rtn);
	$result=sql_query("insert into {$g5['cn_hp_certi']} set mb_id='$mb_id', hp='$nation_hp',pass='$pass', wdate=now() ");
	
	if(!$result) alert_json(false,'인증번호생성불가');
	else alert_json(true,'ok');		
	
}else if($mode=='check') {
	
	$ldate=date("Y-m-d H:i:s",strtotime("-5 minutes"));
	
	//echo "select * from  {$g5['cn_hp_certi']} where  hp='$nation_hp' and pass='$pass_val' and  wdate >= '$ldate' ";
	$data=sql_fetch("select * from  {$g5['cn_hp_certi']} where mb_id='$mb_id' and  hp='$nation_hp' and pass='$pass_val' and  wdate >= '$ldate' ",1);

	if(!$data['hp']) alert_json(false,'인증번호가 옳바르지 않습니다');
	else alert_json(true,'ok');

}
//
?>