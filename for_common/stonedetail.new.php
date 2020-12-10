<?php


include_once('./_common.php');
$outer_css=' stoneDetail';

include_once('../_head.php');

$isum=get_itemsum($member[mb_id]);
?>
  
        <div class="wrap">
            <div class="area area01">
                <h3><span>my store</span></h3>
                <ul class="common">
                    <li class="squareWB hero w50" onclick="location.href='idDetail.html'"><span><?=$member[mb_id]?></span></li>
                    <li class="squareWB stone w50 text-left">&nbsp;<span class="stoneBuystone text-narrow0"><?=number_format2($rpoint['i']['_enable'])?></span>
								<a href='/for_common/fee.php' class="buyBtn text-dark">구매</a></li>
                </ul>
            </div>
            <div class="area area02">
                <ul class="buy sell">
                    <li class="squareWB" onclick="location.href='/for_common/idDetail.php'"><span class="f_yellow text-narrow0">$<?=number_format2($member[mb_trade_amtlmt])?></span><span
                            class="condition">설정금액</span></li>
                    <li class="squareWB" onclick="location.href='/for_common/idDetail.php'"><span class="f_yellow text-narrow0">$<?=number_format2($isum[tot][price]>$member[mb_trade_amtlmt]?0:$member[mb_trade_amtlmt]-$isum[tot][price])?></span><span
                            class="condition">가용금액</span></li>
                    <li class="squareWB" onclick="location.href='/for_common/stonedetail.php'"><span
                            class="f_yellow text-narrow0"><?=number_format2($isum[tot][price])?></span><span class="condition confirm">보유금액</span>
                    </li>
                </ul>
            </div>
            <!--div class="area area03">
                <ul class="common">
                    <li class="squareWB stone w100 clearfix">
                        <span class="stoneBg confirm">MY STONE</span>
                        <p class="f_right">
                            <p class="buyBtn">6일</p>
                            <p  class="buyBtn">18%</p>
                            <p  class="buyBtn">80</p>
                        </p>

                    </li>
                </ul>
				
                <div class="stoneTxt">보유중인 상품이 없습니다.</div>
				
				
            </div-->
			<?
			$temp=sql_fetch("select count(*) cnt from {$g5['cn_item_cart']} where mb_id='$member[mb_id]'  and is_soled!='1'  ",1);
			?>
			<div class="area area03">
                <ul class="common">
                    <li class="squareWB stone w100 clearfix">
                        <span class="stoneBg confirm">MY STONE</span>
                        <p class="f_right pr-2">
                            총 보유 수량 <?=$temp[cnt]?> 개
                        </p>

                    </li>
                </ul>				
				
            </div>
			
			<ul class="stoneList">				
					<?
					$re=sql_query("select * from {$g5['cn_item_cart']} where mb_id='$member[mb_id]' and is_soled!='1'  group by cn_item order by cn_item ",1);
					while($data=sql_fetch_array($re)){
					?>
                    <li class="squareWB">
                        <div class="clearfix">
                            <div class="stoneImg f_left">
                                <img src="<?=G5_THEME_URL?>/images/stone/<?=$g5[cn_item][$data[cn_item]]['img']?>" alt="<?=$g5[cn_item][$data[cn_item]]['name_kr']?>">
                            </div>
                            <div class="stoneDesc f_left">
                                <h4 class='my-1' ><?=$g5[cn_item][$data[cn_item]]['name_kr']?></h4>
                                <ul>
                                    <li class="goldInfo">
									<span class="howlong"><?=$g5[cn_item][$data[cn_item]]['days']?><? //=ceil((time()-strtotime($data[ct_wdate]))/86400)?>일</span><span class="percent"><?=$data[ct_interest]?>%</span>
                                    </li>
                                    <li class="holdDate">
										<?
									$re2=sql_query("select * from {$g5['cn_item_cart']} where mb_id='$member[mb_id]' and cn_item='$data[cn_item]'  and is_soled!='1' ",1);
									while($data2=sql_fetch_array($re2)){
										$past_day=ceil( (strtotime(date("Y-m-d")) - strtotime($data2['ct_validdate'])) /86400 );
									?>
                                        <div>·보유마감 <?=$past_day?>일 <?//=substr($data2[ct_wdate],5,5)?> $<?=$data2[ct_buy_price]?></div>
								
								<? }?>
                                    </li>
                                    <li class="holdVol"><span>보유수량:<?=sql_num_rows($re2)?></span></li>
                                </ul>
                            </div>
                        </div>
                    </li>
					<? }?>

					
					
                </ul>
				
				

        </div>

   
<?	
include_once('../_tail.php');
?>
