<?php
session_start();
$conn = new mysqli("localhost", "root", "", "online_learning");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['course_id']) && isset($_SESSION['user_id'])) {
        $course_id = intval($_POST['course_id']);
        $user_id = intval($_SESSION['user_id']); // Get logged-in user ID

        if ($course_id > 0) {
            // Check if course already added to mycourses by this user
            $checkQuery = "SELECT * FROM mycourses WHERE user_id = ? AND title = (SELECT title FROM courses WHERE id = ?)";
            $stmt = $conn->prepare($checkQuery);
            $stmt->bind_param("ii", $user_id, $course_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                echo "Course already added to My Courses!";
                exit;
            }

            // Get course details
            $query = "SELECT * FROM courses WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $course_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $course = $result->fetch_assoc();

                // Insert into mycourses
                $insert = "INSERT INTO mycourses (title, description, teacher_id, user_id) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($insert);
                $stmt->bind_param(
                    "ssii", 
                    $course['title'], 
                    $course['description'], 
                    $course['teacher_id'], 
                    $user_id
                );

                if ($stmt->execute()) {
                    echo "Course added to My Courses!";
                } else {
                    echo "Error adding course: " . $stmt->error;
                }
            } else {
                echo "Invalid course ID!";
            }
        } else {
            echo "Invalid course ID value!";
        }
    } else {
        echo "User not logged in or course ID not provided!";
    }
} else {
    echo "Invalid request method!";
}

$conn->close();
?>