<?php
include_once('./_common.php');
$msg='';
$mb_id = trim($_POST['mb_id']);
$mb= get_member($mb_id);

if (!$mb)
       $msg="Member not found.";

 die($msg);	
?>