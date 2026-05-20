<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

include_once 'includes/header.php';

$user_name = htmlspecialchars(
    $_SESSION['user_name'] ?? 'Learner',
    ENT_QUOTES,
    'UTF-8'
);

$words = explode(" ", $user_name);
$avatar_initial = strtoupper(mb_substr($words[0] ?? 'L', 0, 1) . mb_substr($words[1] ?? '', 0, 1));
?>

<div class="qz-dashboard-wrapper">
    <div class="qz-dashboard-content">
        
        <div class="qz-main-column">
            
            <div class="qz-welcome-header">
                <h1 class="qz-page-title">Dashboard</h1>
                <p class="qz-current-date"><?php echo date("l, F d, Y"); ?></p>
            </div>

            <div class="qz-overview-row">
                <div class="qz-profile-card">
                    <div class="qz-avatar-placeholder"><?php echo $avatar_initial; ?></div>
                    <div class="qz-profile-details">
                        <h3>Welcome back, <?php echo $user_name; ?>!</h3>
                        <p class="qz-skill-text">Skill level: <span class="qz-highlight-cyan">Intermediate</span></p>
                    </div>
                </div>

                <div class="qz-stat-card">
                    <h4>Quizzes Taken</h4>
                    <p class="qz-stat-counter">12</p>
                </div>

                <div class="qz-stat-card">
                    <h4>Total Points</h4>
                    <p class="qz-stat-counter">2,850</p>
                </div>
            </div>

            <div class="qz-section-header">
                <h2>Active Quiz Modules</h2>
                <a href="quizzes.php" class="qz-browse-link">Browse all →</a>
            </div>

            <div class="qz-quiz-grid">
                <div class="qz-quiz-card qz-cyan-top">
                    <div class="qz-card-body">
                        <h4>Tech Essentials</h4>
                        <p>Core structures of HTML, secure database routines, and system architectures.</p>
                    </div>
                    <a href="#" class="qz-btn-link qz-cyan-text">Start Now →</a>
                </div>

                <div class="qz-quiz-card qz-orange-top">
                    <div class="qz-card-body">
                        <h4>JavaScript Mastery</h4>
                        <p>Master dynamic tracking states and functional JavaScript routines.</p>
                    </div>
                    <a href="#" class="qz-btn-link qz-orange-text">Resume →</a>
                </div>
            </div>

            <div class="qz-tracker-card">
                <h2>Performance Tracker</h2>
                <div class="qz-graph-canvas">
                    <span>[ Analytics Graph Visualization Area ]</span>
                </div>
            </div>
        </div>

        <div class="qz-sidebar-column">
            
            <div class="qz-sidebar-widget">
                <h3>Leaderboard Top 5</h3>
                <ul class="qz-leaderboard-list">
                    <li class="qz-leader-item"><span class="qz-cyan-text">1. Sarah J.</span> <strong>10,625</strong></li>
                    <li class="qz-leader-item"><span>2. Mike R.</span> <strong>2,850</strong></li>
                    <li class="qz-leader-item"><span class="qz-orange-text">5. You</span> <strong class="qz-orange-text">1,543</strong></li>
                </ul>
            </div>

            <div class="qz-sidebar-widget qz-chat-container">
                <h3>Community Chat</h3>
                <div class="qz-chat-box">
                    <div class="qz-chat-msg">
                        <strong class="qz-cyan-text">Sam:</strong> That last question was tough!
                    </div>
                    <div class="qz-chat-msg">
                        <strong>Alex:</strong> Agree, Javascript closure part was super tricky.
                    </div>
                </div>
                <div class="qz-chat-input-row">
                    <input type="text" placeholder="Type a message..." class="qz-chat-input">
                    <button class="qz-chat-send-btn">Send</button>
                </div>
            </div>

        </div>

    </div>
</div>

<?php

include_once 'includes/footer.php';
?>