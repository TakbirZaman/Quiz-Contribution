<?php
$conn = new mysqli("localhost", "root", "", "quiz_app");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'] ?? '';
    $category = $_POST['category'] ?? '';
    $total_questions = $_POST['total_questions'] ?? 0;
    $time_limit = $_POST['time_limit'] ?? 0;
    $created_by = $_POST['created_by'] ?? 0;

    if ($title && $category && $total_questions && $time_limit && $created_by) {
        $sql = "INSERT INTO quizzes (title, category, total_questions, time_limit, created_by)
                VALUES ('$title', '$category', $total_questions, $time_limit, $created_by)";

        if ($conn->query($sql)) {
            $quiz_id = $conn->insert_id;
            $message = "<p class='success'>✅ Quiz created successfully! <a href='add_questions.php?quiz_id=$quiz_id'>Add Questions</a></p>";
        } else {
            $message = "<p class='error'>❌ SQL Error: " . $conn->error . "</p>";
        }
    } else {
        $message = "<p class='error'>❌ All fields are required!</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Quiz</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f0f4f8;
            display: flex;
            justify-content: center;
            align-items: start;
            padding: 40px;
        }

        .quiz-container {
            background: #fff;
            padding: 30px 40px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 500px;
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 25px;
        }

        input[type="text"],
        input[type="number"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            transition: border-color 0.3s;
        }

        input[type="text"]:focus,
        input[type="number"]:focus {
            border-color: #4a90e2;
            outline: none;
        }

        button {
            width: 100%;
            background: #4a90e2;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s;
        }

        button:hover {
            background: #3b7dc4;
        }

        .success {
            background: #e0f9e7;
            color: #2d7a3e;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 15px;
            text-align: center;
        }

        .error {
            background: #ffe6e6;
            color: #c0392b;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 15px;
            text-align: center;
        }

        a {
            color: #4a90e2;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="quiz-container">
        <h2>Create a New Quiz</h2>

        <?php if (isset($message)) echo $message; ?>

        <form method="POST" action="create_quiz.php">
            <input type="text" name="title" placeholder="Quiz Title" required>
            <input type="text" name="category" placeholder="Category" required>
            <input type="number" name="total_questions" placeholder="Total Questions" min="1" required>
            <input type="number" name="time_limit" placeholder="Time Limit (minutes)" min="1" required>
            <input type="hidden" name="created_by" value="101"> <!-- Static teacher ID -->
            <button type="submit">Create Quiz</button>
        </form>
    </div>
</body>
</html>
