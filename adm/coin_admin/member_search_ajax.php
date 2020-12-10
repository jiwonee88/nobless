<?php
header("Content-Type:text/html;charset=utf-8");
$sub_menu = "200100";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');

$sql_search = " where (1) ";

if ($stx) {
    $sql_search .= " and ( ";
    switch ($sfl) {
        case 'mb_point' :
            $sql_search .= " ({$sfl} >= '{$stx}') ";
            break;
        case 'mb_level' :
            $sql_search .= " ({$sfl} = '{$stx}') ";
            break;
        case 'mb_tel' :
        case 'mb_hp' :
            $sql_search .= " ({$sfl} like '%{$stx}') ";
            break;
        default :
            $sql_search .= " ({$sfl} like '%{$stx}%') ";
            break;
    }
    $sql_search .= " ) ";
}

if ($is_admin != 'super')
    $sql_search .= " and mb_level <= '{$member['mb_level']}' ";

if (!$sst) {
    $sst = "mb_datetime";
    $sod = "desc";
}

$sql_order = " order by {$sst} {$sod} ";

$result["msg"] = "";

$sql =" SELECT *  FROM  {$g5['member_table']}  $sql_search limit 100";
$re = sql_query($sql);

$cnt=0;
while($data=sql_fetch_array($re)){	
	$data['mb_password']='';
	$result['list'][$cnt]=$data;	
	$cnt++;
}
if($re) $result["msg"]            = "OK";
else  $result["msg"]            = "ERROR";

//error_log(print_r($sql, TRUE)); 
$json = json_encode($result);
echo $json;
?>