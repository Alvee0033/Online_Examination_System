<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}

$db = new SQLite3('home/database.db');

// Fetch all students
$students = $db->query("SELECT DISTINCT id, username FROM students");

// Fetch previous exams grouped by student
$previous_exams = $db->query("
    SELECT exams.id AS exam_id, exams.title, previous_exams.score, previous_exams.total_questions, students.username 
    FROM previous_exams 
    JOIN exams ON previous_exams.exam_id = exams.id 
    JOIN students ON previous_exams.student_id = students.id
    ORDER BY students.username
");

$exams_by_student = [];
while ($exam = $previous_exams->fetchArray(SQLITE3_ASSOC)) {
    $exams_by_student[$exam['username']][] = $exam;
}

// Get selected student username
$selected_username = isset($_GET['username']) ? $_GET['username'] : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Previous Exams</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
            margin: 0;
        }
        h1 {
            color: #007bff;
            text-align: center;
            margin-bottom: 30px;
        }
        .container {
            max-width: 800px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .form-group {
            margin-bottom: 20px;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }
        label {
            font-weight: bold;
            margin-bottom: 5px;
            color: #555;
        }
        select {
            padding: 10px;
            font-size: 16px;
            border-radius: 5px;
            border: 1px solid #ccc;
            width: 100%;
            transition: border-color 0.3s;
        }
        select:focus {
            border-color: #007bff;
            outline: none;
        }
        .exam-section {
            margin-top: 20px;
        }
        .exam-item {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 15px;
            box-shadow: 0 1px 5px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .exam-item:hover {
            transform: scale(1.02);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        }
        .score {
            font-weight: bold;
            color: #333;
        }
        .exam-item button {
            padding: 8px 12px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-left: 10px;
        }
        .exam-item button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Previous Exams</h1>
        
        <form method="GET" action="">
            <div class="form-group">
                <label for="students">Select Student:</label>
                <select name="username" id="students" onchange="this.form.submit()">
                    <option value="">-- All Students --</option>
                    <?php while ($student = $students->fetchArray(SQLITE3_ASSOC)): ?>
                        <option value="<?php echo htmlspecialchars($student['username']); ?>" <?php echo ($selected_username == $student['username']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($student['username']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
        </form>

        <div class="exam-section">
            <?php if (!empty($exams_by_student)): ?>
                <?php if ($selected_username && isset($exams_by_student[$selected_username])): ?>
                    <?php foreach ($exams_by_student[$selected_username] as $exam): ?>
                        <div class="exam-item">
                            <span><?php echo htmlspecialchars($exam['title']); ?></span>
                            <span class="score">Score: <?php echo $exam['score']; ?>/<?php echo $exam['total_questions']; ?></span>
                            <a href="view_results.php?exam_id=<?php echo $exam['exam_id']; ?>">
                                <button>View Results</button>
                            </a>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Select a student to view their previous exams.</p>
                <?php endif; ?>
            <?php else: ?>
                <p>No exams have been completed yet.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
