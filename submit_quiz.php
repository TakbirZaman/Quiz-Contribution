<?php
session_start();
date_default_timezone_set('Asia/Dhaka');
$conn = new mysqli("localhost", "root", "", "quiz_app");

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    die("Please login to take the quiz.");
}

if (!isset($_POST['quiz_id'])) {
    die("No quiz selected. Please go back and choose a quiz.");
}

$user_answers = $_POST['answer'] ?? [];

$quiz_id = $_POST['quiz_id'] ?? 0;

if ($quiz_id == 0) {
    die("Invalid quiz.");
}


$user_id = $_SESSION['user_id'];
$user_sql = "SELECT name FROM users WHERE id = $user_id";
$user_result = $conn->query($user_sql);
if ($user_result && $user_result->num_rows > 0) {
    $user = $user_result->fetch_assoc();
    $user_name = $user['name'];
} else {
    die("User not found. Please login again.");
}


// Get questions
$question_sql = "SELECT * FROM questions WHERE quiz_id = $quiz_id";
$question_result = $conn->query($question_sql);

$score = 0;
$total = $question_result->num_rows;
date_default_timezone_set('Asia/Dhaka');
$completed_at = date("Y-m-d H:i:s");
$placeholder_percentage = 0.0;

$selected_csv = '';
$correct_csv = '';

foreach ($question_result as $question) {
    $qid = $question['id'];
    $correct_letter = strtoupper($question['correct_option']);
    $user_letter = strtoupper($user_answers[$qid] ?? '');

    $selected_csv .= "$qid:$user_letter,";
    $correct_csv .= "$qid:$correct_letter,";

    if ($user_letter && $user_letter === $correct_letter) {
        $score++;
    }
}

// Remove trailing comma
$selected_csv = rtrim($selected_csv, ',');
$correct_csv = rtrim($correct_csv, ',');

$percentage = ($total > 0) ? round(($score / $total) * 100, 2) : 0.00;


// Insert quiz_results initially
$stmt = $conn->prepare("INSERT INTO quiz_results 
    (user_id, user_name, quiz_id, selected_options, correct_options, score, total_questions, percentage, completed_at)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("isissiiis", 
    $user_id, $user_name, $quiz_id, 
    $selected_csv, $correct_csv, 
    $score, $total, $percentage, $completed_at);

$stmt->execute();
$result_id = $stmt->insert_id;


// Display results
echo "<div class='result-container'>";
echo "<h2 class='result-header'>📋 Quiz Results for $user_name</h2>";

$question_result->data_seek(0);
while ($question = $question_result->fetch_assoc()) {
    $qid = $question['id'];
    $question_text = $question['question_text'];
    $correct_letter = strtoupper($question['correct_option']);
    $correct_index = array_search($correct_letter, ['A', 'B', 'C', 'D']);
    $correct_text = $question['option' . ($correct_index + 1)];

    echo "<div class='question-block'>";
    echo "<strong>Q: " . htmlspecialchars($question_text) . "</strong><br>";

    $user_letter = strtoupper($user_answers[$qid] ?? '');
    $user_index = array_search($user_letter, ['A', 'B', 'C', 'D']);
    $user_text = ($user_index !== false && isset($question['option' . ($user_index + 1)])) 
                 ? $question['option' . ($user_index + 1)] : null;

    if ($user_letter && $user_text) {
        echo "<div class='your-answer'>Your Answer: " . htmlspecialchars($user_text) . "</div>";

        if ($user_letter === $correct_letter) {
            echo "<div class='correct-answer'>Correct ✅</div><br><br>";
            $is_correct = 1;
        } else {
            echo "<div class='wrong-answer'>Wrong ❌</div>";
            echo "<div class='correct-answer'>Correct Answer: " . htmlspecialchars($correct_text) . "</div><br><br>";
            $is_correct = 0;
        }
    } else {
        echo "<div class='your-answer'>Your Answer: <span style='color: gray;'>Not answered</span></div>";
        echo "<div class='correct-answer'>Correct Answer: " . htmlspecialchars($correct_text) . "</div><br>";
        echo "<div class='wrong-answer'>Wrong ❌</div><br><br>";
        $is_correct = 0;
    }

    $stmt2 = $conn->prepare("INSERT INTO quiz_result_details 
        (result_id, question_id, question_text, selected_option, selected_text, correct_option, correct_text, is_correct)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt2->bind_param("iisssssi", $result_id, $qid, $question_text, $user_letter, $user_text, $correct_letter, $correct_text, $is_correct);
    $stmt2->execute();

    echo "</div>";
}

// Update final score and percentage
$percentage = ($total > 0) ? round(($score / $total) * 100, 2) : 0.0;
$update = $conn->prepare("UPDATE quiz_results SET score = ?, percentage = ? WHERE id = ?");
$update->bind_param("idi", $score, $percentage, $result_id);
$update->execute();

echo "<div class='score-summary'><hr><h3>Your Score: <strong>$score / $total</strong> ($percentage%)</h3></div>";
echo "</div>";
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Result</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f2f2f2;
            padding: 20px;
        }
        .result-container {
            background: #fff;
            padding: 30px;
            max-width: 800px;
            margin: auto;
            border-radius: 10px;
            box-shadow: 0 0 12px rgba(0, 0, 0, 0.1);
        }
        .result-header {
            text-align: center;
            color: #007bff;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .question-block {
            background: #f9f9f9;
            padding: 15px;
            border-left: 5px solid #007bff;
            margin-bottom: 15px;
            border-radius: 5px;
        }
        .your-answer, .correct-answer, .wrong-answer {
            font-size: 16px;
            margin-top: 10px;
        }
        .your-answer {
            font-weight: bold;
        }
        .correct-answer {
            color: green;
        }
        .wrong-answer {
            color: red;
        }
        .score-summary {
            text-align: center;
            margin-top: 20px;
        }
        hr {
            border: 1px solid #ccc;
            margin: 20px 0;
        }
    </style>
</head>
<body>

</body>
</html>
