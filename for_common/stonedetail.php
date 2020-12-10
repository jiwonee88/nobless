<?php

include_once('./_common.php');

$outer_css=' stoneDetail';

include_once('../_head.php');

$isum=get_itemsum($member[mb_id]);
?>
<div id="Contents" class="sub_con">
    
    <div id="sec1" class="sec_wrap">
        <img src="<?=G5_THEME_URL?>/images/holding_img1.png" width="100%" />
    </div>
    
    <ul id="sec2" class="sec_wrap sec2_wrap">
        <li class="c_pink">$<?=number_format2($rpoint['i']['_enable'])?></li>
    </ul>

    <div class="mt2em mb1-5em"><img src="<?=G5_THEME_URL?>/images/sec3_line.png" width="100%" /></div>

    <ul class="mem_list">			
		<?php
        $re=sql_query("select * from {$g5['cn_item_cart']} where mb_id='leesy' and is_soled!='1'  group by cn_item order by cn_item ", 1);
        while ($data=sql_fetch_array($re)) {
            $re2=sql_query("select * from {$g5['cn_item_cart']} where mb_id='leesy' and cn_item='$data[cn_item]'  and is_soled!='1' ", 1); ?>
			<li class="mem_bx">
				<div class="mtp">
					<div class="img"><img src="<?=G5_THEME_URL?>/images/<?=$g5[cn_item][$data[cn_item]]['img']?>"  alt="<?=$g5[cn_item][$data[cn_item]]['name_kr']?>"/></div>
					<div class="txt">
						<?=$g5[cn_item][$data[cn_item]]['name_kr']?><br />
						보유수량 : <?=sql_num_rows($re2)?>
					</div>
				</div>
				<div class="mbt center" style="padding:0.5em;">
					<?php while ($data2=sql_fetch_array($re2)) {
                $past_day=ceil((strtotime(date("Y-m-d")) - strtotime($data2['ct_validdate'])) /86400); ?>
						<p>
							<button type="button" class='btn btn-sm ml-2 trans-btn' data-code='<?=$data2[code]?>' data-price='<?=$data2[ct_sell_price]?>' data-name='<?=$g5[cn_item][$data[cn_item]]['name_kr']?>'>
								<img src="<?=G5_THEME_URL?>/images/change_gold.png" width="120px" />
							</button>
						</p>
					<?php
            } ?>
				</div>
			</li>
		<?php
        }?>
    </ul>
</div>

<div class="popup i02">
	<form name='transform' id='transform' onsubmit='transform_submit(this);' action='./item_trans_point.php' >
		<input type='hidden' name='w' value='t' >	
		<input type='hidden' name='code' value='' >		
		<input type='hidden' name='amt' id="form_amt" value='' >		
		<input type='hidden' name='point' id="form_point" value='' >			

		<h4>포인트 변환 신청</h4>			
		<ul>
			<li class='text-left'>
			 <p>변환할 나비 : <is id='item_name'></is></p>
			 
			</li>					
			<li class='text-left'>
			 <p>나비 금액 : <is id='item_price'></is></p>
			 
			</li>
			<li style="margin-top:20px;">
				<p  class='w-100 text-left'>꽃<?=$g5[cn_cointype]['i']?> : <is id='amt_i'><?=number_format($rpoint['e']['_enable'])?></is> 지급 </p>
			</li>        
			<li style="margin-top:20px;">
				<p  class='w-100 text-left'><?=$g5[cn_cointype]['s']?> : <is id='amt_s'>0</is> 지급</p>
			</li>                   
		</ul>

		<div class="btns w-100">
			<ul class='w-100'>
				<li class='w-50'>
					<button type='submit' >확인</button>
				</li>
				<li class='w-50'>
					<button type='button' class='btn-close' >닫기</button>
				</li>
			</ul>
		</div>
	</form>
</div>

<div class="popup i03">
	<form name='new_transform' id='new_transform' onsubmit='new_transform_submit(this);' action='./item_trans_point.php' >
		<input type='hidden' name='w' value='t' >	
		<input type='hidden' name='new_code' value='' >		
		<input type='hidden' name='amt' id="new_form_amt" value='' >		
		<input type='hidden' name='point' id="new_form_point" value='' >		

		<h4>포인트 변환 신청</h4>			
		<ul>
			<li class='text-left'>
			 <p>변환할 나비 : <is id='new_item_name'></is></p>
			 
			</li>					
			<li class='text-left'>
			 <p>나비 금액 : <is id='new_item_price'></is></p>
			 
			</li>
			<li style="margin-top:20px;">
				<p  class='w-100 text-left'>꽃<?=$g5[cn_cointype]['i']?> : <is id='new_amt_i'><?=number_format($rpoint['e']['_enable'])?></is> 지급 </p>
			</li>                    
		</ul>

		<div class="btns w-100">
			<ul class='w-100'>
				<li class='w-50'>
					<button type='submit' >확인</button>
				</li>
				<li class='w-50'>
					<button type='button' class='btn-close' >닫기</button>
				</li>
			</ul>
		</div>
	</form>
</div>


<script>
$(document).ready(function () {
	
	$('.trans-btn').click(function () {
		event.preventDefault();
		$('.popup.i02').addClass('on');
		
		var item_price=parseFloat($(this).attr('data-price'));
		
		$("#item_name").html($(this).attr('data-name'));
		$("#item_price").html(item_price);
		
		$("input[name=code]","#transform").val($(this).attr('data-code'));
		
		$(".sendInfo.buyPopup").addClass("on");
		$(".mask").addClass("on");
		
		var amt_i=Math.floor((item_price/<?=$sise['sise_i']?>)*(50/100));
		var amt_s=Math.floor((item_price/<?=$sise['sise_s']?>)*(50/100));
		$("#amt_i").html(inputNumberFormat(amt_i));
		$("#amt_s").html(inputNumberFormat(amt_s));
		$("#form_amt").val(amt_i);
		$("#form_point").val(amt_s);
	});
	
	$('.trans-new-btn').click(function () {
		event.preventDefault();
		$('.popup.i03').addClass('on');
		
		var item_price=parseFloat($(this).attr('data-price'));
		
		$("#new_item_name").html($(this).attr('data-name'));
		$("#new_item_price").html(item_price);
		
		$("input[name=new_code]","#new_transform").val($(this).attr('data-code'));
		
		$(".sendInfo.buyPopup").addClass("on");
		$(".mask").addClass("on");
		
		var new_amt_i=Math.floor((item_price/<?=$sise['sise_i']?>)*(100/100));
		$("#new_amt_i").html(inputNumberFormat(new_amt_i));
		$("#new_form_amt").val(new_amt_i);
		$("#new_form_point").val(0);
	});
	
	$('.btns .btn-close').click(function () {
		$(this).closest('.popup').removeClass('on');
	});
	
});


function sum(){
	var price=$('input[name=cn_price]','#buyform').val();	
	var qty=$('select[name=qty] option:selected','#buyform').val();
	
	var sum=parseInt(price) * parseInt(qty);
	
	$('#sum_str').html(inputNumberFormat(sum));
}

//  상품 구매

function transform_submit(f)
{
	
		
	event.preventDefault();
	Swal.fire({
	  title: '',
	  text: "<?=lng('포인트 변환을 진행하시겠습니까? 즉시 변환되며 다시 되돌릴수 없습니다')?>",
	  icon: 'warning',
	  showCancelButton: true,
	  confirmButtonColor: '#3085d6',
	  cancelButtonColor: '#d33',
	  confirmButtonText: '<?=lng('진행합니다')?>'
	}).then((result) => {
	  if (result.value) {		
	
			var formData = $(f).serialize();		

			$.ajax({
				type: "POST",
				url: "./item_trans_point.php",
				data:formData,
				cache: false,
				async: false,
				dataType:"json",
				success: function(data) {

					if(data.result==true){			
						$('.popup.i02').removeClass('on');						
						
						Swal.fire({
						  title: '',
						  html: data.message						  
						}).then((result) => {
							document.location.href='./stonedetail.php';
						})
						

					}
					else Swal.fire({html:data.message});
				}
			});		
	
	
	  } //if (result.value) {
	})
	
	return;
	
	
}
//  상품 구매

function new_transform_submit(f)
{
	
		
	event.preventDefault();
	Swal.fire({
	  title: '',
	  text: "<?=lng('포인트 변환을 진행하시겠습니까? 즉시 변환되며 다시 되돌릴수 없습니다')?>",
	  icon: 'warning',
	  showCancelButton: true,
	  confirmButtonColor: '#3085d6',
	  cancelButtonColor: '#d33',
	  confirmButtonText: '<?=lng('진행합니다')?>'
	}).then((result) => {
	  if (result.value) {		
	
			var formData = $(f).serialize();		

			$.ajax({
				type: "POST",
				url: "./new_item_trans_point.php",
				data:formData,
				cache: false,
				async: false,
				dataType:"json",
				success: function(data) {

					if(data.result==true){			
						$('.popup.i03').removeClass('on');						
						
						Swal.fire({
						  title: '',
						  html: data.message						  
						}).then((result) => {
							document.location.href='./stonedetail.php';
						})
						

					}
					else Swal.fire({html:data.message});
				}
			});		
	
	
	  } //if (result.value) {
	})
	
	return;
	
	
}

    </script>
			
			
<?php
include_once('../_tail.php');
?>
<style>

.popup.on {
  display: block;
}
.popup {
	color:#fff;
  display: none;
  position: fixed;
  z-index: 1030;
  left: 10px;
  width: calc(100% - 20px);
  box-sizing: border-box;
  top: 50%;
  margin-top: -50%;
  background: #000;
  padding: 20px;
  border-radius: 10px;
  border: 1px solid #ffe25c;
}
.popup b {
  display: block;
  width: 300px;
  margin: 0 auto;
  text-align: center;
  font-size: 1rem;
  letter-spacing: 0.15rem;
  font-weight: 400;
}
.popup h5 {
  font-size: 1rem;
  color: #ffe25c;
  text-align: center;
  margin: 20px 0;
  font-size: 1rem;
  letter-spacing: 0.15rem;
  font-weight: 400;
}
.popup h4 {
  text-align: center;
  color: #fff;
  position: relative;
  padding-bottom: 40px;
  font-size: 1rem;
  letter-spacing: 0.15rem;
  font-weight: 400;
}
.popup h4:after {
  content: "";
  display: block;
  width: 100%;
  height: 3px;
  background: url(../images/line.png) no-repeat;
  background-size: contain;
  position: absolute;
  bottom: 0;
  left: 0;
  margin: 20px 0;
}

.popup ul {
}
.popup ul li {
  font-size: 1.3rem;
  letter-spacing: 0.15rem;
  margin: 3px 0;
  text-align: center;
}
.popup ul li p {
}
.popup ul li input {
  text-align: center;
  padding: 5px 0;
  font-size: 20px;
}
.popup ul li p span {
}

.btns {
  margin-top: 30px;
  width:100%;
}
.btns ul {
  text-align: center;
  width:100%;
}
.btns ul li {
  display: inline-block;
  padding: 10px;
}
.btns ul li button {
  display: inline-block;
  width: 6.7rem;
  line-height: 2.6rem;
  height: 2.6rem;
  background-color: transparent;
  text-align: center;
background: #fff;
  font-size: 1rem;
  letter-spacing: 0.18rem;
}
</style>
