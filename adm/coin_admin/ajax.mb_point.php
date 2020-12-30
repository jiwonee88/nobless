<?php
include_once('./_common.php');
include_once(G5_LIB_PATH."/register.lib.php");

$mb_id = trim($_POST['mb_id']);

if (!exist_mb_id($mb_id))  die("null");

$point=get_mempoint($mb_id);

//기타 수당만 이전 가능
//die($point['enable']?$point['enable']:'0');
die($point['hold']?$point['hold']:'0');
?>