<?php
$sub_menu = "500200";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'w');

$html_title = '입금전용주소';


if ($w == '') {

    $html_title .= ' 등록';

    $required = 'required';
    $required_valid = 'alnum_';
    $sound_only = '<strong class="sound_only">필수</strong>';
	

} else if ($w == 'u') {

    $html_title .= ' 수정';
	

    if (!$data['token_no'])
        alert('존재하지 않은 내역 입니다.');

    $readonly = 'readonly';
	
}


$g5['title'] = $html_title;
include_once(G5_ADMIN_PATH.'/admin.head.php');


$qstr.="&mb_stx=$mb_stx";

?>
<form name="fparticipationform" id="fparticipationform" action="./token_addr_form_update.php" onsubmit="return fparticipationform_submit(this)" method="post" enctype="multipart/form-data">
<input type="hidden" name="w" value="<?php echo $w ?>">
<input type="hidden" name="token_no" value="<?php echo $token_no ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
<input type="hidden" name="stx" value="<?php echo $stx ?>">
<input type="hidden" name="sst" value="<?php echo $sst ?>">
<input type="hidden" name="sod" value="<?php echo $sod ?>">
<input type="hidden" name="page" value="<?php echo $page ?>">
<input type="hidden" name="mb_stx" value="<?php echo $mb_stx ?>">


<input type="hidden" name="token" value="">

<section id="anc_rt_basic">
	 <h2 class="h2_frm">입급 지갑 주소 등록 (한개의  주소 등록시)</h2>
    <div class="tbl_frm01 tbl_wrap">
        <table>
          <tr class='for-ptc'>
            <th scope="row"><label for="uploadfile">코인/토큰구분</label></th>
            <td><span class="local_sch01 local_sch">
              <select name='token_name' class="form-control input-sm  w-auto  mb-1" id="token_name" >
                <?
foreach($g5['cn_coin_in'] as $k){?>
                <option value='<?=$k?>' <?=$coin_stx==$k?'selected':''?> ><?=$g5['cn_cointype'][$k]?></option>
                <? }?>
              </select>
            </span></td>
          </tr>
          
        <colgroup>
            <col class="grid_4">
            <col>
            <col class="grid_3">
        </colgroup>
        <tbody>
        <tr class='for-ptc'>
          <th scope="row"><label for="uploadfile">입금주소</label></th>
          <td><input type="text" name="token_addr" value="<?php echo $data['token_addr'] ?>" id="token_addr"  class="frm_input" size="80" /></td>
        </tr>
        
        </tbody>
        </table>
    </div>

  <h2 class="h2_frm">엑셀일괄등록 (엑셀파일을 이용하여 여러개의 주소를 일괄 등록시)</h2>
    <div class="tbl_frm01 tbl_wrap">
        <table>
          <tr class='for-ptc'>
            <th scope="row"><label for="uploadfile">코인/토큰구분</label></th>
            <td>
              <select name='token_name_excel' class="form-control input-sm  w-auto  mb-1" id="token_name_excel" >
                <?
foreach($g5['cn_coin_in'] as $k){?>
                <option value='<?=$k?>' <?=$coin_stx==$k?'selected':''?> ><?=$g5['cn_cointype'][$k]?></option>
                <? }?>
              </select>
          </td>
          </tr>
                  <colgroup>
            <col class="grid_4">
            <col>
            <col class="grid_3">
        </colgroup>
        <tbody>
        <tr >
          <th scope="row"><label for="uploadfile">엑셀파일</label></th>
          <td><label for="uploadfile"></label>
            <input type="file" name="uploadfile" id="uploadfile" /></td>
        </tr>
<tr class='for-ptc'>
<th height="14" scope="row">옵션</th>
<td><input name="trunc_addr" type="checkbox" id="trunc_addr" value="1"> 
기존 
<label for="trunc_addr">주소록 모두 비우기</label></td>
</tr>
        <tr class='for-ptc'>
          <th scope="row">샘플</th>
          <td>
          <a href="./token.addr.sample.excel.xlsx" target='_blank' class="lsbtn lsbtn-smt obje-white"> 엑셀다운로드</a>    

</td>
        </tr>
        
        </tbody>
        </table>
    </div>
</section>


<div class="btn_fixed_top">
	<a href="./token_addr_list.php?<?=$qstr?>"  class=" btn_02 btn">전체목록</a>
    <input type="submit" value="확인" class="btn_submi btn btn_01" accesskey="s">
</div>

</form>

<script>
function fparticipationform_submit(f)
{
		// 수급회원아이디 검사	
	if (f.token_addr.value == "" && f.uploadfile.value == "")  {
		
		alert('등록할 토큰 주소 또는 일괄등록할 엑셀 파일을 선택하세요');
		return false;			
	}
	
	if (confirm( $("select[name=token_name_excel] option:selected").text()+"의 지갑주소 등록이 맞습니까?") )  {		
		return true;
	}else return false
	
    return true;
}
</script>

<?php
include_once(G5_ADMIN_PATH.'/admin.tail.php');
?>
