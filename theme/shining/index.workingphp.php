<?php
define('_INDEX_', true);
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

if (G5_IS_MOBILE) {
    include_once(G5_THEME_MOBILE_PATH.'/index.php');
    return;
}

include_once(G5_THEME_PATH.'/head.php');

?>
<div class="card w-60 mx-auto mt-5">
  <div class="card-header text-center ">
  OOPS!
  </div>
  <div class="card-body text-center">
    <h5 class="card-title"><?=$config[cf_title]?>  SITE</h5>
    <p class="card-text">This website does not provide content</p>
	<a href="mailto:boombinet@daum.net" class="btn btn-primary">boombinet@daum.net</a>
  </div>
</div>


<?php
include_once(G5_THEME_PATH.'/tail.php');
?>