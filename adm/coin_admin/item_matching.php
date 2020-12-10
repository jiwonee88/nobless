<?php
$sub_menu = "700700";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'w');

$html_title = $g5[cn_item_name].'-매칭실행-회사';


$g5['title'] = $html_title;
include_once(G5_ADMIN_PATH.'/admin.head.php');
include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');

if($fee_free=='')$fee_free='free';
if($posess_item=='') $posess_item='same';
if($posess_disable=='') $posess_disable='account';



?>
<form name="fcommonform" id="fcommonform" action="<?=$PHP_SELF?>" method="post"  onsubmit="return fcommonform_ajax(this);" enctype="multipart/form-data">
<input type="hidden" name="w" value="p">
<input type='hidden' name='current_item'  id="current_item"  value='<?=$k?>'>

<input type="hidden" name="token" value="">
<section id="anc_rt_basic">


    <div class="tbl_frm01 tbl_wrap">
	<h2 class="h2_frm">매칭 조건 조정 </h2>
        <table>

 <colgroup>
            <col class="grid_4">
            <col>
            <col class="grid_3">
        </colgroup>
        
       
<tr>
<th scope="row"><label for="in_set_amt2">매칭일자</label></th>
<td><input name="start_date" type="text"  class="frm_input " id="start_date" value="<?=date("Y-m-d")?>" size="20" readonly="readonly" <?=$disabled?>/></td>
</tr>
<tr>
<th scope="row"><label for="in_set_amt2">미시용자 제한</label></th>
<td><input name="login_day" type="text"  class="frm_input " id="login_day" value="2" size="5"/>
일 이내 미 로그인 사용자 제외(0 이면 제한없음)</td>
</tr>
<tr>
<th scope="row"><label for="in_set_amt2">수수료무료</label></th>
<td>
<label>
<input name="fee_free" type="radio" id="fee_free1" value="free" <?=$fee_free=='free'?'checked':''?> >
수수료 없이 매칭 진행</label>&nbsp;&nbsp;

<label>
<input name="fee_free" type="radio" id="fee_free2" value="pay" <?=$fee_free=='pay'?'checked':''?>  >
<?=$g5['cn_item_name']?>별 지정 수수료 부과</label>

</td>
</tr>
<tr>
<th scope="row">보유회원제외</th>
<td>
<label>
(
<input name="posess_item" type="radio" id="posess_item" value="same" <?=$posess_item=='same'?'checked':''?>   >
동일 <?=$g5['cn_item_name']?>을</label>&nbsp;&nbsp;
<label><input name="posess_item" type="radio" id="posess_item" value="any" <?=$posess_item=='any'?'checked':''?>  >
어떤 <?=$g5['cn_item_name']?>이라도</label>
) 
<br>
<label>
<input name="posess_disable" type="radio" id="posess_disable" value="member" <?=$posess_disable=='member'?'checked':''?>   >
보유한 회원 제외(서브계정도 제외)</label>
<label>
<input name="posess_disable" type="radio" id="posess_disable" value="account" <?=$posess_disable=='account'?'checked':''?>>
보유한 계정만 제외</label>
<label>
<input name="posess_disable" type="radio" id="posess_disable" value="none" <?=$posess_disable=='none'?'checked':''?>>
조건없음</label>

</td>
</tr>
<tr>
<th scope="row">제외될 아이디</th>
<td>
<?=help('제외될 아이디를 1인/1라인 또는 콤마로 구분하여 입력하세요')?>
<textarea name='except_id' class='frm_input'><?=$except_id?></textarea></td>
</tr>
</table>
 </div>



<div class="tbl_head01 tbl_wrap">	
<h2 class="h2_frm">구매대기/지급량</h2>
  
<table >
<thead>
<tr>
<th width="100" rowspan="2" nowrap="nowrap" scope="row"><?=$g5['cn_item_name']?></th>
<th rowspan="2" nowrap="nowrap">가격</th>
<th rowspan="2" nowrap="nowrap">구매대기<br>
(최대추정)</th>
<th colspan="2" nowrap="nowrap" class='text-center' >USDT</th>
<th colspan="2" nowrap="nowrap"  class='text-center' >CASH</th>
<th rowspan="2" nowrap="nowrap">매칭수량</th>
<th rowspan="2" nowrap="nowrap">총액</th>
</tr>

<tr>
<th nowrap="nowrap">제공금액</th>
<th nowrap="nowrap">할인율표기</th>
<th nowrap="nowrap">제공금액</th>
<th nowrap="nowrap">페이백
<?=$g5[cn_cointype][$g5['cn_reward_coin']]?></th>
</tr>
</thead>


<tbody>
<?php
$i=0;
$cn_item_arr=array_reverse($g5['cn_item']);
foreach($cn_item_arr as $k=> $v) {    



//기본 회원 목록
$sql_common = " from {$g5['cn_sub_account']} as a 
left outer join  {$g5['member_table']} as b on(a.mb_id=b.mb_id)  
left outer join  (select mb_id,count(*) ct_cnt,sum(ct_buy_price) ct_buy_price  from {$g5['cn_item_cart']}  where  is_soled != '1' and  ct_wdate > '$start_date' group by mb_id)  as c on(c.mb_id=b.mb_id) ";

$sql_search = " where  a.ac_active ='1' ";


//해당 상품 오토 매칭 on 계정
$sql_search .=" and ac_auto_".$k." = '1' ";

//구매대기에서 매칭 제외 여부
$sql_search .=" and ac_mc_except!='1' ";

//가용금액이 상품 가격보다 큰 회원
$sql_search .=" and (b.mb_trade_amtlmt - if(c.ct_buy_price,c.ct_buy_price,0) ) >= $v[price] ";

//수수료 가용금액이 지정 수수료 보다 큰 회원
if($item_fee > 0) $sql_search .=" and a.ac_point_".$g5[cn_fee_coin]." >=  $v[fee] ";

//입금 계좌 정보가 있는 회원
$sql_search .=" and (
( b.mb_trade_paytype='both' and (	(b.mb_bank!='' and b.mb_bank_num!='' and b.mb_bank_user!='') || b.mb_wallet_addr_".$g5['cn_pay_coin']." !=''  ) )
or (  b.mb_trade_paytype='cash' and b.mb_bank!='' and b.mb_bank_num!='' and b.mb_bank_user!='' )
)";


//동일스톤 여부
if($posess_item=='same') $posess_sql=" and cn_item='$k'";
else  $posess_sql="";

//보유 회원 제외
if($posess_disable=='member'){	
	$sql_search .=" and  not exists  (select code from {$g5['cn_item_cart']} where mb_id=a.mb_id $posess_sql and is_soled!='1')";
}
//보유 계정 제외
if($posess_disable=='account'){	
	$sql_search .=" and  not exists  (select code from {$g5['cn_item_cart']} where smb_id=a.ac_id $posess_sql and is_soled!='1')";
}

//제외될 아이디가 있는 경우
$except_id_str=preg_replace("/\n+/",",",$except_id);
$except_id_str=preg_replace("/\s+/","",$except_id_str);
$except_id_str=preg_replace("/,/","','",$except_id_str);
if($except_id_str!='' ) $sql_search .=" and a.mb_id not in ('$except_id_str') ";

$sql = " select distinct ac_id
{$sql_common} {$sql_search} group by a.ac_id";
$result = sql_query($sql,1);

?>
<input type='hidden' name='item_code[<?=$i?>]'  id="item_code_<?=$k?>"  value='<?=$k?>'>
<input type='hidden' name='item_fee[<?=$k?>]'  id="item_fee_<?=$k?>"  value='<?=$v[fee]?>'>

<tr>
<td >
<?=$v[name_kr]?>
</td>
<td><?=number_format2($v[price])?></td>
<td><?=sql_num_rows($result)?></td>
<td><input name="item_price[<?=$k?>]" type="text"  id="item_price_<?=$k?>" data-id='<?=$k?>'  class="frm_input"  size="10" value='<?=$v[price]?>' /></td>
<td><input name="item_discount[<?=$k?>]" type="text"  id="item_discount_<?=$k?>" class="frm_input w-auto" size="10" />
%</td>
<td><input name="item_price_cash[<?=$k?>]" type="text"  id="item_price_cash_<?=$k?>" data-id='<?=$k?>'  class="frm_input"  size="10" value='<?=number_format($v[price]*$g5['cn_won_usd'])?>' /></td>
<td><input name="item_payback_cash[<?=$k?>]" type="text"  id="item_payback_cash<?=$k?>" data-id='<?=$k?>'  class="frm_input  w-auto"  size="10" />
%</td>
<td><input name="item_qty[<?=$k?>]" type="text"  id="item_qty_<?=$k?>" class="frm_input"  size="10" /></td>
<td><span id='tot_price_<?=$k?>'>0</span></td>
</tr>
<? 
$i++;
}?>


<tr>
<td colspan="8" align="center" >지급총액</td>
<td><span id='tot_price2'>0</span></td>
</tr>


</tbody>
</table>
</div>


<!--tr>
<th scope="row">수수료</th>
<td><strong><span id='tot_fee'>
<input name="tot_fee" id="tot_fee"  type="text"  class="frm_input"  value="0" size="20" readonly />
</span></strong> <?=$g5[cn_cointype][$g5['cn_fee_coin']]?></td>
</tr-->
    
</section>

<div class="text-center">
<input type="submit" name="act_button" value="미리보기" onclick="document.fcommonform.w.value='p';document.pressed=this.value;" class="btn_prev btn btn_02" accesskey="s">
<input type="submit" name="act_button" value="매칭실행" onclick="document.fcommonform.w.value='x';document.pressed=this.value; "class="btn_submi btn btn_01" accesskey="s">
</div>

</form>


<div id='result_window' style='width:100%;height:700px;padding:10px;overflow-y:auto;background:#efefef;border:1px solid;#787878;margin-top:10px;'>

</div>
<script>  
function sum(){
	var tot=0;
	var tfee=0;
	var t=0
	$("input[name^=item_price]").each(function(){
		var k=$(this).attr('data-id');
		var qty=parseInt($("input#item_qty_" + k).val());		
		if(!qty || qty==0) return;;
		
		var price=parseFloat($("input#item_price_" + k).val());		
		if(!price || price==0) return;;
		
		var fee=parseInt($("input#item_fee_" + k).val());
		t=(qty*price).toFixed(1)*1;
		tot+=(qty*price).toFixed(1)*1;
		tfee+=(qty*fee).toFixed(1)*1;
		
		$("#tot_price_"+k).html(inputNumberFormat(t));
		
	});

	
	$("#tot_price").html(inputNumberFormat(tot.toFixed(1)*1));
	//$("input#tot_fee").val(inputNumberFormat(tfee));

}

$(document).ready(function(e) {
	$("input[name^=item_qty],input[name^=item_price]").on("change keyup",function(){		
		sum();
	})	
	
	$("input[name=fee_free],input[name=posess_item],input[name=posess_disable],input[name=posess_item]").on("change keyup",function(){		
		sum();
	})	
	
	
});

	
function fcommonform_ajax(f)
{
	
	var current_item;
	var qty=0;
	var q=0;
	
	$("input[name^=item_qty]").each(function(){
		 var q = parseInt($(this).val());		
		 
		 if(!q || q==0) return;
		 
		 qty+=q;		 
	});
	
	if (qty < 1) {
	  alert("최소 한개 이상 지급 하세요.");          
	  return false;
	}
	
	if(document.pressed == "매칭실행") {
       if(!confirm("매칭을 진행하시겠습니까?")) {
           return false;
        }
    }	

	$('#result_window').html('');
	alert_loading2('매칭진행중','open');
	 
	event.preventDefault();		
	
	var rtn=true;
	
	$("input[name^=item_code]").each(function(){	
		
		var k=$(this).val();
		
		$("input[name=current_item]").val(k);		
		var qty=$("input#item_qty_"+k).val();
		if(!qty) qty=0;		
		
		if(qty == 0 || qty=='' ) return;
		
		var formData = $(f).serialize();	
		
		$.ajax({
			type: "POST",
			url: "./item_matching_update.php",
			data:formData,
			cache: false,
			async: false,
			dataType:"json",
			success: function(data) {

				if(data.result==true){					
					$('#result_window').html($('#result_window').html()+data.datas['htmls']);
					console.log(data.datas['htmls']);
				}
				else{
					alert(data.message);      
					rtn=false
				}
			}
		});		
		
		if(rtn==false) return false;;
	});
	
	alert_loading2('매칭종료','close');	
	
	return false;;
}
</script>

<?php
include_once(G5_ADMIN_PATH.'/admin.tail.php');
?>
