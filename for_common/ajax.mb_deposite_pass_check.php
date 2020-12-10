<?php
include_once('./_common.php');
$msg='';
$mb_id = trim($_POST['mb_id']);
$mb_deposite_pass= trim($_POST['mb_deposite_pass']);

if (!$mb_id)
       $msg="Member not found";

if (!$mb_deposite_pass)
       $msg="Please enter a password";

if ($member['mb_password'] != get_encrypt_string($mb_deposite_pass ) )
	$msg=" Password is wrong.";
	
 die($msg);	
?>