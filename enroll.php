<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: register.php');
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "online_learning";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get user ID and course ID
$user_id = $_SESSION['user_id'];
$course_id = isset($_POST['course_id']) ? intval($_POST['course_id']) : 0;

$message = "";

if ($course_id > 0) {
    // Check if the user is already enrolled in the course
    $sql_check = "SELECT * FROM user_courses WHERE user_id = ? AND course_id = ?";
    $stmt = $conn->prepare($sql_check);
    $stmt->bind_param("ii", $user_id, $course_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $message = "You are already enrolled in this course.";
    } else {
        // Enroll the user in the course
        $sql_enroll = "INSERT INTO user_courses (user_id, course_id, progress) VALUES (?, ?, 0)";
        $stmt = $conn->prepare($sql_enroll);
        $stmt->bind_param("ii", $user_id, $course_id);

        if ($stmt->execute()) {
            $message = "Course enrolled successfully!";
        } else {
            $message = "Failed to enroll in course. Please try again.";
        }
    }
    $stmt->close();
} else {
    $message = "Invalid course ID.";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Enroll Course - Online Learning</title>
    <link rel="stylesheet" href="styles.css" />
    <style>
        :root {
            --cinnabar: #e94f37;
            --onyx: #393e41;
            --light-gray: #e0e0e0;
            --white: #ffffff;
        }

        body {
            font-family: 'Poppins', Arial, sans-serif;
            background-color: var(--light-gray);
            color: var(--onyx);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            background-color: var(--white);
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            width: 400px;
            text-align: center;
        }

        h1 {
            color: var(--cinnabar);
            font-size: 24px;
            margin-bottom: 20px;
        }

        p {
            font-size: 16px;
            color: var(--onyx);
            margin-bottom: 20px;
        }

        .btn {
            background-color: var(--cinnabar);
            color: var(--white);
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            text-decoration: none;
            display: inline-block;
            margin-top: 10px;
        }

        .btn:hover {
            background-color: #d63b2e;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Course Enrollment</h1>
    <p><?php echo $message; ?></p>
    <a href="my_courses.php" class="btn">Go to My Courses</a>
</div>

</body>
</html>
