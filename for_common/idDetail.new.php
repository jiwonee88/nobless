<?php
include_once('./_common.php');

$outer_css=' stoneDetail  idDetail';
include_once('../_head.php');

//메인계정의 포인트
$mrpoint=get_mempoint($member['mb_id'],$member['mb_id']);
$isum=get_itemsum($member[mb_id]);

add_javascript('<script src="'.G5_THEME_URL.'/extend/clipboard.min.js"></script>',1);

?>

        <div class="wrap" >
            <div class="area area01">
                <h3><span>my assets</span></h3>
                <ul class="common">
                    <li class="squareWB hero w50"><span><?=$member[mb_id]?></span></li>
					<li class="squareWB stone w50"><a href="/for_common/fee.php"><span class="stoneBg confirm gold-enable-i"   ><?=number_format2($rpoint['i']['_enable'])?></span></a></li>
                </ul>
            </div>
            <div class="area area02">
                 <ul class="buy sell">      
                   <li class="squareWB"> <span class="f_yellow text-narrow0"><?=number_format2($rpoint['e']['_enable'])?></span>
					<span class="condition">매너포인트</span>
					</li>

                    <li class="squareWB"><span class="f_yellow text-narrow0"><?=number_format2($rpoint['b']['_enable'])?></span>
					<span    class="condition">꿀단지</span></li>
					
                    <li class="squareWB">
                        <span class="f_yellow  mb_trade_paytype">						
						<? if($member[mb_trade_paytype]=='cash') echo '현금';
						else if($member[mb_trade_paytype]=='both') echo '현금 또는 테더';
						else if($member[mb_trade_paytype]=='usdt') echo '테더';
						?>
						</span><span class="condition">결제방법</span>
                        <span class="setting i02"><img src="<?=G5_THEME_URL?>/images/setting.png" alt="" /></span>
                    </li>
                </ul>
            </div>
			<?
			//서브 계정 없으면 추가
			$subadd=set_basic_account($member);
			
			//서브 계정
			$accresult=sql_query("select * from  {$g5['cn_sub_account']} where mb_id='$member[mb_id]'  order by ac_id asc");
			?>
			<form name='formaccount' id='formaccount' onsubmit='formaccount_submit(this);' >
			<input type='hidden' name='w' value='u6' >	
			<input type='hidden' name='mb_id' value='' >
			<input type='hidden' name='ac_id' value='' >
            <div class="area area02 area03">
                <ul class="buy" id='subAccount' >
				
                    <li class="squareWB list01">
					<span class="f_yellow">보유계정 <is class='account-cnt'><?=sql_num_rows($accresult)-1?>/<?=$cset[max_account_lmt]?></is></span>
                        <span class="accountAdd">계정추가</span>
                    </li>
					
                    <!--li class="squareWB" id='basicAccount'>
                        <div class="accountBox" data-id='<?=$member[mb_id]?>'>
                            <span class="account-check"><input type="checkbox" name='active_main' value='<?=$member[mb_id]?>' id="acc-main" <?=$member[mb_active]?'checked':''?>  disabled/>
							<label  for="acc-main"><?=$member[mb_id]?> (<?=$member[mb_active]?'활성화됨':'비활성화'?>)</label></span>
                            <div class="idDetailActive main-id <?=$member[mb_active]?'d-none':''?>" data-id="<?=$member[mb_id]?>"  data-no="<?=$member[mb_no]?>">활성화</div>
                        </div>
                        <div class="stoneBox">
                            <span class="stone"><span class='gold-enable-<?=$member[mb_id]?>'><?=number_format2($mrpoint['i']['_enable'])?></span></span>
                            <span class="f_right buyBtngroup">
                            </span>
                        </div>
                    </li-->
					
					<?
					while($ac=sql_fetch_array($accresult)){?>
                    <li class="squareWB">					
                        <div class="accountBox" data-id='<?=$ac[ac_id]?>'>
                            <span class="account-check"><input type="checkbox" name='active_sub[]' id="acc-<?=$ac[ac_no]?>"  <?=$ac[ac_active]?'checked':''?> value="<?=$ac[ac_id]?>" disabled />
							<label for="acc-<?=$ac[ac_no]?>" ><?=$ac[ac_id]?> (<?=$ac[ac_active]?'활성화됨':'비활성화'?>)</label></span>
                            <div class="idDetailActive sub-id <?=$ac[ac_active]?'d-none':''?>" data-id="<?=$ac['ac_id']?>"  data-no="<?=$ac['ac_no']?>">활성화</div>
                        </div>
                        <div class="stoneBox overflow-hidden">
                            <span class="stone w-40 d-inline-block"><span  class='gold-enable-i-<?=$ac[ac_id]!=$ac[mb_id]?$ac[ac_no]:$ac[ac_id]?>' ><?=number_format2(max(0,$ac['ac_point_i']))?></span></span>
                        	<span class="honey  w-40  d-inline-block"><span  class='gold-enable-b-<?=$ac[ac_id]!=$ac[mb_id]?$ac[ac_no]:$ac[ac_id]?>' style="background: url('<?=G5_THEME_URL?>/images/honey.png')no-repeat left 50%;padding-left:2rem;
    background-size: 1.4rem auto;"><?=number_format2(max(0,$ac['ac_point_b']))?></span></span>
                           
                        </div>
						
						<div class="stoneBox">                           
							<?
							//if($ac[ac_id]!=$ac[mb_id]){?>
							<span class="f_right buyBtngroup">
                                <span class="buyBtn" data-id="<?=$ac[ac_id]?>" data-no="<?=$ac[ac_no]?>" >전송</span>
                            </span>
							<? 
							//}?>
                        </div>
                    </li>
					<? }?>
					
                </ul>
            </div>
			</form>
			
			
            <div class="area">
                <h3><span>profile</span></h3>
                <div class="area area04 squareWB">
                    <ul>
                        <li>
                            <span class="f_yellow">휴대폰번호</span>
                            <span class="f_yellowDesc">+<?=$member[mb_nation]?> <?=only_number($member[mb_hp])?></span>
                        </li>
                        <li>
                            <span class="f_yellow">레퍼럴URL</span>
                            <span class="f_yellowDesc mb_bank  text-narrow0 btn-clipboard" data-clipboard-text="<?=G5_URL?>/?refid=<?=$member[mb_id]?>" ><?=G5_URL?>/?refid=<?=$member[mb_id]?> <i class="far fa-copy"></i></span>
							
                        </li>
						 <li>
                            <span class="f_yellow">계좌정보</span>
                            <span class="f_yellowDesc mb_bank  text-narrow0"><?=$member[mb_bank]?$member[mb_bank].' '. $member[mb_bank_num].' '. $member[mb_bank_user]:'미등록'?></span>
                        </li>
                    </ul>
                    <div class="storeBuy text-center">
                        <a href="#">계좌정보등록</a>
                    </div>
				
					<ul>
                         <li>
                            <span class="f_yellow">USDT 지갑 정보</span>
                            <span class="f_yellowDesc mb_wallet_addr_u text-narrow1"><?=$member[mb_wallet_addr_u]?$member[mb_wallet_addr_u]:'미등록'?></span>
                        </li>
                    </ul>
                    <div class="storeAddr text-center">
                        <a href="#">주소등록</a>
                    </div>
					<ul>
                         <li>                          
                            <span class="f_yellowDesc text-narrow1">암호를 변경하시려면 아래 버튼을 클릭하세요</span>
                        </li>
                    </ul>
					<div class="updatePass text-center">
                        <a href="#">암호변경</a>
                    </div>
                </div>
            </div>			
			
			
			
            <div class="popup i01">
				<form name='form1' onsubmit='form1_submit(this);' >
				<input type='hidden' name='w' value='u1' >	
                <h4>주의 사항</h4>
                <b>한도 금액을 설정하지 않으면 매칭이</br>이루어 지지 않습니다.</b>
                <h5>한도 금액은 달러 기준입니다.</h5>
                <ul>
                    <li>
                        <p>설정금액 : <input type="text" name='mb_trade_amtlmt' class='common-input number-comma' ></p>
                    </li>
                    <li>
                        <p>보유금액 : <span>$ <?=number_format2($isum[tot][price])?></span></p>
                    </li>
                    <!--li>
                        <p>가용금액 : <span>$ <?=number_format2($isum[tot][price]>$member[mb_trade_amtlmt]?0:$member[mb_trade_amtlmt]-$isum[tot][price])?></span></p>
                    </li-->
                </ul>

                <div class="btns">
                    <ul>
                        <li class='w-50'>
                            <button type='submit' >확인</button>
                        </li>
						<li class='w-50'>
                            <button type='button'  class='btn-close'>닫기</button>
                        </li>
                    </ul>
                </div>
				</form>
            </div>			
			
			
            <div class="popup i02">
				<form name='form2' onsubmit='form2_submit(this);' >
				<input type='hidden' name='w' value='u2' >	
				
                <h4>주의 사항</h4>
                <b>현금+테더를 선택하시면</br>더 많은 매칭이 이루어 질 수 있습니다.</b>
                <h5>국가의 결제 환경에 따라 제한이</br>있을 수 있습니다.</h5>
                <ul class='w-50 mx-auto'>
                    <li class='text-left'>                      
						<input type="radio" name="mb_trade_paytype"  id="mb_trade_paytype1" value='both' <?=$member[mb_trade_paytype]=='both' ?'checked="checked"':''?>  class='designed' disabled > <label for="mb_trade_paytype1">현금/테더(준비중)</label>
                    </li>
                    <li class='text-left'>     
						<input type="radio" name="mb_trade_paytype" id="mb_trade_paytype2" value='cash' <?=$member[mb_trade_paytype]=='cash' ?'checked="checked"':''?> class='designed' > <label for="mb_trade_paytype2">현금</label>

                    </li>
                </ul>

                <div class="btns">
                    <ul>
                        <li class='w-50'>
                            <button>확인</button>
                        </li>
						<li class='w-50'>
                            <button type='button'  class='btn-close'>닫기</button>
                        </li>
                    </ul>
                </div>
				</form>
            </div>
			
			
            <div class="popup i03">
				<form name='form2' onsubmit='form3_submit(this);' >
				<input type='hidden' name='w' value='u3' >	
                <h4>전송 확인</h4>
                <ul>
                    <li>
                        <p>계정을 추가하시겠습니까?</p>
                    </li>
                    
                </ul>

                <div class="btns">
                    <ul>
                        <li class='w-50'>
                            <button class=''>추가합니다</button>
                        </li>
                        <li class='w-50'>
                            <button type='button'  class='btn-close'>닫기</button>
                        </li>
                    </ul>
                </div>
				
				</form>
            </div>
			<style>
			.popup.i04 ul li {
				font-size: .85rem;
				letter-spacing: 0.15rem;
				margin: 3px 0;
				text-align: center;
			}
			</style>
			
            <div class="popup i04" >
				<form name='form4' id='form4' onsubmit='form4_submit(this);' >
				<input type='hidden' name='w' value='u' >	
				<!-- <input type='hidden' name='mb_id' value='<?//=$member[mb_id]?>' > -->
				<input type='hidden' name='stmb_id' value='' >
				<input type='hidden' name='stmb_no' value='' >
                <h4>전송 확인</h4>
                <ul>
                    <li>
                        <p>전송할 계정 정보</p>
                    </li>
					<li>
						<select onchange="callMrPoint(this.value)" name='mb_id' class='common-select w-80' >
							<?php			
							$accresult=sql_query("select * from  {$g5['cn_sub_account']} where mb_id='$member[mb_id]'  order by ac_id asc");
							while($ac=sql_fetch_array($accresult)){?>
								<option value="<?=$ac[ac_id]?>" <?=$member[mb_id]==$ac[ac_id]?"selected=true":""?>><?=$ac[ac_id]?></option>
							<?php }?>
						</select>
					 </li>
                    <li>
                        <p>전송받는 계정 정보 : <span class='stmb_id' >...</span></p>
                    </li>
					<li>
                        <p>전송 포인트 선택</p>
                    </li>
					<li>
					<select name='tr_token' id='tr_token' class='common-select w-80' >
					 <option value='i'  >꽃송이</option>
					  <option value='b'  ><?=$g5[cn_cointype]['b']?></option>
					 </select>
					 </li>
                    <li>
                        <p>전송 수량 입력</p>
                    </li>
                    <li>
                        <p><input type="text" name='tr_amt'  class='common-input w-80 mt-1 number-comma' ></p>
                    </li>
                    <li style="margin-top:20px;" class='text-left'>
                        <p>전송가능 꽃송이 : <span class='gold-enable-i' id="mrpoint_i_enable"><?=number_format2($mrpoint[i]['_enable'])?></span></p>
                    </li>
                    <li  class='text-left'>
                        <p>최소전송 꽃송이 : <span><?=number_format2($cset[min_trans_i])?> </span> </p>
                    </li>
					
					<li style="margin-top:20px;"  class='text-left'>
                        <p>전송가능 <?=$g5[cn_cointype]['b']?> : <span class='gold-enable-b' id="mrpoint_b_enable"><?=number_format2($mrpoint[b]['_enable'])?></span></p>
                    </li>
                    <li  class='text-left'>
                        <p>최소전송 <?=$g5[cn_cointype]['b']?> : <span><?=number_format2($cset[min_trans_b])?> </span> </p>
                    </li>
                </ul>

                <div class="btns">
                    <ul>
                        <li class='w-50'>
                            <button type='submit' >확인</button>
                        </li>
                        <li class='w-50'>
                          <button type='button'  class='btn-close'>닫기</button>
                        </li>
                    </ul>
                </div>
				</form>
            </div>
			
            <div class="popup i05">
				<form name='form5' onsubmit='form5_submit(this);' >
				<input type='hidden' name='w' value='u5' >	
                <h4>주의 사항</h4>
                <b>은행정보를 정확하게 입력하지 않으면</br>거래가 지연되는 등의 불이익을 당하실 수도 있습니다.</b>
               
                <ul>
                    <li>
                         <h5 class='mt-3 mb-1'>은행정보</h5>
						 <input  type="text" name='mb_bank' value="<?=$member[mb_bank]?>" class='common-input w-100 mt-1' placeholder='은행명'>
						 
						 <!--select name='mb_bank' class='common-select w-100'>
						 <?
						 //foreach($g5[bank_arr] as $v) echo '<option value="'.$v.'" '.($member[mb_bank]==$v?'selected':'').'>'.$v.'</option>';
						 ?>
						 </select-->
                    
                        <h5 class='mt-3 mb-1'>계좌번호</h5>
                        <input  type="text" name='mb_bank_num' value="<?=$member[mb_bank_num]?>" class='common-input w-100 mt-1' placeholder='계좌번호'>
						
                        <h5 class='mt-3 mb-1'>예금주</h5>
                        <input  type="text" name='mb_bank_user' value="<?=$member[mb_bank_user]?>" class='common-input w-100 mt-1' placeholder='예금주'>
						
                    </li>
					
                </ul>

                <div class="btns">
                    <ul>
                        <li class='w-50'>
                            <button stype='submit' >저장</button>
                        </li>
                        <li class='w-50'>
                            <button type='button'  class='btn-close'>닫기</button>
                        </li>
                    </ul>
                </div>
				</form>
            </div>
		
		
		 <div class="popup i07">
				<form name='form7' onsubmit='form7_submit(this);' >
				<input type='hidden' name='w' value='u7' >	
                <h4>주의 사항</h4>
                <b>정확한 ERC20 지갑 주소를 입력하지 않으시면 입금액을 분실하게 됩니다.</br>ERC20 형식 지갑주소 오입력으로 발생한 모든 피해는 본인의 책임입니다.</b>
                <ul>                   
                    <li>
                        <h5>USDT지갑주소(ERC20)</h5>
                        <input  type="text" name='mb_wallet_addr_u' value="<?=$member[mb_wallet_addr_u]?>" class='common-input w-100 mt-1' placeholder='USDT지갑주소'>
						
                    </li>
					
                </ul>

                <div class="btns">
                    <ul>
                        <li class='w-50'>
                            <button stype='submit' >저장</button>
                        </li>
                        <li class='w-50'>
                            <button type='button'  class='btn-close'>닫기</button>
                        </li>
                    </ul>
                </div>
				</form>
            </div>
			
		<div class="popup i08">
				<form name='form8' onsubmit='form8_submit(this);' >
				<input type='hidden' name='w' value='u8' >	
                <h4>로그인 암호 변경</h4>
                <b>새로운 암호를 입력하세요</b>
                <ul>                   
                    <li>
                        <h5>암호</h5>
                        <input  type="password" name='mb_password' class='common-input w-100 mt-1' >						
                    </li>
					<li>
                        <h5>암호확인</h5>
                        <input  type="password" name='mb_password_re' class='common-input w-100 mt-1' >						
                    </li>
					
                </ul>

                <div class="btns">
                    <ul>
                        <li class='w-50'>
                            <button stype='submit' >저장</button>
                        </li>
                        <li class='w-50'>
                            <button type='button'  class='btn-close'>닫기</button>
                        </li>
                    </ul>
                </div>
				</form>
            </div>
			
    <script>
        $(document).ready(function () {
			$('.popup .btn-close').click(function () {
                $(this).parents('.popup').removeClass('on');
            });
			
            $('.idDetail .area02 ul.buy li span.setting.i01').click(function () {
                $('.popup.i01').addClass('on');
            });
           

            $('.idDetail .area02 ul.buy li span.setting.i02').click(function () {
                $('.popup.i02').addClass('on');
            });
            

            $('.area02 .accountAdd').click(function () {
                $('.popup.i03').addClass('on');
            });
			
			
          
			$('.idDetailActive.sub-id').click(function () {
                var ac_id=$(this).attr('data-id');	
				
				$('input[name=ac_id]','#formaccount').val(ac_id);
				$('input[name=mb_id]','#formaccount').val('');
				formaccount_submit();
            });
				
			
			$('.idDetailActive.main-id').click(function () {
                var mb_id=$(this).attr('data-id');					
				
				$('input[name=mb_id]','#formaccount').val(mb_id);
				$('input[name=ac_id]','#formaccount').val('');
				formaccount_submit();
            });
			
			
      		

            $('#subAccount li .buyBtn').click(function () {
				var ac_id=$(this).attr('data-id');
				var ac_no=$(this).attr('data-no');
				
				 $('.stmb_id').html(ac_id);
				 $('input[name=stmb_id]','#form4').val(ac_id);
				 $('input[name=stmb_no]','#form4').val(ac_no);
                $('.popup.i04').addClass('on');
            });


            $('.idDetail .area04 .storeBuy a').click(function () {
                $('.popup.i05').addClass('on');
            });
			
			$('.idDetail .area04 .storeAddr a').click(function () {
                $('.popup.i07').addClass('on');
            });
			
			$('.idDetail .area04 .updatePass a').click(function () {
                $('.popup.i08').addClass('on');
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
	

	function callMrPoint($val){
		$.ajax({
			type: "POST",
			url: "./ajax.mrpoint.php",
			data:{'mb_id':$val},
			cache: false,
			async: false,
			dataType:"json",
			success: function(data) {
				console.log(data);
				$("#mrpoint_i_enable").html(data.ac_point_i);
				$("#mrpoint_b_enable").html(data.ac_point_b);
			}
		});		
		return;
	}
	// 한도금액 설정
	function form1_submit(f)
	{

		event.preventDefault();
		var formData = $(f).serialize();	

		$.ajax({
			type: "POST",
			url: "./idDetail.update.php",
			data:formData,
			cache: false,
			async: false,
			dataType:"json",
			success: function(data) {

				if(data.result==true){					


					$('.mb_trade_amtlmt').html('$'+inputNumberFormat(data.datas['mb_trade_amtlmt']));
					$('.mb_trade_amtlenable').html('$'+inputNumberFormat(data.datas['mb_trade_amtenable']));
					$('.popup.i01').removeClass('on');
					Swal.fire({text:'적용되었습니다',timer:1000});
				}
				else Swal.fire(data.message);       
			}
		});		
		return;
	}
		
		
		
	//  결제방법선택
	function form2_submit(f)
	{

		event.preventDefault();
		var formData = $(f).serialize();	

		$.ajax({
			type: "POST",
			url: "./idDetail.update.php",
			data:formData,
			cache: false,
			async: false,
			dataType:"json",
			success: function(data) {

				if(data.result==true){					
					
					var pt=	data.datas['mb_trade_paytype'];
					var ptstr='';
					if(pt=='both') ptstr='현금 또는 테더';
					else if(pt=='cash') ptstr='현금';
					else if(pt=='usdt') ptstr='테더';
					
					$('.mb_trade_paytype').html(ptstr);
					$('.popup.i02').removeClass('on');
					Swal.fire({text:'적용되었습니다',timer:1000});
				}
				else Swal.fire(data.message);       
			}
		});		
		return;
	}
	
	
	//  계정추가
	function form3_submit(f)
	{

		event.preventDefault();
		var formData = $(f).serialize();	

		$.ajax({
			type: "POST",
			url: "./idDetail.update.php",
			data:formData,
			cache: false,
			async: false,
			dataType:"json",
			success: function(data) {

				if(data.result==true){					
						
					var htmls='<li class="squareWB" >'
                    +'    <div class="accountBox" data-id="'+ data.datas['ac_id'] +'">'
                    +'        <span class="account-check"><input type="checkbox" name="active_sub[]" id="acc-'+ data.datas['ac_no'] +'"   value="'+ data.datas['ac_id'] +'" disabled />'
					+'		<label for="acc-'+ data.datas['ac_no'] +'">'+data.datas['ac_id']+' (비활성화)</label></span>'
                    +'        <div class="idDetailActive sub-id" data-id="'+data.datas['ac_id']+'"  data-no="'+data.datas['ac_no']+'">활성화</div>'
                    +'    </div>'
                    +'    <div class="stoneBox">'
                    +'        <span class="stone"><span class="gold-enable-'+ data.datas['ac_no'] +'" >0</span></span>'
                    +'        <span class="f_right buyBtngroup">'
                    +'           <span class="buyBtn" data-id="'+ data.datas['ac_id'] +'" data-no="'+ data.datas['ac_no'] +'" >전송</span>'
                    +'        </span>'
                    +'    </div>'
                    +'</li>';
					
					//alert(htmls);
					//console.log(htmls);
					
					$('#subAccount').append(htmls);
					
					 $('#subAccount li:last-child .buyBtn').click(function () {
						var ac_id=$(this).attr('data-id');
						var ac_no=$(this).attr('data-no');

						 $('.stmb_id').html(ac_id);
						 $('input[name=stmb_id]','#form4').val(ac_id);
						 $('input[name=stmb_no]','#form4').val(ac_no);
						$('.popup.i04').addClass('on');
					});			
					
					$('#subAccount li:last-child .idDetailActive.sub-id').click(function () {
						var ac_id=$(this).attr('data-id');	
						
						$('input[name=ac_id]','#formaccount').val(ac_id);
						$('input[name=mb_id]','#formaccount').val('');
						formaccount_submit();
					});


					//console.log(htmls);
					$('.popup.i03').removeClass('on');
					$('.account-cnt').html(data.datas2['accountCnt'] + '/<?=$cset[max_account_lmt]?>');
					Swal.fire({text:'계정이 추가되었습니다',timer:1000});
				}
				else Swal.fire(data.message);       
			}
		});		
		return;
	}
		
	//  골드 이체
	function form4_submit(f)
	{
		event.preventDefault();
		var formData = $(f).serialize();	
		var mb_id=$('input[name=mb_id]','#form4').val();
		var stmb_id=$('input[name=stmb_id]','#form4').val();
		var stmb_no=$('input[name=stmb_no]','#form4').val();
		
		
		$.ajax({
			type: "POST",
			url: "./coin_transfer_update.php",
			data:formData,
			cache: false,
			async: false,
			dataType:"json",
			success: function(data) {

				if(data.result==true){				

					var mb_bank=	data.datas['mb_bank'];
					
					$('.gold-enable').html(inputNumberFormat(data.datas['i']['_enable']));
					$('.gold-enable-i' ).html(inputNumberFormat(data.datas['i']['_enable']));
					$('.gold-enable-b').html(inputNumberFormat(data.datas['b']['_enable']));
					
					$('.gold-enable-i-' + mb_id).html(inputNumberFormat(data.datas2['i']['_enable']));
					$('.gold-enable-b-' + mb_id).html(inputNumberFormat(data.datas2['b']['_enable']));
					$('.gold-enable-i-' + stmb_no).html(inputNumberFormat(data.datas3['i']['_enable']));
					$('.gold-enable-b-' + stmb_no).html(inputNumberFormat(data.datas3['b']['_enable']));
					
					
					
					$('.popup.i04').removeClass('on');
					Swal.fire({text:'이체 되었습니다'}).then(()=>{location.reload();});   
				}
				else Swal.fire(data.message);       
			}
		});		
		return;
	}
	
	//  입금은행변경
	function form5_submit(f)
	{

		event.preventDefault();
		var formData = $(f).serialize();	

		$.ajax({
			type: "POST",
			url: "./idDetail.update.php",
			data:formData,
			cache: false,
			async: false,
			dataType:"json",
			success: function(data) {

				if(data.result==true){					

					var mb_bank=	data.datas['mb_bank'];
					var mb_bank_num=	data.datas['mb_bank_num'];
					var mb_bank_user=	data.datas['mb_bank_user'];
					
					$('.mb_bank').html(mb_bank +' '+ mb_bank_num  +' '+ mb_bank_user);
					
					$('.popup.i05').removeClass('on');
					Swal.fire({text:'적용되었습니다',timer:1000});   
				}
				else Swal.fire(data.message);       
			}
		});		
		return;
	}
	
	//  테더지갑주소변경
	function form7_submit(f)
	{

		event.preventDefault();
		var formData = $(f).serialize();	

		$.ajax({
			type: "POST",
			url: "./idDetail.update.php",
			data:formData,
			cache: false,
			async: false,
			dataType:"json",
			success: function(data) {

				if(data.result==true){					

					var mb_wallet_addr_u=	data.datas['mb_wallet_addr_u'];
					
					$('.mb_wallet_addr_u').html(mb_wallet_addr_u);
					
					$('.popup.i07').removeClass('on');
					Swal.fire({text:'적용되었습니다',timer:1000});   
				}
				else Swal.fire(data.message);       
			}
		});		
		return;
	}
	
	//  암호변경
	function form8_submit(f)
	{
		event.preventDefault();
		var formData = $(f).serialize();	

		$.ajax({
			type: "POST",
			url: "./idDetail.update.php",
			data:formData,
			cache: false,
			async: false,
			dataType:"json",
			success: function(data) {

				if(data.result==true){					

					document.form8.reset();
					
					$('.popup.i08').removeClass('on');
					Swal.fire({text:'변경되었습니다',timer:1000});   
				}
				else Swal.fire(data.message);       
			}
		});		
		return;
	}
	
		
	//  계정 활성
	function formaccount_submit()
	{

		var ac_id=$('input[name=ac_id]','#formaccount').val();
		var mb_id=$('input[name=mb_id]','#formaccount').val();
		
		var formData = $('#formaccount').serialize();	

		$.ajax({
			type: "POST",
			url: "./idDetail.update.php",
			data:formData,
			cache: false,
			async: true,
			dataType:"json",
			success: function(data) {

				if(data.result==true){													
					
					if(ac_id){
						if(data.datas['ac_active']=='1'){
							$('#acc-'+data.datas['ac_no']).next('label').html(ac_id+' (활성화됨)');
							$('.idDetailActive[data-no='+data.datas['ac_no']+']').addClass('d-none');
							$('input#acc-'+data.datas['ac_no']).prop('checked',true);
						
						}
										
					}
					if(mb_id){
						if(data.datas['mb_active']=='1'){
							$('#acc-main').next('label').html(mb_id+' (활성화됨)');
							$('.idDetailActive[data-no='+data.datas['mb_no']+']').addClass('d-none');
							$('input#acc-main').prop('checked',true);
						}										
					}
					Swal.fire({text:'적용되었습니다',timer:1000});   
				}
				else{
					document.formaccount.reset();
					Swal.fire(data.message);
				}
			}
		});		
		return;
	}	
    </script>
<?	
include_once('../_tail.php');
?>
