<?php 
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}

$db = new SQLite3('home/database.db');
$exam_id = isset($_GET['exam_id']) ? intval($_GET['exam_id']) : null;

// Fetch exam details
$stmt = $db->prepare("SELECT * FROM exams WHERE id = :exam_id");
$stmt->bindValue(':exam_id', $exam_id, SQLITE3_INTEGER);
$exam = $stmt->execute()->fetchArray(SQLITE3_ASSOC);

if (!$exam) {
    die("Invalid Exam ID.");
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $num_questions = $exam['num_questions'];

    for ($i = 1; $i <= $num_questions; $i++) {
        $question = $_POST["question_$i"];
        $option1 = $_POST["option1_$i"];
        $option2 = $_POST["option2_$i"];
        $option3 = $_POST["option3_$i"];
        $option4 = $_POST["option4_$i"];
        $correct_answer = $_POST["correct_answer_$i"];

        $stmt = $db->prepare("INSERT INTO questions (exam_id, question, option1, option2, option3, option4, correct_answer) 
                              VALUES (:exam_id, :question, :option1, :option2, :option3, :option4, :correct_answer)");
        $stmt->bindValue(':exam_id', $exam_id, SQLITE3_INTEGER);
        $stmt->bindValue(':question', $question, SQLITE3_TEXT);
        $stmt->bindValue(':option1', $option1, SQLITE3_TEXT);
        $stmt->bindValue(':option2', $option2, SQLITE3_TEXT);
        $stmt->bindValue(':option3', $option3, SQLITE3_TEXT);
        $stmt->bindValue(':option4', $option4, SQLITE3_TEXT);
        $stmt->bindValue(':correct_answer', $correct_answer, SQLITE3_TEXT);
        $stmt->execute();
    }
    
    header("Location: admin_dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enter Questions</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            padding: 20px;
            background-color: #f0f2f5;
            animation: fadeIn 0.5s;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }

        form {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s;
        }

        form:hover {
            transform: scale(1.02);
        }

        .question-block {
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 15px;
            background-color: #f9f9f9;
            transition: background-color 0.3s;
        }

        .question-block:hover {
            background-color: #e9ecef;
        }

        label {
            display: block;
            margin: 10px 0 5px;
            font-weight: bold;
        }

        input[type="text"], select {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
            transition: border-color 0.3s;
        }

        input[type="text"]:focus, select:focus {
            border-color: #007bff;
            outline: none;
        }

        button {
            background-color: #007bff;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s, transform 0.2s;
            display: block;
            margin: 20px auto 0;
        }

        button:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
        }

        button:active {
            transform: translateY(0);
        }
    </style>
</head>
<body>
    <h1>Enter Questions for Exam ID: <?= htmlspecialchars($exam_id) ?></h1>
    <form method="POST">
        <?php for ($i = 1; $i <= $exam['num_questions']; $i++): ?>
            <div class="question-block">
                <label for="question_<?= $i ?>">Question <?= $i ?>:</label>
                <input type="text" name="question_<?= $i ?>" id="question_<?= $i ?>" required>

                <label for="option1_<?= $i ?>">Option 1:</label>
                <input type="text" name="option1_<?= $i ?>" id="option1_<?= $i ?>" required>

                <label for="option2_<?= $i ?>">Option 2:</label>
                <input type="text" name="option2_<?= $i ?>" id="option2_<?= $i ?>" required>

                <label for="option3_<?= $i ?>">Option 3:</label>
                <input type="text" name="option3_<?= $i ?>" id="option3_<?= $i ?>" required>

                <label for="option4_<?= $i ?>">Option 4:</label>
                <input type="text" name="option4_<?= $i ?>" id="option4_<?= $i ?>" required>

                <label for="correct_answer_<?= $i ?>">Correct Answer:</label>
                <select name="correct_answer_<?= $i ?>" id="correct_answer_<?= $i ?>">
                    <option value="option1">Option 1</option>
                    <option value="option2">Option 2</option>
                    <option value="option3">Option 3</option>
                    <option value="option4">Option 4</option>
                </select>
            </div>
        <?php endfor; ?>
        <button type="submit"><i class="fas fa-paper-plane"></i> Submit Questions</button>
    </form>
</body>
</html>
