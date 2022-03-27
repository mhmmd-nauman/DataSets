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
$brand = "Crown";
header("Content-Type: text/csv");
header("Content-Disposition: attachment; filename=transformation-status-$brand.csv");
// Disable caching
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1
header("Pragma: no-cache"); // HTTP 1.0
header("Expires: 0"); // Proxies



require '../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;


$spreadsheet1 = IOFactory::load("export_catalog_product_20220317_121244.xlsx");
$sheetData1 = $spreadsheet1->getActiveSheet()->toArray(null, true, true, true);
$a=array();
$b=array();
$c=array();
$mrsp=array();
$names = array();
$qty = array();



//echo"<pre>";
//print_r($sheetData);
//echo"</pre>";
//exit();
outputCSV(array(
													   array(
														   'sku',
														   'product_brand',
														   
														   
						)));
foreach($sheetData1 as $row){
	
		$name = trim($row['G']);
		$sku = trim($row['A']);
		//$ecr_page = trim($row['P']);
		
		
				if(strpos($name,$brand)!== false){
					
							
				outputCSV(array(
													   array(
														   trim($sku), // sku
														   $brand,//store_view_code
														   
														   

						)));
				}else{
					outputCSV(array(
													   array(
														   trim($sku), // sku
														   "",//store_view_code
														   
														   

						)));
				}
			
			
}
exit;