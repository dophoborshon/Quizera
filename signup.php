<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'signup') {
  header('Content-Type: application/json');
  require_once __DIR__ . '/config/db.php';

  $name     = trim($_POST['name']    ?? '');
  $email    = trim($_POST['email']   ?? '');
  $country  = trim($_POST['country'] ?? '');
  $mobile   = trim($_POST['mobile']  ?? '');
  $age      = (int)($_POST['age']    ?? 0);
  $password = $_POST['password']     ?? '';

  if ($name === '' || $email === '' || $country === '' || $mobile === '' || $age <= 0 || $password === '') {
    echo json_encode(['success' => false, 'message' => 'Please fill in all required fields.']);
    exit;
  }

  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Please enter a valid email address.']);
    exit;
  }

  if ($age < 1 || $age > 120) {
    echo json_encode(['success' => false, 'message' => 'Please enter a valid age between 1 and 120.']);
    exit;
  }

  if (strlen($password) < 6) {
    echo json_encode(['success' => false, 'message' => 'Password must be at least 6 characters.']);
    exit;
  }

  $db = get_db();

  $check = $db->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
  if (!$check) { echo json_encode(['success' => false, 'message' => 'Database error. Please try again.']); exit; }
  $check->bind_param('s', $email);
  if (!$check->execute()) { echo json_encode(['success' => false, 'message' => 'Database error. Please try again.']); $check->close(); exit; }
  $check->store_result();
  if ($check->num_rows > 0) { echo json_encode(['success' => false, 'message' => 'This email is already registered.']); $check->close(); exit; }
  $check->close();

  $hash = password_hash($password, PASSWORD_DEFAULT);

  $insert = $db->prepare('INSERT INTO users (name, email, country, mobile, age, password) VALUES (?, ?, ?, ?, ?, ?)');
  if (!$insert) { echo json_encode(['success' => false, 'message' => 'Database error. Please try again.']); exit; }
  $insert->bind_param('ssssis', $name, $email, $country, $mobile, $age, $hash);
  
  if (!$insert->execute()) { 
    echo json_encode(['success' => false, 'message' => 'Registration failed. Please try again.']); 
    $insert->close(); 
    exit; 
  }

  if (session_status() === PHP_SESSION_NONE) session_start();
  $_SESSION['user_id']   = $db->insert_id;
  $_SESSION['user_name'] = $name;

  $verificationCode = rand(100000, 999999);
  $_SESSION['verification_code'] = $verificationCode;

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
    $mail->addAddress($email, $name);

    $mail->isHTML(true);
    $mail->Subject = 'Your Quizera Verification Code';
    $mail->Body    = "
      <div style='background-color:#110c1f; color:white; padding:40px; text-align:center; font-family:sans-serif;'>
        <h1 style='color:#00F2FE; font-size:28px;'>✦ Quizera Access Token</h1>
        <p style='font-size:16px;'>Welcome to the ultimate quiz destination, " . htmlspecialchars($name) . "!</p>
        <p style='font-size:14px; color:#a099b8;'>Your identity verification code is:</p>
        <div style='font-size:36px; font-weight:bold; color:#00F2FE; letter-spacing:8px; margin:30px 0; background:#18122B; padding:15px; border-radius:8px; display:inline-block;'>$verificationCode</div>
        <p style='color:#554f6c; font-size:12px;'>This code is highly sensitive and expires shortly.</p>
      </div>
    ";

    $mail->send();
    echo json_encode(['success' => true, 'message' => 'Registration successful! Code sent.']);
  } catch (Exception $e) {
    echo json_encode(['success' => true, 'message' => 'Account created, but verification email could not be sent.']);
  }

  $insert->close();
  $db->close();
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Quizera - Create Account</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&family=Space+Grotesk:wght@700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/styles.css" />
</head>
<body class="login-page">
  <div class="login-card" style="width: 380px;">
    <div class="login-icon">✦</div>
    <h1 class="login-h1">Create Account</h1>
    <p class="login-p">Join Quizera to track your trivia scores</p>
    <input class="login-email" type="text"   id="name"     placeholder="Full Name" />
    <input class="login-email" type="email"  id="email"    placeholder="Email Address" />
    <div class="signup-grid">
      <input class="login-email" type="text"   id="country"  placeholder="Country" />
      <input class="login-email" type="number" id="age"      placeholder="Age" min="1" max="120" />
    </div>
    <input class="login-email"    type="tel"      id="mobile"   placeholder="Mobile Number" />
    <input class="login-password" type="password" id="password" placeholder="Choose Password (min 6 chars)" />
    <button class="btn-login" onclick="signup()" style="margin-top: 10px;">Sign Up</button>
    <div class="login-footer">
      Already have an account? <a href="login.php">Login here</a>
    </div>
  </div>
  <script src="assets/js/quiz.js" defer></script>
</body>
</html>
