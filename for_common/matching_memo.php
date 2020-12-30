<?php
include_once('./_common.php');

$g5['title'] = '나의 그룹 ';
$docu_title=$g5['title'];

add_javascript('<script src="'.G5_THEME_URL.'/extend/jquery.ui.touch-punch.min.js"></script>');

include_once('../_head.php');
?>
      
    
<div style="height:92vh;text-align:center;" id="Contents">
    <img src="<?php echo G5_THEME_URL ?>/images/head_memo.png" style="width:100%" width=100% alt="">
    <div style="padding-top:50px">
        <form action="./matching_memo_update.php" method="POST">
			<div class="borderbox_bg">
				<div class="borderbox_wrap" >
					<div class="borderbox_content borderbox_content_baige">
						<p class="align_left">매칭메모</p>
						<p style=""><input type="text" class="input_readonly" name="memo" value="<?=$member['mb_message']?>"></p>
						<p class="align_right m-t-20"><input type="submit" class="btn_nob bg_blue" value="메모저장">	</p>
					</div>
					<div class="borderbox_content borderbox_content_white p-l-0 p-r-0">
						<p>* 저장시 상대방에게 메세지가 전달됩니다.</p>
					</div>	
				</div>
			</div>
        </form>
    </div>
</div>
