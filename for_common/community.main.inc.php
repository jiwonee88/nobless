<?
add_stylesheet(" <link rel=\"stylesheet\" type=\"text/css\" href='".G5_THEME_URL."/css/community.css' >");

//어제 
$yesterdate=date("Y-m-d",strtotime('-1 days'));
$profit = sql_fetch("select sum(amount) amt from  {$g5['cn_point']} as a  where a.mb_id='{$member['mb_id']}' and pkind='stake_re' and pdate='$yesterdate' and pt_coin='i'",1);

//총 추천인
$recommend = sql_fetch("select count(*) cnt from  {$g5['cn_tree']} as a  where a.mb_id='{$member['mb_id']}' and smb_id!='' ",1);

//스테이킹 수량
$stake_usd=0;
foreach($g5['cn_cointype'] as $k=> $v) $stake_usd+=swap_usd($rpoint[$k]['stake']+$rpoint[$k]['stake_out'],$k,$sise);
$stake_btc=swap_coin($stake_usd,'d','b',$sise);

//예정 마이닝
if(abs($stake_usd) >= $cset['staking_amt']){
	$mine_usd=$stake_usd*$cset['staking_reward']/100;
	$mine_iten=swap_coin($mine_usd,'d','i',$sise);
}

//각종 수당
$fee_usd=0;
foreach($g5['cn_cointype'] as $k=> $v) $fee_usd+=swap_usd($rpoint[$k]['fee'],$k,$sise);

$fee2_usd=0;
foreach($g5['cn_cointype'] as $k=> $v) $fee2_usd+=swap_usd($rpoint[$k]['fee2'],$k,$sise);

$fee3_usd=0;
foreach($g5['cn_cointype'] as $k=> $v) $fee3_usd+=swap_usd($rpoint[$k]['fee3'],$k,$sise);
?>   
    
<div class='wrapper-community-main' >
	<div style="min-height: 0;" class='wrapper-header wrapper-header-assets'>
		<section class="wrapper-header-top">
			<div class="back"></div>
			<h1 class="title" style="margin-top: 3px;">Community</h1>
		</section>
			
    </div>
    <div style="margin: 0px 10px 20px 10px;" class="assets-total-wrap">
		<div class="card">
      <div class="card-title">Yesterday Mining</div>
      <div class="card-content">
        <section class="money">

          <b><?=number_format2($profit[amt],6)?><span style="font-weight: 300;">ITEN</span></b>

      </section>

      <section class="sub-money">

          <span style="font-weight: 300;">≈  </span><span style="font-weight: 500; letter-spacing: 2px;">$ <?=number_format2(swap_usd($profit[amt],'i'),2)?></span>

      </section>

      </div>
    </div>

    <div class="card">
      <div class="card-title">
        Partner
		<div class="mini-button"><a href='/for_common/community.detail.php'>detail</a></div>
      </div>
      <div class="card-content" style="padding-top: 10px;">

        <p>Community</p>

        <div class="people">
          <div class="people-icon"></div>
         <?=number_format($recommend['cnt'])?>
        </div>
      </div>
    </div>

    <div class="card">
      <div class="card-title">
        Income Detail
      </div>
      <div class="card-content" style=" padding: 20px; padding-top: 0;    letter-spacing: -.2px; padding-bottom: 0;     font-size: 11pt;">
        <div class="detail-list">
		  
          <div class="list-item">
            <div class="item-title">Staking Income</div>
            <div class="item-content">≈$<?=number_format2(abs($mine_usd),2)?><span><?=number_format2(abs($mine_iten),6)?> ITEN</span></div>
          </div>
		  
          <div class="list-item">
            <div class="item-title">Direct Market Income</div>
            <div class="item-content">≈$<?=number_format2($fee_usd,2)?><span><?=number_format2(swap_usd($fee_usd,'i'),6)?> ITEN</span></div>
          </div>
          <div class="list-item">
            <div class="item-title">Community Market Income</div>
            <div class="item-content">≈$<?=number_format2($fee2_usd,2)?><span><?=number_format2(swap_usd($fee2_usd,'i'),6)?> ITEN</span></div>
          </div>
          <div class="list-item" style="border-bottom: 0">
            <div class="item-title">Rank more Bonus</div>
            <div class="item-content">≈$<?=number_format2($fee3_usd,2)?><span><?=number_format2(swap_usd($fee3_usd,'i'),6)?> ITEN</span></div>
          </div>
        </div>
      </div>
    </div>
    </div>

</div>
