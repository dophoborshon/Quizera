<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['user_id'])) {
    header('Location: /online-quiz/login.php');
    exit;
}

require_once __DIR__ . '/../config/db.php';
$conn = get_db();
$user_id = $_SESSION['user_id'];

$history_query = "SELECT h.id, h.score, h.total_questions, h.completed_at, q.title, q.category
                  FROM quiz_history h
                  JOIN quizzes q ON h.quiz_id = q.id
                  WHERE h.user_id = ?
                  ORDER BY h.completed_at DESC";

$stmt = $conn->prepare($history_query);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$history_result = $stmt->get_result();

$page_title = 'Activity History';
include_once __DIR__ . '/../includes/header.php';
?>

<div class="qz-dashboard-wrapper">
  <div class="qz-dashboard-content">
    <div class="qz-main-column">
      <div class="qzhst-container">
        <header class="qzhst-header">
          <h1>Activity History</h1>
          <p>Review your completed quizzes and track your progress over time.</p>
        </header>

        <div class="qzhst-list">
          <?php if ($history_result->num_rows > 0): ?>
            <?php while ($row = $history_result->fetch_assoc()):
              $category_badge = 'Tech';
              if ($row['category'] === 'history') { $category_badge = 'History'; }
              if ($row['category'] === 'science') { $category_badge = 'Science'; }
            ?>
              <div class="qzhst-row-card">
                <div class="qzhst-card-left">
                  <span class="qzhst-category-tag"><?php echo $category_badge; ?></span>
                  <h3 class="qzhst-quiz-title"><?php echo htmlspecialchars($row['title']); ?></h3>
                </div>
                <div class="qzhst-card-right">
                  <div class="qzhst-meta-info">
                    <span class="qzhst-date-text"><?php echo date('M j, Y • g:i A', strtotime($row['completed_at'])); ?></span>
                    <div class="qzhst-score-badge">Score: <strong><?php echo $row['score']; ?> / <?php echo $row['total_questions']; ?></strong></div>
                  </div>
                  <a href="review.php?history_id=<?php echo $row['id']; ?>" class="qzhst-view-btn">View Details</a>
                </div>
              </div>
            <?php endwhile; ?>
          <?php else: ?>
            <div class="qzhst-empty">
              <p>No completed quizzes found. Go to the <a href="/online-quiz/dashboard.php">Quizzes portal</a> to take your first test!</p>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>

<?php
$stmt->close();
$conn->close();
include_once __DIR__ . '/../includes/footer.php';
?>
