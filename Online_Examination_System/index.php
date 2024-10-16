<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Barsha Teaching Home</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start; /* Pushes title and buttons higher */
            height: 100vh;
            margin: 0;
            padding-top: 80px; /* Adds space from the top */
            background: linear-gradient(120deg, #6a11cb, #2575fc);
            color: white;
            overflow: hidden;
            text-align: center;
        }

        h1 {
            font-size: 3.5rem;
            margin-bottom: 50px; /* Increases space between title and buttons */
            opacity: 0;
            animation: typewriter 3s steps(30) forwards, fadeIn 1s forwards;
            white-space: nowrap;
            overflow: hidden;
            border-right: 2px solid white;
            width: 0;
            animation-delay: 0.5s;
        }

        @keyframes typewriter {
            from {
                width: 0;
            }
            to {
                width: 100%;
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        .button-container {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        button {
            background-color: #ffffff;
            color: #007bff;
            padding: 20px 40px; /* Increased button size */
            border: none;
            border-radius: 8px;
            font-size: 1.5rem; /* Larger text */
            cursor: pointer;
            margin: 15px; /* More space between buttons */
            transition: transform 0.3s, box-shadow 0.3s, background-color 0.3s;
            position: relative;
            overflow: hidden;
        }

        button::after {
            content: '';
            position: absolute;
            top: 0;
            left: 50%;
            width: 300%;
            height: 100%;
            background-color: rgba(255, 0, 0, 0.1);
            transform: translateX(-50%) translateY(-100%);
            transition: transform 0.5s ease;
            border-radius: 8px;
        }

        button:hover::after {
            transform: translateX(-50%) translateY(0);
        }

        button:hover {
            transform: scale(1.05);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        @media (max-width: 600px) {
            h1 {
                font-size: 2.5rem; /* Adjusted font size for smaller screens */
                white-space: normal; /* Allow wrapping for smaller screens */
            }
            button {
                font-size: 1.2rem; /* Adjusted button size for mobile */
                padding: 15px 30px; /* Adjusted padding for mobile */
            }
        }
    </style>
</head>
<body>
    <h1>Welcome to Barsha Teaching Home</h1>
    <div class="button-container">
        <a href="student_login.php" style="text-decoration: none;">
            <button>Student Login</button>
        </a>
        <a href="admin_login.php" style="text-decoration: none;">
            <button>Teacher Login</button>
        </a>
    </div>
</body>
</html>
