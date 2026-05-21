<?php
session_start();

if (empty($_SESSION['user_id'])) {

    header('Location: login.php');
    exit;

}

$user_name = htmlspecialchars(
    $_SESSION['user_name'] ?? 'Hrithik Debnath',
    ENT_QUOTES,
    'UTF-8'
);
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Quizera Dashboard</title>

    <!-- GOOGLE FONTS -->

    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Space+Grotesk:wght@600;700&display=swap" rel="stylesheet">

    <!-- CSS -->

    <link rel="stylesheet" href="assets/css/styles.css">

</head>

<body class="dashboard-page">

    <!-- TOP NAVBAR -->

    <nav class="navbar">

        <div class="nav-logo">

            Quizera Dashboard

        </div>

        <div class="nav-menu">

            <input type="text" placeholder="Search quizzes..." class="chat-input-field" style="width:220px;">

            <a href="#" class="icon-link active">HD</a>

        </div>

    </nav>

    <!-- APP WORKSPACE -->

    <div class="app-workspace">

        <!-- LEFT ICON BAR -->

        <aside class="left-icon-bar">

            <a href="#" class="icon-link active">Q</a>

            <a href="#" class="icon-link">⌂</a>

            <a href="#" class="icon-link">📚</a>

            <a href="#" class="icon-link">🏆</a>

            <a href="#" class="icon-link">💬</a>

            <a href="#" class="icon-link">⚙</a>

        </aside>

        <!-- DASHBOARD LAYOUT -->

        <main class="dashboard-layout">

            <!-- LEFT CONTENT -->

            <div class="main-content-column">

                <!-- PAGE TITLE -->

                <div>

                    <h1 class="section-heading" style="font-size:36px;">
                        Dashboard
                    </h1>

                    <p class="sub-text">
                        <?php echo date("l, F d, Y"); ?>
                    </p>

                </div>

                <!-- TOP OVERVIEW -->

                <div class="top-overview-row">

                    <!-- PROFILE CARD -->

                    <div class="user-profile-card">

                        <div class="avatar-placeholder">
                            HD
                        </div>

                        <div class="profile-details">

                            <h3>
                                Welcome back, <?php echo $user_name; ?>!
                            </h3>

                            <div class="sub-text">
                                Skill level:
                                <span style="color:#00F2FE;">
                                    Intermediate
                                </span>
                            </div>

                            <div class="xp-bar-container">

                                <div class="xp-bar-fill" style="width:75%;"></div>

                            </div>

                            <div class="sub-text" style="margin-top:8px;">
                                75 XP
                            </div>

                        </div>

                    </div>

                    <!-- QUIZZES TAKEN -->

                    <div class="performance-stats-card">

                        <h4>QUIZZES TAKEN</h4>

                        <div class="stats-counters">

                            <div class="counter-item">

                                <p>12</p>

                                <span>+3 this week</span>

                            </div>

                        </div>

                    </div>

                    <!-- TOTAL POINTS -->

                    <div class="performance-stats-card">

                        <h4>TOTAL POINTS</h4>

                        <div class="stats-counters">

                            <div class="counter-item">

                                <p>2,850</p>

                                <span>+420 pts</span>

                            </div>

                        </div>

                    </div>

                    <!-- GLOBAL RANK -->

                    <div class="performance-stats-card">

                        <h4>GLOBAL RANK</h4>

                        <div class="stats-counters">

                            <div class="counter-item">

                                <p>#1,543</p>

                                <span>Up 12 places</span>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- ACTIVE QUIZ HEADER -->

                <div style="display:flex; justify-content:space-between; align-items:center;">

                    <h2 class="section-heading">
                        Active Quiz Modules
                    </h2>

                    <a href="browse.php" style="color:#00F2FE; text-decoration:none;">
                        Browse all →
                    </a>

                </div>

                <!-- QUIZ GRID -->

                <div class="quiz-modules-grid">

                    <!-- CARD 1 -->

                    <div class="quiz-card border-cyan">

                        <div class="card-header-row">

                            <div class="module-icon-box bg-cyan">
                                💻
                            </div>

                        </div>

                        <h4>Tech Essentials</h4>

                        <p>
                            Core structures of HTML, secure database routines, and system architectures.
                        </p>

                        <div class="sub-text">
                            ⏱ 25 min &nbsp;&nbsp; 📝 20 questions
                        </div>

                        <a href="#" class="btn-action">
                            Start Now →
                        </a>

                    </div>

                    <!-- CARD 2 -->

                    <div class="quiz-card border-yellow">

                        <div class="card-header-row">

                            <div class="module-icon-box bg-yellow">
                                🌍
                            </div>

                            <span class="new-pill">
                                Popular
                            </span>

                        </div>

                        <h4>World History Buff</h4>

                        <p>
                            Historic timelines, significant global revolutions, and social impacts.
                        </p>

                        <div class="sub-text">
                            Progress 60%
                        </div>

                        <div class="xp-bar-container" style="margin-bottom:18px;">

                            <div class="xp-bar-fill" style="width:60%; background:#ff9f43;"></div>

                        </div>

                        <a href="#" class="btn-action bg-blue-btn">
                            Resume →
                        </a>

                    </div>

                    <!-- CARD 3 -->

                    <div class="quiz-card border-cyan">

                        <div class="card-header-row">

                            <div class="module-icon-box bg-cyan">
                                📘
                            </div>

                            <span class="new-pill">
                                New
                            </span>

                        </div>

                        <h4>JavaScript Mastery</h4>

                        <p>
                            Master dynamic tracking states and functional JavaScript routines.
                        </p>

                        <div class="sub-text">
                            ⏱ 35 min &nbsp;&nbsp; 🧠 Advanced
                        </div>

                        <a href="#" class="btn-action">
                            Start Now →
                        </a>

                    </div>

                </div>

                <!-- GRAPH -->

                <div class="analytics-graph-card">

                    <div class="graph-header">

                        <h2 class="section-heading">
                            Performance Tracker
                        </h2>

                        <div style="display:flex; gap:10px;">

                            <button class="btn-action" style="width:auto; padding:8px 14px;">
                                7 days
                            </button>

                            <button class="btn-action" style="width:auto; padding:8px 14px; background:#00F2FE; color:#000;">
                                1 month
                            </button>

                            <button class="btn-action" style="width:auto; padding:8px 14px;">
                                All time
                            </button>

                        </div>

                    </div>

                    <div class="mock-graph-canvas">

                        <div class="graph-line-placeholder"></div>

                        <div class="graph-dates-row">

                            <span>Jan 1</span>
                            <span>Jan 22</span>
                            <span>Feb 12</span>
                            <span>Mar 5</span>
                            <span>Mar 24</span>

                        </div>

                    </div>

                </div>

            </div>

            <!-- RIGHT SIDEBAR -->

            <div class="sidebar-column">

                <!-- LEADERBOARD -->

                <div class="leaderboard-container">

                    <h3>🏆 Leaderboard Top 5</h3>

                    <ul class="ranking-list">

                        <li>
                            <span class="rank-num">1</span>
                            <span class="player-name">Sarah J.</span>
                            <span class="score-val">10,625</span>
                        </li>

                        <li>
                            <span class="rank-num">2</span>
                            <span class="player-name">Mike R.</span>
                            <span class="score-val">2,850</span>
                        </li>

                        <li>
                            <span class="rank-num">3</span>
                            <span class="player-name">Evary M.</span>
                            <span class="score-val">1,900</span>
                        </li>

                        <li>
                            <span class="rank-num">4</span>
                            <span class="player-name">Ariana K.</span>
                            <span class="score-val">1,720</span>
                        </li>

                        <li>
                            <span class="rank-num">5</span>
                            <span class="player-name">You</span>
                            <span class="score-val">1,543</span>
                        </li>

                    </ul>

                </div>

                <!-- UPCOMING -->

                <div class="leaderboard-container">

                    <h3>📅 Upcoming</h3>

                    <ul class="ranking-list">

                        <li>

                            <span class="rank-num">21</span>

                            <span class="player-name">
                                JS Mastery: Closures
                            </span>

                        </li>

                        <li>

                            <span class="rank-num">24</span>

                            <span class="player-name">
                                History Speed Round
                            </span>

                        </li>

                    </ul>

                </div>

                <!-- CHAT -->

                <div class="chat-feed-container">

                    <h3>💬 Community Chat</h3>

                    <div class="chat-messages-box">

                        <div class="msg-row">

                            <strong>Sam</strong>

                            <span class="msg-time">
                                20 mins ago
                            </span>

                            <p>
                                Drawn! That was intense 🔥
                            </p>

                        </div>

                        <div class="msg-row">

                            <strong>Sam</strong>

                            <span class="msg-time">
                                5 hours ago
                            </span>

                            <p>
                                That last question was tough!
                            </p>

                        </div>

                    </div>

                    <div class="chat-input-row">

                        <input type="text" placeholder="Type a message..." class="chat-input-field">

                        <button class="chat-send-btn">
                            Send
                        </button>

                    </div>

                </div>

            </div>

        </main>

    </div>

</body>

</html>