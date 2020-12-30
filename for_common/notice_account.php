<?php
include_once('./_common.php');

$g5['title'] = '나의 그룹 ';
$docu_title=$g5['title'];

add_javascript('<script src="'.G5_THEME_URL.'/extend/jquery.ui.touch-punch.min.js"></script>');

include_once('../_head.php');
?>
      
    
		
<div style="height:92vh;text-align:center;" id="Contents">
	<img src="<?php echo G5_THEME_URL ?>/images/head_gold.png" style="width:100%" width=100% alt="">
        <div class="notice_box" style="">
		1,000골드 = 120,000원
		</div>
		<img src="<?php echo G5_THEME_URL ?>/images/img_line.png" width="100%" />
        <div class="borderbox_bg">
			<div class="borderbox_wrap" >
				<div class="borderbox_content borderbox_content_baige">
					<p class="align_left">입금주소</p>
					<p style=""><input class="input_readonly" type="text" style="" readonly value="<?=$config['cf_usdt']?>"></p>
					<p class="align_right m-t-20"><a class="btn_nob bg_blue" href="">캐릭터로 구매하기</a>	</p>
				</div>
				<div class="borderbox_content borderbox_content_white p-l-0 p-r-0">
					<p>* 상기주소로 전송후에 아이디 구매내용 고객센터로 연락주시면 됩니다.</p>
				</div>	
			</div>
		</div>
		<div class="m-t-50">
			<p class="align_center"><a class="btn_nob bg_green" href="">고객센터 바로가기</a></p>
		</div>
    </div>
