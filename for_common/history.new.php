<?php

include_once('./_common.php');

$outer_css=' store';

include_once('../_head.php');

if($history_page=='') $history_page='gold';

if($history_page=='gold'){

	if($year_stx!='' ) $point_table=$g5[cn_point]."_".$year_stx;
	else  $point_table=$g5['cn_point']."_".date('ym');

	if(chk_table($point_table)) {
		$sql_common = " from {$point_table} a ";
		$sql_search = " where mb_id='$member[mb_id]'  /* a.pkind in ('fee','fee2') */";

		if ($pkind_stx) {
			$sql_search .= " and a.pkind='$pkind_stx' ";	
			$qstr.="&pkind_stx=$pkind_stx";
		}
		if ($coin_stx) {	
			$sql_search .= " and a.ot_coin='$coin_stx' ";	
			$qstr.="&coin_stx=$coin_stx";
		}
		if($dates_stx){
			$sql_search .= " and a.wdate>='$dates_stx' ";	
			$qstr.="&dates_stx=$dates_stx";
		}
		if($datee_stx){
			$sql_search .= " and a.wdate<='$datee_stx 23:59:59' ";	
			$qstr.="&datee_stx=$datee_stx";
		}
		if($year_stx){
			$qstr.="&year_stx=$year_stx";
		}
		if($link_stx){
			$qstr.="&link_no=$link_stx";
		}

		if (!$sst) {
			$sst  = "a.pt_no ";
			$sod = "desc";
		}else{
			if($sst=='a.pkind') $sst  = " field(a.pkind,'".implode("','",array_keys($g5['cn_pkind']))."') ";
		}

		$sql_order = " order by $sst $sod ";

		$sql = " select count(*) as cnt {$sql_common} {$sql_search} {$sql_search_add}";
		$row = sql_fetch($sql,1);
		$total_count = $row['cnt'];

		$rows =15;
		$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
		if ($page < 1) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
		$from_record = ($page - 1) * $rows; // 시작 열을 구함

		$sql = " select a.*  $fields {$sql_common} {$sql_search}  {$sql_search_add} {$sql_order} limit {$from_record}, {$rows} ";
		$result = sql_query($sql);

	}
}
?>
      
    <style>
        .store .wrap .area ul.tabs {
            margin: 0px;
            padding: 0px;
            list-style: none;
            text-align: center;
            font-size: 0;
            padding: 10px 5px;
            border-radius: 3px;
            border: 1px solid #ffe25c;
        }

        .store .wrap .area ul.tabs li {
            width: 33.33%;
            background: none;
            color: #fff;
            display: inline-block;
            cursor: pointer;
            font-size: 14px;
            background: url(../images/staticBtn2.png) no-repeat;
            background-size: 100% 100%;
            min-height: 1rem;
            letter-spacing: 1px;
        }

        .store .wrap .area ul.tabs li.current {
            background: url(../images/staticBtn.png) no-repeat;
            background-size: 100% 100%;
            color: #ffe25c;
        }

        .store .wrap .area .tab-content {
            width: 100%;
            border-radius: 10px;
            margin: 0 auto;           
            background: #ededed;
            background: #000;
            border: #fff 1px solid;
            margin-top: 30px;
            min-height: 400px;
        }

        .store .wrap .area .tab-content.current {
            display: inherit;
        }

        .store .wrap .area .tab-content table {
            width: 100%;
            text-align: center;
            font-size: 13px;

        }

        .store .wrap .area .tab-content table th {
            padding: 10px 0;
            font-size: 14px;
        }

        .store .wrap .area .tab-content table td {
            padding: 5px 0;
        }
    </style>

        <div class="wrap">

            <div class="area">
                <ul class="tabs">
                    <li class="tab-link <?=$history_page=='gold'?"current":""?>" onclick="document.location.href='./history.php?history_page=gold'" >꽃내역</li>
                    <!--li class="tab-link <?=$history_page=='stone'?"current":""?>" onclick="document.location.href='./history.php?history_page=stone'" >나비내역</li-->
                    <li class="tab-link <?=$history_page=='shop'?"current":""?>" onclick="document.location.href='./history.php?history_page=shop'" >상점내역</li>
                    <li class="tab-link <?=$history_page=='total'?"current":""?>" onclick="document.location.href='./history.php?history_page=total'" >총수익금액</li>
                </ul>


<? if($history_page=='gold'){?>
                <div  class="tab-content current">
				
				
<form name="fsearch" id="fsearch" method="get">
<div class='text-center m-2'>
<select name="year_stx" id="year_stx" class='common-select w-100 mx-auto' onchange='this.form.submit();' >
<option value=''>- Month Select -</option>
<?
$re=sql_query("SELECT * FROM information_schema.tables WHERE TABLE_NAME like '{$g5[cn_point]}\_%' and TABLE_NAME!= '{$g5[cn_pointsum]}'  and TABLE_SCHEMA='".G5_MYSQL_DB."'  group by TABLE_NAME order by TABLE_NAME desc",1);
while($td=sql_fetch_array($re)) {
$val1=preg_replace("/".$g5[cn_point]."_/","",$td[TABLE_NAME]);
$val2="20".substr($val1,0,2).'-'.substr($val1,2);
echo "<option value='{$val1}' ".($year_stx==$val1 ? 'selected':'')." class='text-center' >{$val2}</option>";
}
?>
</select>
</div>
</form>


                    <table>
                        <thead>
                            <tr>
                                <th width="25%">날짜</th>
                                <th width="25%">수량</th>
                                <th width="50%">내용</th>
                            </tr>
                        </thead>
                        <tbody class='text-narrow0' >
						<?php
	$list_num = $total_count - ($page - 1) * $rows;
	
    for ($i=0; $row=sql_fetch_array($result); $i++) {

        $bg = 'bg'.($i%2);		

    ?>
                            <tr>
                                <td><?=substr($row[wdate],0,10)?></td>
                                <td <?=$row[amount] < 0?"class='text-warning'":""?> ><?=number_format2($row[amount],1)?></td>
								<td class='text-left' ><?=$g5['cn_pkind'][$row[pkind]]?></td>
                            </tr>
	<?php
	$list_num--;
    }
    if ($i == 0)
        echo '<tr><td colspan="3" class="empty_table">자료가 없습니다.</td></tr>';
    ?>
     
                        </tbody>
                    </table>
					
							<div class='w-100 my-3 d-block'>
 <?=com_pager_print($total_page,$page,5,"&year_stx=$year_stx&history_page=$history_page&page=");?>
 </div>		
        
		
                </div>
<? }

 if($history_page=='stone'){ ?>
        <div class="tab-content">
                    <table>
                        <thead>
                            <tr>
                                <th width="25%">날짜</th>
                                <th width="30%">종류</th>
                                <th width="25%">수량</th>
                                <th width="20%">내용</th>
                            </tr>
                        </thead>
                        <tbody>
						<tr>
						<td colspan='4' class='p-5'>준비중입니다
						</td>
						</tr>
						</tbody>
                    </table>
                </div>
				
<? }

if($history_page=='shop'){ ?>

                <div class="tab-content">
                    <table>
                        <thead>
                            <tr>
                                <th width="25%">날짜</th>
                                <th width="30%">종류</th>
                                <th width="25%">수량</th>
                                <th width="20%">내용</th>
                            </tr>
                        </thead>
                       <tbody>
						<tr>
						<td colspan='4' class='p-5'>준비중입니다
						</td>
						</tr>
						</tbody>
                    </table>
                </div>
				
<? }?>
	
<?
if($history_page=='total'){
//d:20 ,c:18,b:16,a:14
$old=sql_fetch("select sum(if(cn_item='a',tr_price*0.14,0)) a_sum,sum(if(cn_item='b',tr_price*0.16,0)) b_sum,sum(if(cn_item='c',tr_price*0.18,0)) c_sum,sum(if(cn_item='d',tr_price*0.2,0)) d_sum,
sum(if(cn_item='a' or cn_item='b' or cn_item='c' or cn_item='d' ,1,0)) cnt from coin_item_trade_history_shining where fmb_id='{$member['mb_id']}' and tr_stats='3' ",1);


$now=sql_query("select  sum(ct_sell_price - ct_buy_price) amt from {$g5['cn_item_cart']} where mb_id='{$member['mb_id']}' and is_soled='1' ",1);	

$totals=$old[a_sum]+$old[b_sum]+$old[c_sum]+$old[d_sum]+$new[amt];
	 ?>

                <div class="tab-content ">
                    <div class='h5 text-center my-5'>나의 총 수익 발생 금액</div>
					<div class='h5 text-center' >$<?=number_format2($totals)?></div>
                </div>
				
<? }?>			
            </div>
        </div>
		
 
<?	
include_once('../_tail.php');
?>
