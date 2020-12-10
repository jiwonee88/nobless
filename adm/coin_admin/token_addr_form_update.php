<?php
$sub_menu = "800400";
include_once('./_common.php');

$qstr.="&mb_stx=$mb_stx";

if ($w == 'u')
    check_demo();

auth_check($auth[$sub_menu], 'w');

//check_admin_token();

function xlscols($num){
	$alpha="ABCDEFGHIJKLMNOPQRSTUVWXYZ";
	
	if($num <= 26) $colsn=$alpha[$num-1];
	else{
		$n1=floor($num/26)-1;	
		$n2=$num%26;
		if($n2==0){
			$n1--;
			$n2=26;
		}
		$colsn=$alpha[$n1].$alpha[$n2-1];
	}
	return $colsn;		
}

//엑셀 파일 등록의 경우
if($_FILES['uploadfile']['tmp_name']) {
		
	if($token_name_excel=='') alert('코인/토큰 구분을 선택하세요.');

	//주소록 초기화
	if($trunc_addr=='1'){
		sql_query("truncate  table {$g5['cn_token_table']} ",1);	
	}
	
	$file = $_FILES['uploadfile']['tmp_name'];

	require_once G5_LIB_PATH. '/PHPExcel-1.8/Classes/PHPExcel/IOFactory.php';
	
	$objReader = PHPExcel_IOFactory::createReaderForFile($file);
	
	// 읽기전용으로 설정
	$objReader->setReadDataOnly(true);
	
	// 엑셀파일을 읽는다
	
	$objExcel = $objReader->load($file);
	
	// 첫번째 시트를 선택		
	$objExcel->setActiveSheetIndex(0);
	
	$objWorksheet = $objExcel->getActiveSheet();
	
	$rowIterator = $objWorksheet->getRowIterator();
	
	foreach ($rowIterator as $row) { // 모든 행에 대해서		
		   $cellIterator = $row->getCellIterator();		
		   $cellIterator->setIterateOnlyExistingCells(false); 		
	}
	
	$maxRow = $objWorksheet->getHighestRow();
	$maxCol = $objWorksheet->getHighestColumn();
	
	$dup_count = 0;
	$total_count = 0;
	$fail_count = 0;
	$succ_count = 0;
	
	for ($i = 1 ; $i <= $maxRow ; $i++) {
		
		$total_count++;	
		$cols = 1;
		
		for ($j = 1 ; $j <= 20 ; $j++) {
			$token_addr=trim($objWorksheet->getCell(xlscols($j).$i)->getValue());
			
			if($token_addr=='') continue;
			
			$sql="insert into {$g5['cn_token_table']} set token_name='$token_name_excel' , token_addr='$token_addr'";
			//echo $sql."<br>";
			
			$result=sql_query($sql);
			
			if($result) $succ_count++;
			
			if(xlscols($j) == $maxCol ) break;
			
		}
		
		
	}
	
	echo "<script>
	alert(\"신규 {$succ_count}건이 등록되었습니다\");
	document.location.href='./token_addr_list.php?$qstr';
	</script>";
	
	exit;
}else{

	if($token_name=='') alert('코인/토큰 구분을 선택하세요.');

	
	if(!$_POST['token_addr']) { alert('토큰 주소 또는 엑셀 파일을 선택하세요.'); }
	 
	$sql_common = " 
					token_name='$token_name' ,
					token_addr='{$_POST['token_addr']}'
					";
					
	if ($w == '') {
		$sql = " insert into {$g5['cn_token_table']}
					set 
					$sql_common ";
						
		$result=sql_query($sql);		
	
	} else if ($w == 'u') {
		
		
		$sql = " update {$g5['cn_token_table']} set 
				  {$sql_common}
				  where token_no = '{$token_no}' ";
		$result=sql_query($sql);		
	}
		
}
		

if(function_exists('get_admin_captcha_by'))
    get_admin_captcha_by('remove');

goto_url("./token_addr_list.php?$qstr");
?>