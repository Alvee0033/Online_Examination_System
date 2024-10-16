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

// Fetch exam title
$exam_stmt = $db->prepare("SELECT title FROM exams WHERE id = :exam_id");
$exam_stmt->bindValue(':exam_id', $exam_id, SQLITE3_INTEGER);
$exam_result = $exam_stmt->execute()->fetchArray(SQLITE3_ASSOC);
$exam_title = $exam_result['title'];

// Fetch exam results for the student
$results_stmt = $db->prepare("
    SELECT q.question, q.option1, q.option2, q.option3, q.option4, q.correct_answer, sa.student_answer 
    FROM questions q
    JOIN student_answers sa ON q.id = sa.question_id
    WHERE sa.exam_id = :exam_id AND sa.student_id = :student_id
");
$results_stmt->bindValue(':exam_id', $exam_id, SQLITE3_INTEGER);
$results_stmt->bindValue(':student_id', $student_id, SQLITE3_INTEGER);
$results = $results_stmt->execute();

// Calculate obtained marks and total marks
$total_questions = 0;
$correct_answers = 0;
while ($row = $results->fetchArray(SQLITE3_ASSOC)) {
    $total_questions++;
    if ($row['correct_answer'] == $row['student_answer']) {
        $correct_answers++;
    }
}
$obtained_marks = $correct_answers;
$total_marks = $total_questions;

$results->reset(); // Reset result cursor for rendering

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam Results</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f0f8ff;
            padding: 20px;
            margin: 0;
        }

        h1, h2 {
            text-align: center;
            color: #007bff;
            font-size: 2.5em;
            margin-bottom: 10px;
        }

        h2 {
            font-size: 1.8em;
        }

        .result-container {
            max-width: 900px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            animation: fadeIn 1s ease-in-out;
        }

        .question {
            margin-bottom: 20px;
            padding: 15px;
            border-radius: 10px;
            background: #f9f9f9;
            border-left: 5px solid #007bff;
            animation: slideIn 0.8s ease;
        }

        .option {
            padding-left: 25px;
            line-height: 1.6;
            position: relative;
            margin-bottom: 10px;
        }

        .option:before {
            content: attr(data-label);
            font-weight: bold;
            color: #007bff;
            margin-right: 10px;
        }

        .correct {
            color: green;
            font-weight: bold;
        }

        .wrong {
            color: red;
            font-weight: bold;
        }

        .icon {
            font-size: 1.2em;
            display: inline-block;
            margin-left: 10px; /* Minimal distance from option text */
        }

        .tick::before {
            content: "✔";
            color: green;
        }

        .cross::before {
            content: "✘";
            color: red;
        }

        /* Mobile Optimization */
        @media (max-width: 768px) {
            .result-container {
                padding: 15px;
            }

            h1, h2 {
                font-size: 1.8em;
            }

            .question {
                padding: 10px;
            }

            .option {
                padding-left: 20px;
            }
        }

        /* Animations */
        @keyframes fadeIn {
            0% { opacity: 0; }
            100% { opacity: 1; }
        }

        @keyframes slideIn {
            0% { transform: translateX(-50px); opacity: 0; }
            100% { transform: translateX(0); opacity: 1; }
        }
    </style>
</head>
<body>
    <h1><?php echo htmlspecialchars($exam_title); ?></h1>
    <h2>Marks: <?php echo $obtained_marks . " / " . $total_marks; ?></h2>

    <div class="result-container">
        <?php 
        $question_number = 1; // Counter for question numbering
        while ($row = $results->fetchArray(SQLITE3_ASSOC)): ?>
            <div class="question">
                <p><strong>Question <?php echo $question_number++; ?>:</strong> <?php echo htmlspecialchars($row['question']); ?></p>

                <div class="option <?php echo $row['correct_answer'] == 'option1' ? 'correct' : ($row['student_answer'] == 'option1' ? 'wrong' : ''); ?>" data-label="A.">
                    <?php echo htmlspecialchars($row['option1']); ?>
                    <span class="<?php echo ($row['student_answer'] == 'option1') ? ($row['student_answer'] == $row['correct_answer'] ? 'tick icon' : 'cross icon') : ''; ?>"></span>
                </div>

                <div class="option <?php echo $row['correct_answer'] == 'option2' ? 'correct' : ($row['student_answer'] == 'option2' ? 'wrong' : ''); ?>" data-label="B.">
                    <?php echo htmlspecialchars($row['option2']); ?>
                    <span class="<?php echo ($row['student_answer'] == 'option2') ? ($row['student_answer'] == $row['correct_answer'] ? 'tick icon' : 'cross icon') : ''; ?>"></span>
                </div>

                <div class="option <?php echo $row['correct_answer'] == 'option3' ? 'correct' : ($row['student_answer'] == 'option3' ? 'wrong' : ''); ?>" data-label="C.">
                    <?php echo htmlspecialchars($row['option3']); ?>
                    <span class="<?php echo ($row['student_answer'] == 'option3') ? ($row['student_answer'] == $row['correct_answer'] ? 'tick icon' : 'cross icon') : ''; ?>"></span>
                </div>

                <div class="option <?php echo $row['correct_answer'] == 'option4' ? 'correct' : ($row['student_answer'] == 'option4' ? 'wrong' : ''); ?>" data-label="D.">
                    <?php echo htmlspecialchars($row['option4']); ?>
                    <span class="<?php echo ($row['student_answer'] == 'option4') ? ($row['student_answer'] == $row['correct_answer'] ? 'tick icon' : 'cross icon') : ''; ?>"></span>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</body>
</html>
