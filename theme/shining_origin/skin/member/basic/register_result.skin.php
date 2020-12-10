<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$member_skin_url.'/style.css">', 0);
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
                        <div class="col-lg-4 col-md-6 col-10 box-shadow-2 p-0 ">
                            <div class="card border-grey border-lighten-3 px-1 py-1 m-0"  style='background:rgb(255,255,255,0.9);'>
                                <div class="card-header border-0"  style='background:none;'>
                                    <div class="text-center mb-1">
                                        <img src="<?=G5_THEME_URL?>/img/logo_login.png" alt="branding logo">
                                    </div>
                                    <div class="font-large-1  text-center mt-5">
                                       Member  registration</strong> is complete
                                    </div>
                                </div>
                                <div class="card-content">

                                    <div class="card-body">
                                    
                                    </div>
                                    
                                  <div class="form-group text-center">
                                         <a href="/" class="btn round btn-block btn-glow btn-bg-gradient-x-purple-blue col-12 mr-1 mb-1">Go To Main Page</a>
                                    </div>
                                    <p class="card-subtitle text-muted text-right font-small-3 mx-2 my-1">
                                        <span>
                                            <a href="/bbs/register_form.php?w=u" class="card-link">My Infomation</a>
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
<!-- 회원가입결과 시작 { -->
<div id="reg_result">
    <h2><strong>회원가입</strong>이 완료되었습니다.</h2>
    <p class="reg_result_p">
        <strong><?php echo get_text($mb['mb_name']); ?></strong>님의 회원가입을 진심으로 축하합니다.<br>
    </p>

    <?php if (is_use_email_certify()) {  ?>
    <p>
        회원 가입 시 입력하신 이메일 주소로 인증메일이 발송되었습니다.<br>
        발송된 인증메일을 확인하신 후 인증처리를 하시면 사이트를 원활하게 이용하실 수 있습니다.
    </p>
    <div id="result_email">
        <span>아이디</span>
        <strong><?php echo $mb['mb_id'] ?></strong><br>
        <span>이메일 주소</span>
        <strong><?php echo $mb['mb_email'] ?></strong>
    </div>
    <p>
        이메일 주소를 잘못 입력하셨다면, 사이트 관리자에게 문의해주시기 바랍니다.
    </p>
    <?php }  ?>

    <p>
        회원님의 비밀번호는 아무도 알 수 없는 암호화 코드로 저장되므로 안심하셔도 좋습니다.<br>
        아이디, 비밀번호 분실시에는 회원가입시 입력하신 이메일 주소를 이용하여 찾을 수 있습니다.
    </p>

    <p>
        회원 탈퇴는 언제든지 가능하며 일정기간이 지난 후, 회원님의 정보는 삭제하고 있습니다.<br>
        감사합니다.
    </p>

        <a href="<?php echo G5_URL ?>/" class="btn_submit">메인으로</a>

</div>
<!-- } 회원가입결과 끝 -->