<?php
include('config.php');

session_start(); // Start session to handle user authentication
$user_id = $_SESSION['user_id'] ?? 1; // Replace with actual user ID from session

// Fetch available courses
$sql = "SELECT * FROM courses WHERE is_active = 1";
$result = $conn->query($sql);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['course_id'])) {
    $course_id = intval($_POST['course_id']);

    // Check if user is already enrolled
    $check = $conn->prepare("SELECT * FROM user_courses WHERE user_id = ? AND course_id = ?");
    $check->bind_param("ii", $user_id, $course_id);
    $check->execute();
    $checkResult = $check->get_result();

    if ($checkResult->num_rows === 0) {
        // Enroll user
        $stmt = $conn->prepare("INSERT INTO user_courses (user_id, course_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $user_id, $course_id);

        if ($stmt->execute()) {
            // Redirect to "My Courses" page
            header("Location: my_courses.php");
            exit();
        } else {
            echo "<script>alert('Failed to enroll in the course. Please try again.');</script>";
        }

        $stmt->close();
    } else {
        echo "<script>alert('You are already enrolled in this course.');</script>";
    }

    $check->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Available Courses</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
        }

        .container {
            max-width: 1200px;
            margin: 20px;
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .course-card {
            background-color: #ffffff;
            border: 1px solid #e0e0e0;
            border-radius: 12px;
            padding: 20px;
            width: 300px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            transition: transform 0.2s;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .course-card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 15px;
        }

        .course-card h3 {
            font-size: 20px;
            color: #4CAF50;
            margin-bottom: 10px;
        }

        .course-card p {
            font-size: 14px;
            color: #666;
            margin-bottom: 10px;
            line-height: 1.6;
        }

        .course-card .info {
            font-size: 14px;
            color: #777;
            margin-bottom: 10px;
        }

        .course-card .rating {
            color: #ffc107;
            font-size: 16px;
            margin-bottom: 10px;
        }

        .course-card button {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            width: 100%;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            font-weight: bold;
            margin-top: 10px;
        }

        .course-card button:hover {
            background-color: #45a049;
        }

        @media (max-width: 768px) {
            .course-card {
                width: 100%;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <?php if ($result->num_rows > 0): ?>
        <?php while ($course = $result->fetch_assoc()): ?>
            <div class="course-card">
                <img src="<?= htmlspecialchars($course['image_path'] ?: 'default-course.jpg') ?>" alt="<?= htmlspecialchars($course['title']) ?>">
                <h3><?= htmlspecialchars($course['title']) ?></h3>
                <p><?= htmlspecialchars($course['description']) ?></p>
                <div class="info">
                    <span>Duration: <?= htmlspecialchars($course['duration'] ?: 'N/A') ?></span> | 
                    <span>Students: <?= intval($course['student_count']) ?></span>
                </div>
                <div class="rating">
                    Rating: <?= number_format($course['rating'], 1) ?> ⭐ (<?= intval($course['review_count']) ?> reviews)
                </div>
                <p><strong>Price:</strong> $<?= number_format($course['price'], 2) ?></p>
                <form method="POST" action="">
                    <input type="hidden" name="course_id" value="<?= intval($course['id']) ?>">
                    <button type="submit">Enroll Now</button>
                </form>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No courses available.</p>
    <?php endif; ?>
</div>

</body>
</html>

<?php $conn->close(); ?>
