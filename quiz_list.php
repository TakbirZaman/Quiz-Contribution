<?php
$conn = new mysqli("localhost", "root", "", "quiz_app");
$result = $conn->query("SELECT * FROM quizzes");

echo "<h2>Available Quizzes</h2>";
while ($row = $result->fetch_assoc()) {
    echo "<div style='margin-bottom:15px; border:1px solid #ccc; padding:10px'>";
    echo "<strong>Title:</strong> " . $row['title'] . "<br>";
    echo "<strong>Category:</strong> " . $row['category'] . "<br>";
    echo "<strong>Questions:</strong> " . $row['total_questions'] . "<br>";
    echo "<strong>Time Limit:</strong> " . $row['time_limit'] . " mins<br>";
    echo "<a href='take_quiz.php?quiz_id=" . $row['id'] . "'>Start Quiz</a>";
    echo "</div>";
}
?>
