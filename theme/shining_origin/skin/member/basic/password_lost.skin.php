<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
?>


 <div class="wrap">
            <div class="area area-tit">
                <h3><span>암호변경</span></h3>                
            </div>
			
<form class="form-horizontal"  name="findpassform" action="/bbs/password_renew.php" onsubmit="return findpassform_submit(this);" method="post">
	
	
	<div class="profile pt-2">
	
        <div class="wrap"> 
 			<div class="box">
			<input name="mb_id" type="text"  id="reg_mb_id" placeholder="아이디" required >
			</div>
		
            <div class="box">
			<select name='mb_nation' id="reg_mb_nation" required  >
			<?=$g5['cn_nation_tel']?>
			</select>
			</div>

			<div class="box">
			<input name="mb_hp" type="text"  id="reg_mb_hp" placeholder="핸드폰번호" required class='w-70' ><button type='submit' class='w-30 m-0 ' id='hp_certi_btn' >인증번호</button>
			</div>

			<div class="box">
			<input name="mb_hp_certi" type="text"  id="reg_mb_hp_certi" placeholder="인증번호입력" required class='w-70' ><button type='submit' class='w-30 m-0' id='hp_comfirm_btn' >확 인</button>
			</div>		
		
            <div class="box pswd">
                <input name="mb_password" type="password" class="input-text" id="mb_password" required  placeholder="새로운 비밀번호"  autocomplete='off' >
            </div>
			
			<div class="box">
				<input name="mb_password_re" type="password" class="input-text password-input" id="register-password2" placeholder="비밀번호 확인" required autocomplete='off' >
			</div>


			<button type='submit' id='sign_up_btn' >암호변경하기</button>
            <div class="or">
                <span>or</span>
            </div>
            <div class="signUp">
               <a href="/bbs/login.php"  >로그인</a>
                
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
			data:{mb_id:mb_id,nation:nation_val ,hp:hp_val,mode:'findpass'},
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
					
					Swal.fire("",'인증번호가 확인되었습니다. 변경할 새로운 암호를 입력하세요',"warning");
					
				}
				else Swal.fire("",data.message,"warning");
			}
		});					
	
	});	
			
});
	
    // submit 최종 폼체크
    function findpassform_submit(f)
    {
		 
		 event.preventDefault();
            
		if (f.mb_hp.value == "") {            
			Swal.fire("","휴대인증번호를 입력하세요","warning");
			f.mb_hp.focus();
			return false;
        }
		
		if (f.mb_hp_certi.value == "") {
            if (f.mb_hp_certi.value.length < 3) {			
				Swal.fire("","인증번호를 입력하세요","warning");				
                f.mb_hp_certi.focus();
                return false;
            }
        }
		
		if (f.mb_password.value.length < 3) {
			Swal.fire("","새로운 암호를 입력해 주세요.","warning");
			f.mb_password.focus();
			return false;
		}

        if (f.mb_password.value != f.mb_password_re.value) {
            Swal.fire("","입력하신 암호가 일치 하지 않습니다","warning");
            f.mb_password_re.focus();
            return false;
        }

        if (f.mb_password.value.length > 0) {
            if (f.mb_password_re.value.length < 3) {
               Swal.fire("","암호는 최소 3 글자 이상으로 작성해 주세요.","warning");
                f.mb_password_re.focus();
                return false;
            }
        }	
		
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
					Swal.fire({title:"",text:"암호가 변경되었습니다. 새로운 암호로 로그인해 주십시요",icon:'success',
				  	onClose: () => {					
					document.location.href='/bbs/login.php';
				  }
				 });	
				}
				else Swal.fire("",data.message,"error");
			}
		});		
		
		document.getElementById("sign_up_btn").disabled = false;
		
		

    }
    </script>
