<?php
$sub_menu = "800100";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'w');

$g5['title'] = $html_title;

$files = glob(G5_ADMIN_PATH.'/css/admin_extend_*');
if (is_array($files)) {
    foreach ((array) $files as $k=>$css_file) {
        
        $fileinfo = pathinfo($css_file);
        $ext = $fileinfo['extension'];
        
        if( $ext !== 'css' ) continue;
        
        $css_file = str_replace(G5_ADMIN_PATH, G5_ADMIN_URL, $css_file);
        add_stylesheet('<link rel="stylesheet" href="'.$css_file.'">', $k);
    }
}

include_once(G5_PATH.'/head.sub.php');

?>
<section id="anc_rt_basic" style='padding:0 10px;' >
<h2 class="h2_frm" style='border-bottom:1px solid #ddd;' >작업결과</h2>
<div class="tbl_frm01 tbl_wrap">
</div>
</section>
<script>  
var reg_mb_exist_check = function() {
    var result = "";
    $.ajax({
        type: "POST",
        url: g5_bbs_url+"/ajax.mb_exist.php",
        data: {
            "reg_mb_exist": encodeURIComponent($("#reg_mb_exist").val())
        },
        cache: false,
        async: false,
        success: function(data) {
            result = data;
        }
    });
    return result;
}

function member_select(tg,datas){
	var ov=$('#'+tg).val();
	//$('#'+tg).val(ov+(ov?',':'')+datas.mb_id);
	$('#'+tg).val(datas.mb_id);
	$('#in_wallet_addr').val(datas.mb_wallet_addr);
	
	$('#member_search').hide();
}

$(document).ready(function(e) {
    $('#openMSearchBtn').click(function(){
	
		$('#myModalLabel').html('회원 검색')
		$('#mblevle_stx').val('5')
		$("input[name='mb_id']").val('');
		
		search_member_open('mb_id');
	});

});


</script>

<?php
include_once(G5_PATH.'/tail.sub.php');
?>
