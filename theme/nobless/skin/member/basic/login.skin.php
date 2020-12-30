<?php
if (!defined('_GNUBOARD_')) {
    exit;
} // 개별 페이지 접근 불가
?>

<?php
//if(defined('_INDEX_')) { // index에서만 실행
    include G5_THEME_PATH.'/newwin.inc.php'; // 팝업레이어
//}
?>
<div id="wrap" style="height:100vh">
	<div style="padding:50px 10px">
		<img src="<?php echo G5_THEME_URL ?>/images/sec1_img.png" style="width:100%" width=100% alt="">
		<div style="padding:50px 10px 0;text-align:center">
		<form class="form-horizontal"  name="flogin" action="<?php echo $login_action_url ?>" onsubmit="return flogin_submit(this);" method="post">

			<div class="login main_con" id="Contents">
				<div class="wrap">
		
					<div class="box id">
						<input name="mb_id" type="text" class="input-text" id="mb_id" required placeholder="아이디"  autocomplete='off' >
					</div>
					<div class="box pswd">
						<input name="mb_password" type="password"  placeholder="비밀번호"  autocomplete='off' >
					</div>
					<div class="btn_box">
						<button type='submit' >LOGIN</button>
						<div class="or">
							<span>or</span>
						</div>
						<div class="signUp">
						<a href="/bbs/register_form.php"  >SIGNUP</a>
						</div>
					</div>
		<!--			 <div class="signUp">-->
		<!--               <a href="/bbs/password_lost.php"  >암호찾기</a>                -->
		<!--            </div>-->
				</div>
			</div>
			
		</form>
		</div>
	</div>

</div>
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
<style>
.input[type=hidden]{color:#000}
.box {margin:20px 0}
.btn_box {margin: 50px auto;width: 100px;}
.login button{ width: 100%;
    height: 3.125rem;
    margin: 0;
    background-color: #ffffff;
    outline: none;
    color: #fff;
    text-transform: uppercase;
    font-size: 1rem;
    letter-spacing: 0.2rem;
    color: #07243e;
    font-weight: 700;
    border-radius: 10px;
    border: 1px #b0b0b0 solid;
    box-shadow: 2px 2px 2px 2px #ddd;
	margin-bottom: 30px;
}

.login .signUp{    width: 100%;
    height: 3.125rem;
    line-height: 3.125rem;
    margin: 32px 0 0;
    background: rgba(0,0,0,0.6);
    position: relative;
    text-align: center;
    border: 2px solid #fff;
    color: #fff;
    border-radius: 10px;
}

input[type=password] {font-family: 'Roboto', Helvetica, Arial, sans-serif;}
input[type=password]::placeholder {font-family: "HeirofLight";}

.main_con input {width:100%;border-color:transparent;border-bottom:1px #ddd solid; height:50px;margin-bottom:10px}
</style>
