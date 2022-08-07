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
header("Content-Disposition: attachment; filename=sync-file-".date("Y-m-d").".csv");
// Disable caching
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1
header("Pragma: no-cache"); // HTTP 1.0
header("Expires: 0"); // Proxies


require 'file.php';
/*
require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

$spreadsheet = IOFactory::load("Partner Product Feed - 06-30-2022.xlsx");
$sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

$spreadsheet1 = IOFactory::load("export_catalog_product_20220703_033204.xlsx");
$sheetData1 = $spreadsheet1->getActiveSheet()->toArray(null, true, true, true);
*/

$a=array();
$b=array();
$c=array();
$mrsp=array();
$names = array();
$qty = array();
$instock = array();
$isonline = array();

$size = array();
$color = array();
$qty_in_box = array();
$images = array();
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
		//$isonline[$row['A']] = 1;
	} else{
		$instock[$row['A']] = 1;
		//$isonline[$row['A']] = 2;
	}
	
	if($row['K'] == "DISCON" || $row['K'] == "DISABLED"	){
		$isonline[$row['A']] = 2;
	}else{
		$isonline[$row['A']] = 1;
	}

	$size[$row['A']]= str_replace("","", trim($row['C']));
	$color[$row['A']]= str_replace("","", trim($row['E']));
	$qty_in_box[$row['A']]= str_replace("","", trim($row['D']));
	
}

//echo"<pre>";
//print_r($sheetData);
//echo"</pre>";
//exit();
foreach($sheetData1 as $row){
	if(strpos($row['V'],".png")){
		$images[$row['G']][] = array($row['A'],$row['V']) ;
	}
}
foreach($sheetData1 as $row){
	
		$sku = trim($row['A']);
		//$status = trim($row['G']);
		//$ecr_page = trim($row['P']);
		
		
				if(in_array($sku,$a)){

					if(empty($row['V'])){
						$image_base_path = "";
						$image_small_path = "";
						$image_thumb_path = "";
						$image_additional_path = "";
						if(array_key_exists($names[$sku],$images)){
							$image_base_path = $images[$names[$sku]][count($images[$names[$sku]])-1][1];
							$image_small_path = $image_base_path;
							$image_thumb_path = $image_base_path;
							$image_additional_path = $image_base_path;			
						}	
					}else{
						$image_base_path = $row['V'];
						$image_small_path = $row['X'];
						$image_thumb_path = $row['Z'];
						$image_additional_path = $row['BW'];
					}
					$url=str_replace(" ","-", trim($names[$sku]));
					$url=str_replace("'","", $url);	
					$url=str_replace("&","", $url);
					$url=str_replace("/","", $url)."-".$sku;			
					$attibute_string = "";
					$aditional_attributes_array=explode(",",$row['AU']);
					foreach((array)$aditional_attributes_array as $atr_row){
						$row_data=explode("=",$atr_row);
						if(!empty($row_data[0])&&!empty($row_data[1])){
							$attributes_array[$row_data[0]] = $row_data[1];
						}
					}
					if(!empty($size[$row['A']])){
						$flag=0;
						foreach((array)$aditional_attributes_array as $atr_row){
							$row_data=explode("=",$atr_row);
							if($row_data == "products_size"){
								$flag=1;
								$attributes_array[$row_data[0]] = $size[$row['A']];
							}
						}
						if($flag == 0){
							$attributes_array['products_size'] = $size[$row['A']];
						}
					}else{
						if(array_key_exists("products_size",$attributes_array)){
							unset($attributes_array['products_size']);
						}
					}
					
					if(!empty($qty_in_box[$row['A']])){
						$flag=0;
						foreach((array)$aditional_attributes_array as $atr_row){
							$row_data=explode("=",$atr_row);
							if($row_data == "qty_in_box"){
								$flag=1;
								$attributes_array[$row_data[0]] = $qty_in_box[$row['A']];
							}
						}
						if($flag == 0){
							$attributes_array['qty_in_box'] = $qty_in_box[$row['A']];
						}
					}else{
						if(array_key_exists("qty_in_box",$attributes_array)){
							unset($attributes_array['qty_in_box']);
						}
						
					}
					
					if(!empty($color[$row['A']])){
						$flag=0;
						foreach((array)$aditional_attributes_array as $atr_row){
							$row_data=explode("=",$atr_row);
							if($row_data == "product_color"){
								$flag=1;
								$attributes_array[$row_data[0]] = $color[$row['A']];
							}
						}
						if($flag == 0){
							$attributes_array['product_color'] = $color[$row['A']];
						}
					}else{
						if(array_key_exists("product_color",$attributes_array)){
							unset($attributes_array['product_color']);
						}
					}
					
					foreach((array)$attributes_array as $atr=>$value){
						$attibute_string.="$atr=$value,";
					}
					if(!empty($attibute_string)){
						$attibute_string = substr($attibute_string, 0, -1);
					}
					$row['AU'] = $attibute_string;
					($image_base_path == "no_selection")?$image_base_path="":"";
					($image_small_path == "no_selection")?$image_small_path="":"";
					($image_thumb_path == "no_selection")?$image_thumb_path="":"";
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
														   ($instock[$sku]==1)?'2':$isonline[$sku],//$row['K'], // $instock[$sku] disable products product_online
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
														   $image_base_path,//$row['V'],
														   $row['W'],
														   $image_small_path,//$row['X'],
														   $row['Y'],
														   $image_thumb_path,//$row['Z'],
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
														   $image_additional_path,//$row['BW'],
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