<?php
include('config.php'); // Include database connection
session_start();

// Handle registration
if (isset($_POST['register'])) {
    $username = $_POST['name'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash password for security

    // Insert user data into the database
    $sql = "INSERT INTO users (username, email, phone_number, password) 
            VALUES ('$username', '$email', '$phone_number', '$password')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>
                alert('Registration successful! You can now log in.');
                document.getElementById('chk').checked = false;
              </script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }

    $conn->close(); // Close database connection
}

// Handle login
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check for admin login
    if ($email === 'admin@gmail.com' && $password === 'admin') {
        $_SESSION['user_id'] = 'admin';
        $_SESSION['username'] = 'Admin';
        echo "<script>
                alert('Admin login successful!');
                window.location.href = 'admin_dashboard.php';
              </script>";
        exit();
    }

    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            echo "<script>
                    alert('Login successful!');
                    window.location.href = 'home.php';
                  </script>";
        } else {
            echo "<script>alert('Invalid password');</script>";
        }
    } else {
        echo "<script>alert('No user found with this email');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Online Learning Portal</title>
    <link rel="stylesheet" type="text/css" href="slide-navbar-style.css">
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@500&display=swap" rel="stylesheet">
    <link href="registerstyle.css" rel="stylesheet">
</head>
<body>
    <div class="main">  
        <input type="checkbox" id="chk" aria-hidden="true">

        <!-- Success/Error Message -->
        <?php if (!empty($message)) echo $message; ?>

        <!-- Registration Form -->
        <div class="signup">
            <form action="register.php" method="POST">
                <label for="chk" aria-hidden="true">Sign up</label>
                <input type="text" name="name" placeholder="User name" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="text" name="phone" placeholder="Phone Number" required 
                 pattern="[0-9]{10}" maxlength="10" title="Phone number should be 10 digits">
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit" name="register">Sign up</button>
            </form>
        </div>

        <!-- Login Form -->
        <div class="login">
            <form action="register.php" method="POST">
                <label for="chk" aria-hidden="true">Login</label>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit" name="login">Login</button>
            </form>
        </div>
    </div>
</body>
</html>