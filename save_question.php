<?php
$conn = new mysqli("localhost", "root", "", "quiz_app");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $quiz_id = intval($_POST['quiz_id']);
    $qtext = $conn->real_escape_string($_POST['question_text']);
    $correct = strtoupper(trim($_POST['correct']));

    $options = [
        "A" => $conn->real_escape_string($_POST['option1']),
        "B" => $conn->real_escape_string($_POST['option2']),
        "C" => $conn->real_escape_string($_POST['option3']),
        "D" => $conn->real_escape_string($_POST['option4'])
    ];

    // Save question
    $sql = "INSERT INTO questions (quiz_id, question_text) VALUES ($quiz_id, '$qtext')";
    if ($conn->query($sql)) {
        $question_id = $conn->insert_id;

        // Save options
        foreach ($options as $key => $text) {
            $is_correct = ($key === $correct) ? 1 : 0;
            $conn->query("INSERT INTO options (question_id, option_text, is_correct)
                          VALUES ($question_id, '$text', $is_correct)");
        }

        echo "✅ Question saved. <a href='add_questions.php?quiz_id=$quiz_id'>Add another</a>";
    } else {
        echo "❌ Error saving question: " . $conn->error;
    }
} else {
    echo "Invalid request.";
}
?>
