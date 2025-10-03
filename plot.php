<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $function = $_POST['function'];
    $rangeStart = (int)$_POST['rangeStart'];
    $rangeEnd = (int)$_POST['rangeEnd'];

    $dataPoints = [];
    
    for ($x = $rangeStart; $x <= $rangeEnd; $x += 0.1) {
        // Replace 'x' in the function with the current value of $x
        $expression = str_replace("x", "($x)", $function);
        
        // Evaluate the expression and store the result
        try {
            $y = @eval("return $expression;");
            if ($y !== false && !is_nan($y) && !is_infinite($y)) {
                $dataPoints[] = ["x" => $x, "y" => $y];
            }
        } catch (Exception $e) {
            $y = null;  // Ignore if there's an error in calculation
        }
    }

    header('Content-Type: application/json');
    echo json_encode($dataPoints);
}
?>
