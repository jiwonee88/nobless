<?php
$sub_menu = "800300";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');

$mb=get_member($mb_id_stx);

$qstr.="&mb_id_stx=$mb_id_stx";

$sql_common = " from {$g5['cn_tree']} as a left outer join {$g5['member_table']} as b on(a.smb_id=b.mb_id) ";
$sql_search = " where a.mb_id = '{$mb['mb_id']}'";

$count=sql_fetch("select count(*) cnt from {$g5['cn_tree']}  where mb_id='{$mb_id_stx}'");

if (!$sst) {
    $sst = "a.step";
    $sod = "asc";
}

$sql_order = " order by a.mb_id asc,{$sst} {$sod} ";

$sql = " select count(distinct a.smb_id) as cnt {$sql_common} {$sql_search} {$sql_order} ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

// 탈퇴회원수
$sql = " select count(distinct a.smb_id) as cnt  {$sql_common} {$sql_search} and mb_leave_date <> ''   {$sql_order} ";
$row = sql_fetch($sql);
$leave_count = $row['cnt'];

// 차단회원수
$sql = " select count(distinct a.smb_id) as cnt  {$sql_common} {$sql_search} and mb_intercept_date <> ''   {$sql_order} ";
$row = sql_fetch($sql);
$intercept_count = $row['cnt'];

$listall = '<a href="'.$_SERVER['SCRIPT_NAME'].'" class="ov_listall">전체목록</a>';

$g5['title'] = '후원인계보';

include_once(G5_ADMIN_PATH.'/admin.head.php');

$sql = " select a.*,b.* {$sql_common} {$sql_search}  and a.step='1'  {$sql_order} ";
$result = sql_query($sql,1);

$colspan = 16;
?>
<div class="local_ov01 local_ov">
    <?php echo $listall ?>
    <span class="btn_ov01"><span class="ov_txt">총 </span><span class="ov_num"> <?php echo number_format($total_count) ?>명 </span></span>
    <a href="?sst=mb_intercept_date&amp;sod=desc&amp;sfl=<?php echo $sfl ?>&amp;stx=<?php echo $stx ?>" class="btn_ov01"> <span class="ov_txt">차단 </span><span class="ov_num"><?php echo number_format($intercept_count) ?>명</span></a>
    <a href="?sst=mb_leave_date&amp;sod=desc&amp;sfl=<?php echo $sfl ?>&amp;stx=<?php echo $stx ?>" class="btn_ov01"> <span class="ov_txt">탈퇴  </span><span class="ov_num"><?php echo number_format($leave_count) ?>명</span></a>
</div>

<form id="fsearch" name="fsearch" class="local_sch01 local_sch" method="get">



<label for="sfl" class="txt_fail" >상위 회원 아이디</label>
<label for="mb_id_stx" class="sound_only"><strong class="sound_only"> 필수</strong></label>
<input type="text" name="mb_id_stx" value="<?php echo $mb_id_stx ?>" id="mb_id_stx" required class="required frm_input">
<input type="submit" class="btn_submit" value="검색">

</form>
<form name="fmemberlist" id="fmemberlist" action="./member_list_update.php" onsubmit="return fmemberlist_submit(this);" method="post">
  <input type="hidden" name="sst" value="<?php echo $sst ?>">
<input type="hidden" name="sod" value="<?php echo $sod ?>">
<input type="hidden" name="page" value="<?php echo $page ?>">
<input type="hidden" name="return_page" value="<?php echo $return_page ?>">

<input type="hidden" name="token" value="">

<div class="tbl_head01 tbl_wrap">
    <table>
    <caption><?php echo $g5['title']; ?> 목록</caption>
    <thead>
    <tr>
        <th scope="col">번호</a></th>
        <th scope="col" id="mb_list_id" >단계</th>
        <th scope="col" id="mb_list_id" >아이디</a></th>
        <th scope="col" >1단계상위</a></th>
        <?
		//계정별
		foreach($g5['cn_cointype'] as $k=> $v){ ?>
        <th scope="col"><?=$v?>수량</th>
        <? }?>
        <th scope="col" >후원</th>
        <th scope="col" >롤업수량</th>
        <th scope="col" >가입일</th>
        <th scope="col" >내역</th>
        </tr>
    </thead>
    <tbody>
    
    <?
	if($mb){
	
	?>
    <tr class="bg0cancel">
      <td  class="td_num">ROOT</td>
      <td class="td_left"><strong>ROOT</strong></td>
      <td headers="mb_list_id"><?php echo $mb['mb_id'] ?></td>
      <td ><?php echo $mb['mb_recommend']?></td>
	  <?
		//계정별
		foreach($g5['cn_cointype'] as $k=> $v){ ?>
      <td class='text-right' ><?=number_format($mb['mb_point_free_'.$k],8) ?></td>
	  <?} ?>
      <td >
        <?php echo $count['cnt']?>
      </td>
      <td class='text-right' >-</td>
      <td ><?php echo substr($mb['mb_datetime'],0,10); ?></td>
      <td class="td_date"><a href="<?=G5_CN_ADMIN_URL?>/fee_list.php?sfl=a.mb_id&stx=<?php echo $mb_id_stx ?>" class="btn_frmline" target='_blank' >상세</a></td>
      </tr>
     <? }?> 
    <?php
	
function prnt_rows($result,$list_num){
	
	global $g5,$mb,$sql_common,$sql_search,$sql_order; 
	
    for ($i=0; $row=sql_fetch_array($result); $i++) {
       
        $mb_id = $row['mb_id'];
       
	    $_mb = get_member($row['mb_id']);
		 
        $bg = 'bg'.($list_num%2);
		
		//본인 하부가 아닌경우 중지
		$tree=explode(",",$_mb['mb_tree']);
		if(!in_array($mb['mb_id'],$tree)) continue;		
		
		$step=sql_fetch("select step from {$g5['cn_tree']} where mb_id='{$mb['mb_id']}' and smb_id='{$row['mb_id']}'");
		$step=$step['step'];
				
		//수당내역
		$point=sql_fetch("select sum(amount) amount,sum(usd) usd from {$g5['cn_point']}  where mb_id='{$member['mb_id']}'  and smb_id='{$mb['mb_id']}' and pkind in ('fee','fee2')");
		
		//후원자
		$count=sql_fetch("select count(*) cnt from {$g5['cn_tree']}  where mb_id='{$mb_id}'");
		
		
    ?>

    <tr class="<?php echo $bg; ?>">
      <td  class="td_num"><?=$list_num?></td>
      <td class="td_left"><strong><?=str_repeat("&nbsp;&nbsp;",$step)?>└<?php echo $step ?></strong></td>
      <td headers="mb_list_id"><?php echo $mb_id ?></td>
      <td ><?php echo $row['mb_recommend']?></td>
	  <?
		//계정별
		foreach($g5['cn_cointype'] as $k=> $v){ ?>
      <td class='text-right' ><?=number_format($mb['mb_point_free_'.$k],8) ?></td>
	  <? }?>
      <td >
        <?
        if($row['mb_servant_cnt'] > 0 ){?>
        <a href="<?=G5_CN_ADMIN_URL?>/recommender_list.php?mb_id_stx=<?php echo $row['mb_id'] ?>" class="btn_frmline"><?php echo $count['cnt']?></a>
        <?
		}else{?>
        <?php echo $count['cnt']?>
        <? }?>
      </td>
      <td class='text-right' ><?=number_format($point['amount'],8) ?></td>
      <td><?php echo substr($row['mb_datetime'],0,10); ?></td>
      <td class="td_date"><a href="<?=G5_CN_ADMIN_URL?>/fee_list.php?sfl=a.mb_id&stx=<?php echo $row['mb_id'] ?>" class="btn_frmline" target='_blank' >상세</a></td>
      </tr>
    <?php
	$list_num++;
		
	$step=$row['step'] + 1;	
	$sql = " select a.*,b.* {$sql_common} where a.mb_id='{$row['smb_id']}' and step='1'  group by a.smb_id {$sql_order} ";
	//echo $sql.'<br>';
	$_result = sql_query($sql,1);
	
	if(sql_num_rows($_result)) $list_num=prnt_rows($_result,$list_num);
	
	
    }
	return $list_num;
	
}

prnt_rows($result,1);
	 
if(sql_num_rows($result) == 0)
	echo "<tr><td colspan=\"".$colspan."\" class=\"empty_table\">".($mb_id_stx=='' ? "상위 회원 아이디를 입력하세요":"자료가 없습니다")."</td></tr>";
?>
    </tbody>
    </table>
</div>



</form>

<script>
</script>

<?php
include_once(G5_ADMIN_PATH.'/admin.tail.php');
?>
