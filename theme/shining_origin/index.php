<?php
define('_INDEX_', true);
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

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

<style>
.area ul.buy li {
    display: flex;
	justify-content:space-between;
	width:24% !important;
	text-align:center ;
	padding-top: 0.6rem;
	padding-bottom: 0.6rem;
}
.area ul.buy li a {
    width:100%;
	text-align:center ;
}
</style>
    <div style="text-align:center;margin-top:180px;">
        <img src="<?=G5_THEME_URL?>/images/v2/1192.png" class="NAVI-login">
    </div>
    <div class="wrap">

        <div class="area pb-1" >
            <h3><span>About you</span></h3>
            <ul class="aboutYou">
            <li class="squareWB hero" >
                <a href='/for_common/idDetail.php'>
                    <span><?=$member[mb_id]?></span>
                </a>
            </li>
            <li class="squareWB stone"><a href='/for_common/fee.php'><span><?=number_format2($rpoint['i']['_enable'])?></span></a></li>
			<li class="squareWB w-100 mt-2 honey" style='height:auto;' ><span><?=number_format2($rpoint['b']['_enable'])?></span>
			<p>나의 총 꿀단지 포인트 </p>
			</li>
            </ul>
			
<!--			<div class='mt-1 text-center'>-->
<!--			<img src="--><?//=G5_THEME_URL?><!--/images/BlackTransperancy.gif"  alt="automaching" class='w-75 mx-auto' />-->
<!--			</div>-->
        </div>

    </div>
	
	
    <div class="wrap">
		<p class='py-2 text-center'>클릭수 서브 계정별 포인트를 확인 할 수 있습니다.</p>
        <div class="auto mt-1">
            <a href="/for_common/automatching.php"><img src="<?=G5_THEME_URL?>/images/autoMaching.png" alt="automaching"/></a>
        </div>
    </div>


    <?php
	$yester=date('Y-m-d',strtotime("-1 days")) ;
	$today=date('Y-m-d') ;
	$tomo=date('Y-m-d',strtotime("+1 days")) ;
	$tomo2=date('Y-m-d',strtotime("+2 days")) ;
	
    $subadd = set_basic_account($member);
    $accresult = sql_query("select * from  {$g5['cn_sub_account']} where mb_id='{$member['mb_id']}'  order by ac_id asc");
	
	$temp1=sql_fetch("select count(*) cn from  {$g5['cn_item_trade']} where fmb_id='$member[mb_id]' and tr_wdate='$yester' and tr_stats='3'");
	$temp2=sql_fetch("select count(*) cn from  {$g5['cn_item_cart']} where mb_id='$member[mb_id]' and ct_validdate <= '$today' and is_soled!='1' ");
	$temp3=sql_fetch("select count(*) cn from  {$g5['cn_item_cart']} where mb_id='$member[mb_id]' and  ct_validdate = '$tomo' and is_soled!='1' ");
	$temp4=sql_fetch("select count(*) cn from  {$g5['cn_item_cart']} where mb_id='$member[mb_id]' and  ct_validdate = '$tomo2' and is_soled!='1' ");
	
    ?>

    <div class="wrap">
        <ul class="stoneList">
            <li class="squareWB main-my-summary" style="">
				<div>
                    매너포인트 : <span class="emp"><?=number_format2($rpoint['e']['_enable'])?></span>
                </div>
				<div>
                    쇼핑포인트 : <span class="emp"><?=number_format2($rpoint['s']['_enable'])?></span>
                </div>
                <div>
                    보유 캐릭터 : <span class="emp"><?=$isum[tot][cnt]?></span>ea
                </div>
                <div>
                    전일 : <span class="emp"><?=$temp1[cnt]?$temp1[cnt]:'0'?></span>  ea/
                    금일 : <span class="emp"><?=$temp2[cnt]?$temp2[cnt]:'0'?></span> ea
                </div>
                <div>
                    대기 : <span class="emp">D1</span> : <?=$temp3[cnt]?$temp3[cnt]:'0'?> ea /
                    <span class="emp">D2</span class="emp"> : <?=$temp4[cnt]?$temp4[cnt]:'0'?> ea
                </div>
            </li>
        </ul>
    </div>

    <div class="wrap" style="margin-top: 2.5rem!important;">
        <div class="area">
            <h3><span>매수내역</span></h3>
            <ul class="buy">
            <li class="squareWB mb-1"><a href='/for_common/incomplete.php?stats_stx=1-1'><span class="f_yellow"><?=$buyer_stats[cnt_stats_1]>99?'+99':($buyer_stats[cnt_stats_1]?$buyer_stats[cnt_stats_1]:0)?>건</span><span class="condition">매수</span></a></li>
                <li class="squareWB mb-1" ><a href='/for_common/incomplete.php?stats_stx=1-2'><span class="f_yellow"><?=$buyer_stats[cnt_stats_2]>99?'+99':($buyer_stats[cnt_stats_2]?$buyer_stats[cnt_stats_2]:0)?>건</span><span class="condition">완료</span></a></li>
                <li class="squareWB mb-1" ><a href='/for_common/incomplete.php?stats_stx=1-bad'><span class="f_yellow"><?=$buyer_stats[all_claim]>99?'+99':($buyer_stats[all_claim]?$buyer_stats[all_claim]:0)?>건</span><span class="condition">신고</span></a></li>
                <li class="squareWB mb-1"  ><a href='/for_common/incomplete.php?stats_stx=1-3'><span class="f_yellow"><?=$buyer_stats[cnt_stats_3]>99?'+99':($buyer_stats[cnt_stats_3]?$buyer_stats[cnt_stats_3]:0)?>건</span><span class="condition confirm">입금완료</span></a></li>
            </ul>
        </div>
        <div class="area sellList">
            <h3><span>매도내역</span></h3>
            <ul class="buy">
                <li class="squareWB  mb-1"><a href='/for_common/incomplete.php?stats_stx=2-1'><span class="f_yellow"><?=$seller_stats[cnt_stats_1]>99?'+99':($seller_stats[cnt_stats_1]?$seller_stats[cnt_stats_1]:0)?>건</span><span class="condition">매도</span></a></li>
                <li class="squareWB mb-1"><a href='/for_common/incomplete.php?stats_stx=2-2'><span class="f_yellow"><?=$seller_stats[cnt_stats_2]>99?'+99':($seller_stats[cnt_stats_2]?$seller_stats[cnt_stats_2]:0)?>건</span><span class="condition">완료</span></a></li>
                <li class="squareWB mb-1"><a href='/for_common/incomplete.php?stats_stx=2-bad'><span class="f_yellow"><?=$seller_stats[all_claim]>99?'+99':($seller_stats[all_claim]?$seller_stats[all_claim]:0)?>건</span><span class="condition">신고</span></a></li>
                <li class="squareWB mb-1"><a href='/for_common/incomplete.php?stats_stx=2-3'><span class="f_yellow"><?=$seller_stats[cnt_stats_3]>99?'+99':($seller_stats[cnt_stats_3]?$seller_stats[cnt_stats_3]:0)?>건</span><span class="condition confirm">입금완료</span></a></li>
            </ul>
        </div>
        <div style="background: url(<?=G5_THEME_URL?>/images/line.png)no-repeat;
                background-size: contain;
                display: block;
                width: 100%;"
        >
            &nbsp;
        </div>
     </div>

    <!--div class="wrap">

        <ul>
            <li class="squareWB main-my-summary" style="">
                <div style="color: #ffe25c;">
                    매수 설정 금액 : <a href='/for_common/idDetail.php' style="color: inherit">
                        $ <?=number_format2($member[mb_trade_amtlmt])?>
                        설정금액</a>
                </div>

            </li>
        </ul>
    </div-->
	
    <div class="wrap">
        <div class="area shingStone">
            <h3><span>NAVI 상품</span></h3>
            <ul class="stoneList" onclick="document.location.href='/for_common/stonedetail.php'">
                <?php
                foreach($g5['cn_item'] as $k=>$v){?>

                    <li class="squareWB">
                        <h4><?=$v[name_kr]?></h4>
                        <div class="clearfix">
                            <div class="stoneImg f_left" style="padding-left: 0.4rem">
                                <img src="<?=G5_THEME_URL?>/images/butterfly/<?=$v[img]?>" alt='<?=$v[name_kr]?>' >
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
                <?php }?>

            </ul>
        </div>
    </div>

    <!--div id="new_notice" style="
        position: fixed;
    top: 0;
    padding: 1rem 1rem 0 1rem;
    right: 0;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
    z-index: 2;
    background-color: rgba(255,255,255)"><pre>
샤이닝스톤은
회원님들의 캐릭터인
스톤(루비,사파이어,에메랄드, 다이아) 4종류를
2020년 6월 11일 블랙다이아 1개의
캐릭터로 통합하였습니다.

2020년 7월 1일 새롭게 준비하는 P2P 회사로 거듭 나기위해
NAVI 로 사업명이 변경됩니다.
이에 블랙다이아 캐릭터가 나비(NAVI)로 변경됨을 알려드립니다.

회원님들은 위 모든 내용에 본인은 동의하십니까.</pre>
        <div style="text-align: center;
    margin: 1rem;">
            <button onclick="hideNewNotice()">
                <span>동의합니다.</span>
            </button>
            <button>
                <span>아니오.</span>
            </button>
        </div>
    </div-->

    <script>
	/*
    function hideNewNotice() {
        $("div[id=new_notice]").slideUp();

        $.ajax({
            url: '/for_common/member_agree_20200701.php',
            type: 'GET',

            dataType: 'json',
            success: function(data, textStatus) {
                if (data.error) {
                    alert(data.error);
                    return false;
                } else {

                }
            },
            error: function(data) {
                try { console.log(data) } catch (e) { alert(data.error) };
            }
        });

    }
	*/
    </script>

<?php
include_once(G5_THEME_PATH.'/tail.php');
?>