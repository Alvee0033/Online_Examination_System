<?php
session_start();
if (!isset($_SESSION['student_logged_in'])) {
    header("Location: student_login.php");
    exit();
}

$exam_id = isset($_GET['exam_id']) ? intval($_GET['exam_id']) : null;
if (!$exam_id) {
    die("Invalid Exam ID.");
}

$db = new SQLite3('home/database.db');
$student_id = $_SESSION['student_id'];

// Check if the student has already taken the exam
$stmt = $db->prepare("SELECT * FROM previous_exams WHERE student_id = :student_id AND exam_id = :exam_id");
$stmt->bindValue(':student_id', $student_id, SQLITE3_INTEGER);
$stmt->bindValue(':exam_id', $exam_id, SQLITE3_INTEGER);
$already_taken = $stmt->execute()->fetchArray(SQLITE3_ASSOC);

if ($already_taken) {
    die("You have already taken this exam.");
}

// Fetch questions
$questions_stmt = $db->prepare("SELECT * FROM questions WHERE exam_id = :exam_id");
$questions_stmt->bindValue(':exam_id', $exam_id, SQLITE3_INTEGER);
$questions = $questions_stmt->execute();

// Fetch exam time limit
$exam_info_stmt = $db->prepare("SELECT time_limit FROM exams WHERE id = :exam_id");
$exam_info_stmt->bindValue(':exam_id', $exam_id, SQLITE3_INTEGER);
$exam_info = $exam_info_stmt->execute()->fetchArray(SQLITE3_ASSOC);
$time_limit = $exam_info['time_limit'] * 60; // Convert to seconds

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $score = 0;
    $total_questions = 0;

    $questions_stmt->reset(); // Reset the statement
    $questions = $questions_stmt->execute();

    while ($question = $questions->fetchArray(SQLITE3_ASSOC)) {
        $total_questions++;
        $question_id = $question['id'];
        $correct_answer = $question['correct_answer'];
        $student_answer = isset($_POST['answer_'.$question_id]) ? $_POST['answer_'.$question_id] : '';

        // Insert student answer
        $insert_answer = $db->prepare("
            INSERT INTO student_answers (student_id, exam_id, question_id, student_answer)
            VALUES (:student_id, :exam_id, :question_id, :student_answer)
        ");
        $insert_answer->bindValue(':student_id', $student_id, SQLITE3_INTEGER);
        $insert_answer->bindValue(':exam_id', $exam_id, SQLITE3_INTEGER);
        $insert_answer->bindValue(':question_id', $question_id, SQLITE3_INTEGER);
        $insert_answer->bindValue(':student_answer', $student_answer, SQLITE3_TEXT);
        $insert_answer->execute();

        if ($student_answer === $correct_answer) {
            $score++;
        }
    }

    // Insert exam result
    $insert_result = $db->prepare("
        INSERT INTO previous_exams (student_id, exam_id, score, total_questions)
        VALUES (:student_id, :exam_id, :score, :total_questions)
    ");
    $insert_result->bindValue(':student_id', $student_id, SQLITE3_INTEGER);
    $insert_result->bindValue(':exam_id', $exam_id, SQLITE3_INTEGER);
    $insert_result->bindValue(':score', $score, SQLITE3_INTEGER);
    $insert_result->bindValue(':total_questions', $total_questions, SQLITE3_INTEGER);
    $insert_result->execute();

    header("Location: view_results.php?exam_id=" . $exam_id); // Redirect to view results page
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Take Exam</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #e0f7fa; /* Light background */
            padding: 20px;
            margin: 0;
            animation: fadeIn 1s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        h1 {
            text-align: center;
            color: #007bff;
            font-size: 2.5em;
            margin-bottom: 20px;
            animation: slideIn 1s ease;
        }

        @keyframes slideIn {
            from { transform: translateY(-50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .exam-container {
            max-width: 900px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
            animation: zoomIn 0.5s ease;
        }

        @keyframes zoomIn {
            from { transform: scale(0.8); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }

        .timer {
            font-size: 2em;
            color: #e74c3c;
            text-align: center;
            margin-bottom: 30px;
            animation: blink 1s infinite;
        }

        @keyframes blink {
            50% { opacity: 0.5; }
        }

        .question {
            margin-bottom: 25px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 10px;
            background: #f9f9f9;
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .question:hover {
            background-color: #f0f8ff;
            transform: translateY(-5px);
        }

        label {
            display: block;
            margin: 10px 0;
            font-size: 1.2em;
            cursor: pointer;
        }

        button {
            display: block;
            width: 100%;
            padding: 15px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1.2em;
            cursor: pointer;
            transition: background 0.3s ease;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <div class="exam-container">
        <h1>Take Exam</h1>
        <div class="timer" id="timer"></div> <!-- Timer display -->
        <form method="POST">
            <?php while ($question = $questions->fetchArray(SQLITE3_ASSOC)): ?>
                <div class="question">
                    <p><?php echo htmlspecialchars($question['question']); ?></p>
                    <label>
                        <input type="radio" name="answer_<?php echo $question['id']; ?>" value="option1" required>
                        <?php echo htmlspecialchars($question['option1']); ?>
                    </label>
                    <label>
                        <input type="radio" name="answer_<?php echo $question['id']; ?>" value="option2">
                        <?php echo htmlspecialchars($question['option2']); ?>
                    </label>
                    <label>
                        <input type="radio" name="answer_<?php echo $question['id']; ?>" value="option3">
                        <?php echo htmlspecialchars($question['option3']); ?>
                    </label>
                    <label>
                        <input type="radio" name="answer_<?php echo $question['id']; ?>" value="option4">
                        <?php echo htmlspecialchars($question['option4']); ?>
                    </label>
                </div>
            <?php endwhile; ?>
            <button type="submit">Submit Exam</button>
        </form>
    </div>

    <script>
        // Set the timer
        var timeLimit = <?php echo $time_limit; ?>; // Time limit in seconds
        var timerDisplay = document.getElementById('timer');

        function startTimer(duration) {
            var timer = duration, minutes, seconds;
            var interval = setInterval(function () {
                minutes = parseInt(timer / 60, 10);
                seconds = parseInt(timer % 60, 10);

                minutes = minutes < 10 ? "0" + minutes : minutes;
                seconds = seconds < 10 ? "0" + seconds : seconds;

                timerDisplay.textContent = minutes + ":" + seconds;

                if (--timer < 0) {
                    clearInterval(interval);
                    document.forms[0].submit(); // Auto-submit the exam
                }
            }, 1000);
        }

        window.onload = function () {
            startTimer(timeLimit);
        };
    </script>
</body>
</html>
