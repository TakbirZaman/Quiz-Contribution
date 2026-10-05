<?php
// Leaderboard: top scores per quiz + the viewer's personal best and rank.
// Usage: leaderboard.php (pick a quiz) or leaderboard.php?quiz_id=3
session_start();

if (!isset($_SESSION['user_id'])) {
    die("Please <a href='login.php'>login</a> to view the leaderboard.");
}

$conn = new mysqli("localhost", "root", "", "quiz_app");
if ($conn->connect_error) {
    die("Database connection failed.");
}

$quiz_id = isset($_GET['quiz_id']) ? intval($_GET['quiz_id']) : 0;

function h($s) {
    return htmlspecialchars((string)$s);
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leaderboard</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 20px; }
        .wrap { background: #fff; padding: 20px; border-radius: 10px; max-width: 700px; margin: auto; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        table { border-collapse: collapse; width: 100%; margin-top: 12px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: center; }
        th { background: #007bff; color: #fff; }
        tr.me td { background: #fff3cd; font-weight: bold; }
        .gold { font-size: 18px; }
        a { color: #007bff; }
    </style>
</head>
<body>
<div class="wrap">
    <p><a href="quiz_list.php">&larr; All quizzes</a></p>
<?php if ($quiz_id <= 0): ?>
    <h2>Leaderboards</h2>
    <p>Pick a quiz to see its top scores:</p>
    <ul>
    <?php
    $quizzes = $conn->query("SELECT id, title, category FROM quizzes ORDER BY title");
    while ($q = $quizzes->fetch_assoc()):
    ?>
        <li><a href="leaderboard.php?quiz_id=<?php echo (int)$q['id']; ?>"><?php echo h($q['title']); ?></a>
            <small>(<?php echo h($q['category']); ?>)</small></li>
    <?php endwhile; ?>
    </ul>
<?php else:
    $stmt = $conn->prepare("SELECT title, category FROM quizzes WHERE id = ?");
    $stmt->bind_param("i", $quiz_id);
    $stmt->execute();
    $quiz = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$quiz) {
        echo "<p>Quiz not found.</p>";
    } else {
        // Best attempt per user, top 10.
        $stmt = $conn->prepare(
            "SELECT user_id, user_name, MAX(percentage) AS best, MAX(score) AS best_score, MAX(total_questions) AS of_total, MIN(completed_at) AS first_at
             FROM quiz_results WHERE quiz_id = ?
             GROUP BY user_id, user_name
             ORDER BY best DESC, first_at ASC
             LIMIT 10"
        );
        $stmt->bind_param("i", $quiz_id);
        $stmt->execute();
        $top = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        // Viewer's personal best + rank (rank = 1 + users strictly ahead).
        $me = (int)$_SESSION['user_id'];
        $stmt = $conn->prepare("SELECT MAX(percentage) AS best FROM quiz_results WHERE quiz_id = ? AND user_id = ?");
        $stmt->bind_param("ii", $quiz_id, $me);
        $stmt->execute();
        $myBest = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        $myRank = null;
        if ($myBest && $myBest['best'] !== null) {
            $stmt = $conn->prepare("SELECT COUNT(*) + 1 AS r FROM (SELECT user_id FROM quiz_results WHERE quiz_id = ? GROUP BY user_id HAVING MAX(percentage) > ?) t");
            $stmt->bind_param("id", $quiz_id, $myBest['best']);
            $stmt->execute();
            $myRank = $stmt->get_result()->fetch_assoc()['r'];
            $stmt->close();
        }
    ?>
    <h2>Leaderboard: <?php echo h($quiz['title']); ?></h2>
    <?php if ($myRank !== null): ?>
        <p>Your best: <strong><?php echo h($myBest['best']); ?>%</strong> — rank <strong>#<?php echo (int)$myRank; ?></strong></p>
    <?php else: ?>
        <p>You haven't attempted this quiz yet. <a href="take_quiz.php?quiz_id=<?php echo $quiz_id; ?>">Take it</a> to enter the board.</p>
    <?php endif; ?>
    <?php if (empty($top)): ?>
        <p>No attempts yet — be the first.</p>
    <?php else: ?>
    <table>
        <tr><th>#</th><th>Student</th><th>Best</th><th>Score</th></tr>
        <?php $pos = 1; foreach ($top as $row): ?>
        <tr class="<?php echo ((int)$row['user_id'] === $me) ? 'me' : ''; ?>">
            <td class="gold"><?php echo $pos++; ?></td>
            <td><?php echo h($row['user_name']); ?></td>
            <td><?php echo h($row['best']); ?>%</td>
            <td><?php echo (int)$row['best_score']; ?>/<?php echo (int)$row['of_total']; ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
    <?php endif; ?>
    <?php } ?>
<?php endif; ?>
</div>
</body>
</html>
<?php $conn->close(); ?>
