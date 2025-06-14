<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("Unauthorized access.");
}

$conn = new mysqli("localhost", "root", "", "quiz_app");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <style>
        body { font-family: Arial; padding: 20px; background: #f0f0f0; }
        h2 { color: #333; }
        table { border-collapse: collapse; width: 100%; margin-bottom: 30px; background: #fff; }
        th, td { padding: 10px; border: 1px solid #ccc; text-align: left; }
        th { background-color: #007BFF; color: white; }
        a.delete { color: red; }
    </style>
</head>
<body>

<h2>👥 Users</h2>
<table>
    <tr><th>ID</th><th>Name</th><th>Email</th><th>Action</th></tr>
    <?php
    $users = $conn->query("SELECT * FROM users");
    while ($row = $users->fetch_assoc()) {
        echo "<tr>
            <td>{$row['id']}</td>
            <td>" . htmlspecialchars($row['name']) . "</td>
            <td>{$row['email']}</td>
            <td><a class='delete' href='delete_user.php?id={$row['id']}' onclick=\"return confirm('Are you sure?')\">Delete</a></td>
        </tr>";
    }
    ?>
</table>

<h2>📋 Quizzes</h2>
<table>
    <tr><th>ID</th><th>Title</th><th>Category</th><th>Action</th></tr>
    <?php
    $quizzes = $conn->query("SELECT * FROM quizzes");
    while ($row = $quizzes->fetch_assoc()) {
        echo "<tr>
            <td>{$row['id']}</td>
            <td>" . htmlspecialchars($row['title']) . "</td>
            <td>{$row['category']}</td>
            <td><a class='delete' href='delete_quiz.php?id={$row['id']}' onclick=\"return confirm('Are you sure?')\">Delete</a></td>
        </tr>";
    }
    ?>
</table>

<h2>📊 Quiz Results</h2>
<table>
    <tr><th>ID</th><th>User</th><th>Quiz ID</th><th>Score</th><th>Percent</th><th>Date</th></tr>
    <?php
    $results = $conn->query("SELECT * FROM quiz_results");
    while ($row = $results->fetch_assoc()) {
        echo "<tr>
            <td>{$row['id']}</td>
            <td>" . htmlspecialchars($row['user_name']) . "</td>
            <td>{$row['quiz_id']}</td>
            <td>{$row['score']} / {$row['total_questions']}</td>
            <td>{$row['percentage']}%</td>
            <td>{$row['completed_at']}</td>
        </tr>";
    }
    ?>
</table>

</body>
</html>
