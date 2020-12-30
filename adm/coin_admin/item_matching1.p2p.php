 <?php
$sub_menu = "700750";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'w');

$html_title = $g5[cn_item_name].'-매칭실행-P2P';


$g5['title'] = $html_title;
include_once(G5_ADMIN_PATH.'/admin.head.php');
include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');

if($fee_free2=='')$fee_free2='pay';
if($fee_free=='')$fee_free='pay';
if($posess_item=='') $posess_item='same';
if($posess_disable=='') $posess_disable='account';
if($login_day=='') $login_day=2;
if($match_cnt=='') $match_cnt=1;

?>
<form name="fcommonform" id="fcommonform" action="./item_matching.p2p.update.php" method="post"  onsubmit="return fcommonform_ajax(this);" enctype="multipart/form-data">
<input type="hidden" name="w" value="p">
<input type='hidden' name='current_item'  id="current_item"  value='<?=$k?>'>

<input type="hidden" name="token" value="">

<section id="anc_rt_basic">


<h2 class="h2_frm">판매/구매 대기 </h2>
 
 <?
 $sell=array();
 $tot_cnt=$tot_buy_price=$tot_sell_price=0;
 $re=sql_query( "select *,sum(ct_buy_price) buy_price,sum(ct_sell_price) sell_price,  count(code) cnt from {$g5['cn_item_cart']} where is_soled!='1' and is_trade!='1'  and ct_validdate <= date(now()) group by cn_item ",1);
 while($data=sql_fetch_array($re)){
 	$sell[$data[cn_item]]=$data; 
	
	$tot_cnt+=$data[cnt];
	$tot_buy_price+=$data[buy_price];
	$tot_sell_price+=$data[sell_price];
 }
 
 $tomorrow=date("Y-m-d",strtotime("+1 days"));
 $sell2=array();
 $tot_cnt2=$tot_buy_price2=$tot_sell_price2=0;
 $re=sql_query( "select *,sum(ct_buy_price) buy_price,sum(ct_sell_price) sell_price,  count(code) cnt from {$g5['cn_item_cart']} where is_soled!='1' and is_trade!='1'  and ct_validdate = '$tomorrow' group by cn_item ",1);
 while($data=sql_fetch_array($re)){
 	$sell2[$data[cn_item]]=$data; 
	
	$tot_cnt2+=$data[cnt];
	$tot_buy_price2+=$data[buy_price];
	$tot_sell_price2+=$data[sell_price];
 }
 
 $third=date("Y-m-d",strtotime("+2 days"));
 $sell3=array();
 $tot_cnt3=$tot_buy_price3=$tot_sell_price3=0;
 $re=sql_query( "select *,sum(ct_buy_price) buy_price,sum(ct_sell_price) sell_price,  count(code) cnt from {$g5['cn_item_cart']} where is_soled!='1' and is_trade!='1'  and ct_validdate = '$third' group by cn_item ",1);
 while($data=sql_fetch_array($re)){
 	$sell3[$data[cn_item]]=$data; 
	
	$tot_cnt3+=$data[cnt];
	$tot_buy_price3+=$data[buy_price];
	$tot_sell_price3+=$data[sell_price];
 }


 $fourth=date("Y-m-d",strtotime("+3 days"));
 $sell4=array();
 $tot_cnt3=$tot_buy_price4=$tot_sell_price4=0;
 $re=sql_query( "select *,sum(ct_buy_price) buy_price,sum(ct_sell_price) sell_price,  count(code) cnt from {$g5['cn_item_cart']} where is_soled!='1' and is_trade!='1'  and ct_validdate = '$fourth' group by cn_item ",1);
 while($data=sql_fetch_array($re)){
 	$sell4[$data[cn_item]]=$data; 
	
	$tot_cnt4+=$data[cnt];
	$tot_buy_price4+=$data[buy_price];
	$tot_sell_price4+=$data[sell_price];
 }



 $fifth=date("Y-m-d",strtotime("+5 days"));
 $sell5=array();
 $tot_cnt3=$tot_buy_price5=$tot_sell_price5=0;
 $re=sql_query( "select *,sum(ct_buy_price) buy_price,sum(ct_sell_price) sell_price,  count(code) cnt from {$g5['cn_item_cart']} where is_soled!='1' and is_trade!='1'  and ct_validdate = '$fifth' group by cn_item ",1);
 while($data=sql_fetch_array($re)){
 	$sell5[$data[cn_item]]=$data; 
	
	$tot_cnt5+=$data[cnt];
	$tot_buy_price5+=$data[buy_price];
	$tot_sell_price5+=$data[sell_price];
 }

 
?>
 <div class="tbl_head01 tbl_wrap">

 <table  >
<thead>
<tr>
<th width="130" rowspan="2" nowrap="nowrap" scope="row">구분</th>

<?php
$cn_item_arr=array_reverse($g5['cn_item']);
foreach($cn_item_arr as $k=> $v) {    

?>
<th colspan="3" nowrap="nowrap" class='text-center' ><?=$v[name_kr]?></th>
<? }?>
<th rowspan="2" nowrap="nowrap">총수량</th>
<th rowspan="2" nowrap="nowrap">총구매액</th>
<th rowspan="2" nowrap="nowrap">총예정판매액</th>
</tr>

<tr>

<?
foreach($cn_item_arr as $k=> $v) {?>
<th nowrap="nowrap">수량</th>
<th nowrap="nowrap">구매액</th>
<th nowrap="nowrap">예정판매액</th>
<? }?>
</tr>
</thead>

<tbody>
<tr>
<td width="130" scope="row">판매대기(오늘)</td>

<?php
foreach($cn_item_arr as $k=> $v) {    
?>
<td><?=number_format2($sell[$k][cnt]*1.0, 0)?></td>
<td><?=number_format2($sell[$k][buy_price]*1.1, 0)?></td>
<td><?=number_format2($sell[$k][sell_price]*1.1, 0)?></td>

<? }?>
<td><?=number_format2($tot_cnt*1.0, 0)?></td>
<td><?=number_format2($tot_buy_price*1.1, 0)?></td>
<td><?=number_format2($tot_sell_price*1.1, 0)?></td>

</tr>



<tr>
<td scope="row">구매대기</td>
<?php
$tot_buy_cnt=0;
foreach($cn_item_arr as $k=> $v) {    

//기본 회원 목록
$sql_common = " from {$g5['cn_sub_account']} as a 
left outer join  {$g5['member_table']} as b on(a.mb_id=b.mb_id)  
left outer join  (select mb_id,count(*) ct_cnt,sum(ct_buy_price) ct_buy_price  from {$g5['cn_item_cart']}  where  is_soled != '1' and  ct_wdate > '$start_date' group by mb_id)  as c on(c.mb_id=b.mb_id) 
left outer join  (select mb_id,count(*) ct_cnt,sum(tr_price_org) tr_price_org  from {$g5['cn_item_trade']} where  tr_stats in ('1','2')  group by mb_id)  as t on(t.mb_id=b.mb_id) ";

$sql_search = " where  a.ac_active ='1' ";

//수수료 수량
//$sql_search .=' and ';

//가용금액이 상품 가격보다 큰 회원
$sql_search .=" and (b.mb_trade_amtlmt - if(c.ct_buy_price,c.ct_buy_price,0)  - if(t.tr_price_org,t.tr_price_org,0) ) >= $v[price] ";

//수수료가용금액이 상품 가격보다 큰 회원
if($item_fee > 0) $sql_search .=" and a.ac_point_".$g5[cn_fee_coin]." >=  $v[fee] ";

//해당 상품 오토 매칭 on 계정
$sql_search .=" and ac_auto_".$k." = '1' ";

//로그인 시간 제한시
if($login_day !='' && $login_day > 0) {
	$logdate=date("Y-m-d H:i:s",strtotime("- $login_day days"));
	$sql_search.=" and b.mb_today_login >= '$logdate' ";
}

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
	//$sql_search .=" and  not exists  (select code from {$g5['cn_item_cart']} where mb_id=a.mb_id $posess_sql and is_soled!='1' )";
}
//보유 계정 제외
if($posess_disable=='account'){	
	//$sql_search .=" and  not exists  (select code from {$g5['cn_item_cart']} where smb_id=a.ac_id $posess_sql and is_soled!='1' )";
}

//구매대기에서 매칭 제외 여부
$sql_search .=" and ac_mc_except!='1' ";

//제외될 아이디가 있는 경우
$except_id_str=preg_replace("/\n+/",",",$except_id);
$except_id_str=preg_replace("/\s+/","",$except_id_str);
$except_id_str=preg_replace("/,/","','",$except_id_str);
if($except_id_str!='' ) $sql_search .=" and a.mb_id not in ('$except_id_str') ";

$sql = " select distinct ac_id
{$sql_common} {$sql_search} group by a.ac_id";
$result = sql_query($sql,1);

//echo $sql."<br>";
$buy_cnt[$k]=sql_num_rows($result)*1;
$tot_buy_cnt+=$buy_cnt[$k];
?>
<td><strong>
<?=number_format2($buy_cnt[$k]+120)?>
</strong></td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<? }?>
<td><strong>
<?=number_format2($tot_buy_cnt)?>
</strong></td>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>
<tr>
<td scope="row">최대가능매칭</td>
<?php
foreach($cn_item_arr as $k=> $v) {  ?>
<td><strong>
<?=number_format2(($sell[$k][cnt]*1.0)/($buy_cnt[$k]+120)*100,0)?>
</strong>%</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<? }?>
<td><strong>
<?=number_format2($tot_cnt/$tot_buy_cnt*100,1)?>
</strong>%</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>


<tr style='border-top:2px solid #aaa;'>
<td width="130" scope="row">판매대기(+1 days)</td>

<?php
foreach($cn_item_arr as $k=> $v) {    
?>
<td><?=number_format2($sell2[$k][cnt]*1.0, 0)?></td>
<td><?=number_format2($sell2[$k][buy_price])?></td>
<td><?=number_format2($sell2[$k][sell_price])?></td>

<? }?>
<td><?=number_format2($tot_cnt2*1.0, 0)?></td>
<td><?=number_format2($tot_buy_price2)?></td>
<td><?=number_format2($tot_sell_price2)?></td>

</tr>


<tr>
<td width="130" scope="row">판매대기(+2 days)</td>

<?php
foreach($cn_item_arr as $k=> $v) {    
?>
<td><?=number_format2($sell3[$k][cnt]*1.0, 0)?></td>
<td><?=number_format2($sell3[$k][buy_price])?></td>
<td><?=number_format2($sell3[$k][sell_price])?></td>

<? }?>
<td><?=number_format2($tot_cnt*1.0, 0)?></td>
<td><?=number_format2($tot_buy_price3)?></td>
<td><?=number_format2($tot_sell_price3)?></td>

</tr>

<tr>
<td width="130" scope="row">판매대기(+3 days)</td>

<?php
foreach($cn_item_arr as $k=> $v) {    
?>
<td><?=number_format2($sell4[$k][cnt]*1.0, 0)?></td>
<td><?=number_format2($sell4[$k][buy_price])?></td>
<td><?=number_format2($sell4[$k][sell_price])?></td>

<? }?>
<td><?=number_format2($tot_cnt*1.0, 0)?></td>
<td><?=number_format2($tot_buy_price4)?></td>
<td><?=number_format2($tot_sell_price4)?></td>

</tr>

<tr>
<td width="130" scope="row">판매대기(+4 days)</td>

<?php
foreach($cn_item_arr as $k=> $v) {    
?>
<td><?=number_format2($sell5[$k][cnt]*1.0, 0)?></td>
<td><?=number_format2($sell5[$k][buy_price])?></td>
<td><?=number_format2($sell5[$k][sell_price])?></td>

<? }?>
<td><?=number_format2($tot_cnt*1.0, 0)?></td>
<td><?=number_format2($tot_buy_price5)?></td>
<td><?=number_format2($tot_sell_price5)?></td>

</tr>



</table>
 
 </div>



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
<td><input name="start_date" type="text"  class="frm_input " id="start_date" value="<?=date("Y-m-d")?>" size="20" /></td>
</tr>
<tr>
<th scope="row">지정코드</th>
<td><?=help('지정한 코드의 상품만을 매칭합니다.  1개/1라인 또는 콤마로 구분하여 입력하세요. 유효일 미경과 되거나 거래가 진행중인 상품은 매칭되지 않습니다')?>
<textarea name='target_code' class='frm_input' id="target_code"></textarea></td>
</tr>
<tr>
<th scope="row"><label for="in_set_amt2">단일회원</label></th>
<td>

<?=help('단일 회원 지정시 모든 수량이 단일회원에게 매칭됩니다.')?>
                <input type="text" name="target_mb_id" value="<?php echo get_text($mb['mb_id']) ?>" id="target_mb_id"  class=" frm_input" size="30" >
                <input type="button" value="회원검색" id="openMSearchBtn" class="btn btn_03" />
            
            
</td>
</tr>
<tr>
<th scope="row"><label for="in_set_amt2">지정일 미거래</label></th>
<td><input name="miss_date" type="text"  class="frm_input " id="miss_date" size="50" />
<br>
해당일자에 거래가 불발된 상품조건.
콤마로 구분하여 입력</td>
</tr>
<tr>
<th scope="row"><label for="in_set_amt2">연속 미거래</label></th>
<td>오늘을 포함한 이전거래에서 
<input name="miss_cnt" type="text"  class="frm_input " id="miss_cnt" value="<?=$miss_cnt?>" size="5"/>
회 이상 연속 미거래 상품만 매칭<br>
(0 이면 제한없음, 미거래건을 모두 취소로 변경후 실행)</td>
</tr>
<tr>
<th scope="row"><label for="in_set_amt2">매칭횟수제한</label></th>
<td><input name="match_cnt" type="text"  class="frm_input " id="match_cnt" value="<?=$match_cnt?>" size="5"/>
건 이내 /
1계정 이내로 제한 합니다</td>
</tr>
<tr>
<th scope="row"><label for="in_set_amt2">미시용자 제한</label></th>
<td><input name="login_day" type="text"  class="frm_input " id="login_day" value="<?=$login_day?>" size="5"/>
일 이내 미 로그인 사용자 제외 (0 이면 제한없음)</td>
</tr>
<tr>
<th scope="row"><label for="in_set_amt2">판매자수수료</label></th>
<td><label>
<input name="fee_free2" type="radio"  value="free" <?=$fee_free2=='free'?'checked':''?> >
수수료 없이 매칭 진행</label>
&nbsp;&nbsp;
<label>
<input name="fee_free2" type="radio"  value="pay" <?=$fee_free2=='pay'?'checked':''?>  >
<?=$g5['cn_item_name']?>
별 지정 수수료 부과</label></td>
</tr>
<tr>
<th scope="row"><label for="in_set_amt2">구매자수수료</label></th>
<td>
<label>
<input name="fee_free" type="radio"  value="free" <?=$fee_free=='free'?'checked':''?> >
수수료 없이 매칭 진행</label>&nbsp;&nbsp;

<label>
<input name="fee_free" type="radio"  value="pay" <?=$fee_free=='pay'?'checked':''?>  >
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
<tr>
<th scope="row">매칭 실행</th>
<td>

<?
$i=0;
$cn_item_arr=array_reverse($g5['cn_item']);
foreach($cn_item_arr as $k=> $v) {    
?>
<input type='hidden' name='item_code[<?=$i?>]'  id="item_code_<?=$k?>"  value='<?=$k?>'>
<input type='hidden' name='item_fee[<?=$k?>]'  id="item_fee_<?=$k?>"  value='<?=$v[fee]?>'>
<label>
<input name="item_exe_<?=$k?>" type="checkbox" id="item_exe_<?=$k?>" value="y" checked >
<?=$v[name_kr]?></label>&nbsp;&nbsp;
<? }?>
</td>
</tr>
<tr>
<th scope="row"><label for="in_set_amt2">분할매칭</label></th>
<td>
<?=help("실행시마다 불러올 구매대기 회원수. 과부하를 막기 위해 분할매칭을 진행합니다. '매칭횟수제한' 항목을 반드시 설정하세요. ")?>
<select name='buyer_cnt' id='buyer_cnt'>
<option value='300'>300명 단위</option>
<option value='500'>500명 단위</option>
<option value='700' selected >700명 단위</option>
<option value='900'>900명 단위</option>
<option value='1000'>1000명 단위</option>
<option value='1200'>1200명 단위</option>
</select>
</tr>
</table>
 </div>


    
</section>

<div class="text-center">
<input type="submit" name="act_button" value="미리보기" onclick="document.fcommonform.w.value='p';document.pressed=this.value;" class="btn_prev btn btn_02" accesskey="s">
<input type="submit" name="act_button" value="매칭실행" onclick="document.fcommonform.w.value='x';document.pressed=this.value; "class="btn_submi btn btn_01" accesskey="s">
</div>

</form>


<div id='result_window' style='width:100%;height:700px;padding:10px;overflow-y:auto;background:#efefef;border:1px solid;#787878;margin-top:10px;'>

</div>

<?
include "./member_search_modal.php";
?>

<script>  
$(document).ready(function(e) {
	$('#openMSearchBtn').click(function(){
	
		$('#myModalLabel').html('회원 검색')
		$("input[name='target_mb_id']").val('');
		
		search_member_open('target_mb_id');
	});
	
});

function member_select(tg,datas){
	var ov=$('#'+tg).val();
	//$('#'+tg).val(ov+(ov?',':'')+datas.mb_id);
	$('#'+tg).val(datas.mb_id);
	
	$('#member_search').hide();
}	
function fcommonform_ajax(f)
{
		 
	event.preventDefault();	
	
	var current_item;
	var qty=0;
	
	$("input[name^=item_code]").each(function(){
		var k=$(this).val();
		if($("input[name=item_exe_"+k+"]").is(":checked")==true )  qty++;
	
	});
	
	if (qty < 1) {
	  alert("진행할 <?=$g['cn_item_name']?>을 선택하세요.");          
	  return false;
	}
	
	if(document.pressed == "매칭실행") {
       if(!confirm("매칭을 진행하시겠습니까?")) {
           return false;
        }
    }	
	$('#result_window').html('');
	alert_loading2('매칭진행중','open');
		
	var rtn=true;
	
	$("input[name^=item_code]").each(function(){	
		
		var k=$(this).val();
		
		if($("input[name=item_exe_"+k+"]").is(":checked")==false )	return;
		
		$("input[name=current_item]").val(k);		
		
		var formData = $(f).serialize();	
		
		$.ajax({
			type: "POST",
			url: "./item_matching.p2p.update.php",
			data:formData,
			cache: false,
			async: false,
			dataType:"json",
			success: function(data) {

				if(data.result==true){					
					$('#result_window').html($('#result_window').html()+data.datas['htmls']);
					//console.log(data.datas['htmls']);
				}
				else{
					alert(data.message);      
					rtn=false;
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
