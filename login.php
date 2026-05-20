<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'login') {
    header('Content-Type: application/json');
    require_once __DIR__ . '/config/db.php';

    $email    = trim($_POST['email']    ?? '');
    $password = $_POST['password']      ?? '';

    if ($email === '' || $password === '') {
        echo json_encode(['success' => false, 'message' => 'Please enter your email and password.']);
        exit;
    }

    $conn = get_db();

    $stmt = $conn->prepare('SELECT id, name, password FROM users WHERE email = ? LIMIT 1');
    if (!$stmt) {
        echo json_encode(['success' => false, 'message' => 'Database error. Please try again.']);
        exit;
    }
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if (password_verify($password, $row['password'])) {
            if (session_status() === PHP_SESSION_NONE) session_start();
            $_SESSION['user_id']   = $row['id'];
            $_SESSION['user_name'] = $row['name'];
            $stmt->close();
            $conn->close();
            echo json_encode(['success' => true, 'message' => 'Login successful']);
        } else {
            $stmt->close();
            $conn->close();
            echo json_encode(['success' => false, 'message' => 'Wrong email or password.']);
        }
    } else {
        $stmt->close();
        $conn->close();
        echo json_encode(['success' => false, 'message' => 'Wrong email or password.']);
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Quizera - Login</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&family=Space+Grotesk:wght@700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/styles.css" />
  <script src="assets/js/quiz.js" defer></script>
</head>
<body class="login-page">
  <div class="login-card">
    <div class="login-icon"></div>
    <h1 class="login-h1">Welcome to Quizera</h1>
    <p class="login-p">Login to participate in quizzes</p>
    <input class="login-email"    type="email"    id="email"    placeholder="Email Address" />
    <input class="login-password" type="password" id="password" placeholder="Password" />
    <div class="login-row">
      <label class="remember-label">
        <input type="checkbox" /> Remember this session
      </label>
      <a href="forgot-password.php">Forgot Password?</a>
    </div>
    <button class="btn-login" onclick="login()">Login</button>
    <div class="login-footer">
      New to Quizera? <a href="signup.php">Create an account</a>
    </div>
  </div>
</body>
</html>
