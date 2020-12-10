<?php
$sub_menu = '800100';
include_once('./_common.php');

check_demo();

auth_check($auth[$sub_menu], 'w');

check_admin_token();

if ($rt_name=='') {
    alert('채용명을 입력하세요');
}
$rt_no=$_POST['rt_no'];
$update_set='';
$data = sql_fetch("select * from {$g5['recruit_table']} where rt_no='{$rt_no}'") ;

foreach($data as $k=>$v){
	if($is_admin === 'super' || !$is_partner){
		if(in_array($k,array('rt_name','rt_no','rt_wdate','rt_mdate','rt_ipaddr','rt_hits'))) continue;
	}else{
		if(in_array($k,array('rt_name','rt_no','rt_wdate','rt_mdate','rt_ipaddr','rt_hits','rt_enable','rt_cate1','rt_main'))) continue;
	}
	
	$update_set.=",`$k`='".addslashes($v)."'";	
}

// 정보
$sql = " insert into {$g5['recruit_table']} set  `rt_name`='{$_POST['rt_name']}',rt_wdate=now(),rt_mdate=now() ,rt_ipaddr = '{$_SERVER[REMOTE_ADDR]}' $update_set" ;

sql_query($sql, 1);

$new_rt_no=sql_insert_id();

if(!$new_rt_no) alert('복사할수 없습니다');

//폴더 생성
@mkdir(G5_DATA_PATH.'/recruit/'.$new_rt_no, G5_DIR_PERMISSION);

$d = dir(G5_DATA_PATH.'/recruit/'.$rt_no);
while ($entry = $d->read()) {
	if ($entry == '.' || $entry == '..') continue;

	if(is_dir(G5_DATA_PATH.'/recruit/'.$rt_no.'/'.$entry)){
		$dd = dir(G5_DATA_PATH.'/recruit/'.$rt_no.'/'.$entry);
		@mkdir(G5_DATA_PATH.'/recruit/'.$new_rt_no.'/'.$entry, G5_DIR_PERMISSION);
		@chmod(G5_DATA_PATH.'/recruit/'.$new_rt_no.'/'.$entry, G5_DIR_PERMISSION);
		while ($entry2 = $dd->read()) {
			if ($entry2 == '.' || $entry2 == '..') continue;
			@copy(G5_DATA_PATH.'/file/'.$rt_no.'/'.$entry.'/'.$entry2, G5_DATA_PATH.'/file/'.$target_table.'/'.$entry.'/'.$entry2);
			@chmod(G5_DATA_PATH.'/file/'.$target_table.'/'.$entry.'/'.$entry2, G5_DIR_PERMISSION);
		}
		$dd->close();
	}
	else {
		@copy(G5_DATA_PATH.'/recruit/'.$rt_no.'/'.$entry, G5_DATA_PATH.'/recruit/'.$new_rt_no.'/'.$entry);
		@chmod(G5_DATA_PATH.'/recruit/'.$new_rt_no.'/'.$entry, G5_DIR_PERMISSION);
	}
}
$d->close();

//파일명 변경
foreach(array("rt_img_list","rt_img_detail") as $fname){
	if($data[$fname]){
		$_tmp=preg_replace("@recruit/$rt_no/@","recruit/$new_rt_no/",$data[$fname]);	
		sql_query(" update {$g5['recruit_table']} set `$fname`='$_tmp' where rt_no = '{$new_rt_no}'  ");
	}	
}

echo "<script>opener.document.location.href='./recruit_form.php?w=u&rt_no=".$new_rt_no."&".$qstr."'</script>";

alert("복사에 성공 했습니다.", './recruit_copy.php?rt_no='.$new_rt_no.'&amp;'.$qstr);
?>