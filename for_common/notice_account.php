<?php
include_once('./_common.php');

$g5['title'] = '나의 그룹 ';
$docu_title=$g5['title'];

add_javascript('<script src="'.G5_THEME_URL.'/extend/jquery.ui.touch-punch.min.js"></script>');

include_once('../_head.php');
?>
      
    
    <div class="wrap"> 	
        
        <div class="area area-tit">
            <h3><span><?=$g5[title]?></span></h3>
        </div>
        <input type="text" readonly value="<?=$config['cf_usdt']?>">
    </div>
