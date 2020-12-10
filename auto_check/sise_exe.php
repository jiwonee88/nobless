<?php
/* 코인 시세 갱신 */
$exe_path=dirname(__FILE__);
include $exe_path."/../common.php";

//현재의 시세
$data=sql_fetch("select * from {$g5['cn_sise_table']} ");
if($data['is_flow']=='2') exit;

$datas=json_decode($data[data],true);

//웹페이지 텍스트 크롤링
function get_json($urls){

	/*	
	if( date("H") > 0 && date("H") <= 5) $key=$key1;
	else if( date("H") > 5 && date("H") <= 10) $key=$key2;
	else if( date("H") > 10 && date("H") <= 15) $key=$key3;
	else if( date("H") > 15 && date("H") <= 20 ) $key=$key4;
	else if( date("H") > 20 && date("H") <= 23 || date('H')=='00' ) $key=$key5;
	*/
	
	$key='b70a0368-80ba-4f92-8905-ee8ffac7cca5'; //boombinet@daum.net
	
	//echo $urls."&CMC_PRO_API_KEY=$key";
	$context=array( 
		"ssl"=>array( 
			"verify_peer"=>false, 
			"verify_peer_name"=>false, 
		), 
	); 

	$result = file_get_contents($urls."&CMC_PRO_API_KEY=$key",false, stream_context_create($context));
	
	return $result;
}

$html=get_json('https://pro-api.coinmarketcap.com/v1/cryptocurrency/listings/latest?sort=market_cap&start=1&limit=300&cryptocurrency_type=coins&convert=USD');
$html_obj=json_decode($html);

foreach($html_obj->data as $obj){

	$id=$obj->id;
	$symbol=$obj->symbol;
	$name=$obj->name;
	$usd_price=$obj->quote->USD->price;	

	//echo "<br> $id $symbol  $name $usd_price ";		
	
	if($id=='1'){		
		$datas['sise_b']=round(only_number($usd_price),2);
	}	

	if($id=='1027'){		
		$datas['sise_e']=round(only_number($usd_price),2);
	}	
}

$datas=json_encode($datas);

$sql =  "update  {$g5['cn_sise_table']} set data='".addslashes($datas)."',wdate=now() ";	
echo $sql;
$result = sql_query($sql,1);

