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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $duration = $_POST['duration'];
    $price = $_POST['price'];

    // Image upload
    $targetDir = "../uploads/courses/";
    $fileName = basename($_FILES["image"]["name"]);
    $targetFilePath = $targetDir . $fileName;

    if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath)) {
        $sql = "INSERT INTO courses (title, description, image_path, duration, price) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssd", $title, $description, $fileName, $duration, $price);
        $stmt->execute();
        $stmt->close();
        echo "<script>alert('Course added successfully');</script>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Add Course</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
<div class="container">
    <form action="add_course.php" method="POST" enctype="multipart/form-data">
        <h1>Add Course</h1>
        <input type="text" name="title" placeholder="Course Title" required />
        <textarea name="description" placeholder="Course Description"></textarea>
        <input type="file" name="image" required />
        <input type="text" name="duration" placeholder="Duration (e.g., 10 hours)" required />
        <input type="number" step="0.01" name="price" placeholder="Price" required />
        <button type="submit">Add Course</button>
    </form>
</div>
</body>
</html>
