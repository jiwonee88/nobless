<?php
$sub_menu = "800400";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'w');

$html_title = '이전내역';


if ($w == '') {

    $html_title .= ' 등록';

    $required = 'required';
    $required_valid = 'alnum_';
    $sound_only = '<strong class="sound_only">필수</strong>';
	
	$data['pt_kind'] = 'etc';
    $data['rt_order'] = 0;
	$data['rt_open'] = 1;
    $data['rt_enable'] = 202;
	
	$data['deposit_date']=date("Y-m-d");

} else if ($w == 'u') {

    $html_title .= ' 수정';
	
    $data= get_pointtrans($pt_no);

    if (!$data['pt_no'])
        alert('존재하지 않은 내역 입니다.');

    $readonly = 'readonly';
	
}


$g5['title'] = $html_title;
include_once(G5_ADMIN_PATH.'/admin.head.php');


$qstr.="&mb_stx=$mb_stx&coin_stx=$coin_stx";

?>
<form name="fparticipationform" id="fparticipationform" action="./point_form_update.php" onsubmit="return fparticipationform_submit(this)" method="post" enctype="multipart/form-data">
<input type="hidden" name="w" value="<?php echo $w ?>">
<input type="hidden" name="pt_no" value="<?php echo $pt_no ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
<input type="hidden" name="stx" value="<?php echo $stx ?>">
<input type="hidden" name="sst" value="<?php echo $sst ?>">
<input type="hidden" name="sod" value="<?php echo $sod ?>">
<input type="hidden" name="page" value="<?php echo $page ?>">
<input type="hidden" name="mb_stx" value="<?php echo $mb_stx ?>">
<input type="hidden" name="mb_point" value='0'>
<input type="hidden" name="smb_point" value='0'>


<input type="hidden" name="token" value="">

<section id="anc_rt_basic">

    <div class="tbl_frm01 tbl_wrap">
        <table>
          <tr>
            <th scope="row"><label for="amount">구분</label></th>
            <td>
            <label for="pt_kind1"><input type="radio" name="pt_kind" id="pt_kind1" value="etc" <?=$data['pt_kind']=='etc' || !$data ? 'checked':''?> />
              기타수당으로 </label>
                &nbsp;&nbsp;<label for="pt_kind2">
                  <input type="radio" name="pt_kind" id="pt_kind2" value="ptc" <?=$data['pt_kind']=='ptc' ? 'checked':''?>  />
구매수량으로</label></td>
          </tr>
          <tr>
            <th scope="row"><label for="smb_id">지급아이디</label></th>
            <td><?
		if(!$data['pt_no']){?>
              <input type="text" name="smb_id" value="<?php echo get_text($data['smb_id']) ?>" id="reg_smb_exist" required="required" class="required frm_input" size="30" />              
              <input type="button" value="회원검색" id="openMSearchBtn1" class="btn btn_03">
              <? }else{
				
				$smb=get_member($data['smb_id']);
				echo $smb['mb_name']." [{$data['smb_id']}]";
				
				?>
              <? }?>
              <span id='smb_point_str'><?
              if($data['smb_id']){
				  $points=get_mempoint($data['smb_id']);
				  echo "현재보유 : ". number_format($points['tot_earn'],2);
			  }?></span>
              </td>
          </tr>
          
        <caption>내역 추가</caption>
        <colgroup>
            <col class="grid_4">
            <col>
            <col class="grid_3">
        </colgroup>
        <tbody>
        <tr>
            <th scope="row"><label for="smb_id">수급아이디</label></th>
            <td>
            <?
		if(!$data['pt_no']){?>
                <input type="text" name="mb_id" value="<?php echo get_text($data['mb_id']) ?>" id="reg_mb_exist" required class="required frm_input" size="30">
                <input type="button" value="회원검색" id="openMSearchBtn2" class="btn btn_03">
            <? }else{
				
				$mb=get_member($data['mb_id']);
				echo $mb['mb_name']." [{$data['mb_id']}]";
				
				?>            
            <? }?>
            <span id='mb_point_str'><?
              if($data['mb_id']){
				  $point=get_mempoint($data['mb_id']);
				  echo "현재보유 : ". number_format($point['tot_earn'],2);
			  }?></span>
            </td>
        </tr>
        <tr class='for-ptc'>
          <th scope="row"><label for="account">입금주소</label></th>
          <td><input type="text" name="account" value="<?php echo $data['account'] ?>" id="account"  class="frm_input" size="50" /></td>
        </tr>
        <tr class='for-ptc'>
          <th scope="row"><label for="rt_name">지불수단</label></th>
          <td><input type="text" name="coin" value="<?php echo $data['coin'] ?>" id="coin" class="frm_input" size="25" />
            <select id="coin_sel" name="coin_sel" class='data-sync' data-link='coin'>
              <option value=''>-선택-</option>
              <?
			  
          	$coin_arr=get_fielddata($g5['cn_pp_table'],'coin');
			foreach($coin_arr as $v) echo "<option value='{$v}' ".($data['coin']==$v ? 'selected':'').">$v</option>";
			?>
            </select></td>
        </tr>
        
        
          <tr>
            <th scope="row"><label for="amount">이전<?=$g5['cn_point_name']?></label></th>
            <td>
            <input name="amount" type="text"  class="required frm_input number-comma" id="amount" required=required value="<?php echo $data['amount'] ? number_format($data['amount'],2):'0'?>" size="20" /></td>
          </tr>
        <tr>
          <th scope="row"><label for="subject">이전일자</label></th>
          <td><input type="text" name="deposit_date" value="<?php echo substr($data['deposit_date'],0,10) ?>" id="deposit_date" required=required  class="required frm_input calendar-input" size="25" /></td>
        </tr>
        <tr>
          <th scope="row"><label for="subject">설명</label></th>
          <td><input type="text" name="subject" value="<?php echo $data['subject'] ?>" id="subject"  class="frm_input" size="60" /></td>
        </tr>
        
       
        <tr>
          <th scope="row">비고</th>
          <td><textarea name="comment" cols="50" rows="4"  class="frm_input" id="comment"><?php echo get_text($data['comment']) ?></textarea></td>
        </tr>
        <?
		if($data['pt_no']){?>
        <tr>
          <th scope="row">등록정보</th>
          <td><?=$data['wdate']?></td>
        </tr>
        <? }?>
        </tbody>
        </table>
    </div>
</section>




<div class="btn_fixed_top">
	<a href="./point_list.php?<?=$qstr?>"  class=" btn_02 btn">전체목록</a>
    <input type="submit" value="확인" class="btn_submi btn btn_01" accesskey="s">
</div>

</form>

<!-- Modal -->
<style>
.modal-body{position:absolute; display:none;width:500px;border:1px solid #ededed;background:#ffffff;}
.modal-header{padding:10px 10px;}
.modal-header .close{float:right;border:0;width:25px;height:25px;}
.modal-contents{padding:10px 10px;}
.modal-body .result-data{min-height:150px;max-height:300px;overflow-y:auto;}
.modal-body .result-data ul{list-style:none;margin:0;padding:0;}
.modal-body .result-data li{padding:5px 5px;float:left;cursor:pointer;}
.modal-body .result-data li:hover{background:#efefef;}
</style>
 <form name="fsearchid" id="fsearchid"  class="local_sch01 local_sch" onsubmit="return search_member_ajax();">
 <input type='hidden' name='tg'  value='' />
    <div id='member_search' class="modal-body">
        <div class="modal-header">
            <button type="button" class="close "  >&times;</button>
            <h4 class="modal-title" id="myModalLabel">멤버 찾기</h4>
        </div>
        <div class="modal-contents">
                <div class="form-group has-success">
                        <div class="input-group">
                           <select name="sfl" id="sfl">
    <option value="mb_name"<?php echo get_selected($_GET['sfl'], "mb_name"); ?>>이름</option>                           
    <option value="mb_id"<?php echo get_selected($_GET['sfl'], "mb_id"); ?>>회원아이디</option>
    <!--option value="mb_nick"<?php echo get_selected($_GET['sfl'], "mb_nick"); ?>>닉네임</option-->
    <!--option value="mb_level"<?php echo get_selected($_GET['sfl'], "mb_level"); ?>>권한</option-->
    <option value="mb_email"<?php echo get_selected($_GET['sfl'], "mb_email"); ?>>E-MAIL</option>
    <option value="mb_tel"<?php echo get_selected($_GET['sfl'], "mb_tel"); ?>>전화번호</option>
    <option value="mb_hp"<?php echo get_selected($_GET['sfl'], "mb_hp"); ?>>휴대폰번호</option>
    <option value="mb_point"<?php echo get_selected($_GET['sfl'], "mb_point"); ?>>포인트</option>
    <option value="mb_datetime"<?php echo get_selected($_GET['sfl'], "mb_datetime"); ?>>가입일시</option>
    <option value="mb_ip"<?php echo get_selected($_GET['sfl'], "mb_ip"); ?>>IP</option>
    <option value="mb_recommend"<?php echo get_selected($_GET['sfl'], "mb_recommend"); ?>>추천인</option>
</select>
                            <input name='stx' id="btn-input-name" type="text"  placeholder="검색어" class="required frm_input">
                            <input type="submit" class="btn_submit" value="검색">
                        </div>
                </div>
                <div class="result-data">
                   
                </div>
               
            </div>
         </div>
    </div>
 </form>

<script>  
$(function(){
	$("input[name='pt_kind']").click(function(){
		toggle_ptc();
	});
	toggle_ptc();
	$('#openMSearchBtn1').click(function(){
		$("input[name='tg']",'#fsearchid').val('reg_smb_exist');
		
		var sWidth = window.innerWidth;
		var sHeight = window.innerHeight;

		var oWidth = $('#member_search').width();
		var oHeight = $('#member_search').height();
		
		
		// 레이어가 나타날 위치를 셋팅한다.
		var divLeft = event.clientX + 10 - Math.floor(oWidth/2);
		var divTop = event.clientY + 5;
		
		// 레이어가 화면 크기를 벗어나면 위치를 바꾸어 배치한다.
		if( divLeft + oWidth > sWidth ) divLeft -= oWidth;
		if( divTop + oHeight > sHeight ) divTop -= oHeight;

		// 레이어 위치를 바꾸었더니 상단기준점(0,0) 밖으로 벗어난다면 상단기준점(0,0)에 배치하자.
		if( divLeft < 0 ) divLeft = 0;
		if( divTop < 0 ) divTop = 0;

		$('#member_search').css({
			"top": divTop,
			"left": divLeft
		}).show();
		
	});

	$('#openMSearchBtn2').click(function(){
		$("input[name='tg']",'#fsearchid').val('reg_mb_exist');
		
		var sWidth = window.innerWidth;
		var sHeight = window.innerHeight;

		var oWidth = $('#member_search').width();
		var oHeight = $('#member_search').height();
		
		
		// 레이어가 나타날 위치를 셋팅한다.
		var divLeft = event.clientX + 10 - Math.floor(oWidth/2);
		var divTop = event.clientY + 5;
		
		// 레이어가 화면 크기를 벗어나면 위치를 바꾸어 배치한다.
		if( divLeft + oWidth > sWidth ) divLeft -= oWidth;
		if( divTop + oHeight > sHeight ) divTop -= oHeight;

		// 레이어 위치를 바꾸었더니 상단기준점(0,0) 밖으로 벗어난다면 상단기준점(0,0)에 배치하자.
		if( divLeft < 0 ) divLeft = 0;
		if( divTop < 0 ) divTop = 0;

		$('#member_search').css({
			"top": divTop,
			"left": divLeft
		}).show();
		
	});

	$('#member_search .close').on('click',function(){
		$('#member_search').hide()	
	});

	$("input[name='mb_id']").on('blur',function(){
		if($(this).val()==''){
			$("#mb_point_str").html("");
			$("input[name='mb_point']").val(0);
			return ;			
		}
		var point= mb_point_check($(this).val());
		if(point!='null'){
			$("#mb_point_str").html("현재보유 : "+inputNumberFormat(point));
			$("input[name='mb_point']").val(point);
		}else  $("#mb_point_str").html("회원을 찾을수 없습니다");
			
	});
	
	$("input[name='smb_id']").on('blur',function(){
		if($(this).val()==''){
			$("#smb_point_str").html("");
			$("input[name='mb_point']").val(0);
			return ;			
		}
		
		var point= mb_point_check($(this).val());
		if(point!='null'){
			$("#smb_point_str").html("현재보유:"+inputNumberFormat(point));
			$("input[name='smb_point']").val(point);
		}else  $("#smb_point_str").html("회원을 찾을수 없습니다");
	});
});

function toggle_ptc(){
	var pkind=$("input[name='pt_kind']:checked").val();	
	if(pkind=='ptc') $('.for-ptc').show();
	else $('.for-ptc').hide();
}

function search_member_ajax(){
	var allData = $('#fsearchid').serialize();
	
	 $.ajax({
		type: "POST",
		url: g5_admin_url+"/member_search_ajax.php", dataType:'json',
		contentType: "application/x-www-form-urlencoded; charset=UTF-8",  
		data: allData,
		success: function(data)
		{
	
			if(data.msg == "OK"){	
				
				var htm='';
				for(lis in data.list){
					datas=data.list[lis];
					htm+=
					"<li class='member-search-result' data-member='"+datas.mb_id+"'  data-point='"+datas.mb_epoint+"'>"+datas.mb_name+" ["+datas.mb_id+", 보유: "+datas.mb_epoint2+"]";	
					console.log(lis);
				}
				if(htm) htm="<ul>"+htm+"</ul>";
				else  htm="검색결과 없습니다";
				$('.result-data').html(htm);
				
				var tg=$("input[name='tg']",'#fsearchid').val();
				
				$('.member-search-result').on('click',function(){
					$('#'+tg).val($(this).attr('data-member'));
					
					if(tg=='reg_mb_exist'){
						$("#mb_point_str").html("현재보유 : "+inputNumberFormat($(this).attr('data-point')));
						$("input[name='mb_point']").val($(this).attr('data-point'));
					}else{
						$("#smb_point_str").html("현재보유 : "+inputNumberFormat($(this).attr('data-point')));
						$("input[name='smb_point']").val($(this).attr('data-point'));

					}
			
			
				});			
			
			} else {
				//alert("찾는 멤버가 없습니다.");
			}
						
		},
		complete: function(data){
		
		}
	});
	
	return false;
}

//회원검사
var reg_mb_exist_check = function(mb_id) {
    var result = "";
    $.ajax({
        type: "POST",
        url: g5_bbs_url+"/ajax.mb_exist.php",
        data: {
            "reg_mb_exist": encodeURIComponent(mb_id)
        },
        cache: false,
        async: false,
        success: function(data) {
            result = data;
        }
    });
    return result;
}

//수당 검사
var mb_point_check = function(mb_id) {
    var result = "";
    $.ajax({
        type: "POST",
        url: g5_url+"/adm/stable_admin/ajax.mb_point.php",
        data: {
            "mb_id": encodeURIComponent(mb_id)
        },
        cache: false,
        async: false,
        success: function(data) {
            result = data;
        }
    });
    return result;
}


function fparticipationform_submit(f)
{
	
	// 수급회원아이디 검사	
	if (f.w.value == "") {
		var msg = reg_mb_exist_check(f.mb_id.value);
		if (msg) {
			alert(msg);
			f.mb_id.select();
			return false;
		}
		
		var msg = reg_mb_exist_check(f.smb_id.value);
		if (msg) {
			alert(msg);
			f.smb_id.select();
			return false;
		}
		
		var smb_point=$("input[name='smb_point']").val();
		
		if(parseFloat(smb_point) < parseFloat(amount)){
			alert('이전<?=$g5['cn_poiint_name']?>이 부족합니다');
			return false;	
		}
		
		
	}
    return true;
}
</script>

<?php
include_once(G5_ADMIN_PATH.'/admin.tail.php');
?>
