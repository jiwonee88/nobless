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
    <img src="<?=G5_THEME_URL?>/images/v2/1192.png" class="NAVI-login">
</div>
<div style="text-align: center;display:flex;justify-content: center; margin-top: 138px;">
    <span class="NAVI-login-text">NAVI</span>
</div>
<form class="form-horizontal"  name="flogin" action="<?php echo $login_action_url ?>" onsubmit="return flogin_submit(this);" method="post">

	<div class="login">
        <div class="wrap">
 
            <div class="box id">
                <input name="mb_id" type="text" class="input-text" id="mb_id" required placeholder="아이디"  autocomplete='off' >
            </div>
            <div class="box pswd">
                <input name="mb_password" type="password" class="input-text" id="mb_password" required  placeholder="비밀번호"  autocomplete='off' >
            </div>
			<button type='submit' >LOGIN</button>
            <div class="or">
                <span>or</span>
            </div>
            <div class="signUp">
               <a href="/bbs/register_form.php"  >SIGNUP</a>
            </div>
<!--			 <div class="signUp">-->
<!--               <a href="/bbs/password_lost.php"  >암호찾기</a>                -->
<!--            </div>-->
        </div>
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