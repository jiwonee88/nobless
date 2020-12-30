<?php
include_once('./_common.php');

$g5['title'] = '나의 그룹 ';
$docu_title=$g5['title'];

add_javascript('<script src="'.G5_THEME_URL.'/extend/jquery.ui.touch-punch.min.js"></script>');

include_once('../_head.php');

$_tree_db=$g5['cn_tree'];
$_tree_fd='mb_tree';


if ($mb_id_stx) {
    $mbs=get_member($mb_id_stx);
    $temp=sql_fetch("select * from {$g5['cn_tree']} where mb_id='{$member['mb_id']}' and smb_id='$mb_id_stx'");
    if (!$temp[smb_id]) {
        $mbs=$member;
    }
} else {
    $mbs=$member;
}


function prnt_rows($result, $list_num)
{
    global $g5,$sql_common,$sql_search,$sql_order,$member, $_tree_db,$_tree_fd,$mbs;
    
    $str='';
    for ($i=0; $row=sql_fetch_array($result); $i++) {
        $mb = get_member($row['smb_id']);
        
        $str.='
		<li id="mb_box_'.$mb['mb_id'].'" class="mb_box" data-id="'.$mb['mb_id'].'">
		<div class="arrow"></div>
        <p class="btn-user-info" data-id="'.$mb['mb_id'].'" >'.$mb['mb_id'].'</p>
		';
        
        //제한이 넘은경우
        $mbtree=explode(",", $mb[mb_tree]);
        $keys=array_search($mbs[mb_id], $mbtree);
        
        if (count($mbtree) - $keys >= $list_num) {
            continue;
        }
       
        $sql = " select a.*,b.mb_id bmb_id,b.mb_name,b.mb_tree {$sql_common} where a.mb_id='{$row['smb_id']}' and a.step='1'  {$sql_order} ";
        
        //echo $sql.'<br>';
        $_result = sql_query($sql, 1);
        
        if (sql_num_rows($_result)) {
            $str2=prnt_rows($_result, $list_num);
            if ($str2) {
                $str.='<ul>'.$str2.'</ul>';
            }
        }
        $str.='</li>';
    }
    return $str;
}

if ($mbs[mb_id]) {
    $sql_common = " from $_tree_db as a left outer join {$g5['member_table']} as b on(a.smb_id=b.mb_id) ";
    $sql_search = " where a.mb_id = '{$mbs['mb_id']}'  ";


    $sql_order = " order by a.step ,a.no desc";

    $sql = " select a.*,b.mb_id bmb_id,b.mb_name,b.mb_tree {$sql_common} {$sql_search}  and a.step='1'  {$sql_order} ";
    $result = sql_query($sql, 1);
}


?>

<style>

.layerpop {
    display: none;
    z-index: 1000;
    border: 2px solid #ccc;
    background: rgba(0,0,0,0.6) ;
    cursor: move; 
	font-size:14px;
	width:300px;
	height:180px;
}


.layerpop_area .layerpop_close {
    width: 25px;
    height: 25px;
    display: block;
    position: absolute;
    top: 10px;
    right: 10px;
	color: #ffffff;
   }

.layerpop_area .layerpop_close:hover {    
	color: #0000ff;
   }
.layerpop_area .layerpop_data {
    width:96%;    
    margin: 2%;
    color: #ffffff; 
}

.layerpop_area .layerpop_data p span{
	font-weight:600;
    color: #ffffff; 
}

</style>

      
		
<div style="height:92vh;text-align:center;" id="Contents">
	<img src="<?php echo G5_THEME_URL ?>/images/head_partner.png" style="width:100%" width=100% alt="">


             	<!--div class="w-100 overflow-hidden">            
						
					<div class='button-group button-group-sm float-right mt-0'>
					<button class='btn btn-dark btn-sm' id='btnScaleDown'>-</button>
					<button class='btn btn-light btn-sm' id='btnScaleNone'>1X</button>
					<button class='btn btn-dark btn-sm' id='btnScaleUp'>+</button>
					</div>

                </div-->
				
                <div class="w-100 mt-1 p-0" style="text-align:left;overflow:auto;height:80vh">
                    <div class="card-block" style='width:100%;min-height:100%;'>                                               
					<?php
                    if ($mb_id_stx && $mb_id_stx != $member[mb_id]) {?>
					<a href="<?=$_SERVER[SCRIPT_NAME]?>" class='btn btn-dark btn-sm m-1' style='position:absolute;top;10px;left:10px;z-index:1001;' >RESET</a>
					<?php }?>

					<div class="tree" >

						<ul>
						<li id="mb_box_<?=$mbs['mb_id']?>" class="mb_box" data-id="<?=$mbs['mb_id']?>">  
						<div class="arrow"></div>
						<p id='starter' class='btn-user-info' data-id='<?=$mbs['mb_id']?>' ><?=$mbs['mb_id']?></p>

						<?php if (sql_num_rows($result) > 0) { ?>        
							<ul>
						<?php
                        echo prnt_rows($result, 10);
                        ?>
							  </ul>
						<?php }?>    
					  </li>
					</ul>
					</div>


            
                    </div>
                </div>
        
		<div class="gene">
                <ul>
                    <li d='btnScaleUp'>
                        <img src="<?=G5_THEME_URL?>/images/plus.png" alt="">
                    </li>
                    <li  id='btnScaleDown'>
                        <img src="<?=G5_THEME_URL?>/images/mius.png" alt="">
                    </li>
                </ul>
            </div>
		</div>
<!--Popup Start -->
<div id="layerbox" class="layerpop">
	<article class="layerpop_area">	
	<a href="javascript:popupClose();" class="layerpop_close"
		id="layerbox_close"><i class="fas fa-times fa-2x"></i></a> <br>
		<div class="layerpop_data mx-auto p-2">

		</div>
	</article>
</div>
<!--Popup End -->



<script>
$(document).ready(function(){
    $(".mb_box").on("click",function(){
        var div_id=$(this).attr("data-id");
        $("#mb_box_"+div_id).find("ul").toggle();
        $("#mb_box_"+div_id).find(".arrow").toggleClass("arrow_bottom");
        console.log("#mb_box_"+div_id);
        event.stopPropagation();
    });
});
</script>
<?php
include_once('../_tail.php');
?>
<style>
.mb_box {margin-left:10px}
.mb_box > div {display:inline-block}
.mb_box > p {display:inline-block;line-height:200%}
.arrow:before {content:"▶"}
.arrow_bottom:before {content:"▼"}
.tree ul {cursor:pointer}
</style>
