<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// 선택옵션으로 인해 셀합치기가 가변적으로 변함
$colspan = 5;

if ($is_checkbox) $colspan++;
if ($is_good) $colspan++;
if ($is_nogood) $colspan++;

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$board_skin_url.'/style.css">', 0);

// 분류 사용 여부
$is_category = false;
$category_option = '';
if ($board['bo_use_category']) {
    $is_category = true;
    $category_href = G5_BBS_URL.'/board.php?bo_table='.$bo_table;

    $category_option .= '<li ';
    if ($sca=='')
        $category_option .= ' class="active2"';
    $category_option .= '><a href="'.$category_href.'">전체</a></li>';

    $categories = explode('|', $board['bo_category_list']); // 구분자가 , 로 되어 있음
    for ($i=0; $i<count($categories); $i++) {
        $category = trim($categories[$i]);
        if ($category=='') continue;
        $category_option .= '<li ';
        $category_msg = '';
        if ($category==$sca) { // 현재 선택된 카테고리라면
            $category_option .= ' class="active2" ';
            //$category_msg = '<span class="sound_only">열린 분류 </span>';
        }
        $category_option .= '><a href="'.($category_href."&amp;sca=".urlencode($category)).'" >'.$category_msg.$category.'</a></li>';
    }
}

?>
<div id="Contents">
	<!-- 게시판 목록 시작 { -->
	<div id="bo_list" style="width:<?php echo $width; ?>">

		<?php if($bo_table=='notice'){?>
			<img src="<?php echo G5_THEME_URL ?>/images/head_notice.png" style="width:100%;margin-bottom:30px" width=100% alt="">
		<?php }?>
		<img src="<?php echo G5_THEME_URL ?>/images/img_line.png" width="100%" />
			<!-- 게시판 검색 시작 { -->

		<!-- } 게시판 검색 끝 -->   
		
		<!-- 게시판 페이지 정보 및 버튼 시작 { -->
		<!-- } 게시판 페이지 정보 및 버튼 끝 -->
		
		<!-- 게시판 카테고리 시작 { -->
		<?php if ($is_category) { ?>
		
		<ul  class='sub-tab divide-<?=count($categories)+1?>' >
			<?php echo $category_option ?>
		</ul>
		<?php } ?>
		<!-- } 게시판 카테고리 끝 -->

		<form name="fboardlist" id="fboardlist" action="./board_list_update.php" onsubmit="return fboardlist_submit(this);" method="post">
		<input type="hidden" name="bo_table" value="<?php echo $bo_table ?>">
		<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
		<input type="hidden" name="stx" value="<?php echo $stx ?>">
		<input type="hidden" name="spt" value="<?php echo $spt ?>">
		<input type="hidden" name="sca" value="<?php echo $sca ?>">
		<input type="hidden" name="sst" value="<?php echo $sst ?>">
		<input type="hidden" name="sod" value="<?php echo $sod ?>">
		<input type="hidden" name="page" value="<?php echo $page ?>">
		<input type="hidden" name="sw" value="">

		<ul class="notice_list">
			<?php
			for ($i=0; $i<count($list); $i++) {
			 ?>
			 <li class="notice_board">
					<span>[공지]</span>
					<a href="<?php echo $list[$i]['href'] ?>">
						<?php echo $list[$i]['subject'] ?>
					</a>
					<p><?php echo $list[$i]['datetime2'] ?></p>
			 </li>
			<?php } ?>
			<?php if (count($list) == 0) { echo '<li>게시물이 없습니다.</li>'; } ?>

		</ul>
	  </form>
		 
	   
	</div>
</div>

<?php if($is_checkbox) { ?>
<noscript>
<p>자바스크립트를 사용하지 않는 경우<br>별도의 확인 절차 없이 바로 선택삭제 처리하므로 주의하시기 바랍니다.</p>
</noscript>
<?php } ?>



<!-- 페이지 -->
<?php //echo $write_pages;  
echo com_pager_print($total_page,$page,G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'],$qstr."&page=");
?>


<?php if ($is_checkbox) { ?>
<script>
function all_checked(sw) {
    var f = document.fboardlist;

    for (var i=0; i<f.length; i++) {
        if (f.elements[i].name == "chk_wr_id[]")
            f.elements[i].checked = sw;
    }
}

function fboardlist_submit(f) {
    var chk_count = 0;

    for (var i=0; i<f.length; i++) {
        if (f.elements[i].name == "chk_wr_id[]" && f.elements[i].checked)
            chk_count++;
    }

    if (!chk_count) {
        alert(document.pressed + "할 게시물을 하나 이상 선택하세요.");
        return false;
    }

    if(document.pressed == "선택복사") {
        select_copy("copy");
        return;
    }

    if(document.pressed == "선택이동") {
        select_copy("move");
        return;
    }

    if(document.pressed == "선택삭제") {
        if (!confirm("선택한 게시물을 정말 삭제하시겠습니까?\n\n한번 삭제한 자료는 복구할 수 없습니다\n\n답변글이 있는 게시글을 선택하신 경우\n답변글도 선택하셔야 게시글이 삭제됩니다."))
            return false;

        f.removeAttribute("target");
        f.action = "./board_list_update.php";
    }

    return true;
}

// 선택한 게시물 복사 및 이동
function select_copy(sw) {
    var f = document.fboardlist;

    if (sw == "copy")
        str = "복사";
    else
        str = "이동";

    var sub_win = window.open("", "move", "left=50, top=50, width=500, height=550, scrollbars=1");

    f.sw.value = sw;
    f.target = "move";
    f.action = "./move.php";
    f.submit();
}
</script>
<?php } ?>
<!-- } 게시판 목록 끝 -->
<style>
.pagination {padding-bottom:20px}
.notice_list {padding:20px 0}
.notice_list li {width:100%;border:2px #cabba1 solid;border-radius:20px;padding:20px 10px;box-shadow:1px 1px 1px 1px #747474;background:#f9efca;margin-bottom:20px}
.notice_list li:nth-child(2n) {background:#fff}
</style>