<?php
include_once('./_common.php');

//서비스 블럭
service_block();
if ($_POST['w']=='new') {
	$sql = "update {$g5['cn_sub_account']} set ac_auto_{$_POST[type]} = '0' where mb_id = '{$member[mb_id]}'";
	$result= sql_query($sql);
	for($i=1;$i<=$_POST['cnt'];$i++){
		$ac_id = $member['mb_id'].".".str_pad($i,"2","0",STR_PAD_LEFT);
		$sql = "update {$g5['cn_sub_account']} set ac_auto_{$_POST[type]} = '1' where ac_id = '{$ac_id}' ORDER BY ac_no asc LIMIT 1";
		$result= sql_query($sql);
		$log_sql = "insert into coin_item_matching_log set 
				ac_auto_{$_POST[type]} = '{$_POST['cnt']}',
				mb_id = '{$member['mb_id']}'
				log_wdate = now()";
	}
    //sql_query($log_sql);

} else {
    if ($w == 'm') {
        $sql = " update {$g5['member_table']}
                set 				
				mb_auto_all		 = '{$_POST[mb_auto_all]}'
				where mb_id='$member[mb_id]'";
    
        //echo $sql;
        $result= sql_query($sql);
    
        if ($result) {
            alert_json(true, 'ok');
        } else {
            alert_json(false, '저장 할 수 없습니다');
        }
    }

    $a_block=$b_block=$c_block=0;

    if ($w == 'mu' || $w == 'au') {

    
    /*
    $yesterday=date("Y-m-d",strtotime('-1 days'));
    $today=date("Y-m-d");

    //오늘 매칭여부
    $mx_trade=sql_fetch("select tr_code,tr_rdate from {$g5['cn_item_trade']} order by tr_rdate desc limit 1",1);
    $p_trade=sql_fetch("select tr_code,tr_rdate from {$g5['cn_item_trade']}  where tr_wdate='$yesterday' limit 1",1);
    $n_trade=sql_fetch("select tr_code,tr_rdate from {$g5['cn_item_trade']}  where tr_wdate='$today' limit 1",1);

    //오늘 매칭이 있는 경우
    if($n_trade[tr_code]){

        //오늘 구매가 있는 경우 블럭
        $n_buy = sql_fetch("select  sum(if(cn_item='a',1,0)) a_cnt,sum(if(cn_item='b',1,0)) b_cnt,sum(if(cn_item='c',1,0)) c_cnt from {$g5['cn_item_purchase']} where mb_id='$member[mb_id]' and  it_wdate > '$n_trade[tr_rdate]'  and it_stats in ('1','2','3') ",1);

        if($n_buy[a_cnt] && $n_buy[a_cnt]*1 > 0 ) $a_block=1;
        if($n_buy[b_cnt] && $n_buy[b_cnt]*1 > 0 ) $b_block=1;
        if($n_buy[c_cnt] && $n_buy[c_cnt]*1 > 0 ) $c_block=1;

    //어제 매칭만 있는 경우
    }else if($p_trade[tr_code]){

        //어제 이후 구매가 있는 경우
        $p_buy = sql_fetch("select sum(if(cn_item='a',1,0)) a_cnt,sum(if(cn_item='b',1,0)) b_cnt,sum(if(cn_item='c',1,0)) c_cnt from {$g5['cn_item_purchase']} where mb_id='$member[mb_id]' and  it_wdate > '$p_trade[tr_rdate]'  and it_stats in ('1','2','3') ",1);

        if($p_buy[a_cnt] && $p_buy[a_cnt]*1 > 0 ) $a_block=1;
        if($p_buy[b_cnt] && $p_buy[b_cnt]*1 > 0 ) $b_block=1;
        if($p_buy[c_cnt] && $p_buy[c_cnt]*1 > 0 ) $c_block=1;

    }else{

        if($mx_trade[tr_code]) $sql=" and it_wdate > '$mx_trade[tr_rdate]' "	;
        else $sql='';
        //구매가 있는 경우
        $p_buy = sql_fetch("select sum(if(cn_item='a',1,0)) a_cnt,sum(if(cn_item='b',1,0)) b_cnt,sum(if(cn_item='c',1,0)) c_cnt from {$g5['cn_item_purchase']} where mb_id='$member[mb_id]' $sql  and it_stats in ('1','2','3') ",1);

        if($p_buy[a_cnt] && $p_buy[a_cnt]*1 > 0 ) $a_block=1;
        if($p_buy[b_cnt] && $p_buy[b_cnt]*1 > 0 ) $b_block=1;
        if($p_buy[c_cnt] && $p_buy[c_cnt]*1 > 0 ) $c_block=1;
    }
    */
    
        //무조건 블럭
        $p_buy = sql_fetch("select sum(if(cn_item='a',1,0)) a_cnt,sum(if(cn_item='b',1,0)) b_cnt,sum(if(cn_item='c',1,0)) c_cnt from {$g5['cn_item_purchase']} where mb_id='$member[mb_id]'  and it_stats in ('1','2','3') ", 1);
                
        if ($p_buy[a_cnt] && $p_buy[a_cnt]*1 > 0) {
            $a_block=1;
        }
        if ($p_buy[b_cnt] && $p_buy[b_cnt]*1 > 0) {
            $b_block=1;
        }
        if ($p_buy[c_cnt] && $p_buy[c_cnt]*1 > 0) {
            $c_block=1;
        }
    }


    //오토매칭
    if ($w == 'mu') {
        $rpoint=get_mempoint($member[mb_id]);
    
        if (!$_POST[mb_auto_a]) {
            $_POST[mb_auto_b]=0;
        }
        if (!$_POST[mb_auto_b]) {
            $_POST[mb_auto_c]=0;
        }
                        
        $sql = " update {$g5['member_table']}
                set 				
				mb_auto_a		 = '{$_POST[mb_auto_a]}',
				mb_auto_b		 = '{$_POST[mb_auto_b]}',
				mb_auto_c		 = '{$_POST[mb_auto_c]}',
				mb_auto_d		 = '{$_POST[mb_auto_d]}',
				mb_auto_e		 = '{$_POST[mb_auto_e]}',
				mb_auto_f		 = '{$_POST[mb_auto_f]}',
				mb_auto_g		 = '{$_POST[mb_auto_g]}',
				mb_auto_h		 = '{$_POST[mb_auto_h]}'
				
				where mb_id='$member[mb_id]'";
                
    
        //echo $sql;
        $result= sql_query($sql);
    
        if ($result) {
            alert_json(true, 'ok');
        } else {
            alert_json(false, '저장 할 수 없습니다');
        }
    }

    //오토매칭
    if ($w == 'au') {
        $chkarr=array();
        $smb=get_submember($_POST[ac_id]);
        $added=0;
        $prev='';
        $rpoint=get_mempoint($member[mb_id], $_POST[ac_id]);
    
        foreach (array('a','b','c','d','e','f','g','h') as $v) {
        
        //전단계 미설정시
            if ($prev && !isset($_POST['ac_auto_'.$prev])) {
                $_POST['ac_auto_'.$prev]=0;
            }
        
            if (!isset($_POST['ac_auto_'.$v])) {
                $_POST['ac_auto_'.$v]=0;
            }
    
            if ($smb['ac_auto_'.$v]==0 && $_POST['ac_auto_'.$v]=='1') {
                $added++;
            }
        
            if ($_POST['ac_auto_'.$v]=='1') {
                $chkarr[$_POST[ac_no]][]=$v;
            }
        
            $prev=$v;
        }
        if ($smb[ac_active]!='1') {
            alert_json(false, '활성화되지 않은 계정입니다');
        }

        $srpoint=get_mempoint($member['mb_id'], $_POST[ac_id]);
        if ($srpoint['i']['_enable']<100) {
            alert_json(false, '최소 꽃송이는 100개부터 예약구매가 가능합니다.', $chkarr);
        }
    
        if (($_POST['ac_auto_a']=='1' && $rpoint['i']['_enable'] < $g5['cn_item']['a']['fee']) || ($_POST['ac_auto_b']=='1' && $rpoint['i']['_enable'] < $g5['cn_item']['b']['fee']) ||  ($_POST['ac_auto_c']=='1' && $rpoint['i']['_enable'] < $g5['cn_item']['c']['fee'])) {
            foreach (array('a','b','c','d','e','f','g','h') as $v) {
                if ($smb['ac_auto_'.$v]==1) {
                    $chkarr[$_POST[ac_no]][]=$v;
                }
            }
    
            alert_json(false, '최소 꽃송이가 부족합니다', $chkarr);
        }
    
    
        //if($added > 0 && $srpoint['i']['_enable'] < $cset[staking_amt]) alert_json(false,'최소 보유금액이 부족합니다.');
        //if($added > 0 && $smb[ac_active]!='1' ) alert_json(false,'활성화되지 않은 계정입니다');
    
        //취소 불가 처리
        if ($smb[ac_auto_a]=='1' && $a_block) {
            $_POST[ac_auto_a]=1;
            if (!in_array('a', $chkarr[$_POST[ac_no]])) {
                $chkarr[$_POST[ac_no]][]='a';
            }
        }
        if ($smb[ac_auto_b]=='1' && $b_block) {
            $_POST[ac_auto_b]=1;
            if (!in_array('b', $chkarr[$_POST[ac_no]])) {
                $chkarr[$_POST[ac_no]][]='b';
            }
        }
        if ($smb[ac_auto_c]=='1' && $c_block) {
            $_POST[ac_auto_c]=1;
            if (!in_array('c', $chkarr[$_POST[ac_no]])) {
                $chkarr[$_POST[ac_no]][]='c';
            }
        }
    
    
        $sql = " update {$g5['cn_sub_account']}
                set 				
				ac_auto_a		 = '{$_POST[ac_auto_a]}',
				ac_auto_b		 = '{$_POST[ac_auto_b]}',
				ac_auto_c		 = '{$_POST[ac_auto_c]}',
				ac_auto_d		 = '{$_POST[ac_auto_d]}',
				ac_auto_e		 = '{$_POST[ac_auto_e]}',
				ac_auto_f		 = '{$_POST[ac_auto_f]}',
				ac_auto_g		 = '{$_POST[ac_auto_g]}',
				ac_auto_h		 = '{$_POST[ac_auto_h]}'
				
				where ac_id='$_POST[ac_id]'";
        //echo $sql;
        $result= sql_query($sql);
        $log_sql = "insert into coin_item_matching_log set 
			ac_auto_a		 = '{$_POST[ac_auto_a]}',
			ac_auto_b		 = '{$_POST[ac_auto_b]}',
			ac_auto_c		 = '{$_POST[ac_auto_c]}',
			ac_auto_d		 = '{$_POST[ac_auto_d]}',
			ac_auto_e		 = '{$_POST[ac_auto_e]}',
			ac_auto_f		 = '{$_POST[ac_auto_f]}',
			ac_auto_g		 = '{$_POST[ac_auto_g]}',
			ac_auto_h		 = '{$_POST[ac_auto_h]}',
			mb_id = '{$member['mb_id']}',
			ac_id = '{$_POST[ac_id]}',
			log_wdate = now()";
        sql_query($log_sql);
    
        if ($result) {
            alert_json(true, 'ok', $chkarr);
        } else {
            alert_json(false, '저장 할 수 없습니다'.$sql);
        }
    }

    //전체오토매칭
    if ($w == 'al') {
        if ($rpoint['i']['_enable'] < 20) {
            alert_json(false, '최소 꽃송이가 부족합니다');
        }
        //취소 불가 처리
        if ($a_block) {
            $a_auto_sql="ac_auto_a  = ac_auto_a,";
        } else {
            "ac_auto_a  = '0',";
        }
        if ($b_block) {
            $b_auto_sql="ac_auto_b  = ac_auto_b,";
        } else {
            "ac_auto_b  = '0',";
        }
        if ($c_block) {
            $c_auto_sql="ac_auto_c  = ac_auto_c,";
        } else {
            "ac_auto_c  = '0',";
        }
    
    
        //전체 선택
        if ($mb_auto_all=='1') {
        
        //$isum=get_itemsum($member[mb_id]);
        
            //$enable_amt=$member[mb_trade_amtlmt]-$isum[tot][price];
            //$enable_amt=$member[mb_trade_amtlmt];
        
            $paid=0;
        
            $chkarr=array();
        
            $cn_item=array_reverse($g5[cn_item]);
        
            //서브계정 전체 취소
            sql_query("update {$g5['cn_sub_account']} 
		set 				
		$a_auto_sql
		$b_auto_sql
		$c_auto_sql
		ac_auto_d		 = '0',
		ac_auto_e		 = '0',
		ac_auto_f		 = '0',
		ac_auto_g		 = '0',
		ac_auto_h		 = '0'

		where mb_id='{$member[mb_id]}'  ", 1);
        
            $result=sql_query(" select *  from  {$g5['cn_sub_account']} where mb_id='{$member['mb_id']}' and ac_active='1'  order by ac_id asc", 1);
        
            while ($data=sql_fetch_array($result)) {
                $set='';
                $chkarr[$data[ac_no]]=array();
            
                foreach ($cn_item as $k => $v) {
                
                //$paid+=$v[price];
                    //echo $enable_amt.'/'.$v[price].'/'. $paid."<br>";
                
                    //if($enable_amt <= $paid ) break;
                
                    $set.=($set?",":"")." ac_auto_".$k." = '1' ";
                
                    $chkarr[$data[ac_no]][]=$k;
                }
            
                if ($a_block) {
                    $chkarr[$data[ac_no]][]='a';
                }
                if ($b_block) {
                    $chkarr[$data[ac_no]][]='b';
                }
                if ($c_block) {
                    $chkarr[$data[ac_no]][]='c';
                }

            
                $sql="update {$g5['cn_sub_account']}
				set 				
				$set				
				where ac_no='{$data[ac_no]}' ";
                        
                if ($set!='') {
                    $re=sql_query($sql, 1);
                }
            
                //echo $sql;
            }
            
            //if($enable_amt<=0) alert_json(false,'설정금액을 확인하세요');
    
    //전체 취소
        } elseif ($mb_auto_all!='1') {
            $sql="update {$g5['cn_sub_account']} 
				set 				
				$a_auto_sql
				$b_auto_sql
				$c_auto_sql
				ac_auto_d		 = '0',
				ac_auto_e		 = '0',
				ac_auto_f		 = '0',
				ac_auto_g		 = '0',
				ac_auto_h		 = '0'
				
				where mb_id='{$member[mb_id]}'  ";
        
            //서브계정 전체 취소
            $result=sql_query($sql, 1);
            //echo $sql;
        }
        
    
        alert_json(true, 'ok', $chkarr);
        //else alert_json(false,'저장 할 수 없습니다');
    }
}
