<?php
session_start();
include('config.php');

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'teacher') {
    header("Location: login.php");
    exit();
}

$id = $_GET['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];

    $sql = "UPDATE courses SET title = '$title', description = '$description' WHERE id = $id";
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Course updated successfully!'); window.location='dashboard.php';</script>";
    } else {
        echo "Error: " . $conn->error;
    }
}

$sql = "SELECT * FROM courses WHERE id = $id";
$result = $conn->query($sql);
$course = $result->fetch_assoc();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Course</title>
</head>
<body>
    <h2>Edit Course</h2>
    <form method="POST" action="">
        <input type="text" name="title" value="<?php echo $course['title']; ?>" required><br>
        <textarea name="description" required><?php echo $course['description']; ?></textarea><br>
        <button type="submit">Update Course</button>
    </form>
</body>
</html>
