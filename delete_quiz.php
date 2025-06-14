<?php
session_start();
if ($_SESSION['role'] !== 'admin') { die("Access denied."); }

$conn = new mysqli("localhost", "root", "", "quiz_app");
$quiz_id = intval($_GET['id']);

// Delete result details linked to this quiz
$conn->query("DELETE FROM quiz_result_details WHERE result_id IN 
    (SELECT id FROM quiz_results WHERE quiz_id = $quiz_id)");

// Delete quiz results
$conn->query("DELETE FROM quiz_results WHERE quiz_id = $quiz_id");

// Delete questions
$conn->query("DELETE FROM questions WHERE quiz_id = $quiz_id");

// Delete quiz
$conn->query("DELETE FROM quizzes WHERE id = $quiz_id");

header("Location: admin_dashboard.php"); // or your quiz list page
?>
