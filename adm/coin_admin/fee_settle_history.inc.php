<?php
$sql_search_add='';

if($sub_menu == "800100") $sql_search_add .= " and pkind='stake_re' ";
else if($sub_menu == "800110") $sql_search_add .= " and pkind='fee' ";
else if($sub_menu == "800120") $sql_search_add .= " and pkind='fee2' ";

$g5['title'] = "지급내역-";

if($sub_menu == "800100") $g5['title'].= "채굴";
else if($sub_menu == "800110") $g5['title'].= "추천인 롤업";
else if($sub_menu == "800120") $g5['title'].= "서브계정 롤업";

include "fee_list.inc.php";
