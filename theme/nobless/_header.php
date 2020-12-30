    <article class="wrapper">
	
		<?
		if(defined("_INDEX_")){?>  
      <article class="loading__container active" id="loading_container">
        <div class="loader"></div>
      </article>	  
	  <? }?>
	  
      <article class=" <?=defined("_INDEX_")?"main__container":"sub__container"?>
	  <?
	  if(defined("IS_WALLET")) echo "wallet__container";
	  else if(defined("IS_ENTRUST")) echo "entrust__container";
	  else if(defined("IS_COMMUNIT")) echo "community__container";
	  else if(defined("IS_QR")) echo "qr__container";
	  ?>
	  " id="main_container">	  	
		
        <section class="header__box <?=!defined("_INDEX_")?"d-close":""?>">
          <!-- <div class="ico menu"></div> -->
        </section>
        <section class="banner__box   <?=!defined("_INDEX_")?"d-close":""?>">
          <div class="swiper-container">
            <div class="swiper-wrapper">
              <!-- Slides -->
			  <?
			  echo str_repeat('
              <div class="swiper-slide">
                <section class="title__box">
                  <section class="logo__box">
                    <div class="ico wallet">
                      wallet
                    </div>
                  </section>
                  <section class="title"><b style="color:#278cff;">ITEN</b> Launching</section>
                  <section class="sub-title">
                    The new <b>trading platform</b>
                  </section>
                </section>
              </div>
			  ',3)?>
              
            </div>
            <!-- If we need pagination -->
            <div class="swiper-pagination"></div>
          </div>
        </section>
		
	
	<?
	$list_header=$list_footer='';
	ob_start();
	?>
				
	
        <section class="wallet__list">
          <ul>
		  
		  
            <li id="wallet" class='wallet  <?=!defined("IS_WALLET") && !defined("_INDEX_")?"d-close":""?>'>						
				<div class='phase phase-intro  <?=!defined("_INDEX_")?"d-close":""?>' >
					  <div class='wallet-title' >
					  My Total Balance <span>ITEN</span>
					  </div>
					  <div class='wallet-amount' >
					   $ 1343.441271			   
					   <div class='wallet-arrow'>＞</div>
					  </div>
					  <div class='wallet-stitle' > = 0.003778<em>BTC</em> </div>
				  </div>
				  
				  <div class='phase phase-main'>
				  <?
				  
				  if(defined("IS_WALLET"))  $list_header= ob_get_contents();
				  include "./assets.main.inc.php";
				  
				  if(defined("IS_WALLET")) {
				  ob_clean();
				  ob_start();
				  }
				  ?>
				  </div>						  
            </li>
			
			
            <li id="entrust" class="entrust <?=!defined("IS_ENTRUST")&&!defined("_INDEX_")?"d-close":""?>">
				
				  <div class='phase phase-intro <?=!defined("_INDEX_")?"d-close":""?>'>
					  <div class='wallet-title' >
						Entrust Contract <span>ITEN</span>
					  </div>
					  <div class='wallet-amount' >
					   1.441271	<em>BTC</em>		   
					   <div class='wallet-arrow'>＞</div>
					  </div>
					  <div class='wallet-stitle' >= $ 1.925</div>
				  </div>
				  <div class='phase phase-main'>
				  <?
				  if(defined("IS_ENTRUST"))  $list_header= ob_get_contents();
				  include "./entrust.main.inc.php";
				  
				  if(defined("IS_ENTRUST")) {
				  ob_clean();
				  ob_start();
				  }
				  ?>
				  </div>  			  
            </li>
			
			
            <li id="community" class="community <?=!defined("IS_COMMUNITY")&&!defined("_INDEX_")?"d-close":""?>">
				<div class='phase phase-intro <?=!defined("_INDEX_")?"d-close":""?>'>
				  <div class='wallet-title' >
					Community   |   Daily Mining <span>ITEN</span>
				  </div>
				  <div class='wallet-amount' >
				   4.441271	<em>BTC</em>		   
				   <div class='wallet-arrow'>＞</div>
				  </div>
				  <div class='wallet-stitle' > = $ 3.925</div>
				  </div>
				  
				  <div class='phase phase-main'>
				  <?
				  if(defined("IS_COMMUNITY"))  $list_header= ob_get_contents();
				  //include "./entrust.main.inc.php";
				  
				  if(defined("IS_COMMUNITY"))  {
				  ob_clean();
				  ob_start();
				  }
				  ?>
				  </div>  	
				  
            </li>
			
			
            <li id="qr" class="qr <?=!defined("IS_QR")&&!defined("_INDEX_")?"d-close":""?>">
				<div class='phase phase-intro <?=!defined("_INDEX_")?"d-close":""?>'>
					  <div class='wallet-title' >
					   My QR Code Scan <span>ITEN</span>
					  </div>
					  <div class='wallet-amount' >
					   QR code  
					   <div class='wallet-qr' ><div  id='qrcode'></div></div>

					  </div>  
				  </div>
				  
				  <div class='phase phase-main'>
				  <?
				  if(defined("IS_QR"))  $list_header= ob_get_contents();
				  //include "./entrust.main.inc.php";
				  
				  if(defined("IS_QR")){
				  ob_clean();
				  ob_start();
				  }
				  ?>
				  </div>  	
				  
            </li>			
			
          </ul>
        </section>
		<?
		
		 $list_footer= ob_get_contents();
		 ob_clean();
		?> 
		
		
