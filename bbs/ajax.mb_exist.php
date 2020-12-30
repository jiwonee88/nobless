<?php
include_once('./_common.php');

$mb_id = trim($_POST['reg_mb_id']);

if ($msg = exist_mb_id($mb_id))     die($msg);

?>