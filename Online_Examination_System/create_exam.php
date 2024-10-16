<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}

$db = new SQLite3('home/database.db'); // Ensure correct path to SQLite database

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $exam_title = $_POST['exam_title'];
    $num_questions = intval($_POST['num_questions']);
    $time_limit = intval($_POST['time_limit']); // Get time limit from POST data

    $stmt = $db->prepare("INSERT INTO exams (title, num_questions, time_limit) VALUES (:title, :num_questions, :time_limit)");
    $stmt->bindValue(':title', $exam_title, SQLITE3_TEXT);
    $stmt->bindValue(':num_questions', $num_questions, SQLITE3_INTEGER);
    $stmt->bindValue(':time_limit', $time_limit, SQLITE3_INTEGER); // Bind the time limit
    $result = $stmt->execute();

    if ($result) {
        $exam_id = $db->lastInsertRowID();
        header("Location: enter_questions.php?exam_id=$exam_id&num_questions=$num_questions");
        exit();
    } else {
        echo "Error creating exam: " . $db->lastErrorMsg();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Exam</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f0f2f5;
            padding: 20px;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            animation: fadeIn 0.5s;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .container {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            padding: 30px;
            width: 100%;
            max-width: 400px;
            text-align: center;
            transition: transform 0.2s;
        }

        .container:hover {
            transform: scale(1.02);
        }

        h1 {
            margin-bottom: 20px;
            color: #333;
            font-size: 24px;
        }

        input, label, button {
            display: block;
            margin-bottom: 15px;
            width: 100%;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 10px;
            font-size: 16px;
            transition: border-color 0.3s;
        }

        input:focus {
            border-color: #28a745;
            outline: none;
        }

        button {
            background-color: #28a745;
            color: white;
            padding: 10px;
            border: none;
            cursor: pointer;
            font-size: 16px;
            border-radius: 4px;
            transition: background-color 0.3s, transform 0.2s;
        }

        button:hover {
            background-color: #218838;
            transform: translateY(-2px);
        }

        button:active {
            transform: translateY(0);
        }

        label {
            text-align: left;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .footer {
            margin-top: 20px;
            font-size: 14px;
            color: #888;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Create Exam</h1>
        <form method="POST">
            <label for="exam_title">Exam Title:</label>
            <input type="text" id="exam_title" name="exam_title" required>
            
            <label for="num_questions">Number of Questions:</label>
            <input type="number" id="num_questions" name="num_questions" required min="1" value="1">
            
            <label for="time_limit">Time Limit (in minutes):</label>
            <input type="number" id="time_limit" name="time_limit" required min="1" value="1">
            
            <button type="submit"><i class="fas fa-plus"></i> Create Exam</button>
        </form>
        <div class="footer">© 2024 Barsha Coaching</div>
    </div>
</body>
</html>
