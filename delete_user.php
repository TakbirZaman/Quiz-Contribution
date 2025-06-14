<?php
session_start();
if ($_SESSION['role'] !== 'admin') { die("Access denied."); }

$conn = new mysqli("localhost", "root", "", "quiz_app");
$id = intval($_GET['id']);

// First delete quiz_result_details for this user (if needed)
$conn->query("DELETE FROM quiz_result_details WHERE result_id IN 
    (SELECT id FROM quiz_results WHERE user_id = $id)");

// Then delete from quiz_results
$conn->query("DELETE FROM quiz_results WHERE user_id = $id");

// Finally delete the user
$conn->query("DELETE FROM users WHERE id = $id");

header("Location: admin_dashboard.php");
?>
