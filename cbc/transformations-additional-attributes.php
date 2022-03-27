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
prints(3);
exit;
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

$spreadsheet = IOFactory::load("Feed - 09-28-2021.xlsx");
$sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

$spreadsheet1 = IOFactory::load("actual-transformations.xlsx");
$sheetData1 = $spreadsheet1->getActiveSheet()->toArray(null, true, true, true);
$a=array();
$b=array();
foreach($sheetData1 as $row){
	$a[]=trim($row['A']);
	if(!empty($row['AU'])){
		$b[trim($row['A'])]=trim($row['AU']).",";
	}else{
		$b[trim($row['A'])]=trim($row['AU']);
	}
}
//echo"<pre>";
//print_r($sheetData);
//echo"</pre>";
//exit();
outputCSV(array(
													   array(
														   'sku',
														   'store_view_code',
														   'attribute_set_code',
														   'product_type',
														   'product_websites',
														   'name',
														   'additional_attributes'
						)));
foreach($sheetData as $row){
	if(!empty($row['B'])){
		//$old_emp_code = trim($row['A']);
		//$status = trim($row['G']);
		//$ecr_page = trim($row['P']);
		
		if(empty($row['E'])){
				  
				   
				   
			}else{
				if(in_array(trim($row['B']),$a)){
						
				outputCSV(array(
													   array(
														   trim($row['B']), // sku
														   '',//store_view_code
														   'Default',//attribute_set_code
														   'simple',//product_type
														   'base',//product_websites
														   trim($row['C']), // name
														   $b[trim($row['B'])].'qty_in_box='.trim($row['E']) // additional_attributes
														   //$temp_data_b_code['budget_unique_key'],
														   //@(int)$scale_stage['stage'],
														  // @(int)$scale_stage['scale'],
														   //'found'
														   

						)));
				}
			}
				   

		
	}
}
exit;