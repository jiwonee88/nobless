<?php
include_once('./_common.php');

//지갑 검사
$tokentype= trim($_POST['tokentype']);
$addr= trim($_POST['addr']);


 $return=check_coin_addr($tokentype,$addr);
 
 if( $return[0]==false) die($return[1]);
 
?>