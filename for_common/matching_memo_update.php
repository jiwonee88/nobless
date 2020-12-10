<?php
include_once('./_common.php');

$memo = $_POST['memo'];
$mb_id = $member['mb_id'];
$sql = "update g5_member set mb_message = '{$memo}' where mb_id = '{$mb_id}'";
sql_query($sql);
?>
<script>
location.href="/";
</script>
