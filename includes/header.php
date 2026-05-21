<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$current_page = basename($_SERVER['PHP_SELF']);
$userName = isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : 'Guest';

$words = explode(" ", $userName);
$avatar_initial = strtoupper(mb_substr($words[0] ?? 'G', 0, 1) . mb_substr($words[1] ?? '', 0, 1));
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?php echo htmlspecialchars($page_title ?? 'Quizera - Interactive Hub'); ?></title>
  
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Space+Grotesk:wght@600;700&display=swap" rel="stylesheet">
  
  <link rel="stylesheet" href="/online-quiz/assets/css/styles.css" />

  <style>
    
    .qz-search-box {
      background: #110c1f;
      border: 1px solid #251d3e;
      padding: 10px 16px;
      border-radius: 8px;
      color: #ffffff;
      font-size: 13px;
      outline: none;
      width: 180px;
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .qz-search-box:focus {
      border-color: #00F2FE;
      width: 240px;
      box-shadow: 0 0 10px rgba(0, 242, 254, 0.1);
    }

    
    .qz-profile-dropdown {
      position: relative;
    }

    
    .qz-avatar-trigger {
      width: 42px;
      height: 42px;
      background: #00F2FE;
      color: #110c1f;
      border-radius: 50%;
      display: flex;
      justify-content: center;
      align-items: center;
      font-weight: 700;
      font-size: 14px;
      cursor: pointer;
      user-select: none;
      box-shadow: 0 0 12px rgba(0, 242, 254, 0.15);
      transition: all 0.2s ease;
    }
    .qz-avatar-trigger:hover {
      transform: scale(1.05);
      box-shadow: 0 0 18px rgba(0, 242, 254, 0.35);
    }

    
    .qz-dropdown-menu {
      display: none;
      position: absolute;
      right: 0;
      top: 55px;
      background: #18122B;
      border: 1px solid #251d3e;
      border-radius: 12px;
      min-width: 180px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.55);
      z-index: 9999;
      overflow: hidden;
      padding: 8px 0;
      animation: qzSlideIn 0.2s ease;
    }
    .qz-dropdown-menu.show {
      display: block;
    }
    .qz-menu-title {
      padding: 8px 16px 4px 16px;
      font-size: 11px;
      color: #51496e;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }
    .qz-dropdown-menu a {
      color: #8c85a3;
      padding: 11px 16px;
      text-decoration: none;
      display: block;
      font-size: 14px;
      font-weight: 500;
      transition: all 0.2s ease;
    }
    .qz-dropdown-menu a:hover {
      background: #251d3e;
      color: #00F2FE;
    }
    .qz-menu-divider {
      height: 1px;
      background: #251d3e;
      margin: 6px 0;
    }
    .qz-dropdown-menu a.qz-logout-item:hover {
      color: #ff4d4d;
      background: rgba(255, 77, 77, 0.05);
    }

    @keyframes qzSlideIn {
      from { opacity: 0; transform: translateY(-10px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .qznav-fixed-header {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      z-index: 1000;
      width: 100%;
      backdrop-filter: blur(12px);
    }

    .global-page-container {
      padding-top: 110px;
    }
  </style>
</head>
<body class="qzglb-app-body" style="background-color: #110c1f; font-family: 'Inter', sans-serif; color: #ffffff; margin: 0; padding: 0;">

  <nav class="qznav-fixed-header" style="display: flex; justify-content: space-between; align-items: center; padding: 18px 40px; background: #18122B; border-bottom: 1px solid #251d3e; height: 80px; box-sizing: border-box;">
    
    <div class="qznav-brand-logo" style="font-family: 'Space Grotesk', sans-serif; font-size: 22px; font-weight: 700; color: #00F2FE; letter-spacing: 0.5px;"> QUIZERA</div>
    
    <div class="qznav-links-menu" style="display: flex; gap: 28px;">
      <a href="/online-quiz/dashboard.php" 
         style="text-decoration: none; font-size: 14px; font-weight: 500; color: <?php echo ($current_page == 'dashboard.php') ? '#00F2FE' : '#8c85a3'; ?>;" 
         class="<?php echo ($current_page == 'dashboard.php') ? 'active' : ''; ?>">Dashboard</a>
      
      <a href="/online-quiz/users/browse.php" 
         style="text-decoration: none; font-size: 14px; font-weight: 500; color: <?php echo ($current_page == 'browse.php') ? '#00F2FE' : '#8c85a3'; ?>;" 
         class="<?php echo ($current_page == 'browse.php') ? 'active' : ''; ?>">Quizzes</a>
      
      <a href="/online-quiz/users/ranks.php" 
         style="text-decoration: none; font-size: 14px; font-weight: 500; color: <?php echo ($current_page == 'ranks.php') ? '#00F2FE' : '#8c85a3'; ?>;" 
         class="<?php echo ($current_page == 'ranks.php') ? 'active' : ''; ?>">Ranks</a>
      
      <a href="/online-quiz/users/history.php" 
         style="text-decoration: none; font-size: 14px; font-weight: 500; color: <?php echo ($current_page == 'history.php') ? '#00F2FE' : '#8c85a3'; ?>;" 
         class="<?php echo ($current_page == 'history.php') ? 'active' : ''; ?>">History</a>
    </div>

    <div class="qznav-user" style="display: flex; align-items: center; gap: 20px;">
      
      <form action="/online-quiz/users/browse.php" method="GET" style="margin: 0;">
        <input type="text" name="search" placeholder="Search quizzes..." class="qz-search-box">
      </form>
      
      <div class="qz-profile-dropdown" id="qzProfileDropdown">
        <div class="qz-avatar-trigger" title="<?php echo $userName; ?>">
          <?php echo $avatar_initial; ?>
        </div>
        
        <div class="qz-dropdown-menu" id="qzDropdownMenu">
          <div class="qz-menu-title">Account</div>
          <a href="/online-quiz/profile.php">View Profile</a>
          <div class="qz-menu-divider"></div>
          <a href="/online-quiz/logout.php" class="qz-logout-item">Logout</a>
        </div>
      </div>

    </div>
  </nav>

  <div class="global-page-container" style="max-width: 1240px; margin: 0 auto; padding: 0 20px;">

  <script>
    document.addEventListener('DOMContentLoaded', () => {
        const profileDropdown = document.getElementById('qzProfileDropdown');
        const dropdownMenu = document.getElementById('qzDropdownMenu');

        if (profileDropdown && dropdownMenu) {

            profileDropdown.addEventListener('click', (e) => {
                e.stopPropagation();
                dropdownMenu.classList.toggle('show');
            });

            document.addEventListener('click', (e) => {
                if (!profileDropdown.contains(e.target)) {
                    dropdownMenu.classList.remove('show');
                }
            });
        }
    });
  </script>