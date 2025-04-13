<?php
session_start();
// Check if admin is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] !== 'admin') {
    header("Location: register.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
        }
        .header {
            background-color: #333;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .container {
            width: 80%;
            margin: auto;
            overflow: hidden;
            padding: 20px;
        }
        .dashboard-menu {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .dashboard-menu a {
            display: block;
            padding: 15px;
            background: #333;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background 0.3s;
        }
        .dashboard-menu a:hover {
            background: #555;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Admin Dashboard</h1>
        <p>Welcome, <?php echo $_SESSION['username']; ?>!</p>
        <a href="logout.php" style="color: white;">Logout</a>
    </div>
    
    <div class="container">
        <div class="dashboard-menu">
            <a href="manage_users.php">Manage Users</a>
            <a href="manage_courses.php">Manage Courses</a>
            <a href="manage_teachers.php">Manage Teachers</a>
        </div>
    </div>
</body>
</html>