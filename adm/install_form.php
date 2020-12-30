<?php
$sub_menu = "800100";
include_once('./_common.php');
include_once(G5_EDITOR_LIB);

auth_check($auth[$sub_menu], 'w');

$html_title = '채용';

$required = "";
$readonly = "";

if ($w == '') {

    $html_title .= ' 등록';

    $required = 'required';
    $required_valid = 'alnum_';
    $sound_only = '<strong class="sound_only">필수</strong>';

    $data['rt_order'] = 0;
	$data['rt_open'] = 1;
    $data['rt_enable'] = 202;

} else if ($w == 'u') {

    $html_title .= ' 수정';
	
    $data= get_recruit($rt_no);

    if (!$data['rt_no'])
        alert('존재하지 않은 채용 입니다.');

    $readonly = 'readonly';
	
	
	$data['rt_partner_ext']=preg_replace("/^,|,$/","",$data['rt_partner_ext']);
	
	
}


$g5['title'] = $html_title;
include_once ('../admin.head.php');

$pg_anchor = '<ul class="anchor">
    <li><a href="#anc_rt_basic">기본 설정</a></li>
    <li><a href="#anc_rt_function">기능 설정</a></li>
    <li><a href="#anc_rt_design">채용소개</a></li>
	<li><a href="#anc_rt_app">신청정보</a></li>
</ul>';



$qstr.="&partner_stx=$partner_stx&cate1_stx=$cate1_stx";

?>
<form name="frecruitform" id="frecruitform" action="./recruit_form_update.php" onsubmit="return frecruitform_submit(this)" method="post" enctype="multipart/form-data">
<input type="hidden" name="w" value="<?php echo $w ?>">
<input type="hidden" name="rt_no" value="<?php echo $rt_no ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
<input type="hidden" name="stx" value="<?php echo $stx ?>">
<input type="hidden" name="sst" value="<?php echo $sst ?>">
<input type="hidden" name="sod" value="<?php echo $sod ?>">
<input type="hidden" name="page" value="<?php echo $page ?>">
<input type="hidden" name="partner_stx" value="<?php echo $partner_stx ?>">
<input type="hidden" name="cate1_stx" value="<?php echo $cate1_stx ?>">

<input type="hidden" name="token" value="">

<section id="anc_rt_basic">
    <h2 class="h2_frm">채용 기본 설정</h2>
    <?php echo $pg_anchor ?>

    <div class="tbl_frm01 tbl_wrap">
        <table>
          
        <caption>채용 기본 설정</caption>
        <colgroup>
            <col class="grid_4">
            <col>
            <col class="grid_3">
        </colgroup>
        <tbody>
        <tr>
            <th scope="row"><label for="rt_charge">공고번호</label></th>
            <td>
                <input type="text" name="rt_code" value="<?php echo get_text($data['rt_code']) ?>" id="rt_code" required class="required frm_input" size="40">
            </td>
        </tr>
        <tr>
          <th scope="row"><label for="rt_charge_tel">공고제목</label></th>
          <td><input type="text" name="rt_name" value="<?php echo get_text($data['rt_name']) ?>" id="rt_name" required="required" class="required frm_input" size="80" /></td>
        </tr>
        <tr>
          <th scope="row"><label for="rt_charge_tel">공고간략안내</label></th>
          <td>
          <?php echo help('채용공고 상세화면에서 제목 아래 출력 되는 부제') ?>
          <input type="text" name="rt_prev" value="<?php echo get_text($data['rt_prev']) ?>" id="rt_name" class="frm_input" size="80" /></td>
        </tr>
        <tr>
          <th scope="row"><label for="rt_url">상세 URL</label></th>
          <td><input type="text" name="rt_url" value="<?php echo get_text($data['rt_url']) ?>" id="rt_url"  class="frm_input" size="80" /></td>
        </tr>
        
        
          <tr>
            <th scope="row"><label for="rt_stats">진행상태</label></th>
            <td><input type="text" name="rt_stats" value="<?php echo $data['rt_stats'] ?>" id="rt_stats" required="required" class="required frm_input" size="25" />
              <select id="rt_stats_sel" name="rt_stats_sel" class='data-sync' data-link='rt_stats'>
              <option value=''>-선택-</option>
              <?
			  
          	$stats_arr=get_fielddata($g5['recruit_table'],'rt_stats');
			foreach($stats_arr as $v) echo "<option value='{$v}' ".($data['rt_stats']==$v ? 'selected':'').">$v</option>";
			?>
            </select>
           <?
			if($data['rt_no']) echo "최종변경 : {$data['rt_cdate']} ";?>
            </td>
          </tr>
          <tr>
            <th scope="row"><label for="rt_sdate">시작일</label></th>
            <td><input type="text" name="rt_sdate" value="<?php echo $data['rt_sdate'] ?>" id="rt_sdate" required="required" class="required frm_input calendar-input" size="25" /></td>
          </tr>
          <tr>
            <th scope="row"><label for="rt_partner">마감일</label></th>
            <td><input type="text" name="rt_edate" value="<?php echo $data['rt_edate'] ?>" id="rt_edate" required="required" class="required frm_input calendar-input" size="25" /></td>
          </tr>
          <tr>
            <th scope="row"><label for="rt_partner">기업회원</label></th>
            <td>
            <?php echo help('해당 채용공고를 등록 또는 관리할 마스터 기업회원') ?>
            
            <input type="text" name="rt_partner" value="<?php echo $data['rt_partner'] ?>" id="rt_partner" required="required" class="required frm_input " size="40" />
            <input type="button" value="회원검색" id="openMSearchBtn" class="btn btn_03">
            
            </td>
          </tr>
          <tr>
            <th scope="row"><label for="rt_partner">관리회원추가</label></th>
            <td>
            <?php echo help('해당 채용공고 관리 권한을 부여할 회원') ?>
            
            <input type="text" name="rt_partner_ext" value="<?php echo $data['rt_partner_ext'] ?>" id="rt_partner_ext" required="required" class="required frm_input " size="40" />
            <input type="button" value="회원검색" id="openMSearchBtn2" class="btn btn_03">
            
            </td>
          </tr>
          
        <tr>
          <th scope="row"><label for="rt_maxapp">업체명</label></th>
          <td><input type="text" name="rt_company" value="<?php echo get_text($data['rt_company']) ?>" id="rt_company" required="required" class="required frm_input" size="50" /></td>
        </tr>
        <tr>
          <th scope="row"><label for="rt_maxapp">채용인원</label></th>
          <td><input type="text" name="rt_maxapp" value="<?php echo $data['rt_maxapp'] ?>" id="rt_maxapp" required="required" class="required frm_input" size="5" />
            명</td>
        </tr>
        
       
        <tr>
          <th scope="row"><label for="rt_comment">PM코멘트</label></th>
          <td><textarea name="rt_comment" cols="70" rows="4"  class="frm_input" id="rt_comment"><?php echo get_text($data['rt_comment']) ?></textarea></td>
        </tr>
         <tr>
          <th scope="row"><label for="rt_order">출력순서</label></th>
          <td>
            <?php echo help('숫자가 낮을수록 우선 출력됩니다. -(음수) 가능') ?>
            <input type="text" name="rt_order" value="<?php echo get_text($data['rt_order']) ?>" id="rt_order"  class="numeric frm_input" size="10" /></td>
        </tr>
        <?
		if($data['rt_no']){?>
        <tr>
          <th scope="row">등록정보</th>
          <td><?=$data['rt_wdate']?>등록, <?=$data['rt_ipaddr']?>, <?=$data['rt_mdate']?>수정 , <strong><?=$data['rt_hits']?></strong> view</td>
        </tr>
        <? }?>
        </tbody>
        </table>
    </div>
</section>







<section id="anc_rt_function">
    <h2 class="h2_frm">채용 기능 설정</h2>
    <?php echo $pg_anchor ?>

    <div class="tbl_frm01 tbl_wrap">
        <table>
        <caption>채용 기능 설정</caption>
        <colgroup>
            <col class="grid_4">
            <col>
            <col class="grid_3">
        </colgroup>
        <tbody>
        <tr>
            <th scope="row"><label for="rt_view">노출 여부</label></th>
            <td>
                <label><input type="checkbox" name="rt_view" value="1" id="rt_view" <?php echo $data['rt_view']?'checked':''; ?>>
            노출합니다</label></td>
          </tr>
        <?
        if ($is_admin == 'super') {
		?>
        <tr>
          <th scope="row"><label for="rt_enable">노출 승인</label></th>
          <td><label><input type="checkbox" name="rt_enable" value="1" id="rt_enable" <?php echo $data['rt_enable']?'checked':''; ?> />
            노출을 승인합니다</label></td>
        </tr>
        <tr>
          <th scope="row"><label for="rt_enable">메인 노출</label></th>
          <td><label>
            <input type="checkbox" name="rt_main" value="1" id="rt_enable" <?php echo $data['rt_main']?'checked':''; ?> />
            메인에 노출합니다)</label></td>
        </tr>
        
        <? }?>
        <tr>
            <th scope="row"><label for="rt_cate1">노출 분류</label></th>
            <td>
            <?
			 $cate1_arr=get_recruitcate_arr('cate1');
			 $cate1_val_arr=explode(',',$data['rt_cate1']);
			 foreach($cate1_arr as $v){
				 echo '
                <label><input type="checkbox" name="rt_cate1[]" value="'.$v['ct_id'].'" id="rt_cate1_'.$v['ct_id'].'" '.(in_array($v['ct_id'],$cate1_val_arr)?'checked':'').'>'.
            $v['ct_name'].'</label>&nbsp;
			';
			 }
			?></td>
          </tr>
          <tr>
            <th scope="row"><label for="rt_cate2">옵션 분류</label></th>
            <td>
            <?
			 $cate2_arr=get_recruitcate_arr('cate2');
			 $cate2_val_arr=explode(',',$data['rt_cate2']);
			 foreach($cate2_arr as $v){
				 echo '
                <label><input type="checkbox" name="rt_cate2[]" value="'.$v['ct_id'].'" id="rt_cate2_'.$v['ct_id'].'" '.(in_array($v['ct_id'],$cate2_val_arr)?'checked':'').'>'.
            $v['ct_name'].'</label>&nbsp;
			';
			 }
			?></td>
          </tr>
          
        </tbody>
        </table>
    </div>
</section>


<section id="anc_rt_design">
    <h2 class="h2_frm">채용 내용</h2>
    <?php echo $pg_anchor ?>

    <div class="tbl_frm01 tbl_wrap">
        <table>
          <tr>
            <th scope="row"><label for="rt_img_list">리스트이미지</label></th>
            <td>
            <?php echo help('채용 목록에 출력될 이미지 입니다. 미 입력시 상세이미지를 차용합니다. <strong>넓이 300픽셀 높이 300픽셀</strong>권장.') ?>
            <input type="file" name="rt_img_list" id="rt_img_list">
            <?php
            $_file = G5_DATA_PATH.'/'.$data['rt_img_list'];
            if (file_exists($_file) && $data['rt_img_list']) {
                $icon_url = G5_DATA_URL.'/'.$data['rt_img_list'];
                echo '<br /><img src="'.$icon_url.'" alt="" style="max-width:300px;margin:10px 10px 0 0;" >';
                echo '<label><input type="checkbox" id="del_rt_img_list" name="del_rt_img_list" value="1">삭제</label>';
            }
            ?>
            </td>
          </tr>
         
          <tr>
            <th scope="row"><label for="rt_img_detail">상세이미지</label></th>
            <td><?php echo help('채용 상세페이지에 출력될 이미지 입니다 <strong>넓이 600픽셀 높이 600픽셀</strong>권장.') ?>
              <input type="file" name="rt_img_detail" id="rt_img_detail" />
              <?php
            $_file = G5_DATA_PATH.'/'.$data['rt_img_detail'];
            if (file_exists($_file) && $data['rt_img_detail']) {
                $icon_url = G5_DATA_URL.'/'.$data['rt_img_detail'];
                echo '<br /><img src="'.$icon_url.'" alt="" style="max-width:300px;margin:10px 10px 0 0;" >';
                echo '<label><input type="checkbox" id="del_rt_img_detail" name="del_rt_img_detail" value="1">삭제</label>';
            }
            ?></td>
          </tr>
       
        </table>
    </div>
    
    <div class="tbl_frm01 tbl_wrap">
    <?php echo editor_html("rt_content_info", get_text($data['rt_content_info'], 0)); ?>
    </div>

</section>




<section id="anc_rt_app">
    <h2 class="h2_frm">신청정보</h2>
    <?php echo $pg_anchor ?>

    <div class="tbl_frm01 tbl_wrap">
        <table>
          
        <caption>신청정보</caption>
        <colgroup>
            <col class="grid_4">
            <col>
            <col class="grid_3">
        </colgroup>
        <tbody>
        <tr>
            <th scope="row"><label for="rt_charge"> 담당자</label></th>
            <td>
                <input type="text" name="rt_charge" value="<?php echo get_text($data['rt_charge']) ?>" id="rt_charge" required class="required frm_input" size="40">
            </td>
        </tr>
        <tr>
          <th scope="row"><label for="rt_charge_tel">연락처</label></th>
          <td><input type="text" name="rt_charge_tel" value="<?php echo get_text($data['rt_charge_tel']) ?>" id="rt_charge_tel" required="required" class="required frm_input" size="60" /></td>
        </tr>
        <tr>
          <th scope="row"><label for="rt_charge_tel">이메일</label></th>
          <td>
          <?php echo help('채용공고 상세화면에서 제목 아래 출력 되는 부제') ?>
          <input type="text" name="rt_charge_email" value="<?php echo get_text($data['rt_charge_email']) ?>" id="rt_name" required="required" class="required frm_input" size="60" /></td>
        </tr>
       
        <tr>
            <th scope="row"><label for="rt_img_list">채용공고/사진</label></th>
            <td>
            <?php echo help('채용 목록에 출력될 이미지 입니다. 미 입력시 상세이미지를 차용합니다. <strong>넓이 300픽셀 높이 300픽셀</strong>권장.') ?>
            <input type="file" name="rt_file1" id="rt_file1">
            <?php
            $_file = G5_DATA_PATH.'/'.$data['rt_file1'];
            if (file_exists($_file) && $data['rt_file1']) {
                $icon_url = G5_DATA_URL.'/'.$data['rt_file1'];
                echo '<br /><a href="./download.php?rt_no='.$rt_no.'&fn=rt_file1&fnv=rt_file1v" class="lsbtn lsbtn-sm obje-blue" style="margin-top:3px;" >'.$data['rt_file1v']."</a>&nbsp;";
                echo '<label><input type="checkbox" id="del_rt_file1" name="del_rt_file1" value="1">삭제</label>';
            }
            ?>
            </td>
          </tr>
         
          <tr>
            <th scope="row"><label for="rt_file2">기타자료</label></th>
            <td><?php echo help('채용 상세페이지에 출력될 이미지 입니다 <strong>넓이 600픽셀 높이 600픽셀</strong>권장.') ?>
              <input type="file" name="rt_file2" id="rt_file2" />
              <?php
            $_file = G5_DATA_PATH.'/'.$data['rt_file2'];
            if (file_exists($_file) && $data['rt_file2']) {
                $icon_url = G5_DATA_URL.'/'.$data['rt_file2'];
                echo '<br /><a href="./download.php?rt_no='.$rt_no.'&fn=rt_file2&fnv=rt_file2v"  class="lsbtn lsbtn-sm obje-blue" style="margin-top:3px;" >'.$data['rt_file2v']."</a>&nbsp;";
                echo '<label><input type="checkbox" id="del_rt_file2" name="del_rt_file2" value="1">삭제</label>';
            }
            ?></td>
          </tr>
        </tbody>
        </table>
    </div>
    
    <div class="tbl_frm01 tbl_wrap">
    <?php echo editor_html("rt_memo", get_text($data['rt_memo'], 0)); ?>
    </div>

</section>


<div class="btn_fixed_top">
	<a href="./recruit_list.php?<?=$qstr?>"  class=" btn_02 btn">전체목록</a>
    <?php if($data['rt_no'] &&  $w ){ ?>
    	
        <a href="./recruit_copy.php?rt_no=<?php echo $data['rt_no']; ?>" id="recruit_copy" target="win_recruit_copy" class=" btn_02 btn">채용복사</a>
        <a href="<?php echo G5_BBS_URL ?>/recruit.php?rt_no=<?php echo $data['rt_table']; ?>" class=" btn_02 btn">채용 바로가기</a>        
    <?php } ?>
    <input type="submit" value="확인" class="btn_submi btn btn_01" accesskey="s">
</div>

</form>

<?
include "member_search_modal.php";
?>
<script>  
$('#openMSearchBtn').click(function(){
	search_member_open('rt_partner');
});
$('#openMSearchBtn2').click(function(){
	search_member_open('rt_partner_ext');
});
function member_select(tg,datas){
	var ov=$('#'+tg).val();
	$('#'+tg).val(ov+(ov?',':'')+datas.mb_id);
}


$(function(){

    $("#recruit_copy").click(function(){
        window.open(this.href, "win_recruit_copy", "left=10,top=10,width=500,height=400");
        return false;
    });

   
});

function recruit_copy(rt_no) {
    window.open("./recruit_copy.php?rt_no="+rt_no, "BoardCopy", "left=10,top=10,width=500,height=200");
}


function frecruitform_submit(f)
{
    <?php echo get_editor_js("rt_content_info"); ?>
 	<?php echo get_editor_js("rt_memo"); ?>
    return true;
}
</script>

<?php
include_once ('../admin.tail.php');
?>
