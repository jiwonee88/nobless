<?php
include_once('./_common.php');

$g5['title'] = '나의 그룹 ';
$docu_title=$g5['title'];

add_javascript('<script src="'.G5_THEME_URL.'/extend/jquery.ui.touch-punch.min.js"></script>');

include_once('../_head.php');
?>
      
    
<div style="height:92vh;text-align:center;" id="bo_list ">
    <img src="<?php echo G5_THEME_URL ?>/images/head_memo.png" style="width:100%" width=100% alt="">
    <div style="padding-top:100px">
        <form action="./matching_memo_update.php" method="POST">
            <input type="text" name="memo" value="<?=$member['mb_message']?>">
            <input type="submit" value="메모저장">
        </form>
    </div>
</div>
