<?php



// Collision Detection
//
// Given the 2D coordinates and dimensions of multiple objects on a 2D plane. We want to determine if any of these objects intersect or occupy the same space.
// Bonus points if the solution allows for 3D coordinates and/or supports different shapes.
//
// There’s no need to provide a visual solution to this, I’m more interested in seeing the business logic.
// The shapes and their coordinates can be randomly generated or user-provided. No specific requirement on the structure he should use for the solution or for defining a shape – can be as simple as an array [[0,0],[1,0],[0,1],[1,1]] to define a square of width ‘1’ –
// I want to be able to see a solution that can be run and verified that it calculates the result correctly.



function getinvalidShapes(array $shapes) {
  $invalid_shapes = array();
  $num_shapes = count($shapes);
  for ($i=0; $i<$num_shapes; $i++) {
    if ($shapes[$i][0][1] != $shapes[$i][1][1] || $shapes[$i][0][0] != $shapes[$i][2][0] || $shapes[$i][2][1] != $shapes[$i][3][1] || $shapes[$i][1][0] != $shapes[$i][3][0])  {
      $shape_num = $i+1;
      $invalid_shapes[] = "Shape $shape_num has invalid coordinates. The shape must be a square or rectangle.";
    }
  }
  return $invalid_shapes;
}

function getCombinations(array $shapes) {
  $combinations = array();
  $num_shapes = count($shapes);
  for ($i=0; $i<$num_shapes; $i++) {
    for ($j=$i+1; $j<$num_shapes; $j++) {
      $shape_1 = $i+1;
      $shape_2 = $j+1;
      $combinations["shapes $shape_1 and $shape_2"] = array($shapes[$i], $shapes[$j]);
    }
  }
  return $combinations;
}

function detectCollision(array $shapes) {
  $invalid_shapes = getinvalidShapes($shapes);
  if (empty($invalid_shapes)) {
    $combinations = getCombinations($shapes);
	//echo "<pre>";
	//print_r($combinations);
    $collisions = array();

    foreach($combinations as $shape_pair => $shape_value) {
		
		$r1x1 = $shape_value[0][0][0];
		$r1x2 = $shape_value[0][1][0];
		$r1y1 = $shape_value[0][0][1];
		$r1y2 = $shape_value[0][1][1];
		
		$r2x1 = $shape_value[1][0][0];
		$r2x2 = $shape_value[1][1][0];
		$r2y1 = $shape_value[1][0][1];
		$r2y2 = $shape_value[1][1][1];
		
		$r1x = $shape_value[0][2][0];
		$r1y = $shape_value[0][2][1];
		//echo "<br>";
		$r2x = $shape_value[1][2][0];
		$r2y = $shape_value[1][2][1];
		
		$r1w = sqrt( ($r1x2 - $r1x1)*($r1x2 - $r1x1) + ( $r1y2 - $r1y1)*( $r1y2 - $r1y1) );
		$r1h = sqrt( ($r1x1 - $shape_value[0][2][0])*($r1x1 - $shape_value[0][2][0]) + ( $r1y1 - $shape_value[0][2][1])*( $r1y1 - $shape_value[0][2][1]) );
		
		$r2w = sqrt( ($r2x2 - $r2x1)*($r2x2 - $r2x1) + ( $r2y2 - $r2y1)*( $r2y2 - $r2y1) );
		$r2h = sqrt( ($r2x1 - $shape_value[1][2][0])*($r2x1 - $shape_value[1][2][0]) + ( $r2y1 - $shape_value[1][2][1])*( $r2y1 - $shape_value[1][2][1]) );
		
		
		
		$dx =($r1x+$r1w/2)-($r2x+$r2w/2);
		$dy =($r1y+$r1h/2)-($r2y+$r2h/2);
		$width = ($r1w+$r2w)/2;
		$height = ($r1h+$r2h)/2;
		$crossWidth=$width*$dy;
		$crossHeight=$height*$dx;
		$collision='none';
		
      //Is the right side of the first shape greater than the left side of the second shape AND is the right side of the second shape greater than the left side of the first shape;
      //AND Is the top side of the first shape greater than the bottom side of the second shape AND is the top side of the second shape greater than the bottom side of the first shape;
      if ($r1x < $r2x + $r2w &&
	   $r1x + $r1w > $r2x &&
	   $r1y < $r2y + $r2h &&
	   $r1y + $r1h > $r2y) {
		$collisions[] = "Collision detected between ".$shape_pair. "!";
	}
	 // echo " (((".$shape_value[0][2][1]." > ".$shape_value[1][0][1].") && (".$shape_value[1][2][1]." > ".$shape_value[0][0][1].")) && (($shape_value[0][1][0] > $shape_value[1][0][0]) && ($shape_value[1][1][0] > $shape_value[0][0][0])))";
	  if ((($shape_value[0][2][1] > $shape_value[1][0][1]) && ($shape_value[1][2][1] > $shape_value[0][0][1])) && (($shape_value[0][1][0] > $shape_value[1][0][0]) && ($shape_value[1][1][0] > $shape_value[0][0][0]))) {
        //$collisions[] = "Collision detected between ".$shape_pair. "!";
      }
    }
    if (!empty($collisions)) {
      return array('result' => true, 'message' => $collisions);
    } else {
      return array('result' => false, 'message' => 'No collisions.');
    }
  } else {
    return array('result' => false, 'message' => $invalid_shapes);
  }
}

// bottom_left = X, Y , 4,8
// bottom_right = X, Y 
// top_left = X, Y
// top_right = X, Y

$shapes = array([[4,8],[9,8],[4,11],[9,11]], [[4,2],[9,2],[4,9],[9,9]]);

$detect_collisions = detectCollision($shapes);

print_r($detect_collisions);




?>
