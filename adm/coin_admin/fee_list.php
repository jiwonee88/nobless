<?php
$sub_menu = "700800";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');

$g5['title'] = "자산내역";
include "fee_list.inc.php";

include_once(G5_ADMIN_PATH.'/admin.tail.php');
