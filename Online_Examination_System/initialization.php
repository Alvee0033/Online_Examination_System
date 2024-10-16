<?php
// Path where the SQLite database will be created
$databaseFile = 'home/database.db';

// Check if the database file already exists
if (!file_exists($databaseFile)) {
    // Create a new SQLite database
    $db = new SQLite3($databaseFile);

    // Create the 'students' table
    $db->exec("
        CREATE TABLE IF NOT EXISTS students (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            username TEXT UNIQUE,
            password TEXT
        )
    ");

    // Create the 'exams' table
    $db->exec("
        CREATE TABLE IF NOT EXISTS exams (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            title TEXT,
            points_per_question INTEGER,
            num_questions INTEGER
        )
    ");

    // Create the 'questions' table
    $db->exec("
        CREATE TABLE IF NOT EXISTS questions (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            exam_id INTEGER,
            question TEXT,
            option1 TEXT,
            option2 TEXT,
            option3 TEXT,
            option4 TEXT,
            correct_answer TEXT,
            FOREIGN KEY (exam_id) REFERENCES exams(id)
        )
    ");

    // Create the 'previous_exams' table to store completed exams
    $db->exec("
        CREATE TABLE IF NOT EXISTS previous_exams (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            student_id INTEGER,
            exam_id INTEGER,
            score INTEGER,
            total_questions INTEGER,
            FOREIGN KEY (student_id) REFERENCES students(id),
            FOREIGN KEY (exam_id) REFERENCES exams(id)
        )
    ");

    // Create the 'student_answers' table to store student responses
    $db->exec("
        CREATE TABLE IF NOT EXISTS student_answers (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            student_id INTEGER,
            exam_id INTEGER,
            question_id INTEGER,
            student_answer TEXT,
            FOREIGN KEY (student_id) REFERENCES students(id),
            FOREIGN KEY (exam_id) REFERENCES exams(id),
            FOREIGN KEY (question_id) REFERENCES questions(id)
        )
    ");

    // Insert a default admin user (Barsha) if it doesn't exist
    $defaultAdminUsername = 'Barsha';
    $defaultAdminPassword = password_hash('kutta', PASSWORD_DEFAULT); // Hashed password

    $insertAdminQuery = $db->prepare("
        INSERT OR IGNORE INTO students (username, password) VALUES (:username, :password)
    ");
    $insertAdminQuery->bindValue(':username', $defaultAdminUsername, SQLITE3_TEXT);
    $insertAdminQuery->bindValue(':password', $defaultAdminPassword, SQLITE3_TEXT);
    $insertAdminQuery->execute();

    // Check if 'time_limit' column exists, if not add it
    $result = $db->query("PRAGMA table_info(exams)");
    $hasTimeLimitColumn = false;

    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        if ($row['name'] === 'time_limit') {
            $hasTimeLimitColumn = true;
            break;
        }
    }

    // If 'time_limit' column doesn't exist, alter the table to add it
    if (!$hasTimeLimitColumn) {
        $db->exec("ALTER TABLE exams ADD COLUMN time_limit INTEGER");
    }

    echo "Database and tables created successfully.";
} else {
    echo "Database already exists.";
}
?>
<?php
// Path where the SQLite database will be created
$databaseFile = 'home/database.db';

// Check if the database file already exists
if (!file_exists($databaseFile)) {
    // Create a new SQLite database
    $db = new SQLite3($databaseFile);

    // Create the 'students' table
    $db->exec("
        CREATE TABLE IF NOT EXISTS students (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            username TEXT UNIQUE,
            password TEXT
        )
    ");

    // Create the 'exams' table
    $db->exec("
        CREATE TABLE IF NOT EXISTS exams (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            title TEXT,
            points_per_question INTEGER,
            num_questions INTEGER
        )
    ");

    // Create the 'questions' table
    $db->exec("
        CREATE TABLE IF NOT EXISTS questions (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            exam_id INTEGER,
            question TEXT,
            option1 TEXT,
            option2 TEXT,
            option3 TEXT,
            option4 TEXT,
            correct_answer TEXT,
            FOREIGN KEY (exam_id) REFERENCES exams(id)
        )
    ");

    // Create the 'previous_exams' table to store completed exams
    $db->exec("
        CREATE TABLE IF NOT EXISTS previous_exams (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            student_id INTEGER,
            exam_id INTEGER,
            score INTEGER,
            total_questions INTEGER,
            FOREIGN KEY (student_id) REFERENCES students(id),
            FOREIGN KEY (exam_id) REFERENCES exams(id)
        )
    ");

    // Create the 'student_answers' table to store student responses
    $db->exec("
        CREATE TABLE IF NOT EXISTS student_answers (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            student_id INTEGER,
            exam_id INTEGER,
            question_id INTEGER,
            student_answer TEXT,
            FOREIGN KEY (student_id) REFERENCES students(id),
            FOREIGN KEY (exam_id) REFERENCES exams(id),
            FOREIGN KEY (question_id) REFERENCES questions(id)
        )
    ");

    // Insert a default admin user (Barsha) if it doesn't exist
    $defaultAdminUsername = 'Barsha';
    $defaultAdminPassword = password_hash('kutta', PASSWORD_DEFAULT); // Hashed password

    $insertAdminQuery = $db->prepare("
        INSERT OR IGNORE INTO students (username, password) VALUES (:username, :password)
    ");
    $insertAdminQuery->bindValue(':username', $defaultAdminUsername, SQLITE3_TEXT);
    $insertAdminQuery->bindValue(':password', $defaultAdminPassword, SQLITE3_TEXT);
    $insertAdminQuery->execute();

    // Check if 'time_limit' column exists, if not add it
    $result = $db->query("PRAGMA table_info(exams)");
    $hasTimeLimitColumn = false;

    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        if ($row['name'] === 'time_limit') {
            $hasTimeLimitColumn = true;
            break;
        }
    }

    // If 'time_limit' column doesn't exist, alter the table to add it
    if (!$hasTimeLimitColumn) {
        $db->exec("ALTER TABLE exams ADD COLUMN time_limit INTEGER");
    }

    echo "Database and tables created successfully.";
} else {
    echo "Database already exists.";
}
?>
<?php
// Path where the SQLite database will be created
$databaseFile = 'home/database.db';

// Check if the database file already exists
if (!file_exists($databaseFile)) {
    // Create a new SQLite database
    $db = new SQLite3($databaseFile);

    // Create the 'students' table
    $db->exec("
        CREATE TABLE IF NOT EXISTS students (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            username TEXT UNIQUE,
            password TEXT
        )
    ");

    // Create the 'exams' table
    $db->exec("
        CREATE TABLE IF NOT EXISTS exams (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            title TEXT,
            points_per_question INTEGER,
            num_questions INTEGER
        )
    ");

    // Create the 'questions' table
    $db->exec("
        CREATE TABLE IF NOT EXISTS questions (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            exam_id INTEGER,
            question TEXT,
            option1 TEXT,
            option2 TEXT,
            option3 TEXT,
            option4 TEXT,
            correct_answer TEXT,
            FOREIGN KEY (exam_id) REFERENCES exams(id)
        )
    ");

    // Create the 'previous_exams' table to store completed exams
    $db->exec("
        CREATE TABLE IF NOT EXISTS previous_exams (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            student_id INTEGER,
            exam_id INTEGER,
            score INTEGER,
            total_questions INTEGER,
            FOREIGN KEY (student_id) REFERENCES students(id),
            FOREIGN KEY (exam_id) REFERENCES exams(id)
        )
    ");

    // Create the 'student_answers' table to store student responses
    $db->exec("
        CREATE TABLE IF NOT EXISTS student_answers (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            student_id INTEGER,
            exam_id INTEGER,
            question_id INTEGER,
            student_answer TEXT,
            FOREIGN KEY (student_id) REFERENCES students(id),
            FOREIGN KEY (exam_id) REFERENCES exams(id),
            FOREIGN KEY (question_id) REFERENCES questions(id)
        )
    ");

    // Insert a default admin user (Barsha) if it doesn't exist
    $defaultAdminUsername = 'Barsha';
    $defaultAdminPassword = password_hash('kutta', PASSWORD_DEFAULT); // Hashed password

    $insertAdminQuery = $db->prepare("
        INSERT OR IGNORE INTO students (username, password) VALUES (:username, :password)
    ");
    $insertAdminQuery->bindValue(':username', $defaultAdminUsername, SQLITE3_TEXT);
    $insertAdminQuery->bindValue(':password', $defaultAdminPassword, SQLITE3_TEXT);
    $insertAdminQuery->execute();

    // Check if 'time_limit' column exists, if not add it
    $result = $db->query("PRAGMA table_info(exams)");
    $hasTimeLimitColumn = false;

    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        if ($row['name'] === 'time_limit') {
            $hasTimeLimitColumn = true;
            break;
        }
    }

    // If 'time_limit' column doesn't exist, alter the table to add it
    if (!$hasTimeLimitColumn) {
        $db->exec("ALTER TABLE exams ADD COLUMN time_limit INTEGER");
    }

    echo "Database and tables created successfully.";
} else {
    echo "Database already exists.";
}
?>
