<?php
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
		<td>
		<label><input type="radio" name="f_kind" id="radio" value="stake"   <?=$f_kind=='stake' || $f_kind=='' ?'checked':''?> > Staking 채굴정산</label>&nbsp;&nbsp;
		<label><input type="radio" name="f_kind" id="radio" value="fee" <?=$f_kind=='fee'?'checked':''?> > Staking 롤업 정산</label>&nbsp;&nbsp;
		<label><input type="radio" name="f_kind" id="radio" value="fee2" <?=$f_kind=='fee2'?'checked':''?> > 직급보너스 정산</label>

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
if($f_kind=='stake' || $f_kind=='fee' || $f_kind=='fee2'){

	if ( !preg_match('/^(\d{4})-?(\d{2})-?(\d{2})$/',$f_date,$match) || !checkdate($match[2],$match[3],$match[1]) ) {
		echo "<p><strong class='text-danger'>".$f_date." 일자형식이 옳바르지 않습니다</strong></p>";
		$w='e';
	}

}

if($f_kind=='fee' || $f_kind=='fee2'){
	
	//롤업 수당 정산 내역이 있어야 계산 가능
	$sdata=sql_fetch("select * from {$g5['cn_set_table']} where st_pkind ='stake_re' and  st_date='$f_date' and st_result='1' ",1);

	if(!$sdata['st_no']){
		echo "<p><strong class='text-danger'>".$f_date." <strong>Staking 롤업수당 정산</strong> 및 <strong>직급수당 정산</strong>은 <strong>Staking  채굴 정산</strong>을 먼저 진행후 실행 가능합니다</strong></p>";
		$w='e';
	}	

}

/***************************************************
스테이킹 리워드
***************************************************/

if($f_kind=='stake'){
		
	if( $w=='u' ){				
		$sdata=sql_fetch("select * from {$g5['cn_set_table']} where st_pkind ='stake_re' and st_date='$f_date' ",1);

		if($sdata['st_no']){
			echo "<p><strong class='text-danger'>".$f_date." 일자의 정산 내역이 이미 있습니다. 기존 정산을 삭제후 시도해 주십시요</strong></p>";
			$w='e';
		}	
	}	

	//스테이킹 롤업
	if( $w=='p'|| $w=='u' ){
		
		if( $w=='u' ){		
			sql_query("insert into {$g5['cn_set_table']} set st_pkind ='stake_re' ,st_date='$f_date',st_wdate=now()",1);
			$st_no=sql_insert_id();
		}

		//스테이킹 내역
		$sql_common = " from {$g5['cn_stake_table']} a left outer join  {$g5['member_table']} as b on(a.mb_id=b.mb_id)";
		$sql_search = " where ( a.sk_sdate <= '$f_date 23:59:59' and a.sk_stats='2') or ( a.sk_sdate <= '$f_date 23:59:59' and a.sk_edate >= '$f_date')  group by a.mb_id";

		$sql = " select a.*,
		sum(if(sk_token='b',sk_amt,0)) bit_amt,
		sum(if(sk_token='e',sk_amt,0)) eth_amt,
		sum(if(sk_token='i',sk_amt,0)) iten_amt,
		sum(if(sk_token='u',sk_amt,0)) usdt_amt,

		b.mb_id,b.mb_email,b.mb_name,b.mb_grade,b.mb_recommend {$sql_common} {$sql_search} {$sql_order}";
		
		//echo $sql;
		$result = sql_query($sql,1);

		$list_num = $total_count - ($page - 1) * $rows;
		
		$st_amt=$st_usd=$st_amt_b=$st_amt_e=$st_amt_i=$st_amt_u=$st_cnt=0;

		for ($i=0,$j=1; $row=sql_fetch_array($result); $i++,$j++) {

			//스테이킹 총액
			$tot_stake_usd=swap_usd($row['bit_amt'],'b',$sise) + swap_usd($row['eth_amt'],'e',$sise) + swap_usd($row['iten_amt'],'i',$sise) + swap_usd($row['usdt_amt'],'u',$sise);

			$tot_coin=0;

			$lines="<p><strong>$j</strong>. ".$row[mb_email]." ({$row['mb_id']})  BIT : ".number_format2($row['bit_amt'],6)." /  ETH : ".number_format2($row['eth_amt'],6) ." /  ITEN : ".number_format2($row['iten_amt'],6) ." /  USDT : ".number_format2($row['usdt_amt'],6) ." / STAKING  :  <strong class='text-danger'>$".number_format2($tot_stake_usd,2)."</strong>";

			if($tot_stake_usd >=$cset['staking_amt']) {		
			
				//리워드 코인
				$tot_coin=number_format2($tot_stake_usd*($cset['staking_reward']/100)/$sise['sise_'.$g5['cn_reward_coin']],6);			
					
				//리워드 usd
				$tot_usd=number_format2($tot_stake_usd*($cset['staking_reward']/100),2);			


				$lines.=" / 리워드(채굴) => <strong class='text-primary'>".($g5['cn_cointype'][$g5['cn_reward_coin']])." ".$tot_coin." .....OK</strong> ";			

				$st_amt+=$tot_coin;
				$st_usd+=$tot_usd;
				
				$st_amt_b+=$row['bit_amt'];
				$st_amt_e+=$row['eth_amt'];
				$st_amt_i+=$row['iten_amt'];
				$st_amt_u+=$row['usdt_amt'];
				$st_cnt++;

			}else{
				$lines.=" / 리워드(채굴) => <strong class='text-danger'>$".number_format2($cset['staking_amt'],2)." 미만 .....SKIP</strong>  ";						
			}

			$lines.="</p>";
			
			echo $lines;
			
			//정산 실행시 리워드 지급
			if( $w=='u' && $tot_coin > 0 ){
				$mb['mb_id']=$row['mb_id'];
				$mb['mb_grade']=$row['mb_grade'];

				//포인트 입력
				$content['link_no']=$st_no; //지갑구
				$content['pt_wallet']='free'; //지갑구
				$content['pt_coin']=$g5['cn_reward_coin']; //화폐구분
				$content['amount']=$tot_coin;
				$content['usd']=$tot_usd;	
				$content['pdate']=$f_date;	
				$content['subject']=strip_tags($lines);		
				set_add_point('stake_re',$mb,'',$content);		
			}
			
		}
		
		$lines="<p style='border-bottom : 1px dashed #dddddd;' ></p><p class='result' >정산결과  :  적용회원 <strong class='text-danger'>".number_format($st_cnt)."</strong>명 / 총 스테이킹 BIT : ".number_format2($st_amt_b,6).",  ETH : ".number_format2($st_amt_e,6).",  ITEN : ".number_format2($st_amt_i,6).",  USDT : ".number_format2($st_amt_u,6)." / 총 지급수량 <strong class='text-danger'>".number_format2($st_amt,6)."</strong> ".($g5['cn_cointype'][$g5['cn_reward_coin']])." / 총 지급액 <strong class='text-danger'> $".number_format2($st_usd,2)."</strong> USD </p>";

		echo $lines;

		if( $w=='u' ){
		
			//정산 결과 입력
			$result=sql_query("update {$g5['cn_set_table']} set 
			`st_pkind` ='stake_re',	
			`st_token` ='{$g5['cn_reward_coin']}',
			`st_amt` ='$st_amt',
			`st_usd`  ='$st_usd',
			`st_amt_b` ='$st_amt_b',
			`st_amt_e` ='$st_amt_e',
			`st_amt_i`  ='$st_amt_i',
			`st_amt_u`  ='$st_amt_u',

			`st_cnt`  ='$st_cnt',
			`st_log` ='".addslashes(strip_tags($lines))."',
			`st_date` ='$f_date',
			`st_wdate` =now(),
			`st_result` ='1'

			where st_no='$st_no'");

		}
	}
}

/***************************************************
롤업 리워드
***************************************************/


if($f_kind=='fee'){
	
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
		
		$ttot_usd=$ttot_amt=$st_amt_tot=$st_usd_tot=$st_cnt_tot=$st_cnt_tot=0;

		//대상 선정
		$cnt=1;
		$re= sql_query("select mb_id from  {$g5['member_table']} where mb_10!='1' and mb_10!='2' ",1);
		while($data=sql_fetch_array($re)){

			//추천인 목록
			$recommend_arr=array();
			$recommend_cnt=0;
			$recommend_cnt_valid=0;

			$tot_coin=$st_amt=$st_usd=$st_cnt=0;	
			
			
			//대상의 하부 회원의 해당일자의 채굴량
			$re2= sql_query("select a.*,b.step, 
			
			abs(sum(if(pkind='stake' and pt_coin='b',amount,0))) bit_stake_amt,
			abs(sum(if(pkind='stake' and pt_coin='e',amount,0))) eth_stake_amt,
			abs(sum(if(pkind='stake' and pt_coin='i',amount,0))) iten_stake_amt,
			abs(sum(if(pkind='stake' and pt_coin='u',amount,0))) usdt_stake_amt,

			sum(if(pkind='stake_re' and pt_coin='b',amount,0)) bit_stake_re_amt,
			sum(if(pkind='stake_re' and pt_coin='e',amount,0)) eth_stake_re_amt,
			sum(if(pkind='stake_re' and pt_coin='i',amount,0)) iten_stake_re_amt,
			sum(if(pkind='stake_re' and pt_coin='u',amount,0)) usdt_stake_re_amt

			
			from  {$g5['cn_point']}  as a 
			left outer join  {$g5['cn_tree']} as b on(a.mb_id=b.smb_id)		
			where  (a.pkind='stake' or (a.pkind='stake_re' and a.pdate='$f_date' )) and  b.mb_id= '{$data['mb_id']}'  group by a.mb_id order by b.step asc",1);
			
			while($data2=sql_fetch_array($re2)){
				
				//스테이킹 총액
				$sum_stake_usd=swap_usd($data2['bit_stake_amt'],'b',$sise) + swap_usd($data2['eth_stake_amt'],'e',$sise) + swap_usd($data2['iten_stake_amt'],'i',$sise) + swap_usd($data2['usdt_stake_amt'],'u',$sise);
								
				//지정 금액 이상만 하부로 인정
				if($sum_stake_usd >= $cset['staking_ref_amt']){ 
					
					//echo "<pre>";
					//print_r($data2);
					//echo "</pre>";
				
					$recommend_arr[$data2[mb_id]]['step']=$data2[step];		
					
					$recommend_arr[$data2[mb_id]]['amt_b']=$data2['bit_stake_re_amt'];
					$recommend_arr[$data2[mb_id]]['amt_e']=$data2['eth_stake_re_amt'];
					$recommend_arr[$data2[mb_id]]['amt_i']=$data2['iten_stake_re_amt'];
					$recommend_arr[$data2[mb_id]]['amt_u']=$data2['usdt_stake_re_amt'];
					
					$recommend_arr[$data2[mb_id]]['usd']=swap_usd($data2['bit_stake_re_amt'],'b',$sise) + swap_usd($data2['eth_stake_re_amt'],'e',$sise) + swap_usd($data2['iten_stake_re_amt'],'i',$sise) + swap_usd($data2['usdt_stake_re_amt'],'u',$sise);

					//1대 추천인
					if($data2[step]=='1')  $recommend_cnt_valid++;
					$recommend_cnt++;
				}
				

			}

			$fee=0;
			$max_step=1;
			if($recommend_cnt_valid > $max_step_cnt) $max_step=$max_step_base;
			else $max_step=max($max_step_arr[$recommend_cnt_valid],$max_step);
			
			
			//최대 롤업 단		
			foreach($recommend_arr as $mb_id => $val){	
				
				//최대 롤업 단계
				if($val['step'] > $max_step )  continue;					
				
				//롤업수당usd
				$fee=cnt_rollup($val['step'],$val['usd']);		
				
				//롤업수당 coin
				$tot_coin+=swap_coin($fee,'d',$g5['cn_reward_coin'],$sise);				
				
				$ttot_coin+=$tot_coin;
				
				$st_b_amt+=$val['amt_b'];	
				$st_e_amt+=$val['amt_e'];	
				$st_i_amt+=$val['amt_i'];	
				$st_u_amt+=$val['amt_u'];	
				
			}
			
			//리워드 usd
			$tot_usd=number_format2($tot_coin*$sise['sise_'.$g5['cn_reward_coin']],2);		
			$ttot_usd+=$tot_usd;

			$lines="<p><strong>".($cnt++)."</strong>. ".$data[mb_email]." ({$data['mb_id']}) 1대 추천인수 :  <strong class='text-primary'>".number_format($recommend_cnt_valid)."</strong> / 전체 유효 추천인수 :  <strong class='text-primary'>".number_format($recommend_cnt)."</strong>";
			
			foreach($g5['cn_cointype'] as $k=>$v){
				$lines.="	/  총 {$v} 리워드(채굴) : <strong class='text-primary'>".number_format2(${'st_'.$k.'amt'},2) ."$v</strong>";
			}
			
			$lines.=" / 리워드 지급 => ";

			
			if( $tot_coin > 0 ){
				
				$st_cnt_tot++;
				
				$lines.="<strong class='text-primary'>".($g5['cn_cointype'][$g5['cn_reward_coin']])." ".number_format2($tot_coin,6)." .....OK</strong> ";

				//정산 실행시 입력
				if( $w=='u' && $tot_coin > 0 ){
				
					$mb['mb_id']=$data['mb_id'];
					$mb['mb_grade']=$data['mb_grade'];

					//포인트 입력
					$content['link_no']=$st_no; //관련 내역
					$content['pt_wallet']='free'; //지갑구분
					$content['pt_coin']=$g5['cn_reward_coin']; //지급 코인
					$content['amount']=$tot_coin;
					$content['usd']=$tot_usd;				
					$content['subject']=strip_tags($lines);		
					set_add_point('fee',$mb,'',$content);		
				}
			}else{
			
				$lines.="<strong class='text-danger'>ITEN ".number_format2($tot_coin,6)." .....SKIP</strong> ";

			}

			echo $lines;			
		}

		$lines="<p style='border-bottom : 1px dashed #dddddd;' ></p><p class='result' >".($w!='u'?'미리보기 ':'')."정산결과  :  적용회원 <strong class='text-danger'>".number_format($st_cnt_tot)."</strong>명 / 총 지급수량 <strong class='text-danger'>".number_format2($ttot_coin,6)."</strong> ".($g5['cn_cointype'][$g5['cn_reward_coin']])." / 총 지급액 <strong class='text-danger'> $".number_format2($ttot_usd,2)."</strong> USD  </p>";

		echo $lines;

		if( $w=='u' ){

			//정산 결과 입력
			$result=sql_query("update {$g5['cn_set_table']} set 
			`st_pkind` ='fee',
			`st_token` ='{$g5['cn_reward_coin']}',
			`st_amt` ='$ttot_coin',
			`st_usd`  ='$ttot_usd',
			
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
직급보너스
***************************************************/

if($f_kind=='fee2'){
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
		
		$ttot_usd=$ttot_amt=$st_amt_tot=$st_usd_tot=$st_cnt_tot=$st_cnt_tot=0;

		//대상 선정
		$cnt=1;
		$re= sql_query("select a.*,b.mb_id,b.mb_email, 
			
			abs(sum(if(pkind='stake' and pt_coin='b',amount,0))) bit_stake_amt,
			abs(sum(if(pkind='stake' and pt_coin='e',amount,0))) eth_stake_amt,
			abs(sum(if(pkind='stake' and pt_coin='i',amount,0))) iten_stake_amt,
			abs(sum(if(pkind='stake' and pt_coin='u',amount,0))) usdt_stake_amt,

			sum(if(pkind='stake_re' and pt_coin='b',amount,0)) bit_stake_re_amt,
			sum(if(pkind='stake_re' and pt_coin='e',amount,0)) eth_stake_re_amt,
			sum(if(pkind='stake_re' and pt_coin='i',amount,0)) iten_stake_re_amt,
			sum(if(pkind='stake_re' and pt_coin='u',amount,0)) usdt_stake_re_amt
			
			from  {$g5['cn_point']}  as a  left outer join  {$g5['member_table']} as b on(a.mb_id=b.mb_id) where a.pkind='stake' and b.mb_10!='1' and b.mb_10!='2' ",1);
			
		while($data=sql_fetch_array($re)){

			//추천인 목록
			$recommend_arr=array();
			$recommend_cnt=0;
			$recommend_cnt_valid=0;

			$tot_coin=$st_amt=$st_usd=$st_cnt=0;	
			
			
			//대상의 하부 회원의 해당일자의 채굴량
			$re2= sql_query("select a.*,b.step, 
			
			abs(sum(if(pkind='stake' and pt_coin='b',amount,0))) bit_stake_amt,
			abs(sum(if(pkind='stake' and pt_coin='e',amount,0))) eth_stake_amt,
			abs(sum(if(pkind='stake' and pt_coin='i',amount,0))) iten_stake_amt,
			abs(sum(if(pkind='stake' and pt_coin='u',amount,0))) usdt_stake_amt,

			sum(if(pkind='stake_re' and pt_coin='b',amount,0)) bit_stake_re_amt,
			sum(if(pkind='stake_re' and pt_coin='e',amount,0)) eth_stake_re_amt,
			sum(if(pkind='stake_re' and pt_coin='i',amount,0)) iten_stake_re_amt,
			sum(if(pkind='stake_re' and pt_coin='u',amount,0)) usdt_stake_re_amt

			
			from  {$g5['cn_point']}  as a 
			left outer join  {$g5['cn_tree']} as b on(a.mb_id=b.smb_id)		
			where  (a.pkind='stake' or (a.pkind='stake_re' and a.pdate='$f_date' )) and  b.mb_id= '{$data['mb_id']}'  group by a.mb_id order by b.step asc",1);
			
			while($data2=sql_fetch_array($re2)){
				
				//스테이킹 총액
				$sum_stake_usd=swap_usd($data2['bit_stake_amt'],'b',$sise) + swap_usd($data2['eth_stake_amt'],'e',$sise) + swap_usd($data2['iten_stake_amt'],'i',$sise) + swap_usd($data2['usdt_stake_amt'],'u',$sise);
								
				//지정 금액 이상만 하부로 인정
				if($sum_stake_usd >= $cset['staking_ref_amt']){ 
					
					//echo "<pre>";
					//print_r($data2);
					//echo "</pre>";
				
					$recommend_arr[$data2[mb_id]]['step']=$data2[step];		
					
					$recommend_arr[$data2[mb_id]]['amt_b']=$data2['bit_stake_re_amt'];
					$recommend_arr[$data2[mb_id]]['amt_e']=$data2['eth_stake_re_amt'];
					$recommend_arr[$data2[mb_id]]['amt_i']=$data2['iten_stake_re_amt'];
					$recommend_arr[$data2[mb_id]]['amt_u']=$data2['usdt_stake_re_amt'];
					
					$recommend_arr[$data2[mb_id]]['usd']=swap_usd($data2['bit_stake_re_amt'],'b',$sise) + swap_usd($data2['eth_stake_re_amt'],'e',$sise) + swap_usd($data2['iten_stake_re_amt'],'i',$sise) + swap_usd($data2['usdt_stake_re_amt'],'u',$sise);

					//1대 추천인
					if($data2[step]=='1')  $recommend_cnt_valid++;
					$recommend_cnt++;
				}
				

			}

			$fee=0;
			$max_step=1;
			if($recommend_cnt_valid > $max_step_cnt) $max_step=$max_step_base;
			else $max_step=max($max_step_arr[$recommend_cnt_valid],$max_step);
			
			
			//최대 롤업 단		
			foreach($recommend_arr as $mb_id => $val){	
				
				//최대 롤업 단계
				if($val['step'] > $max_step )  continue;					
				
				//롤업수당usd
				$fee=cnt_rollup($val['step'],$val['usd']);		
				
				//롤업수당 coin
				$tot_coin+=swap_coin($fee,'d',$g5['cn_reward_coin'],$sise);				
				
				$ttot_coin+=$tot_coin;
				
				$st_b_amt+=$val['amt_b'];	
				$st_e_amt+=$val['amt_e'];	
				$st_i_amt+=$val['amt_i'];	
				$st_u_amt+=$val['amt_u'];	
				
			}
			
			//리워드 usd
			$tot_usd=number_format2($tot_coin*$sise['sise_'.$g5['cn_reward_coin']],2);		
			$ttot_usd+=$tot_usd;

			$lines="<p><strong>".($cnt++)."</strong>. ".$data[mb_email]." ({$data['mb_id']}) 1대 추천인수 :  <strong class='text-primary'>".number_format($recommend_cnt_valid)."</strong> / 전체 유효 추천인수 :  <strong class='text-primary'>".number_format($recommend_cnt)."</strong>";
			
			foreach($g5['cn_cointype'] as $k=>$v){
				$lines.="	/  총 {$v} 리워드(채굴) : <strong class='text-primary'>".number_format2(${'st_'.$k.'amt'},2) ."$v</strong>";
			}
			
			$lines.=" / 리워드 지급 => ";

			
			if( $tot_coin > 0 ){
				
				$st_cnt_tot++;
				
				$lines.="<strong class='text-primary'>".($g5['cn_cointype'][$g5['cn_reward_coin']])." ".number_format2($tot_coin,6)." .....OK</strong> ";

				//정산 실행시 입력
				if( $w=='u' && $tot_coin > 0 ){
				
					$mb['mb_id']=$data['mb_id'];
					$mb['mb_grade']=$data['mb_grade'];

					//포인트 입력
					$content['link_no']=$st_no; //관련 내역
					$content['pt_wallet']='free'; //지갑구분
					$content['pt_coin']=$g5['cn_reward_coin']; //지급 코인
					$content['amount']=$tot_coin;
					$content['usd']=$tot_usd;				
					$content['subject']=strip_tags($lines);		
					set_add_point('fee',$mb,'',$content);		
				}
			}else{
			
				$lines.="<strong class='text-danger'>ITEN ".number_format2($tot_coin,6)." .....SKIP</strong> ";

			}

			echo $lines;			
		}

		$lines="<p style='border-bottom : 1px dashed #dddddd;' ></p><p class='result' >".($w!='u'?'미리보기 ':'')."정산결과  :  적용회원 <strong class='text-danger'>".number_format($st_cnt_tot)."</strong>명 / 총 지급수량 <strong class='text-danger'>".number_format2($ttot_coin,6)."</strong> ".($g5['cn_cointype'][$g5['cn_reward_coin']])." / 총 지급액 <strong class='text-danger'> $".number_format2($ttot_usd,2)."</strong> USD  </p>";

		echo $lines;

		if( $w=='u' ){

			//정산 결과 입력
			$result=sql_query("update {$g5['cn_set_table']} set 
			`st_pkind` ='fee',
			`st_token` ='{$g5['cn_reward_coin']}',
			`st_amt` ='$ttot_coin',
			`st_usd`  ='$ttot_usd',
			
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
