<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$member_skin_url.'/style.css">', 0);
?>
<style>
html, body {width:100%; height:100%; max-width:2000px; min-width:320px; max-width:520px;  margin-left:auto; margin-right:auto; font-family:'HeirofLight'; background:#f6f6f6;}
</style>
<div id="Contents" class="main_con" style="padding:100px 0 50px;margin:0;background:url('<?=G5_THEME_URL?>/images/bg_login.jpg') no-repeat;background-size:100% 100%">
	<!-- 로그인 시작 { -->
	<p style="color: #e8cd0b;text-align: center;font-size: 3em;">NOBLESS</p>
	<div id="mb_login" class="mbskin">
		<h1 style="color:#fff"><?php echo $g5['title'] ?></h1>

		<form name="flogin" action="<?php echo $login_action_url ?>" onsubmit="return flogin_submit(this);" method="post">
		<input type="hidden" name="url" value="<?php echo $login_url ?>">

		<fieldset id="login_fs">
			<legend>회원로그인</legend>
			<label for="login_id" class="sound_only">회원아이디<strong class="sound_only"> 필수</strong></label>
			<input type="text" name="mb_id" id="login_id" required class="frm_input required" size="20" maxLength="20" placeholder="아이디">
			<label for="login_pw" class="sound_only">비밀번호<strong class="sound_only"> 필수</strong></label>
			<input type="password" name="mb_password" id="login_pw" required class="frm_input required" size="20" maxLength="20" placeholder="비밀번호">
			<input type="submit" value="로그인" class="btn_submit">
			<input type="checkbox" name="auto_login" id="login_auto_login">
			<label for="login_auto_login">자동로그인</label>
		</fieldset>

		<?php
		// 소셜로그인 사용시 소셜로그인 버튼
		@include_once(get_social_skin_path().'/social_login.skin.php');
		?>

		<aside id="login_info">
			<h2>회원로그인 안내</h2>
			<div>
				<a href="<?php echo G5_BBS_URL ?>/password_lost.php" target="_blank" id="login_password_lost">아이디 비밀번호 찾기</a>
				<a href="./register.php">회원 가입</a>
			</div>
		</aside>

		</form>


	</div>
</div>
<script>
$(function(){
    $("#login_auto_login").click(function(){
        if (this.checked) {
            this.checked = confirm("자동로그인을 사용하시면 다음부터 회원아이디와 비밀번호를 입력하실 필요가 없습니다.\n\n공공장소에서는 개인정보가 유출될 수 있으니 사용을 자제하여 주십시오.\n\n자동로그인을 사용하시겠습니까?");
        }
    });
});


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
<!-- } 로그인 끝 -->
<style>
label {color:#ddd}
input[type=text],input[type=password] {background:transparent;color:#fff;border-bottom:1px solid #ddd}
input[type=password] {font-family: 'Roboto', Helvetica, Arial, sans-serif;}
input[type=password]::placeholder {font-family: "HeirofLight";}

/* 기본박스 */
.mbskin{
	position: relative;margin:20px auto 0;border: 1px solid #ffd505;width:400px;text-align:center;background: transparent;
	padding:10px
}

</style>
