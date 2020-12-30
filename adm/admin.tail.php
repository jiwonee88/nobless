<?php
if (!defined('_GNUBOARD_')) exit;

$print_version = defined('G5_YOUNGCART_VER') ? 'YoungCart Version '.G5_YOUNGCART_VER : 'Version '.G5_GNUBOARD_VER;
?>

        <noscript>
            <p>
                귀하께서 사용하시는 브라우저는 현재 <strong>자바스크립트를 사용하지 않음</strong>으로 설정되어 있습니다.<br>
                <strong>자바스크립트를 사용하지 않음</strong>으로 설정하신 경우는 수정이나 삭제시 별도의 경고창이 나오지 않으므로 이점 주의하시기 바랍니다.
            </p>
        </noscript>

        </div>    
        <footer id="ft">
            <p>
                Copyright &copy; <?php echo $_SERVER['HTTP_HOST']; ?>. All rights reserved. <?php //echo $print_version; ?><br>
               <button type="button" class="scroll_top"><span class="top_img"></span><span class="top_txt">TOP</span></button>
           </p>
        </footer>
    </div>

</div>


<div id='admin_loading' style='position:fixed;width:300px;height:70px;border:1px solid #343434;background:rgba(255,255,255,0.7);top:50%;left:50%;transform;translate(-50%,-50%);display:none;text-align:center;font-size:20px;line-height:70px;font-weight:500;'>
적용완료
</div>

<?
//정회원
$_temp1 = sql_fetch(" select count(*) as cnt from {$g5['member_table']} where  mb_level between 2 and 8  ");
//임시 회원 카운팅
$_temp2 = sql_fetch(" select count(*) as cnt from {$g5['member_table']} where mb_level ='1' ");
//삭제회원
$_temp3 = sql_fetch(" select count(*) as cnt from {$g5['member_table']} where mb_10='1' or mb_10='2' ");
?>

<script>
$(".scroll_top").click(function(){
     $("body,html").animate({scrollTop:0},400);
})
</script>

<!-- <p>실행시간 : <?php echo get_microtime() - $begin_time; ?> -->

<script src="<?php echo G5_ADMIN_URL ?>/admin.js?ver=<?php echo G5_JS_VER; ?>"></script>
<script src="<?php echo G5_JS_URL ?>/jquery.anchorScroll.js?ver=<?php echo G5_JS_VER; ?>"></script>
<script>
$(function(){

    var admin_head_height = $("#hd_top").height() + $("#container_title").height() + 5;

    $("a[href^='#']").anchorScroll({
        scrollSpeed: 0, // scroll speed
        offsetTop: admin_head_height, // offset for fixed top bars (defaults to 0)
        onScroll: function () { 
          // callback on scroll start
        },
        scrollEnd: function () { 
          // callback on scroll end
        }
    });

    var hide_menu = false;
    var mouse_event = false;
    var oldX = oldY = 0;

    $(document).mousemove(function(e) {
        if(oldX == 0) {
            oldX = e.pageX;
            oldY = e.pageY;
        }

        if(oldX != e.pageX || oldY != e.pageY) {
            mouse_event = true;
        }
    });

    // 주메뉴
    var $gnb = $(".gnb_1dli > a");
    $gnb.mouseover(function() {
        if(mouse_event) {
            $(".gnb_1dli").removeClass("gnb_1dli_over gnb_1dli_over2 gnb_1dli_on");
            $(this).parent().addClass("gnb_1dli_over gnb_1dli_on");
            menu_rearrange($(this).parent());
            hide_menu = false;
        }
    });

    $gnb.mouseout(function() {
        hide_menu = true;
    });

    $(".gnb_2dli").mouseover(function() {
        hide_menu = false;
    });

    $(".gnb_2dli").mouseout(function() {
        hide_menu = true;
    });

    $gnb.focusin(function() {
        $(".gnb_1dli").removeClass("gnb_1dli_over gnb_1dli_over2 gnb_1dli_on");
        $(this).parent().addClass("gnb_1dli_over gnb_1dli_on");
        menu_rearrange($(this).parent());
        hide_menu = false;
    });

    $gnb.focusout(function() {
        hide_menu = true;
    });

    $(".gnb_2da").focusin(function() {
        $(".gnb_1dli").removeClass("gnb_1dli_over gnb_1dli_over2 gnb_1dli_on");
        var $gnb_li = $(this).closest(".gnb_1dli").addClass("gnb_1dli_over gnb_1dli_on");
        menu_rearrange($(this).closest(".gnb_1dli"));
        hide_menu = false;
    });

    $(".gnb_2da").focusout(function() {
        hide_menu = true;
    });

    $('#gnb_1dul>li').bind('mouseleave',function(){
        submenu_hide();
    });

    $(document).bind('click focusin',function(){
        if(hide_menu) {
            submenu_hide();
        }
    });

    // 폰트 리사이즈 쿠키있으면 실행
    var font_resize_act = get_cookie("ck_font_resize_act");
    if(font_resize_act != "") {
        font_resize("container", font_resize_act);
    }

	<? if($_temp1[cnt]){?>
	$(".gnb_oparea li[data-menu='200120']").html($(".gnb_oparea li[data-menu='200120']").html()+' <span class="lsbtn lsbtn-xxx obje-red"><?=$_temp1[cnt]?></span>')
	<? }?>	
	//임시회원
	<? if($_temp2[cnt]){?>
	$(".gnb_oparea li[data-menu='200150']").html($(".gnb_oparea li[data-menu='200150']").html()+' <span class="lsbtn lsbtn-xxx obje-red"><?=$_temp2[cnt]?></span>')
	<? }?>
	//탈퇴회원
	<? if($_temp3[cnt]){?>
	$(".gnb_oparea li[data-menu='200130']").html($(".gnb_oparea li[data-menu='200130']").html()+' <span class="lsbtn lsbtn-xxx obje-red"><?=$_temp3[cnt]?></span>')
	<? }?>

});

function submenu_hide() {
    $(".gnb_1dli").removeClass("gnb_1dli_over gnb_1dli_over2 gnb_1dli_on");
}

function menu_rearrange(el)
{
    var width = $("#gnb_1dul").width();
    var left = w1 = w2 = 0;
    var idx = $(".gnb_1dli").index(el);

    for(i=0; i<=idx; i++) {
        w1 = $(".gnb_1dli:eq("+i+")").outerWidth();
        w2 = $(".gnb_2dli > a:eq("+i+")").outerWidth(true);

        if((left + w2) > width) {
            el.removeClass("gnb_1dli_over").addClass("gnb_1dli_over2");
        }

        left += w1;
    }
}


function alert_loading(str){
if(typeof str !== 'undefined' && str!=''){
	$('#admin_loading').html(str);
}
 $('#admin_loading').fadeIn(700).fadeOut(400);
}

function alert_loading2(str,s){
	if(typeof str !== 'undefined' && str!=''){
		$('#admin_loading').html(str);
	}
	if(s=='open') 	$('#admin_loading').fadeIn(700);
	else if(s=='close') $('#admin_loading').fadeOut(400);
}


</script>

<?php
include_once(G5_PATH.'/tail.sub.php');
?>