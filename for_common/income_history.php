<?php
include_once('./_common.php');

$g5['title'] = '나의 그룹 ';
$docu_title=$g5['title'];

add_javascript('<script src="'.G5_THEME_URL.'/extend/jquery.ui.touch-punch.min.js"></script>');

include_once('../_head.php');
$mb_id = $member['mb_id'];
?>
<link rel="stylesheet" href="<?php echo G5_THEME_URL ?>/skin/board/basic/style.css?ver=171222201210075048"  crossorigin="anonymous">
    
<div style="height:92vh;text-align:center;" id="bo_list ">
    <img src="<?php echo G5_THEME_URL ?>/images/head_income.png" style="width:100%" width=100% alt="">
    
                
        <?php
            /*
            $union_all='';
            $re=sql_query("SELECT *  FROM information_schema.tables WHERE  TABLE_SCHEMA='".G5_MYSQL_DB."' and  TABLE_NAME like '{$g5['cn_item_cart']}%' ");
            while($d=sql_fetch_array($re)){
                if($d['TABLE_NAME']!=$g5['cn_item_cart'] &&  !preg_match("/".$g5['cn_item_cart']."_[0-9]{4}/",$d['TABLE_NAME'])) continue;

                $union_all.=($union_all ? " union all ":"")." select date_format(soled_date,'%Y-%m') dates, sum(ct_sell_price - ct_buy_price) amt,count(code) cnt  from {$d['TABLE_NAME']} where mb_id='$mb_id' and is_soled='1' group by date_format(soled_date,'%Y-%m')";
            }

            if($union_all != ''){


            $result=sql_query_ext("select dates ,sum(amt) amt,sum(cnt) cnt from ($union_all) as A group by dates",1);
            */
            $result=sql_query("select date_format(if(soled_date='0000-00-00',ct_validdate,soled_date),'%Y-%m-%d') dates, sum(ct_sell_price - ct_buy_price) amt,count(code) cnt  from {$g5['cn_item_cart']} where mb_id='$mb_id' and is_soled='1' group by date_format(if(soled_date='0000-00-00',ct_validdate,soled_date),'%Y-%m-%d') order by ct_validdate desc, soled_date desc", 1); ?>

        <div class="tbl_head01 tbl_wrap">
        <table>
        <thead>
        <tr>
        <th id="mb_list_id" scope="col">월</a></th>
        <th id="mb_list_id" scope="col">수익</a></th>
        <th scope="col" >건수</a></th>

        </tr>
        </thead>
        <tbody>




        <?php
        $tot_amt=$tot_cnt=0;
            while ($ds=sql_fetch_array($result)) {
                ?>
        <tr class="<?php echo $bg; ?>">
        <td  ><?php echo $ds['dates'] ?></td>
        <td  ><?php echo number_format2($ds['amt']) ?></td>
        <td  ><?=number_format2($ds['cnt'])?>
        </td>

        </tr>

        <?php
                $tot_amt+=$ds['amt'];
                $tot_cnt+=$ds['cnt'];
            } ?>
        <tr class="<?php echo $bg; ?>">
        <td  >총</td>
        <td  ><strong><?php echo number_format2($tot_amt) ?></strong></td>
        <td  ><strong>
        <?=number_format2($tot_cnt)?>
        </strong></td>
        </tr>
        </tbody>
        </table>
        </div>

    </div>
<style>


/* 기본테이블 */
.tbl_wrap table {width:100%;border-collapse:collapse;border-spacing:0 5px;background:#fff;border-top:1px solid #ececec;border-bottom:1px solid #ececec} 
.tbl_wrap caption {padding:10px 0;font-weight:bold;text-align:left}
.tbl_head01 {margin:0 0 10px}
.tbl_head01 caption {padding:0;font-size:0;line-height:0;overflow:hidden}
.tbl_head01 thead th {padding:20px 0;font-weight:normal;text-align:center;border-bottom:1px solid #ececec;height:40px}
.tbl_head01 thead th input {vertical-align:top} /* middle 로 하면 게시판 읽기에서 목록 사용시 체크박스 라인 깨짐 */
.tbl_head01 tfoot th, .tbl_head01 tfoot td {padding:10px 0;border-top:1px solid #c1d1d5;border-bottom:1px solid #c1d1d5;background:#d7e0e2;text-align:center}
.tbl_head01 tbody th {padding:8px 0;border-bottom:1px solid #e8e8e8}
.tbl_head01 td {color:#666;padding:10px 5px;border-top:1px solid #ecf0f1;border-bottom:1px solid #ecf0f1;line-height:1.4em;height:60px;word-break:break-all}
.tbl_head01 tbody tr:hover td {background:#fafafa}
.tbl_head01 a:hover {text-decoration:underline}

.tbl_head02 {margin:0 0 10px}
.tbl_head02 caption {padding:0;font-size:0;line-height:0;overflow:hidden}
.tbl_head02 thead th {padding:5px 0;border-top:1px solid #d1dee2;border-bottom:1px solid #d1dee2;background:#e5ecef;color:#383838;font-size:0.95em;text-align:center;letter-spacing:-0.1em}
.tbl_head02 thead a {color:#383838}
.tbl_head02 thead th input {vertical-align:top} /* middle 로 하면 게시판 읽기에서 목록 사용시 체크박스 라인 깨짐 */
.tbl_head02 tfoot th, .tbl_head02 tfoot td {padding:10px 0;border-top:1px solid #c1d1d5;border-bottom:1px solid #c1d1d5;background:#d7e0e2;text-align:center}
.tbl_head02 tbody th {padding:5px 0;border-top:1px solid #e9e9e9;border-bottom:1px solid #e9e9e9;background:#fff}
.tbl_head02 td {padding:5px 3px;border-top:1px solid #e9e9e9;border-bottom:1px solid #e9e9e9;background:#fff;line-height:1.4em;word-break:break-all}
.tbl_head02 a {}
</style>
