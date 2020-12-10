<!-- Modal -->
<style>
.modal-body{position:absolute; display:none;width:500px;border:1px solid #ededed;background:#ffffff;}
.modal-header{padding:10px 10px;}
.modal-header .close{float:right;border:0;width:25px;height:25px;}
.modal-contents{padding:10px 10px;}
.modal-body .result-data{min-height:150px;max-height:300px;overflow-y:auto;}
.modal-body .result-data ul{list-style:none;margin:0;padding:0;}
.modal-body .result-data li{padding:5px 5px;float:left;cursor:pointer;}
.modal-body .result-data li:hover{background:#efefef;}
</style>
 <form name="fsearchid" id="fsearchid"  class="local_sch01 local_sch" >
 <input type='hidden' name='tg'  value='' />
    <div id='member_search' class="modal-body">
        <div class="modal-header">
            <button type="button" class="close "  >&times;</button>
            <h4 class="modal-title" id="myModalLabel">회원 검색</h4>
        </div>
        <div class="modal-contents">
                <div class="form-group has-success">
                        <div class="input-group">
            <select name="sfl" id="sfl">            
                                                   
            <!--option value="mb_5"<?php echo get_selected($_GET['sfl'], "mb_5"); ?>>업체명</option-->
            <!--option value="mb_nick"<?php echo get_selected($_GET['sfl'], "mb_nick"); ?>>닉네임</option-->
            <!--option value="mb_level"<?php echo get_selected($_GET['sfl'], "mb_level"); ?>>권한</option-->
            <option value="mb_id"<?php echo get_selected($_GET['sfl'], "mb_id"); ?>>아이디</option>
			<option value="mb_email"<?php echo get_selected($_GET['sfl'], "mb_email"); ?>>E-MAIL</option>
			
			<option value="mb_name"<?php echo get_selected($_GET['sfl'], "mb_name"); ?>>이름</option>
            <option value="mb_tel"<?php echo get_selected($_GET['sfl'], "mb_tel"); ?>>전화번호</option>
            <option value="mb_hp"<?php echo get_selected($_GET['sfl'], "mb_hp"); ?>>휴대폰번호</option>
            <!--option value="mb_point"<?php echo get_selected($_GET['sfl'], "mb_point"); ?>>포인트</option-->
            <option value="mb_datetime"<?php echo get_selected($_GET['sfl'], "mb_datetime"); ?>>가입일시</option>
            <option value="mb_ip"<?php echo get_selected($_GET['sfl'], "mb_ip"); ?>>IP</option>
            <option value="mb_recommend"<?php echo get_selected($_GET['sfl'], "mb_recommend"); ?>>추천인코드</option>
            </select>
                            <input name='stx' id="btn-input-name" type="text"  placeholder="검색어" class="required frm_input">
                            <input type="submit" class="btn_submit" value="검색">
                        </div>
                </div>
                <div class="result-data">
                   
                </div>
               
            </div>
         </div>
    </div>
 </form>
<script>
function search_member_open(tg){
	$("input[name='tg']",'#fsearchid').val(tg);
	
	$('.result-data').html('');
	
	var sWidth = window.innerWidth;
	var sHeight = window.innerHeight;

	var oWidth = $('#member_search').width();
	var oHeight = $('#member_search').height();
	
	// 레이어가 나타날 위치를 셋팅한다.
	var divLeft = event.clientX + 10 - Math.floor(oWidth/2);
	var divTop = event.clientY + $(window).scrollTop() + 5;
	
	// 레이어가 화면 크기를 벗어나면 위치를 바꾸어 배치한다.
	if( divLeft + oWidth > sWidth ) divLeft -= oWidth;
	if( divTop + oHeight > sHeight ) divTop -= oHeight;

	// 레이어 위치를 바꾸었더니 상단기준점(0,0) 밖으로 벗어난다면 상단기준점(0,0)에 배치하자.
	if( divLeft < 0 ) divLeft = 0;
	if( divTop < 0 ) divTop = 0;

	$('#member_search').css({
		"top": divTop,
		"left": divLeft
	}).show();
	
	$('#member_search .close').on('click',function(){
		$('#member_search').hide();
	});
		
	
	$('#fsearchid').on('submit',function(){
		var allData = $(this).serialize();	
		 $.ajax({
			type: "POST",
			url: "<?=G5_CN_ADMIN_URL?>/member_search_ajax.php", dataType:'json',
			contentType: "application/x-www-form-urlencoded; charset=UTF-8",  
			data: allData,
			success: function(data)
			{
		
				if(data.msg == "OK"){	
					
					var htm='';
					for(lis in data.list){
						datas=data.list[lis];
						htm+=
						"<li class='member-search-result' data-id='"+lis+"' >"+datas.mb_id+" ["+(datas.mb_name?datas.mb_name:datas.mb_hp)+"]</li>";	
						//console.log(lis);
					}
					if(htm) htm="<ul>"+htm+"</ul>";
					else  htm="검색결과 없습니다";
					$('.result-data').html(htm);
					
					$('.member-search-result').on('click',function(){	
						//console.log(	data.list[$(this).attr('data-id')]);					
						member_select(tg,data.list[$(this).attr('data-id')]);
					});			
				
				} else {
					//alert("찾는 멤버가 없습니다.");
				}
							
			},
			complete: function(data){
			
			}
		});
		
		return false;
	});
	
}
	

</script>