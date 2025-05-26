<?php
require_once 'functions.php';

// This Handles Task Operations
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['task-name'])) {
        addTask($_POST['task-name']);
    } elseif (isset($_POST['toggle-task'])) {
        markTaskAsCompleted($_POST['toggle-task'], $_POST['status'] === '1' ? 1 : 0);
    } elseif (isset($_POST['delete-task'])) {
        deleteTask($_POST['delete-task']);
    } elseif (isset($_POST['email'])) {
        subscribeEmail($_POST['email']);
        $email_message = "Verification email sent to {$_POST['email']}";
    }
}

$tasks = getAllTasks();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Task Scheduler</title>
    <style>
        body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: #f9f9f9;
    padding: 30px;
    max-width: 700px;
    margin: auto;
    color: #333;
}

h1 {
    text-align: center;
    color: #4A90E2;
}

h2 {
    margin-top: 40px;
    color: #4A90E2;
    border-bottom: 2px solid #eee;
    padding-bottom: 5px;
}

form {
    margin-bottom: 20px;
}

input[type="text"],
input[type="email"] {
    padding: 10px;
    font-size: 16px;
    width: 70%;
    border: 1px solid #ccc;
    border-radius: 6px;
    box-sizing: border-box;
}

button {
    padding: 10px 15px;
    background-color: #4A90E2;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
    margin-left: 10px;
}

button:hover {
    background-color: #357ABD;
}

.tasks-list {
    list-style: none;
    padding: 0;
    margin-top: 20px;
}

.task-item {
    background-color: #fff;
    border: 1px solid #ddd;
    padding: 10px;
    margin-bottom: 10px;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
}

.task-item.completed {
    text-decoration: line-through;
    color: gray;
    background-color: #f0f0f0;
}

.task-status {
    margin-right: 10px;
    transform: scale(1.3);
}

.delete-task {
    background-color: #e74c3c;
    margin-left: 10px;
}

.delete-task:hover {
    background-color: #c0392b;
}

p {
    font-size: 14px;
}

    </style>
</head>
<body>
    <h1>Task Scheduler</h1>

    <!-- Add Task -->
    <form method="POST">
        <input type="text" name="task-name" id="task-name" placeholder="Enter new task" required>
        <button type="submit" id="add-task">Add Task</button>
    </form>

    <!-- Task List -->
    <ul class="tasks-list">
        <?php foreach ($tasks as $task): ?>
            <li class="task-item <?= $task['completed'] ? 'completed' : '' ?>">
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="toggle-task" value="<?= $task['id'] ?>">
                    <input type="hidden" name="status" value="<?= $task['completed'] ? 0 : 1 ?>">
                    <input type="checkbox" class="task-status" onchange="this.form.submit()" <?= $task['completed'] ? 'checked' : '' ?>>
                </form>
                <?= htmlspecialchars($task['name']) ?>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="delete-task" value="<?= $task['id'] ?>">
                    <button class="delete-task" type="submit">Delete</button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>

    <!-- Subscribe Email -->
    <h2>Subscribe to Email Reminders</h2>
    <form method="POST">
        <input type="email" name="email" required />
        <button id="submit-email" type="submit">Submit</button>
    </form>

    <?php if (!empty($email_message)) echo "<p style='color: green;'>$email_message</p>"; ?>
</body>
</html>
