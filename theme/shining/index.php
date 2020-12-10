<?php
define('_INDEX_', true);
if (!defined('_GNUBOARD_')) {
    exit;
} // 개별 페이지 접근 불가

if (G5_IS_MOBILE) {
    include_once(G5_THEME_MOBILE_PATH.'/index.php');
    return;
}

include_once(G5_THEME_PATH.'/head.php');

$rpoint = get_mempoint($member['mb_id']);
$isum = get_itemsum($member['mb_id']);
?>
<?php
//if(defined('_INDEX_')) { // index에서만 실행
    include G5_THEME_PATH.'/newwin.inc.php'; // 팝업레이어
//}
?>


<div id="Contents" class="main_con">
    
    <div id="sec1" class="sec_wrap">
        <img src="<?=G5_THEME_URL?>/images/sec1_img.png" width="100%" />
    </div>
    
    <ul id="sec2" class="sec_wrap sec2_wrap">
        <li class="first"><a href="/for_common/my_url.php">ID : <?=$member[mb_id]?></a></li>
        <li><a href="/for_common/recommender_list.php"><img src="<?=G5_THEME_URL?>/images/sec2_img1.png" /> 파트너</a></li>
        <li><a href="/for_common/accountInfo.php"><img src="<?=G5_THEME_URL?>/images/sec2_img2.png" /> 계좌</a></li>
        <li><a href="/for_common/stonedetail.php"><img src="<?=G5_THEME_URL?>/images/sec2_img3.png" /> 보유금액 : <?=number_format2($rpoint['b']['_enable'])?></a></li>
    </ul>


    <ul id="sec3" class="sec_wrap sec3_wrap">
        <li>
            <?php
            $sql =  "select count(*) as cnt from  {$g5['cn_sub_account']} where mb_id='$member[mb_id]' and ac_auto_a = 1 and ac_auto_b = 1 order by ac_id asc";
            $row = sql_fetch($sql);
            ?>
            <a href="/for_common/automatching.php">
                <div class="img">
                    <div class="in" style="background-image:url(<?=G5_THEME_URL?>/images/sec3_img1.png);">
                        <span class="t"><?=$row['cnt']?>개</span>
                    </div>
                </div>
                <div class="txt">구매예약</div>
            </a>
        </li>
        <li>
            <a href="/for_common/incomplete.php?stats_stx=1-1">
                <div class="img">
                    <div class="in" style="background-image:url(<?=G5_THEME_URL?>/images/sec3_img2.png);">
                        <span class="t"><?=$buyer_stats[cnt_stats_1]?$buyer_stats[cnt_stats_1]:0?>개 가능</span>
                    </div>
                </div>
                <div class="txt">당첨현황</div>
            </a>
        </li>
        <li>
            <a href="/for_common/incomplete.php?stats_stx=2-1">
                <div class="img">
                    <div class="in" style="background-image:url(<?=G5_THEME_URL?>/images/sec3_img3.png);">
                        <span class="t">$2,423</span>
                    </div>
                </div>
                <div class="txt">판매현황</div>
            </a>
        </li>
    </ul>

    <div class="mt2em mb1-5em"><img src="<?=G5_THEME_URL?>/images/sec3_line.png" width="100%" /></div>

    <ul id="sec3" class="sec_wrap sec3_wrap sec3_wrap2">
        <li>
            <a href="/for_common/income_history.php">
                <div class="img">
                    <div class="in" style="background-image:url(<?=G5_THEME_URL?>/images/sec3_img4.png);"></div>
                    <div class="txt">수익내역</div>
                </div>
            </a>
        </li>
        <li>
            <a href="/for_common/fee.php">
                <div class="img">
                    <div class="in" style="background-image:url(<?=G5_THEME_URL?>/images/sec3_img5.png);">
                        <span class="t">보유골드 : 1,235</span>
                    </div>
                    <div class="txt">골드구매</div>
                </div>
            </a>
        </li>
        <li>
            <a href="https://m.navimall.co.kr/" target="_blank">
                <div class="img">
                    <div class="in" style="background-image:url(<?=G5_THEME_URL?>/images/sec3_img6.png);"></div>
                    <div class="txt">쇼핑몰</div>
                </div>
            </a>
        </li>
    </ul>

    <div class="mt2em mb1-5em"><img src="<?=G5_THEME_URL?>/images/sec3_line.png" width="100%" /></div>

    <ul id="sec4" class="sec_wrap sec4_wrap">
        <li><a href="/for_common/matching_memo.php"><img src="<?=G5_THEME_URL?>/images/sec4_img1.png" /></a></li>    
        <li><a href="/bbs/board.php?bo_table=qna"><img src="<?=G5_THEME_URL?>/images/sec4_img2.png" /></a></li>
        <li><a href="/bbs/board.php?bo_table=notice"><img src="<?=G5_THEME_URL?>/images/sec4_img3.png" /></a></li>
    </ul>
</div>

<?php
include_once(G5_THEME_PATH.'/tail.php');
?>
