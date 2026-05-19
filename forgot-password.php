<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'reset_password') {
    header('Content-Type: application/json');
    require_once __DIR__ . '/config/db.php';

    $email = trim($_POST['email'] ?? '');

    if ($email === '') {
        echo json_encode(['success' => false, 'message' => 'Please enter your email address.']);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Please enter a valid email address.']);
        exit;
    }

    $conn = get_db();
    $stmt = $conn->prepare('SELECT id, name FROM users WHERE email = ? LIMIT 1');
    if (!$stmt) {
        echo json_encode(['success' => false, 'message' => 'Database error. Please try again.']);
        exit;
    }
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $resetCode = rand(100000, 999999);
        $_SESSION['reset_code'] = $resetCode;
        $_SESSION['reset_email'] = $email;

        require __DIR__ . '/vendor/phpmailer/src/Exception.php';
        require __DIR__ . '/vendor/phpmailer/src/PHPMailer.php';
        require __DIR__ . '/vendor/phpmailer/src/SMTP.php';

        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com'; 
            $mail->SMTPAuth   = true;
            $mail->Username   = 'bdopho221337@bscse.uiu.ac.bd';
            $mail->Password   = 'odmn rllg rodj kvww';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            $mail->setFrom('bdopho221337@bscse.uiu.ac.bd', 'Quizera Platform');
            $mail->addAddress($email, $user['name']);

            $mail->isHTML(true);
            $mail->Subject = 'Reset Your Quizera Password';
            $mail->Body    = "
              <div style='background-color:#110c1f; color:white; padding:40px; text-align:center; font-family:sans-serif;'>
                <h1 style='color:#00F2FE; font-size:28px;'>✦ Password Reset Request</h1>
                <p style='font-size:16px;'>Hello, " . htmlspecialchars($user['name']) . ".</p>
                <p style='font-size:14px; color:#a099b8;'>Use the verification code below to authorize your password update:</p>
                <div style='font-size:36px; font-weight:bold; color:#00F2FE; letter-spacing:8px; margin:30px 0; background:#18122B; padding:15px; border-radius:8px; display:inline-block;'>$resetCode</div>
                <p style='color:#554f6c; font-size:12px;'>If you did not request this change, you can safely ignore this message.</p>
              </div>
            ";

            $mail->send();
        } catch (Exception $e) {
            // Live error debugger popup helper
            echo json_encode(['success' => false, 'message' => 'Mailer processing failed: ' . $mail->ErrorInfo]);
            $stmt->close();
            $conn->close();
            exit;
        }
    }

    echo json_encode(['success' => true, 'message' => 'If that email address exists in our records, a security code has been sent.']);
    $stmt->close();
    $conn->close();
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Quizera - Password Reset</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&family=Space+Grotesk:wght@700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/styles.css" />
  <script src="assets/js/quiz.js" defer></script>
</head>
<body class="passreset-page">
  <div class="passreset-card">
    <h1 class="login-h1">Password Reset</h1>
    <p class="login-p">Enter your email to reset your password</p>
    <input class="login-email" type="email" id="email" placeholder="Email Address" />
    <button class="btn-login" onclick="resetPassword()">Reset Password</button>
    <div class="login-footer">
      Remembered your password? <a href="login.php">Login here</a>
    </div>
  </div>
</body>
</html>
