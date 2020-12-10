<?php
$sub_menu = "700910";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');

$g5['title'] = '판매날짜조정';

include_once('../admin.head.php');
include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');

?>
<div class="local_ov01 local_ov">
	<div>
		<input type="text" class="frm_input" size="10" name="member" id="member" value="전체회원" placeholder="회원아이디 입력">의
		보유마감일자가 <input type="text"  class="frm_input calendar-input" size="10" name="ct_validdate" id="ct_validdate" value="<?=date('Y-m-d')?>"/>인 모든 아이템의 보유일자를 <input type="text"  class="frm_input" name="ct_intervaldate" id="ct_intervaldate" size="1"  value="1"/>일 추가합니다.
		<button type="button" class="btn btn_01" id="change_validdate">보유일자 변경</button>
	</div>
</div>

<script>
$(document).ready(function(){
	$("#change_validdate").on("click",function(){
		if($("#ct_validdate").val()==''){
			alert("보유마감일자를 입력하세요.");
			$("#ct_validdate").focus();
			return false;
		}

		if($("#ct_intervaldate").val()==''){
			alert("가산일자를 입력하세요.");
			$("#ct_intervaldate").focus();
			return false;
		}

		$.ajax({
			url : "./member_penalty_list_update.php",
			type : "POST",
			data : {"act_button":"보유일자변경","member":$("#member").val(),"ct_validdate":$("#ct_validdate").val(),"ct_intervaldate":$("#ct_intervaldate").val()},
			success : function(res){
				alert(res);
				location.reload();
			}
		});
	});
});
</script>

<?php
include_once ('../admin.tail.php');
?>
