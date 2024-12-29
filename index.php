<?php
// Include API configuration file
include 'api_config.php';

// Minimum and maximum amount
$min_amount = 100; // Minimum allowed amount
$max_amount = 1000; // Maximum allowed amount

// Function to send an order to the SMM panel API
function sendOrder($api_url, $api_key, $service_id, $link, $quantity) {
    $data = [
        'key' => $api_key,
        'action' => 'add',
        'service' => $service_id,
        'link' => $link,
        'quantity' => $quantity
    ];

    // Initialize cURL
    $ch = curl_init($api_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    
    // Execute cURL and get the response
    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}

// Handling form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $link = $_POST['link']; // URL for the order
    $quantity = (int)$_POST['quantity']; // Amount for the order

    // Validate amount within min and max range
    if ($quantity < $min_amount) {
        echo 'Error: The minimum amount allowed is ' . $min_amount . '.';
    } elseif ($quantity > $max_amount) {
        echo 'Error: The maximum amount allowed is ' . $max_amount . '.';
    } else {
        // Send order to the SMM panel API
        $result = sendOrder($api_url, $api_key, $service_id, $link, $quantity);

        // Check response and display the result
        if ($result && isset($result['order'])) {
            echo 'Order successfully placed. Order ID: ' . $result['order'];
        } else {
            echo 'Error placing the order: ' . json_encode($result);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TIKTOK</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #282c34, #4b6cb7);
            color: white;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }

        h1 {
            color: #f0f0f0;
        }

        form {
            background: rgba(0, 0, 0, 0.7);
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
        }

        input[type="url"], input[type="number"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: none;
            border-radius: 4px;
            background: rgba(255, 255, 255, 0.2);
            color: white;
        }

        input[type="url"]::placeholder, input[type="number"]::placeholder {
            color: #ddd;
        }

        button {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 4px;
            background: linear-gradient(90deg, #ff512f, #dd2476);
            color: white;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        button:hover {
            background: linear-gradient(90deg, #ff512f, #e12f71);
        }

        .developer {
            margin-top: 20px;
            text-align: center;
        }

        .developer a {
            color: #ff8c00;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <h1>TIKTOK VIEWS</h1>
    <form method="POST">
        <label for="link">Link:</label>
        <input type="url" id="link" name="link" placeholder="Enter your link" required><br>
        <label for="quantity">Amount (between <?php echo $min_amount; ?> and <?php echo $max_amount; ?>):</label>
        <input type="number" id="quantity" name="quantity" placeholder="Enter amount" required><br>
        <button type="submit">Submit Order</button>
    </form>

    <div class="developer">
        <p>Developed By: <a href="https://siyam-404.github.io/Profile/" target="_blank">Team Cicada3301</a></p>
    </div>
</body>
</html>