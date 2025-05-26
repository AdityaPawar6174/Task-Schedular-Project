# Task Scheduler
This assignment is a PHP-based task management system where users can add tasks to a common list and subscribe to receive hourly email reminders for pending tasks.

---

## 📌 Features to Implemented

### 1️⃣ **Task Management**

- Add new tasks to the common list
- Duplicate tasks should not be added.
- Mark tasks as complete/incomplete
- Delete tasks
- Store tasks in `tasks.txt`

### 2️⃣ **Email Subscription System**

- Users can subscribe with their email
- Email verification process:
  - System generates a unique 6-digit verification code
  - Sends verification email with activation link
  - Link contains email and verification code
  - User clicks link to verify subscription
  - System moves email from pending to verified subscribers
- Store subscribers in `subscribers.txt`
- Store pending verifications in `pending_subscriptions.txt`

### 3️⃣ **Reminder System**

- CRON job runs every hour
- Sends emails to verified subscribers
- Only includes pending tasks in reminders
- Includes unsubscribe link in emails
- Unsubscribe process:
  - Every email includes an unsubscribe link
  - Link contains encoded email address
  - One-click unsubscribe removes email from subscribers

---

## 📜 File Details & Function Stubs

Implemented the following functions in `functions.php`:

```php
function addTask($task_name) {
    // Add a new task to the list
}

function getAllTasks() {
    // Get all tasks from tasks.txt
}

function markTaskAsCompleted($task_id, $is_completed) {
    // Mark/unmark a task as complete
}

function deleteTask($task_id) {
    // Delete a task from the list
}

function generateVerificationCode() {
    // Generate a 6-digit verification code
}

function subscribeEmail($email) {
    // Add email to pending subscriptions and send verification
}

function verifySubscription($email, $code) {
    // Verify email subscription
}

function unsubscribeEmail($email) {
    // Remove email from subscribers list
}

function sendTaskReminders() {
    // Sends task reminders to all subscribers
 	// Internally calls  sendTaskEmail() for each subscriber
}

function sendTaskEmail( $email, $pending_tasks ) {
	// Sends a task reminder email to a subscriber with pending tasks.
}
```

## 📁 File Structure

- `functions.php` (Core functions)
- `index.php` (Main interface)
- `verify.php` (Email verification handler)
- `unsubscribe.php` (Unsubscribe handler)
- `cron.php` (Reminder sender)
- `setup_cron.sh` (CRON job setup)
- `tasks.txt` (Task storage)
- `subscribers.txt` (Verified subscribers)
- `pending_subscriptions.txt` (Pending verifications)

## 🔄 CRON Job Implementation

 Iedmplemented a **CRON job** that runs `cron.php` every 1 hour.  
 
---

## 📩 Email Handling

✅ The email content is in **HTML format**  
✅ **PHP's `mail()` function** is used for sending emails.  
✅ Each email includes an **unsubscribe link**.  
✅ Subscribers email is stored in `subscribers.txt`.
✅ Pending verifications are stored in `pending_subscriptions.txt`.
✅ Each email included an **unsubscribe link**.

---
