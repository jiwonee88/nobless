<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

add_javascript('<script src="'.G5_JS_URL.'/jquery.register_form.js"></script>');
add_stylesheet('<link rel="stylesheet" type="text/css" href="'.G5_THEME_URL.'/app-assets/css/pages/users.css">');
?>


<div class="profile">
     
<form class='form-horizontal' id="fregisterform" name="fregisterform" action="<?php echo $register_action_url ?>" onsubmit="return fregisterform_submit(this);" method="post" enctype="multipart/form-data" autocomplete="off"  >
<input type="hidden" name="w" value="<?php echo $w ?>">
<input type="hidden" name="url" value="<?php echo $urlencode ?>">
<input type="hidden" name="mb_id" value="<?php echo $mb_id ?>">
<input type="hidden" name="old_mb_hp" value="<?php echo $member[mb_hp] ?>">
   
		
		<div class='position-relative'>
		<h3 class='strike-line' ><span>PROFILE</span></h3>
		</div>
		
        <div class="wrap"> 		
		
		
		<div class="box">
		아이디: <?=$member['mb_id']?>           
		</div>
		
		<div class="box">
		가입일 : <?=substr($member['mb_datetime'],0,10)?>           
		</div>
		
		<div class="box">
		Refferral ID : <?=$member[mb_recommend]?>
		</div>
		
		<div class="box">
		<select name='mb_nation' id="reg_mb_nation"  >
		<?=$g5['cn_nation_tel']?>
		</select>
		</div>
		
		<p>번호를 변경하시면 재 인증을 받으셔야 합니다.</p>
		<div class="box">
		<input name="mb_hp" type="tel"  id="reg_mb_hp" placeholder="Mobile Phone" required class='' value='<?=$member[mb_hp]?>' ><button type='submit' class='w-30 m-0 d-none ' id='hp_certi_btn' >인증번호</button>
		</div>		
		
		<div class="box d-none" id='reg_mb_hp_certi_tr' >
		<input name="mb_hp_certi" type="text"  id="reg_mb_hp_certi" placeholder="인증번호입력" class='w-70' ><button type='submit' class='w-30 m-0' id='hp_comfirm_btn' >확 인</button>
		</div>
		
		<p>암호를 변경하시려면 새로운 암호를 입력하세요</p>
		<div class="box">
		<input name="mb_password" type="password" class="input-text password-input" id="register-password" placeholder="Password"  autocomplete='off'  >
		</div>

		<div class="box">
			<input name="mb_password_re" type="password" class="input-text password-input" id="register-password2" placeholder="confirm Password" autocomplete='off' >
		</div>

		

		<button type='submit' class='mt-3' id='sign_up_btn' >EDIT PROFILE</button>
	
	</div>
          
	</form>
	
	</div>




<script>

$(document).ready(function() {
	
	$('#reg_mb_hp').on('change',function(){
		if($('#reg_mb_hp').val()!=$('input[name=old_mb_hp]').val()){
			$('#hp_certi_btn,#reg_mb_hp_certi_tr').removeClass('d-none');
			$('#reg_mb_hp').addClass('w-70');
		}else {
			$('#hp_certi_btn,#reg_mb_hp_certi_tr').addClass('d-none');
			$('#reg_mb_hp').removeClass('w-70');
		}
	
	});
	
 	$('#hp_certi_btn').on('click',function(){
		event.preventDefault();
		
		var mb_id= $('input[name=mb_id]').val();
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
		
		var mb_id= $('input[name=mb_id]').val();
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
		
		if($('#reg_mb_hp').val()!=$('input[name=old_mb_hp]').val()){
			if($('mb_hp_certi').val()=='') swal.fire("휴대전화 인증을 진행하셔야 합니다");
		}		
		
        if (f.mb_password.value != f.mb_password_re.value) {
            swal.fire("암호가 동일하지 않습니다.");
            f.mb_password_re.focus();
            return false;
        }

        if (f.mb_password.value.length > 0) {
            if (f.mb_password_re.value.length < 3) {
                 swal.fire("암호는 최소 3글자 이상을 입력하세요");
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
		
		
        //document.getElementById("btn_submit").disabled = "disabled";

        return true;
    }
    </script>

<!-- } 회원정보 입력/수정 끝 -->