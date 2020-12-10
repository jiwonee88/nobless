<?php

include_once('./_common.php');

$outer_css=' stoneDetail fee';

add_javascript('<script src="'.G5_THEME_URL.'/extend/clipboard.min.js"></script>',1);
include_once('../_head.php');
$isum=get_itemsum($member[mb_id]);
?>
      

	
    <div class="wrap">
        <div class="area shingStone">
            <h3><span>NAVI 상품</span></h3>
			
			
			<p> 매너 포인트 상점 비활성화<br>
		각 나비별로 1일 1개만 구매 가능.
		</p>
            <ul class="stoneList" >
                <?php
                foreach($g5['cn_item'] as $k=>$v){?>

                    <!--li class="squareWB">
                        <h4><?=$v[name_kr]?></h4>
                        <div class="clearfix">
                            <div class="stoneImg f_left" style="padding-left: 0.4rem">
                                <img src="<?=G5_THEME_URL?>/images/butterfly/<?=$v[img]?>" alt='<?=$v[name_kr]?>' >
                            </div>
                            <div class="stoneDesc f_left">
                                <ul>
                                    <li>보유량:<?=$isum[$k][cnt]?$isum[$k][cnt]:0?>개</li>
                                    <li>판매대기:<?=$v[days]?>일</li>
                                    <li>판매시수익:<?=$v[interest]?>%</li>
									<li>가격 : $<?=$v[price]?></li>
                                </ul>
                                
                            </div>
                        </div>
						<!--button class="common-btn buy-btn" data-item='<?=$k?>' data-price='<?=swap_coin($v[price],'u','e',$sise)?>' data-itemname="<?=$v[name_kr]?>"  >---</button-->
                    </li-->
                <?php }?>

            </ul>
        </div>
    </div>
	<div class="popup i02">
			<form name='buyform' id='buyform' onsubmit='buyform_submit(this);' >
			<input type='hidden' name='w' value='' >	
			<input type='hidden' name='mb_id' value='<?=$member[mb_id]?>' >
			<input type='hidden' name='it_token' value='u' >			
			<input type='hidden' name='it_set_token' value='<?=$g5[cn_shop_coin]?>' >			
			<input type='hidden' name='cn_item' value='' >				
			<input type='hidden' name='cn_price' value='' >			
				
                <h4><?=$g5[cn_itemname]?>구매신청</h4>
                <!--h5>GOLD 구매시 선택 상품에 따라</br>할인이 적용 됩니다.</h5-->
                <ul>
                    <li>
                     <p>계정</p>
                     <select name='smb_id' class='common-select w-80' >
					 <option value=''  ><?=$member[mb_id]?></option>
					 <?
					 //서브 계정
					 $accresult=sql_query("select * from  {$g5['cn_sub_account']} where mb_id='$member[mb_id]'  and ac_id!='$member[mb_id]' order by ac_id asc");
					 while($ac=sql_fetch_array($accresult)){?>
					 <option value='<?=$ac[ac_id]?>'  ><?=$ac[ac_id]?></option>
					 <? }?>
					 </select>
                    </li>					
					
                    <li style="margin-top:10px;">
                        <p>수량</p>
                         <select name='qty' class='common-select w-80' >
					<?
					for($i=1;$i <=1;$i++){?>
					 <option value='<?=$i?>'><?=$i?></option>
					 <? }?>
					 </select>
					 
                    </li>
					<li style="margin-top:20px;">
                        <p  class='w-100 text-left'>이용가능금액 : <is id='enableAmt'><?=number_format($rpoint['e']['_enable'])?></is> </p>
                    </li>        
                    <li style="margin-top:20px;">
                        <p  class='w-100 text-left'>최종결제금액 : <is id='sum_str'>0</is> <?=$g5[cn_cointype]['e']?></p>
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
	sum()
	$('select[name=item],select[name=qty]','#buyform').change(function () {
		sum();
	});
	
	$('.buy-btn').click(function () {
		event.preventDefault();
		$('.popup.i02').addClass('on');
			
		$(".item-name").html($(this).attr('data-itemname'));
		$("input[name=cn_item]","#buyform").val($(this).attr('data-item'));
		$("input[name=cn_price]","#buyform").val($(this).attr('data-price'));
		
		$(".sendInfo.buyPopup").addClass("on");
		$(".mask").addClass("on");
		
		sum();
		
	});
	
	$('.btns .btn-close').click(function () {
		$(this).closest('.popup').removeClass('on');
	});
	
	sum()
	
	$('select[name=item],select[name=qty]','#buyform').change(function () {
		sum();
	});
	
});


function sum(){
	var price=$('input[name=cn_price]','#buyform').val();	
	var qty=$('select[name=qty] option:selected','#buyform').val();
	
	var sum=parseInt(price) * parseInt(qty);
	
	$('#sum_str').html(inputNumberFormat(sum));
}

//  상품 구매

function buyform_submit(f)
{
	
		
	event.preventDefault();
	Swal.fire({
	  title: '',
	  text: "<?=lng('구매를 진행하시겠습니까')?>",
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
				url: "./coin_purchase_item.php",
				data:formData,
				cache: false,
				async: false,
				dataType:"json",
				success: function(data) {

					if(data.result==true){			
						$('.popup.i02').removeClass('on');						
						
						$('#enableAmt').html(inputNumberFormat(data.datas['remainAmt'].toFixed(0)));
						
						Swal.fire({html:data.message});

					}
					else Swal.fire({html:data.message});
				}
			});		
	
	
	  } //if (result.value) {
	})
	
	return;
	
	
}

    </script>
			
<?	
include_once('../_tail.php');
?>
