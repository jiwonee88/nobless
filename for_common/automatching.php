<?php

include_once('./_common.php');

$outer_css=' automaching ';

add_javascript('<script src="'.G5_THEME_URL.'/extend/clipboard.min.js"></script>', 1);
include_once('../_head.php');

$mrpoint=get_mempoint($member[mb_id], $member[mb_id]);
$isum=get_itemsum($member[mb_id]);

$acc_sql = "select * from  {$g5['cn_sub_account']} where mb_id='$member[mb_id]' order by ac_id asc limit 1";
$acc_row = sql_fetch($acc_sql);
?>

<div id="Contents" class="sub_con">
    
    <div id="sec1" class="sec_wrap">
        <img src="<?=G5_THEME_URL?>/images/buy_img1.png" width="100%" />
    </div>
    
    <ul id="sec2" class="sec_wrap sec2_wrap">
        <li class=""><img src="<?=G5_THEME_URL?>/images/sec2_img3.png" /> 보유골드  <span class="c_pink">28,123</span></li>
    </ul>

    <div class="mt2em mb1-5em"><img src="<?=G5_THEME_URL?>/images/sec3_line.png" width="100%" /></div>

    <ul class="mem_list">
		<?php
        foreach ($g5['cn_item'] as $k=>$v) {?>
			<li class="mem_bx">
			<input type='hidden' name='w' value='al' >
				<div class="mtp">
					<div class="img">
						<img src="<?=G5_THEME_URL?>/images/<?=$v[img]?>" alt="" />
					</div>
					<div class="txt">
						<div class="mb0-5em"><img src="<?=G5_THEME_URL?>/images/<?=$v[img_label]?>" style="height:30px" /></div>
						
						보유기간<?=$v[days]?>일 이율<?=$v[interest]?>%<br />
						<span class="c_pink">$<?=$v[price]?> ~ $<?=$v[mxprice]?></span>
					</div>
				</div>
				<ul class="msct">
					<li class="org">
						<div class="t">구매신청</div>
						<div class="c">
							<input type="text" name="" class="ipt_num" id="cnt_<?=$k?>" value="<?=$acc_row["ac_auto_{$k}"]?>" />
							<div class="ipt_arw">
								<div class="up"><button type="button" onclick="fn_controller('cnt','<?=$k?>','up')"><img src="<?=G5_THEME_URL?>/images/icon_up.png" /></button></div>
								<div class="dw"><button type="button" onclick="fn_controller('cnt','<?=$k?>','down')"><img src="<?=G5_THEME_URL?>/images/icon_down.png" /></button></div>
							</div>
							개
						</div>
						<button type="reset" name="" class="ipt_reset" onclick="fn_controller('cnt','<?=$k?>','del')"><img src="<?=G5_THEME_URL?>/images/btn_delete.png" width="100%"/></button>
					</li>
					<li class="sky">
						<div class="t">소요골드</div>
						<div class="c">
							<input type="text" name="" class="ipt_num" id="price_<?=$k?>" value="1" />
							<div class="ipt_arw">
								<div class="up"><button type="button" onclick="fn_controller('price','<?=$k?>','up')"><img src="<?=G5_THEME_URL?>/images/icon_up.png" /></button></div>
								<div class="dw"><button type="button" onclick="fn_controller('price','<?=$k?>','down')"><img src="<?=G5_THEME_URL?>/images/icon_down.png" /></button></div>
							</div>
							개
						</div>
						<button type="reset" name="" class="ipt_reset" onclick="fn_controller('price','<?=$k?>','del')"><img src="<?=G5_THEME_URL?>/images/btn_delete.png" width="100%"/></button>
					</li>
					<button type="button" onclick="schedule_buy('<?=$k?>');" name="" class="ipt_submit"><img src="<?=G5_THEME_URL?>/images/btn_save.png" /></button>
				</ul>
			</li>
		<?php
        }?>
    </ul>
</div>
<script>
function fn_controller($mode,$obj,$fn){
	var $obj = $("#"+$mode+"_"+$obj);
	var $obj_val = $obj.val();
	if($fn=='up'){
		$obj_val++;
	}else
	if($fn=='down'){
		if($obj_val>0){
			$obj_val--;
		}
	}else
	if($fn=='del'){
		$obj_val = 0;
	}
	$obj.val($obj_val);

}

function schedule_buy($k){
	var itemCnt = $("#cnt_"+$k).val();
	if(itemCnt<=0){
		alert("아이템은 0개 이상만 가능합니다.");
		return;
	}
	var itemPrice = $("#price_"+$k).val();
	var formData = {"w":"new","cnt":itemCnt,"price":itemPrice,"type":$k};
	$.ajax({
		type: "POST",
		url: "./automatching.update.php",
		data:formData,
		cache: false,
		async: false,
		dataType:"json",
		success: function(data) {
		}
	});
}
</script>
<div style="width:100%;height:100%;position:fixed;top:0;left:0;z-index:10;background:#00000094;display:none" id="process_overlay">
	<div style="top:50%;left:0;width:100%;position:fixed;text-align:center">
		처리중입니다.
	</div>
</div>
<?php
include_once('../_tail.php');
?>
