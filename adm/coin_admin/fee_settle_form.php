<?php
error_reporting(E_ALL);

ini_set("display_errors", 1);

$sub_menu = "800000";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'w');

$html_title = '정산실행';

$data[sw_set_token]='e';

$g5['title'] = $html_title;
include_once('../admin.head.pop.php');
include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');
?>
<form name="fcommonform" id="fcommonform" action="<?=$_SERVER[SCRIPT_NAME]?>" onsubmit="return fcommonform_submit(this)" method="post" >
<input type="hidden" name="w" value="p">
<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
<input type="hidden" name="stx" value="<?php echo $stx ?>">
<input type="hidden" name="sst" value="<?php echo $sst ?>">
<input type="hidden" name="sod" value="<?php echo $sod ?>">
<input type="hidden" name="page" value="<?php echo $page ?>">

<input type="hidden" name="token" value="">
<?=$common_form?>
<section id="anc_rt_basic">

    <div class="tbl_frm01 tbl_wrap">
        <table>
<tr>
<th scope="row"><label for="f_date">정산일자</label></th>
<td><input type="text" name="f_date" value="<?php echo $f_date?$f_date : date("Y-m-d")?>" id="f_date"  class="frm_input calendar-input" size="25" autocomplete='off' /></td>
</tr>
         <colgroup>
            <col class="grid_4">
            <col>
            <col class="grid_3">
        </colgroup>
         
          <tr>
            <th scope="row"><label for="sw_stats">정산구분</label></th>
		<td><label><input type="radio" name="f_kind" id="radio" value="fee" <?=$f_kind=='fee'?'checked':''?> > 
추천인 롤업 정산</label>&nbsp;&nbsp;
		<label><input type="radio" name="f_kind" id="radio" value="fee2" <?=$f_kind=='fee2'?'checked':''?> > 서브계정 롤럽 정산</label>

			</td>
          </tr>
              
        <tbody>

        </tbody>
        </table>
    </div>
</section>

<div class="text-center">
	<input type="submit" value="결과 미리보기" class="btn_submi btn btn_02" onclick="document.pressed=this.value" accesskey="s">
    <input type="submit" value="정산실행" class="btn_submi btn btn_01" onclick="document.pressed=this.value" accesskey="s">
</div>

</form>
<style>
.result-window{width:100%;height:500px;overflow-y:scroll;border:1px solid #dddddd;margin-top:30px;line-height:1.8em;font-size:1.1em;padding:10px;}
.result-window > p:hover{background:rgba(50,50,100,0.1);cursor:pointer;}
.result-window p.result{font-size:1.3em;padding:5px 0;}
</style>

<div class='result-window' >
<?

	
$f_kind=$_POST[f_kind];
 
if( $f_kind=='fee' || $f_kind=='fee2'){

	if ( !preg_match('/^(\d{4})-?(\d{2})-?(\d{2})$/',$f_date,$match) || !checkdate($match[2],$match[3],$match[1]) ) {
		echo "<p><strong class='text-danger'>".$f_date." 일자형식이 옳바르지 않습니다</strong></p>";
		$w='e';
	}
	
	
	$max_step=0;
	$max_step_cnt=15;	// 최대 15명까지 체크
	for($i=1;$i <= $max_step_cnt;$i++){	
		
		if($cset['pr_rup_bs'.$i.'_r']!='0'){
			$max_step=$cset['pr_rup_bs'.$i.'_step'];
		}		
	}	
	

	//단계별 지급
	function cnt_rollup($step,$amt){
		global $cset;

		$benefit=0;
		
		for($i=1;$i <= 15;$i++){	
			if($cset['pr_rup_bs'.$i.'_step']!=0 && $cset['pr_rup_bs'.$i.'_step'] <= $step){
				$r=$cset['pr_rup_bs'.$i.'_r'];	
			}else break;
		}		

		if($r > 0) $benefit=$amt*$r/100;
		else $benefit=0;

		return $benefit;

	}

	
}

/***************************************************
추천인 롤업 리워드
***************************************************/

if($f_kind=='fee'){
	
	//포인트 테이
	$point_table=$g5['cn_point']."_".date('ym',strtotime($f_date));
	$table_chk=chk_table($point_table);
	
	if ( !$table_chk ) {
		echo "<p><strong class='text-danger'>".$f_date.$point_table. " 해당일에 지급대상이 없습니다</strong></p>";
		$w='e';
	}
	
	/*
	//추천일별 단계
	$max_step_arr=array();
	$max_step_base=$prev_step=1;
	$max_step_cnt=15;	// 최대 15명까지 체크
	for($i=1;$i <= $max_step_cnt;$i++){	
		
		if($cset['pr_rup_bs'.$i.'_step']!='0'){
			$max_step_arr[$i]=$cset['pr_rup_bs'.$i.'_step'];
			$max_step_base=max($max_step_base,$cset['pr_rup_bs'.$i.'_step']);
		}
		else $max_step_arr[$i]=$prev_step;
		
		$prev_step=$cset['pr_rup_bs'.$i.'_step'];
	}
	
	//단계별 지급
	function cnt_rollup($step,$amt){
		global $cset;
			
		$benefit=0;
		for($i=1;$i <= 15;$i++){
			if($cset['pr_rup_bs'.$i.'_step'] !=0 && $cset['pr_rup_bs'.$i.'_step'] <= $step){
				$r=$cset['pr_rup_bs'.$i.'_r'];
				if($r > 0) $benefit=$amt*$r/100;
				else $benefit=0;
				
				return $benefit;
			}
		}
		return $benefit;
	}
	*/
	
	
	if( $w=='u' ){				
		$rdata=sql_fetch("select * from {$g5['cn_set_table']} where st_pkind ='fee' and st_date='$f_date'  ",1);

		if($rdata['st_no']){
			echo "<p><strong class='text-danger'>".$f_date." 일자의 정산 내역이 이미 있습니다. 기존 정산을 삭제후 시도해 주십시요</strong></p>";
			$w='e';
		}	
	}	
	
	
	
	if( $w=='p'|| $w=='u' ){
		
		if( $w=='u' ){		
			sql_query("insert into {$g5['cn_set_table']} set st_pkind ='fee',st_date='$f_date',st_wdate=now()",1);
			$st_no=sql_insert_id();
		}
		
		$ttot_amt=$st_amt_tot=$st_fee_tot=$st_fee2_tot=$st_cnt_tot=$tst_fee=$tst_fee2=$tst_fee_cnt=$tst_fee2_cnt=0;


		//추천인 목록
		$recommend_arr=array();
		$recommend_cnt=0;
		$recommend_cnt_valid=0;
		
		
		//회원의 수수료 량
		$sql="select a.*,b.mb_id bmb_id,b.step step

		from  {$point_table}  as a 
		left outer join  {$g5['cn_tree']} as b on(a.mb_id=b.smb_id)		
		where a.wdate  between'$f_date 00:00:00' and  '$f_date 23:59:59' and  (a.pkind='mfee' or a.pkind='mfee2' ) and  b.step <= $max_step group by a.pt_no order by b.step asc";
	
		$re2= sql_query($sql,1);
		while($data2=sql_fetch_array($re2)){
			
			//if($data2[bmb_id]=='simon01') {
			//echo "<pre>";
			//print_r($data2);
			//echo "</pre>";
			//}
			$recommend_arr[$data2[bmb_id]][$data2[mb_id]]['step']=$data2['step'];	

			if($data2[pkind]=='mfee'){
				$recommend_arr[$data2[bmb_id]][$data2[mb_id]]['fee']+=abs($data2['amount']);
				$recommend_arr[$data2[bmb_id]][$data2[mb_id]]['fee_cnt']++;
			}else if($data2[pkind]=='mfee2'){
				$recommend_arr[$data2[bmb_id]][$data2[mb_id]]['fee2']+=abs($data2['amount']);
				$recommend_arr[$data2[bmb_id]][$data2[mb_id]]['fee2_cnt']++;			
			} 
		}
		
		//print_r($recommend_arr);		
		$fee=$fee2=$cnt=0;
		
		//최대 롤업 단		
		foreach($recommend_arr as $bmb_id => $val_arr){	
			
			$cnt++;
			
			$recommend_cnt=0;
			$tot_coin=$st_amt=$st_fee=$st_fee2=$st_fee_cnt=$st_fee2_cnt=0;	

			foreach($val_arr as $mb_id => $val){	

				//최대 롤업 단계
				if($val['step'] > $max_step )  continue;					

				//롤업수당
				$fee=cnt_rollup($val['step'],$val['fee']);		
				$fee2=cnt_rollup($val['step'],$val['fee2']);	
				
				//echo $val['step'] .'/'.$val[fee].'/'.$fee.'//'.$val[fee2].'/'.$fee2."<br>"	;
				//롤업수당 coin
				$tot_coin+=$fee + $fee2;				

				$st_fee+=$val['fee'];	
				$st_fee2+=$val['fee2'];
				$st_fee_cnt+=$val['fee_cnt'];	
				$st_fee2_cnt+=$val['fee2_cnt'];
				
				$tst_fee+=$val['fee'];	
				$tst_fee2+=$val['fee2'];
				$tst_fee_cnt+=$val['fee_cnt'];	
				$tst_fee2_cnt+=$val['fee2_cnt'];				
				
				$recommend_cnt++;				
			}

			$lines="<p><strong>".($cnt)."</strong>. ".$bmb_id." =&gt; 해당 추천인수 :  <strong class='text-primary'>".number_format($recommend_cnt)."</strong>";			

			$lines.="	/  총 판매수수료 : <strong class='text-primary'>".number_format2($st_fee2,2) .($g5['cn_cointype'][$g5['cn_fee_coin']])." </strong> ({$st_fee2_cnt}건) ";
			$lines.="	/  총 구매수수료 : <strong class='text-primary'>".number_format2($st_fee,2) .($g5['cn_cointype'][$g5['cn_fee_coin']])." </strong> ({$st_fee_cnt}건) ";

			$lines.=" / 리워드 지급 => ";


			if( $tot_coin > 0 ){

				$st_cnt_tot++;
								
				//코인 컨버팅
				if($g5['cn_fee_coin'] != $g5['cn_reward_coin']){				
					$tot_coin=swap_coin($tot_coin,$g5['cn_fee_coin'],$g5['cn_reward_coin'],$sise);	
					
				}
				$ttot_coin+=$tot_coin;
				
				$lines.="<strong class='text-primary'>".($g5['cn_cointype'][$g5['cn_reward_coin']])." ".number_format2($tot_coin,6)." .....OK</strong> ";

				//정산 실행시 입력
				if( $w=='u' ){

					$mb['mb_id']=$bmb_id;					

					//포인트 입력
					$content['link_no']=$st_no; //관련 내역
					$content['pt_wallet']='free'; //지갑구분
					$content['pt_coin']=$g5['cn_reward_coin']; //지급 코인
					$content['amount']=$tot_coin;
					$content['subject']=strip_tags($lines);		
					set_add_point('fee',$mb,$bmb_id,$member[mb_id],$content);		
				}
			}else{

				$lines.="<strong class='text-danger'> ".number_format2($tot_coin,6)." .....SKIP</strong> ";

			}		
			
			
			echo $lines;	
		
		}//foreach($recommend_arr as $bmb_id => $val_arr){		

		$lines="<p style='border-bottom : 1px dashed #dddddd;' ></p><p class='result' >".($w!='u'?'미리보기 ':'')."정산결과  :  적용회원 <strong class='text-danger'>".number_format($st_cnt_tot)."</strong>명 / 총 지급액 <strong class='text-danger'>".number_format2($ttot_coin,6)."</strong> ".($g5['cn_cointype'][$g5['cn_reward_coin']]);
		$lines.="	/  총 판매수수료 : <strong class='text-primary'>".number_format2($tst_fee2,2) .($g5['cn_cointype'][$g5['cn_fee_coin']])." </strong> ({$tst_fee2_cnt}건) ";
		$lines.="	/  총 구매수수료 : <strong class='text-primary'>".number_format2($tst_fee,2) .($g5['cn_cointype'][$g5['cn_fee_coin']])." </strong> ({$tst_fee_cnt}건) ";

		$lines.="	</p>";

		echo $lines;

		if( $w=='u' ){

			//정산 결과 입력
			$result=sql_query("update {$g5['cn_set_table']} set 
			`st_pkind` ='fee',
			`st_token` ='{$g5['cn_reward_coin']}',
			`st_amt` ='$ttot_coin',
			`st_usd`  ='',
			
			`st_cnt`  ='$st_cnt_tot',
			`st_log` ='".addslashes(strip_tags($lines))."',
			`st_date` ='$f_date',
			`st_wdate` =now(),
			`st_result` ='1'

			where st_no='$st_no'");

		}
		
	}

}
	

/***************************************************
서브계정 롤업 리워드 - 1단계 추천인으로 계산
***************************************************/

if($f_kind=='fee2'){
	
	//포인트 테이
	$point_table=$g5['cn_point']."_".date('ym',strtotime($f_date));
	$table_chk=chk_table($point_table);
	
	if ( !$table_chk ) {
		echo "<p><strong class='text-danger'>".$f_date.$point_table. " 해당일에 지급대상이 없습니다</strong></p>";
		$w='e';
	}
		
	
	if( $w=='u' ){				
		$rdata=sql_fetch("select * from {$g5['cn_set_table']} where st_pkind ='fee2' and st_date='$f_date'  ",1);

		if($rdata['st_no']){
			echo "<p><strong class='text-danger'>".$f_date." 일자의 정산 내역이 이미 있습니다. 기존 정산을 삭제후 시도해 주십시요</strong></p>";
			$w='e';
		}	
	}	
	
	
	
	if( $w=='p'|| $w=='u' ){
		
		if( $w=='u' ){		
			sql_query("insert into {$g5['cn_set_table']} set st_pkind ='fee2',st_date='$f_date',st_wdate=now()",1);
			$st_no=sql_insert_id();
		}
		
		$ttot_amt=$st_amt_tot=$st_fee_tot=$st_fee2_tot=$st_cnt_tot=$tst_fee=$tst_fee2=$tst_fee_cnt=$tst_fee2_cnt=0;


		//서브계정 목록
		$recommend_arr=array();
		$recommend_cnt=0;
		$recommend_cnt_valid=0;
		
		
		//회원의 수수료 량
		$sql="select a.*,b.mb_id bmb_id
		from  {$point_table}  as a 
		left outer join  {$g5['cn_sub_account']} as b on(a.smb_id=b.ac_id)		
		where a.wdate  between'$f_date 00:00:00' and  '$f_date 23:59:59' and  (a.pkind='mfee' or a.pkind='mfee2' ) and  b.mb_id!=b.ac_id group by a.pt_no order by b.mb_id asc";
	
		//echo $sql;
		$re2= sql_query($sql,1);
		while($data2=sql_fetch_array($re2)){

			//echo "<pre>";
			//print_r($data2);
			//echo "</pre>";
			
			if($data2[bmb_id]==$data2[smb_id]) continue;
			
			//echo $data2[bmb_id]."/".$data2[smb_id]."/".$data2['amount']."<br>";
			
			if($data2[pkind]=='mfee'){
				$recommend_arr[$data2[bmb_id]][$data2[smb_id]]['fee']+=abs($data2['amount']);
				$recommend_arr[$data2[bmb_id]][$data2[smb_id]]['fee_cnt']++;
				
			}else if($data2[pkind]=='mfee2'){
				$recommend_arr[$data2[bmb_id]][$data2[smb_id]]['fee2']+=abs($data2['amount']);
				$recommend_arr[$data2[bmb_id]][$data2[smb_id]]['fee2_cnt']++;			
			}  
		
		}
		
		//print_r($recommend_arr);
		
		$fee=$fee2=$cnt=0;
		
		foreach($recommend_arr as $bmb_id => $val_arr){	
			
			$cnt++;
			
			$recommend_cnt=0;
			$tot_coin=$st_amt=$st_fee=$st_fee2=$st_fee_cnt=$st_fee2_cnt=0;	

			foreach($val_arr as $mb_id => $val){	

			
				//롤업수당usd
				$fee=cnt_rollup(1,$val['fee']);		
				$fee2=cnt_rollup(1,$val['fee2']);		

				//echo $val['step'] .'/'.$fee.'/'.$fee2	;
				//롤업수당 coin
				$tot_coin+=$fee + $fee2;				

				$st_fee+=$val['fee'];	
				$st_fee2+=$val['fee2'];
				$st_fee_cnt+=$val['fee_cnt'];	
				$st_fee2_cnt+=$val['fee2_cnt'];
				
				$tst_fee+=$val['fee'];	
				$tst_fee2+=$val['fee2'];
				$tst_fee_cnt+=$val['fee_cnt'];	
				$tst_fee2_cnt+=$val['fee2_cnt'];				
				
				$recommend_cnt++;				
			}

			$lines="<p><strong>".($cnt)."</strong>. ".$bmb_id." =&gt; 해당 서브계정수 :  <strong class='text-primary'>".number_format($recommend_cnt)."</strong>";			

			$lines.="	/  총 판매수수료 : <strong class='text-primary'>".number_format2($st_fee2,2) .($g5['cn_cointype'][$g5['cn_fee_coin']])." </strong> ({$st_fee2_cnt}건) ";
			$lines.="	/  총 구매수수료 : <strong class='text-primary'>".number_format2($st_fee,2) .($g5['cn_cointype'][$g5['cn_fee_coin']])." </strong> ({$st_fee_cnt}건) ";

			$lines.=" / 리워드 지급 => ";


			if( $tot_coin > 0 ){

				$st_cnt_tot++;
				
				//코인 컨버팅
				if($g5['cn_fee_coin'] != $g5['cn_reward_coin']){				
					$tot_coin=swap_coin($tot_coin,$g5['cn_fee_coin'],$g5['cn_reward_coin'],$sise);						
				}
				$ttot_coin+=$tot_coin;
				
				$lines.="<strong class='text-primary'>".($g5['cn_cointype'][$g5['cn_reward_coin']])." ".number_format2($tot_coin,6)." .....OK</strong> ";

				//정산 실행시 입력
				if( $w=='u' ){

					$mb['mb_id']=$bmb_id;					

					//포인트 입력
					$content['link_no']=$st_no; //관련 내역
					$content['pt_wallet']='free'; //지갑구분
					$content['pt_coin']=$g5['cn_reward_coin']; //지급 코인
					$content['amount']=$tot_coin;
					$content['subject']=strip_tags($lines);		
					set_add_point('fee2',$mb,$bmb_id,$member[mb_id],$content);		
				}
			}else{

				$lines.="<strong class='text-danger'> ".number_format2($tot_coin,6)." .....SKIP</strong> ";

			}

			echo $lines;	
		
		}//foreach($recommend_arr as $bmb_id => $val_arr){		

		$lines="<p style='border-bottom : 1px dashed #dddddd;' ></p><p class='result' >".($w!='u'?'미리보기 ':'')."정산결과  :  적용회원 <strong class='text-danger'>".number_format($st_cnt_tot)."</strong>명 / 총 지급액 <strong class='text-danger'>".number_format2($ttot_coin,6)."</strong> ".($g5['cn_cointype'][$g5['cn_reward_coin']]);
		$lines.="	/  총 판매수수료 : <strong class='text-primary'>".number_format2($tst_fee2,2) .($g5['cn_cointype'][$g5['cn_fee_coin']])." </strong> ({$tst_fee2_cnt}건) ";
		$lines.="	/  총 구매수수료 : <strong class='text-primary'>".number_format2($tst_fee,2) .($g5['cn_cointype'][$g5['cn_fee_coin']])." </strong> ({$tst_fee_cnt}건) ";

		$lines.="	</p>";

		echo $lines;

		if( $w=='u' ){

			//정산 결과 입력
			$result=sql_query("update {$g5['cn_set_table']} set 
			`st_pkind` ='fee2',
			`st_token` ='{$g5['cn_reward_coin']}',
			`st_amt` ='$ttot_coin',
			`st_usd`  ='',
			
			`st_cnt`  ='$st_cnt_tot',
			`st_log` ='".addslashes(strip_tags($lines))."',
			`st_date` ='$f_date',
			`st_wdate` =now(),
			`st_result` ='1'

			where st_no='$st_no'");

		}
		
	}

}
	
	
?>
</div>

<script>  
$(document).ready(function(e) {
   	
});

function fcommonform_submit(f)
{	

	if(f.f_date.value == "") {
		alert('정산일자를 입력하세요');
		return false;
    }	
    if(document.pressed == "결과 미리보기") {
		f.w.value='p';
    }
	if(document.pressed == "정산실행") {
        if(!confirm("정산을 실행하시겠습니까?")) {
            return false;
        }
		f.w.value='u';
    }
    return true;
}
</script>

<?php
include_once('../admin.tail.pop.php');
?>
