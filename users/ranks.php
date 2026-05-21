<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

require_once __DIR__ . '/../config/db.php';
$conn = get_db();
$current_user_id = $_SESSION['user_id'];

$leaderboard_query = "SELECT u.id, u.name, 
                             IFNULL(SUM(h.score), 0) as total_score,
                             COUNT(h.id) as quizzes_taken,
                             IFNULL(ROUND((SUM(h.score) / SUM(h.total_questions)) * 100), 0) as avg_accuracy
                      FROM users u
                      LEFT JOIN quiz_history h ON u.id = h.user_id
                      GROUP BY u.id
                      ORDER BY total_score DESC, avg_accuracy DESC";

$result = $conn->query($leaderboard_query);

$podium = [];
$remaining_players = [];
$rank_counter = 1;
$user_rank_data = null;

while ($row = $result->fetch_assoc()) {
    $player = [
        'rank' => $rank_counter,
        'id' => $row['id'],
        'name' => htmlspecialchars($row['name']),
        'total_score' => $row['total_score'],
        'quizzes_taken' => $row['quizzes_taken'],
        'avg_accuracy' => $row['avg_accuracy']
    ];

    if ($row['id'] == $current_user_id) {
        $user_rank_data = $player;
    }

    if ($rank_counter <= 3) {
        $podium[$rank_counter] = $player;
    } else {
        $remaining_players[] = $player;
    }
    $rank_counter++;
}

include_once __DIR__ . '/../includes/header.php';
?>

<div class="qzrnk-main-stream">
  <header class="qzrnk-stream-header">
    <div class="qzrnk-header-titlebox">
      <h1>Global Leaderboard</h1>
      <p class="qzrnk-header-desc">Track your standing against top contenders across the global arena metrics.</p>
    </div>
  </header>

  <!-- Section A: Top 3 Visual Podium Display -->
  <div class="qzrnk-podium-section">
    
    <!-- 2nd Place Position -->
    <div class="qzrnk-podium-column qzrnk-pos-2">
      <div class="qzrnk-podium-avatar qzrnk-border-silver">🥈</div>
      <h3 class="qzrnk-podium-name"><?php echo isset($podium[2]) ? $podium[2]['name'] : 'Empty'; ?></h3>
      <p class="qzrnk-podium-score"><?php echo isset($podium[2]) ? $podium[2]['total_score'] . ' pts' : '--'; ?></p>
      <div class="qzrnk-podium-pedestal qzrnk-ped-2">2</div>
    </div>

    <!-- 1st Place Position -->
    <div class="qzrnk-podium-column qzrnk-pos-1">
      <div class="qzrnk-podium-avatar qzrnk-border-gold">👑</div>
      <h3 class="qzrnk-podium-name"><?php echo isset($podium[1]) ? $podium[1]['name'] : 'Empty'; ?></h3>
      <p class="qzrnk-podium-score"><?php echo isset($podium[1]) ? $podium[1]['total_score'] . ' pts' : '--'; ?></p>
      <div class="qzrnk-podium-pedestal qzrnk-ped-1">1</div>
    </div>

    <!-- 3rd Place Position -->
    <div class="qzrnk-podium-column qzrnk-pos-3">
      <div class="qzrnk-podium-avatar qzrnk-border-bronze">🥉</div>
      <h3 class="qzrnk-podium-name"><?php echo isset($podium[3]) ? $podium[3]['name'] : 'Empty'; ?></h3>
      <p class="qzrnk-podium-score"><?php echo isset($podium[3]) ? $podium[3]['total_score'] . ' pts' : '--'; ?></p>
      <div class="qzrnk-podium-pedestal qzrnk-ped-3">3</div>
    </div>

  </div>

  <!-- Section B: Horizontal Rows Ledger Stack -->
  <div class="qzrnk-ledger-stack">
    <h2 class="qzrnk-block-heading">Arena Standings</h2>
    
    <!-- Loop through top podium users first to ensure complete horizontal list availability -->
    <?php 
    $all_players = array_merge(array_values($podium), $remaining_players);
    foreach ($all_players as $player): 
        $is_current_user = ($player['id'] == $current_user_id) ? 'qzrnk-current-user-row' : '';
        
        $rank_badge_class = 'qzrnk-bg-grey';
        if ($player['rank'] == 1) $rank_badge_class = 'qzrnk-bg-gold';
        if ($player['rank'] == 2) $rank_badge_class = 'qzrnk-bg-silver';
        if ($player['rank'] == 3) $rank_badge_class = 'qzrnk-bg-bronze';
    ?>
      <div class="qzrnk-ledger-row <?php echo $is_current_user; ?>">
        <div class="qzrnk-ledger-meta">
          <span class="qzrnk-num <?php echo $rank_badge_class; ?>"><?php echo $player['rank']; ?></span>
          <div class="qzrnk-badge-initials"><?php echo strtoupper(substr($player['name'], 0, 2)); ?></div>
          <h3 class="qzrnk-player-name"><?php echo $player['name']; ?> <?php echo ($player['id'] == $current_user_id) ? '<span class="qzrnk-you-tag">(You)</span>' : ''; ?></h3>
        </div>
        
        <div class="qzrnk-ledger-data">
          <div class="qzrnk-data-block">
            <span class="qzrnk-data-val"><?php echo $player['quizzes_taken']; ?></span>
            <p>Quizzes</p>
          </div>
          <div class="qzrnk-data-block">
            <span class="qzrnk-data-val qzrnk-cyan-txt"><?php echo $player['avg_accuracy']; ?>%</span>
            <p>Accuracy</p>
          </div>
          <div class="qzrnk-data-block">
            <span class="qzrnk-data-val qzrnk-yellow-txt"><?php echo $player['total_score']; ?></span>
            <p>Total Points</p>
          </div>
        </div>
      </div>
    <?php endforeach; ?>

  </div>
</div>

<?php 
$conn->close();
include_once __DIR__ . '/../includes/footer.php'; 
?>
