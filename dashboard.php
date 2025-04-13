<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "online_learning";

$conn = new mysqli($servername, $username, $password, $dbname);

$totalCourses = $conn->query("SELECT COUNT(*) AS count FROM courses")->fetch_assoc()['count'];
$totalTeachers = $conn->query("SELECT COUNT(*) AS count FROM teachers")->fetch_assoc()['count'];
$totalUsers = $conn->query("SELECT COUNT(*) AS count FROM users")->fetch_assoc()['count'];

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
<div class="container">
    <h1>Admin Dashboard</h1>
    <div class="stats">
        <div class="stat-item">
            <h2>Total Courses</h2>
            <p><?php echo $totalCourses; ?></p>
        </div>
        <div class="stat-item">
            <h2>Total Teachers</h2>
            <p><?php echo $totalTeachers; ?></p>
        </div>
        <div class="stat-item">
            <h2>Total Users</h2>
            <p><?php echo $totalUsers; ?></p>
        </div>
    </div>

    <a href="add_course.php" class="btn">Add Course</a>
    <a href="manage_courses.php" class="btn">Manage Courses</a>
    <a href="add_teacher.php" class="btn">Add Teacher</a>
    <a href="manage_teachers.php" class="btn">Manage Teachers</a>
    <a href="manage_users.php" class="btn">Manage Users</a>
</div>
</body>
</html>
