<?php
include_once('./_common.php');

$sql = " insert into coin_agreement_20200701
    set 
    mb_id		 = '{$member['mb_id']}',
    wdate = now()
    ON DUPLICATE KEY UPDATE wdate=now();
    ";

//echo $sql;
$result = sql_query($sql,1);

if($result) alert_json(true,'');
else alert_json(false,'등록할수 없습니다');

