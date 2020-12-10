<?php

//error_reporting(E_ALL);

//ini_set("display_errors", 1);
include_once('./_common.php');

$outer_css='  stoneDetail fee';
add_javascript('<script src="'.G5_THEME_URL.'/extend/clipboard.min.js"></script>', 1);
include_once('../_head.php');

$isum=get_itemsum($member[mb_id]);

$last_date=date("Y-m-d", strtotime('-1 days'));

//신고가 가능한 시간대
if ((date("H") >= 19 ||  date("H") <= 10)) {
    $enable_siren=true;
} else {
    $enable_siren=false;
}

?>

<?php?>


<div id="Contents" class="sub_con">
    
    <div id="sec1" class="sec_wrap">
	<?php
    if (preg_match("/^2/", $stats_stx)) {?>
        <img src="<?=G5_THEME_URL?>/images/head_sales.png" width="100%" />
	<?} else {?>
        <img src="<?=G5_THEME_URL?>/images/prize_img1.png" width="100%" />
	<?}?>
    </div>
    
    <ul id="sec2" class="sec_wrap sec2_wrap">
        <li>10/25</li>
        <li class="c_pink">$2,580</li>
    </ul>


    <ul id="sec5" class="sec_wrap sec5_wrap">
        <li><a href="#" style="background-image:url(<?=G5_THEME_URL?>/images/prize_bg2.png);">송금대기 <?=$buyer_stats[cnt_stats_1]>99?'+99':($buyer_stats[cnt_stats_1]?$buyer_stats[cnt_stats_1]:0)?>건</a></li>    
        <li><a href="#" style="background-image:url(<?=G5_THEME_URL?>/images/prize_bg4.png);">송금완료 <?=$buyer_stats[cnt_stats_2]>99?'+99':($buyer_stats[cnt_stats_2]?$buyer_stats[cnt_stats_2]:0)?>건</a></li>
        <li><a href="#" style="background-image:url(<?=G5_THEME_URL?>/images/prize_bg1.png);">완료대기 <?=$buyer_stats[all_claim]>99?'+99':($buyer_stats[all_claim]?$buyer_stats[all_claim]:0)?>건</a></li>
        <li><a href="#" style="background-image:url(<?=G5_THEME_URL?>/images/prize_bg3.png);">거래완료 <?=$buyer_stats[cnt_stats_4]>99?'+99':($buyer_stats[cnt_stats_4]?$buyer_stats[cnt_stats_4]:0)?>건</a></li>
    </ul>

    <div class="mt2em mb1-5em"><img src="<?=G5_THEME_URL?>/images/sec3_line.png" width="100%" /></div>

    <ul class="mem_list">
	<?php
if ($cset[service_block] !='1') {
        //판매
        if (preg_match("/^2/", $stats_stx)) {
            $sql_common = " from {$g5['cn_item_trade']} a 
			left outer join  {$g5['member_table']} as b on(a.mb_id=b.mb_id) ";
            $sql_search = " where (1)  ";//and a.fmb_id='{$member[mb_id]}'
            if (!$sst) {
                $sst  = "a.tr_code ";
                $sod = "desc";
            }
            
            if ($stats_stx=='2-1') {
                $sql_search.=" and tr_stats='1' ";
            } elseif ($stats_stx=='2-2') {
                $sql_search.=" and tr_stats='2' ";
            } elseif ($stats_stx=='2-bad') {
                $sql_search.=" and (tr_buyer_claim='1' or tr_seller_claim='1' ) and tr_stats!='9' and tr_stats!='3' ";
            } elseif ($stats_stx=='2-3') {
                $sql_search.=" and tr_wdate>='$last_date' and  tr_stats='3' ";
            }
            
            
            $sql_order = " order by $sst $sod ";
            
            $sql = " select count(*) as cnt {$sql_common} {$sql_search}";
            $row = sql_fetch($sql, 1);
            $total_count = $row['cnt'];
        
            $rows = $config['cf_mobile_page_rows'];
            $total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
            if ($page < 1) {
                $page = 1;
            } // 페이지가 없으면 첫 페이지 (1 페이지)
            $from_record = ($page - 1) * $rows; // 시작 열을 구함
            
            $sql = " select a.*,b.mb_id bmb_id,b.mb_name bmb_name,b.mb_hp bmb_hp,b.mb_bank,b.mb_bank_num, b.mb_bank_user  {$sql_common} {$sql_search} {$sql_order} limit {$from_record}, {$rows} ";
            $result = sql_query($sql, 1);
            $list_num = $total_count - ($page - 1) * $rows;
            for ($i=0; $row=sql_fetch_array($result); $i++) {
                $info=$g5[cn_item][$row[cn_item]]; ?>
				<li class="mem_bx">
					<div class="mtp">
						<div class="img"><img src="<?=G5_THEME_URL?>/images/<?=$info[img]?>" /></div>
						<div class="txt">
							<?=$info[name_kr]?><br />
							판매자 : <?=$row[smb_id]?><br />
							<?php
                            if ($row[tr_paytype]!='cash') {
                                if ($row[tr_discount] > 0) {?>
									가격 : $ <?=number_format2($row[tr_price_org])?><br />
									할인가격 : $ <?=number_format2($row[tr_price])?><br />
									할인율 : <?=number_format2($row[tr_discount])?>%<br />
								<?} else {?>
									팔릴가격 : $<?=number_format2($row[tr_price])?><br />
								<?}
                            } ?>
							<?if ($row[tr_paytype]=='cash' || $row[tr_paytype]=='both') {?>
							원화가격 : ￦<?=number_format2($row[tr_price_org]*$g5['cn_won_usd'])?><br />
							<?} ?>
							수수료 : <?=number_format2($row[tr_fee])?> <?=$g5[cn_cointype][$row[cn_fee_coin]]?>
		
							<br /><br />
							<?php
                            if ($row[tr_paytype]=='cash' ||  $row[tr_paytype]=='both') {?>
							<?php
                                //회사직영
                                if ($row[tr_distri]=='hq') {?>
									입금정보 : --- / 예금주 : ---<br />
									계좌번호 : -------						
								<?} else {?>					
									입금정보 : <?=$row[tr_bank]?> / 예금주 : <?=$row[tr_bank_user]?><br />
									계좌번호 : <?=$row[tr_bank_num]?>		
								<?php }?>
							<?php } ?>	
						</div>
					</div>
					<div class="mbt">
						<div class="t">상품번호 + 입금자명</div>
						<div class="c">
							<?php if ($row[tr_bank_num]!='' || $row[tr_distri]=='hq') {?>
								<input name="tr_deposit" type="text" class="orderCopy ipt_txt" id="tr_deposit" value="<?=$row[tr_deposit]?>" placeholder="입금자명"  readonly >
							<?php } ?>
							<?php if ($row[tr_stats]!='3') { ?>
							<button type='button' class='ipt_btn btn-complete' data-code='<?=$row[tr_code]?>' ><img src="<?=G5_THEME_URL?>/images/price_chk_btn.png" /></button>
							<?php } ?>
							
						</div>
						<div class="s"><b>* dhghst21 </b> <span>오후 1시전까지 확인해드립니다. 문제 있을 경우 문자 요망</span></div>
					</div>
					<!--
					<form name='form_<?=$row[tr_code]?>' id='form_<?=$row[tr_code]?>'  >
						<input type='hidden' name='tr_code' value='<?=$row[tr_code]?>' >	
						<input type='hidden' name='w' value='complete' >	
						<div class="popup i01">			
							<h4 style='line-height:1.2em;'>신고하기</h4>
							<b>신고사유를 입력하세요</b>
							<ul>
								<li>
								<textarea name='tr_buyer_memo' id='tr_buyer_memo' class='w-100 text-limit' ></textarea>
								</li>
							</ul>
		
							<div class="btns">
								<ul>
									<li class='w-50'>
										<button type='submit' >신고하기</button>
									</li>
									<li class='w-50'>
										<button type='button'  class='btn-close'>닫기</button>
									</li>
								</ul>
							</div>
						</div>
					</form>	
					<div class="storeBuy">
						<ul>
							<li>
								<button type='button' class='btn-bank' data-code="<?=$row[tr_code]?>" >입금정보</button>
							</li>
							//<li>
								//  <button>독촉</button>
							//</li>
							//<li>
								//  <button>신고</button>
							//</li>
							<?php
                            
                            if ($enable_siren) {?>				
							<li>
								<button type='button' class='btn-claim-open' data-code='<?=$row[tr_code]?>' >신고</button>
							</li>
							<?php
                            } ?>
							
						<?php
                            if ($row[tr_stats]!='3') {?>
							<li>
								<button type='button' class='btn-complete' data-code='<?=$row[tr_code]?>' >상품전송</button>
							</li>
							<?php } ?>
						</ul>
					</div>
					-->
				</li>
			<?php
            } ?>
		
		<?php
        }
        //구매
        if (preg_match("/^1/", $stats_stx)) {
            $sql_common = " from {$g5['cn_item_trade']} a 
	left outer join  {$g5['member_table']} as b on(a.fmb_id=b.mb_id) ";
            $sql_search = " where (1)  ";//and a.mb_id='{$member[mb_id]}'
            if (!$sst) {
                $sst  = "a.tr_code ";
                $sod = "desc";
            }
    
            if ($stats_stx=='1-1') {
                $sql_search.=" and tr_stats='1' ";
            } elseif ($stats_stx=='1-2') {
                $sql_search.=" and tr_stats='2' ";
            } elseif ($stats_stx=='1-bad') {
                $sql_search.=" and (tr_buyer_claim='1' or tr_seller_claim='1' )  and tr_stats!='9'  and tr_stats!='3' ";
            } elseif ($stats_stx=='1-3') {
                $sql_search.=" and tr_wdate>='$last_date' and tr_stats='3'  ";
            }
    
            $sql_order = " order by $sst $sod ";
    
            $sql = " select count(*) as cnt {$sql_common} {$sql_search}";
            $row = sql_fetch($sql, 1);
            $total_count = $row['cnt'];

            $rows = $config['cf_mobile_page_rows'];
            $total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
            if ($page < 1) {
                $page = 1;
            } // 페이지가 없으면 첫 페이지 (1 페이지)
    $from_record = ($page - 1) * $rows; // 시작 열을 구함

    

    $sql = " select a.*,b.mb_id bmb_id,b.mb_hp bmb_hp,b.mb_name bmb_name,b.mb_bank,b.mb_bank_num, b.mb_bank_user, b.mb_trade_paytype  {$sql_common} {$sql_search} {$sql_order} limit {$from_record}, {$rows} ";
            $result = sql_query($sql, 1);

            $list_num = $total_count - ($page - 1) * $rows;


            for ($i=0; $row=sql_fetch_array($result); $i++) {
                $info=$g5[cn_item][$row[cn_item]];
    
                if ($row[tr_stats]=='1') {
                    $warn_class="  class=' text-warning'";
                } else {
                    $warn_class="";
                } ?>
			<li class="mem_bx">
				<div class="mtp">
					<div class="img"><img src="<?=G5_THEME_URL?>/images/<?=$info[img]?>" /></div>
					<div class="txt">
						<?=$info[name_kr]?><br />
						판매자 : <?=$row[fsmb_id]?><br />
						<!-- 판매자 연락처 : <?=$row[bmb_hp]?><br />
						판매자 이름 : <?=$row[mb_bank_user]!=''?$row[mb_bank_user]:$row[bmb_name]?><br /> -->
						<br /><br />
						<!-- 거래번호 : <?=$row[tr_code]?><br /> -->
						주문일 : <?=$row[tr_rdate]?>(싱가폴)<br />
						결제일 : <?=!preg_match("/^00/", $row[tr_paydate])?$row[tr_paydate]:'-'?><br />
						확정일 : <?=!preg_match("/^00/", $row[tr_setdate])?$row[tr_setdate]:'-'?><br />
						상태 : <?=$g5['tr_stat'][$row[tr_stats]]?><br />
						<br />
						<?php
                        if ($row[tr_paytype]!='cash') {
                            if ($row[tr_discount] > 0) {?>
								가격 : $ <?=number_format2($row[tr_price_org])?><br />
								할인가격 : $ <?=number_format2($row[tr_price])?><br />
								할인율 : <?=number_format2($row[tr_discount])?>%<br />
							<?} else {?>
								팔릴가격 : $<?=number_format2($row[tr_price])?><br />
							<?}
                        } ?>
						<?if ($row[tr_paytype]=='cash' || $row[tr_paytype]=='both') {?>
						원화가격 : ￦<?=number_format2($row[tr_price_org]*$g5['cn_won_usd'])?><br />
						<?} ?>
						수수료 : <?=number_format2($row[tr_fee])?> <?=$g5[cn_cointype][$row[cn_fee_coin]]?>
	
						<br /><br />
						<?php
                        if ($row[tr_paytype]=='cash' ||  $row[tr_paytype]=='both') {?>
						<?php
                            //회사직영
                            if ($row[tr_distri]=='hq') {?>
								입금정보 : --- / 예금주 : ---<br />
								계좌번호 : -------						
							<?} else {?>					
								입금정보 : <?=$row[tr_bank]?> / 예금주 : <?=$row[tr_bank_user]?><br />
								계좌번호 : <?=$row[tr_bank_num]?>		
							<?php }?>
						<?php } ?>	
					</div>
				</div>
				<div class="mbt">
					<div class="t">상품번호 + 입금자명</div>
					<div class="c">
						<?php if ($row[tr_bank_num]!='' || $row[tr_distri]=='hq') {?>
							<input name="tr_deposit" type="text" class="orderCopy ipt_txt" id="tr_deposit" value="<?=$row[tr_deposit]?>" placeholder="입금자명"  readonly >
						<?php } ?>
						<?php if ($row[tr_stats]!='3') { ?>
						<button type='button' class='ipt_btn btn-complete' data-code='<?=$row[tr_code]?>' ><img src="<?=G5_THEME_URL?>/images/price_chk_btn.png" /></button>
						<?php } ?>
						
					</div>
					<div class="s"><b>* dhghst21 </b> <span>오후 1시전까지 확인해드립니다. 문제 있을 경우 문자 요망</span></div>
				</div>
				<!--
				<form name='form_<?=$row[tr_code]?>' id='form_<?=$row[tr_code]?>'  >
					<input type='hidden' name='tr_code' value='<?=$row[tr_code]?>' >	
					<input type='hidden' name='w' value='complete' >	
					<div class="popup i01">			
						<h4 style='line-height:1.2em;'>신고하기</h4>
						<b>신고사유를 입력하세요</b>
						<ul>
							<li>
							<textarea name='tr_buyer_memo' id='tr_buyer_memo' class='w-100 text-limit' ></textarea>
							</li>
						</ul>
	
						<div class="btns">
							<ul>
								<li class='w-50'>
									<button type='submit' >신고하기</button>
								</li>
								<li class='w-50'>
									<button type='button'  class='btn-close'>닫기</button>
								</li>
							</ul>
						</div>
					</div>
				</form>	
				<div class="storeBuy">
					<ul>
						<li>
							<button type='button' class='btn-bank' data-code="<?=$row[tr_code]?>" >입금정보</button>
						</li>
						//<li>
							//  <button>독촉</button>
						//</li>
						//<li>
							//  <button>신고</button>
						//</li>
						<?php
                        
                        if ($enable_siren) {?>				
						<li>
							<button type='button' class='btn-claim-open' data-code='<?=$row[tr_code]?>' >신고</button>
						</li>
						<?php
                        } ?>
						
					<?php
                        if ($row[tr_stats]!='3') {?>
						<li>
							<button type='button' class='btn-complete' data-code='<?=$row[tr_code]?>' >상품전송</button>
						</li>
						<?php } ?>
					</ul>
				</div>
				-->
			</li>
	<?php
            } ?>

<?php
        } ?>

<?php
    } else {
        ?>

<div class='text-center mt-5'>
지금은 매칭이 진행중이거나 시스템 점검중으로<br>잠시 서비스 이용이 정지 중입니다
</div>

<?php
    }?>
<script>
	
	$(document).ready(function () {
		$('.btn-bank').click(function () {
			event.preventDefault();
			var c=$(this).attr('data-code');
			 $('#form_'+c+" .deposit-bank").toggleClass('d-none');
			 
			 console.log(c);
			 
		});
		
		$('.btn-deposit').click(function () {			
			
			var c=$(this).attr('data-code');
			form_submit(c);
		});
		
		$('.btn-complete').click(function () {			
			event.preventDefault();
			Swal.fire({
			  title: '주의',
			  text: "입금확인을 정확히 하셨습니까? 전송이 완료된 나비는 취소가 불가능합니다.",
			  icon: 'warning',
			  showCancelButton: true,
			  confirmButtonColor: '#3085d6',
			  cancelButtonColor: '#d33',
			  confirmButtonText: '나비 보내기'
			}).then((result) => {
			  if (result.value) {
				var c=$(this).attr('data-code');
				trade_end(c);
			  }
			})
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

		//txid복사
		$("button.txid-btn").click(function(){
			var c = $(this).attr('data-code');
			var txid=$("input[name='tr_txid']","#form_"+c).val();
			
			if(txid=='') {
				Swal.fire({text:'TXID가 입력되지 않았습니다',timer:2000});   
				return 
			}

			window.open("https://etherscan.io/tx/"+txid);
		});


		$('.popup .btn-close').click(function () {
			$(this).parents('.popup').removeClass('on');
		});

		$('.btn-claim-open').click(function () {
			var c=$(this).attr('data-code');
			$('.popup-'+c).addClass('on');
		});		
		
		$('textarea.text-limit').on('keyup keypress',function () {			
			var limit=$(this).attr('text-limit-len');	
		 	var lng=$(this).val().length;
			if (lng > limit) {
				$(this).val($(this).val().substring(0, limit));
				return false;
			}
			$(this).next('.text-legnth-val').html(lng);
			console.log(lng);
		});
		   
		   
		//구매자 신고
		$('.btn-buyer-claim').click(function () {			
			event.preventDefault();
			Swal.fire({
			  title: '주의',
			  text: " 신고를 진행 하시겠습니까",
			  icon: 'warning',
			  showCancelButton: true,
			  confirmButtonColor: '#3085d6',
			  cancelButtonColor: '#d33',
			  confirmButtonText: '진행합니다'
			}).then((result) => {
			  if (result.value) {
				var c=$(this).attr('data-code');
				insert_claim(c,'buyer',$('#form_'+c+' textarea[name=tr_seller_memo]').val() );			
			  }
			})
		});
		
		//판매자 신고
		$('.btn-seller-claim').click(function () {			
			event.preventDefault();
			Swal.fire({
			  title: '주의',
			  text: "신고를 진행 하시겠습니까",
			  icon: 'warning',
			  showCancelButton: true,
			  confirmButtonColor: '#3085d6',
			  cancelButtonColor: '#d33',
			  confirmButtonText: '진행합니다'
			}).then((result) => {
			  if (result.value) {
				var c=$(this).attr('data-code');
				insert_claim(c,'seller',$('#form_'+c+' textarea[name=tr_seller_memo]').val() );
			  }
			})
		});
		

	});
	
	//  테더지갑주소변경
	function form_submit(c)
	{

		event.preventDefault();
		var formData = $('#form_'+c).serialize();	

		$.ajax({
			type: "POST",
			url: "./incomplete.update.php",
			data:formData,
			cache: false,
			async: false,
			dataType:"json",
			success: function(data) {

				if(data.result==true){					

					Swal.fire({text:' 송금완료 신청이 접수되었습니다',timer:2000});   
				}
				else Swal.fire(data.message);       
			}
		});		
		return;
	}
	
	//  거래 종료
	function trade_end(c)
	{

		event.preventDefault();
		var formData = $('#form_'+c).serialize();	

		$.ajax({
			type: "POST",
			url: "./incomplete.update.php",
			data:formData,
			cache: false,
			async: false,
			dataType:"json",
			success: function(data) {

				if(data.result==true){					
					$('#form_'+c +' .stats-str').html('상 태 : '+data.datas['stats']);
					$('#form_'+c +' .setdate-str').html('확정일 : '+data.datas['setdate']);
					$('#form_'+c +' .btn-complete').parent('li').hide();
					Swal.fire({text:'거래를 종료하였습니다',timer:2000});   
				}
				else Swal.fire(data.message);       
			}
		});		
		return;
	}
	
	//  신고
	function insert_claim(c,f)
	{

		event.preventDefault();
		
		$.ajax({
			type: "POST",
			url: "./incomplete.update.php",
			data:{w:'claim',tr_code:c,from:f,memo:m},
			cache: false,
			async: false,
			dataType:"json",
			success: function(data) {

				if(data.result==true){	
					if(f=='buyer'){
						$('#form_'+c +' .buyer-claim').html('구매자 신고중 : '+data.datas['tr_note']).removeClass('d-none');					
					}
					if(f=='seller'){
						$('#form_'+c +' .seller-claim').html('판매자 신고중 : '+data.datas['tr_note']).removeClass('d-none');						
					}
					Swal.fire({text:'신고되었습니다',timer:2000});   
				}
				else Swal.fire(data.message);  
				
			}
		});		
		$('.popup-'+c).removeClass('on');
		
		return;
	}
	
</script>
<?php
include_once('../_tail.php');
