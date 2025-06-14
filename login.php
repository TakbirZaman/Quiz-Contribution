<?php
session_start();
$conn = new mysqli("localhost", "root", "", "quiz_app");

$email = $_POST['email'];
$password = $_POST['password'];

// Admin logins
if ($email == '01@teacher.aiub.edu') {
    if ($password == 'teacher123') {
        $_SESSION['user_id'] = 0;
        $_SESSION['user_name'] = "Admin";
        $_SESSION['role'] = 'admin';
        header("Location: create_quiz.php");
        exit();
    } else {
        echo "❌ Invalid admin password.";
        exit();
    }
} else if ($email == 'admin2@aiub.edu') {
    if ($password == 'admin456') {
        $_SESSION['user_id'] = -1; // Unique ID for second admin
        $_SESSION['user_name'] = "Admin2";
        $_SESSION['role'] = 'admin';
        header("Location: admin_dashboard.php");
        exit();
    } else {
        echo "❌ Invalid admin2 password.";
        exit();
    }
} else {
    // Student login path
    $result = $conn->query("SELECT * FROM users WHERE email = '$email'");
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['role'] = 'student';
        header("Location: quiz_list.php");
        exit();
    } else {
        echo "❌ Invalid email or password. Please register if you haven't.";
        echo "<br><a href='registration.php'>Go to Registration</a>";
    }
}
?>
