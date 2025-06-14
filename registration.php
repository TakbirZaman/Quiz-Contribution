<?php
session_start();

// ✅ Replace with your actual DB credentials
$conn = new mysqli("localhost", "root", "", "quiz_app");

// Check DB connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Collect and sanitize form data
$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$dob = $_POST['dob'] ?? '';
$country = $_POST['country'] ?? '';
$gender = $_POST['gender'] ?? '';
$favorite_color = $_POST['favorite_color'] ?? '';
$agreed = isset($_POST['agree']) ? 1 : 0;
$role = 'student';

// ✅ Hash password
$hashed_password = password_hash($password, PASSWORD_BCRYPT);

// ✅ Check if email already exists (using prepared statement)
$check_stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
$check_stmt->bind_param("s", $email);
$check_stmt->execute();
$check_stmt->store_result();

if ($check_stmt->num_rows > 0) {
    echo "❌ Email already registered. <a href='login.php'>Login here</a>";
    exit;
}
$check_stmt->close();

// ✅ Insert data (using prepared statement)
$insert_stmt = $conn->prepare("INSERT INTO users (name, email, password, dob, country, gender, favorite_color, agreed, role) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
$insert_stmt->bind_param("sssssssis", $name, $email, $hashed_password, $dob, $country, $gender, $favorite_color, $agreed, $role);

if ($insert_stmt->execute()) {
    echo "✅ Registration successful. <a href='login.php'>Login now</a>";
} else {
    echo "❌ Error: " . $insert_stmt->error;
}

$insert_stmt->close();
$conn->close();
?>
