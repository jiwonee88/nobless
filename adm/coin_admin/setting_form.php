<?php
$sub_menu = "500100";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'w');

$data= get_coinset();
$g5['title'] = "지급설정";

include_once ('../admin.head.php');

$pg_anchor = '<ul class="anchor">
    <li><a href="#anc_1">기본설정</a></li>
    <li><a href="#anc_2">추천보너스</a></li>    
	<li><a href="#anc_4">후원롤업보너스</a></li>
	</ul>
	';
	 
?>
<form name="set_form" id="set_form" action="./setting_form_update.php" onsubmit="return item_form_submit(this)" method="post" enctype="multipart/form-data">
<input type="hidden" name="w" value="u">
<input type="hidden" name="token" value="">

<?=$common_form?>

<section id="anc_1">
    <h2 class="h2_frm">기본설정</h2>
    <?php// echo $pg_anchor ?>

    <div class="tbl_frm01 tbl_wrap">
        <table class='w-auto' style='min-width:800px;'>
<tr>
<th colspan="2" scope="row" class="grid_2">가입시 지급</th>
<td><?
		  foreach($g5['cn_cointype'] as $k=>$v){?>
<?=$v?>
<input type="text" name="join_bonus_<?=$k?>" value="<?php echo number_format2($data['join_bonus_'.$k]) ?>" id="join_bonus_<?=$k?>"  class=" frm_input number-comma" size="15"  />
<?=$v?>
,&nbsp;&nbsp;
<? }?></td>
</tr>
<tr>
<th colspan="2" scope="row" class="grid_2">가입시 추천인 지급</th>
<td><?
		  foreach($g5['cn_cointype'] as $k=>$v){?>
<?=$v?>
<input type="text" name="promote_bonus_<?=$k?>" value="<?php echo number_format2($data['promote_bonus_'.$k]) ?>" id="promote_bonus_<?=$k?>"  class=" frm_input number-comma" size="15"  />
<?=$v?>
,&nbsp;&nbsp;
<? }?></td>
</tr>
<tr>
<th colspan="2" scope="row" class="grid_2">최대계정/1인</th>
<td>
<input type="text" name="max_account_lmt" value="<?php echo number_format2($data['max_account_lmt']) ?>" id="max_account_lmt"  class=" frm_input number-comma" size="15"  />
개&nbsp;&nbsp;
</td>
</tr>

<tr>
<th colspan="2" scope="row" class="grid_2">구매리워드</th>
<td>구매액의 
<input name="deposite_reward_r" type="text"  class=" frm_input number-comma" id="deposite_reward_r" value="<?php echo number_format2($data['deposite_reward_r']) ?>" size="15"  />
% 를 <strong>
<?=$g5[cn_cointype][$g5['cn_reward_coin']]?>
</strong>으로 지급(USD환산기준)
</td>
</tr>

<tr>
<th colspan="2" scope="row" class="grid_4">최소활성화 보유액</th>
<td><input type="text" name="staking_amt" value="<?php echo number_format2($data['staking_amt']) ?>" id="staking_amt"  class=" frm_input  number-comma " size="20"  />
<?=$g5['cn_cointype']['i']?></td>
</tr>
<tr>
<th colspan="2" scope="row" class="grid_4">최소  설정금액</th>
<td>$
<input type="text" name="min_sp_num" value="<?php echo number_format2($data['min_sp_num']) ?>" id="min_sp_num"  class=" frm_input  number-comma " size="20"  />
 </td>
</tr>
<tr>
<th colspan="2" scope="row" class="grid_4">최대  설정금액</th>
<td>$
<input type="text" name="max_sp_num" value="<?php echo number_format2($data['max_sp_num']) ?>" id="max_sp_num"  class=" frm_input  number-comma " size="20"  />
(0이면 무제한)
</td>
</tr>
<!--tr>
<th colspan="2" scope="row" class="grid_2">1일 출금 제한</th>
<td><?
		  foreach($g5['cn_cointype'] as $k=>$v){?>
<?=$v?>
<input type="text" name="max_out_<?=$k?>" value="<?php echo number_format2($data['max_out_'.$k]) ?>" id="max_out_<?=$k?>"  class=" frm_input number-comma" size="15"  />
이내,&nbsp;&nbsp;
<? }?></td>
</tr-->
          
        <caption>기본정보</caption>
        <colgroup>
            <col class="grid_2">
			<col class="grid_2">
            <col>
        </colgroup>
        <tbody>
        <tr>
          <th colspan="2" scope="row" class="grid_2">최소 출금 제한</th>
          <td>
          
          <?
		  foreach($g5['cn_cointype'] as $k=>$v){?>          
          <?=$v?> <input type="text" name="min_out_<?=$k?>" value="<?php echo number_format2($data['min_out_'.$k]) ?>" id="min_out_<?=$k?>"  class=" frm_input number-comma" size="15"  />
<?=$v?>
,&nbsp;&nbsp;
          <? }?>
          
          
            </td>
        </tr>
     	
		
		<tr>
          <th colspan="2" scope="row" class="grid_2">최소 이체 제한</th>
          <td>
          
          <?
		  foreach($g5['cn_cointype'] as $k=>$v){?>          
          <?=$v?> <input type="text" name="min_trans_<?=$k?>" value="<?php echo number_format2($data['min_trans_'.$k]) ?>" id="min_trans_<?=$k?>"  class=" frm_input number-comma" size="15"  />
<?=$v?>,&nbsp;&nbsp;
          <? }?>
          
          
            </td>
        </tr>
     		

	<tr>
	<th colspan="2"   scope="row"  > 출금 수수료</th>
	<td>
	<?
	  foreach($g5['cn_cointype'] as $k=>$v){?>   
	  <?=$v?> <input type="text" name="out_fee_<?=$k?>" value="<?php echo number_format2($data['out_fee_'.$k]) ?>" id="out_fee_<?=$k?>"  class=" frm_input " size="15"  />%,&nbsp;&nbsp;
	  <? }?>
	</td>
	</tr>

	
	
	
	<tr>
	<th colspan="2"   scope="row"  > 이체 수수료</th>
	<td>
	<?
	  foreach($g5['cn_cointype'] as $k=>$v){?>   
	  <?=$v?> <input type="text" name="trans_fee_<?=$k?>" value="<?php echo number_format2($data['trans_fee_'.$k]) ?>" id="trans_fee_<?=$k?>"  class=" frm_input " size="15"  />
<?=$v?>,&nbsp;&nbsp;
	  <? }?>
	</td>
	</tr>
	


	<tr>
	<th colspan="2"   scope="row"  > 스왑 수수료</th>
	<td>
	<?
	  foreach($g5['cn_cointype'] as $k=>$v){?>   
	  <?=$v?> <input type="text" name="swap_fee_<?=$k?>" value="<?php echo number_format2($data['swap_fee_'.$k]) ?>" id="swap_fee_<?=$k?>"  class=" frm_input " size="15"  />%,&nbsp;&nbsp;
	  <? }?>
	</td>
	</tr>
<!--tr>
<th colspan="2" scope="row" class="grid_4">스테이킹 리워드</th>
<td><input type="text" name="staking_reward" value="<?php echo number_format2($data['staking_reward']) ?>" id="staking_reward"  class=" frm_input  number-comma" size="20"  /> 
%  </td>
</tr>
<tr>
<th colspan="2" scope="row" class="grid_4">유효 추천인 스테이킹</th>
<td><input type="text" name="staking_ref_amt" value="<?php echo number_format2($data['staking_ref_amt']) ?>" id="staking_ref_amt"  class=" frm_input  number-comma " size="20"  />
USD 이상  회원부터 하부 추천인으로 설정</td>
</tr>

<tr>
<th colspan="2" scope="row" class="grid_4">스테이킹 해지 수수료</th>
<td>
<?
foreach($g5['cn_staking_cancelfee'] as $k => $day){?>
<?=$day=='over'?'그이상':$day.'일'?>
<input type="text" name="staking_fee<?=$k?>" value="<?php echo number_format2($data['staking_fee'.$k]) ?>" id="staking_fee<?=$k?>"  class=" frm_input  number-comma " size="10"  />
%&nbsp;&nbsp;
<? }?>
</td>
</tr-->


<?
foreach($g5['cn_cointype'] as $k=>$v){?> 
<tr>
<th width="100" rowspan="2" scope="row" class="grid_2" style='border-right:1px solid #e6e6e6;' ><?=$v?>지갑</th>
<th width="100" scope="row"  class="grid_2">출금</th>
<td><?=help('회원에게 지급하는 경우 출금될 지갑')?><input type="text" name="wallet_out_<?=$k?>" value="<?php echo $data['wallet_out_'.$k] ?>" id="wallet_out_<?=$k?>"  class=" frm_input " size="80"  /></td>
</tr>
        <tr>
        <th scope="row"  class="grid_2">입금</th>
          <td><?=help('회원으로 부터 지급 받는 경우 입금받을 지갑')?><input type="text" name="wallet_in_<?=$k?>" value="<?php echo $data['wallet_in_'.$k] ?>" id="wallet_in_<?=$k?>"  class=" frm_input " size="80"  /></td>
        </tr>
<? }?>          
        </tbody>
        </table>
    </div>
</section>





<section id="anc_1">
<h2 class="h2_frm">회사 입금은행 정보</h2>
    <?php// echo $pg_anchor ?>

    <div class="tbl_frm01 tbl_wrap">
        <table class='w-auto' style='min-width:800px;'>
<tr>
<th width="100" class="grid_2" scope="row">은행명</th>
<td><input type="text" name="bank_name" value="<?php echo $data['bank_name'] ?>" id="bank_name"  class=" frm_input" size="30"  /></td>
</tr>
<tr>
<th scope="row" class="grid_2">계좌번호</th>
<td><input type="text" name="bank_num" value="<?php echo $data['bank_num'] ?>" id="bank_num"  class=" frm_input" size="50"  /></td>
</tr>
<tr>
<th scope="row" class="grid_4">예금주</th>
<td><input type="text" name="bank_user" value="<?php echo $data['bank_user'] ?>" id="bank_user"  class=" frm_input" size="30"  /></td>
</tr>


         
        </tbody>
        </table>
    </div>
</section>




<section id="anc_2">
    <h2 class="h2_frm">추천롤업보너스</h2>
    <?php //echo $pg_anchor ?>

    <div class="tbl_frm01 tbl_wrap">
<table class='w-auto' style='min-width:800px;'>
     
        <colgroup>
            <col class="grid_4">         
            <col>
        </colgroup>
<tbody>
<tr >
<th scope="row" class="grid_2"  style='border-right:1px solid #e6e6e6;'>구분</th>
	
	<!--th scope="row" class="grid_2" >추천인수조건</th-->
	<td> 추가되는 하부 단계 및 지급율</td>
</tr>

<?
for($i=1;$i <=15;$i++){

if($i==1){?>
<tr >
	<th scope="row" class="grid_2"  rowspan='15' style='border-right:1px solid #e6e6e6;'>추천<br>
	롤업 </th>
	<?} ?>
	<!--th scope="row" class="grid_2" ><?=$i?>명</th-->
	<td>
	~
<input type="text" name="pr_rup_bs<?=$i?>_step" value="<?php echo $data['pr_rup_bs'.$i.'_step'] ?>" id="pr_rup_bs<?=$i?>_step"  class=" frm_input " size="6"  />
	대&nbsp;
<input type="text" name="pr_rup_bs<?=$i?>_r" value="<?php echo $data['pr_rup_bs'.$i.'_r'] ?>" id="pr_rup_bs<?=$i?>_r"  class=" frm_input " size="6"  />
	% </td>
</tr>
<? }?>
</tbody>
</table>
</div>
</section>



<section id="anc_5">
    <h2 class="h2_frm">직급보너스</h2>
    <?php //echo $pg_anchor ?>

    <div class="tbl_frm01 tbl_wrap">
       <table class='w-auto' style='min-width:800px;'>
          
        <colgroup>
            <col class="grid_4">         
            <col>
        </colgroup>
        
        <tr>
          <th scope="row">직급</th>
          <td>그룹수익롤업</td>
<td>총수입롤업</td>
        </tr>
        <tbody>
        <?
		foreach($g5['member_grade2'] as $k){?>
        
        <tr>
          <th scope="row"><?=$g5['member_grade'][$k]?></th>
          <td><input name="sp_bs_cls<?=$k?>_r" type="text"  class=" frm_input " id="sp_bs_cls<?=$k?>_r" value="<?php echo $data['sp_bs_cls'.$k.'_r']?>" size="6"  />
            %</td>
<td><input name="cls_bs_cls<?=$k?>_r" type="text"  class=" frm_input " id="cls_bs_cls<?=$k?>_r" value="<?php echo $data['cls_bs_cls'.$k.'_r']?>" size="6"  />
%</td>
          </tr>
        <? }?>
       
        </tbody>
        </table>
    </div>
</section>


<?/*

<section id="anc_6">
    <h2 class="h2_frm">교육지원보너스</h2>
    <?php echo $pg_anchor ?>

    <div class="tbl_frm01 tbl_wrap">
       <table class='w-auto' style='min-width:800px;'>
           
        <colgroup>
            <col class="grid_4">
            <col>
        </colgroup>
        <tbody>
        <tr>
            <th scope="row"><label for="is_charge">지점조건</label></th>
            <td><input name="ed_bs_jijum_sales" type="text"  class=" frm_input number-comma " id="ed_bs_jijum_sales" value="<?php echo number_format($data['ed_bs_jijum_sales'])?>" size="20"  />
              포인트이상</td>
          </tr>
        <tr>
          <th scope="row"><label for="is_charge">지점보너스</label></th>
          <td>매출
            <input type="text" name="ed_bs_jijum_r" value="<?php echo $data['ed_bs_jijum_r'] ?>" id="ed_bs_jijum_r"  class=" frm_input  " size="4"  />
            %지급</td>
        </tr>
        <tr>
          <th scope="row"><label for="is_charge">지사조건</label></th>
          <td><input name="ed_bs_jisa_sales" type="text"  class=" frm_input number-comma " id="ed_bs_jisa_sales" value="<?php echo number_format($data['ed_bs_jisa_sales'])?>" size="20"  />
            포인트이상</td>
          </tr>
        <tr>
          <th scope="row"><label for="is_charge">지사보너스</label></th>
          <td>매출
            <input type="text" name="ed_bs_jisa" value="<?php echo $data['ed_bs_jisa_r'] ?>" id="ed_bs_jisa"  class=" frm_input  " size="4"  />
            %지급</td>
          </tr>
       
        </tbody>
        </table>
    </div>
</section>


<section id="anc_7">
    <h2 class="h2_frm">승급조건</h2>
    <?php echo $pg_anchor ?>

    <div class="tbl_frm01 tbl_wrap">
       <table class='w-auto' style='min-width:800px;'>
          
        <colgroup>
            <col class="grid_4">         
            <col>
            <col>
            <col class="grid_4">   
        </colgroup>
        <tr>
          <th scope="row">직급</th>
          <td>개인매출조건</td>
          <td>팀매출조건(소실적)</td>
          <td>합산매출조건</td>
          <td>직접추천인</td>
         </tr>
        <tbody>
        <?
		foreach($g5['member_grade2'] as $k){?>
       
        <tr>
          <th scope="row"><?=$g5['member_grade'][$k]?></th>
          <td><input name="lvup_cls<?=$k?>_sales1" type="text"  class=" frm_input number-comma " id="lvup_cls<?=$k?>_sales1" value="<?php echo number_format($data['lvup_cls'.$k.'_sales1'])?>" size="20"  /></td>
          <td> OR 
            <input name="lvup_cls<?=$k?>_sales2" type="text"  class=" frm_input number-comma " id="lvup_cls<?=$k?>_sales2" value="<?php echo number_format($data['lvup_cls'.$k.'_sales2'])?>" size="20"  /></td>
          <td> OR
            <input name="lvup_cls<?=$k?>_sales3" type="text"  class=" frm_input number-comma " id="lvup_cls<?=$k?>_sales3" value="<?php echo number_format($data['lvup_cls'.$k.'_sales3'])?>" size="20"  /></td>
          <td><input name="lvup_cls<?=$k?>_subor" type="text"  class=" frm_input number-comma " id="lvup_cls<?=$k?>_subor" value="<?php echo number_format($data['lvup_cls'.$k.'_subor'])?>" size="5"  />
            명이상</td>
          </tr>
        <? }?>
       
        </tbody>
        </table>
    </div>
</section>

*/?>

<div class="btn_fixed_top">
    <input type="submit" value="확인" class="btn_submit btn btn_01" accesskey="s">
</div>

</form>
<script>  

function item_form_submit(f)
{

    return true;
}
</script>
<?php
include_once ('../admin.tail.php');
?>
