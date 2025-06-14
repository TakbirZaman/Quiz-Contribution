<?php
$conn = new mysqli("localhost", "root", "", "quiz_app");

$quiz_id = isset($_GET['quiz_id']) ? intval($_GET['quiz_id']) : 0;

if (!$quiz_id) {
    echo "❌ Invalid quiz ID.";
    exit;
}

$questions = $conn->query("SELECT * FROM questions WHERE quiz_id = $quiz_id");

if (!$questions || $questions->num_rows === 0) {
    echo "❌ No questions found for this quiz.";
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Quiz</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            margin: 20px;
        }
        form {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 700px;
            margin: auto;
        }
        fieldset {
            border: 2px solid #007bff;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        legend {
            font-weight: bold;
            color: #007bff;
        }
        label {
            display: block;
            margin-bottom: 8px;
        }
        button {
            padding: 10px 20px;
            background: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }
        button:hover {
            background: #218838;
        }
        .timer {
            text-align: right;
            font-weight: bold;
            font-size: 18px;
            color: red;
            margin-bottom: 10px;
        }
    </style>
    <script>
        let timeLeft = 60; // seconds
        function startTimer() {
            const timerDisplay = document.getElementById('timer');
            const interval = setInterval(() => {
                const minutes = Math.floor(timeLeft / 60);
                const seconds = timeLeft % 60;
                timerDisplay.textContent = `Time Left: ${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;

                if (timeLeft <= 0) {
                    clearInterval(interval);
                    alert("Time's up! Submitting quiz.");
                    document.getElementById('quizForm').submit();
                }
                timeLeft--;
            }, 1000);
        }
        window.onload = startTimer;
    </script>
</head>
<body>
   <form id="quizForm" action="submit_quiz.php" method="POST">
    <div class="timer" id="timer">Time Left: 1:00</div>
    <input type="hidden" name="quiz_id" value="<?= $quiz_id ?>">

    <?php
    $qno = 1;
    $option_labels = ['A', 'B', 'C', 'D'];
    $option_keys = ['option1', 'option2', 'option3', 'option4'];

    while ($q = $questions->fetch_assoc()) {
        echo "<fieldset>";
        echo "<legend>Q$qno: " . htmlspecialchars($q['question_text']) . "</legend>";

        for ($i = 0; $i < 4; $i++) {
            $label = $option_labels[$i];
            $option_text = htmlspecialchars($q[$option_keys[$i]]);
            echo "<label><input type='radio' name='answer[{$q['id']}]' value='$label' required> $label) $option_text</label>";
        }

        echo "</fieldset>";
        $qno++;
    }
    ?>

    <script>
    let timeLeft = 60; // Time in seconds
    const timerElement = document.getElementById("timer");
    const submitButton = document.querySelector("button[type='submit']");
    
    function updateTimer() {
        const minutes = Math.floor(timeLeft / 60);
        const seconds = timeLeft % 60;
        timerElement.textContent = `Time Left: ${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
        
        if (timeLeft <= 0) {
            clearInterval(timerInterval);
            submitButton.disabled = true;  // Disable submit button after time is up
            alert("Time's up! Your quiz has been submitted automatically.");
            document.getElementById("quizForm").submit();  // Automatically submit the form
        }
        timeLeft--;
    }

    const timerInterval = setInterval(updateTimer, 1000); // Update every second
</script>

    <button type="submit">Submit Quiz</button>
</form>
</body>
</html>
