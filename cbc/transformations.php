<?php
function outputCSV($data) {
    $output = fopen("php://output", "w");
    foreach ($data as $row) {
        fputcsv($output, $row); // here you can change delimiter/enclosure
    }
    fclose($output);
}
function prints($i){
	print " * $i  ";
	if($i>1){
		
		prints($i/2);
		prints($i/2);
		prints($i/2);
		prints($i/2);
	}
}
//prints(3);
//exit;
//print_r(get_stage_scale(2544,$db));
//exit;
header("Content-Type: text/csv");
header("Content-Disposition: attachment; filename=transformation-status.csv");
// Disable caching
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1
header("Pragma: no-cache"); // HTTP 1.0
header("Expires: 0"); // Proxies



require '../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

$spreadsheet = IOFactory::load("Partner Product Feed - 03-09-2022.xlsx");
$sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

$spreadsheet1 = IOFactory::load("export_catalog_product_20220313_070357.xlsx");
$sheetData1 = $spreadsheet1->getActiveSheet()->toArray(null, true, true, true);
$a=array();
$b=array();
$c=array();
$d=array();
foreach($sheetData1 as $row){
	$a[]=trim($row['A']);
	
}
foreach($sheetData as $row){
	
	$b[$row['A']]= str_replace("$","", trim($row['G']));
	$c[$row['A']]= str_replace("$","", trim($row['F']));
	$d[$row['A']]= str_replace("$","", trim($row['J']));
}
//echo"<pre>";
//print_r($sheetData);
//echo"</pre>";
//exit();
outputCSV(array(
													   array(
														   'sku',
														   'price',
														   'msrp_price',
														   'qty'
														   
						)));
foreach($a as $sku){
	
		//$old_emp_code = trim($row['A']);
		//$status = trim($row['G']);
		//$ecr_page = trim($row['P']);
		
		
				if(array_key_exists($sku,$b)){
						
				outputCSV(array(
													   array(
														   trim($sku), // sku
														   $b[$sku],//store_view_code
														   $c[$sku],
														   $d[$sku],
														   

						)));
				}else{
					outputCSV(array(
													   array(
														   trim($sku), // sku
														   0,//store_view_code
														   0,
														   0
														   

						)));
				}
			
			
}
exit;