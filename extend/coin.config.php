<?php
if (!defined('_GNUBOARD_')) {
    exit;
} // 개별 페이지 접근 불가

define('G5_CN_ADMIN_DIR', 'coin_admin');
define('G5_CN_ADMIN_PATH', G5_ADMIN_PATH.'/'.G5_CN_ADMIN_DIR);
define('G5_CN_ADMIN_URL', G5_ADMIN_URL.'/'.G5_CN_ADMIN_DIR);

//테이블 정의
$g5['cn_prefix']                = 'coin_';
$g5['cn_sub_account'] =$g5['cn_prefix'].'sub_account'; // 계정테이블

$g5['cn_hp_certi'] =$g5['cn_prefix'].'hp_certi'; // 휴대폰 계정테이블

$g5['cn_set'] =$g5['cn_prefix'].'setting'; // 지급 설정 테이블

$g5['cn_tree'] =$g5['cn_prefix'].'tree'; 				// 추천인 단계
$g5['cn_point'] =$g5['cn_prefix'].'point'; 				// 수당내역 테이블
$g5['cn_pointsum'] =$g5['cn_prefix'].'pointsum'; 				// 수당내역 합산 테이블
$g5['cn_token_table'] =$g5['cn_prefix'].'token_addr'; 				// 배당할 토근 목록
$g5['cn_purchase_table'] =$g5['cn_prefix'].'in_purchase'; 				// 기본재화(토큰) 구매 테이블
$g5['cn_reserve_table'] =$g5['cn_prefix'].'in_reserve'; 				// 코인입금
$g5['cn_draw_table'] =$g5['cn_prefix'].'draw_out'; 				//출금 내역
$g5['cn_swap_table'] =$g5['cn_prefix'].'swap'; 				//스왑 내역
$g5['cn_transfer_table']	 =$g5['cn_prefix'].'transfer'; 			//  계정간 이체

$g5['cn_item_info'] =$g5['cn_prefix'].'item_info'; 			// 상품 정보 테이블

$g5['cn_item_cart'] =$g5['cn_prefix'].'item_cart'; 			// 상품 보유 테이블
$g5['cn_item_trade'] =$g5['cn_prefix'].'item_trade'; 		// 상품 매칭 테이블
$g5['cn_item_trade_test'] =$g5['cn_prefix'].'item_trade_test'; 		// 상품 매칭 테스트 테이블

$g5['cn_item_log'] =$g5['cn_prefix'].'item_log'; 		// 상품 매칭 로그

$g5['cn_set_table'] =$g5['cn_prefix'].'settle'; 		// 정산내역테이블
$g5['cn_set2_table'] =$g5['cn_prefix'].'settle2'; 		// 정산내역테이블
$g5['cn_set3_table'] =$g5['cn_prefix'].'settle3'; 		// 정산내역테이블
$g5['cn_sise_table'] =$g5['cn_prefix'].'sise'; 			// 코인별 시세


$g5['cn_item_purchase'] =$g5['cn_prefix'].'item_purchase'; 				// 기본재화(토큰) 구매 테이블


//수당 이름
$g5['cn_point_name'] ='수당';

//수당 단위
$g5['cn_point_unit'] ='개';

//수당 달러 환산
$g5['cn_point_usd'] =0.88;

//입금 마감 지 시간
$g5['cn_intime_hour'] =2;

//원화시세
$g5['cn_won_usd'] =1200;


//회원레벨명 변경
$g5['member_level_name']=array(
'10'=>'관리자',
'9'=>'관리직원',
'5'=>'정회원',
'1'=>'손님',
);

//참여 등급-직접 추천 인원에 따른 등급-관리자급 제외하고 자동승격
$g5['member_grade']=array(
'0'=>'크라운',
'1'=>'더블크라운',
'2'=>'트리플크라운',
);

$g5['member_grade_css']=array(
'0'=>'light',
'1'=>'primary',
'2'=>'success',
'3'=>'info',
'4'=>'warning',
'5'=>'danger',
'6'=>'secondary',
'7'=>'dark',
'8'=>'dark',

);

//직급자 멤버
$g5['member_grade2']=array('1','2','3','4','5','6','7');

//지갑별 구분
$g5['cn_wallet']=array(
"free"=>"WALLET",
//"stable"=>"고정계정",
//"out"=>"출금계정",
);

//화폐의 구분
$g5['cn_cointype']=array(
"b"=>"꿀단지",
"e"=>"매너포인트",
"i"=>"송이",
"u"=>"USDT",
"s"=>"쇼핑포인트",
);

//화폐의 구분
$g5['cn_cointype_sym']=array(
"b"=>"꿀단지",
"e"=>"매너포인트",
"i"=>"송이",
"u"=>"TetherUS",
"s"=>"쇼핑포인트",
);

//입금이 가능한 코인 - 가입시 입금 주소 자동 발급
$g5['cn_coin_in']=array('u');

//출금이 가능한 코인 - 가입시 입금 주소 자동 발급
$g5['cn_coin_out']=array('u');

//지불코인
$g5['cn_pay_coin']='u';

//수수료 코인
$g5['cn_fee_coin']='i';

//지급 코인
$g5['cn_reward_coin']='i';

//상점 이용 포인트
$g5['cn_shop_coin']='e';

//거래 구분
$g5['cn_pkind']=array(
"pin"=>"Deposit",//구매입금",
"in"=>"Deposit",//입금",
"joinb"=>"Joinning Bonus",//가입축하금",
"buy"=>"Purchase",//입금",
"mfee"=>"Matching Fee",//매칭 구매 수수료",
"mfee2"=>"Matching Fee",//매칭 판매 수수료",

"itembuy"=>"Item Purchaing",	//상품 구매,
"itemmat"=>"Item Matching",	//상품 매칭 분양(꿀단지),


"cashbonus"=>"Cash Bonus",//오픈기념 현금구매 환급",
"pnfine"=>"Penalty Fine",	//패널티 벌금",
"pnreward"=>"Penalty Reward",	//패널티 보상금",
"promoted"=>"Promote Reward",	//추천인 보상금",

"fee"=>"Direct Market Income",//추천인 하부 롤업",
"fee2"=>"Sub Account Income",	//서브계정 롤업",
"fee3"=>"Rank more Bonus",	//그룹보너스",
"out"=>"Withdrawal",//출금",			//음수
"outing"=>"In Withdrawing",//출금중",
"transin"=>"Transfer deposit", //계정간입금",
"transout"=>"Transfer withdrawal",//계정간출금",	//음수
"transfee"=>"Transfer fee",		//계정간출금 수수료 iten",	//음수
"sending"=>"In sending",		//전송중",	//음수
"swap_out"=>"Swap Out",		// 스왑 코인간 전환
"swap_in"=>"Swap In",		// 스왑 코인간 전환

"mtransin"=>"Manner point in", //매너포인트 입급
"mtransout"=>"Manner point trans out",//매너 포인트 변환 출금",	//음수

"burnin"=>"Convert NAVI to point", //나비 소각",

"change_out"=>"Convert Point Out", //나비 소각",
"change_in"=>"Convert Point In", //나비 소각",

"act_bonus"=>"Sub-account Activation Point", //서브계정 활성화 포인트",
);

//승인 상태
$g5['deposit_stat']=array(
"1"=>"Submit",
"2"=>"Processing",
"3"=>"completed"
);


//수당 이전 구분
$g5['pointtrans_kind']=array(
"etc"=>"기타수당",
"ptc"=>"구매수량",
);

//공용 처리 상태
$g5['cn_instats']=array(
"1"=>"Submit",
"2"=>"Processing",
"3"=>"Completed",
"4"=>"Cancel"
);
$g5['cn_instats_css']=array(
"1"=>"light",
"2"=>"success",
"3"=>"primary",
"4"=>"danger"
);

//거래상태
$g5['purchase_stat']=array(
"1"=>"TXID 입력대기",
"2"=>"입금대기",
"3"=>"입금완료",
"4"=>"취소"
);

//거래상태
$g5['item_purchase_stat']=array(
"1"=>"Submit",
"2"=>"Processing",
"3"=>"Completed",
"4"=>"Cancel"
);

//매칭상태
$g5['tr_stat']=array(
"1"=>"입금대기",
"2"=>"송금완료",
"3"=>"거래완료",
"9"=>"취소"
);

//현재 스테이킹 상태
$g5['stake_stat_css']=array(
"1"=>"secondary",
"2"=>"success",
"3"=>"danger"
);

//공용 처리 상태
$g5['cn_stats_css']=array(
"0"=>"danger",
"1"=>"success",
);

//결제 방식
$g5['cn_paytype']=array(
"cash"=>"현금",
"usdt"=>"USDT",
"both"=>"현금/USDT",
);

// 상품구분 (e 코드 상품은 분할 판매 상품)
$g5['cn_item_name']='나비';

$g5['cn_item']=array();


$g5['cn_item']['a']=array(
'name_kr'=>"백작",
'days'=>3,
'interest'=>12,
'price'=>50,
'mxprice'=>300,
'fee'=>20,
'img'=>'member_1.png',
'img_label'=>'member_n1.png'
);

$g5['cn_item']['b']=array(
'name_kr'=>'공작',
'days'=>4,
'interest'=>14,
'price'=>100,
'mxprice'=>500,
'fee'=>40,
'img'=>'member_2.png',
'img_label'=>'member_n2.png'
);

$g5['cn_item']['c']=array(
'name_kr'=>'후작',
'days'=>5,
'interest'=>16,
'price'=>300,
'mxprice'=>1000,
'fee'=>60,
'img'=>'member_3.png',
'img_label'=>'member_n3.png'
);


$g5['cn_item_org']=$g5['cn_item'];

//골드 상품구분
$g5['cn_golditem']=array();
$g5['cn_golditem']['1000']=array(
'name_kr'=>'1000개 팩',
'amt'=>1000,
'price'=>100
);
$g5['cn_golditem']['2000']=array(
'name_kr'=>'2000개 팩',
'amt'=>2000,
'price'=>200
);
$g5['cn_golditem']['5000']=array(
'name_kr'=>'5000개 팩',
'amt'=>5000,
'price'=>500
);
$g5['cn_golditem']['10000']=array(
'name_kr'=>'10000개 팩',
'amt'=>10000,
'price'=>1000
);

//매너 포인트 지급 시간 조건
$g5['cn_bonus_hour1']=12;	//2시 이내
$g5['cn_bonus_hour_r1']=0; //0.1;
$g5['cn_bonus_hour_r2']=0; //0.03;
$g5['cn_bonus_hour9']=18;	//8 시 이후 미지급

//꽃송이 추가지급
$g5['cn_bonusf_hour']=9;	//2시 이내
$g5['cn_bonusf_hour_amt']=0; //20;

//트윌로 문자 서비스
$g5['cn_id']='AC72b90ec1b820e556d8b9d58b9ee9450f';
$g5['cn_token']='f20a25a6493679f5ae1d902827085b38';
$g5['cn_callback']="+12058284940";

$g5['cn_nation']='
<option data-countryCode="TH"  data-phone="66" value="Thailand">Thailand</option> 
<option data-countryCode="VN"  data-phone="84" value="Vietnam">Vietnam</option> 
<option data-countryCode="DZ"  data-phone="213" value="Algeria">Algeria</option> 
<option data-countryCode="AD"  data-phone="376" value="Andorra">Andorra</option> 
<option data-countryCode="AO"  data-phone="244" value="Angola">Angola</option> 
<option data-countryCode="AI"  data-phone="1264" value="Anguilla">Anguilla</option> 
<option data-countryCode="AG" value="1268">Antigua &amp; Barbuda (+1268)</option> 
<option data-countryCode="AR"  data-phone="54" value="Argentina">Argentina</option> 
<option data-countryCode="AM"  data-phone="374" value="Armenia">Armenia</option> 
<option data-countryCode="AW"  data-phone="297" value="Aruba">Aruba</option> 
<option data-countryCode="AU"  data-phone="61" value="Australia">Australia</option> 
<option data-countryCode="AT"  data-phone="43" value="Austria">Austria</option> 
<option data-countryCode="AZ"  data-phone="994" value="Azerbaijan">Azerbaijan</option> 
<option data-countryCode="BS"  data-phone="1242" value="Bahamas">Bahamas</option> 
<option data-countryCode="BH"  data-phone="973" value="Bahrain">Bahrain</option> 
<option data-countryCode="BD"  data-phone="880" value="Bangladesh">Bangladesh</option> 
<option data-countryCode="BB"  data-phone="1246" value="Barbados">Barbados</option> 
<option data-countryCode="BY"  data-phone="375" value="Belarus">Belarus</option> 
<option data-countryCode="BE"  data-phone="32" value="Belgium">Belgium</option> 
<option data-countryCode="BZ"  data-phone="501" value="Belize">Belize</option> 
<option data-countryCode="BJ"  data-phone="229" value="Benin">Benin</option> 
<option data-countryCode="BM"  data-phone="1441" value="Bermuda">Bermuda</option> 
<option data-countryCode="BT"  data-phone="975" value="Bhutan">Bhutan</option> 
<option data-countryCode="BO"  data-phone="591" value="Bolivia">Bolivia</option> 
<option data-countryCode="BA"  data-phone="387" value="Bosnia Herzegovina">Bosnia Herzegovina</option> 
<option data-countryCode="BW"  data-phone="267" value="Botswana">Botswana</option> 
<option data-countryCode="BR"  data-phone="55" value="Brazil">Brazil</option> 
<option data-countryCode="BN"  data-phone="673" value="Brunei">Brunei</option> 
<option data-countryCode="BG"  data-phone="359" value="Bulgaria">Bulgaria</option> 
<option data-countryCode="BF"  data-phone="226" value="Burkina Faso">Burkina Faso</option> 
<option data-countryCode="BI"  data-phone="257" value="Burundi">Burundi</option> 
<option data-countryCode="KH"  data-phone="855" value="Cambodia">Cambodia</option> 
<option data-countryCode="CM"  data-phone="237" value="Cameroon">Cameroon</option> 
<option data-countryCode="CA"  data-phone="1" value="Canada">Canada</option> 
<option data-countryCode="CV"  data-phone="238" value="Cape Verde Islands">Cape Verde Islands</option> 
<option data-countryCode="KY"  data-phone="1345" value="Cayman Islands">Cayman Islands</option> 
<option data-countryCode="CF"  data-phone="236" value="Central African Republic">Central African Republic</option> 
<option data-countryCode="CL"  data-phone="56" value="Chile">Chile</option> 
<option data-countryCode="CN"  data-phone="86" value="China">China</option> 
<option data-countryCode="CO"  data-phone="57" value="Colombia">Colombia</option> 
<option data-countryCode="KM"  data-phone="269" value="Comoros">Comoros</option> 
<option data-countryCode="CG"  data-phone="242" value="Congo">Congo</option> 
<option data-countryCode="CK"  data-phone="682" value="Cook Islands">Cook Islands</option> 
<option data-countryCode="CR"  data-phone="506" value="Costa Rica">Costa Rica</option> 
<option data-countryCode="HR"  data-phone="385" value="Croatia">Croatia</option> 
<option data-countryCode="CU"  data-phone="53" value="Cuba">Cuba</option> 
<option data-countryCode="CY"  data-phone="90392" value="Cyprus North">Cyprus North</option> 
<option data-countryCode="CY"  data-phone="357" value="Cyprus South">Cyprus South</option> 
<option data-countryCode="CZ"  data-phone="42" value="Czech Republic">Czech Republic</option> 
<option data-countryCode="DK"  data-phone="45" value="Denmark">Denmark</option> 
<option data-countryCode="DJ"  data-phone="253" value="Djibouti">Djibouti</option> 
<option data-countryCode="DM"  data-phone="1809" value="Dominica">Dominica</option> 
<option data-countryCode="DO"  data-phone="1809" value="Dominican Republic">Dominican Republic</option> 
<option data-countryCode="EC"  data-phone="593" value="Ecuador">Ecuador</option> 
<option data-countryCode="EG"  data-phone="20" value="Egypt">Egypt</option> 
<option data-countryCode="SV"  data-phone="503" value="El Salvador">El Salvador</option> 
<option data-countryCode="GQ"  data-phone="240" value="Equatorial Guinea">Equatorial Guinea</option> 
<option data-countryCode="ER"  data-phone="291" value="Eritrea">Eritrea</option> 
<option data-countryCode="EE"  data-phone="372" value="Estonia">Estonia</option> 
<option data-countryCode="ET"  data-phone="251" value="Ethiopia">Ethiopia</option> 
<option data-countryCode="FK"  data-phone="500" value="Falkland Islands">Falkland Islands</option> 
<option data-countryCode="FO"  data-phone="298" value="Faroe Islands">Faroe Islands</option> 
<option data-countryCode="FJ"  data-phone="679" value="Fiji">Fiji</option> 
<option data-countryCode="FI"  data-phone="358" value="Finland">Finland</option> 
<option data-countryCode="FR"  data-phone="33" value="France">France</option> 
<option data-countryCode="GF"  data-phone="594" value="French Guiana">French Guiana</option> 
<option data-countryCode="PF"  data-phone="689" value="French Polynesia">French Polynesia</option> 
<option data-countryCode="GA"  data-phone="241" value="Gabon">Gabon</option> 
<option data-countryCode="GM"  data-phone="220" value="Gambia">Gambia</option> 
<option data-countryCode="GE"  data-phone="7880" value="Georgia">Georgia</option> 
<option data-countryCode="DE"  data-phone="49" value="Germany">Germany</option> 
<option data-countryCode="GH"  data-phone="233" value="Ghana">Ghana</option> 
<option data-countryCode="GI"  data-phone="350" value="Gibraltar">Gibraltar</option> 
<option data-countryCode="GR"  data-phone="30" value="Greece">Greece</option> 
<option data-countryCode="GL"  data-phone="299" value="Greenland">Greenland</option> 
<option data-countryCode="GD"  data-phone="1473" value="Grenada">Grenada</option> 
<option data-countryCode="GP"  data-phone="590" value="Guadeloupe">Guadeloupe</option> 
<option data-countryCode="GU"  data-phone="671" value="Guam">Guam</option> 
<option data-countryCode="GT"  data-phone="502" value="Guatemala">Guatemala</option> 
<option data-countryCode="GN"  data-phone="224" value="Guinea">Guinea</option> 
<option data-countryCode="GW"  data-phone="245" value="Guinea - Bissau">Guinea - Bissau</option> 
<option data-countryCode="GY"  data-phone="592" value="Guyana">Guyana</option> 
<option data-countryCode="HT"  data-phone="509" value="Haiti">Haiti</option> 
<option data-countryCode="HN"  data-phone="504" value="Honduras">Honduras</option> 
<option data-countryCode="HK"  data-phone="852" value="Hong Kong">Hong Kong</option> 
<option data-countryCode="HU"  data-phone="36" value="Hungary">Hungary</option> 
<option data-countryCode="IS"  data-phone="354" value="Iceland">Iceland</option> 
<option data-countryCode="IN"  data-phone="91" value="India">India</option> 
<option data-countryCode="ID"  data-phone="62" value="Indonesia">Indonesia</option> 
<option data-countryCode="IR"  data-phone="98" value="Iran">Iran</option> 
<option data-countryCode="IQ"  data-phone="964" value="Iraq">Iraq</option> 
<option data-countryCode="IE"  data-phone="353" value="Ireland">Ireland</option> 
<option data-countryCode="IL"  data-phone="972" value="Israel">Israel</option> 
<option data-countryCode="IT"  data-phone="39" value="Italy">Italy</option> 
<option data-countryCode="JM"  data-phone="1876" value="Jamaica">Jamaica</option> 
<option data-countryCode="JP"  data-phone="81" value="Japan">Japan</option> 
<option data-countryCode="JO"  data-phone="962" value="Jordan">Jordan</option> 
<option data-countryCode="KZ"  data-phone="7" value="Kazakhstan">Kazakhstan</option> 
<option data-countryCode="KE"  data-phone="254" value="Kenya">Kenya</option> 
<option data-countryCode="KI"  data-phone="686" value="Kiribati">Kiribati</option> 
<option data-countryCode="KP"  data-phone="850" value="Korea North">Korea North</option> 
<option data-countryCode="KR"  data-phone="82" value="Korea South">Korea South</option> 
<option data-countryCode="KW"  data-phone="965" value="Kuwait">Kuwait</option> 
<option data-countryCode="KG"  data-phone="996" value="Kyrgyzstan">Kyrgyzstan</option> 
<option data-countryCode="LA"  data-phone="856" value="Laos">Laos</option> 
<option data-countryCode="LV"  data-phone="371" value="Latvia">Latvia</option> 
<option data-countryCode="LB"  data-phone="961" value="Lebanon">Lebanon</option> 
<option data-countryCode="LS"  data-phone="266" value="Lesotho">Lesotho</option> 
<option data-countryCode="LR"  data-phone="231" value="Liberia">Liberia</option> 
<option data-countryCode="LY"  data-phone="218" value="Libya">Libya</option> 
<option data-countryCode="LI"  data-phone="417" value="Liechtenstein">Liechtenstein</option> 
<option data-countryCode="LT"  data-phone="370" value="Lithuania">Lithuania</option> 
<option data-countryCode="LU"  data-phone="352" value="Luxembourg">Luxembourg</option> 
<option data-countryCode="MO"  data-phone="853" value="Macao">Macao</option> 
<option data-countryCode="MK"  data-phone="389" value="Macedonia">Macedonia</option> 
<option data-countryCode="MG"  data-phone="261" value="Madagascar">Madagascar</option> 
<option data-countryCode="MW"  data-phone="265" value="Malawi">Malawi</option> 
<option data-countryCode="MY"  data-phone="60" value="Malaysia">Malaysia</option> 
<option data-countryCode="MV"  data-phone="960" value="Maldives">Maldives</option> 
<option data-countryCode="ML"  data-phone="223" value="Mali">Mali</option> 
<option data-countryCode="MT"  data-phone="356" value="Malta">Malta</option> 
<option data-countryCode="MH"  data-phone="692" value="Marshall Islands">Marshall Islands</option> 
<option data-countryCode="MQ"  data-phone="596" value="Martinique">Martinique</option> 
<option data-countryCode="MR"  data-phone="222" value="Mauritania">Mauritania</option> 
<option data-countryCode="YT"  data-phone="269" value="Mayotte">Mayotte</option> 
<option data-countryCode="MX"  data-phone="52" value="Mexico">Mexico</option> 
<option data-countryCode="FM"  data-phone="691" value="Micronesia">Micronesia</option> 
<option data-countryCode="MD"  data-phone="373" value="Moldova">Moldova</option> 
<option data-countryCode="MC"  data-phone="377" value="Monaco">Monaco</option> 
<option data-countryCode="MN"  data-phone="976" value="Mongolia">Mongolia</option> 
<option data-countryCode="MS"  data-phone="1664" value="Montserrat">Montserrat</option> 
<option data-countryCode="MA"  data-phone="212" value="Morocco">Morocco</option> 
<option data-countryCode="MZ"  data-phone="258" value="Mozambique">Mozambique</option> 
<option data-countryCode="MN"  data-phone="95" value="Myanmar">Myanmar</option> 
<option data-countryCode="NA"  data-phone="264" value="Namibia">Namibia</option> 
<option data-countryCode="NR"  data-phone="674" value="Nauru">Nauru</option> 
<option data-countryCode="NP"  data-phone="977" value="Nepal">Nepal</option> 
<option data-countryCode="NL"  data-phone="31" value="Netherlands">Netherlands</option> 
<option data-countryCode="NC"  data-phone="687" value="New Caledonia">New Caledonia</option> 
<option data-countryCode="NZ"  data-phone="64" value="New Zealand">New Zealand</option> 
<option data-countryCode="NI"  data-phone="505" value="Nicaragua">Nicaragua</option> 
<option data-countryCode="NE"  data-phone="227" value="Niger">Niger</option> 
<option data-countryCode="NG"  data-phone="234" value="Nigeria">Nigeria</option> 
<option data-countryCode="NU"  data-phone="683" value="Niue">Niue</option> 
<option data-countryCode="NF"  data-phone="672" value="Norfolk Islands">Norfolk Islands</option> 
<option data-countryCode="NP"  data-phone="670" value="Northern Marianas">Northern Marianas</option> 
<option data-countryCode="NO"  data-phone="47" value="Norway">Norway</option> 
<option data-countryCode="OM"  data-phone="968" value="Oman">Oman</option> 
<option data-countryCode="PW"  data-phone="680" value="Palau">Palau</option> 
<option data-countryCode="PA"  data-phone="507" value="Panama">Panama</option> 
<option data-countryCode="PG"  data-phone="675" value="Papua New Guinea">Papua New Guinea</option> 
<option data-countryCode="PY"  data-phone="595" value="Paraguay">Paraguay</option> 
<option data-countryCode="PE"  data-phone="51" value="Peru">Peru</option> 
<option data-countryCode="PH"  data-phone="63" value="Philippines">Philippines</option> 
<option data-countryCode="PL"  data-phone="48" value="Poland">Poland</option> 
<option data-countryCode="PT"  data-phone="351" value="Portugal">Portugal</option> 
<option data-countryCode="PR"  data-phone="1787" value="Puerto Rico">Puerto Rico</option> 
<option data-countryCode="QA"  data-phone="974" value="Qatar">Qatar</option> 
<option data-countryCode="RE"  data-phone="262" value="Reunion">Reunion</option> 
<option data-countryCode="RO"  data-phone="40" value="Romania">Romania</option> 
<option data-countryCode="RU"  data-phone="7" value="Russia">Russia</option> 
<option data-countryCode="RW"  data-phone="250" value="Rwanda">Rwanda</option> 
<option data-countryCode="SM"  data-phone="378" value="San Marino">San Marino</option> 
<option data-countryCode="ST" value="239">Sao Tome &amp; Principe (+239)</option> 
<option data-countryCode="SA"  data-phone="966" value="Saudi Arabia">Saudi Arabia</option> 
<option data-countryCode="SN"  data-phone="221" value="Senegal">Senegal</option> 
<option data-countryCode="CS"  data-phone="381" value="Serbia">Serbia</option> 
<option data-countryCode="SC"  data-phone="248" value="Seychelles">Seychelles</option> 
<option data-countryCode="SL"  data-phone="232" value="Sierra Leone">Sierra Leone</option> 
<option data-countryCode="SG"  data-phone="65" value="Singapore">Singapore</option> 
<option data-countryCode="SK"  data-phone="421" value="Slovak Republic">Slovak Republic</option> 
<option data-countryCode="SI"  data-phone="386" value="Slovenia">Slovenia</option> 
<option data-countryCode="SB"  data-phone="677" value="Solomon Islands">Solomon Islands</option> 
<option data-countryCode="SO"  data-phone="252" value="Somalia">Somalia</option> 
<option data-countryCode="ZA"  data-phone="27" value="South Africa">South Africa</option> 
<option data-countryCode="ES"  data-phone="34" value="Spain">Spain</option> 
<option data-countryCode="LK"  data-phone="94" value="Sri Lanka">Sri Lanka</option> 
<option data-countryCode="SH" value="290">St. Helena (+290)</option> 
<option data-countryCode="KN" value="1869">St. Kitts (+1869)</option> 
<option data-countryCode="SC" value="1758">St. Lucia (+1758)</option> 
<option data-countryCode="SD"  data-phone="249" value="Sudan">Sudan</option> 
<option data-countryCode="SR"  data-phone="597" value="Suriname">Suriname</option> 
<option data-countryCode="SZ"  data-phone="268" value="Swaziland">Swaziland</option> 
<option data-countryCode="SE"  data-phone="46" value="Sweden">Sweden</option> 
<option data-countryCode="CH"  data-phone="41" value="Switzerland">Switzerland</option> 
<option data-countryCode="SI"  data-phone="963" value="Syria">Syria</option> 
<option data-countryCode="TW"  data-phone="886" value="Taiwan">Taiwan</option> 
<option data-countryCode="TJ"  data-phone="7" value="Tajikstan">Tajikstan</option> 
<option data-countryCode="TG"  data-phone="228" value="Togo">Togo</option> 
<option data-countryCode="TO"  data-phone="676" value="Tonga">Tonga</option> 
<option data-countryCode="TT" value="1868">Trinidad Tobago (+1868)</option> 
<option data-countryCode="TN"  data-phone="216" value="Tunisia">Tunisia</option> 
<option data-countryCode="TR"  data-phone="90" value="Turkey">Turkey</option> 
<option data-countryCode="TM"  data-phone="7" value="Turkmenistan">Turkmenistan</option> 
<option data-countryCode="TM"  data-phone="993" value="Turkmenistan">Turkmenistan</option> 
<option data-countryCode="TC" value="1649">Turks &amp; Caicos Islands (+1649)</option> 
<option data-countryCode="TV"  data-phone="688" value="Tuvalu">Tuvalu</option> 
<option data-countryCode="UG"  data-phone="256" value="Uganda">Uganda</option> 
<!-- <option data-countryCode="GB"  data-phone="44" value="UK">UK</option> --> 
<option data-countryCode="UA"  data-phone="380" value="Ukraine">Ukraine</option> 
<option data-countryCode="GB"  data-phone="44" value="UK">UK</option> 
<option data-countryCode="US"  data-phone="1" value="USA">USA</option> 
<option data-countryCode="AE"  data-phone="971" value="United Arab Emirates">United Arab Emirates</option> 
<option data-countryCode="UY"  data-phone="598" value="Uruguay">Uruguay</option> 
<!-- <option data-countryCode="US"  data-phone="1" value="USA">USA</option> --> 
<option data-countryCode="UZ"  data-phone="7" value="Uzbekistan">Uzbekistan</option> 
<option data-countryCode="VU"  data-phone="678" value="Vanuatu">Vanuatu</option> 
<option data-countryCode="VA"  data-phone="379" value="Vatican City">Vatican City</option> 
<option data-countryCode="VE"  data-phone="58" value="Venezuela">Venezuela</option> 
<option data-countryCode="VG"  data-phone="84" value="Virgin Islands - British">Virgin Islands - British</option> 
<option data-countryCode="VI"  data-phone="84" value="Virgin Islands - US">Virgin Islands - US</option> 
<option data-countryCode="WF"  data-phone="681" value="Wallis Futuna" ></option> 
<option data-countryCode="YE" data-phone="969" value="Yemen (North)">Yemen (North)</option> 
<option data-countryCode="YE" data-phone="967" alue="Yemen (South)">Yemen (South)</option> 
<option data-countryCode="ZM"  data-phone="260" value="Zambia">Zambia</option> 
<option data-countryCode="ZW"  data-phone="263" value="Zimbabwe">Zimbabwe</option>  
';

$g5['cn_nation_tel']='
<option data-countryCode="KR" value="82">Korea South (+82)</option> 
<option data-countryCode="TH" value="66">Thailand (+66)</option> 
<option data-countryCode="VN" value="84">Vietnam (+84)</option> 
<option data-countryCode="DZ" value="213">Algeria (+213)</option> 
<option data-countryCode="AD" value="376">Andorra (+376)</option> 
<option data-countryCode="AO" value="244">Angola (+244)</option> 
<option data-countryCode="AI" value="1264">Anguilla (+1264)</option> 
<option data-countryCode="AG" value="1268">Antigua &amp; Barbuda (+1268)</option> 
<option data-countryCode="AR" value="54">Argentina (+54)</option> 
<option data-countryCode="AM" value="374">Armenia (+374)</option> 
<option data-countryCode="AW" value="297">Aruba (+297)</option> 
<option data-countryCode="AU" value="61">Australia (+61)</option> 
<option data-countryCode="AT" value="43">Austria (+43)</option> 
<option data-countryCode="AZ" value="994">Azerbaijan (+994)</option> 
<option data-countryCode="BS" value="1242">Bahamas (+1242)</option> 
<option data-countryCode="BH" value="973">Bahrain (+973)</option> 
<option data-countryCode="BD" value="880">Bangladesh (+880)</option> 
<option data-countryCode="BB" value="1246">Barbados (+1246)</option> 
<option data-countryCode="BY" value="375">Belarus (+375)</option> 
<option data-countryCode="BE" value="32">Belgium (+32)</option> 
<option data-countryCode="BZ" value="501">Belize (+501)</option> 
<option data-countryCode="BJ" value="229">Benin (+229)</option> 
<option data-countryCode="BM" value="1441">Bermuda (+1441)</option> 
<option data-countryCode="BT" value="975">Bhutan (+975)</option> 
<option data-countryCode="BO" value="591">Bolivia (+591)</option> 
<option data-countryCode="BA" value="387">Bosnia Herzegovina (+387)</option> 
<option data-countryCode="BW" value="267">Botswana (+267)</option> 
<option data-countryCode="BR" value="55">Brazil (+55)</option> 
<option data-countryCode="BN" value="673">Brunei (+673)</option> 
<option data-countryCode="BG" value="359">Bulgaria (+359)</option> 
<option data-countryCode="BF" value="226">Burkina Faso (+226)</option> 
<option data-countryCode="BI" value="257">Burundi (+257)</option> 
<option data-countryCode="KH" value="855">Cambodia (+855)</option> 
<option data-countryCode="CM" value="237">Cameroon (+237)</option> 
<option data-countryCode="CA" value="1">Canada (+1)</option> 
<option data-countryCode="CV" value="238">Cape Verde Islands (+238)</option> 
<option data-countryCode="KY" value="1345">Cayman Islands (+1345)</option> 
<option data-countryCode="CF" value="236">Central African Republic (+236)</option> 
<option data-countryCode="CL" value="56">Chile (+56)</option> 
<option data-countryCode="CN" value="86">China (+86)</option> 
<option data-countryCode="CO" value="57">Colombia (+57)</option> 
<option data-countryCode="KM" value="269">Comoros (+269)</option> 
<option data-countryCode="CG" value="242">Congo (+242)</option> 
<option data-countryCode="CK" value="682">Cook Islands (+682)</option> 
<option data-countryCode="CR" value="506">Costa Rica (+506)</option> 
<option data-countryCode="HR" value="385">Croatia (+385)</option> 
<option data-countryCode="CU" value="53">Cuba (+53)</option> 
<option data-countryCode="CY" value="90392">Cyprus North (+90392)</option> 
<option data-countryCode="CY" value="357">Cyprus South (+357)</option> 
<option data-countryCode="CZ" value="42">Czech Republic (+42)</option> 
<option data-countryCode="DK" value="45">Denmark (+45)</option> 
<option data-countryCode="DJ" value="253">Djibouti (+253)</option> 
<option data-countryCode="DM" value="1809">Dominica (+1809)</option> 
<option data-countryCode="DO" value="1809">Dominican Republic (+1809)</option> 
<option data-countryCode="EC" value="593">Ecuador (+593)</option> 
<option data-countryCode="EG" value="20">Egypt (+20)</option> 
<option data-countryCode="SV" value="503">El Salvador (+503)</option> 
<option data-countryCode="GQ" value="240">Equatorial Guinea (+240)</option> 
<option data-countryCode="ER" value="291">Eritrea (+291)</option> 
<option data-countryCode="EE" value="372">Estonia (+372)</option> 
<option data-countryCode="ET" value="251">Ethiopia (+251)</option> 
<option data-countryCode="FK" value="500">Falkland Islands (+500)</option> 
<option data-countryCode="FO" value="298">Faroe Islands (+298)</option> 
<option data-countryCode="FJ" value="679">Fiji (+679)</option> 
<option data-countryCode="FI" value="358">Finland (+358)</option> 
<option data-countryCode="FR" value="33">France (+33)</option> 
<option data-countryCode="GF" value="594">French Guiana (+594)</option> 
<option data-countryCode="PF" value="689">French Polynesia (+689)</option> 
<option data-countryCode="GA" value="241">Gabon (+241)</option> 
<option data-countryCode="GM" value="220">Gambia (+220)</option> 
<option data-countryCode="GE" value="7880">Georgia (+7880)</option> 
<option data-countryCode="DE" value="49">Germany (+49)</option> 
<option data-countryCode="GH" value="233">Ghana (+233)</option> 
<option data-countryCode="GI" value="350">Gibraltar (+350)</option> 
<option data-countryCode="GR" value="30">Greece (+30)</option> 
<option data-countryCode="GL" value="299">Greenland (+299)</option> 
<option data-countryCode="GD" value="1473">Grenada (+1473)</option> 
<option data-countryCode="GP" value="590">Guadeloupe (+590)</option> 
<option data-countryCode="GU" value="671">Guam (+671)</option> 
<option data-countryCode="GT" value="502">Guatemala (+502)</option> 
<option data-countryCode="GN" value="224">Guinea (+224)</option> 
<option data-countryCode="GW" value="245">Guinea - Bissau (+245)</option> 
<option data-countryCode="GY" value="592">Guyana (+592)</option> 
<option data-countryCode="HT" value="509">Haiti (+509)</option> 
<option data-countryCode="HN" value="504">Honduras (+504)</option> 
<option data-countryCode="HK" value="852">Hong Kong (+852)</option> 
<option data-countryCode="HU" value="36">Hungary (+36)</option> 
<option data-countryCode="IS" value="354">Iceland (+354)</option> 
<option data-countryCode="IN" value="91">India (+91)</option> 
<option data-countryCode="ID" value="62">Indonesia (+62)</option> 
<option data-countryCode="IR" value="98">Iran (+98)</option> 
<option data-countryCode="IQ" value="964">Iraq (+964)</option> 
<option data-countryCode="IE" value="353">Ireland (+353)</option> 
<option data-countryCode="IL" value="972">Israel (+972)</option> 
<option data-countryCode="IT" value="39">Italy (+39)</option> 
<option data-countryCode="JM" value="1876">Jamaica (+1876)</option> 
<option data-countryCode="JP" value="81">Japan (+81)</option> 
<option data-countryCode="JO" value="962">Jordan (+962)</option> 
<option data-countryCode="KZ" value="7">Kazakhstan (+7)</option> 
<option data-countryCode="KE" value="254">Kenya (+254)</option> 
<option data-countryCode="KI" value="686">Kiribati (+686)</option> 
<option data-countryCode="KP" value="850">Korea North (+850)</option> 

<option data-countryCode="KW" value="965">Kuwait (+965)</option> 
<option data-countryCode="KG" value="996">Kyrgyzstan (+996)</option> 
<option data-countryCode="LA" value="856">Laos (+856)</option> 
<option data-countryCode="LV" value="371">Latvia (+371)</option> 
<option data-countryCode="LB" value="961">Lebanon (+961)</option> 
<option data-countryCode="LS" value="266">Lesotho (+266)</option> 
<option data-countryCode="LR" value="231">Liberia (+231)</option> 
<option data-countryCode="LY" value="218">Libya (+218)</option> 
<option data-countryCode="LI" value="417">Liechtenstein (+417)</option> 
<option data-countryCode="LT" value="370">Lithuania (+370)</option> 
<option data-countryCode="LU" value="352">Luxembourg (+352)</option> 
<option data-countryCode="MO" value="853">Macao (+853)</option> 
<option data-countryCode="MK" value="389">Macedonia (+389)</option> 
<option data-countryCode="MG" value="261">Madagascar (+261)</option> 
<option data-countryCode="MW" value="265">Malawi (+265)</option> 
<option data-countryCode="MY" value="60">Malaysia (+60)</option> 
<option data-countryCode="MV" value="960">Maldives (+960)</option> 
<option data-countryCode="ML" value="223">Mali (+223)</option> 
<option data-countryCode="MT" value="356">Malta (+356)</option> 
<option data-countryCode="MH" value="692">Marshall Islands (+692)</option> 
<option data-countryCode="MQ" value="596">Martinique (+596)</option> 
<option data-countryCode="MR" value="222">Mauritania (+222)</option> 
<option data-countryCode="YT" value="269">Mayotte (+269)</option> 
<option data-countryCode="MX" value="52">Mexico (+52)</option> 
<option data-countryCode="FM" value="691">Micronesia (+691)</option> 
<option data-countryCode="MD" value="373">Moldova (+373)</option> 
<option data-countryCode="MC" value="377">Monaco (+377)</option> 
<option data-countryCode="MN" value="976">Mongolia (+976)</option> 
<option data-countryCode="MS" value="1664">Montserrat (+1664)</option> 
<option data-countryCode="MA" value="212">Morocco (+212)</option> 
<option data-countryCode="MZ" value="258">Mozambique (+258)</option> 
<option data-countryCode="MN" value="95">Myanmar (+95)</option> 
<option data-countryCode="NA" value="264">Namibia (+264)</option> 
<option data-countryCode="NR" value="674">Nauru (+674)</option> 
<option data-countryCode="NP" value="977">Nepal (+977)</option> 
<option data-countryCode="NL" value="31">Netherlands (+31)</option> 
<option data-countryCode="NC" value="687">New Caledonia (+687)</option> 
<option data-countryCode="NZ" value="64">New Zealand (+64)</option> 
<option data-countryCode="NI" value="505">Nicaragua (+505)</option> 
<option data-countryCode="NE" value="227">Niger (+227)</option> 
<option data-countryCode="NG" value="234">Nigeria (+234)</option> 
<option data-countryCode="NU" value="683">Niue (+683)</option> 
<option data-countryCode="NF" value="672">Norfolk Islands (+672)</option> 
<option data-countryCode="NP" value="670">Northern Marianas (+670)</option> 
<option data-countryCode="NO" value="47">Norway (+47)</option> 
<option data-countryCode="OM" value="968">Oman (+968)</option> 
<option data-countryCode="PW" value="680">Palau (+680)</option> 
<option data-countryCode="PA" value="507">Panama (+507)</option> 
<option data-countryCode="PG" value="675">Papua New Guinea (+675)</option> 
<option data-countryCode="PY" value="595">Paraguay (+595)</option> 
<option data-countryCode="PE" value="51">Peru (+51)</option> 
<option data-countryCode="PH" value="63">Philippines (+63)</option> 
<option data-countryCode="PL" value="48">Poland (+48)</option> 
<option data-countryCode="PT" value="351">Portugal (+351)</option> 
<option data-countryCode="PR" value="1787">Puerto Rico (+1787)</option> 
<option data-countryCode="QA" value="974">Qatar (+974)</option> 
<option data-countryCode="RE" value="262">Reunion (+262)</option> 
<option data-countryCode="RO" value="40">Romania (+40)</option> 
<option data-countryCode="RU" value="7">Russia (+7)</option> 
<option data-countryCode="RW" value="250">Rwanda (+250)</option> 
<option data-countryCode="SM" value="378">San Marino (+378)</option> 
<option data-countryCode="ST" value="239">Sao Tome &amp; Principe (+239)</option> 
<option data-countryCode="SA" value="966">Saudi Arabia (+966)</option> 
<option data-countryCode="SN" value="221">Senegal (+221)</option> 
<option data-countryCode="CS" value="381">Serbia (+381)</option> 
<option data-countryCode="SC" value="248">Seychelles (+248)</option> 
<option data-countryCode="SL" value="232">Sierra Leone (+232)</option> 
<option data-countryCode="SG" value="65">Singapore (+65)</option> 
<option data-countryCode="SK" value="421">Slovak Republic (+421)</option> 
<option data-countryCode="SI" value="386">Slovenia (+386)</option> 
<option data-countryCode="SB" value="677">Solomon Islands (+677)</option> 
<option data-countryCode="SO" value="252">Somalia (+252)</option> 
<option data-countryCode="ZA" value="27">South Africa (+27)</option> 
<option data-countryCode="ES" value="34">Spain (+34)</option> 
<option data-countryCode="LK" value="94">Sri Lanka (+94)</option> 
<option data-countryCode="SH" value="290">St. Helena (+290)</option> 
<option data-countryCode="KN" value="1869">St. Kitts (+1869)</option> 
<option data-countryCode="SC" value="1758">St. Lucia (+1758)</option> 
<option data-countryCode="SD" value="249">Sudan (+249)</option> 
<option data-countryCode="SR" value="597">Suriname (+597)</option> 
<option data-countryCode="SZ" value="268">Swaziland (+268)</option> 
<option data-countryCode="SE" value="46">Sweden (+46)</option> 
<option data-countryCode="CH" value="41">Switzerland (+41)</option> 
<option data-countryCode="SI" value="963">Syria (+963)</option> 
<option data-countryCode="TW" value="886">Taiwan (+886)</option> 
<option data-countryCode="TJ" value="7">Tajikstan (+7)</option> 
<option data-countryCode="TG" value="228">Togo (+228)</option> 
<option data-countryCode="TO" value="676">Tonga (+676)</option> 
<option data-countryCode="TT" value="1868">Trinidad &amp; Tobago (+1868)</option> 
<option data-countryCode="TN" value="216">Tunisia (+216)</option> 
<option data-countryCode="TR" value="90">Turkey (+90)</option> 
<option data-countryCode="TM" value="7">Turkmenistan (+7)</option> 
<option data-countryCode="TM" value="993">Turkmenistan (+993)</option> 
<option data-countryCode="TC" value="1649">Turks &amp; Caicos Islands (+1649)</option> 
<option data-countryCode="TV" value="688">Tuvalu (+688)</option> 
<option data-countryCode="UG" value="256">Uganda (+256)</option> 
<option data-countryCode="UA" value="380">Ukraine (+380)</option> 
<option data-countryCode="GB" value="44">UK (+44)</option> 
<option data-countryCode="US" value="1">USA (+1)</option> 
<option data-countryCode="AE" value="971">United Arab Emirates (+971)</option> 
<option data-countryCode="UY" value="598">Uruguay (+598)</option> 
<option data-countryCode="US" value="1">USA (+1)</option>
<option data-countryCode="UZ" value="7">Uzbekistan (+7)</option> 
<option data-countryCode="VU" value="678">Vanuatu (+678)</option> 
<option data-countryCode="VA" value="379">Vatican City (+379)</option> 
<option data-countryCode="VE" value="58">Venezuela (+58)</option> 
<option data-countryCode="WF" value="681">Wallis &amp; Futuna (+681)</option> 
<option data-countryCode="YE" value="969">Yemen (North)(+969)</option> 
<option data-countryCode="YE" value="967">Yemen (South)(+967)</option> 
<option data-countryCode="ZM" value="260">Zambia (+260)</option> 
<option data-countryCode="ZW" value="263">Zimbabwe (+263)</option> 
';

$g5[bank_arr]=array(
'SC제일은행',
'경남은행',
'광주은행',
'국민은행',
'굿모닝신한증권',
'기업은행',
'농협중앙회',
'농협회원조합',
'대구은행',
'대신증권',
'대우증권',
'동부증권',
'동양종합금융증권',
'메리츠증권',
'미래에셋증권',
'뱅크오브아메리카(BOA)',
'부국증권',
'부산은행',
'산림조합중앙회',
'산업은행',
'삼성증권',
'상호신용금고',
'새마을금고',
'수출입은행',
'수협중앙회',
'신영증권',
'신한은행',
'신협중앙회',
'에스케이증권',
'에이치엠씨투자증권',
'엔에이치투자증권',
'엘아이지투자증권',
'외환은행',
'우리은행',
'우리투자증권',
'우체국',
'유진투자증권',
'전북은행',
'제주은행',
'키움증권',
'하나대투증권',
'하나은행',
'하이투자증권',
'한국씨티은행',
'한국투자증권',
'한화증권',
'현대증권',
'홍콩상하이은행'
);

include_once(G5_LIB_PATH.'/coin.lib.php');    // 공통 라이브러리
//print_r($g5['cn_item']);
