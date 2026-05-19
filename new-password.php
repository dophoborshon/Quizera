<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['allow_password_update']) || $_SESSION['allow_password_update'] !== true || !isset($_SESSION['reset_email'])) {
    header('Location: forgot-password.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_password') {
    header('Content-Type: application/json');
    require_once __DIR__ . '/config/db.php';

    $newPassword = $_POST['password'] ?? '';

    if ($newPassword === '') {
        echo json_encode(['success' => false, 'message' => 'Please enter a new password.']);
        exit;
    }

    if (strlen($newPassword) < 6) {
        echo json_encode(['success' => false, 'message' => 'Password must be at least 6 characters long.']);
        exit;
    }

    $email = $_SESSION['reset_email'];
    $db = get_db();

    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    $stmt = $db->prepare('UPDATE users SET password = ? WHERE email = ?');
    $stmt->bind_param('ss', $hashedPassword, $email);

    if ($stmt->execute()) {
        unset($_SESSION['reset_code']);
        unset($_SESSION['reset_email']);
        unset($_SESSION['allow_password_update']);

        echo json_encode(['success' => true, 'message' => 'Your password has been successfully updated!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database update error. Please try again.']);
    }

    $stmt->close();
    $db->close();
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Quizera - Create New Password</title>
  <link rel="preconnect" href="https://googleapis.com">
  <link rel="preconnect" href="https://gstatic.com" crossorigin>
  <link href="https://googleapis.com/css2?family=Inter:wght@400;500;700&family=Space+Grotesk:wght@700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/styles.css" />
</head>
<body class="passreset-page">

  <div class="passreset-card">

    <h1 class="login-h1">New Password</h1>
    <p class="login-p">Create a secure password with a minimum of 6 characters.</p>

    <input class="login-password" type="password" id="new_password" placeholder="New Password (min 6 chars)" />
    <input class="login-password" type="password" id="confirm_password" placeholder="Confirm Password" />
    
    <button class="btn-login" onclick="updatePassword()">Update Password</button>

    <div class="login-footer">
      Back to safety? <a href="login.php">Return to Login</a>
    </div>
  </div>

  <script src="assets/js/quiz.js"></script>
</body>
</html>
