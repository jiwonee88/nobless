<?php
$sub_menu = "700765";

include_once('./_common.php');

$matching_table=$g5['cn_item_trade_test'];

auth_check($auth[$sub_menu], 'r');

if($date_stx=='' ) $date_stx=date("Y-m-d");

$g5['title'] = $g5['cn_item_name'].'-사용자별 매칭현황-프리뷰';

include_once ('./item_matching_user_stat.inc.php');

include_once ('../admin.tail.php');
?>
