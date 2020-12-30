<?php
$menu['menu700'] = array (
    array('700000', '입출금',  ''.G5_CN_ADMIN_URL.'/insert_purchase_list.php', 'purchase_list'),
    array('700100', ($g5[cn_cointype]['i']).'-구매내역', ''.G5_CN_ADMIN_URL.'/insert_purchase_list.php', 'purchase_list'),
	array('700200', '직접입금처리', ''.G5_CN_ADMIN_URL.'/insert_reserve_list.php', 'insert_list'),
	array('700250',  ($g5['cn_item_name']).'직접지급', ''.G5_CN_ADMIN_URL.'/item_give_form.php', 'item_give'),	
	
	array('700500', ($g5['cn_item_name']).'-보유목록', ''.G5_CN_ADMIN_URL.'/item_cart_list.php', 'item_cart'),	
	array('700510', ($g5['cn_item_name']).'-회원별 보유현황', ''.G5_CN_ADMIN_URL.'/item_cart_user_stat.php', 'item_cart'),	
	
	array('700550', ($g5['cn_item_name']).'-구매대기', ''.G5_CN_ADMIN_URL.'/item_buyer_list.php', 'item_buy'),	
	array('700600', ($g5['cn_item_name']).'-거래관리', ''.G5_CN_ADMIN_URL.'/item_trade_list.php', 'item_trade'),
	
	array('700610', ($g5['cn_item_name']).'-매칭현황', ''.G5_CN_ADMIN_URL.'/item_matching_user_stat.php', 'matching'),	
	array('700710', ($g5['cn_item_name']).'-매칭대기현황', ''.G5_CN_ADMIN_URL.'/item_matching.ready.php', 'matching'),	
	
	//array('700700', ($g5['cn_item_name']).'-매칭(회사)', ''.G5_CN_ADMIN_URL.'/item_matching.php', 'matching'),
	array('700740', ($g5['cn_item_name']).'-직접매칭', ''.G5_CN_ADMIN_URL.'/item_matching.direct.php', 'matching'),	
	array('700750', ($g5['cn_item_name']).'-매칭(p2p)', ''.G5_CN_ADMIN_URL.'/item_matching.p2p.php', 'matching'),	
	array('700755', ($g5['cn_item_name']).'-매칭(p2p)-프리뷰', ''.G5_CN_ADMIN_URL.'/item_matching.p2p.list.php', 'matching'),
	array('700765', ($g5['cn_item_name']).'-매칭현황-프리뷰', ''.G5_CN_ADMIN_URL.'/item_matching_user_stat.prev.php', 'matching'),	
	//array('700760', ($g5['cn_item_name']).'-매칭LOG', ''.G5_CN_ADMIN_URL.'/item_matching_history.php', 'matching'),	
	
	array('700300', '출금내역', ''.G5_CN_ADMIN_URL.'/coin_draw_list.php', 'coin_draw'),
	array('700400', '계정간이체', ''.G5_CN_ADMIN_URL.'/coin_transfer_list.php', 'insert_list'),	
	
	array('700800', '자산내역', ''.G5_CN_ADMIN_URL.'/fee_list.php', 'fee_list'),	
	
	array('700850',  ($g5['cn_item_name']).'-구매내역', ''.G5_CN_ADMIN_URL.'/item_purchase_list.php', 'item_purchase'),	
	
	array('700860', '수익현황', ''.G5_CN_ADMIN_URL.'/item_trade_profit.php', 'item_purchase'),	
	
	
	array('700900', '패널티관리', ''.G5_CN_ADMIN_URL.'/member_penalty_list.php', 'coin_draw'),
	array('700910', '판매날짜조정', ''.G5_CN_ADMIN_URL.'/member_penalty_date.php', 'coin_date'),
	
	//array('700500', '코인스왑', ''.G5_CN_ADMIN_URL.'/coin_swap_list.php', 'insert_list'),	
);
?>