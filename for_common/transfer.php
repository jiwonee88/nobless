<?php

include_once('./_common.php');

$outer_css=' stoneDetail fee';

add_javascript('<script src="'.G5_THEME_URL.'/extend/clipboard.min.js"></script>',1);
include_once('../_head.php');
$isum=get_itemsum($member[mb_id]);

//전송 가능 금액
$enable_sum=get_eanble_trans($member[mb_id],$rpoint,'i');

?>
      
	  
        <div class="wrap">
            <div class="area area01">
                <h3><span>Transfer</span></h3>
                <ul class="common">
                    <li class="squareWB hero w50" onclick="location.href='/for_common/idDetail.php'"><span><?=$member[mb_id]?></span></li>
                    <li class="squareWB stone w50 text-left fs09 text-narrow0 ">&nbsp;<span class="stoneBuystone balance-value"><?=number_format2($rpoint['i']['_enable'])?></span>
								
                </ul>
            </div>
			
            <div class="area area02">
                <ul class="buy sell">                    
					<li class="squareWB"> <span class="f_yellow text-narrow0"><?=number_format2($rpoint['e']['_enable'])?></span>
					<span class="condition">매너포인트</span>
					</li>

                    <li class="squareWB"><span class="f_yellow text-narrow0"><?=number_format2($rpoint['b']['_enable'])?></span>
					<span    class="condition">꿀단지</span></li>
					
					<li class="squareWB w-100 mt-2"  onclick="location.href='/for_common/stonedetail.php'" ><span    class="f_yellow text-narrow0">$<?=number_format2($isum[tot][price])?></span><span class="condition confirm">보유금액</span>
					</li>
                </ul>
            </div>
			
			
			
        <div class="area area03 squareWB">

		<form id="transferform" name="transferform" action="./coin_transfer_others.php" onsubmit="return  transferform_submit(this);" method="post"  autocomplete="off" >                                                
		<input type="hidden" name="w" value="u">
		<input type="hidden" name="mb_id" value="<?=$member['mb_id']?>">
		<input type="hidden" name="tr_token" value="i">

			
                <ul >					
					
					<li class='fs125'>
                     <p>전송가능 꽃 : <is class='balance-value-enable' ><?=number_format($enable_sum)?></is></p>
					 
                    </li>
					
                    <li class='fs125'>
                     <p>받는 회원 아이디</p>
                     <input name="tmb_id" type="text" id="tmb_id" class='common-input w-100' value="<?=$_POST['scanned_id']?>"  placeholder="회원 아이디">
                    </li>
										
                    <li style="margin-top:10px;" class='fs125'>
                        <p>전송수량</p>
                       <input name="tr_amt" type="text" id="tr_amt" class='common-input number-comma w-100'  placeholder="전송수량">  
					 
                    </li>
					
					<!--li style="margin-top:10px;">
                        <p>이체암호</p>
                      <input name="pass" type="password" id="pass"  required placeholder="이체암호" class='common-input number-comma w-100'  >  
					 
                    </li-->
					
                   
        </ul>

                <div class="btns w-100">
                    <ul class='w-100'>
                        <li class='w-100 text-center'>
                            <button type='submit' >전송실행</button>
                        </li>                        
                    </ul>
        </div>
				</form>
            </div>
			
						
			
</div>


<script>


$(document).ready(function () {

});

// submit 최종 폼체크
function transferform_submit(f){

	/*
	// 이체 비밀번호 검사
	var msg = mb_deposite_pass_check($("input[name=mb_id]").val(),$("input[name=pass]").val());
	if (msg) {
		Swal.fire(msg);
		f.pass.select();
		return false;
	}
	*/
	$(f).find('button[type=submit]').attr('disabled',true);
	event.preventDefault();
	var formData = $(f).serialize();	

	$.ajax({
		type: "POST",
		url: "./coin_transfer_others.php",
		data:formData,
		cache: false,
		async: false,
		dataType:"json",
		success: function(data) {

			if(data.result){					

				f.reset();

				$('.balance-value').html(data.datas['enable_amt']);
				$('.balance-value-enable').html(data.datas['trans_enable_amt']);
				//$('.balance-value-usd').html(data.datas['max_enable_usd'])
				//$("input[name='max_enable']").val(data.datas['max_enable']);

				Swal.fire('전송이 완료 되었습니다');   
			}
			else Swal.fire(data.message);       
		}
	});		
	$(f).find('button[type=submit]').attr('disabled',false);
	return;

}




</script>
<?	
include_once('../_tail.php');
?>
