<?php
$courses = [
    [
        'title' => 'Learn Guitar The Easy Way',
        'description' => 'This course is your "Free Pass" to playing guitar. It is the most direct and to-the-point complete online guitar course.',
        'old_price' => 1000,
        'new_price' => 1655,
        'image' => 'images/guitar.jpg'
    ],
    [
        'title' => 'Complete PHP Bootcamp',
        'description' => 'This course will help you get all the Object-Oriented PHP, MySQLi, and ending the course by building a CMS system.',
        'old_price' => 1700,
        'new_price' => 700,
        'image' => 'images/php.jpg'
    ],
    [
        'title' => 'Learn Python A-Z',
        'description' => 'This is the most comprehensive, yet straightforward course for the Python programming language.',
        'old_price' => 1000,
        'new_price' => 800,
        'image' => 'images/python.jpg'
    ]
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Courses - Online Learning Platform</title>
    <link rel="stylesheet" href="styles.css" />
    <style>
        /* General Styling */
body {
    font-family: Arial, sans-serif;
    background-color: #f9f9f9;
    margin: 0;
    padding: 0;
}

/* Navbar */
nav {
    background-color: #1e90ff;
    padding: 15px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

nav .logo {
    font-size: 24px;
    color: #fff;
    font-weight: bold;
}

nav ul {
    list-style-type: none;
    display: flex;
    gap: 20px;
}

nav ul li {
    display: inline;
}

nav ul li a {
    color: #fff;
    text-decoration: none;
    font-size: 16px;
    transition: color 0.3s;
}

nav ul li a:hover,
nav ul li a.active {
    color: #ddd;
}

/* Hero Section */
.hero {
    background-color: #1e90ff;
    color: #fff;
    text-align: center;
    padding: 100px 20px;
}

.hero h1 {
    font-size: 36px;
    margin-bottom: 20px;
}

.hero p {
    font-size: 18px;
    margin-bottom: 30px;
}

.btn {
    background-color: #fff;
    color: #1e90ff;
    padding: 12px 24px;
    border-radius: 5px;
    text-decoration: none;
    font-weight: bold;
}

.btn:hover {
    background-color: #0077cc;
    color: #fff;
}

/* Course Section */
.popular-courses {
    padding: 50px;
    text-align: center;
}

.section-title {
    font-size: 28px;
    color: #333;
    margin-bottom: 30px;
}

.course-row {
    display: flex;
    gap: 20px;
    justify-content: center;
}

.course-card {
    background-color: #ffffff;
    border: 1px solid #ddd;
    border-radius: 10px;
    overflow: hidden;
    width: 300px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

.course-card img {
    width: 100%;
    height: 180px;
    object-fit: cover;
}

.course-card h3 {
    font-size: 20px;
    margin: 10px 15px;
    color: #1e90ff;
}

.course-card p {
    font-size: 14px;
    color: #555;
    margin: 0 15px 15px;
}

.price {
    font-size: 16px;
    margin: 0 15px;
}

.old-price {
    text-decoration: line-through;
    color: #888;
}

.new-price {
    color: #27ae60;
    font-weight: bold;
}

.enroll-btn {
    background-color: #1e90ff;
    color: #fff;
    border: none;
    padding: 10px;
    width: 100%;
    cursor: pointer;
    font-size: 16px;
    transition: background-color 0.3s ease;
}

.enroll-btn:hover {
    background-color: #0077cc;
}

.course-card:hover {
    transform: translateY(-5px);
}
    </style>
</head>
<body>

<nav>
    <div class="logo">Online Learning</div>
    <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="courses.php" class="active">Courses</a></li>
        <li><a href="#contact">Contact</a></li>
    </ul>
</nav>

<div class="popular-courses">
    <h2 class="section-title">Popular Courses</h2>
    <div class="course-row">
        <?php foreach ($courses as $course): ?>
            <div class="course-card">
                <img src="<?= $course['image'] ?>" alt="<?= $course['title'] ?>">
                <h3><?= $course['title'] ?></h3>
                <p><?= $course['description'] ?></p>
                <div class="price">
                    Price: <span class="old-price">₹<?= $course['old_price'] ?></span> 
                    <span class="new-price">₹<?= $course['new_price'] ?></span>
                </div>
                <button class="enroll-btn">Enroll</button>
            </div>
        <?php endforeach; ?>
    </div>
</div>

</body>
</html>
