<?php

function addTask($task_name) {
    $task_name = trim($task_name);
    if (empty($task_name)) return;

    $tasks = getAllTasks();
    foreach ($tasks as $task) {
        if ($task['name'] === $task_name) return;
    }

    $id = uniqid();
    file_put_contents('tasks.txt', "$id|$task_name|0\n", FILE_APPEND);
}

function getAllTasks() {
    $tasks = [];

    if (!file_exists('tasks.txt')) return $tasks;

    $lines = file('tasks.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {
        $parts = explode('|', trim($line));
        
        if (count($parts) === 3) {
            list($id, $name, $completed) = $parts;
            $tasks[] = [
                'id' => $id,
                'name' => $name,
                'completed' => $completed
            ];
        }
    }

    return $tasks;
}


function markTaskAsCompleted($task_id, $is_completed) {
    $tasks = getAllTasks();
    $output = '';
    foreach ($tasks as $task) {
        $completed = ($task['id'] == $task_id) ? $is_completed : $task['completed'];
        $output .= "{$task['id']}|{$task['name']}|$completed\n";
    }
    file_put_contents('tasks.txt', $output);
}

function deleteTask($task_id) {
    $tasks = getAllTasks();
    $output = '';
    foreach ($tasks as $task) {
        if ($task['id'] !== $task_id) {
            $output .= "{$task['id']}|{$task['name']}|{$task['completed']}\n";
        }
    }
    file_put_contents('tasks.txt', $output);
}

function generateVerificationCode() {
    return str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
}

// function subscribeEmail($email) {
//     $email = trim($email);
    
//     if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
//         return "Invalid email address.";
//     }

//     // Checks if already subscribed
//     if (file_exists('subscribers.txt')) {
//         $subscribers = file('subscribers.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
//         if (in_array($email, $subscribers)) {
//             return "Email is already subscribed.";
//         }
//     }


// if (file_exists('pending_subscriptions.txt')) {
//     $pending = file('pending_subscriptions.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
//     foreach ($pending as $line) {
//         $parts = explode('|', trim($line));
//         if (count($parts) === 2) {
//             list($pendingEmail, $code) = $parts;
//             if ($pendingEmail === $email) {
//                 return "Verification already pending for this email.";
//             }
//         }
//     }
// }


//     $code = generateVerificationCode();
//     file_put_contents('pending_subscriptions.txt', "$email|$code\n", FILE_APPEND);

//     // Sending verification email
//     $verification_link = "http://localhost/task-scheduler-AdityaPawar6174/src/verify.php?email=" . urlencode($email) . "&code=$code";

//     $subject = "Verify subscription to Task Planner";
//     $message = "
//     <p>Click the link below to verify your subscription to Task Planner:</p>
//     <p><a id=\"verification-link\" href=\"$verification_link\">Verify Subscription</a></p>
//     ";
//     $headers = "MIME-Version: 1.0\r\n";
//     $headers .= "Content-type:text/html;charset=UTF-8\r\n";
//     $headers .= "From: no-reply@example.com\r\n";

//     mail($email, $subject, $message, $headers);

//     return "Verification email sent.";
// }

function subscribeEmail($email) {
    $code = rand(100000, 999999);

    // Prevent duplicate pending subscriptions
    if (file_exists('pending_subscriptions.txt')) {
        $pending = file('pending_subscriptions.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($pending as $line) {
            $parts = explode('|', trim($line));
            if (count($parts) < 2) continue;
            list($pendingEmail, $pendingCode) = $parts;
            if ($pendingEmail === $email) {
                return "Verification already pending for this email.";
            }
        }
    }

    // Save to pending
    file_put_contents('pending_subscriptions.txt', "$email|$code\n", FILE_APPEND);

    // Verification link
    $encodedEmail = urlencode($email);
    $verificationLink = "http://localhost/task-scheduler-AdityaPawar6174/src/verify.php?email=$encodedEmail&code=$code";

    // HTML message
    $subject = "Verify Your Task Scheduler Subscription";
    $message = "
    <html>
    <body>
        <h2>Confirm Your Subscription</h2>
        <p>Thank you for subscribing to Task Scheduler reminders.</p>
        <p>Please click the link below to verify your email:</p>
        <a href=\"$verificationLink\">Verify My Email</a>
        <p>If you didn’t request this, you can ignore this email.</p>
    </body>
    </html>
    ";

    // Headers
    $headers  = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=UTF-8\r\n";
    $headers .= "From: Task Scheduler <no-reply@example.com>\r\n";

    mail($email, $subject, $message, $headers);

    return true;
}



function verifySubscription($email, $code) {
    if (!file_exists('pending_subscriptions.txt')) return false;

    $lines = file('pending_subscriptions.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $new_lines = [];
    $is_verified = false;

	foreach ($lines as $line) {
		$parts = explode('|', trim($line));
		if (count($parts) === 2) {
			list($pendingEmail, $pendingCode) = $parts;
			if ($pendingEmail === $email && $pendingCode === $code) {
				// Move to verified
				file_put_contents('subscribers.txt', "$email\n", FILE_APPEND);
				$is_verified = true;
			} else {
				$new_lines[] = $line;
			}
		} else {
			
		}
	}	

    file_put_contents('pending_subscriptions.txt', implode("\n", $new_lines) . "\n");

    return $is_verified;
}



function unsubscribeEmail($email) {
    if (!file_exists('subscribers.txt')) return;

    $subscribers = file('subscribers.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $subscribers = array_filter($subscribers, fn($sub) => trim($sub) !== $email);

    file_put_contents('subscribers.txt', implode("\n", $subscribers) . "\n");
}



function sendTaskReminders() {
    $tasks = getAllTasks();
    $pending_tasks = array_filter($tasks, fn($task) => !$task['completed']);

    if (empty($pending_tasks)) return;

    if (!file_exists('subscribers.txt')) return;

    $subscribers = file('subscribers.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($subscribers as $email) {
        sendTaskEmail($email, $pending_tasks);
    }
}



function sendTaskEmail($email, $pending_tasks) {
    $subject = "Task Planner - Pending Tasks Reminder";

    // Build HTML task list
    $taskListHtml = "<ul>";
    foreach ($pending_tasks as $task) {
        $taskListHtml .= "<li>" . htmlspecialchars($task['name']) . "</li>";
    }
    $taskListHtml .= "</ul>";

    // Unsubscribe link
    $encodedEmail = urlencode(base64_encode($email));
    $unsubscribeLink = "http://localhost/task-scheduler-AdityaPawar6174/src/unsubscribe.php?email={$encodedEmail}";

    // Complete HTML message
    $message = "
    <html>
    <head>
        <title>Task Reminder</title>
    </head>
    <body>
        <h2>Pending Tasks Reminder</h2>
        <p>Here are your current pending tasks:</p>
        $taskListHtml
        <p>
            <a href=\"$unsubscribeLink\" style=\"color:red;\">Unsubscribe from notifications</a>
        </p>
    </body>
    </html>
    ";

    // Proper HTML headers
    $headers  = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=UTF-8\r\n";
    $headers .= "From: Task Planner <no-reply@example.com>\r\n";

    // Send email
    mail($email, $subject, $message, $headers);
}

