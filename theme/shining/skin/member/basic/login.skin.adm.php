<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
// add_stylesheet('<link rel="stylesheet" href="'.$member_skin_url.'/style.css">', 0);
?>
<!--<div style='position:absolute;left:50%;top:50%;width:600px;transform: translate(-50%,-50%);'>-->
<!--<h2 class='sub-title1 text-center' ><img src='--><?//=G5_THEME_URL?><!--/img/shield.png' width=35 > 관리자 로그인</h2>-->
<!-- 로그인 시작 { -->
<!--<div id="mb_login" class="p-5 mbskin border rounded shadow">-->
<!--   -->
<!--    <form name="flogin" action="--><?php //echo $login_action_url ?><!--" onsubmit="return flogin_submit(this);" method="post">-->
<!--    <input type="hidden" name="url" value="--><?php //echo $login_url ?><!--">-->
<!---->
<!--    <fieldset id="login_fs">        -->
<!--        <input type="text" name="mb_id" id="login_id" required class="form-control  form-control-lg required mt-2" size="20" maxLength="20" placeholder="아이디 또는 이메일">-->
<!--        <input type="password" name="mb_password" id="login_pw" required class="form-control  form-control-lg  required  mt-2" size="20" maxLength="20" placeholder="비밀번호">-->
<!--        <input type="submit" value="로그인" class="btn btn-lg btn-danger  w-100  mt-2">-->
<!--</fieldset>-->
<!---->
<!--    </form>-->
<!---->
<!---->
<!--</div>-->
<!--<p class='my-3 text-center' >Copyrightⓒ --><?//=$config[cf_title]?><!-- All right reserved.</p>-->
<!--</div>-->


<div style='position:absolute;left:50%;top:50%;width:600px;transform: translate(-50%,-50%);'>
    <h2 class='sub-title1 text-center' >관리자 로그인</h2>
    <!-- 로그인 시작 { -->
    <div id="mb_login" class="p-5 mbskin border rounded shadow">

        <form name="flogin" action="<?php echo $login_action_url ?>" onsubmit="return flogin_submit(this);" method="post">
            <input type="hidden" name="url" value="<?php echo $login_url ?>">

            <fieldset id="login_fs">
                <input type="text" name="mb_id" id="login_id" required class="form-control  form-control-lg required mt-2" size="20" maxLength="20" placeholder="아이디 또는 이메일">
                <input type="password" name="mb_password" id="login_pw" required class="form-control  form-control-lg  required  mt-2" size="20" maxLength="20" placeholder="비밀번호">
                <input type="submit" value="로그인" class="btn btn-lg btn-danger  w-100  mt-2">
            </fieldset>

        </form>


    </div>
</div>
<script>
$(function(){
/*
    $("#login_auto_login").click(function(){
        if (this.checked) {
            this.checked = confirm("자동로그인을 사용하시면 다음부터 회원아이디와 비밀번호를 입력하실 필요가 없습니다.\n\n공공장소에서는 개인정보가 유출될 수 있으니 사용을 자제하여 주십시오.\n\n자동로그인을 사용하시겠습니까?");
        }
    });
*/
});

function flogin_submit(f)
{
  var formData = $(f).serialize();	

	$.ajax({
		type: "POST",
		url: $(f).attr('action'),
		data:formData,
		cache: false,
		async: false,
		dataType:"json",
		success: function(data) {
			if(data.result){				

				if(data.datas['goto_url']) document.location.href=data.datas['goto_url'];
				else  document.location.href='/';
				
			}
			else Swal.fire("",data.message,"warning");
		}
	});		

	event.preventDefault();

	return;
}
</script>
<!-- } 로그인 끝 -->
