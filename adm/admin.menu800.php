<?php
$menu['menu800'] = array (
	array('800000', '수당관리', ''.G5_CN_ADMIN_URL.'/recommender_list.php', 'recommend_list'),
	
	
    //array('800100', '지급내역-채굴', ''.G5_CN_ADMIN_URL.'/fee_settle_history.php', 'fee_list'),			
	array('800110', '내역-추천인 롤업',   ''.G5_CN_ADMIN_URL.'/fee_settle2_history.php', 'fee_list'),							
	array('800120', '내역-서브계정 롤업',  ''.G5_CN_ADMIN_URL.'/fee_settle3_history.php', 'fee_list'),		
	
	//array('800200', '정산-스테이킹 채굴', ''.G5_CN_ADMIN_URL.'/fee_settle_list.php', 'fee_rollup'),			
	array('800210', '정산-추천인 롤업', ''.G5_CN_ADMIN_URL.'/fee_settle2_list.php', 'fee_rollup'),	
	array('800220', '정산-서브계정 롤업',  ''.G5_CN_ADMIN_URL.'/fee_settle3_list.php', 'fee_rollup'),	
	
	array('800300', '추천인계보', ''.G5_CN_ADMIN_URL.'/recommender_list.php', 'recommend_list'),
	array('800310', '추천인다이어그램', ''.G5_CN_ADMIN_URL.'/recommender_diagram.php', 'recommend_diagram'),
	array('800500', '계정구간별통계', ''.G5_CN_ADMIN_URL.'/statistics.php', 'statistics'),
	array('800600', '판매금액조정', ''.G5_CN_ADMIN_URL.'/sise_price.php', 'sise_price'),
	array('800700', '소각금액순위', ''.G5_CN_ADMIN_URL.'/rank.php', 'rank'),
	array('800800', '소각내역', ''.G5_CN_ADMIN_URL.'/logged.php', 'rank'),

);
?>