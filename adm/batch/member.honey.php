<?php
include_once('./_common.php');

exit;
//회원 포인트 배치
if($is_admin!='super') die('권한 없음');

//서브계정 비우기
//sql_query("truncate table coin_sub_account");

/*
sql_query("delete from `coin_pointsum` WHERE pt_coin='b'");
sql_query("delete from `coin_point_2007` WHERE pt_coin='b'");
sql_query("update `coin_sub_account` set ac_point_b='0' ");
sql_query("update `g5_member` set mb_point_free_b='0' ");
*./

	 $file = './member.honey.xls';

    include_once(G5_LIB_PATH.'/Excel/reader.php');

    $data = new Spreadsheet_Excel_Reader();

    // Set output Encoding.
    $data->setOutputEncoding('UTF-8');

    /***
    * if you want you can change 'iconv' to mb_convert_encoding:
    * $data->setUTFEncoder('mb');
    *
    **/

    /***
    * By default rows & cols indeces start with 1
    * For change initial index use:
    * $data->setRowColOffset(0);
    *
    **/



    /***
    *  Some function for formatting output.
    * $data->setDefaultFormat('%.2f');
    * setDefaultFormat - set format for columns with unknown formatting
    *
    * $data->setColumnFormat(4, '%.3f');
    * setColumnFormat - set format for column (apply only to number fields)
    *
    **/

    $data->read($file);

    /*


     $data->sheets[0]['numRows'] - count rows
     $data->sheets[0]['numCols'] - count columns
     $data->sheets[0]['cells'][$i][$j] - data from $i-row $j-column

     $data->sheets[0]['cellsInfo'][$i][$j] - extended info about cell

        $data->sheets[0]['cellsInfo'][$i][$j]['type'] = "date" | "number" | "unknown"
            if 'type' == "unknown" - use 'raw' value, because  cell contain value with format '0.00';
        $data->sheets[0]['cellsInfo'][$i][$j]['raw'] = value if cell without format
        $data->sheets[0]['cellsInfo'][$i][$j]['colspan']
        $data->sheets[0]['cellsInfo'][$i][$j]['rowspan']
    */

    error_reporting(E_ALL ^ E_NOTICE);

    $fail_od_id = array();
    $total_count = 0;
    $fail_count = 0;
    $succ_count = 0;
	$skip_count= 0;
	
	$member_count=0;	
	
	//print_r($data->sheets);
    // $i 사용시 ordermail.inc.php의 $i 때문에 무한루프에 빠짐
    for ($k = 2; $k <= $data->sheets[0]['numRows']; $k++) {
        $total_count++;

        $mb_id               = addslashes(trim($data->sheets[0]['cells'][$k][2]));
        $mb_name = addslashes(trim($data->sheets[0]['cells'][$k][1]));
        $amt          = addslashes(trim($data->sheets[0]['cells'][$k][4]));		
		echo $mb_id  ."/".$mb_name."/". $amt   ."<br>";
		
		if($amt==0) continue;
		
		//회원 검사
		//$mb=sql_fetch("select * from {$g5['member_table']} where mb_id='$mb_id' and mb_name='$mb_name' ");
		$mb=sql_fetch("select * from {$g5['member_table']} where mb_id='$mb_id'  ",1);

		if($mb[mb_id]){
			

				if($amt > 30000) $unit=2000;
				else  $unit=1000;

				$acc_count=floor($amt/$unit);
				$acc_remain=$amt%$unit;

				if($acc_remain < 200){
					$omake=$unit+$acc_remain;

				}else{
					$omake=$acc_remain;
					$acc_count++;
				}	

				echo "$cnt. $mb_id : 총금액 ".number_format($amt)." / 필요계정수 $acc_count / 단위금액 $unit / 오마케 $omake <br>"; 	

				if($amt==0) continue;

				//서브계정 생성
				for($i=1;$i <= $acc_count;$i++){

					$ac_id=$mb_id.'.'.sprintf("%02d",$i);			

					$temp=sql_fetch("select ac_id from  {$g5['cn_sub_account']} where mb_id='{$mb_id}' and ac_id='$ac_id'",1);
					if($temp[ac_id]!='') continue;

					$sql = " insert into {$g5['cn_sub_account']}
								set
								mb_id='{$mb_id}',
								ac_id='$ac_id',
								ac_active='1',
								ac_wdate=now()
								";			

					echo $sql ."<br>";
					sql_query($sql,1);	
				}	

				//서브계정 포인트 지
				for($i=1;$i <= $acc_count;$i++){

					$ac_id=$mb_id.'.'.sprintf("%02d",$i);	

					if($i < $acc_count) $amtv=$unit;
					else $amtv=$omake;

					//꿀단지 지급
					$content['pt_wallet']='free'; //지갑구
					$content['pt_coin']='b'; //화폐구분
					$content['amount']=$amtv;			
					$content['subject']='보유금액 꿀단지 변환';

					echo "<br>";
					
					//print_r($content);

					set_add_point('in',$mb,$ac_id,$mb_id,$content);		

					echo "<br>$i ---------- 지급 /$ac_id / $amtv  <br>"; 		

				}


				//회원별 최종 수당 정보 업데이트
				set_update_point($mb_id);

				$cnt++;

		}else{
			echo $mb_id  ."/".$mb_name."/". $amt   ." 회원없음<br>";
		}
		
		
	}