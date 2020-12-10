<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
?>

<?php
//if(defined('_INDEX_')) { // index에서만 실행
	include G5_THEME_PATH.'/newwin.inc.php'; // 팝업레이어
//}
?>
<div class="bg"></div>
<div style="text-align:center;margin-top:364px;">
    <img src="<?=G5_THEME_URL?>/images/v2/1192.png" class="Layer-1192">
</div>
<div style="text-align: center;display:flex;justify-content: center; margin-top: 138px;">
    <span class="NAVI-login">NAVI</span>
</div>
<form class="form-horizontal"  name="flogin" action="<?php echo $login_action_url ?>" onsubmit="return flogin_submit(this);" method="post">

	<div class="login">
        <div class="wrap">

            <div class="Layer-13-copy-2">
                <input name="mb_id" type="text" class="input-13" id="mb_id" required placeholder="ID"  autocomplete='off' >
            </div>
            <div style="margin-bottom: 2rem"></div>

            <div class="Layer-13-copy-2">
                <input name="mb_password" type="password" class="input-13" id="mb_password" required  placeholder="PASSWORD"  autocomplete='off' >
            </div>
            <div style="margin-bottom: 2rem"></div>
            <button type='submit' class="Rectangle-1-copy-2">LOGIN</button>

            <div class="or">
                <span>or</span>
            </div>

            <div style="    position: relative;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 134px;
                border: 2px solid #fff;"
            >
                <div class="Layer-13-copy-2" style="
                position: absolute;
                top: 0;
                background-color: rgba(0,0,0,0.6)!important;
                width: 100%;"
                >&nbsp;</div>
                <a href="/bbs/register_form.php" style="z-index: 2;
                font-size: 2rem;
                letter-spacing: 7.56px;">SIGN UP</a>
            </div>

<!--			 <div class="signUp">-->
<!--               <a href="/bbs/password_lost.php"  >암호찾기</a>                -->
<!--            </div>-->
        </br>
    </div>
	
</form>
	
<script>		

//로그인 스크립트
function flogin_submit(f)
{
	event.preventDefault();

	var formData = $(f).serialize();	
	
	$.ajax({
		type: "POST",
		url: $(f).attr('action'),
		data:formData,
		cache: false,
		/*async: false,*/
		dataType:"json",
		success: function(data) {
			
			if(data.result==true){				

				if(data.datas['goto_url']){
					document.location.href='/' //data.datas['goto_url'];
				}
				else  document.location.href='/';
				
				return;
				
			}
			else Swal.fire({title:"",text:data.message,icon: 'warning'
			,
			  onClose: () => {
				if(data.datas['goto_url']) document.location.href=data.datas['goto_url'];
			  }
			 });
			
		}
	});		

	
	return;
}
	
</script>