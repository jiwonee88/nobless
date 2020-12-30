<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가


//게시판
if($bo_table) $docu_title=$board['bo_subject'];
if($docu_title=='') $docu_title=$g5[title];

include_once(G5_THEME_PATH.'/head.sub-v2.php');
include_once(G5_LIB_PATH.'/latest.lib.php');
include_once(G5_LIB_PATH.'/outlogin.lib.php');
include_once(G5_LIB_PATH.'/poll.lib.php');
include_once(G5_LIB_PATH.'/visit.lib.php');
include_once(G5_LIB_PATH.'/connect.lib.php');
include_once(G5_LIB_PATH.'/popular.lib.php');

$last_date=date("Y-m-d",strtotime('-1 days'));


//구매내역 카운트
$buyer_stats=sql_fetch("select 
sum(if(tr_stats='1',1,0)) cnt_stats_1,
sum(if(tr_stats='2',1,0)) cnt_stats_2,
sum(if(tr_wdate>='$last_date' and tr_stats='3',1,0)) cnt_stats_3,
sum(if(tr_stats='9',1,0)) cnt_stats_9,
sum(if(	tr_buyer_claim='1' and tr_stats!='3' and tr_stats!='9',1,0)) buyer_claim,
sum(if(	tr_seller_claim='1'  and tr_stats!='3' and tr_stats!='9',1,0)) seller_claim,
sum(if(	(tr_buyer_claim='1' or tr_seller_claim='1')  and tr_stats!='3'  and tr_stats!='9',1,0)) all_claim
from {$g5['cn_item_trade']} where mb_id='$member[mb_id]'",1);

//판매내역 카운트
$seller_stats=sql_fetch("select 
sum(if(tr_stats='1',1,0)) cnt_stats_1,
sum(if(tr_stats='2',1,0)) cnt_stats_2,
sum(if(tr_wdate>='$last_date' and tr_stats='3',1,0)) cnt_stats_3,
sum(if(tr_stats='9',1,0)) cnt_stats_9,
sum(if(	tr_buyer_claim='1' and tr_stats!='3' and tr_stats!='9',1,0)) buyer_claim,
sum(if(	tr_seller_claim='1' and tr_stats!='3'  and tr_stats!='9',1,0)) seller_claim,
sum(if(	(tr_buyer_claim='1' or tr_seller_claim='1')  and tr_stats!='3' and tr_stats!='9' ,1,0)) all_claim
from {$g5['cn_item_trade']} where fmb_id='$member[mb_id]'",1);

?>

	<div class="container main <?=$outer_css?$outer_css:' main_opacity '?> ">            
        <div class="header">
            <div class="header_top">           
                <div class="logo">
                    <span class="NAVI">NAVI</span>
                </div>
                <div class="menuBtn"><img src="<?=G5_THEME_URL?>/images/menu.png" alt="메뉴버튼" /></div>
            </div>
        </div>
		
