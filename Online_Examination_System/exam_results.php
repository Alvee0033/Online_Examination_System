<?php
session_start();
if (!isset($_SESSION['student_logged_in'])) {
    header("Location: student_login.php");
    exit();
}

$db = new SQLite3('home/database.db');
$exam_id = isset($_GET['exam_id']) ? intval($_GET['exam_id']) : null;
$student_id = $_SESSION['student_id'];

$results = $db->prepare("SELECT * FROM results WHERE student_id = :student_id AND exam_id = :exam_id");
$results->bindValue(':student_id', $student_id, SQLITE3_INTEGER);
$results->bindValue(':exam_id', $exam_id, SQLITE3_INTEGER);
$result = $results->execute()->fetchArray();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam Results</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background-color: #f4f4f4;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <h1>Exam Results</h1>
    <p>Score: <?php echo $result['score']; ?> / <?php echo $result['total_questions']; ?></p>
    <a href="student_dashboard.php"><button>Back to Dashboard</button></a>
</body>
</html>
