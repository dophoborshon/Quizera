<?php
session_start();

if (empty($_SESSION['user_id'])) {
    header('Location: /online-quiz/login.php');
    exit;
}

require_once __DIR__ . '/../config/db.php';
$conn = get_db();

if (!isset($_GET['id'])) {
    die('Quiz ID Missing');
}

$quiz_id = (int) $_GET['id'];
$current_question = isset($_GET['q']) ? (int) $_GET['q'] : 0;

$quiz_query = mysqli_query($conn, "SELECT * FROM quizzes WHERE id = '$quiz_id'");
$quiz = mysqli_fetch_assoc($quiz_query);

$questions_query = mysqli_query($conn, "SELECT * FROM questions WHERE quiz_id = '$quiz_id'");
$questions = [];
while ($row = mysqli_fetch_assoc($questions_query)) {
    $questions[] = $row;
}

$total_questions = count($questions);
if ($total_questions == 0) {
    die('No Questions Found');
}

if ($current_question >= $total_questions) {
    header("Location: result.php?id=$quiz_id");
    exit;
}

$current_data = $questions[$current_question];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['answers'][$current_question] = $_POST['answer'] ?? '';
    $next_question = $current_question + 1;
    if ($next_question < $total_questions) {
        header("Location: take-quiz.php?id=$quiz_id&q=$next_question");
    } else {
        header("Location: result.php?id=$quiz_id");
    }
    exit;
}

$page_title = 'Active Quiz';
include_once __DIR__ . '/../includes/header.php';
?>


<div class="qz-dashboard-wrapper">
  <div class="qz-dashboard-content">
    <div class="qz-main-column">
      <div class="tq-container">

        <div class="tq-top">

        <div class="tq-title">
            Quizera Quiz
        </div>

        <div class="tq-timer" id="timer">
            00:00
        </div>

    </div>

    <div class="tq-progress-bar">

        <div
            class="tq-progress-fill"

            style="width:
            <?php echo (($current_question + 1)
            / $total_questions) * 100; ?>%;">

        </div>

    </div>

    <div class="tq-card">

        <div class="tq-question-number">

            Question
            <?php echo $current_question + 1; ?>

            of

            <?php echo $total_questions; ?>

        </div>

        <div class="tq-question-text">

            <?php
            echo htmlspecialchars(
                $current_data['question_text']
            );
            ?>

        </div>

        <form method="POST">

            <label class="tq-option">

                <input
                    type="radio"
                    name="answer"
                    value="A"
                    required>

                <span>

                    A.

                    <?php
                    echo htmlspecialchars(
                        $current_data['option_a']
                    );
                    ?>

                </span>

            </label>

            <label class="tq-option">

                <input
                    type="radio"
                    name="answer"
                    value="B">

                <span>

                    B.

                    <?php
                    echo htmlspecialchars(
                        $current_data['option_b']
                    );
                    ?>

                </span>

            </label>

            <label class="tq-option">

                <input
                    type="radio"
                    name="answer"
                    value="C">

                <span>

                    C.

                    <?php
                    echo htmlspecialchars(
                        $current_data['option_c']
                    );
                    ?>

                </span>

            </label>

            <label class="tq-option">

                <input
                    type="radio"
                    name="answer"
                    value="D">

                <span>

                    D.

                    <?php
                    echo htmlspecialchars(
                        $current_data['option_d']
                    );
                    ?>

                </span>

            </label>

            <button
                type="submit"
                class="tq-next-btn">

                <?php

                if (
                    ($current_question + 1)
                    == $total_questions
                ) {

                    echo "Submit Quiz";

                } else {

                    echo "Next Question →";

                }

                ?>

            </button>

        </form>

    </div>

</div>

<script>

let timeLeft =
<?php echo $quiz['time_limit'] * 60; ?>;

const timer =
document.getElementById("timer");

function updateTimer() {

    let minutes =
    Math.floor(timeLeft / 60);

    let seconds =
    timeLeft % 60;

    seconds =
    seconds < 10
    ? '0' + seconds
    : seconds;

    timer.innerHTML =
    minutes + ":" + seconds;

    if (timeLeft <= 0) {

        window.location.href =
        "result.php?id=<?php echo $quiz_id; ?>";

    }

    timeLeft--;

}

setInterval(updateTimer, 1000);

updateTimer();

</script>

      </div>
    </div>
  </div>
</div>

<?php include_once __DIR__ . '/../includes/footer.php'; ?>