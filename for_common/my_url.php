<?php
include_once('./_common.php');

$g5['title'] = '아이디페이지';
$docu_title=$g5['title'];

include_once('../_head.php');
include "./qrcode/qrlib.php";
$PNG_TEMP_DIR = dirname(__FILE__).DIRECTORY_SEPARATOR.'qrcode'.DIRECTORY_SEPARATOR.'qrimg'.DIRECTORY_SEPARATOR;
$PNG_WEB_DIR = './qrcode/qrimg/';
$url = G5_URL."/?refid=".$member[mb_id];
//ofcourse we need rights to create temp dir
if (!file_exists($PNG_TEMP_DIR)) {
    mkdir($PNG_TEMP_DIR);
}


$filename = $PNG_TEMP_DIR.$member['mb_id'].'.png';
$errorCorrectionLevel = 'L';
$matrixPointSize = 5;
QRcode::png($url, $filename, $errorCorrectionLevel, $matrixPointSize, 2);
?>
<div style="height:90vh;text-align:center" id="Contents">
	<img src="<?php echo G5_THEME_URL ?>/images/head_myl.png" style="width:100%" width=100% alt="">
		<div class="notice_box" style="">
			초대QR코드
		</div>
		<img src="<?php echo G5_THEME_URL ?>/images/img_line.png" width="100%" />
	<div style="padding:30px 0">
		<div class="borderbox_bg" style="width:200px;margin:0 auto">
			<div class="borderbox_wrap" >
				<div class="borderbox_content borderbox_content_white">
					<img src="<?=$PNG_WEB_DIR.basename($filename)?>" alt="레퍼럴 qr코드">
				</div>
			</div>
		</div>
		<button class="btn-clipboard btn_nob bg_blue" style="margin:30px 0" data-clipboard-text="<?=G5_URL?>/?refid=<?=$member[mb_id]?>" >
			추천코드 복사
		</button>
	</div>
</div>
<script src="http://navi.one/theme/shining/extend/clipboard.min.js"></script>
<script>
$(document).ready(function () {

    var clipboard = new ClipboardJS('.btn-clipboard');
    clipboard.on('success', function(e) {
        Swal.fire({html:'복사완료',timer:1000});

        var selection = window.getSelection();
        selection.removeAllRanges();
    });

    clipboard.on('error', function(e) {
        Swal.fire({html:'복사실패',timer:1000});
    });
});
</script>
<style>
.btn-copy {
    padding: 10px;
    background: #f4f4f4;
    box-shadow: 2px 2px 2px 2px #ddd;
    color: #000000;
    border-color: #ddd;
}
</style>
