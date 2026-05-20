<?php

session_start();

if (empty($_SESSION['user_id'])) {
    header('Location: /online-quiz/login.php');
    exit;
}

require_once __DIR__ . '/../config/db.php';
$conn = get_db();

if (!isset($_GET['id'])) {
    header('Location: /online-quiz/users/browse.php');
    exit;
}

$quiz_id = intval($_GET['id']);
$query = "SELECT quizzes.*, categories.name AS category_name
          FROM quizzes
          JOIN categories
          ON quizzes.category_id = categories.id
          WHERE quizzes.id = $quiz_id";

$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
    die('Quiz not found');
}

$quiz = mysqli_fetch_assoc($result);
$page_title = 'Quiz Details';
include_once __DIR__ . '/../includes/header.php';
?>

<div class="qz-dashboard-wrapper">
  <div class="qz-dashboard-content">
    <main class="dashboard-layout">

      <div class="main-content-column">
        <div class="details-top-card">
          <span class="new-pill"><?php echo htmlspecialchars($quiz['category_name']); ?></span>
          <h1 class="section-heading" style="margin-top:20px;">
            <?php echo htmlspecialchars($quiz['title']); ?>
          </h1>
          <p class="sub-text"><?php echo htmlspecialchars($quiz['description']); ?></p>

          <div class="details-stats-row">
            <div class="details-stat-box">
              <h3>20</h3>
              <span>Total Questions</span>
            </div>
            <div class="details-stat-box">
              <h3><?php echo $quiz['time_limit']; ?></h3>
              <span>Minutes</span>
            </div>
            <div class="details-stat-box">
              <h3>MCQ</h3>
              <span>Quiz Type</span>
            </div>
          </div>
        </div>

        <div class="details-rules-card">
          <h2>Quiz Instructions</h2>
          <ul class="rules-list">
            <li>One question will appear at a time</li>
            <li>Timer will start immediately</li>
            <li>Quiz will auto submit after timeout</li>
            <li>Refreshing page may reset progress</li>
            <li>Read every question carefully</li>
          </ul>
          <a href="take-quiz.php?id=<?php echo $quiz['id']; ?>&q=0" class="btn-action" style="margin-top:25px; display:inline-block;">Start Quiz →</a>
        </div>
      </div>

      <div class="sidebar-column">
        <div class="leaderboard-container">
          <h3>Leaderboard Top 5</h3>
          <ul class="ranking-list">
            <li><span class="rank-num">1</span><span class="player-name">Sarah J.</span><span class="score-val">10,625</span></li>
            <li><span class="rank-num">2</span><span class="player-name">Mike R.</span><span class="score-val">2,850</span></li>
            <li><span class="rank-num">3</span><span class="player-name">Ariana K.</span><span class="score-val">1,720</span></li>
            <li><span class="rank-num">4</span><span class="player-name">Ranvir</span><span class="score-val">1,543</span></li>
            <li><span class="rank-num">5</span><span class="player-name">You</span><span class="score-val">1,120</span></li>
          </ul>
        </div>

        <div class="chat-feed-container">
          <h3>Community Chat</h3>
          <div class="chat-messages-box">
            <div class="msg-row">
              <strong>Sam</strong>
              <span class="msg-time">20 mins ago</span>
              <p>Ready for the next challenge</p>
            </div>
          </div>
          <div class="chat-input-row">
            <input type="text" placeholder="Type a message..." class="chat-input-field">
            <button class="chat-send-btn">Send</button>
          </div>
        </div>
      </div>

    </main>
  </div>
</div>

<?php include_once __DIR__ . '/../includes/footer.php'; ?>