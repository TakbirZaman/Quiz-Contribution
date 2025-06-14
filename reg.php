<?php
$conn = new mysqli("localhost", "root", "", "quiz_app");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Basic example: just show a message (you can later add database logic)
    $name = htmlspecialchars($_POST["name"]);
    echo "<p style='color: green;'>✅ Registration successful!!!!</p>";
} else {
    echo "<p style='color: red;'>❌ Invalid request.</p>";
}
?>
