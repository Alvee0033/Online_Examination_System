<?php  
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}

// Get the username from the session
$username = 'Barsha'; // Set the username directly to "Barsha"
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            padding: 20px;
            background: linear-gradient(to right, #eaeaea, #ffffff); /* Light gradient background */
            animation: fadeIn 0.5s;
            color: #333; /* Dark text for better contrast */
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
            font-size: 2.5em; /* Increase title size */
            overflow: hidden; /* Ensures the text does not overflow */
            white-space: nowrap; /* Prevent text wrapping */
            border-right: 3px solid #007bff; /* Cursor effect */
            animation: typing 3s steps(30, end), blink-caret 0.75s step-end infinite;
        }

        @keyframes typing {
            from { width: 0; }
            to { width: 100%; }
        }

        @keyframes blink-caret {
            from, to { border-color: transparent; }
            50% { border-color: #007bff; }
        }

        .container {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            opacity: 0; /* Initial hidden state for animation */
            animation: spread 0.5s forwards 0.5s; /* Delay for spreading animation */
        }

        @keyframes spread {
            from {
                transform: scale(0);
                opacity: 0;
            }
            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        .button {
            background-color: #4CAF50; /* Bright green background */
            color: white;
            padding: 15px 25px;
            margin: 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            text-decoration: none;
            transition: background-color 0.3s, transform 0.2s;
            display: inline-block;
            box-shadow: 0 0 5px #4CAF50, 0 0 10px #4CAF50, 0 0 15px #4CAF50; /* Neon glow */
            opacity: 0; /* Initial hidden state for button animation */
            animation: fadeInButton 0.5s forwards; /* Fade-in animation */
        }

        @keyframes fadeInButton {
            to {
                opacity: 1; /* Make buttons visible */
            }
        }

        .button:hover {
            background-color: #45a049; /* Darker green on hover */
            transform: translateY(-2px);
            box-shadow: 0 0 10px #45a049, 0 0 20px #45a049, 0 0 30px #45a049; /* Enhanced neon glow on hover */
        }

        .button:active {
            transform: translateY(0);
        }

        .icon {
            margin-right: 8px;
        }

        @media (max-width: 600px) {
            .button {
                width: 100%;
                padding: 12px 20px;
            }
            h1 {
                font-size: 2em; /* Adjust title size for smaller screens */
            }
        }
    </style>
</head>
<body>
    <h1>Welcome, Barsha</h1> <!-- Changed to Welcome, Barsha -->
    <div class="container">
        <a href="create_exam.php" class="button"><i class="fas fa-plus-circle icon"></i>Create Exam</a>
        <a href="manage_students.php" class="button"><i class="fas fa-users icon"></i>Manage Students</a>
        <a href="previous_exams.php" class="button"><i class="fas fa-history icon"></i>View Previous Exams</a>
        <a href="logout.php" class="button"><i class="fas fa-sign-out-alt icon"></i>Logout</a>
    </div>
</body>
</html>
