<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

add_javascript('<script src="'.G5_JS_URL.'/jquery.register_form.js"></script>');

?>
<!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-wrapper-before"></div>
            <div class="content-header row">
            </div>
            <div class="content-body">
                <section class="py-3">
                    <div class="col-12 d-flex align-items-center justify-content-center">
                        <div class="col-lg-6 col-md-6 col-12 box-shadow-2 p-0 ">
                            <div class="card border-grey border-lighten-3 px-1 py-1 m-0"  style='background:rgb(255,255,255,0.9);'>
                                <div class="card-header border-0"  style='background:none;'>
                                    <div class="text-center mb-1">
                                        <img src="<?=G5_THEME_URL?>/img/logo_login.png" alt="branding logo" class='w-60' style='max-width:238px'>
                                    </div>
                                    <div class="font-large-1  text-center">
                                        Become A Member
                                    </div>
                                </div>
                                <div class="card-content">

                                    <div class="card-body">
                                    <form class='form-horizontal' id="fregisterform" name="fregisterform" action="<?php echo $register_action_url ?>" onsubmit="return fregisterform_submit(this);" method="post" enctype="multipart/form-data" autocomplete="off">
<input type="hidden" name="w" value="<?php echo $w ?>">
<input type="hidden" name="url" value="<?php echo $urlencode ?>">

                                            
                                            <fieldset class="form-group position-relative has-icon-left">
                                              <select class="required form-control round" name="mb_1" id="mb_1"   placeholder="Country of residence">
                                              	
													<?=$g5['cn_nation']?>
                                              </select>
                                                <div class="form-control-position">
                                                  <i class="ft-globe"></i>
                                                </div>
                                            </fieldset>
                                            
                                            <fieldset class="form-group position-relative has-icon-left">
                                                <input name="mb_id" type="text" class="required  form-control round" id="reg_mb_id" placeholder="Enter Your ID" chkname="Your ID" >
                                                <div class="form-control-position">
                                                    <i class="ft-user"></i>
                                                </div>
                                          </fieldset>
                                          
                                          <fieldset class="form-group position-relative has-icon-left">
                                                <input name="mb_password" type="password" class="form-control round" id="reg_mb_password" placeholder="Enter Password">
                                                <div class="form-control-position">
                                                    <i class="ft-lock"></i>
                                                </div>
                                          </fieldset>
                                            
                                            <fieldset class="form-group position-relative has-icon-left">
                                              <input name="mb_password_re" type="password" class="form-control round" id="mb_password_re" placeholder="Confirm Password">
                                                <div class="form-control-position">
                                                    <i class="ft-lock"></i>
                                                </div>
                                          </fieldset>
                                            <fieldset class="form-group position-relative has-icon-left">
                                                <input name="mb_name" type="text" class="form-control round" id="mb_name" placeholder="Choose Username">
                                                <div class="form-control-position">
                                                    <i class="ft-user"></i>
                                                </div>
                                          </fieldset>
                                            
                                          
                                          
                                           <fieldset class="form-group position-relative has-icon-left">
                                             <input name="mb_deposite_pass" type='password' class="form-control round" id="mb_deposite_pass" placeholder="E-PIN(Transfer Password)">
                                                <div class="form-control-position">
                                                  <i class="ft-lock"></i>
                                                </div>
                                          </fieldset>
                                          
                                           <fieldset class="form-group position-relative has-icon-left">
                                             <input name="mb_deposite_pass_re" type='password' class="form-control round" id="mb_deposite_pass_re" placeholder="Confirm E-PIN">
                                                <div class="form-control-position">
                                                    <i class="ft-lock"></i>
                                                </div>
                                          </fieldset>
                                          
                                           <fieldset class="form-group position-relative has-icon-left">                                               
                                          
                                              <input type="hidden" name="mb_hp" value="<?php echo $member['mb_email'] ?>" id="reg_mb_hp" >
                                              
                                             <div class='row'> 
                                             <div class="col-lg-3 col-md-3 col-12 pr-md-0	mb-1 mb-md-0"> 
                                              <select class="required form-control round" name="mb_2" id="mb_2"   placeholder="Country" >
													<?=$g5['cn_nation_tel']?>
                                              </select>
                                                <div class="form-control-position" style='padding-left:15px'>
                                                  <i class="ft-globe"></i>
                                                </div>
                                             </div>   
                                              <div class="col-lg-3 col-md-3 col-12 pr-md-0  mb-1 mb-md-0">   
                                                <input type="text" name="mb_hp1" id="mb_hp1"  class=" form-control round float-left px-1 " size="5"   placeholder='CELLPHONE' >
                                                </div>
                                             <div class="col-lg-3 col-md-3 col-12 pr-md-0 mb-1 mb-md-0">    
                                             <input type="text" name="mb_hp2" id="mb_hp2"  class=" form-control round float-left px-1" size="5" placeholder='CELLPHONE' > 
                                             </div>
                                             <div class="col-lg-3 col-md-3 col-12  mb-1 mb-md-0"> 
                                             <input type="text" name="mb_hp3" id="mb_hp3"  class=" form-control round float-left px-1" size="5"  placeholder='CELLPHONE' >
                							</div>
                                            </div>
                
                                         </fieldset>
                                            
                                          <fieldset class="form-group position-relative has-icon-left">
                                                <input name="mb_email" type="email" class="form-control round" id="reg_mb_email" placeholder="Your Email Address">
                                                <div class="form-control-position">
                                                    <i class="ft-mail"></i>
                                                </div>
                                          </fieldset>                                       
                                          
                                          
                                           <fieldset class="form-group position-relative has-icon-left">
                                                <input name="mb_recommend" type="text" class="form-control round" id="reg_mb_recommend" placeholder="Refererral ID">
                                                <div class="form-control-position">
                                                    <i class="ft-users"></i>
                                                </div>                                               
                                               
                                          </fieldset>
                                          
                                           <fieldset class="form-group position-relative has-icon-left">
                                                <input name="mb_recommend2" type="text" class="form-control round" id="reg_mb_recommend2" placeholder="Placement ID">
                                                <div class="form-control-position">
                                                    <i class="ft-users"></i>
                                                </div>
                                          </fieldset>
                                          
                                            
											

                                          <div class="form-group text-center">
                                                <button type="submit" class="btn round btn-block btn-glow btn-bg-gradient-x-purple-blue col-12 mr-1 mb-1">Register</button>
                                            </div>

                                        </form>
                                    </div>
                                    <!--p class="card-subtitle line-on-side text-muted text-center font-small-3 mx-2 my-2 ">
                                        <span>OR Sign Up Using</span>
                                    </p>
                                    <div class="text-center">
                                        <a href="#" class="btn btn-social-icon round mr-1 mb-1 btn-facebook">
                                            <span class="ft-facebook"></span>
                                        </a>
                                        <a href="#" class="btn btn-social-icon round mr-1 mb-1 btn-twitter">
                                            <span class="ft-twitter"></span>
                                        </a>
                                        <a href="#" class="btn btn-social-icon round mr-1 mb-1 btn-instagram">
                                            <span class="ft-instagram"></span>
                                        </a>
                                    </div-->

                                    <p class="card-subtitle text-muted text-right font-small-3 mx-2 my-1">
                                        <span>Already a member ?
                                            <a href="login.php" class="card-link">Sign In</a>
                                        </span>
                                    </p>
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
	function select_nation(){
		var hp=$("select[name='mb_1']").find("option:selected").attr('data-phone');		
		$("select[name='mb_2']").val(hp)		
	}
	
	$(document).ready(function(e) {
		$("select[name='mb_1']").on("change",function(){
			 select_nation()
		});
		
        select_nation()	;	
    });
	
	var hp0=$(this).find("option:selected").attr('data-phone');		
	$("select[name='mb_2']").val(hp0)		
		
    // submit 최종 폼체크
    function fregisterform_submit(f)
    {
        // 회원아이디 검사
        if (f.w.value == "") {
            var msg = reg_mb_id_check();
            if (msg) {
                alert(msg);
                f.mb_id.select();
                return false;
            }
        }

        if (f.w.value == "") {
            if (f.mb_password.value.length < 3) {
                alert("비밀번호를 3글자 이상 입력하십시오.");
                f.mb_password.focus();
                return false;
            }
        }

        if (f.mb_password.value != f.mb_password_re.value) {
            alert("비밀번호가 같지 않습니다.");
            f.mb_password_re.focus();
            return false;
        }

        if (f.mb_password.value.length > 0) {
            if (f.mb_password_re.value.length < 3) {
                alert("비밀번호를 3글자 이상 입력하십시오.");
                f.mb_password_re.focus();
                return false;
            }
        }

        // 이름 검사
		if (f.mb_name.value.length < 1) {
			alert("이름을 입력하십시오.");
			f.mb_name.focus();
			return false;
		}

		/*
		var pattern = /([^가-힣\x20])/i;
		if (pattern.test(f.mb_name.value)) {
			alert("이름은 한글로 입력하십시오.");
			f.mb_name.select();
			return false;
		}
		*/
		
		if (f.w.value == "") {
            if (f.mb_deposite_pass.value.length < 3) {
                alert("이체 비밀번호를 3글자 이상 입력하십시오.");
                f.mb_deposite_pass.focus();
                return false;
            }
        }

        if (f.mb_deposite_pass.value != f.mb_deposite_pass_re.value) {
            alert("이체 비밀번호가 같지 않습니다.");
            f.mb_deposite_pass_re.focus();
            return false;
        }

        if (f.mb_deposite_pass.value.length > 0) {
            if (f.mb_deposite_pass_re.value.length < 3) {
                alert("이체 비밀번호를 3글자 이상 입력하십시오.");
                f.mb_password_re.focus();
                return false;
            }
        }
		
		
		//휴대전화 검사		
		if(f.mb_hp1.value!='' && f.mb_hp2.value!='' && f.mb_hp3.value!=''){
			var hp=f.mb_hp1.value+"-"+f.mb_hp2.value+"-"+f.mb_hp3.value;
			f.mb_hp.value=hp;
		}		
        // 휴대폰번호 체크
        var msg = reg_mb_hp_check();
        if (msg) {
            alert(msg);
            f.reg_mb_hp.select();
            return false;
        }


        // E-mail 검사
		var msg = reg_mb_email_check();
		if (msg) {
			alert(msg);
			f.reg_mb_email.select();
			return false;
		}
	
		
		if (f.mb_recommend.value.length < 1) {
			alert("후원인 아이디를 입력하십시오.");
			f.mb_recommend.focus();
			return false;
		}
		
        if (typeof(f.mb_recommend) != "undefined" && f.mb_recommend.value) {
            if (f.mb_id.value == f.mb_recommend.value) {
                alert("본인을 후원 할 수 없습니다.");
                f.mb_recommend.focus();
                return false;
            }

            var msg = reg_mb_recommend_check();
            if (msg) {
                alert(msg);
                f.mb_recommend.select();
                return false;
            }
        }
		
		if (f.mb_recommend2.value.length < 1) {
			alert("추천인 아이디를 입력하십시오.");
			f.mb_recommend2.focus();
			return false;
		}
		 if (typeof(f.mb_recommend2) != "undefined" && f.mb_recommend2.value) {
            if (f.mb_id.value == f.mb_recommend2.value) {
                alert("본인을 추천 할 수 없습니다.");
                f.mb_recommend2.focus();
                return false;
            }

            var msg = reg_mb_recommend_check2();
            if (msg) {
                alert(msg);
                f.mb_recommend2.select();
                return false;
            }
        }
		

        //document.getElementById("btn_submit").disabled = "disabled";

        return true;
    }
    </script>

<!-- } 회원정보 입력/수정 끝 -->