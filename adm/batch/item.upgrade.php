<?php
include_once('./_common.php');


exit;

//회원 포인트 배치
if($is_admin!='super') die('권한 없음');

$sql_search = " ";

//총액
$total=sql_fetch(" select sum(ct_buy_price) tot_price from coin_item_cart_history where is_soled!='1' ");

echo "<strong>총액 : ".number_format2($total[tot_price]). "</strong> " ;

$sql = " select *, sum(if(cn_item='a',1,0)) cnt_a, sum(if(cn_item='b',1,0)) cnt_b, sum(if(cn_item='c',1,0)) cnt_c, sum(if(cn_item='d',1,0)) cnt_d, 
sum(ct_buy_price) tot_price from coin_item_cart_history where is_soled!='1' and ct_validdate >= '2020-06-8' group by smb_id  order by if(ct_validdate >= '2020-06-10',1,0) desc , min(ct_validdate) asc";

$result = sql_query($sql,1);

$days=1;
$cnt=1;
$days=1;
$sum=0;

$cart_cnt=1;
$price1=800;
$interest=20;

while($data=sql_fetch_array($result)) {
	
	if($data[mb_id]=='company3') continue;
	echo "<br>".$cnt.") $data[mb_id]  / $data[smb_id]  => ";	
	
	echo "a:".$data[cnt_a] . " / ". "b:".$data[cnt_b] . " /  ". "c:".$data[cnt_c] . " / "  ."d:".$data[cnt_d] . " / ";
	
	echo "총액:".number_format2($data[tot_price]) ;
	
	echo " / <strong>$days</strong> 보관  <br>";
	
	
	$div1=$data[tot_price]/$price1;
	
	for($i=1;$i <= $div1;$i++){
	
		//지급코드
		//$code= get_itemcode();	
		$code=date('ymdhis').sprintf("%08d",$cart_cnt).strtoupper(get_randstr(10));

		$validdate=date("Y-m-d",strtotime("+ {$days} days"));

		//예정가격
		$sell_price=floor( ($price1 + ($price1*$interest/100))*10 )/10;

		$sql="insert into {$g5['cn_item_cart']}
		set 
		code='$code',
		cn_item='e',
		mb_id='$data[mb_id]',
		smb_id='$data[smb_id]',
		fmb_id='admin',
		fsmb_id='admin',

		ct_buy_price='$price1',
		ct_sell_price='$sell_price',

		ct_class='1',
		ct_interest='$interest',
		ct_days='$days',
		ct_validdate='$validdate',

		ct_wdate=now()
		";
		
		echo "<strong style='color:red;' >".$price1."</strong>".$sql."<br>";
		//sql_query($sql,1);
		
		$cart_cnt++;
		
		$sum+=$price1;
		
		if($sum > $total[tot_price] * (0.25*$days)){
			$days++;	
		}
	
	}
	
	$price2=$data[tot_price]%$price1;
	
	if($price2 >= 30){
		
		if($price2 < 50) $price2 = 50;
		$price3=ceil($price2/10)*10;
		
		//지급코드
		//$code= get_itemcode();	
		$code=date('ymdhis').sprintf("%08d",$cart_cnt).strtoupper(get_randstr(10));

		$validdate=date("Y-m-d",strtotime("+ {$days} days"));

		//예정가격
		$sell_price=floor( ($price3 + ($price3*$interest/100))*10 )/10;

		$sql="insert into {$g5['cn_item_cart']}
		set 
		code='$code',
		cn_item='e',
		mb_id='$data[mb_id]',
		smb_id='$data[smb_id]',
		fmb_id='admin',
		fsmb_id='admin',

		ct_buy_price='$price3',
		ct_sell_price='$sell_price',

		ct_class='1',
		ct_interest='$interest',
		ct_days='$days',
		ct_validdate='$validdate',

		ct_wdate=now()
		";
		
		echo "<strong style='color:red;' >".$price3."</strong>".$sql."<br>";
		//sql_query($sql,1);
		
		$cart_cnt++;
		
		$sum+= $data[tot_price]%$price1;	
		
		
		//일자 증가
		if($sum > $total[tot_price] * (0.25*$days)){
			$days++;	
		}
		
	}
	
	$cnt++;
	
	

}

// 오토매칭 설정
//sql_query("update `coin_sub_account` set `ac_auto_e`='1' WHERE `ac_auto_a`='1' or `ac_auto_b`='1' or `ac_auto_c`='1' or `ac_auto_e`='1' ");