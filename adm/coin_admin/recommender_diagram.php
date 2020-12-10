<?php
$sub_menu = "800310";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');

$step_stx*=1;

$_tree_db=$g5['cn_tree'];
$_tree_fd='mb_tree';
$_in_sql=" and pkind in ('fee') ";

if(!$step_stx || $step_stx < 1) $step_stx=10;

if($mb_id_stx)	$mbs=get_member($mb_id_stx);	
$step_count = $step_stx;
function prnt_rows($result,$list_num){
	
	global $g5,$sql_common,$sql_search,$sql_order,$member, $_tree_db,$_tree_fd,$_in_sql,$mbs,$step_count; 
	
	$str='';
    for ($i=0; $row=sql_fetch_array($result); $i++) {
		
       
        $mb = get_member($row['smb_id']);	
		//제한이 넘은경우		
		$mbtree=explode(",",$mb[mb_tree]);
		$keys=array_search($mbs[mb_id],$mbtree);
		$steps = count($mbtree) - $keys;

		$price_label = "";
		if($steps<=3){
			$price_query = "select sum(b.ct_buy_price) as price from coin_tree as a left join coin_item_cart as b on a.smb_id = b.mb_id where a.mb_id = '{$mb['mb_id']}' and b.is_soled!='1' group by b.cn_item";
			$price_result = sql_fetch($price_query);
			$isum = get_itemsum($mb['mb_id']);
			$price_label = $price_result['price']+$isum['tot']['price'];

			$rpoint=get_mempoint($mb['mb_id']);
			$flower_query = "select sum(b.amount) as price from coin_tree as a left join coin_pointsum as b on a.smb_id = b.mb_id where a.mb_id = '{$mb['mb_id']}' and b.pt_coin = 'i'";
			$flower_result = sql_fetch($flower_query);
			$flower_label = $flower_result['price']+$rpoint['i']['_enable'];
			
			$price_label = "(".number_format($price_label+$flower_label).")";
		}		
		$str.='
		<li>
		<div><i class="fa fa-user-circle fa-2x btn-reload-tree" data-id="'.$mb['mb_id'].'" ></i></div>
        <p class="btn-user-info" data-id="'.$mb['mb_id'].'" >'.$mb['mb_id'].$price_label.'</p>
		';
		
		if($steps >= $list_num) continue;
       
		$sql = " select a.*,b.mb_id bmb_id,b.mb_name,b.mb_tree {$sql_common} where a.mb_id='{$row['smb_id']}' and a.step='1'  {$sql_order} ";
		
		//echo $sql.'<br>';
		$_result = sql_query($sql,1);
		
		if(sql_num_rows($_result)){			
			
			$str2=prnt_rows($_result,$list_num);			
			if($str2) $str.='<ul>'.$str2.'</ul>';

		}
		$str.='</li>';
		
	
    }	
	$step_count--;
	return $str;
}

if($mbs[mb_id]){
	$sql_common = " from $_tree_db as a left outer join {$g5['member_table']} as b on(a.smb_id=b.mb_id) ";
	$sql_search = " where a.mb_id = '{$mbs['mb_id']}' ";


	$sql_order = " order by a.step ,a.no desc";

	$sql = " select a.*,b.mb_id bmb_id,b.mb_name,b.mb_tree {$sql_common} {$sql_search}  and a.step='1'  {$sql_order} ";
	$result = sql_query($sql,1);



	$g5['title'] = "추천인계보다이어그램".($mb['mb_name']?" - ".$mb['mb_name']:"");
}

add_javascript('<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>');
include_once(G5_ADMIN_PATH.'/admin.head.php');

?>


<div class="local_ov01 local_ov">
    <?php echo $listall ?>
    <span class="btn_ov01"><span class="ov_txt">총 </span><span class="ov_num"> <?php echo number_format($total_count) ?>명 </span></span>
    <a href="?sst=mb_intercept_date&amp;sod=desc&amp;sfl=<?php echo $sfl ?>&amp;stx=<?php echo $stx ?>" class="btn_ov01"> <span class="ov_txt">차단 </span><span class="ov_num"><?php echo number_format($intercept_count) ?>명</span></a>
    <a href="?sst=mb_leave_date&amp;sod=desc&amp;sfl=<?php echo $sfl ?>&amp;stx=<?php echo $stx ?>" class="btn_ov01"> <span class="ov_txt">탈퇴  </span><span class="ov_num"><?php echo number_format($leave_count) ?>명</span></a>
</div>

<form id="fsearch" name="fsearch" class="local_sch01 local_sch" method="get">

<label for="sfl" class="txt_fail" > 회원 아이디</label>
<label for="step_stx" class="sound_only"><strong class="sound_only"> 필수</strong></label>
<input type="text" name="mb_id_stx" value="<?php echo $mb_id_stx ?>" id="mb_id_stx" required class="required frm_input">
<label for="step_stx" class="step_stx" > 출력단계</label>
<input name="step_stx" type="text" required="required" class="frm_input" id="step_stx" value="<?php echo $step_stx?>" size="10" placeholder='출력단계' />
<input type="submit" class="btn_submit" value="검색">

</form>
<style>

.layerpop {
    display: none;
    z-index: 1000;
    border: 2px solid #ccc;
    background: #fff;
    cursor: move; 
	font-size:14px;
}


.layerpop_area .layerpop_close {
    width: 25px;
    height: 25px;
    display: block;
    position: absolute;
    top: 10px;
    right: 10px;
	color: #242424;
   }

.layerpop_area .layerpop_close:hover {    
	color: #ff0000;
   }
.layerpop_area .layerpop_data {
    width: 96%;    
    margin: 2%;
    color: #424242; 
	line-height:150%;
}

.layerpop_area .layerpop_data p span{
	font-weight:600;
    color: #ff4242; 
}

/* 다이어그램 */

.tree ul {
  position: relative;
  padding: 1em 0;
  white-space: nowrap;
  margin: 0 auto;
  text-align: center;
  cursor:move;
}
.tree ul::after {
  content: '';
  display: table;
  clear: both;
}

.tree li {
  display: inline-block;
  vertical-align: top;
  text-align: center;
  list-style-type: none;
  position: relative;
  padding: 1em .1em 0 .1em;
  margin:0 -2px;
}
.tree li::before, .tree li::after {
  content: '';
  position: absolute;
  top: 0;
  right: 50%;
  border-top: 1px solid #dadada;
  width: 50%;
  height: 1em;
}
.tree li::after {
  right: auto;
  left: 50%;
  border-left: 1px solid #dadada;
}

.tree li div{
	position:relative;
	width:100%;
	display:block;
	margin:0.1rem;	
}

.tree li i{
	display:inline-block;
	margin:0.1rem;
	cursor:pointer;;
}
.tree li span.dates{
	font-size:0.9em
}
.tree li span.point{
	font-size:0.9em;
	color:#ff0000;
}

.tree li:only-child::after, .tree li:only-child::before {
  display: none;
}
.tree li:only-child {
  padding-top: 0;
}
.tree li:first-child::before, .tree li:last-child::after {
  border: 0 none;
}
.tree li:last-child::before {
  border-right: 1px solid #dadada;
  border-radius: 0 5px 0 0;
}
.tree li:first-child::after {
  border-radius: 5px 0 0 0;
}

.tree ul ul::before {
  content: '';
  position: absolute;
  top: 0;
  left: 50%;
  border-left: 1px solid #dadada;
  width: 0;
  height: 1em;
}

.tree li p {
  background:#ffffff;	
  border: 1px solid #dadada;
  padding: .5em .75em;
  text-decoration: none;
  display: inline-block;
  border-radius: 5px;
  color: #242424;
  position: relative;
  top: 1px;
  line-height:100%;
   cursor:pointer;
}

.tree li p.now-member {
	background:#2F7EAE;
	color: #fff;
	border: 1px solid #4470A6;
}
.tree li p.now-member span.ids{
	color:#efefef;	
}
.tree li p.now-member span.point{
	color:#ffefef;	
}


.tree li p:hover,
.tree li p:hover + ul li p {
  background: #e9453f;
  color: #fff;
  border: 1px solid #e9453f;
}

.tree li p:hover + ul li::after,
.tree li p:hover + ul li::before,
.tree li p:hover + ul::before,
.tree li p:hover + ul ul::before {
  border-color: #e9453f;
}
</style>

<div class="tbl_head01 tbl_wrap" style='height:100%;width:100%;overflow:hidden;background:#f7f7f7;min-width:800px;;min-height:500px;'>

<?
if($mbs[mb_id]){
$pprice_query = "select sum(b.ct_buy_price) as price from coin_tree as a left join coin_item_cart as b on a.smb_id = b.mb_id where a.mb_id = '{$mbs['mb_id']}' and b.is_soled!='1' group by b.cn_item";
$pprice_result = sql_fetch($pprice_query);
$pisum = get_itemsum($mbs['mb_id']);
$pprice_label = $pprice_result['price']+$pisum['tot']['price'];

$prpoint=get_mempoint($mbs['mb_id']);
$pflower_query = "select sum(b.amount) as price from coin_tree as a left join coin_pointsum as b on a.smb_id = b.mb_id where a.mb_id = '{$mbs['mb_id']}' and b.pt_coin = 'i'";
$pflower_result = sql_fetch($pflower_query);
$pflower_label = $pflower_result['price']+$prpoint['i']['_enable'];

$pprice_label = "(".number_format($pprice_label+$pflower_label).")";	
?>
<div class="tree">
	<ul>    	
    <li>
	<div><i class="fa fa-user-circle fa-2x btn-reload-tree" data-id='<?=$mbs['mb_id']?>' ></i></div>
	<p class='btn-user-info' data-id='<?=$mbs['mb_id']?>' ><?=$mbs['mb_id'].$pprice_label?></p>
<?

if(sql_num_rows($result) > 0){ ?>    
    <ul>
<?
//print_r($upper);
echo prnt_rows($result,$step_stx);
?>
      </ul>
<? }
?>          
  </li>
</ul>
</div>
<? }?>
    
</div>




<!--Popup Start -->
<div id="layerbox" class="layerpop"
	style="width: 350px; height: 200px;">
	<article class="layerpop_area">	
	<a href="javascript:popupClose();" class="layerpop_close"
		id="layerbox_close"><i class="fa fa-times fa-2x"></i></a> <br>
		<div class="layerpop_data mx-auto p-2">

		</div>
	</article>
</div>
<!--Popup End -->


<script>

$( ".tree > ul" ).draggable();


function popupOpen(htmls) {
	$('.layerpop').css("position", "absolute");
	$('.layerpop').css("top",(($(window).height() - $('.layerpop').outerHeight()) / 2) + $(window).scrollTop());
	$('.layerpop').css("left",(($(window).width() - $('.layerpop').outerWidth()) / 2) + $(window).scrollLeft());
	$('#layerbox .layerpop_data').html(htmls);
	$('#layerbox').show();
}

function popupClose() {
	$('#layerbox').hide();
}


var scale=1;
function scale_update(amt){
	scale+=amt;
	$('.tree>ul').css('transform', 'scale('+scale.toPrecision()+')');
}
function scale_reset(){
	scale=1;
	$('.tree>ul').css('transform', 'scale(1)');
}

$(document).ready(function(){
	$(".tree").bind('wheel mousewheel', function(e){
	
	event.preventDefault();
	var delta;

	if (e.originalEvent.wheelDelta !== undefined)
		delta = e.originalEvent.wheelDelta;
	else
		delta = e.originalEvent.deltaY * -1;

		if(delta > 0) {
			scale_update(0.07);
		}
		else{
			scale_update(-0.07);
		}
	});
		
	
	$('#btnScaleUp').on('click',function(){
		scale_update(0.02)
	});
	$('#btnScaleDown').on('click',function(){
		scale_update(-0.02)
	});
	$('#btnScaleNone').on('click',function(){
		scale_reset()
	});
	
	
	$(".btn-reload-tree").click(function(){
		var mb_id_val=$(this).attr('data-id');
		document.location.href='<?=$_SERVER[SCRIPT_NAME]?>?mb_id_stx='+mb_id_val;
	});
	
	//var formData = $(f).serialize();				
	$(".btn-user-info").click(function(){
		
		var mb_id_val=$(this).attr('data-id');
		
		$.ajax({
			type: "POST",
			url: "<?=G5_URL?>/for_common/ajax.user.info.php",
			data:{mb_id:mb_id_val},
			cache: false,
			async: false,
			dataType:"json",
			success: function(data) {
				
				var htmls='';
				if(data.result==true){	
					
						htmls+='<p>Member ID : <span>'+data.datas['mb_id']+'</span></p>'
						+'<p>Join Date : <span>'+data.datas['mb_datetime']+'</span></p>'
						+'<p>Total Balance amount : <span>'+data.datas['tot_btc']+'</span></p>'
						+'<p>Staking amount : <span>'+data.datas['stake_btc']+'</span></p>'
						+'<p>Daliy Mining : <span>'+data.datas['mine_btc']+'</span></p>'
	
						popupOpen(htmls);

				}
				else{
					alert(data.message);   
				}
			}
		});		
	});


		
});
</script>

<?php
include_once(G5_ADMIN_PATH.'/admin.tail.php');
?>
