<?php
define("IS_ENTRUST",true) ;
include_once('./_common.php');

$phase='stake';
include_once('./entrust.header.php');


if(!array_key_exists($coin_stx,$g5['cn_cointype']) || $coin_stx=='') $coin_stx='b';
$coin_name=$g5['cn_cointype_sym'][$coin_stx];
$coin_sym=$g5['cn_cointype'][$coin_stx];

$phase='stake';
?>   

	<div class="wrap"> 
            <div class="area">
                <h3><span>ORDER</span></h3>
                <ul class="aboutYou">
                    <li class="squareWB hero"><span><?=$member[mb_id]?></span></li>
					<li class="squareWB stone"><span><?=number_format2($isim)?></span><a class='btn btn-xs btn-grad-yellow'>구매</a></li>
                </ul>
                <div class="total">총 거래수:0</div>
            </div>     
			
<form class='form-horizontal' id="fregisterform" name="fregisterform" action="<?=G5_URL?>/for_common/staking_coin_update.php" onsubmit="return fregisterform_submit(this);" method="post" enctype="multipart/form-data" autocomplete="off" target='_blank' >                                                
<input type="hidden" name="w" value="u">
<input type="hidden" name="max_enable" value="<?=$rpoint[$coin_stx]['_enable']?>">
<input type="hidden"  name='sk_token'   value='<?=$coin_stx?>' checked="checked"  /> 
		<section class="option-wrap option-wrap-header">

			<p class="option-title">Asset</p>

			<div class="option-list" >

				<div class="deem" id="deem"></div>

				<div class="on" id="select">

					<div class="ico <?=strtolower($coin_sym)?>"><?=$coin_name?></div>	

				</div>

				<div class="list">

					<ul>

						 <?
					foreach($g5['cn_cointype'] as $k=> $v) {				
					if($k==$coin_stx) continue;
					$n=strtolower($v);
					$sym=$g5['cn_cointype_sym'][$k];

					?>
					<li data-coin='<?=$k?>' data-sym='<?=strtolower($g5['cn_cointype'][$k])?>' ><div class="ico <?=$n?>"><a href='/for_common/entrust.php?coin_stx=<?=$k?>&pmode=<?=$pmode?>'><?=$sym?></a></div></li>
					<? }?>	



					</ul>

				</div>

			</div>

			<p class="sub-desc">Balance : <b class='balance-value' ><?=number_format2($rpoint[$coin_stx]['_enable'],6)?> </b> <?=$coin_sym?></p>
		</section>


	  

		<section class="option-wrap">

			

			<p class="option-title">Staking Amount</p>

			<div class="input-wrap">

				<input name="sk_amt" type="text" id="sk_amt" class='number-comma' placeholder="Input Amount">
				<span class='coin-appendix'><?=$coin_sym?></span>	
			</div>
		
			

			<div class="button">

				<div class="all all-stake-btn">All</div>      
				
			</div>

		</section>

        

		<section class="agree-wrap">

			<div class="desc">Bonus payments may be suspended if the amount of assets not sufficient causes the USD value of the asset to fall due to changes.</div> 

			<p class="sub-desc">

				<span></span>&nbsp;

				<input id="checkbox1" name="checkbox" type="checkbox" class='designed' >
<label for='checkbox1' >I Agree Staking Rules</label> 

				<!--<input id="checkbox1" name="checkbox" type="checkbox" checked="checked"> <label for="checkbox1">Choice A</label>-->

			</p>

		</section>

		  

		<section class="button-wrap">

			<input type='submit' class="start" value='Start Staking' />  

		</section>
		</form>		

</div>


</div>
<script>

/* 출금페이지 */

$(function(){

//모두 스테이
  $(".all-stake-btn").on('click',function(){
		$("input[name='sk_amt']").val(inputNumberFormat($("input[name='max_enable']").val()));
		
  });

});



    // submit 최종 폼체크
    function fregisterform_submit(f)
    {
       	  
	   	var sk_token=$("input[name='sk_token']").val();
		
			
		if (!sk_token) {
            //Swal.fire("Please select a coin.");
            r//eturn false;
        }
		if (f.sk_amt.value.length==0 || f.sk_amt.value==0) {
            Swal.fire("Enter the quantity to be staked.");
            f.sk_amt.focus();
            return false;
        }
					 
		if ($('input[name=checkbox]').prop('checked')==false) {
            Swal.fire("You must agree to the staking rules.");
            f.sk_amt.focus();
            return false;
        }
		
		
		var sum=parseFloat(no_comma($("input[name='max_enable']").val()));
		var amt=parseFloat(no_comma($("input[name='sk_amt']").val()))*1;
				
		if (sum < amt ) {
            Swal.fire("There is not enough quantity to hold");
            f.sk_amt.focus();
            return false;
        }	
		/*
		if (f.pass.value.length==0) {
            Swal.fire("Please enter a password.");
            f.pass.focus();
            return false;
        }
		
		
		// 비밀번호 검사
		var msg = mb_deposite_pass_check($("input[name=mb_id]").val(),$("input[name=pass]").val());
		if (msg) {
			Swal.fire(msg);
			f.pass.select();
			return false;
		}
        */
		if (!confirm('Do you perform staking?' ) ){
            return false;
        }	
		
		var formData = $(f).serialize();			
		
		$.ajax({
			type: "POST",
			url: "<?=G5_URL?>/for_common/staking_coin_update.php",
			data:formData,
			cache: false,
			async: false,
			dataType:"json",
			success: function(data) {
				
				if(data.result){					
					f.reset();
									
					$('.balance-value').html(data.datas['max_enable']);
					//$('.balance-value-usd').html(data.datas['max_enable_usd'])
					$("input[name='max_enable']").val(data.datas['max_enable']);

					Swal.fire('Staking is complete');   

				}
				else Swal.fire(data.message);       
			}
		});		
		
		event.preventDefault();
		
        return;
		
		
    }
</script>

<?
include_once('./entrust.footer.php');
