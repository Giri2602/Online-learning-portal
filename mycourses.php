<?php
session_start();
include('config.php');

$user_id = $_SESSION['user_id'];

$sql = "SELECT c.* FROM mycourses m 
        JOIN courses c ON m.course_id = c.id 
        WHERE m.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Courses</title>
</head>
<body>
    <h1>My Courses</h1>
    <ul>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <li>
                <?php echo $row['title']; ?> - $<?php echo $row['price']; ?>
            </li>
        <?php } ?>
    </ul>
</body>
</html>

<?php $stmt->close(); ?>
