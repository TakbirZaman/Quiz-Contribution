<?php
$conn = new mysqli("localhost", "root", "", "quiz_app");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$quiz_id = $_GET['quiz_id'] ?? null;

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $quiz_id = $_POST['quiz_id'];
    $question_text = mysqli_real_escape_string($conn, $_POST['question_text'] ?? '');
    $option1 = mysqli_real_escape_string($conn, $_POST['option1'] ?? '');
    $option2 = mysqli_real_escape_string($conn, $_POST['option2'] ?? '');
    $option3 = mysqli_real_escape_string($conn, $_POST['option3'] ?? '');
    $option4 = mysqli_real_escape_string($conn, $_POST['option4'] ?? '');
    $correct = strtoupper(mysqli_real_escape_string($conn, $_POST['correct'] ?? ''));

    if ($quiz_id && $question_text && $option1 && $option2 && $option3 && $option4 && in_array($correct, ['A', 'B', 'C', 'D'])) {
        $sql = "INSERT INTO questions (quiz_id, question_text, option1, option2, option3, option4, correct_option)
                VALUES ($quiz_id, '$question_text', '$option1', '$option2', '$option3', '$option4', '$correct')";

        if ($conn->query($sql)) {
            $message = "<p class='success'>✅ Question saved successfully!</p>";
        } else {
            $message = "<p class='error'>❌ SQL Error: " . $conn->error . "</p>";
        }
    } else {
        $message = "<p class='error'>❗ Missing or invalid input!</p>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Question</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f6f8;
            padding: 30px;
        }
        .container {
            max-width: 600px;
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
            margin: auto;
        }
        h2 {
            text-align: center;
            color: #333;
        }
        label {
            font-weight: bold;
            margin-top: 10px;
            display: block;
        }
        textarea, input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border-radius: 6px;
            border: 1px solid #ccc;
            margin-bottom: 15px;
        }
        .option-label {
            display: flex;
            align-items: center;
        }
        .option-label span {
            width: 25px;
            font-weight: bold;
        }
        .success {
            color: green;
            text-align: center;
        }
        .error {
            color: red;
            text-align: center;
        }
        button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            display: block;
            margin: auto;
            margin-top: 10px;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>➕ Add a New Question</h2>
    <?= $message ?? '' ?>
    <form action="add_questions.php?quiz_id=<?= $quiz_id ?>" method="POST">
        <input type="hidden" name="quiz_id" value="<?= $quiz_id ?>">

        <label>Question:</label>
        <textarea name="question_text" rows="3" required placeholder="e.g. A ball released in positive gravity will:"></textarea>

        <label>Options:</label>
        <div class="option-label"><span>A)</span> <input type="text" name="option1" required></div>
        <div class="option-label"><span>B)</span> <input type="text" name="option2" required></div>
        <div class="option-label"><span>C)</span> <input type="text" name="option3" required></div>
        <div class="option-label"><span>D)</span> <input type="text" name="option4" required></div>

        <label>Correct Option (A/B/C/D):</label>
        <input type="text" name="correct" maxlength="1" placeholder="e.g. A" required>

        <button type="submit">💾 Save Question</button>
        <button type="button" onclick="window.location.href='index.html'" style="background-color: #dc3545; margin-left: 10px;">❌ Cancel</button>

    </form>
</div>
</body>
</html>
