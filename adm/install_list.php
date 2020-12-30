<?php
$sub_menu = "800100";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');

$sql_common = " from {$g5['recruit_table']} a left outer join  {$g5['member_table']} as b on(a.rt_partner=b.mb_id) ";
$sql_search = " where (1) ";


if ($stx) {
    $sql_search .= "and ($sfl like '%$stx%') ";
}
if ($partner_stx) {
    $sql_search .= " and a.rt_partner='$partner_stx' ";	
	$qstr.="&partner_stx=$partner_stx";
}

//노출 분류 검색
if ($cate1_stx) {
	$sql_search .= " and  find_in_set('{$cate1_stx}',a.rt_cate1)  ";	
	$qstr.="&cate1_stx=$cate1_stx";
}

if (!$sst) {
    $sst  = "a.rt_no ";
    $sod = "desc";
}
$sql_order = " order by $sst $sod ";

$sql = " select count(*) as cnt {$sql_common} {$sql_search}";
$row = sql_fetch($sql,1);
$total_count = $row['cnt'];

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = " select a.*,b.mb_id,b.mb_name,b.mb_10 {$sql_common} {$sql_search} {$sql_order} limit {$from_record}, {$rows} ";
$result = sql_query($sql);

$listall = '<a href="'.$_SERVER['SCRIPT_NAME'].'" class="ov_listall">전체목록</a>';

$g5['title'] = '채용목록';
include_once('../admin.head.php');

$colspan = 14;

//분류 배열
$cate1_arr=get_recruitcate_arr('cate1');


?>

<div class="local_ov01 local_ov">
    <?php echo $listall ?>
    <span class="btn_ov01"><span class="ov_txt">등록된 채용공고 수</span><span class="ov_num"> <?php echo number_format($total_count) ?>개</span></span>
</div>

<form name="fsearch" id="fsearch" class="local_sch01 local_sch" method="get">

  <select id="partner_stx" name="partner_stx" onchange="this.form.submit();">
    <option value=''>-기업회원-</option>
<?
	$recruit_arr=get_partner_arr();
	foreach($recruit_arr as $v) echo "<option value='{$v['mb_id']}' ".($partner_stx==$v['mb_id'] ? 'selected':'').">{$v['mb_1']} [{$v['mb_id']}/{$v['mb_name']}]</option>";
	?>
</select>
<label for="cate1_stx" class="sound_only">분류검색</label>
<select name="cate1_stx" id="cate1_stx">
<option value=''>-분류검색-</option>
<?
	 foreach($cate1_arr as $v){
		echo "<option value='{$v['ct_id']}' ".($cate1_stx==$v['ct_id'] ? 'selected':'').">
	".str_replace("."," &gt; ",preg_replace("/\.$/","",$v['ct_name']))."
	</option>";
	}
	?>
</select>


<label for="sfl" class="sound_only">검색대상</label>
<select name="sfl" id="sfl">
<option value="a.rt_name"<?php echo get_selected($_GET['sfl'], "a.rt_name"); ?>>채용공고명</option>
<option value="a.rt_code"<?php echo get_selected($_GET['sfl'], "a.rt_code"); ?>>공고번호</option>

<option value="b.mb_5"<?php echo get_selected($_GET['sfl'], "b.mb_5"); ?>>기업명</option>
<option value="b.mb_name"<?php echo get_selected($_GET['sfl'], "b.mb_name"); ?>>기업회원명</option>
<option value="b.mb_id"<?php echo get_selected($_GET['sfl'], "b.mb_id"); ?>>기업회원아이디</option>
<option value="rt_content_info"<?php echo get_selected($_GET['sfl'], "rt_content_info", true); ?>>공고상세</option>    
</select>

<label for="stx" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
<input type="text" name="stx" value="<?php echo $stx ?>" id="stx" class="frm_input">
<input type="submit" value="검색" class="btn_submit">

</form>



<form name="fboardlist" id="fboardlist" action="./recruit_list_update.php" onsubmit="return fboardlist_submit(this);" method="post">
<input type="hidden" name="sst" value="<?php echo $sst ?>">
<input type="hidden" name="sod" value="<?php echo $sod ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
<input type="hidden" name="stx" value="<?php echo $stx ?>">
<input type="hidden" name="page" value="<?php echo $page ?>">
<input type="hidden" name="partner_stx" value="<?php echo $partner_stx ?>">
<input type="hidden" name="local_stx" value="<?php echo $local_stx ?>">

<input type="hidden" name="token" value="<?php echo $token ?>">

<div class="tbl_head01 tbl_wrap">
    <table>
    <caption><?php echo $g5['title']; ?> 목록</caption>
    <thead>
    <tr>
        <th scope="col">
            <label for="chkall" class="sound_only">채용 전체</label>
            <input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all(this.form)">
        </th>
        <th scope="col">번호</a></th>
        <th scope="col"><?php echo subject_sort_link('rt_name') ?>공고번호</a></th>
        
        <th scope="col"><?php echo subject_sort_link('rt_name') ?>채용공고</a></th>
        
        <th scope="col">노출분류</th>
        <th scope="col">시작일</th>
        <th scope="col">마감일</th>
        <th scope="col">추천DB</th>
        <th scope="col">채용인원</th>
        <th scope="col">접수상태</th>
        <th scope="col"><?php echo subject_sort_link('rt_view','','desc') ?>노출</a></th>
        <th scope="col"><?php echo subject_sort_link('rt_enable','','desc') ?>승인</a></th>
        <th scope="col"><?php echo subject_sort_link('rt_main','','desc') ?>메인</th>
        <th scope="col"><?php echo subject_sort_link('rt_order','','desc') ?>출력<br>순서</a></th>
        <th scope="col">관리</th>
    </tr>
    </thead>
    <tbody>
    <?php
	$list_num = $total_count - ($page - 1) * $rows;
	
    for ($i=0; $row=sql_fetch_array($result); $i++) {
        $one_update = '<a href="./recruit_form.php?w=u&amp;rt_no='.$row['rt_no'].'&amp;'.$qstr.'" class="btn btn_03">수정</a>';
        $one_copy = '<a href="./recruit_copy.php?rt_no='.$row['rt_no'].'" class="recruit_copy btn btn_02" target="win_recruit_copy">복사</a>';
		$one_app = '<a href="./recruit_app_step1.php?rt_no='.$row['rt_no'].'" class="recruit_app btn btn_01" target="win_recruit_app">추천</a>';

        $bg = 'bg'.($i%2);
		
		
		//추천된 DB 수량
		$app=sql_fetch("select count(*)  cnt,sum(if(app_stats='3',1,0)) as cnt1 from {$g5['recruitapp_table']} where app_rt='{$row['rt_no']}'",1);
    ?>
	
    <tr class="<?php echo $bg; ?>">
        <td class="td_chk">
            <label for="chk_<?php echo $i; ?>" class="sound_only"><?php echo get_text($row['rt_subject']) ?></label>
            <input type="checkbox" name="chk[]" value="<?php echo $i ?>" id="chk_<?php echo $i ?>">
            <input type="hidden" name="rt_no[<?php echo $i ?>]" value="<?php echo $row['rt_no'] ?>">
        </td>
       <td  class="td_num">
       <?=$list_num?>
        </td>
       <td ><a href="./recruit_form.php?<?=$qstr?>&w=u&rt_no=<?php echo $row['rt_no'] ?>" target='_blank' ><?php echo $row['rt_code']?$row['rt_code']:'<span class="fgray">[미정]</span>' ?></a></td>
        
        <td class="td_left">        
          <a href="<?php echo G5_URL ?>/for_common/recruit.php?rt_no=<?php echo $row['rt_no'] ?>" target='_blank' ><?php echo $row['rt_name']?$row['rt_name']:'<span class="fgray">[미정]</span>'; ?></a>
          <br /><span class='txt_succeed'>[ <?=$row['rt_company']?$row['rt_company']:$row['mb_5']?> / <?=$row['rt_partner']?> ] </span>
        </td>
        
        <td>
            <label for="rt_mobile_skin_<?php echo $i; ?>" class="sound_only">분류</label>
           <?    
		   	$str='';
		   	$rt_cate1_arr=explode(",",$row['rt_cate1']);      	
			foreach($rt_cate1_arr as $v){				
				 $str.=($str ? ',':'').$cate1_arr[$v]['ct_name'];
			}
			echo $str;
			?>
        </td>
        <td  class="td_num_c3"><label for="rt_sdate<?php echo $i; ?>" class="sound_only">시작일</label>
          <input type="text" name="rt_sdate[<?php echo $i ?>]" value="<?php echo $row['rt_sdate'] ?>" id="rt_sdate<?php echo $i; ?>" class="tbl_input calendar-input" size="10" /></td>
        <td class="td_num_c3">
            <label for="rt_edate_<?php echo $i; ?>" class="sound_only">마감일</label>
            <input type="text" name="rt_edate[<?php echo $i ?>]" value="<?php echo $row['rt_edate'] ?>" id="rt_edate<?php echo $i; ?>" class="tbl_input calendar-input" size="10">
        </td>
        <td class="td_num">
           
            <a href='recruitapp_list.php?rt_stx=<?=$row['rt_no']?>' target='_blank' class='lsbtn lsbtn-sm obje-light' ><strong class='txt_fail' ><?=$app['cnt1'] ? $app['cnt1']:'0'?></strong> / <?=$app['cnt']?></a></td>
        <td class="td_num">
            <input type="text" name="rt_maxapp[<?php echo $i ?>]" value="<?php echo $row['rt_maxapp'] ?>" id="rt_maxapp<?php echo $i; ?>" class="tbl_input" size="5" />
         </td>
        <td>
            <label for="rt_score_<?php echo $i; ?>" class="sound_only">접수상태</label>
            <span class="td_num_c3">
            <input type="text" name="rt_stats[<?php echo $i ?>]" value="<?php echo $row['rt_stats'] ?>" id="rt_stats<?php echo $i; ?>" class="tbl_input" size="10" />
            </span></td>
        <td class="td_numsmall"><label for="rt_order_<?php echo $i; ?>" class="sound_only">노출</label>
          <input type="checkbox" name="rt_view[<?php echo $i ?>]" value="1" id="rt_view_<?php echo $i ?>" <?=$row['rt_view'] ? 'checked':''?> /></td>
        <td class="td_numsmall"><label for="rt_order_<?php echo $i; ?>" class="sound_only">승인</label>
          <input type="checkbox" name="rt_enable[<?php echo $i ?>]" value="1" id="rt_enable_<?php echo $i ?>" <?=$row['rt_enable'] ? 'checked':''?> <?=$manage_disable?> /></td>
        <td class="td_numsmall"><label for="rt_order_<?php echo $i; ?>" class="sound_only">메인</label>
          <input type="checkbox" name="rt_main[<?php echo $i ?>]" value="1" id="rt_main_<?php echo $i ?>" <?=$row['rt_main'] ? 'checked':''?>  <?=$manage_disable?> /></td>
        <td class="td_numsmall">
            <label for="rt_order_<?php echo $i; ?>" class="sound_only">출력<br>순서</label>
            <input type="text" name="rt_order[<?php echo $i ?>]" value="<?php echo $row['rt_order'] ?>" id="rt_order_<?php echo $i ?>" class="tbl_input" size="2"  <?=$manage_disable?>>
        </td>
        <td class="td_mng td_mng_m">
		   <?=$one_app?><?php echo $one_update ?><br /><a href="<?=G5_URL?>/for_common/talk.php?tkg_mb_id=<?=$row['rt_partner']?>&tkg_new_reset=y" class="talk_open btn btn_02" target="win_talk_view">대화</a><?php echo $one_copy ?>
        </td>
    </tr>
    <?php
	$list_num--;
    }
    if ($i == 0)
        echo '<tr><td colspan="'.$colspan.'" class="empty_table">자료가 없습니다.</td></tr>';
    ?>
    </tbody>
    </table>
</div>

<div class="btn_fixed_top">
    <input type="submit" name="act_button" value="선택수정" onclick="document.pressed=this.value" class="btn_02 btn">
    <input type="submit" name="act_button" value="선택삭제" onclick="document.pressed=this.value" class="btn_02 btn">
    <a href="./recruit_form.php" id="rt_add" class="btn_01 btn">채용 추가</a>
    <?//=$listall?>
</div>

</form>

<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?'.$qstr.'&amp;page='); ?>

<script>
function fboardlist_submit(f)
{
    if (!is_checked("chk[]")) {
        alert(document.pressed+" 하실 항목을 하나 이상 선택하세요.");
        return false;
    }

    if(document.pressed == "선택삭제") {
        if(!confirm("선택한 자료를 정말 삭제하시겠습니까?")) {
            return false;
        }
    }

    return true;
}

$(function(){
	//대화방 오픈
	$(".talk_open").click(function(){
        window.open(this.href, "win_talk_view", "left=50,top=50,width=950,height=850,scrollbars=no");
        return false;
    });



    $(".recruit_copy").click(function(){
        window.open(this.href, "win_recruit_copy", "left=100,top=100,width=550,height=450");
        return false;
    });
	$(".recruit_app").click(function(){
        window.open(this.href, "win_recruit_app", "left=0,top=0,width=1150,height=900");
        return false;
    });
});
</script>

<?php
include_once('../admin.tail.php');
?>
