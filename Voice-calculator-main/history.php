<?php
$historyFile = 'history.log';

// Check if the user has clicked the "Clear History" button
if (isset($_POST['clear_history'])) {
    file_put_contents($historyFile, ''); // Clear the contents of the history.log file
    header("Location: history.php"); // Redirect to refresh the page
    exit;
}

// Read history from the file
if (file_exists($historyFile)) {
    $history = file($historyFile);
} else {
    $history = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>History</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 20px;
            padding: 20px;
            color: #333;
        }
        .history-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #007BFF;
        }
        ul {
            list-style: none;
            padding: 0;
        }
        ul li {
            background-color: #e9ecef;
            margin: 8px 0;
            padding: 10px;
            border-radius: 4px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        p {
            text-align: center;
            font-size: 18px;
            color: #555;
        }
        .buttons {
            text-align: center;
            margin-top: 20px;
        }
        button {
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 4px;
            padding: 10px 20px;
            margin: 5px;
            font-size: 16px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="history-container">
        <h1>History</h1>
        <?php if (!empty($history)): ?>
            <ul>
                <?php foreach (array_reverse($history) as $line): ?>
                    <li><?php echo htmlspecialchars($line); ?></li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>No history yet.</p>
        <?php endif; ?>

        <div class="buttons">
            <form action="index.php" method="GET" style="display: inline;">
                <button type="submit">Home</button>
            </form>
            <form action="history.php" method="POST" style="display: inline;">
                <button type="submit" name="clear_history">Clear History</button>
            </form>
        </div>
    </div>
</body>
</html>
