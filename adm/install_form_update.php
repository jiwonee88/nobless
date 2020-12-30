<?php
$sub_menu = "800100";
include_once('./_common.php');

$qstr.="&partner_stx=$partner_stx&cate1_stx=$cate1_stx";

if ($w == 'u' || $w == 'u1')
    check_demo();

auth_check($auth[$sub_menu], 'w');

check_admin_token();


if ($w == '' || $w == 'u') {

	if (!$_POST['rt_name']) { alert('채용공고명은 반드시 선택하세요.'); }

	//다중분류설정값
	if(is_array($_POST['rt_cate1'])) $rt_cate1_val=implode(",",$_POST['rt_cate1']);
	if(is_array($_POST['rt_cate2'])) $rt_cate2_val=implode(",",$_POST['rt_cate2']);
	if(is_array($_POST['rt_cate3'])) $rt_cate3_val=implode(",",$_POST['rt_cate3']);
	
	$sql_common = " 
				   
					rt_partner     = '{$_POST['rt_partner']}',
					rt_partner_ext     = ',{$_POST['rt_partner_ext']},',
					rt_code          = '{$_POST['rt_code']}',
					rt_name          = '{$_POST['rt_name']}',
					rt_prev          = '{$_POST['rt_prev']}',
					rt_url          = '{$_POST['rt_url']}',
					
					rt_stats= '{$_POST['rt_stats']}',
					rt_company= '{$_POST['rt_company']}',
					rt_comment= '{$_POST['rt_comment']}',				
					
					rt_sdate          = '{$_POST['rt_sdate']}',
					rt_edate          = '{$_POST['rt_edate']}',
					
					rt_cate1   = '{$rt_cate1_val}',
					rt_cate2   = '{$rt_cate2_val}',
					rt_cate3   = '{$rt_cate3_val}',
					
					rt_view      = '{$_POST['rt_view']}',
					rt_enable     = '{$_POST['rt_enable']}',
					rt_main     = '{$_POST['rt_main']}',
					rt_order            = '{$_POST['rt_order']}',
					 
					rt_content_info     = '{$_POST['rt_content_info']}',
					rt_mobile_content_info     = '{$_POST['rt_mobile_content_info']}',
					
					rt_charge          = '{$_POST['rt_charge']}',
					rt_charge_tel          = '{$_POST['rt_charge_tel']}',
					rt_charge_email          = '{$_POST['rt_charge_email']}',
					
					rt_memo= '{$_POST['rt_memo']}',
					
					rt_maxapp= '{$_POST['rt_maxapp']}',
					
					rt_mdate               = now() ";
}

if ($w == '') {

    $sql = " insert into {$g5['recruit_table']}
                set 
                    rt_wdate = now(),
					rt_cdate = now(),
					rt_ipaddr = '{$_SERVER[REMOTE_ADDR]}',
					
                    $sql_common ";
					
    sql_query($sql);
	
	$rt_no=sql_insert_id();
	

} else if ($w == 'u') {
	
	$data=get_recruit($rt_no);
	
	//상태변경일	
	if($data['rt_stats']!=$_POST['rt_stats']) $sql_common.=",rt_cdate=now() ";
	
    $sql = " update {$g5['recruit_table']} set 
	          {$sql_common}
              where rt_no = '{$rt_no}' ";
    sql_query($sql);

//채용DB목록에서 바로 수정
} else if ($w == 'u1') {
	
	$data=get_recruit($rt_no);
	
	//상태변경일	
	if($data['rt_stats']!=$_POST['rt_stats']) $sql_common.=",rt_cdate=now() ";
	
    $sql = " update {$g5['recruit_table']} set 
				rt_sdate          = '{$_POST['rt_sdate']}',
				rt_edate          = '{$_POST['rt_edate']}',
				rt_stats	= '{$_POST['rt_stats']}'			

	    	      {$sql_common}
              where rt_no = '{$rt_no}' ";
    $re=sql_query($sql);
	
	
	if($re) $result["msg"]            = "OK";
	else  $result["msg"]            = "ERROR";
	
	$json = json_encode($result);
	echo unistr_to_xnstr($json);
	exit;
}

$img_dir = G5_DATA_PATH.'/recruit/'.$rt_no;

if( !is_dir($img_dir) ){
	@mkdir($img_dir, G5_DIR_PERMISSION);
	@chmod($img_dir, G5_DIR_PERMISSION);
}

// 이미지 삭제
if ($del_rt_img_list){
	@unlink(G5_DATA_PATH.'/'.$data['rt_img_list']);
	sql_query(" update {$g5['recruit_table']} set rt_img_list='' where rt_no = '{$rt_no}'  ");
}

// 이미지 삭제
if ($del_rt_img_detail){
	@unlink(G5_DATA_PATH.'/'.$data['rt_img_detail']);
	sql_query(" update {$g5['recruit_table']} set rt_img_detail='' where rt_no = '{$rt_no}'  ");
}


$chars_array = array_merge(range(0,9), range('a','z'), range('A','Z'));

// 업로드
foreach(array("rt_img_list","rt_img_detail") as $fname){
	
	if (isset($_FILES[$fname]) && is_uploaded_file($_FILES[$fname]['tmp_name'])) {
			
		 // 아래의 문자열이 들어간 파일은 -x 를 붙여서 웹경로를 알더라도 실행을 하지 못하도록 함
		$filename = preg_replace("/\.(php|pht|phtm|htm|cgi|pl|exe|jsp|asp|inc)/i", "$0-x", $_FILES[$fname]['name']);

		shuffle($chars_array);
		$shuffle = implode('', $chars_array);

		// 공백삭제
		$_dert_file = abs(ip2long($_SERVER['REMOTE_ADDR'])).'_'.substr($shuffle,0,8).'_'.replace_filename($filename);

		$dert_file = G5_DATA_PATH.'/recruit/'.$rt_no."/".$_dert_file;
		
	
		move_uploaded_file($_FILES[$fname]['tmp_name'], $dert_file);
		chmod($dert_file, G5_FILE_PERMISSION);
		
		$fset=" $fname='recruit/{$rt_no}/{$_dert_file}' ";
		
		sql_query(" update {$g5['recruit_table']} set $fset where rt_no = '{$rt_no}'  ");
		if($data[$fname]) @unlink($img_dir.'/'.$data[$fname]);		
		
	}
}

// 업로드
foreach(array("rt_file1","rt_file2") as $fname){
	
	if (isset($_FILES[$fname]) && is_uploaded_file($_FILES[$fname]['tmp_name'])) {
			
		 // 아래의 문자열이 들어간 파일은 -x 를 붙여서 웹경로를 알더라도 실행을 하지 못하도록 함
		$filename = preg_replace("/\.(php|pht|phtm|htm|cgi|pl|exe|jsp|asp|inc)/i", "$0-x", $_FILES[$fname]['name']);

		shuffle($chars_array);
		$shuffle = implode('', $chars_array);

		// 공백삭제
		$_dert_file = abs(ip2long($_SERVER['REMOTE_ADDR'])).'_'.substr($shuffle,0,8).'_'.replace_filename($filename);

		$dert_file = G5_DATA_PATH.'/recruit/'.$rt_no."/".$_dert_file;
		
	
		move_uploaded_file($_FILES[$fname]['tmp_name'], $dert_file);
		chmod($dert_file, G5_FILE_PERMISSION);
		
		$fset=" $fname='recruit/{$rt_no}/{$_dert_file}', ".$fname."v='".$_FILES[$fname]['name']."' ";
		
		sql_query(" update {$g5['recruit_table']} set $fset where rt_no = '{$rt_no}'  ");
		if($data[$fname]) @unlink($img_dir.'/'.$data[$fname]);		
		
	}
}



if(function_exists('get_admin_captcha_by'))
    get_admin_captcha_by('remove');

goto_url("./recruit_form.php?w=u&rt_no={$rt_no}&amp;{$qstr}");
?>