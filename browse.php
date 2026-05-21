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

include("config/db.php");

$conn = get_db();

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Quizera Browsing</title>

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

            Quizera Browsing

        </div>

        <div class="nav-menu">

            <input type="text" placeholder="Search quizzes..." class="chat-input-field">

            <a href="#" class="icon-link active">HD</a>

        </div>

    </nav>

    <!-- APP WRAPPER -->

    <div class="app-workspace">

        <!-- LEFT SIDEBAR -->

        <aside class="left-icon-bar">

            <a href="dashboard.php" class="icon-link">⌂</a>

            <a href="browse.php" class="icon-link active">📚</a>

            <a href="#" class="icon-link">🏆</a>

            <a href="#" class="icon-link">💬</a>

            <a href="#" class="icon-link">⚙</a>

        </aside>

        <!-- MAIN CONTENT -->

        <main class="dashboard-layout">

            <!-- LEFT SECTION -->

            <div class="main-content-column">

                <!-- PAGE HEADING -->

                <div>

                    <h1 class="section-heading">
                        Browse Quizzes
                    </h1>

                    <p class="sub-text">
                        Explore CSE related quizzes and improve your technical skills.
                    </p>

                </div>

                <!-- FILTER BUTTONS -->

                <div class="filter-btn-row">

                    <button class="btn-action">
                        Web Development
                    </button>

                    <button class="btn-action bg-blue-btn">
                        Programming
                    </button>

                    <button class="btn-action">
                        Database
                    </button>

                    <button class="btn-action bg-blue-btn">
                        AI & ML
                    </button>

                    <button class="btn-action">
                        Networking
                    </button>

                    <button class="btn-action bg-blue-btn">
                        Cyber Security
                    </button>

                </div>

                <!-- QUIZ GRID -->

                <div class="quiz-modules-grid">

                <?php

                $query = "
                SELECT quizzes.*, categories.name AS category_name
                FROM quizzes
                JOIN categories
                ON quizzes.category_id = categories.id
                WHERE quizzes.is_published = 1
                ORDER BY quizzes.id DESC
                ";

                $result = mysqli_query($conn, $query);

                if(mysqli_num_rows($result) > 0){

                    while($quiz = mysqli_fetch_assoc($result)){

                ?>

                <div class="quiz-card border-cyan">

                    <div class="card-header-row">

                        <div class="module-icon-box bg-cyan">

                            <?php

                            $category = strtolower($quiz['category_name']);

                            if($category == 'web development'){

                                echo "🌐";

                            }

                            elseif($category == 'programming'){

                                echo "💻";

                            }

                            elseif($category == 'database'){

                                echo "🗄";

                            }

                            elseif($category == 'ai & ml'){

                                echo "🤖";

                            }

                            elseif($category == 'networking'){

                                echo "🌍";

                            }

                            elseif($category == 'cyber security'){

                                echo "🔒";

                            }

                            else{

                                echo "📘";

                            }

                            ?>

                        </div>

                        <span class="new-pill">

                            <?php echo htmlspecialchars($quiz['category_name']); ?>

                        </span>

                    </div>

                    <h4>

                        <?php echo htmlspecialchars($quiz['title']); ?>

                    </h4>

                    <p>

                        <?php echo htmlspecialchars($quiz['description']); ?>

                    </p>

                    <div class="sub-text">

                        ⏱ <?php echo $quiz['time_limit']; ?> mins

                    </div>

                    <a href="quiz-details.php?id=<?php echo $quiz['id']; ?>" class="btn-action">

                        Start Quiz →

                    </a>

                </div>

                <?php

                    }

                } else {

                ?>

                <div class="quiz-card border-yellow">

                    <h4>No Published Quiz Found</h4>

                    <p>
                        Admin has not published any quiz yet.
                    </p>

                </div>

                <?php

                }

                ?>

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
                                JS Mastery
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

                <!-- COMMUNITY CHAT -->

                <div class="chat-feed-container">

                    <h3>💬 Community Chat</h3>

                    <div class="chat-messages-box">

                        <div class="msg-row">

                            <strong>Sam</strong>

                            <span class="msg-time">
                                20 mins ago
                            </span>

                            <p>
                                That JavaScript quiz was intense 🔥
                            </p>

                        </div>

                        <div class="msg-row">

                            <strong>Maya</strong>

                            <span class="msg-time">
                                1 hour ago
                            </span>

                            <p>
                                Good luck on the networking module!
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