<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$member_skin_url.'/style.css">', 0);

$g5['title'] = ' Email Authentication';
include_once('./_head.sub.php');
?>
<div style='position:absolute;left:50%;top:50%;width:600px;max-width:90%;transform: translate(-50%,-50%);'>
<h2 class='h4 text-center' ><img src='<?=G5_THEME_URL?>/img/shield.png' width=35 align='absmiddle' style='vertical-align:middle;' > <?=$config[cf_title]?> Email Certification</h2>
<div  class="p-5  border rounded text-center my-5 shadow">
   
   <?=$msg?>

</div>
<p class='my-3 text-center' >Copyrightⓒ <?=$config[cf_title]?>. All right reserved.</p>
</div>
<?
include_once('./_tail.sub.php');