<?php
define('_INDEX_', true);
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

if (G5_IS_MOBILE) {
    include_once(G5_THEME_MOBILE_PATH.'/index.php');
    return;
}

include_once(G5_THEME_PATH.'/head-v2.php');

$rpoint=get_mempoint($member[mb_id]);
$isum=get_itemsum($member[mb_id]);
?>
<?php
//if(defined('_INDEX_')) { // index에서만 실행
	include G5_THEME_PATH.'/newwin.inc.php'; // 팝업레이어
//}
?>
        <div class="wrap"> 

            <div class="area pb-1" >
                <h3><span>About you</span></h3>
                <ul class="aboutYou">
				<li class="squareWB hero" ><a href='/for_common/idDetail.php'><span><?=$member[mb_id]?></span></a></li>
				<li class="squareWB stone"><a href='/for_common/fee.php'><span><?=number_format2($rpoint['i']['_enable'])?></span></a></li>
                </ul>
			</div>
            
		</div>

		<div class="wrap mt-0"> 
		
            <div class="area">
                <h3><span>구매내역</span></h3>
                <ul class="buy">
				<li class="squareWB"><a href='/for_common/incomplete.php?stats_stx=1-1'><span class="f_yellow"><?=$buyer_stats[cnt_stats_1]>99?'+99':($buyer_stats[cnt_stats_1]?$buyer_stats[cnt_stats_1]:0)?>건</span><span class="condition">미처리</span></a></li>
                    <li class="squareWB"><a href='/for_common/incomplete.php?stats_stx=1-2'><span class="f_yellow"><?=$buyer_stats[cnt_stats_2]>99?'+99':($buyer_stats[cnt_stats_2]?$buyer_stats[cnt_stats_2]:0)?>건</span><span class="condition">완료대기</span></a></li>
                    <li class="squareWB"><a href='/for_common/incomplete.php?stats_stx=1-bad'><span class="f_yellow"><?=$buyer_stats[all_claim]>99?'+99':($buyer_stats[all_claim]?$buyer_stats[all_claim]:0)?>건</span><span class="condition">신고</span></a></li>
                    <li class="squareWB"><a href='/for_common/incomplete.php?stats_stx=1-3'><span class="f_yellow"><?=$buyer_stats[cnt_stats_3]>99?'+99':($buyer_stats[cnt_stats_3]?$buyer_stats[cnt_stats_3]:0)?>건</span><span class="condition confirm">완료</span></a></li>
                </ul>
            </div>     
            <div class="area sellList">
                <h3><span>판매내역</span></h3>
                <ul class="buy">
                    <li class="squareWB"><a href='/for_common/incomplete.php?stats_stx=2-1'><span class="f_yellow"><?=$seller_stats[cnt_stats_1]>99?'+99':($seller_stats[cnt_stats_1]?$seller_stats[cnt_stats_1]:0)?>건</span><span class="condition">미처리</span></a></li>
                    <li class="squareWB"><a href='/for_common/incomplete.php?stats_stx=2-2'><span class="f_yellow"><?=$seller_stats[cnt_stats_2]>99?'+99':($seller_stats[cnt_stats_2]?$seller_stats[cnt_stats_2]:0)?>건</span><span class="condition">완료대기</span></a></li>                    
                    <li class="squareWB"><a href='/for_common/incomplete.php?stats_stx=2-bad'><span class="f_yellow"><?=$seller_stats[all_claim]>99?'+99':($seller_stats[all_claim]?$seller_stats[all_claim]:0)?>건</span><span class="condition">신고</span></a></li>
                    <li class="squareWB"><a href='/for_common/incomplete.php?stats_stx=2-3'><span class="f_yellow"><?=$seller_stats[cnt_stats_3]>99?'+99':($seller_stats[cnt_stats_3]?$seller_stats[cnt_stats_3]:0)?>건</span><span class="condition confirm">완료</span></a></li>
                </ul>
                <ul class="buy  sell">
				<li class="squareWB"><a href='/for_common/idDetail.php'><span class="f_yellow price text-narrow0">$ <?=number_format2($member[mb_trade_amtlmt])?></span><span class="condition f_pink">설정금액</span></a></li>
				<li class="squareWB"><a href='/for_common/idDetail.php'><span class="f_yellow price  text-narrow0">$ <?=number_format2($isum[tot][price]>$member[mb_trade_amtlmt]?0:$member[mb_trade_amtlmt]-$isum[tot][price])?></span><span class="condition f_pink">가용금액</span></a></li>
					<li class="squareWB"><a href='/for_common/stonedetail.php'><span class="f_yellow price  text-narrow1">$ <?=number_format2($isum[tot][price])?></span><span class="condition confirm">보유금액</span></a></li>
                </ul>
                <div class="auto"><a href="/for_common/automatching.php"><img src="<?=G5_THEME_URL?>/images/autoMaching.png" alt="automaching"/></a></div>
            </div>   
             <div class="area shingStone">
                <h3><span>샤이닝 스톤</span></h3> 
                <ul class="stoneList" onclick="document.location.href='/for_common/stonedetail.php'">
					
					<?
				foreach($g5['cn_item'] as $k=>$v){?>
				
                    <li class="squareWB">
                        <h4><?=$v[name_kr]?></h4>
                        <div class="clearfix">
                            <div class="stoneImg f_left">
                                <img src="<?=G5_THEME_URL?>/images/stone/<?=$v[img]?>" alt='<?=$v[name_kr]?>' >
                            </div>
                            <div class="stoneDesc f_left">
                                <ul>
                                    <li>보유량:<?=$isum[$k][cnt]?$isum[$k][cnt]:0?>개</li>
                                    <li>판매대기:<?=$v[days]?>일</li>
                                    <li>판매시수익:<?=$v[interest]?>%</li>
                                </ul>
                                <div class="stone"><span><?=$v[fee]?></span></div>
                            </div>
                        </div>
					<div class='text-center mb-2'>
					판매가격 : $<?=$v[price]?> ~ $<?=$v[mxprice]?> 
					</div>
                   </li>
				<? }?>   
				
                </ul> 
            </div>
            <div class="symbol"><img src="<?=G5_THEME_URL?>/images/star-group-bold.gif" alt="" /></div>
        </div>
	
	

<?php
include_once(G5_THEME_PATH.'/tail.php');
?>