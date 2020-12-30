<?php
//$sub_menu = "200100";
//include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');

$sql_common = " from {$g5['member_table']} ";

$sql_search = " where (1) ";

//관리자
if($sub_menu == "600200"){
	$sql_search .= " and (mb_level='10' or mb_level='9') ";
	$return_page="member_list_adm.php";


//회원 회원
}else if($sub_menu == "200120"){
	$sql_search .= " and (mb_level between 2 and 8 ) ";
	$return_page="member_list_partner.php";

//임시회원
}else if($sub_menu == "200150"){
	$sql_search .= " and (mb_level='1') ";
	$return_page="member_list_ready.php";

//탈퇴/삭제 회원
}else if($sub_menu == "200130"){
	 $sql_search .= " and mb_10 in ('1','2') ";
	 $return_page="member_list_leave.php";
}

$qstr.="&return_page=$return_page";


if ($stx) {
    $sql_search .= " and ( ";
    switch ($sfl) {
        case 'mb_point' :
            $sql_search .= " ({$sfl} >= '{$stx}') ";
            break;
        case 'mb_level' :
            $sql_search .= " ({$sfl} = '{$stx}') ";
            break;
        case 'mb_tel' :
        case 'mb_hp' :
            $sql_search .= " ({$sfl} like '%{$stx}') ";
            break;
        default :
            $sql_search .= " ({$sfl} like '{$stx}%') ";
            break;
    }
    $sql_search .= " ) ";
}
//탈퇴일
if ($_REQUEST['date_start_stx']) {	
    $sql_search .= " and mb_leave_date >= '".str_replace("-","",$date_start_stx)."'";	
	$qstr.="&date_start_stx=$date_start_stx";		
}
if ($_REQUEST['date_end_stx']) {
    $sql_search .= " and mb_leave_date <= '".str_replace("-","",$date_end_stx)."'";	
	$qstr.="&date_end_stx=$date_end_stx";
}
if ($mb9_stx != '')
    $sql_search .= " and mb_9 = '{$mb9_stx}' ";

if ($is_admin != 'super')
    $sql_search .= " and mb_level <= '{$member['mb_level']}' ";

if (!$sst) {
    $sst = "mb_datetime";
    $sod = "desc";
}

$sql_order = " order by {$sst} {$sod} ";

$sql = " select count(*) as cnt {$sql_common} {$sql_search} {$sql_order} ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

// 탈퇴회원수
$sql = " select count(*) as cnt {$sql_common} {$sql_search} and mb_leave_date <> '' {$sql_order} ";
$row = sql_fetch($sql);
$leave_count = $row['cnt'];

// 차단회원수
$sql = " select count(*) as cnt {$sql_common} {$sql_search} and mb_intercept_date <> '' {$sql_order} ";
$row = sql_fetch($sql);
$intercept_count = $row['cnt'];

$listall = '<a href="'.$_SERVER['SCRIPT_NAME'].'" class="ov_listall">전체목록</a>';

$g5['title'] = '회원관리';
if($sub_menu == "600200") $g5['title']="관리자계정관리";

else if($sub_menu == "200120") $g5['title'] .="-일반회원";

else if($sub_menu == "200150") $g5['title'] .="-임시회원";

else if($sub_menu == "200130") $g5['title'] .="-탈퇴/삭제";

include_once('./admin.head.php');
include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');

$sql = " select * {$sql_common} {$sql_search} {$sql_order} limit {$from_record}, {$rows} ";
$result = sql_query($sql);

$colspan = 16;
?>
<div class="local_ov01 local_ov">
    <?php echo $listall ?>
    <span class="btn_ov01"><span class="ov_txt">총회원수 </span><span class="ov_num"> <?php echo number_format($total_count) ?>명 </span></span>
    <a href="?sst=mb_intercept_date&amp;sod=desc&amp;sfl=<?php echo $sfl ?>&amp;stx=<?php echo $stx ?>" class="btn_ov01"> <span class="ov_txt">차단 </span><span class="ov_num"><?php echo number_format($intercept_count) ?>명</span></a>
    <a href="?sst=mb_leave_date&amp;sod=desc&amp;sfl=<?php echo $sfl ?>&amp;stx=<?php echo $stx ?>" class="btn_ov01"> <span class="ov_txt">탈퇴  </span><span class="ov_num"><?php echo number_format($leave_count) ?>명</span></a>
</div>

<form id="fsearch" name="fsearch" class="local_sch01 local_sch" method="get">

<?
if($sub_menu == "200130"){?>
탈퇴/삭제일:
<input type="text" name="date_start_stx" value="<?php echo $date_start_stx?>" id="date_start_stx"  class="frm_input calendar-input" size="12" placeholder="시작일" />
~
<input type="text" name="date_end_stx" value="<?php echo $date_end_stx?>" id="date_end_stx"  class="frm_input calendar-input" size="12" placeholder="종료일" />
<? }?>

<span class="nowrap">

<select name="sfl" id="sfl">
    
    <option value="mb_id"<?php echo get_selected($_GET['sfl'], "mb_id"); ?>>아이디</option>
    <!--option value="mb_nick"<?php echo get_selected($_GET['sfl'], "mb_nick"); ?>>닉네임</option-->
    
    <!--option value="mb_level"<?php echo get_selected($_GET['sfl'], "mb_level"); ?>>권한</option-->
    <option value="mb_email"<?php echo get_selected($_GET['sfl'], "mb_email"); ?>>E-MAIL</option>
    <option value="mb_tel"<?php echo get_selected($_GET['sfl'], "mb_tel"); ?>>전화번호</option>
    <option value="mb_hp"<?php echo get_selected($_GET['sfl'], "mb_hp"); ?>>휴대폰번호</option>
	<option value="mb_recommend"<?php echo get_selected($_GET['sfl'], "mb_recommend"); ?>>추천인 Rerferral 코드 </option>
	<option value="mb_name"<?php echo get_selected($_GET['sfl'], "mb_name"); ?>>이름</option>
    <!--option value="mb_point"<?php echo get_selected($_GET['sfl'], "mb_point"); ?>>포인트</option-->
    <option value="mb_datetime"<?php echo get_selected($_GET['sfl'], "mb_datetime"); ?>>가입일시</option>
    <option value="mb_ip"<?php echo get_selected($_GET['sfl'], "mb_ip"); ?>>IP</option>
    <!--option value="mb_recommend"<?php echo get_selected($_GET['sfl'], "mb_recommend"); ?>>추천인</option-->
</select>
<label for="stx" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
<input type="text" name="stx" value="<?php echo $stx ?>" id="stx" class="frm_input">
<input type="submit" class="btn_submit" value="검색">
</span>
</form>


<form name="fmemberlist" id="fmemberlist" action="./member_list_update.php" onsubmit="return fmemberlist_submit(this);" method="post">
<input type="hidden" name="sst" value="<?php echo $sst ?>">
<input type="hidden" name="sod" value="<?php echo $sod ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
<input type="hidden" name="stx" value="<?php echo $stx ?>">
<input type="hidden" name="page" value="<?php echo $page ?>">
<input type="hidden" name="return_page" value="<?php echo $return_page ?>">
<input type="hidden" name="date_start_stx" value="<?php echo $date_start_stx ?>">
<input type="hidden" name="date_end_stx" value="<?php echo $date_end_stx ?>">
<input type="hidden" name="mb9_stx" value="<?php echo $mb9_stx ?>">

<input type="hidden" name="token" value="">

<div class="tbl_head01 tbl_wrap">

<?
//탈퇴/삭제회원
if($sub_menu == "200130"){ ?>

    <table>
    <caption><?php echo $g5['title']; ?> 목록</caption>
    <thead>
    <tr>
        <th scope="col" id="mb_list_chk" >
            <label for="chkall" class="sound_only">회원 전체</label>
            <input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all(this.form)">
        </th>
<th id="mb_list_id" scope="col"><?php echo subject_sort_link('mb_id') ?>아이디</a></th>
<th id="mb_list_id" scope="col"><?php echo subject_sort_link('mb_name') ?>이름</a></th>
        <th scope="col" ><?php echo subject_sort_link('mb_email') ?>이메일</a></th>
       
        <th scope="col" id="mb_list_cert"><?php echo subject_sort_link('mb_10', '', 'desc') ?>구분</a></th>
        
        <th scope="col" id="mb_list_cert"><?php echo subject_sort_link('mb_leave_date', '', 'desc') ?>탈퇴/삭제일자</th>
        <th scope="col" id="mb_list_cert">사유
        </th>
       
        <th id="mb_list_lastcall" scope="col"><?php echo subject_sort_link('mb_today_login', '', 'desc') ?>최종접속</a></th>
        <th scope="col" ><?php echo subject_sort_link('mb_datetime', '', 'desc') ?>가입일</th>
        <th scope="col" id="mb_list_mng">관리</th>
    </tr>
    </thead>
    <tbody>
    <?php
    for ($i=0; $row=sql_fetch_array($result); $i++) {
       
        if ($is_admin == 'group') {
            $s_mod = '';
        } else {
            $s_mod_href = '<a href="./member_form.php?'.$qstr.'&amp;w=u&amp;mb_id='.$row['mb_id'].'">';
			$s_mod = '<a href="./member_form.php?'.$qstr.'&amp;w=u&amp;mb_id='.$row['mb_id'].'" class="btn btn_03">수정</a>';
        }
      
        $leave_date = $row['mb_leave_date'] ? $row['mb_leave_date'] : date('Ymd', G5_SERVER_TIME);
        $intercept_date = $row['mb_intercept_date'] ? $row['mb_intercept_date'] : date('Ymd', G5_SERVER_TIME);

        $mb_nick = get_sideview($row['mb_id'], get_text($row['mb_nick']), $row['mb_email'], $row['mb_homepage']);

        $mb_id = $row['mb_id'];
        $leave_msg = '';
        $intercept_msg = '';
        $intercept_title = '';
        if ($row['mb_leave_date']) {
            $mb_id = $mb_id;
            $leave_msg = '<span class="mb_leave_msg">탈퇴함</span>';
        }
        else if ($row['mb_intercept_date']) {
            $mb_id = $mb_id;
            $intercept_msg = '<span class="mb_intercept_msg">차단됨</span>';
            $intercept_title = '차단해제';
        }
        if ($intercept_title == '')
            $intercept_title = '차단하기';

        $address = $row['mb_zip1'] ? print_address($row['mb_addr1'], $row['mb_addr2'], $row['mb_addr3'], $row['mb_addr_jibeon']) : '';

        $bg = 'bg'.($i%2);

        switch($row['mb_certify']) {
            case 'hp':
                $mb_certify_case = '휴대폰';
                $mb_certify_val = 'hp';
                break;
            case 'ipin':
                $mb_certify_case = '아이핀';
                $mb_certify_val = '';
                break;
            case 'admin':
                $mb_certify_case = '관리자';
                $mb_certify_val = 'admin';
                break;
            default:
                $mb_certify_case = '&nbsp;';
                $mb_certify_val = 'admin';
                break;
        }
		
    ?>

    <tr class="<?php echo $bg; ?>">
      <td headers="mb_list_chk" class="td_chk">
        <input type="hidden" name="mb_id[<?php echo $i ?>]" value="<?php echo $row['mb_id'] ?>" id="mb_id_<?php echo $i ?>">
        <label for="chk_<?php echo $i; ?>" class="sound_only"><?php echo get_text($row['mb_name']); ?> <?php echo get_text($row['mb_nick']); ?>님</label>
        <input type="checkbox" name="chk[]" value="<?php echo $i ?>" id="chk_<?php echo $i ?>">
        </td>
<td class='mb-info-open' data-id='<?php echo $row['mb_id'] ?>' ><?php echo $mb_id ?>
<?php
            //소셜계정이 있다면
            if(function_exists('social_login_link_account')){
                if( $my_social_accounts = social_login_link_account($row['mb_id'], false, 'get_data') ){
                    
                    echo '<div class="member_social_provider sns-wrap-over sns-wrap-32">';
                    foreach( (array) $my_social_accounts as $account){     //반복문
                        if( empty($account) || empty($account['provider']) ) continue;
                        
                        $provider = strtolower($account['provider']);
                        $provider_name = social_get_provider_service_name($provider);
                        
                        echo '<span class="sns-icon sns-'.$provider.'" title="'.$provider_name.'">';
                        echo '<span class="ico"></span>';
                        echo '<span class="txt">'.$provider_name.'</span>';
                        echo '</span>';
                    }
                    echo '</div>';
                }
            }
            ?></td>
<td  ><?php echo $row[mb_name] ?></td>
      <td  ><?= $s_mod_href ?><?=$row['mb_email']?></a></td>
      
      <td><?=$row['mb_10']=='1' ? '삭제':'탈퇴'?></td>
      <td><?=$row['mb_leave_date']?substr($row['mb_leave_date'],0,4)."-".substr($row['mb_leave_date'],4,2)."-".substr($row['mb_leave_date'],6,2):''?></td>
      <td class='td_left' ><?=$row['mb_8']?></td>
      
      <td class="td_date"><?php echo substr($row['mb_today_login'],2,8); ?></td>
      <td class="td_date"><?php echo substr($row['mb_datetime'],2,8); ?></td>
      <td headers="mb_list_mng" class="td_mng td_mng_s"><?php echo $s_mod ?><?php echo $s_grp ?></td>
    </tr>
    <?php
    }
    if ($i == 0)
        echo "<tr><td colspan=\"".$colspan."\" class=\"empty_table\">자료가 없습니다.</td></tr>";
    ?>
    </tbody>
    </table>
    

<? }else{ ?>
	<?=$row['mb_email']?>
	<table>
    <caption><?php echo $g5['title']; ?> 목록</caption>
    <thead>
    <tr>
        <th scope="col" id="mb_list_chk" >
            <label for="chkall" class="sound_only">회원 전체</label>
            <input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all(this.form)">
        </th>
		<th id="mb_list_id" scope="col"><?php echo subject_sort_link('mb_id') ?>아이디</a></th>
<th id="mb_list_id" scope="col"><?php echo subject_sort_link('mb_name') ?>이름</a></th>
        <th scope="col" ><?php echo subject_sort_link('mb_email') ?>이메일</a></th>
        
    <th scope="col" id="mb_list_cert"><?php echo subject_sort_link('mb_level') ?>권한</th>
<th id="mb_list_id" scope="col"><?php echo subject_sort_link('mb_recommend') ?>추천인</th>
        <th id="mb_list_id" scope="col">하위추천인</th>
<th id="mb_list_id" scope="col">
 서브계정</th>
        <?
		//계정별
		foreach($g5['cn_cointype'] as $k=> $v){ ?>
        <th id="mb_list_id" scope="col"><?php echo subject_sort_link('mb_point_free_'.$k) ?>총<?=$v?>수량</th>
        <? }?>
        <th scope="col" id="mb_list_cert"><!--이메일/-->지갑</th>
        <th scope="col" id="mb_list_cert">연락처</th>
        <th id="mb_list_lastcall" scope="col"><?php echo subject_sort_link('mb_today_login', '', 'desc') ?>최종접속</a></th>
        <th scope="col" ><?php echo subject_sort_link('mb_datetime', '', 'desc') ?>가입일</th>
        <th scope="col" id="mb_list_mng">관리</th>
    </tr>
    </thead>
    <tbody>
    <?php
    for ($i=0; $row=sql_fetch_array($result); $i++) {
        // 접근가능한 그룹수
        $sql2 = " select count(*) as cnt from {$g5['group_member_table']} where mb_id = '{$row['mb_id']}' ";
        $row2 = sql_fetch($sql2);
        $group = '';
        if ($row2['cnt'])
            $group = '<a href="./boardgroupmember_form.php?mb_id='.$row['mb_id'].'">'.$row2['cnt'].'</a>';

        if ($is_admin == 'group') {
            $s_mod = '';
        } else {
            $s_mod_href = '<a href="./member_form.php?'.$qstr.'&amp;w=u&amp;mb_id='.$row['mb_id'].'" >';
			$s_mod = '<a href="./member_form.php?'.$qstr.'&amp;w=u&amp;mb_id='.$row['mb_id'].'" class="btn btn_03">수정</a>';
        }
        $s_grp = '<a href="./boardgroupmember_form.php?mb_id='.$row['mb_id'].'" class="btn btn_02">그룹</a>';

        $leave_date = $row['mb_leave_date'] ? $row['mb_leave_date'] : date('Ymd', G5_SERVER_TIME);
        $intercept_date = $row['mb_intercept_date'] ? $row['mb_intercept_date'] : date('Ymd', G5_SERVER_TIME);

        $mb_nick = get_sideview($row['mb_id'], get_text($row['mb_nick']), $row['mb_email'], $row['mb_homepage']);

        $mb_id = $row['mb_id'];
        $leave_msg = '';
        $intercept_msg = '';
        $intercept_title = '';
        if ($row['mb_leave_date']) {
            $mb_id = $mb_id;
            $leave_msg = '<span class="mb_leave_msg">탈퇴함</span>';
        }
        else if ($row['mb_intercept_date']) {
            $mb_id = $mb_id;
            $intercept_msg = '<span class="mb_intercept_msg">차단됨</span>';
            $intercept_title = '차단해제';
        }
        if ($intercept_title == '')
            $intercept_title = '차단하기';

        $address = $row['mb_zip1'] ? print_address($row['mb_addr1'], $row['mb_addr2'], $row['mb_addr3'], $row['mb_addr_jibeon']) : '';

        $bg = 'bg'.($i%2);

        switch($row['mb_certify']) {
            case 'hp':
                $mb_certify_case = '휴대폰';
                $mb_certify_val = 'hp';
                break;
            case 'ipin':
                $mb_certify_case = '아이핀';
                $mb_certify_val = '';
                break;
            case 'admin':
                $mb_certify_case = '관리자';
                $mb_certify_val = 'admin';
                break;
            default:
                $mb_certify_case = '&nbsp;';
                $mb_certify_val = 'admin';
                break;
        }
		
		$count=sql_fetch("select count(*) cnt  from {$g5['cn_tree']} where mb_id='{$row[mb_id]}'");			
		
		//서브 계정
		$temp=sql_fetch("select count(*) cnt  from  {$g5['cn_sub_account']} where mb_id='{$row[mb_id]}' and ac_id != '{$row[mb_id]}'");
    ?>

    <tr class="<?php echo $bg; ?>">
      <td headers="mb_list_chk" class="td_chk">
        <input type="hidden" name="mb_id[<?php echo $i ?>]" value="<?php echo $row['mb_id'] ?>" id="mb_id_<?php echo $i ?>">
        <label for="chk_<?php echo $i; ?>" class="sound_only"><?php echo get_text($row['mb_name']); ?> <?php echo get_text($row['mb_nick']); ?>님</label>
        <input type="checkbox" name="chk[]" value="<?php echo $i ?>" id="chk_<?php echo $i ?>">
        </td>
	
	<td  class='mb-info-open' data-id='<?php echo $row['mb_id'] ?>'>
        <?php echo $mb_id ?>
        <?php
            //소셜계정이 있다면
            if(function_exists('social_login_link_account')){
                if( $my_social_accounts = social_login_link_account($row['mb_id'], false, 'get_data') ){
                    
                    echo '<div class="member_social_provider sns-wrap-over sns-wrap-32">';
                    foreach( (array) $my_social_accounts as $account){     //반복문
                        if( empty($account) || empty($account['provider']) ) continue;
                        
                        $provider = strtolower($account['provider']);
                        $provider_name = social_get_provider_service_name($provider);
                        
                        echo '<span class="sns-icon sns-'.$provider.'" title="'.$provider_name.'">';
                        echo '<span class="ico"></span>';
                        echo '<span class="txt">'.$provider_name.'</span>';
                        echo '</span>';
                    }
                    echo '</div>';
                }
            }
            ?>
    </td>
<td  ><?php echo $row[mb_name] ?></td>
		
      <td  ><?= $s_mod_href ?><?=$row['mb_email']?></a></td>
      
      
      
    <td><strong><?php echo $g5['member_level_name'][$row['mb_level']] ?></strong></td>
<td  ><?php echo $row['mb_recommend']?$row['mb_recommend']:'-'?></td>
      <td  >
	  <?
        if($count['cnt'] > 0 ){?>
			<a href="<?=G5_CN_ADMIN_URL?>/recommender_list.php?mb_id_stx=<?php echo $row['mb_id'] ?>" class="btn_frmline"><?php echo $count['cnt']?></a>
            <?
		}else{?>
       <?php echo $count['cnt']?>
        <? }?></td>
		<td  ><a href="<?=G5_CN_ADMIN_URL?>/member_sub_list.php?mb_id_stx=<?php echo $row['mb_id'] ?>" class="btn_frmline"><?=$temp[cnt]?></a></td>
	<?
    //계정별
    foreach($g5['cn_cointype'] as $k=> $v){ ?>
      <td  ><?php echo number_format2($row['mb_point_free_'.$k],8)?></td>
     <? }?> 
      <td class='td_left' ><?//=$row['mb_email']?> <!--(인증 : <?php echo preg_match('/[1-9]/', $row['mb_email_certify'])?'<span class="txt_true">Yes</span>':'<span class="txt_false">No</span>'; ?>)-->
	  <?
    //계정별
    foreach($g5['cn_coin_in'] as $v){?>
	<p><?=$g5['cn_cointype'][$v]?> : <?php echo $row['mb_wallet_addr_'.$v]?$row['mb_wallet_addr_'.$v]:'-'?></p>
     <? }?> 
	  </td>
      <td><?=$row['mb_hp']?></td>
      <td class="td_date"><?php echo substr($row['mb_today_login'],2,8); ?></td>
      <td class="td_date"><?php echo substr($row['mb_datetime'],2,8); ?></td>
      <td headers="mb_list_mng" class="td_mng td_mng_s"><?php echo $s_mod ?><?php echo $s_grp ?></td>
    </tr>
    <?php
    }
    if ($i == 0)
        echo "<tr><td colspan=\"".$colspan."\" class=\"empty_table\">자료가 없습니다.</td></tr>";
    ?>
    </tbody>
    </table>
    
    <?
	/*
    <table>
    <caption><?php echo $g5['title']; ?> 목록</caption>
    <thead>
    <tr>
        <th scope="col" id="mb_list_chk" rowspan="2" >
            <label for="chkall" class="sound_only">회원 전체</label>
            <input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all(this.form)">
        </th>
       
       
        <th scope="col" id="mb_list_id" colspan="2"><?php echo subject_sort_link('mb_id') ?>아이디</a></th>
        <?
		if($config['cf_cert_use']){?>
        <th scope="col" rowspan="2" id="mb_list_cert"><?php echo subject_sort_link('mb_certify', '', 'desc') ?>본인확인</a></th>
        <? }?>
        <th scope="col" id="mb_list_mailc"><?php echo subject_sort_link('mb_email_certify', '', 'desc') ?>메일인증</a></th>
        <th scope="col" id="mb_list_open"><?php echo subject_sort_link('mb_open', '', 'desc') ?>정보공개</a></th>
        <th scope="col" id="mb_list_mailr"><?php echo subject_sort_link('mb_mailling', '', 'desc') ?>메일수신</a></th>
        <th scope="col" id="mb_list_auth">상태</th>
        <th scope="col" id="mb_list_mobile">휴대폰</th>
        <th scope="col" id="mb_list_lastcall"><?php echo subject_sort_link('mb_today_login', '', 'desc') ?>최종접속</a></th>
        <th scope="col" id="mb_list_grp">접근그룹</th>
        <th scope="col" rowspan="2" id="mb_list_mng">관리</th>
    </tr>
    <tr>
        <th scope="col" id="mb_list_name"><?php echo subject_sort_link('mb_name') ?>성명</th>
        <th scope="col" id="mb_list_nick">성별</a></th>
        <th scope="col" id="mb_list_sms"><?php echo subject_sort_link('mb_sms', '', 'desc') ?>SMS수신</a></th>
        <th scope="col" id="mb_list_adultc"><?php echo subject_sort_link('mb_adult', '', 'desc') ?>성인인증</a></th>
        <th scope="col" id="mb_list_auth"><?php echo subject_sort_link('mb_intercept_date', '', 'desc') ?>접근차단</a></th>
        <th scope="col" id="mb_list_deny"><?php echo subject_sort_link('mb_level', '', 'desc') ?>권한</a></th>
        <th scope="col" id="mb_list_tel">전화번호</th>
        <th scope="col" id="mb_list_join"><?php echo subject_sort_link('mb_datetime', '', 'desc') ?>가입일</a></th>
        <th scope="col" id="mb_list_point"><?php echo subject_sort_link('mb_point', '', 'desc') ?> 포인트</a></th>
    </tr>
    </thead>
    <tbody>
    <?php
    for ($i=0; $row=sql_fetch_array($result); $i++) {
        // 접근가능한 그룹수
        $sql2 = " select count(*) as cnt from {$g5['group_member_table']} where mb_id = '{$row['mb_id']}' ";
        $row2 = sql_fetch($sql2);
        $group = '';
        if ($row2['cnt'])
            $group = '<a href="./boardgroupmember_form.php?mb_id='.$row['mb_id'].'">'.$row2['cnt'].'</a>';

        if ($is_admin == 'group') {
            $s_mod = '';
        } else {
            $s_mod = '<a href="./member_form.php?'.$qstr.'&amp;w=u&amp;mb_id='.$row['mb_id'].'" class="btn btn_03">수정</a>';
        }
        $s_grp = '<a href="./boardgroupmember_form.php?mb_id='.$row['mb_id'].'" class="btn btn_02">그룹</a>';

        $leave_date = $row['mb_leave_date'] ? $row['mb_leave_date'] : date('Ymd', G5_SERVER_TIME);
        $intercept_date = $row['mb_intercept_date'] ? $row['mb_intercept_date'] : date('Ymd', G5_SERVER_TIME);

        $mb_nick = get_sideview($row['mb_id'], get_text($row['mb_nick']), $row['mb_email'], $row['mb_homepage']);

        $mb_id = $row['mb_id'];
        $leave_msg = '';
        $intercept_msg = '';
        $intercept_title = '';
        if ($row['mb_leave_date']) {
            $mb_id = $mb_id;
            $leave_msg = '<span class="mb_leave_msg">탈퇴함</span>';
        }
        else if ($row['mb_intercept_date']) {
            $mb_id = $mb_id;
            $intercept_msg = '<span class="mb_intercept_msg">차단됨</span>';
            $intercept_title = '차단해제';
        }
        if ($intercept_title == '')
            $intercept_title = '차단하기';

        $address = $row['mb_zip1'] ? print_address($row['mb_addr1'], $row['mb_addr2'], $row['mb_addr3'], $row['mb_addr_jibeon']) : '';

        $bg = 'bg'.($i%2);

        switch($row['mb_certify']) {
            case 'hp':
                $mb_certify_case = '휴대폰';
                $mb_certify_val = 'hp';
                break;
            case 'ipin':
                $mb_certify_case = '아이핀';
                $mb_certify_val = '';
                break;
            case 'admin':
                $mb_certify_case = '관리자';
                $mb_certify_val = 'admin';
                break;
            default:
                $mb_certify_case = '&nbsp;';
                $mb_certify_val = 'admin';
                break;
        }
		
		//등록체용
		if($sub_menu=='200110' || $sub_menu=='200115'){
		$rt_cnt=sql_fetch("select count(*) cnt from {$g5['recruit_table']} where rt_partner='{$row['mb_id']}'");
        }
        
        if($sub_menu=='200120' || $sub_menu=='200125'){
        $db_cnt=sql_fetch("select count(*) cnt from {$g5['recruitdb_table']} where db_mb='{$row['mb_id']}'");
		}
    ?>

    <tr class="<?php echo $bg; ?>">
        <td headers="mb_list_chk" class="td_chk" rowspan="2">
            <input type="hidden" name="mb_id[<?php echo $i ?>]" value="<?php echo $row['mb_id'] ?>" id="mb_id_<?php echo $i ?>">
            <label for="chk_<?php echo $i; ?>" class="sound_only"><?php echo get_text($row['mb_name']); ?> <?php echo get_text($row['mb_nick']); ?>님</label>
            <input type="checkbox" name="chk[]" value="<?php echo $i ?>" id="chk_<?php echo $i ?>">
        </td>
        <?
		if($sub_menu=='200110' || $sub_menu=='200115'){?>
        <? }?>
        <?
		if($sub_menu=='200120' || $sub_menu=='200125'){?>
        <? }?>
        
        <td  colspan="2" >
            <?php echo $mb_id ?>
            <?php
            //소셜계정이 있다면
            if(function_exists('social_login_link_account')){
                if( $my_social_accounts = social_login_link_account($row['mb_id'], false, 'get_data') ){
                    
                    echo '<div class="member_social_provider sns-wrap-over sns-wrap-32">';
                    foreach( (array) $my_social_accounts as $account){     //반복문
                        if( empty($account) || empty($account['provider']) ) continue;
                        
                        $provider = strtolower($account['provider']);
                        $provider_name = social_get_provider_service_name($provider);
                        
                        echo '<span class="sns-icon sns-'.$provider.'" title="'.$provider_name.'">';
                        echo '<span class="ico"></span>';
                        echo '<span class="txt">'.$provider_name.'</span>';
                        echo '</span>';
                    }
                    echo '</div>';
                }
            }
            ?>
        </td>
        <?
		if($config['cf_cert_use']){?>
        <td headers="mb_list_cert"  rowspan="2" class="td_mbcert">
            <input type="radio" name="mb_certify[<?php echo $i; ?>]" value="ipin" id="mb_certify_ipin_<?php echo $i; ?>" <?php echo $row['mb_certify']=='ipin'?'checked':''; ?>>
            <label for="mb_certify_ipin_<?php echo $i; ?>">아이핀</label><br>
            <input type="radio" name="mb_certify[<?php echo $i; ?>]" value="hp" id="mb_certify_hp_<?php echo $i; ?>" <?php echo $row['mb_certify']=='hp'?'checked':''; ?>>
            <label for="mb_certify_hp_<?php echo $i; ?>">휴대폰</label>
        </td>
        <? }?>
        <td headers="mb_list_mailc"><?=$row['mb_email']?> <?php echo preg_match('/[1-9]/', $row['mb_email_certify'])?'<span class="txt_true">Yes</span>':'<span class="txt_false">No</span>'; ?></td>
        <td headers="mb_list_open">
            <label for="mb_open_<?php echo $i; ?>" class="sound_only">정보공개</label>
            <input type="checkbox" name="mb_open[<?php echo $i; ?>]" <?php echo $row['mb_open']?'checked':''; ?> value="1" id="mb_open_<?php echo $i; ?>">
        </td>
        <td headers="mb_list_mailr">
            <label for="mb_mailling_<?php echo $i; ?>" class="sound_only">메일수신</label>
            <input type="checkbox" name="mb_mailling[<?php echo $i; ?>]" <?php echo $row['mb_mailling']?'checked':''; ?> value="1" id="mb_mailling_<?php echo $i; ?>">
        </td>
        <td headers="mb_list_auth" class="td_mbstat">
            <?php
            if ($leave_msg || $intercept_msg) echo $leave_msg.' '.$intercept_msg;
            else echo "정상";
            ?>
        </td>
        <td headers="mb_list_mobile" class="td_tel"><?php echo get_text($row['mb_hp']); ?></td>
        <td headers="mb_list_lastcall" class="td_date"><?php echo substr($row['mb_today_login'],2,8); ?></td>
        <td headers="mb_list_grp" class="td_numsmall"><?php echo $group ?></td>
        <td headers="mb_list_mng" rowspan="2" class="td_mng td_mng_s"><?php echo $s_mod ?><?php echo $s_grp ?></td>
    </tr>
    <tr class="<?php echo $bg; ?>">
    <?
		if($sub_menu=='200110' || $sub_menu=='200115'){?>
      <? }?>
    <?
		if($sub_menu=='200120' || $sub_menu=='200125'){?>
      <? }?>
        
        <td headers="mb_list_name" class="td_mbname"><?php echo get_text($row['mb_name']); ?></td>
        <td headers="mb_list_nick" ><div><?php echo $row['mb_sex']=='F'?'남성':'여성' ?></div></td>
        
        <td headers="mb_list_sms">
            <label for="mb_sms_<?php echo $i; ?>" class="sound_only">SMS수신</label>
            <input type="checkbox" name="mb_sms[<?php echo $i; ?>]" <?php echo $row['mb_sms']?'checked':''; ?> value="1" id="mb_sms_<?php echo $i; ?>">
        </td>
        <td headers="mb_list_adultc">
            <label for="mb_adult_<?php echo $i; ?>" class="sound_only">성인인증</label>
            <input type="checkbox" name="mb_adult[<?php echo $i; ?>]" <?php echo $row['mb_adult']?'checked':''; ?> value="1" id="mb_adult_<?php echo $i; ?>">
        </td>
        <td headers="mb_list_deny">
            <?php if(empty($row['mb_leave_date'])){ ?>
            <input type="checkbox" name="mb_intercept_date[<?php echo $i; ?>]" <?php echo $row['mb_intercept_date']?'checked':''; ?> value="<?php echo $intercept_date ?>" id="mb_intercept_date_<?php echo $i ?>" title="<?php echo $intercept_title ?>">
            <label for="mb_intercept_date_<?php echo $i; ?>" class="sound_only">접근차단</label>
            <?php } ?>
        </td>
        <td headers="mb_list_auth" class="td_mbstat">
            <?php echo get_member_level_select("mb_level[$i]", 1, $member['mb_level'], $row['mb_level']) ?>
        </td>
        <td headers="mb_list_tel" class="td_tel"><?php echo get_text($row['mb_tel']); ?></td>
        <td headers="mb_list_join" class="td_date"><?php echo substr($row['mb_datetime'],2,8); ?></td>
        <td headers="mb_list_point" class="td_num"><a href="point_list.php?sfl=mb_id&amp;stx=<?php echo $row['mb_id'] ?>"><?php echo number_format($row['mb_point']) ?></a></td>

    </tr>

    <?php
    }
    if ($i == 0)
        echo "<tr><td colspan=\"".$colspan."\" class=\"empty_table\">자료가 없습니다.</td></tr>";
    ?>
    </tbody>
    </table>
	*/
	?>

<? }?>    
</div>

<div class="btn_fixed_top">
    <input type="submit" name="act_button" value="선택수정" onclick="document.pressed=this.value" class="btn btn_02">
    <input type="submit" name="act_button" value="선택삭제" onclick="document.pressed=this.value" class="btn btn_02">
    <input type="submit" name="act_button" value="완전삭제" onclick="document.pressed=this.value" class="btn btn_02">
    <?php if ($is_admin == 'super') { ?>
    <a href="./member_form.php" id="member_add" class="btn btn_01">회원추가</a>
    <?php } ?>

</div>


</form>

<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, '?'.$qstr.'&amp;page='); ?>

<script>
function fmemberlist_submit(f)
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
		
	if(document.pressed == "완전삭제") {
        if(!confirm("선택한 자료를 정말 완전히 삭제하시겠습니까?\n\n삭제된 정보는 복구 할 수 없습니다")) {
            return false;
        }
    }
    return true;
}
</script>

<?php
include_once ('./admin.tail.php');
?>
