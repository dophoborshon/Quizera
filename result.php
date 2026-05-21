<?php
session_start();

include("config/db.php");

$conn = get_db();

if (!isset($_GET['id'])) {
    die("Quiz ID Missing");
}

$quiz_id = (int) $_GET['id'];

$quiz_query = mysqli_query($conn,
"SELECT * FROM quizzes WHERE id='$quiz_id'");

$quiz = mysqli_fetch_assoc($quiz_query);

$questions_query = mysqli_query($conn,
"SELECT * FROM questions WHERE quiz_id='$quiz_id'");

$total_questions = mysqli_num_rows($questions_query);

$correct = 0;
$wrong = 0;

$answers = $_SESSION['answers'] ?? [];

$index = 0;

while($question = mysqli_fetch_assoc($questions_query)){

    $correct_answer = $question['correct_option'];

    $user_answer = $answers[$index] ?? '';

    if($user_answer == $correct_answer){

        $correct++;

    } else {

        $wrong++;

    }

    $index++;
}

$percentage = 0;

if($total_questions > 0){

    $percentage = ($correct / $total_questions) * 100;

}

$pass = $percentage >= 40;

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<title>Quiz Result</title>

<link rel="stylesheet"
href="assets/css/styles.css">

</head>

<body class="rs-body">

<div class="rs-container">

<div class="rs-card">

<div class="rs-title">
Quiz Result
</div>

<div class="rs-grid">

<div class="rs-box">

<h2>Correct Answers</h2>

<p class="rs-correct">
<?php echo $correct; ?>
</p>

</div>

<div class="rs-box">

<h2>Wrong Answers</h2>

<p class="rs-wrong">
<?php echo $wrong; ?>
</p>

</div>

<div class="rs-box">

<h2>Percentage</h2>

<p class="rs-percentage">
<?php echo round($percentage); ?>%
</p>

</div>

<div class="rs-box">

<h2>Status</h2>

<p class="<?php echo $pass ? 'rs-pass' : 'rs-fail'; ?>">

<?php

if($pass){

    echo "PASS";

} else {

    echo "FAIL";

}

?>

</p>

</div>

</div>

<a href="take-quiz.php?id=<?php echo $quiz_id; ?>&q=0">

<button class="rs-retry-btn">

Retry Quiz →

</button>

</a>

<a href="browse.php">

<button class="rs-home-btn">

Back To Browse

</button>

</a>

</div>

</div>

</body>
</html>