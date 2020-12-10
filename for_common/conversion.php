<?php

include_once('./_common.php');

$outer_css=' stoneDetail fee';

add_javascript('<script src="'.G5_THEME_URL.'/extend/clipboard.min.js"></script>',1);
include_once('../_head.php');
$isum=get_itemsum($member[mb_id]);

?>
      
	  
        <div class="wrap">
            <div class="area area01">
                <h3><span>Transfer</span></h3>
                <ul class="common">
                    <li class="squareWB hero w50" onclick="location.href='/for_common/idDetail.php'"><span><?=$member[mb_id]?></span></li>
                    <li class="squareWB stone w50 text-left fs09 text-narrow0 ">&nbsp;<span class="stoneBuystone balance-value-enable-i"><?=number_format2($rpoint['i']['_enable'],1)?></span>
								
                </ul>
            </div>
			
            <div class="area area02 py-1">
                <ul class="buy sell">                    
					<li class="squareWB"> <span class="f_yellow text-narrow0 "><?=number_format2($rpoint['e']['_enable'])?></span>
					<span class="condition">매너포인트</span>
					</li>

                    <li class="squareWB"><span class="f_yellow text-narrow0  balance-value-enable-b"><?=number_format2($rpoint['b']['_enable'])?></span>
					<span    class="condition">꿀단지</span></li>
					
					<li class="squareWB w-100 mt-2" ><span    class="f_yellow text-narrow0  balance-value-enable-s"><?=number_format2($rpoint['s']['_enable'],0)?></span><span class="condition confirm">쇼핑포인트</span>
					</li>
					
					<li class="squareWB w-100 mt-2"  onclick="location.href='/for_common/stonedetail.php'" ><span    class="f_yellow text-narrow0">$<?=number_format2($isum[tot][price])?></span><span class="condition confirm">보유금액</span>
					</li>
                </ul>
            </div>
			
			
			
        <div class="area area03 squareWB">

		<form id="transferform" name="transferform" action="./coin_conversion_update.php" onsubmit="return  transferform_submit(this);" method="post"  autocomplete="off" >                                                
		<input type="hidden" name="w" value="u">
		<input type="hidden" name="mb_id" value="<?=$member['mb_id']?>">
		<input type="hidden" name="tr_token" value="b">


		<ul >					

			<li class='fs125'>
			 <p>변환가능 <?=$g5[cn_cointype]['b']?> : <is class='balance-value-enable' ><?=number_format($rpoint['b']['_enable'])?></is></p>
			</li>
			
			<li class='fs125'>
			<p>변환될 포인트</p> 
			<p>
			<label><input type='radio' name='tr_set_token' value='i' checked data-name='꽃<?=$g5[cn_cointype]['i']?>' > <?=$g5[cn_cointype]['i']?></label>&nbsp;&nbsp;
			<label><input type='radio' name='tr_set_token' value='s'  data-name='<?=$g5[cn_cointype]['s']?>' > <?=$g5[cn_cointype]['s']?></label>
			</p>

			</li>
			
			<li style="margin-top:10px;" class='fs125'>
				<p>변환할 수량</p>
			   <input name="tr_amt" type="text" id="tr_amt" class='common-input number-comma w-100'  placeholder="변환수량" value='0'>  

			</li>

			<li style="margin-top:10px;"  class='fs125'>
			<p>변환될 수량</p>
			<p  class='w-100 text-left'><is id='amt_name'>꽃<?=$g5[cn_cointype]['i']?></is> : <is id='amt_i'>0</is> 변환 </p>

			</li>


		</ul>

                <div class="btns w-100">
                    <ul class='w-100'>
                        <li class='w-100 text-center'>
                            <button type='submit' >변환실행</button>
                        </li>                        
                    </ul>
        </div>
				</form>
            </div>
			
						
			
</div>


<script>


$(document).ready(function () {
	$('input[name=tr_set_token]').on('change',function () {		
		sum();
		
	});
	$('input[name=tr_amt]').on('keypress keydown keyup',function () {		
		sum();
		
	});
});

function sum(){
	
		var tr_amt=parseFloat(no_comma($('input[name=tr_amt]').val()));
		var tr_name=$('input[name=tr_set_token]:checked').attr('data-name');
		var tr_set_token=$('input[name=tr_set_token]:checked').val();
		var amt=0;
		
		if(tr_set_token=='i') amt=tr_amt * (<?=$sise['sise_b']?>/<?=$sise['sise_i']?>) ;
		else if(tr_set_token=='s') amt=tr_amt * (<?=$sise['sise_b']?>/<?=$sise['sise_s']?>) 
		
		var amt_i=Math.floor(amt);
		
		$("#amt_name").html(tr_name);				
		$("#amt_i").html(inputNumberFormat(amt_i));

}

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
	Swal.fire('꿀단지 변환기능이 정지되었습니다.');
	return false;
	
	$(f).find('button[type=submit]').attr('disabled',true);
	event.preventDefault();
	
	Swal.fire({
	  title: '',
	  html: "변환을 진행하시겠습니까? 변환된 포인트는 다시 되돌릴수 없습니다!",
	  icon: 'warning',
	  showCancelButton: true,
	  confirmButtonColor: '#3085d6',
	  cancelButtonColor: '#d33',
	  confirmButtonText: '네 변환합니다',
	  cancelButtonText: '아니오'
	}).then((result) => {
	  if (result.value) {
	  
			
			var formData = $(f).serialize();	

			$.ajax({
				type: "POST",
				url: "./coin_conversion_update.php",
				data:formData,
				cache: false,
				async: false,
				dataType:"json",
				success: function(data) {

					if(data.result){
						f.reset();				
						$('.balance-value-enable-b').html(data.datas['enable_amt_b']);
						$('.balance-value-enable-s').html(data.datas['enable_amt_s']);
						$('.balance-value-enable-i').html(data.datas['enable_amt_i']);

						Swal.fire('변환이 완료 되었습니다');   
					}
					else Swal.fire(data.message);       
				}
			});		
		
	  }
	})
		
	$(f).find('button[type=submit]').attr('disabled',false);
	return;		 
			 
	
	
}




</script>
<?	
include_once('../_tail.php');
?>
