<?php
// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "quiz_app"; // Replace with your actual database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    echo "Connected successfully!<br>"; // For debugging purposes
}

// SQL query to fetch quizzes sorted by category
$sql = "SELECT * FROM quizzes ORDER BY category ASC";

// Debugging: Display the SQL query
echo "Running query: " . $sql . "<br>";

$result = $conn->query($sql);

// Check if the query was successful
if ($result) {
    echo "Query executed successfully.<br>";
    
    // Check if there are any rows returned
    if ($result->num_rows > 0) {
        // Output data for each row
        while ($row = $result->fetch_assoc()) {
            echo "ID: " . $row["id"] . " - Title: " . $row["title"] . " - Category: " . $row["category"] . "<br>";
        }
    } else {
        echo "No quizzes found.<br>";
    }
} else {
    echo "Error executing query: " . $conn->error . "<br>";
}

// Close the connection
$conn->close();
?>
