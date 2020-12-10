<?php
include_once('./_common.php');

define('_INDEX_', true);
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가


if(defined('G5_THEME_PATH')) {
    require_once(G5_THEME_PATH.'/intro.php');
    return;
}
