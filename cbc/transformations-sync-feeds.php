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
header("Content-Disposition: attachment; filename=sync-file".time().".csv");
// Disable caching
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1
header("Pragma: no-cache"); // HTTP 1.0
header("Expires: 0"); // Proxies



require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

$spreadsheet = IOFactory::load("Partner Product Feed - 04-22-2022.xlsx");
$sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

$spreadsheet1 = IOFactory::load("export_catalog_product_20220422_190929.xlsx");
$sheetData1 = $spreadsheet1->getActiveSheet()->toArray(null, true, true, true);
$a=array();
$b=array();
$c=array();
$mrsp=array();
$names = array();
$qty = array();
$instock = array();
$isonline = array();
foreach($sheetData as $row){
	$a[]=trim($row['A']);
	$names[$row['A']] = trim($row['B']);
	$row['G'] = str_replace(",","", trim($row['G']));
	$row['F'] = str_replace(",","", trim($row['F']));
	$c[$row['A']]= str_replace("$","", trim($row['G']));// min adv price
	$mrsp[$row['A']]= str_replace("$","", trim($row['F']));
	$qty[$row['A']]= str_replace("","", trim($row['J']));
	if($row['J'] > 0){
		$instock[$row['A']] = 0;
		$isonline[$row['A']] = 1;
	} else{
		$instock[$row['A']] = 1;
		$isonline[$row['A']] = 2;
	}
}

//echo"<pre>";
//print_r($sheetData);
//echo"</pre>";
//exit();

foreach($sheetData1 as $row){
	
		$sku = trim($row['A']);
		//$status = trim($row['G']);
		//$ecr_page = trim($row['P']);
		
		
				if(in_array($sku,$a)){
				$url=str_replace(" ","-", trim($names[$sku]));
				$url=str_replace("'","", $url);	
				$url=str_replace("&","", $url);
				$url=str_replace("/","", $url)."-".$sku;			
				outputCSV(array(
													   array(
														   trim($sku), // sku
														   
														   $row['B'],
														   $row['C'],
														   $row['D'],
														   $row['E'],
														   $row['F'],
														   $names[$sku],// name
														   $row['H'],
														   $row['I'],
														   $row['J'],
														   $isonline[$sku],//$row['K'], // disable products product_online
														   $row['L'],
														   $row['M'],
														   
														   $c[$sku],//price
														   $row['O'],
														   $row['P'],
														   $row['Q'],
														   $row['R'],// url_key
														   $row['S'],// name
														   $row['T'], // meta_keywords
														   $row['U'], // name,
														   $row['V'],
														   $row['W'],
														   $row['X'],
														   $row['Y'],
														   $row['Z'],
														   $row['AA'], // 
														   $row['AB'],
														   $row['AC'],
														   $row['AD'],
														   $row['AE'],
														   $row['AF'],
														   $row['AG'],
														   
														   $row['AH'],
														   $row['AI'],
														   $mrsp[$sku], // msrp_price
														   $row['AK'],
														   $row['AL'],
														   $row['AM'],
														   $row['AN'],
														   $row['AO'],
														   $row['AP'],
														   $row['AQ'],
														   $row['AR'],
														   $row['AS'],
														   $row['AT'],
														   $row['AU'],
														   $qty[$sku], // qty
														   $instock[$sku], // mark it out of stock
														   $row['AX'],
														   $row['AY'],
														   $row['AZ'],
														   $row['BA'],
														   $row['BB'],
														   $row['BC'],
														   $row['BD'], // max_cart_qty
														   $row['BE'],
														   $row['BF'],
														   $row['BG'], // use_config_notify_stock_qty
														   $row['BH'],
														   $row['BI'],
														   $row['BJ'],
														   $row['BK'], // use_config_qty_increments
														   $row['BL'],
														   $row['BM'],
														   $row['BN'], // enable_qty_increments
														   $row['BO'],
														   $row['BP'], // website_id
														   $row['BQ'],
														   $row['BR'],
														   $row['BS'],
														   $row['BT'],
														   $row['BU'],
														   $row['BV'], // upsell_position
														   $row['BW'],
														   $row['BX'], // additional_image_labels
														   $row['BY'],
														   $row['BZ'],
														   $row['CA'],
														   $row['CB'],
														   $row['CC'],
														   $row['CD'],
														   $row['CE'],
														   $row['CF'],
														   $row['CG'],
														   $row['CH'],
														   $row['CI'],
														   $row['CJ'],
														   $row['CK'],
														   

						)));
				}else{
					/*
					outputCSV(array(
													   array(
														   trim($sku), // sku
														   0,//store_view_code
														   0,
														   0
														   

						)));
					*/
				}
			
			
}
exit;