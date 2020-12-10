<?php
define("IS_COMMUNITY",true) ;
include_once('./_common.php');

add_stylesheet(" <link rel=\"stylesheet\" type=\"text/css\" href='".G5_THEME_URL."/css/community.css' >");

include_once('../_head.php');
?>

<div class='wrapper-community  anima-fade' style=" padding-bottom: 60px;">
<?
include_once('./community.main.inc.php');
?>
</div>

<?
include_once('../_tail.php');

