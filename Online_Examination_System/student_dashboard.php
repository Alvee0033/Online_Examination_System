<?php
session_start();
if (!isset($_SESSION['student_logged_in'])) {
    header("Location: student_login.php");
    exit();
}

$db = new SQLite3('home/database.db');
$student_id = $_SESSION['student_id'];

// Fetch available exams (exams the student hasn't taken yet)
$available_exams = $db->query("
    SELECT * FROM exams 
    WHERE id NOT IN (
        SELECT exam_id FROM previous_exams WHERE student_id = $student_id
    )
");

// Fetch previous exams (exams the student has already taken)
$previous_exams = $db->query("
    SELECT exams.id, exams.title, previous_exams.score, previous_exams.total_questions 
    FROM previous_exams 
    JOIN exams ON previous_exams.exam_id = exams.id 
    WHERE previous_exams.student_id = $student_id
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Responsive meta tag -->
    <title>Student Dashboard</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            color: #333;
            padding: 20px;
            margin: 0;
            overflow-x: hidden; /* Prevent horizontal overflow */
            display: flex; /* Flexbox for centering content */
            flex-direction: column;
            align-items: center; /* Center all items */
        }

        h1 {
            color: #007bff;
            animation: typing 2s steps(20) forwards;
            white-space: nowrap;
            overflow: hidden;
            width: 0; /* Width of the title for the typing effect */
            font-size: 2.5rem; /* Larger font size for better visibility */
            text-align: center; /* Center text */
            margin-bottom: 30px; /* Space below title */
        }

        @keyframes typing {
            from { width: 0; }
            to { width: 100%; }
        }

        h2 {
            color: #007bff;
            margin-top: 30px;
            margin-bottom: 15px;
            font-size: 1.5rem; /* Increased font size for subheadings */
            text-align: center; /* Center text */
        }

        .exam-section {
            background-color: #fff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            width: 100%; /* Full width on mobile */
            max-width: 500px; /* Limit max width for larger screens */
            transition: transform 0.3s;
        }

        .exam-section:hover {
            transform: scale(1.02);
        }

        .exam-item {
            margin-bottom: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            border: 1px solid #eaeaea;
            border-radius: 5px;
            background-color: #f9f9f9;
            transition: background-color 0.3s;
        }

        .exam-item:hover {
            background-color: #e9ecef;
        }

        .exam-item button {
            padding: 10px 15px; /* Larger button padding */
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            font-size: 1rem; /* Larger font size for buttons */
        }

        .exam-item button:hover {
            background-color: #218838;
        }

        .score {
            font-weight: bold;
            margin-left: 10px;
            color: #333;
        }

        @media (max-width: 600px) {
            h1 {
                font-size: 2rem; /* Adjusted font size for smaller screens */
                white-space: normal; /* Allow wrapping for smaller screens */
            }

            h2 {
                font-size: 1.25rem; /* Adjusted font size for smaller screens */
            }

            .exam-section {
                padding: 15px;
                margin: 0 10px; /* Add margin for mobile spacing */
            }

            .exam-item {
                flex-direction: column;
                align-items: flex-start;
                padding: 10px; /* Reduce padding for mobile */
            }

            .exam-item button {
                margin-top: 10px;
                width: 100%; /* Full width for buttons on mobile */
                font-size: 1rem; /* Consistent font size */
            }
        }
    </style>
</head>
<body>
    <h1>Welcome to Your Dashboard</h1>

    <div class="exam-section">
        <h2>Available Exams</h2>
        <?php if ($available_exams->fetchArray()): ?>
            <?php
            // Reset pointer to the beginning
            $available_exams->reset();
            while ($exam = $available_exams->fetchArray(SQLITE3_ASSOC)):
            ?>
                <div class="exam-item">
                    <span><?php echo htmlspecialchars($exam['title']); ?></span>
                    <a href="take_exam.php?exam_id=<?php echo $exam['id']; ?>">
                        <button>Take Exam</button>
                    </a>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No available exams at the moment.</p>
        <?php endif; ?>
    </div>

    <div class="exam-section">
        <h2>Previous Exams</h2>
        <?php if ($previous_exams->fetchArray()): ?>
            <?php
            // Reset pointer to the beginning
            $previous_exams->reset();
            while ($exam = $previous_exams->fetchArray(SQLITE3_ASSOC)):
            ?>
                <div class="exam-item">
                    <span><?php echo htmlspecialchars($exam['title']); ?></span>
                    <span class="score">Score: <?php echo $exam['score']; ?>/<?php echo $exam['total_questions']; ?></span>
                    <a href="view_results.php?exam_id=<?php echo $exam['id']; ?>">
                        <button>View Results</button>
                    </a>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>You haven't completed any exams yet.</p>
        <?php endif; ?>
    </div>
</body>
</html>
