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
    $name = $_POST['name'];
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $bio = $_POST['bio'];

    // Upload image
    $targetDir = "../uploads/teachers/";
    $fileName = basename($_FILES["image"]["name"]);
    $targetFilePath = $targetDir . $fileName;

    if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath)) {
        $sql = "INSERT INTO teachers (name, email, subject, bio, image_path) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $name, $email, $subject, $bio, $fileName);
        $stmt->execute();
        $stmt->close();
        echo "<script>alert('Teacher added successfully');</script>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Add Teacher</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
<div class="container">
    <form action="add_teacher.php" method="POST" enctype="multipart/form-data">
        <h1>Add Teacher</h1>
        <input type="text" name="name" placeholder="Name" required />
        <input type="email" name="email" placeholder="Email" required />
        <input type="text" name="subject" placeholder="Subject" required />
        <textarea name="bio" placeholder="Bio"></textarea>
        <input type="file" name="image" required />
        <button type="submit">Add Teacher</button>
    </form>
</div>
</body>
</html>
