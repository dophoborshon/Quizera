<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'verify') {
    header('Content-Type: application/json');

    $code = trim($_POST['code'] ?? '');

    if ($code === '') {
        echo json_encode(['success' => false, 'message' => 'Please enter the verification code.']);
        exit;
    }

    if (!preg_match('/^\d{6}$/', $code)) {
        echo json_encode(['success' => false, 'message' => 'Verification code must be exactly 6 digits.']);
        exit;
    }

    if (isset($_SESSION['verification_code']) && $code == $_SESSION['verification_code']) {
        unset($_SESSION['verification_code']);
        echo json_encode(['success' => true, 'redirect' => 'dashboard.php', 'message' => 'Verification successful!']);
        exit;
    }

    if (isset($_SESSION['reset_code']) && $code == $_SESSION['reset_code']) {
        unset($_SESSION['reset_code']);

        $_SESSION['allow_password_update'] = true; 
        echo json_encode(['success' => true, 'redirect' => 'new-password.php', 'message' => 'Identity verified!']);
        exit;
    }

    echo json_encode(['success' => false, 'message' => 'Invalid verification code. Please check your Mailtrap inbox.']);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Quizera - Identity Verification</title>
  <link rel="preconnect" href="https://googleapis.com">
  <link rel="preconnect" href="https://gstatic.com" crossorigin>
  <link href="https://googleapis.com/css2?family=Inter:wght@400;500;700&family=Space+Grotesk:wght@700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/styles.css" />
</head>
<body class="verify-page">

  <div class="verify-card">

    <h1 class="login-h1">Verification</h1>
    <p class="login-p">Enter the 6-digit code sent to your email address.</p>

    <input class="login-email" type="text" id="code" placeholder="000000" maxlength="6" style="text-align: center; font-size: 18px; letter-spacing: 4px;" />
    
    <button class="btn-login" onclick="verifyCode()">Verify Code</button>

    <div class="login-footer">
      Didn't receive the code? <a href="#">Resend Code</a>
    </div>
  </div>

  <script src="assets/js/quiz.js"></script>
</body>
</html>
