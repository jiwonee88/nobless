<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
?>

	
	
    </div>

<div class="menu" >
	<div class="menuList">
		<div class="menuClose"><img src="<?=G5_THEME_URL?>/images/menuCloseBtn.png" alt="메뉴닫기"></div>
		<div class="area">
			<ul class="common">
				<li class="hero w100"><span><?=$member[mb_id]?></span></li>
				<li class="w100"><span>총거래수: 0</span></li>
			</ul>
		</div>
		<div class="gnb">
			<ul>
				<li><a href="/for_common/recommender_diagram.php">나의 그룹 </a></li>
				<li><a href="/for_common/transfer.php">꽃 전송</a></li>
				<li><a href="/for_common/buyitem.php">매너상점</a></li>
				<li><a href="/for_common/conversion.php">꿀단지변환</a></li>
				<li><a href="/for_common/history.php">History</a></li>				
				<li><a href="#">공지사항</a></li>
				<li><a href="https://open.kakao.com/o/s7ThbTnc" target='_blank' >1 대 1 문의</a></li>
				<li><a href="https://navimall.co.kr">나비몰 가기</a></li>
				<li><a href='/bbs/logout.php'>로그아웃</a></li>
			</ul>
		</div>
	</div>
</div>

			
<script>
/* 모바일뷰메뉴 노출 */
$(document).ready(function() {    
    $('.menuClose').click(function(){
        $('.menu').animate({left: '-100%'}, 700);
    });
    
   $('.menuBtn').click(function(){
   		console.log('dd');
        $('.menu').animate({left: '0'}, 700);

     });
});   
</script>    

	
<?
if ($config['cf_analytics']) {
    echo $config['cf_analytics'];
}
include_once(G5_THEME_PATH."/tail.sub.php");
?>