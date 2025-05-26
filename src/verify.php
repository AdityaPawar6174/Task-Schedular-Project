<?php
require_once 'functions.php';

if (isset($_GET['email']) && isset($_GET['code'])) {
    $email = $_GET['email'];
    $code = $_GET['code'];

    $result = verifySubscription($email, $code);
    if ($result) {
        $message = "Your email has been successfully verified!";
    } else {
        $message = "Invalid verification link or code.";
    }
} else {
    $message = "Missing email or verification code.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Email Verification</title>
	<style>
		<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: #f9f9f9;
        padding: 50px;
        max-width: 600px;
        margin: auto;
        color: #333;
        text-align: center;
    }

    h2 {
        color: #4A90E2;
        font-size: 28px;
        margin-bottom: 20px;
    }

    p {
        font-size: 18px;
        background-color: #eaf8e7;
        border: 1px solid #c6e5c3;
        color: #2c662d;
        padding: 15px;
        border-radius: 6px;
        margin-bottom: 30px;
    }

    a {
        text-decoration: none;
        color: white;
        background-color: #4A90E2;
        padding: 10px 20px;
        border-radius: 6px;
        transition: background-color 0.2s ease-in-out;
        display: inline-block;
    }

    a:hover {
        background-color: #357ABD;
    }
</style>

	</style>
</head>
<body>
    <h2>Email Verification</h2>
    <p><?= htmlspecialchars($message) ?></p>
    <a href="index.php">Go Back to Task Scheduler</a>
</body>
</html>
