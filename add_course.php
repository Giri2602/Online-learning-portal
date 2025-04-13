<?php
include('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = floatval($_POST['price']);

    // Handle video upload
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["video"]["name"]);
    $uploadOk = 1;
    $videoFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    $allowedTypes = ['mp4', 'avi', 'mov'];
    if (!in_array($videoFileType, $allowedTypes)) {
        echo "<script>alert('Sorry, only MP4, AVI, and MOV files are allowed.');</script>";
        $uploadOk = 0;
    }

    if ($_FILES["video"]["size"] > 500 * 1024 * 1024) {
        echo "<script>alert('Sorry, your file is too large. Max size is 500MB.');</script>";
        $uploadOk = 0;
    }

    if ($uploadOk) {
        if (move_uploaded_file($_FILES["video"]["tmp_name"], $target_file)) {
            $stmt = $conn->prepare("INSERT INTO courses (title, description, price, video_path) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssds", $title, $description, $price, $target_file);

            if ($stmt->execute()) {
                echo "<script>alert('Course added successfully!');</script>";
            } else {
                echo "<script>alert('Failed to add course: " . $conn->error . "');</script>";
            }

            $stmt->close();
        } else {
            echo "<script>alert('Sorry, there was an error uploading your file.');</script>";
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Course</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f9;
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            width: 400px;
            max-width: 90%;
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        h2 {
            color: #4CAF50;
            text-align: center;
            margin-bottom: 20px;
            font-size: 24px;
        }

        label {
            font-weight: bold;
            display: block;
            margin-bottom: 6px;
            color: #555;
        }

        input[type="text"],
        input[type="number"],
        textarea,
        input[type="file"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-sizing: border-box;
            font-size: 16px;
            transition: border-color 0.3s;
            background-color: #f9f9f9;
        }

        input[type="text"]:focus,
        input[type="number"]:focus,
        textarea:focus,
        input[type="file"]:focus {
            border-color: #4CAF50;
            outline: none;
            background-color: #ffffff;
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 14px;
            width: 100%;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            font-weight: bold;
        }

        button:hover {
            background-color: #45a049;
        }

        .success-message,
        .error-message {
            margin-top: 10px;
            padding: 10px;
            border-radius: 8px;
            font-size: 14px;
            text-align: center;
        }

        .success-message {
            background-color: #e7f3e7;
            color: #4CAF50;
            border: 1px solid #4CAF50;
        }

        .error-message {
            background-color: #fdecea;
            color: #f44336;
            border: 1px solid #f44336;
        }

        /* Responsive Design */
        @media (max-width: 480px) {
            .container {
                padding: 20px;
            }

            h2 {
                font-size: 20px;
            }

            input,
            button {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <h2><i class="fa fa-plus-circle"></i> Add New Course</h2>
    <form method="POST" action="add_course.php" enctype="multipart/form-data">
        <label for="title"><i class="fa fa-book"></i> Title:</label>
        <input type="text" name="title" placeholder="Enter course title" required>

        <label for="description"><i class="fa fa-info-circle"></i> Description:</label>
        <textarea name="description" placeholder="Enter course description" rows="4" required></textarea>

        <label for="price"><i class="fa fa-dollar-sign"></i> Price ($):</label>
        <input type="number" name="price" placeholder="Enter course price" step="0.01" required>

        <label for="video"><i class="fa fa-video"></i> Upload Video:</label>
        <input type="file" name="video" accept="video/*" required>

        <button type="submit"><i class="fa fa-upload"></i> Add Course</button>
    </form>
</div>

</body>
</html>
