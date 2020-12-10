<?php

include_once('./_common.php');

$outer_css=' stoneDetail fee';

add_javascript('<script src="'.G5_THEME_URL.'/extend/clipboard.min.js"></script>',1);
include_once('../_head.php');
$isum=get_itemsum($member[mb_id]);
?>
      
	  
        <div class="wrap">
            <div class="area area01">
                <h3><span>order</span></h3>
                <ul class="common">
                    <li class="squareWB hero w50" onclick="location.href='/for_common/idDetail.php'"><span><?=$member[mb_id]?></span></li>
                    <li class="squareWB stone w50 text-left">&nbsp;<span class="stoneBuystone">&nbsp;</span>
								<p class="buyBtn">구매</p></li>
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
			
			<?
			//신청내역 - 입금 준비중인 것만 표
			$result=sql_query("select * from  {$g5['cn_purchase_table']} where mb_id='$member[mb_id]' and in_stats='1' order by in_no desc");
			while($data=sql_fetch_array($result)){?>
            <div class="area area03 squareWB">
				
				<form name='depositform<?=$data[in_no]?>' id='depositform<?=$data[in_no]?>' onsubmit='depositform_submit(this);' >
				<input type='hidden' name='w' value='u' >	
				<input type='hidden' name='in_no' value='<?=$data[in_no]?>' >
				
                <div class="feeImg"><img src="<?=G5_THEME_URL?>/images/stone.png" alt=""></div>
                <div class="orderTxt">주문상품 : <?=$data[in_item]?> x <?=$data[in_item_qty]?></div>
                <ul>
                    <li>갯수 : <?=number_format2($data[in_set_amt])?> <?=$g5[cn_cointype][$data[in_set_token]]?></li>
                    <li>가격 : $ <?=number_format2($data[in_rsv_amt])?> <?=$g5[cn_cointype][$data[in_token]]?></li>
                    <li>상태 : <?=$g5[purchase_stat][$data[in_stats]]?></li>
                    <li class="btn-clipboard" data-clipboard-text="<?=$data[in_wallet_addr]?>" >지갑주소 <span class='text-narrow025' ><?=$data['in_wallet_addr']?></span></li>
                </ul>
				<span class='banking-info d-none'>
                <input type="text" name='in_txn_id' value='<?=$data[in_txn_id]?>'  placeholder="TXN HASH 복사 후 붙여넣기" class="orderCopy " >
                <div class="orderCopyNum">* Transaction Hash : 테더 전송 거래 번호</div>
				</span>
                <div class="storeBuy">
                    <span class="on">입금정보</span>
                    <button type='submit'>결제확인</button>
                </div>
				
				</form>
            </div>
			<? }
			
			if(sql_num_rows($result)==0) echo '<p class="py-5 text-center">구매 신청중인 내역이 없습니다</p>';
			?>
					
			
            <div class="popup i02">
			<form name='buyform' id='buyform' onsubmit='buyform_submit(this);' >
				<input type='hidden' name='w' value='' >	
				<input type='hidden' name='mb_id' value='<?=$member[mb_id]?>' >
				<input type='hidden' name='tr_token' value='u' >
				<input type='hidden' name='tr_set_token' value='i' >
				
                <h4><?=$g5[cn_cointype]['i']?>구매신청</h4>
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
					
					<li>
                     <p>꽃</p>
                     <select name='item' class='common-select w-80' >
					 <?
					 foreach($g5['cn_golditem'] as $k=>$v){?>
					 <option value='<?=$k?>' data-price='<?=$v[price]?>' data-amt='<?=$v[amt]?>' ><?=$v[name_kr]?></option>
					 <? }?>
					 </select>
                    </li>
                    <li style="margin-top:10px;">
                        <p>수량</p>
                         <select name='qty' class='common-select w-80' >
					<?
					for($i=1;$i <=10;$i++){?>
					 <option value='<?=$i?>'><?=$i?></option>
					 <? }?>
					 </select>
					 
                    </li>
                    <li style="margin-top:20px;">
                        <p  class='w-100 text-left'>최종 지급 수량 : <span id='amt_str'>0</span> 송이</p>
                    </li>
                    <li >
                        <p  class='w-100 text-left'>최종 결제 금액 : $<span id='sum_str'>0</span></p>
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
			
			
        </div>


    <script>


$(document).ready(function () {
	
	$('.fee .storeBuy span.on').click(function () {
		$(this).parent('div').prev('.banking-info').toggleClass('d-none');
	});


	$('.stoneDetail .area01 .stone .buyBtn').click(function () {
		$('.popup.i02').addClass('on');
	});
	
	$('.btns .btn-close').click(function () {
		$(this).closest('.popup').removeClass('on');
	});
	
	sum()
	$('select[name=item],select[name=qty]','#buyform').change(function () {
		sum();
	});
	
	var clipboard = new ClipboardJS('.btn-clipboard');
	clipboard.on('success', function(e) {
		Swal.fire({text:'복사완료',timer:1000});

		var selection = window.getSelection();
		selection.removeAllRanges();
	});
	
	clipboard.on('error', function(e) {
		Swal.fire({text:'복사실패',timer:1000});
	});

});


function sum(){
	var price=$('select[name=item] option:selected','#buyform').attr('data-price');
	var amt=$('select[name=item] option:selected','#buyform').attr('data-amt');
	var qty=$('select[name=qty] option:selected','#buyform').val();
	
	var sum=parseInt(price) * parseInt(qty);
	var tamt=parseInt(amt) * parseInt(qty);
	
	$('#sum_str').html(inputNumberFormat(sum));
	$('#amt_str').html(inputNumberFormat(tamt));
}

//  골드 구매 신
function buyform_submit(f)
{
	event.preventDefault();
	var formData = $(f).serialize();		

	$.ajax({
		type: "POST",
		url: "./coin_purchase_update.php",
		data:formData,
		cache: false,
		async: false,
		dataType:"json",
		success: function(data) {

			if(data.result==true){			
				$('.popup.i02').removeClass('on');
				document.location.href='./fee.php';
				//document.location.href='./fee.php';Swal.fire({text:'접수되었습니다. 입금 확인후 처리 됩니다.',timer:1000});  
				
			}
			else Swal.fire(data.message);       
		}
	});		
	return;
}


//  입금확인 신
function depositform_submit(f)
{
	event.preventDefault();
	var formData = $(f).serialize();		
	
	var in_txn_id=$('input[name=in_txn_id]',$(f)).val();
	if(in_txn_id==''){
		Swal.fire({text:'Txn Hash를 입력하세요.'});   
		return;
	}
	$.ajax({
		type: "POST",
		url: "./coin_purchase_update.php",
		data:formData,
		cache: false,
		async: false,
		dataType:"json",
		success: function(data) {

			if(data.result==true){
				Swal.fire({text:'등록되었습니다.',timer:1000});   
			}
			else Swal.fire(data.message);       
		}
	});		
	return;
}



    </script>

<?	
include_once('../_tail.php');
?>
