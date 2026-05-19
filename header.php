<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$current_page = basename($_SERVER['PHP_SELF']);
$userName = isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : 'Guest';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Quizera - Interactive Hub</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Space+Grotesk:wght@600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/online-quiz/assets/css/styles.css" />
</head>
<body class="qzglb-app-body">

  <nav class="qznav-fixed-header">
    <div class="qznav-brand-logo">✦ QUIZERA</div>
    
    <div class="qznav-links-menu">
      <a href="/online-quiz/index.php" class="qznav-link-item <?php echo ($current_page == 'index.php') ? 'active' : ''; ?>">Dashboard</a>
      <a href="/online-quiz/dashboard.php" class="qznav-link-item <?php echo ($current_page == 'dashboard.php') ? 'active' : ''; ?>">Quizzes</a>
      <a href="/online-quiz/ranks.php" class="qznav-link-item <?php echo ($current_page == 'ranks.php') ? 'active' : ''; ?>">Ranks</a>
      <a href="/online-quiz/users/history.php" class="qznav-link-item <?php echo ($current_page == 'history.php') ? 'active' : ''; ?>">History</a>
      <a href="/online-quiz/profile.php" class="qznav-link-item <?php echo ($current_page == 'profile.php') ? 'active' : ''; ?>">Profile</a>
    </div>

    <div class="qznav-action-zone">
      <?php if (isset($_SESSION['user_id'])): ?>
        <span class="qznav-user-greeting">Hi, <strong><?php echo $userName; ?></strong></span>
        <a href="/online-quiz/logout.php" class="qznav-logout-btn">Logout</a>
      <?php else: ?>
        <a href="/online-quiz/login.php" class="qznav-login-btn">Login</a>
      <?php endif; ?>
    </div>
  </nav>

  <div class="qzglb-page-viewport">
