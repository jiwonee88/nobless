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
    <li><a href="#anc_3">추천롤업보너스</a></li>
	<li><a href="#anc_4">후원롤업보너스</a></li>
	<li><a href="#anc_5">직급보너스</a></li>
	<li><a href="#anc_6">교육지원보너스</a></li>
	<li><a href="#anc_6">승급조건</a></li>
	</ul>
	';

 /*
sql_query(" ALTER TABLE {$g5['cn_set']}
ADD `lvup_cls1_sales3` bigint NOT NULL  AFTER `lvup_cls1_sales2`,
ADD `lvup_cls2_sales3` bigint NOT NULL  AFTER `lvup_cls2_sales2`,
ADD `lvup_cls3_sales3` bigint NOT NULL  AFTER `lvup_cls3_sales2`,
ADD `lvup_cls4_sales3` bigint NOT NULL  AFTER `lvup_cls4_sales2`,
ADD `lvup_cls5_sales3` bigint NOT NULL  AFTER `lvup_cls5_sales2`,
ADD `lvup_cls6_sales3` bigint NOT NULL  AFTER `lvup_cls6_sales2`,
ADD `lvup_cls7_sales3` bigint NOT NULL  AFTER `lvup_cls7_sales2`,
ADD `lvup_cls8_sales3` bigint NOT NULL  AFTER `lvup_cls8_sales2`,
ADD `lvup_cls9_sales3` bigint NOT NULL  AFTER `lvup_cls9_sales2`
", false);
*/
	 
?>
<form name="set_form" id="set_form" action="./setting_form_update.php" onsubmit="return item_form_submit(this)" method="post" enctype="multipart/form-data">
<input type="hidden" name="w" value="u">
<input type="hidden" name="token" value="">

<?=$common_form?>

<section id="anc_1">
    <h2 class="h2_frm">기본설정</h2>
    <?php echo $pg_anchor ?>

    <div class="tbl_frm01 tbl_wrap">
        <table class='w-auto' style='min-width:800px;'>
          
        <caption>기본정보</caption>
        <colgroup>
            <col class="grid_4">
            <col>
        </colgroup>
        <tbody>
        <tr>
            <th scope="row"><label for="is_charge">입금분할비</label></th>
            <td>자유계정
            <input type="text" name="in_div_r1" value="<?php echo $data['in_div_r1'] ?>" id="in_div_r1"  class=" frm_input  " size="6"  />
            %
            <strong>            : </strong>고정계정
            <input type="text" name="in_div_r2" value="<?php echo $data['in_div_r2'] ?>" id="in_div_r2"  class=" frm_input  " size="6"  />
%</td>
          </tr>
        <tr>
          <th scope="row">적금전환배율</th>
          <td>자유계정 → 고정계정 이동시 
            <input type="text" name="trans_r" value="<?php echo $data['trans_r'] ?>" id="trans_r"  class=" frm_input" size="6"  />
% 비율로 변경</td>
        </tr>
        <tr>
          <th scope="row">데일리보너스</th>
          <td>고정계정 보유량의 
            <input type="text" name="daily_bs_r" value="<?php echo $data['daily_bs_r'] ?>" id="daily_bs_r"  class=" frm_input " size="6"  />
%를 매일 출금계정으로 배당(최소금액
<input type="text" name="daily_bs_min" value="<?php echo number_format($data['daily_bs_min']) ?>" id="daily_bs_min number-comma"  class=" frm_input " size="15"  />
이상인 경우)</td>
          </tr>
        <tr>
          <th scope="row">마감매출</th>
          <td>원금의
            <input type="text" name="max_bs_r" value="<?php echo $data['max_bs_r'] ?>" id="max_bs_r"  class=" frm_input " size="6"  />
            % 이상 수당 수령시 마감</td>
        </tr>
        <tr>
          <th scope="row">M토큰 최소배당</th>
          <td>보상 플랜 설계시 최소 
            <input type="text" name="mtoken_min_r" value="<?php echo $data['mtoken_min_r'] ?>" id="mtoken_min_r"  class=" frm_input " size="6"  />
% 이상</td>
        </tr>
        <tr>
          <th scope="row">1일 출금 제한</th>
          <td>
          
          <?
		  foreach($g5['cn_cointype'] as $k=>$v){?>          
          <?=$v?> <input type="text" name="max_out_<?=$k?>" value="<?php echo $data['max_out_'.$k] ?>" id="max_out_<?=$k?>"  class=" frm_input number-comma" size="15"  /> 이내&nbsp;&nbsp;
          <? }?>
          
          
            </td>
        </tr>
        <tr>
          <th scope="row">출금 수수료</th>
          <td><input type="text" name="out_r" value="<?php echo $data['out_r'] ?>" id="out_r"  class=" frm_input " size="6"  />
            %</td>
        </tr>
          
        </tbody>
        </table>
    </div>
</section>




<section id="anc_2">
    <h2 class="h2_frm">추천보너스</h2>
    <?php echo $pg_anchor ?>

    <div class="tbl_frm01 tbl_wrap">
       <table class='w-auto' style='min-width:800px;'>
          
        <colgroup>
            <col class="grid_4">
            <col>
        </colgroup>
        <tbody>
        <tr>
            <th scope="row"><label for="is_charge">추천보너스</label></th>
            <td><input type="text" name="pr_bs_r" value="<?php echo $data['pr_bs_r'] ?>" id="pr_bs_r"  class=" frm_input " size="6"  />
              % 
              직접 추천수장</td>
          </tr>
       
        </tbody>
        </table>
    </div>
</section>




<section id="anc_3">
    <h2 class="h2_frm">추천롤업보너스</h2>
    <?php echo $pg_anchor ?>

    <div class="tbl_frm01 tbl_wrap">
        <table class='w-auto' style='min-width:800px;'>
         
        <colgroup>
            <col class="grid_3">            
            <col class="grid_3">            
            <col>
        </colgroup>
        <tbody>
        <tr>
            <th rowspan="5" scope="row" >공통
              <label for="is_charge"></label></th>
            <td scope="row"><label for="is_charge">추천1명</label></td>
            <td><input type="text" name="pr_rup_bs1_step" value="<?php echo $data['pr_rup_bs1_step'] ?>" id="pr_rup_bs1_step"  class=" frm_input " size="6"  />
              대 까지
                <input type="text" name="pr_rup_bs1_r" value="<?php echo $data['pr_rup_bs1_r'] ?>" id="pr_rup_bs1_r"  class=" frm_input " size="6"  />
% </td>
          </tr>
        <tr>
          <td scope="row"><label for="is_charge">추천2명</label></td>
          <td><input type="text" name="pr_rup_bs2_step" value="<?php echo $data['pr_rup_bs2_step'] ?>" id="pr_rup_bs2_step"  class=" frm_input " size="6"  />
            대 까지
            <input type="text" name="pr_rup_bs2_r" value="<?php echo $data['pr_rup_bs2_r'] ?>" id="pr_rup_bs2_r"  class=" frm_input " size="6"  />
            % </td>
          </tr>
        <tr>
          <td scope="row"><label for="is_charge">추천3명</label></td>
          <td><input type="text" name="pr_rup_bs3_step" value="<?php echo $data['pr_rup_bs3_step'] ?>" id="pr_rup_bs3_step"  class=" frm_input " size="6"  />
            대 까지
            <input type="text" name="pr_rup_bs3_r" value="<?php echo $data['pr_rup_bs3_r'] ?>" id="pr_rup_bs3_r"  class=" frm_input " size="6"  />
            % </td>
          </tr>
        <tr>
          <td scope="row"><label for="is_charge">추천4명</label></td>
          <td><input type="text" name="pr_rup_bs4_step" value="<?php echo $data['pr_rup_bs4_step'] ?>" id="pr_rup_bs4_step"  class=" frm_input" size="6"  />
            대 까지
            <input type="text" name="pr_rup_bs4_r" value="<?php echo $data['pr_rup_bs4_r'] ?>" id="pr_rup_bs4_r"  class=" frm_input " size="6"  />
            % </td>
          </tr>
        <tr>
          <td scope="row"><label for="is_charge">추천5명</label></td>
          <td><input type="text" name="pr_rup_bs5_step" value="<?php echo $data['pr_rup_bs5_step'] ?>" id="pr_rup_bs5_step"  class=" frm_input  " size="6"  />
            대 까지
            <input type="text" name="pr_rup_bs5_r" value="<?php echo $data['pr_rup_bs5_r'] ?>" id="pr_rup_bs5_r"  class=" frm_input " size="6"  />
            % </td>
          </tr>
        </tbody>
        </table><br />

         <table class='w-auto' style='min-width:800px;'>  
         <colgroup>
        <col class="grid_3">            
        <col>
        </colgroup>
        <?
		foreach($g5['member_grade2'] as $k){?>
        <tr>
          <th scope="row"><?=$g5['member_grade'][$k]?></th>
          <td><input type="text" name="pr_rup_bs_cls<?=$k?>_step" value="<?php echo $data['pr_rup_bs_cls'.$k.'_step'] ?>" id="pr_rup_bs_cls<?=$k?>_step"  class=" frm_input " size="6"  />
            대 까지
              <input type="text" name="pr_rup_bs_cls<?=$k?>_r" value="<?php echo $data['pr_rup_bs_cls'.$k.'_r'] ?>" id="pr_rup_bs_cls<?=$k?>_r"  class=" frm_input  " size="6"  />
% </td>
          </tr>
        <? }?>
       
        </tbody>
        </table>
    </div>
</section>


<section id="anc_4">
    <h2 class="h2_frm">후원롤업보너스</h2>
    <?php echo $pg_anchor ?>

    <div class="tbl_frm01 tbl_wrap">
       <table class='w-auto' style='min-width:800px;'>
          
        <colgroup>
            <col class="grid_4" />         
            <col />
        </colgroup>
        <tr>
          <th scope="row">직급</th>
          <td>공유비율</td>
        </tr>
        <tbody>
        <?
		foreach($g5['member_grade2'] as $k){?>
       
        <tr>
          <th scope="row"><?=$g5['member_grade'][$k]?></th>
          <td><input name="sp_bs_cls<?=$k?>_r" type="text"  class=" frm_input " id="sp_bs_cls<?=$k?>_r" value="<?php echo $data['sp_bs_cls'.$k.'_r']?>" size="6"  />
            %</td>
        </tr>
        <? }?>
       
        </tbody>
        </table>
    </div>
</section>


<section id="anc_5">
    <h2 class="h2_frm">직급보너스</h2>
    <?php echo $pg_anchor ?>

    <div class="tbl_frm01 tbl_wrap">
       <table class='w-auto' style='min-width:800px;'>
          
        <colgroup>
            <col class="grid_4">         
            <col>
        </colgroup>
        
        <tr>
          <th scope="row">직급</th>
          <td>공유비율</td>
        </tr>
        <tbody>
        <?
		foreach($g5['member_grade2'] as $k){?>
        
        <tr>
          <th scope="row"><?=$g5['member_grade'][$k]?></th>
          <td><input name="cls_bs_cls<?=$k?>_r" type="text"  class=" frm_input " id="cls_bs_cls<?=$k?>_r" value="<?php echo $data['cls_bs_cls'.$k.'_r']?>" size="6"  />
            %</td>
          </tr>
        <? }?>
       
        </tbody>
        </table>
    </div>
</section>




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
