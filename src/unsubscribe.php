<?php
require_once 'functions.php';

$message = ""; 

if (isset($_GET['email'])) {
    $encodedEmail = $_GET['email'];
    $email = base64_decode(urldecode($encodedEmail));
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        unsubscribeEmail($email);
        echo "You have been unsubscribed from Task Planner notifications.";
    } else {
        echo "Invalid unsubscribe request.";
    }
} else {
    echo "Missing unsubscribe parameter.";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Unsubscribe</title>
	<style>
		 body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f6f8;
            padding: 50px;
            max-width: 600px;
            margin: auto;
            color: #333;
            text-align: center;
        }

        h2 {
            font-size: 28px;
            color: #e74c3c;
            margin-bottom: 20px;
        }

        p {
            font-size: 18px;
            background-color: #fceaea;
            border: 1px solid #e6bcbc;
            color: #b33a3a;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 30px;
        }

        a {
            text-decoration: none;
            background-color: #4A90E2;
            color: white;
            padding: 10px 20px;
            border-radius: 6px;
            transition: background-color 0.3s ease;
            display: inline-block;
        }

        a:hover {
            background-color: #357ABD;
        }
	</style>
</head>
<body>
    <h2>Unsubscribed</h2>
    <p><?= htmlspecialchars($message) ?></p>
    <a href="index.php">Back to Task Scheduler</a>
</body>
</html>
