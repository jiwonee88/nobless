<?php
if (!defined('_GNUBOARD_')) {
    exit;
} // 개별 페이지 접근 불가

add_javascript('<script src="'.G5_JS_URL.'/jquery.register_form.js"></script>');

?>
<style>
.main_con input {width:100%;border-color:transparent;border-bottom:1px #ddd solid; height:50px;margin-bottom:10px}
#sign_up_btn {width: 100%;
    height: 3.125rem;
    line-height: 3.125rem;
    margin: 32px 0 0;
    background: rgba(0,0,0,0.6);
    position: relative;
    text-align: center;
    border: 2px solid #fff;
    color: #fff;
    border-radius: 10px;}
input[type=password] {font-family: 'Roboto', Helvetica, Arial, sans-serif;}
input[type=password]::placeholder {font-family: "HeirofLight";}

.w-70{width:70% !important;}
.w-30{width:30% !important;}
#hp_certi_btn , #hp_comfirm_btn{
    width: 100%;
    height: 3.125rem;
    margin: 4.33rem 0 2.25rem;
    background-color: rgba(0,0,0,0.9);
    outline: none;
    color: #fff;
    text-transform: uppercase;
    font-size: 1rem;
    letter-spacing: 1px;
    color: #07243e;
    font-weight: 700;
    color: #ffffff;}

</style>
<div id="wrap" style="height:100vh">
<form class='form-horizontal' id="fregisterform" name="fregisterform" action="<?php echo $register_action_url ?>" onsubmit="return fregisterform_submit(this);" method="post" enctype="multipart/form-data" autocomplete="off"  >
<input type="hidden" name="w" value="<?php echo $w ?>">
<input type="hidden" name="url" value="<?php echo $urlencode ?>">
<input type="hidden" name="mb_id" value="<?php echo $mb_id ?>">


	<div style="padding:50px 10px">
		<img src="<?php echo G5_THEME_URL ?>/images/sec1_img.png" style="width:100%" width=100% alt="">
        <div class="wrap main_con"> 
		
 		<div class="box">
		<input name="mb_id" type="text"  id="reg_mb_id" placeholder="아이디" required >
		</div>
		
		<!--
		<div class="box">
		<select name='mb_nation' id="reg_mb_nation"  >
		<?//=$g5['cn_nation_tel']?>
		</select>
		</div>
		
		<div class="box">
		<input name="mb_hp" type="text"  id="reg_mb_hp" placeholder="핸드폰번호" required class='w-70' ><button type='submit' class='w-30 m-0 ' id='hp_certi_btn' >인증번호</button>
		</div>
	
		<div class="box">
		<input name="mb_hp_certi" type="text"  id="reg_mb_hp_certi" placeholder="인증번호입력" required class='w-70' ><button type='submit' class='w-30 m-0' id='hp_comfirm_btn' >확 인</button>
		</div>
		-->
		
		<!--div class="box">
		<input name="mb_email" type="email"  id="reg_mb_email" placeholder="E-mail" required >
		</div-->
		
		<div class="box">
		<input name="mb_password" type="password" class="input-text password-input" id="register-password" placeholder="비밀번호" required autocomplete='off'  >
		</div>

		<div class="box">
			<input name="mb_password_re" type="password" class="input-text password-input" id="register-password2" placeholder="비밀번호 확인" required autocomplete='off' >
		</div>

		<div class="box">
		<input name="mb_recommend" type="text"  id="reg_mb_recommend" placeholder="레퍼럴 코드" autocomplete='off' value="<?=get_cookie('refid')?>" 
		</div>

		<button type='submit' class='mt-3' id='sign_up_btn' >가입하기</button>
	
	</div>
	
	</div>
          </div>
	</form>


</div>
<script>		

$(document).ready(function() {
	
 	$('#hp_certi_btn').on('click',function(){
		event.preventDefault();
		
		var mb_id= $('#reg_mb_id').val();
		var nation_val= $('#reg_mb_nation').val();
		var hp_val=$('#reg_mb_hp').val();
		
		if(hp_val=='') Swal.fire('휴대전화 번호를 입력하세요');
		
			$.ajax({
			type: "POST",
			url: './ajax.mb_hp_certi.php',
			data:{mb_id:mb_id,nation:nation_val ,hp:hp_val,mode:'create'},
			cache: false,
			async: false,
			dataType:"json",
			success: function(data) {
				if(data.result==true){				
					Swal.fire('인증번호를 전송하였습니다.\n전송받은 인증번호를 입력하세요');
				}
				else Swal.fire("",data.message,"warning");
			}
		});					
	
	});	
	
	
	$('#hp_comfirm_btn').on('click',function(){
		event.preventDefault();
		
		var mb_id= $('#reg_mb_id').val();
		var hp_val=$('#reg_mb_hp').val();
		var nation_val=$('#reg_mb_nation').val();
		var pass_val=$('#reg_mb_hp_certi').val()
		
			$.ajax({
			type: "POST",
			url: './ajax.mb_hp_certi.php',
			data:{mb_id:mb_id,nation:nation_val ,hp:hp_val,mode:'check',pass:pass_val},
			cache: false,
			async: false,
			dataType:"json",
			success: function(data) {
				if(data.result){				
					$('#reg_mb_hp_certi,#mb_reg_hp').attr('readonly',true);
					$('#hp_comfirm_btn,#hp_certi_btn').attr('disabled',true);
					
					Swal.fire("",'인증번호가 확인되었습니다',"warning");
					
				}
				else Swal.fire("",data.message,"warning");
			}
		});					
	
	});	
			
});
	
    // submit 최종 폼체크
    function fregisterform_submit(f)
    {
		 
		 event.preventDefault();
       
	   // E-mail 검사
		if($("#reg_mb_id").val()!=''){
			var msg = reg_mb_id_check();
			if (msg) {
				Swal.fire("",msg,"warning");
				f.reg_mb_id.select();
				return false;
			}
		}		       
		// 휴대폰
		if($("#reg_mb_hp").val()!=''){
			var msg = reg_mb_hp_check();
			if (msg) {
				Swal.fire("",msg,"warning");
				f.reg_mb_hp.select();
				return false;
			}
		}		
		
		/*
        // E-mail 검사
		if($("#reg_mb_email").val()!=''){
			var msg = reg_mb_email_check();
			if (msg) {
				Swal.fire("",msg,"warning");
				f.reg_mb_email.select();
				return false;
			}
		}
		*/

        if (f.w.value == "") {
            if (f.mb_password.value.length < 3) {
				Swal.fire("","Please enter at least 3 characters in your password.","warning");
                f.mb_password.focus();
                return false;
            }
        }

        if (f.mb_password.value != f.mb_password_re.value) {
            Swal.fire("","Passwords are not the same","warning");
            f.mb_password_re.focus();
            return false;
        }

        if (f.mb_password.value.length > 0) {
            if (f.mb_password_re.value.length < 3) {
               Swal.fire("","Please enter at least 3 characters in your password.","warning");
                f.mb_password_re.focus();
                return false;
            }
        }	
		
		if (f.mb_recommend.value.length < 1) {
			Swal.fire("","Please enter your Referral Code","warning");
			f.mb_recommend.focus();
			return false;
		}
		
        if (typeof(f.mb_recommend) != "undefined" && f.mb_recommend.value) {
            if (f.mb_id.value == f.mb_recommend.value) {
               Swal.fire("","You cannot enter your Referral Code into the Referral Code","warning");
                f.mb_recommend.focus();
                return false;
            }

            var msg = reg_mb_recommend_check();
            if (msg) {
               Swal.fire("",msg,"warning");
                f.mb_recommend.select();
                return false;
            }
        }
		
		/*
		 if (f.mb_deposite_pass.value.length < 3) {
			Swal.fire("","Please enter at least 3 characters in your transfer password.","warning");
			f.mb_deposite_pass.focus();
			return false;           
        }
		*/
	
		
		var formData = $(f).serialize();	
		document.getElementById("sign_up_btn").disabled = "disabled";
		
		$.ajax({
			type: "POST",
			url: $(f).attr('action'),
			data:formData,
			cache: false,
			async: false,
			dataType:"json",
			success: function(data) {
				if(data.result==true){									
					Swal.fire({title:"",text:"Congratulations on joining",icon:'success',
				  	onClose: () => {					
					if(data.datas['goto_url']) document.location.href=data.datas['goto_url'];
					else  document.location.href='/';
				  }
				 });	
				}
				else Swal.fire("",data.message,"error");
			}
		});		
		
		document.getElementById("sign_up_btn").disabled = false;
		
		

    }
    </script>

<!-- } 회원정보 입력/수정 끝 -->
