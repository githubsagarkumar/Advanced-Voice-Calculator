<?php
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['expression'])) {
    $rawExpression = (string)$_POST['expression'];

    // Normalize and map spoken words to operators
    $normalized = strtolower(trim($rawExpression));

    // Replace common spoken terms with math operators
    $replacements = [
        '/\bplus\b/' => '+',
        '/\bminus\b/' => '-',
        '/\b(add|and)\b/' => '+',
        '/\b(subtract|less)\b/' => '-',
        '/\b(times|multiplied\s+by|multiply\s+by)\b/' => '*',
        '/\b(divided\s+by|divide\s+by|over)\b/' => '/',
        '/\b(to\s+the\s+power\s+of|power\s+of|power)\b/' => '^',
        // Handle the letter x as multiply when used between numbers/spaces
        '/(?<=\d)\s*x\s*(?=\d)/' => '*'
    ];
    foreach ($replacements as $pattern => $symbol) {
        $normalized = preg_replace($pattern, $symbol, $normalized);
    }

    // Remove spaces
    $normalized = preg_replace('/\s+/', '', $normalized);

    // Support caret exponent by converting ^ to ** for PHP
    $normalized = str_replace('^', '**', $normalized);

    // Only allow safe characters now: digits, decimal point, parentheses, and operators + - * / **
    // Validate that string contains nothing else
    if (!preg_match('/^[0-9\.+\-\/\*\(\)]+$/', $normalized)) {
        header("Location: index.php?result=" . urlencode('Error'));
        exit;
    }

    // Prevent consecutive invalid operator sequences except for ** (already present as two asterisks)
    // A simple sanity check: expression should not start or end with an operator (except leading minus)
    if (preg_match('/^[\+\/*]+|[\+\-\/*]+$/', $normalized)) {
        header("Location: index.php?result=" . urlencode('Error'));
        exit;
    }

    // Evaluate safely. Catch all Throwables (ParseError, Error, Exception)
    try {
        // Suppress notices inside eval but keep evaluation
        $result = @eval("return $normalized;");

        if ($result === false && $result !== 0 && $result !== 0.0) {
            throw new Exception('Evaluation failed');
        }

        // Persist history
        @file_put_contents("history.log", $rawExpression . " => " . $result . "\n", FILE_APPEND);

        header("Location: index.php?result=" . urlencode((string)$result));
    } catch (\Throwable $e) {
        header("Location: index.php?result=Error");
    }
    exit;
}
?>
