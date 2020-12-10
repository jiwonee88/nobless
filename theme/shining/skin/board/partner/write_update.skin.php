<?
delete_cache_latest($bo_table);

if ($file_upload_msg)
    alert($file_upload_msg, G5_HTTP_BBS_URL.'/write.php?bo_table='.$bo_table);

if($w != 'u') {
	alert("문의사항이 접수되었습니다. 빠른시간내에 연락드리겠습니다.", G5_HTTP_BBS_URL.'/write.php?bo_table='.$bo_table);
}

exit;