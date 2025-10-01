<?php
// Predefined currency exchange rates
$exchangeRates = [
    "USD_TO_INR" => 83.2,
    "INR_TO_USD" => 1 / 83.2,
    "EUR_TO_INR" => 89.5,
    "INR_TO_EUR" => 1 / 89.5,
    "GBP_TO_INR" => 103.7,
    "INR_TO_GBP" => 1 / 103.7,
    "USD_TO_EUR" => 0.92,
    "EUR_TO_USD" => 1 / 0.92,
    "USD_TO_GBP" => 0.77,
    "GBP_TO_USD" => 1 / 0.77,
    "EUR_TO_GBP" => 0.84,
    "GBP_TO_EUR" => 1 / 0.84
];

// Default values
$amount = isset($_POST['amount']) ? (float)$_POST['amount'] : 1;
$conversionKey = isset($_POST['conversionKey']) ? $_POST['conversionKey'] : "USD_TO_INR";

// Validate conversion key
if (!array_key_exists($conversionKey, $exchangeRates)) {
    $conversionKey = "USD_TO_INR";
}

// Calculate converted amount
$convertedAmount = $amount * $exchangeRates[$conversionKey];

// Parse the conversion key for better readability
list($fromCurrency, $toCurrency) = explode("_TO_", $conversionKey);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Advanced Currency Converter</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 20px;
            background-color: #f9f9f9;
        }
        h1, h2 {
            color: #333;
        }
        form {
            background-color: #fff;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            max-width: 400px;
            margin: 0 auto;
        }
        input, select, button {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            font-size: 16px;
        }
        button {
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        .result {
            text-align: center;
            margin-top: 20px;
            padding: 10px;
            background-color: #eaf7e9;
            color: #4CAF50;
            font-size: 18px;
            border: 1px solid #c8e6c9;
            border-radius: 8px;
        }
        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            text-decoration: none;
            color: #007BFF;
        }
        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h1>Advanced Currency Converter</h1>
    <form method="POST">
        <label for="amount">Enter Amount:</label>
        <input type="number" id="amount" name="amount" value="<?php echo htmlspecialchars($amount); ?>" required min="0" step="0.01">
        
        <label for="conversionKey">Convert From - To:</label>
        <select id="conversionKey" name="conversionKey">
            <?php foreach ($exchangeRates as $key => $rate): ?>
                <option value="<?php echo $key; ?>" <?php echo $conversionKey === $key ? "selected" : ""; ?>>
                    <?php echo str_replace("_TO_", " to ", $key); ?>
                </option>
            <?php endforeach; ?>
        </select>
        
        <button type="submit">Convert</button>
    </form>

    <div class="result">
        <strong>Converted Amount:</strong><br>
        <?php echo number_format($amount, 2) . " $fromCurrency = " . number_format($convertedAmount, 2) . " $toCurrency"; ?>
    </div>

    <a href="index.php" class="back-link">Go Back to Home</a>

    <!-- Contact Us Button -->
    <form action="contact.php" method="GET" style="text-align: center; margin-top: 20px;">
        <button type="submit">Contact Us</button>
    </form>
</body>
</html>
