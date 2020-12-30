<?php
include_once('./_common.php');

$outer_css=' stoneDetail  idDetail';
include_once('../_head.php');

//메인계정의 포인트
$mrpoint=get_mempoint($member['mb_id'], $member['mb_id']);
$isum=get_itemsum($member[mb_id]);

add_javascript('<script src="'.G5_THEME_URL.'/extend/clipboard.min.js"></script>', 1);

?>
<div style="height:92vh;text-align:center;" id="Contents">
    <img src="<?php echo G5_THEME_URL ?>/images/head_account.png" style="width:100%" width=100% alt="">
	<div class="notice_box" style="">
        <b>은행정보를 정확하게 입력하지 않으면</br>거래가 지연되는 등의 불이익을 당하실 수도 있습니다.</b>
	</div>
	<img src="<?php echo G5_THEME_URL ?>/images/img_line.png" width="100%" />
	<form name='form5' onsubmit='form5_submit(this);' >
        <input type='hidden' name='w' value='u5' >	
		<div class="borderbox_bg">
			<div class="borderbox_wrap" >
				<div class="borderbox_content borderbox_content_white p-30 p-l-10 p-r-10">
					<p class="pb1em">
						<span class="width-20p display-inblock">은행명</span>
						<input type="text" name='mb_bank' value="<?=$member[mb_bank]?>" class='input_readonly width-70p' placeholder='은행명'>
					</p>
					<p class="pb1em">
						<span class="width-20p display-inblock">예금주</span>
						<input type="text" name='mb_bank_user' value="<?=$member[mb_bank_user]?>" class='input_readonly width-70p' placeholder='은행명'>
					</p>
					<p class="pb1em">
						<span class="width-20p display-inblock">계좌번호</span>
						<input type="text" type="text" name='mb_bank_num' value="<?=$member[mb_bank_num]?>" class='input_readonly width-70p' placeholder='계좌번호'>
					</p>
					<p class="right">
						<button class="btn_nob bg_blue" stype='submit' >저장</button>
					</p>
				</div>
			</div>
		</div>
	</form>

	<form name='form7' onsubmit='form7_submit(this);' >
		<input type='hidden' name='w' value='u7' >	
		<div class="borderbox_bg m-t-20">
			<div class="borderbox_wrap" >
				<div class="borderbox_content borderbox_content_white p-30 p-l-10 p-r-10">
					<p class="pb1em center">
						<button class="btn_nob bg_green" type='button' >USDT</button>
						<button class="btn_nob bg_blue" stype='submit' >저장</button>
					</p>
					<p>
						<input type="text" name='mb_wallet_addr_u' value="<?=$member[mb_wallet_addr_u]?>" class='input_readonly' placeholder='USDT지갑주소'>
					</p>
				</div>
			</div>
		</div>
	</form>
            
   <!--  <div class="popup i07">
        <form name='form7' onsubmit='form7_submit(this);' >
        <input type='hidden' name='w' value='u7' >	
        <b>정확한 ERC20 지갑 주소를 입력하지 않으시면 입금액을 분실하게 됩니다.</br>ERC20 형식 지갑주소 오입력으로 발생한 모든 피해는 본인의 책임입니다.</b>
        <ul>                   
            <li>
                <h5>USDT지갑주소(ERC20)</h5>
                <input  type="text" name='mb_wallet_addr_u' value="<?=$member[mb_wallet_addr_u]?>" class='common-input w-100 mt-1' placeholder='USDT지갑주소'>
                
            </li>
            
        </ul>
    
        <div class="btns">
            <ul>
                <li class='w-50'>
                    <button stype='submit' >저장</button>
                </li>
            </ul>
        </div>
        </form>
    </div> -->
</div>
<style>
.common-input {height:35px;border:none;border-bottom:1px #ccc solid}
.btn {
	margin-top:50px;
	padding: 10px;
    background: #f4f4f4;
    box-shadow: 2px 2px 2px 2px #ddd;
    color: #000000;
    border-color: #ddd;
	}
</style>
<?php
include_once('../_tail.php');
?>
<script>

//  입금은행변경
function form5_submit(f)
{

    event.preventDefault();
    var formData = $(f).serialize();	

    $.ajax({
        type: "POST",
        url: "./idDetail.update.php",
        data:formData,
        cache: false,
        async: false,
        dataType:"json",
        success: function(data) {

            if(data.result==true){					

                var mb_bank=	data.datas['mb_bank'];
                var mb_bank_num=	data.datas['mb_bank_num'];
                var mb_bank_user=	data.datas['mb_bank_user'];
                
                $('.mb_bank').html(mb_bank +' '+ mb_bank_num  +' '+ mb_bank_user);
                
                $('.popup.i05').removeClass('on');
                Swal.fire({html:'적용되었습니다',timer:1000});   
            }
            else Swal.fire(data.message);       
        }
    });		
    return;
}

//  테더지갑주소변경
function form7_submit(f)
{

    event.preventDefault();
    var formData = $(f).serialize();	

    $.ajax({
        type: "POST",
        url: "./idDetail.update.php",
        data:formData,
        cache: false,
        async: false,
        dataType:"json",
        success: function(data) {

            if(data.result==true){					

                var mb_wallet_addr_u=	data.datas['mb_wallet_addr_u'];
                
                $('.mb_wallet_addr_u').html(mb_wallet_addr_u);
                
                $('.popup.i07').removeClass('on');
                Swal.fire({html:'적용되었습니다',timer:1000});   
            }
            else Swal.fire(data.message);       
        }
    });		
    return;
}
</script>
