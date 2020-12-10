<?php
include_once('./_common.php');

$result=get_wow_coin($data);

if($result[0]) echo "ok^";
else echo "fail^".$result[1];
