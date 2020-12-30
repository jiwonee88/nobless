<?php
$sub_menu = "200100";
include_once("./_common.php");
include_once(G5_LIB_PATH."/register.lib.php");
include_once(G5_LIB_PATH.'/thumbnail.lib.php');

if ($w == 'u')
    check_demo();

auth_check($auth[$sub_menu], 'w');

check_admin_token();

$mb_id = trim($_POST['mb_id']);

if($mb_id=='') $mb_id=get_mbcode();

// 휴대폰번호 체크
//$mb_hp = hyphen_hp_number($_POST['mb_hp']);
$mb_hp = only_numeric($_POST['mb_hp']);
if($mb_hp) {
    //$result = exist_mb_hp($mb_hp, $mb_id);
    //if ($result)
    //    alert($result);
}

// 인증정보처리
if($_POST['mb_certify_case'] && $_POST['mb_certify']) {
    $mb_certify = $_POST['mb_certify_case'];
    $mb_adult = $_POST['mb_adult'];
} else {
    $mb_certify = '';
    $mb_adult = 0;
}


$mb_zip1 = substr($_POST['mb_zip'], 0, 3);
$mb_zip2 = substr($_POST['mb_zip'], 3);

$mb_email = isset($_POST['mb_email']) ? get_email_address(trim($_POST['mb_email'])) : '';
//$mb_nick = isset($_POST['mb_nick']) ? trim(strip_tags($_POST['mb_nick'])) : '';

$mb_birth=trim($_POST['birth_year']).'-'.sprintf("%02d",trim($_POST['birth_month'])).'-'.sprintf("%02d",trim($_POST['birth_day']));

//if ($msg = valid_mb_nick($mb_nick))     alert($msg, "", true, true);

if(is_array($mb_5)) $mb_5_val=implode("|",$mb_5);
else $mb_5_val='';

if($_POST['mb_datetime']=='') $_POST['mb_datetime']=date("Y-m-d H:i:s");

$sql_common = "  mb_name = '{$_POST['mb_name']}',
                 mb_nick = '{$_POST['mb_name']}',
                 mb_email = '{$mb_email}',
                 mb_homepage = '{$_POST['mb_homepage']}',
                 mb_tel = '{$_POST['mb_tel']}',
				 mb_birth	= '{$mb_birth}',
				 mb_sex = '{$_POST['mb_sex']}',
                 mb_hp = '{$mb_hp}',
                 mb_certify = '{$mb_certify}',
                 mb_adult = '{$mb_adult}',
                 mb_zip1 = '$mb_zip1',
                 mb_zip2 = '$mb_zip2',
                 mb_addr1 = '{$_POST['mb_addr1']}',
                 mb_addr2 = '{$_POST['mb_addr2']}',
                 mb_addr3 = '{$_POST['mb_addr3']}',
                 mb_addr_jibeon = '{$_POST['mb_addr_jibeon']}',
                 mb_signature = '{$_POST['mb_signature']}',
                 mb_leave_date = '{$_POST['mb_leave_date']}',
                 mb_intercept_date='{$_POST['mb_intercept_date']}',
                 mb_memo = '{$_POST['mb_memo']}',
                 mb_mailling = '{$_POST['mb_mailling']}',
                 mb_sms = '{$_POST['mb_sms']}',
                 mb_open = '{$_POST['mb_open']}',
                 mb_profile = '{$_POST['mb_profile']}',
                 mb_level = '{$_POST['mb_level']}',
				 
				 mb_plan_b= '{$_POST['mb_plan_b']}',
				 mb_plan_m= '{$_POST['mb_plan_m']}',
				 mb_plan_t= '{$_POST['mb_plan_t']}',
				 mb_plan_s= '{$_POST['mb_plan_s']}',
				 
				 mb_bank= '{$_POST['mb_bank']}',
				 mb_bank_num= '{$_POST['mb_bank_num']}',
				 mb_bank_user= '{$_POST['mb_bank_user']}',
				 
				 
				 mb_nation= '{$_POST['mb_nation']}',
				 
                 mb_1 = '{$_POST['mb_1']}',
                 mb_2 = '{$_POST['mb_2']}',
                 mb_3 = '{$_POST['mb_3']}',
                 mb_4 = '{$_POST['mb_4']}',
                 mb_5 = '{$mb_5_val}',
                 mb_6 = '{$_POST['mb_6']}',
                 mb_7 = '{$_POST['mb_7']}',
                 mb_8 = '{$_POST['mb_8']}',
                 mb_9 = '{$_POST['mb_9']}', 
				 mb_10 = '{$_POST['mb_10']}' ,
				 mb_11 = '{$_POST['mb_11']}' ,
				 mb_12 = '{$_POST['mb_12']}' ,
				 mb_13 = '{$_POST['mb_13']}' ,
				 mb_14 = '{$_POST['mb_14']}' ,				 
				 
				 mb_datetime = '{$_POST['mb_datetime']}' 
				 
				 ";



//이체 비밀번호
if ($w == '' || $mb_deposite_pass!='' ) {
	$sql_common .= " , mb_deposite_pass = '".get_encrypt_string($mb_deposite_pass)."'";
}


if ($w == '')
{	
    	
	
	$mb = get_member($mb_id);
    if ($mb['mb_id'])
        alert('이미 존재하는 Rerferral 코드입니다.\\nＩＤ : '.$mb['mb_id'].'\\n이름 : '.$mb['mb_name'].'\\n메일 : '.$mb['mb_email']);

    // 닉네임중복체크
	/*
    $sql = " select mb_id, mb_name, mb_nick, mb_email from {$g5['member_table']} where mb_nick = '{$mb_nick}' ";
    $row = sql_fetch($sql);
    if ($row['mb_id'])
        alert('이미 존재하는 닉네임입니다.\\nＩＤ : '.$row['mb_id'].'\\n이름 : '.$row['mb_name'].'\\n닉네임 : '.$row['mb_nick'].'\\n메일 : '.$row['mb_email']);
	*/
	
    // 이메일중복체크
	 if ($mb_email !=''){
		$sql = " select mb_id, mb_name, mb_nick, mb_email from {$g5['member_table']} where mb_email = '{$mb_email}' ";
		$row = sql_fetch($sql);
		if ($row['mb_id'])
			alert('이미 존재하는 이메일입니다.\\nＩＤ : '.$row['mb_id'].'\\n이름 : '.$row['mb_name'].'\\n메일 : '.$row['mb_email']);
	}

	
	$mb_recommend   = isset($_POST['mb_recommend'])     ? trim($_POST['mb_recommend'])   : "";

	if ( $mb_recommend) {
		if (!exist_mb_id($mb_recommend))
			alert("추천인이 존재하지 않습니다.");
	}
	
	if (strtolower($mb_id) == strtolower($mb_recommend)) {
		alert('본인을 추천 할 수 없습니다.');
	}
	
	$sql_common.="
	,mb_recommend = '{$mb_recommend}'
	";

    sql_query(" insert into {$g5['member_table']} set mb_id = '{$mb_id}', mb_password = '".get_encrypt_string($mb_password)."', mb_15 = '{$mb_password}' , mb_ip = '{$_SERVER['REMOTE_ADDR']}', mb_email_certify = '".G5_TIME_YMDHIS."', {$sql_common} ");
}
else if ($w == 'u')
{
	//아이디 변경
	if($new_mb_id && $new_mb_id!=$mb_id && $change_mb_id=='y'){
		
		$rtn=change_mb_id($mb_id,$new_mb_id);
		if (!$rtn[0])
        	alert('아이디 변경 실패.',"./member_list.php?$qstr");			
			$mb_id=$new_mb_id;
	}
	
	
    $mb = get_member($mb_id);
    if (!$mb['mb_id'])
        alert('존재하지 않는 회원자료입니다.');

    if ($is_admin != 'super' && $mb['mb_level'] >= $member['mb_level'])
        alert('자신보다 권한이 높거나 같은 회원은 수정할 수 없습니다.');

    if ($is_admin !== 'super' && is_admin($mb['mb_id']) === 'super' ) {
        alert('최고관리자의 비밀번호를 수정할수 없습니다.');
    }
	
    if ($_POST['mb_id'] == $member['mb_id'] && $_POST['mb_level'] != $mb['mb_level'])
        alert($mb['mb_id'].' : 로그인 중인 관리자 레벨은 수정 할 수 없습니다.');

    // 닉네임중복체크
	/*
    $sql = " select mb_id, mb_name, mb_nick, mb_email from {$g5['member_table']} where mb_nick = '{$mb_nick}' and mb_id <> '$mb_id' ";
    $row = sql_fetch($sql);
    if ($row['mb_id'])
        alert('이미 존재하는 닉네임입니다.\\nＩＤ : '.$row['mb_id'].'\\n이름 : '.$row['mb_name'].'\\n닉네임 : '.$row['mb_nick'].'\\n메일 : '.$row['mb_email']);
	*/
    // 이메일중복체크
	if ($mb_email !=''){
		$sql = " select mb_id, mb_name, mb_nick, mb_email from {$g5['member_table']} where mb_email = '{$mb_email}' and mb_id <> '$mb_id' ";
		$row = sql_fetch($sql);
		if ($row['mb_id'])
			alert('이미 존재하는 이메일입니다.\\nＩＤ : '.$row['mb_id'].'\\n이름 : '.$row['mb_name'].'\\n메일 : '.$row['mb_email']);
	}

    
	$mb_dir = substr($mb_id,0,2);


    // 회원 아이콘 삭제
    if ($del_mb_icon)
        @unlink(G5_DATA_PATH.'/member/'.$mb_dir.'/'.$mb_id.'.gif');

    $image_regex = "/(\.(gif|jpe?g|png))$/i";
    $mb_icon_img = $mb_id.'.gif';

    // 아이콘 업로드
    if (isset($_FILES['mb_icon']) && is_uploaded_file($_FILES['mb_icon']['tmp_name'])) {
        if (!preg_match($image_regex, $_FILES['mb_icon']['name'])) {
            alert($_FILES['mb_icon']['name'] . '은(는) 이미지 파일이 아닙니다.');
        }

        if (preg_match($image_regex, $_FILES['mb_icon']['name'])) {
            $mb_icon_dir = G5_DATA_PATH.'/member/'.$mb_dir;
            @mkdir($mb_icon_dir, G5_DIR_PERMISSION);
            @chmod($mb_icon_dir, G5_DIR_PERMISSION);

            $dest_path = $mb_icon_dir.'/'.$mb_icon_img;

            move_uploaded_file($_FILES['mb_icon']['tmp_name'], $dest_path);
            chmod($dest_path, G5_FILE_PERMISSION);
            
            if (file_exists($dest_path)) {
                $size = @getimagesize($dest_path);
                if ($size[0] > $config['cf_member_icon_width'] || $size[1] > $config['cf_member_icon_height']) {
                    $thumb = null;
                    if($size[2] === 2 || $size[2] === 3) {
                        //jpg 또는 png 파일 적용
                        $thumb = thumbnail($mb_icon_img, $mb_icon_dir, $mb_icon_dir, $config['cf_member_icon_width'], $config['cf_member_icon_height'], true, true);
                        if($thumb) {
                            @unlink($dest_path);
                            rename($mb_icon_dir.'/'.$thumb, $dest_path);
                        }
                    }
                    if( !$thumb ){
                        // 아이콘의 폭 또는 높이가 설정값 보다 크다면 이미 업로드 된 아이콘 삭제
                        @unlink($dest_path);
                    }
                }
            }
        }
    }
    
    $mb_img_dir = G5_DATA_PATH.'/member_image/';
    if( !is_dir($mb_img_dir) ){
        @mkdir($mb_img_dir, G5_DIR_PERMISSION);
        @chmod($mb_img_dir, G5_DIR_PERMISSION);
    }
    $mb_img_dir .= substr($mb_id,0,2);

    // 회원 이미지 삭제
    if ($del_mb_img)
        @unlink($mb_img_dir.'/'.$mb_icon_img);

    // 아이콘 업로드
    if (isset($_FILES['mb_img']) && is_uploaded_file($_FILES['mb_img']['tmp_name'])) {
        if (!preg_match($image_regex, $_FILES['mb_img']['name'])) {
            alert($_FILES['mb_img']['name'] . '은(는) 이미지 파일이 아닙니다.');
        }
        
        if (preg_match($image_regex, $_FILES['mb_img']['name'])) {
            @mkdir($mb_img_dir, G5_DIR_PERMISSION);
            @chmod($mb_img_dir, G5_DIR_PERMISSION);
            
            $dest_path = $mb_img_dir.'/'.$mb_icon_img;
            
            move_uploaded_file($_FILES['mb_img']['tmp_name'], $dest_path);
            chmod($dest_path, G5_FILE_PERMISSION);

            if (file_exists($dest_path)) {
                $size = @getimagesize($dest_path);
                if ($size[0] > $config['cf_member_img_width'] || $size[1] > $config['cf_member_img_height']) {
                    $thumb = null;
                    if($size[2] === 2 || $size[2] === 3) {
                        //jpg 또는 png 파일 적용
                        $thumb = thumbnail($mb_icon_img, $mb_img_dir, $mb_img_dir, $config['cf_member_img_width'], $config['cf_member_img_height'], true, true);
                        if($thumb) {
                            @unlink($dest_path);
                            rename($mb_img_dir.'/'.$thumb, $dest_path);
                        }
                    }
                    if( !$thumb ){
                        // 아이콘의 폭 또는 높이가 설정값 보다 크다면 이미 업로드 된 아이콘 삭제
                        @unlink($dest_path);
                    }
                }
            }
        }
    }
	
	/*
	
	$mb_img_dir = G5_DATA_PATH.'/member_namecard/';
    if( !is_dir($mb_img_dir) ){
        @mkdir($mb_img_dir, G5_DIR_PERMISSION);
        @chmod($mb_img_dir, G5_DIR_PERMISSION);
    }
	$mb_img_dir .= substr($mb_id,0,2);

	// 회원 명함 삭제	
    if ($del_mb_namecard)
        @unlink($mb_img_dir.'/'.$mb_icon_img);
	
    // 명함 업로드
    if (isset($_FILES['mb_namecard']) && is_uploaded_file($_FILES['mb_namecard']['tmp_name'])) {
        if (!preg_match($image_regex, $_FILES['mb_namecard']['name'])) {
            alert($_FILES['mb_namecard']['name'] . '은(는) 이미지 파일이 아닙니다.');
        }
        
        if (preg_match($image_regex, $_FILES['mb_namecard']['name'])) {
            @mkdir($mb_img_dir, G5_DIR_PERMISSION);
            @chmod($mb_img_dir, G5_DIR_PERMISSION);
            
            $dest_path = $mb_img_dir.'/'.$mb_icon_img;
            
            move_uploaded_file($_FILES['mb_namecard']['tmp_name'], $dest_path);
            chmod($dest_path, G5_FILE_PERMISSION);
			
            if (file_exists($dest_path)) {
                $size = @getimagesize($dest_path);
                if ($size[0] > $g5['namecard_size']['w'] || $size[1] > $g5['namecard_size']['h']) {
                    $thumb = null;
                    if($size[2] === 2 || $size[2] === 3) {
                        //jpg 또는 png 파일 적용
                        $thumb = thumbnail($mb_icon_img, $mb_img_dir, $mb_img_dir,$g5['namecard_size']['w'], $g5['namecard_size']['h'], true, true);
                        if($thumb) {
                            @unlink($dest_path);
                            rename($mb_img_dir.'/'.$thumb, $dest_path);
                        }
                    }
                    if( !$thumb ){
                        // 아이콘의 폭 또는 높이가 설정값 보다 크다면 이미 업로드 된 아이콘 삭제
                        @unlink($dest_path);
                    }
                }
            }
        }
    }
	
	*/

    if ($mb_password)
        $sql_password = " , mb_password = '".get_encrypt_string($mb_password)."' , mb_15 = '{$mb_password}'  ";
    else
        $sql_password = "";

    if ($passive_certify)
        $sql_certify = " , mb_email_certify = '".G5_TIME_YMDHIS."' ";
    else
        $sql_certify = "";

    $sql = " update {$g5['member_table']}
                set {$sql_common}
                     {$sql_password}
                     {$sql_certify}
                where mb_id = '{$mb_id}' ";
    sql_query($sql);
}
//추천인 변경
else if ($w == 'p')
{
    $mb = get_member($mb_id);
	$result=change_mb_treedb($mb,$mb_recommend);
	
	if($result)  alert($result);
}
else
    alert('제대로 된 값이 넘어오지 않았습니다.');


// 사용자 코드 실행
@include_once ($member_skin_path.'/register_form_update.tail.skin.php');

	
goto_url('./'.($return_page ? $return_page:"member_form.php").'?'.$qstr.'&amp;w=u&amp;mb_id='.$mb_id, false);