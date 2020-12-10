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
                            <div class="card border-grey border-lighten-3 px-0 py-1 m-1" style='background:rgb(255,255,255,0.9);'>
                                <div class="card-header border-0 bg-none" style='background:none;'>
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
               Please enter your e-mail address.<br>

We will send you your username and password..
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
    <?//php echo chk_captcha_js();  ?>
   var formData = $(f).serialize();	
   $.ajax({
		type: "POST",
		url: "./password_lost2.php",
		data:formData,
		cache: false,
		async: false,
		dataType:"json",
		success: function(data) {

			if(data.result){					
				
				f.reset();
				
				var rc=parseFloat($("input[name=tr_wallet_"+tr_token+"]").val()).toPrecision() - amt;
				$("input[name=tr_wallet_"+tr_token+"]").val(rc);
				$("input[name=tr_wallet_"+tr_token+"_tmp]").val(inputNumberFormat(rc));
				
				Swal.fire('Transfer application completed');   
			}
			else Swal.fire(data.message);       
		}
	});		

	event.preventDefault();

	return;
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