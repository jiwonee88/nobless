<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$member_skin_url.'/style.css">', 0);
?>

<!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-wrapper-before"></div>
            <div class="content-header row">
            </div>
            
            
            
            <div class="content-body">
                <section class="flexbox-container">
                    <div class="col-12 d-flex align-items-center justify-content-center">
                        <div class="col-lg-4 col-md-6 col-12 box-shadow-2 p-0">
                            <div class="card border-grey border-lighten-3 px-1 py-1 m-0"  style='background:rgb(255,255,255,0.9);'>
                                <div class="card-header border-0"  style='background:none;'>
                                    <div class="text-center mb-1">
                                        <img src="<?=G5_THEME_URL?>/img/logo_login.png" alt="branding logo" class='w-60' style='max-width:238px'>
                                    </div>
                                    <div class="font-large-1  text-center">
                                        Find My Information
                                    </div>
                                </div>
                                <div class="card-content">
                                
                                
                                 <div class="card-body">                                
                                 
                                   <form name="fpasswordlost" action="<?php echo $action_url ?>" onsubmit="return fpasswordlost_submit(this);" method="post" autocomplete="off">
       										<p class='text-center' >
                회원가입 시 등록하신 이메일 주소를 입력해 주세요.<br>
                해당 이메일로 아이디와 비밀번호 정보를 보내드립니다.
            </p>
											 <fieldset class="form-group position-relative has-icon-left">
                                                <input name="mb_email" type="text" required class="form-control round" id="mb_email" placeholder="Your Email" chkname='ID'  >
                                                <div class="form-control-position">
                                                    <i class="ft-mail"></i>
                                                </div>
                                            </fieldset>
                                            
                                            <div class="form-group text-center">
                                                <button type="submit" class="btn round btn-block btn-glow btn-bg-gradient-x-purple-blue col-12 mr-1 mb-1">Get Email</button>
                                            </div>

                                        </form>
                                    </div>
                                   
                                    <p class="card-subtitle text-muted text-right font-small-3 mx-2 my-1"><span><a href="login.php" class="card-link">Sing In</a></span></p>
                                
                                
                                
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

            </div>
        </div>
    </div>
    <!-- END: Content-->


<script>
function fpasswordlost_submit(f)
{
    <?php echo chk_captcha_js();  ?>

    return true;
}

$(function() {
    var sw = screen.width;
    var sh = screen.height;
    var cw = document.body.clientWidth;
    var ch = document.body.clientHeight;
    var top  = sh / 2 - ch / 2 - 100;
    var left = sw / 2 - cw / 2;
    moveTo(left, top);
});
</script>
<!-- } 회원정보 찾기 끝 -->