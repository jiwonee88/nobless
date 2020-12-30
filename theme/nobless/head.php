<?php
if (!defined('_GNUBOARD_')) {
    exit;
} // 개별 페이지 접근 불가


//게시판
if ($bo_table) {
    $docu_title=$board['bo_subject'];
}
if ($docu_title=='') {
    $docu_title=$g5[title];
}

include_once(G5_THEME_PATH.'/head.sub.php');
include_once(G5_LIB_PATH.'/latest.lib.php');
include_once(G5_LIB_PATH.'/outlogin.lib.php');
include_once(G5_LIB_PATH.'/poll.lib.php');
include_once(G5_LIB_PATH.'/visit.lib.php');
include_once(G5_LIB_PATH.'/connect.lib.php');
include_once(G5_LIB_PATH.'/popular.lib.php');

$last_date=date("Y-m-d", strtotime('-1 days'));


//구매내역 카운트
$buyer_stats=sql_fetch("select 
sum(if(tr_stats='1',1,0)) cnt_stats_1,
sum(if(tr_stats='2',1,0)) cnt_stats_2,
sum(if(tr_wdate>='$last_date' and tr_stats='3',1,0)) cnt_stats_3,
sum(if(tr_stats='9',1,0)) cnt_stats_9,
sum(if(	tr_buyer_claim='1' and tr_stats!='3' and tr_stats!='9',1,0)) buyer_claim,
sum(if(	tr_seller_claim='1'  and tr_stats!='3' and tr_stats!='9',1,0)) seller_claim,
sum(if(	(tr_buyer_claim='1' or tr_seller_claim='1')  and tr_stats!='3'  and tr_stats!='9',1,0)) all_claim
from {$g5['cn_item_trade']} where mb_id='$member[mb_id]'", 1);

//판매내역 카운트
$seller_stats=sql_fetch("select 
sum(if(tr_stats='1',1,0)) cnt_stats_1,
sum(if(tr_stats='2',1,0)) cnt_stats_2,
sum(if(tr_wdate>='$last_date' and tr_stats='3',1,0)) cnt_stats_3,
sum(if(tr_stats='9',1,0)) cnt_stats_9,
sum(if(	tr_buyer_claim='1' and tr_stats!='3' and tr_stats!='9',1,0)) buyer_claim,
sum(if(	tr_seller_claim='1' and tr_stats!='3'  and tr_stats!='9',1,0)) seller_claim,
sum(if(	(tr_buyer_claim='1' or tr_seller_claim='1')  and tr_stats!='3' and tr_stats!='9' ,1,0)) all_claim
from {$g5['cn_item_trade']} where fmb_id='$member[mb_id]'", 1);




?>
<!DOCTYPE html>
<html lang="ko" xml:lang="ko" xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="initial-scale=1.0, width=device-width" />

<meta name="title" content="NOBLESS"> 
<meta name="author" content="http://"> 
<meta name="description" content="NOBLESS"> 
<meta name="subject" content="NOBLESS"> 
<meta name="keywords" content="NOBLESS"> 
<meta property="og:type" content="website"> 
<meta property="og:title" content="NOBLESS"> 
<meta property="og:subject" content="NOBLESS"> 
<meta property="og:description" content="NOBLESS"> 
<meta property="og:image" content="http://"> 
<link rel="canonical" href="http://"> 
<meta property="al:web:url" content="http://">
<link rel="shortcut icon" href="http://">


<title>NOBLESS</title>


<!-- js -->
<script type="text/javascript" src="<?=G5_THEME_URL?>/js/jquery-1.12.4.min.js"></script>
<script type="text/javascript" src="<?=G5_THEME_URL?>/js/basic.js"></script>


</head>
<body>

<div id="wrap">

<script language="javascript">
function GoPg(code) {

	if ( !code )						{	window.location = "/";	}

	else if ( code == "main" )			{	window.location = "/index.php";		} //메인
	
}
</script>
<header id="hd_wrap">
	<div class="hd_inner">
		<ul class="hd_left">
			<!-- <li class="hd_full"><a href="#"><img src="<?=G5_THEME_URL?>/images/hd_icon1.png" /> 메뉴</a></li> -->
			<li class=""><a href="/"><img src="<?=G5_THEME_URL?>/images/hd_icon3.png" /> 홈</a></li>
			<li class=""><a href="/"><img src="<?=G5_THEME_URL?>/images/hd_icon2.png" /> 이용가이드</a></li>
		</ul>

		<div class="hd_right">
			<a href="/bbs/logout.php"><img src="<?=G5_THEME_URL?>/images/hd_icon4.png" /> 로그아웃</a>
		</div>
	</div>
</header>

<div id="gnb_mo" class="hd_full_wrap">
	<div class="hd_full">
		<span class="m1"></span>
	</div>
	<?php include "menu.php" ?>
</div>
<div id="gnb_bg"></div>





