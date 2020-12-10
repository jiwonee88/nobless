<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
?>

<div class="bg"></div>

<form method="post" name="fregister_email" action="<?php echo G5_HTTPS_BBS_URL.'/register_email_update.php'; ?>" onsubmit="return fregister_email_submit(this);">
<input type="hidden" name="mb_id" value="<?php echo $mb_id; ?>">
    <div class="main main-login animsition " data-animation-in='fade-in' data-animation-out='fade-out-left' >
		
		
        <div class="login">
            <div class="top-content">
                <div class="top-logo"></div>
                <div class="top-title">ITEN</div>
                <div class="top-subtitle">The new <span style="color:#278cff;">trading platform</span></div>
            </div>
            <div class="content">
				<p class="text-center py-1">If you do not receive the mail authentication, you can change the email address of the member information.</p>

                <input name="mb_email" type="email" class="input-text" id="reg_mb_id" required placeholder="E-mail" value="<?php echo $mb['mb_email']; ?>">

                <div class="content-button">
                    <input  class="input-button w-100" type="submit" value="Change authentication mail" >
            </div>
        </div>
        </div>
		<div  class="register-button"><a href="/bbs/register_form.php"  class='animsition-link' >Register</a></div>
      
		
		 
    </div>
</form>
	
<script>		
function fregister_email_submit(f)
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
			
				Swal.fire({title:"",text:data.message,icon:"error"
				,onClose: () => {
					if(data.datas['goto_url']) document.location.href=data.datas['goto_url'];
					else  document.location.href='/';
				  }						
				});		
				
				
			}
			else Swal.fire({title:"",text:data.message,icon:"warning"
			,onClose: () => {
					if(data.datas['goto_url']) document.location.href=data.datas['goto_url'];
				  }						
			});	
			
		}
	});		

	event.preventDefault();

	return;
}

</script>