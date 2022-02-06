<?php
$string = "abc-d-e&f#j";
//$string = "j<*zz";
//$string = "k*j<%bb*zzt";
$string_array = str_split($string);
echo "<pre>";
print_r($string_array);
$reverse_array = array_reverse($string_array);
foreach($string_array as $key=>$l){
	if(!ctype_alpha($l)){
		$positions[$key] = $l;
		array_splice($reverse_array, array_search($l, $reverse_array ), 1);	
	}
}
print_r($reverse_array);
//print_r(array_slice($reverse_array,3,1));
print_r($positions);

$startpoint = 0;
$endpoint= 0;
$final_array = array();
$pre_position = 0;
foreach($positions as $pos=>$l){
	if(empty($pre_position)){
		$endpoint = $pos;
		$pre_position = $pos;
	}else{
		$endpoint = $pos - $pre_position - 1 ;
		echo " $endpoint = $pos - $pre_position <br>";
	}
	echo " $startpoint => $endpoint <br>";
	
	$slice = array_slice($reverse_array,$startpoint,$endpoint);
	print_r($slice);
	
	$a = array();
	$a[] = $l;
	//if(empty($j)){
		$final_array = array_merge($final_array,$slice,$a);
	//}else{
		//$final_array = array_merge($final_array,$a,$slice);
	//}
	print_r($final_array);
	if(count($slice)){
		$startpoint = $startpoint+count($slice);
	}else{
		$endpoint--;
	}
	$pre_position = $pos;
	
}
//$startpoint = $startpoint - $i;
echo " $startpoint => $endpoint <br>";
$slice = array_slice($reverse_array,$startpoint);
print_r($slice);
$final_array=array_merge($final_array,$slice);
print_r($final_array);