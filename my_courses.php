<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: register.php');
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "online_learning";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];

// Fetch enrolled courses for the user
$sql = "SELECT c.id, c.title, c.description, c.image_path, c.duration, c.rating, c.review_count, c.price, uc.progress 
        FROM user_courses uc
        JOIN courses c ON uc.course_id = c.id
        WHERE uc.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$courses = [];
while ($row = $result->fetch_assoc()) {
    $courses[] = $row;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>My Courses - Online Learning</title>
    <link rel="stylesheet" href="styles.css" />
    <style>
        :root {
            --cinnabar: #e94f37;
            --onyx: #393e41;
            --light-gray: #f4f4f4;
            --white: #ffffff;
            --green: #4caf50;
            --gold: #fbc02d;
            --shadow-color: rgba(0, 0, 0, 0.1);
        }

        body {
            font-family: 'Poppins', Arial, sans-serif;
            background-color: var(--light-gray);
            color: var(--onyx);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
        }

        .container {
            background-color: var(--white);
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 8px 24px var(--shadow-color);
            width: 90%;
            max-width: 1200px;
            margin-top: 30px;
            transition: transform 0.3s ease;
        }

        h1 {
            color: var(--cinnabar);
            font-size: 32px;
            margin-bottom: 24px;
            text-align: center;
            font-weight: 700;
            border-bottom: 2px solid var(--cinnabar);
            padding-bottom: 8px;
            display: inline-block;
        }

        .course-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 24px;
        }

        .course-card {
            background-color: var(--white);
            border: 1px solid var(--light-gray);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 6px 12px var(--shadow-color);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
            cursor: pointer;
        }

        .course-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 24px var(--shadow-color);
        }

        .course-card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-bottom: 4px solid var(--cinnabar);
        }

        .course-content {
            padding: 20px;
        }

        .course-title {
            font-size: 22px;
            color: var(--cinnabar);
            font-weight: 700;
            margin-bottom: 12px;
            text-transform: capitalize;
        }

        .course-description {
            font-size: 16px;
            color: var(--onyx);
            margin-bottom: 16px;
            line-height: 1.5;
            max-height: 48px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .course-meta {
            font-size: 14px;
            color: var(--onyx);
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .rating {
            color: var(--gold);
            font-size: 16px;
            font-weight: 600;
        }

        .progress-bar {
            width: 100%;
            background-color: var(--light-gray);
            border-radius: 8px;
            overflow: hidden;
            height: 12px;
            margin-bottom: 16px;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .progress-bar-inner {
            height: 100%;
            background: linear-gradient(90deg, var(--green) 0%, #66bb6a 100%);
            width: 0%;
            transition: width 0.4s ease;
        }

        .btn {
            background-color: var(--cinnabar);
            color: var(--white);
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            text-decoration: none;
            font-weight: 600;
            display: inline-block;
            text-align: center;
            margin-top: 10px;
            width: 100%;
            box-sizing: border-box;
        }

        .btn:hover {
            background-color: #d63b2e;
        }

        .empty-message {
            text-align: center;
            font-size: 18px;
            color: var(--onyx);
            margin-top: 20px;
            font-weight: 500;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>My Courses</h1>

    <?php if (count($courses) > 0): ?>
        <div class="course-list">
            <?php foreach ($courses as $course): ?>
                <div class="course-card">
                    <img src="uploads/courses/<?php echo htmlspecialchars($course['image_path']); ?>" alt="Course Image" />
                    <div class="course-content">
                        <div class="course-title"><?php echo htmlspecialchars($course['title']); ?></div>
                        <div class="course-description"><?php echo htmlspecialchars($course['description']); ?></div>
                        <div class="course-meta">
                            <span>Duration: <?php echo htmlspecialchars($course['duration']); ?></span>
                            <span class="rating">⭐ <?php echo htmlspecialchars($course['rating']); ?> (<?php echo htmlspecialchars($course['review_count']); ?>)</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-bar-inner" style="width: <?php echo $course['progress']; ?>%;"></div>
                        </div>
                        <div class="course-meta">
                            Price: ₹<?php echo htmlspecialchars(number_format($course['price'], 2)); ?>
                        </div>
                        <a href="course_details.php?id=<?php echo $course['id']; ?>" class="btn">View Details</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p class="empty-message">You haven't enrolled in any courses yet!</p>
    <?php endif; ?>
</div>

</body>
</html>
