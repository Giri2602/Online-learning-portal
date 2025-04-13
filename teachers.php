<?php
session_start();
include('config.php');

// Get all teachers
$teachers = [];
$sql = "SELECT * FROM teachers ORDER BY name ASC";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $teachers[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Teachers - Online Learning Portal</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root {
            --primary-color: #6c63ff;
            --secondary-color: #4a42e8;
            --light-color: #f8f9fa;
            --dark-color: #343a40;
            --success-color: #28a745;
            --danger-color: #dc3545;
            --warning-color: #ffc107;
            --info-color: #17a2b8;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }
        
        body {
            background-color: #f5f7fb;
            color: #333;
            line-height: 1.6;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            padding: 40px 0;
        }
        
        .header h1 {
            color: var(--primary-color);
            font-weight: 600;
            font-size: 2.5rem;
            margin-bottom: 15px;
        }
        
        .header p {
            color: #6c757d;
            max-width: 700px;
            margin: 0 auto;
        }
        
        .teachers-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 30px;
            margin-top: 40px;
        }
        
        .teacher-card {
            background-color: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
        }
        
        .teacher-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        }
        
        .teacher-image {
            width: 100%;
            height: 250px;
            object-fit: cover;
            border-bottom: 3px solid var(--primary-color);
        }
        
        .default-teacher-image {
            width: 100%;
            height: 250px;
            background-color: var(--primary-color);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 5rem;
            font-weight: bold;
            border-bottom: 3px solid var(--primary-color);
        }
        
        .teacher-info {
            padding: 25px;
            text-align: center;
        }
        
        .teacher-name {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 5px;
            color: var(--dark-color);
        }
        
        .teacher-subject {
            color: var(--primary-color);
            font-weight: 500;
            margin-bottom: 15px;
            display: inline-block;
            padding: 5px 15px;
            background-color: rgba(108, 99, 255, 0.1);
            border-radius: 20px;
        }
        
        .teacher-bio {
            color: #6c757d;
            margin-bottom: 20px;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .teacher-social {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 15px;
        }
        
        .social-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: var(--light-color);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--dark-color);
            transition: all 0.3s ease;
            text-decoration: none;
        }
        
        .social-icon:hover {
            background-color: var(--primary-color);
            color: white;
            transform: translateY(-3px);
        }
        
        .view-profile {
            display: inline-block;
            padding: 10px 25px;
            background-color: var(--primary-color);
            color: white;
            border-radius: 30px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            margin-top: 10px;
        }
        
        .view-profile:hover {
            background-color: var(--secondary-color);
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(108, 99, 255, 0.3);
        }
        
        .badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background-color: var(--success-color);
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            grid-column: 1 / -1;
        }
        
        .empty-state i {
            font-size: 5rem;
            color: #dee2e6;
            margin-bottom: 20px;
        }
        
        .empty-state h3 {
            color: #6c757d;
            margin-bottom: 10px;
        }
        
        @media (max-width: 768px) {
            .teachers-grid {
                grid-template-columns: 1fr;
            }
            
            .header h1 {
                font-size: 2rem;
            }
        }
        
        /* Animation for cards */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .teacher-card {
            animation: fadeIn 0.5s ease forwards;
            opacity: 0;
        }
        
        .teacher-card:nth-child(1) { animation-delay: 0.1s; }
        .teacher-card:nth-child(2) { animation-delay: 0.2s; }
        .teacher-card:nth-child(3) { animation-delay: 0.3s; }
        .teacher-card:nth-child(4) { animation-delay: 0.4s; }
        .teacher-card:nth-child(5) { animation-delay: 0.5s; }
        .teacher-card:nth-child(6) { animation-delay: 0.6s; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Meet Our Expert Instructors</h1>
            <p>Learn from industry professionals with years of experience in their respective fields. Our teachers are dedicated to helping you succeed.</p>
        </div>
        
        <div class="teachers-grid">
            <?php if (empty($teachers)): ?>
                <div class="empty-state">
                    <i class="fas fa-chalkboard-teacher"></i>
                    <h3>No Teachers Available</h3>
                    <p>Check back later to meet our instructors.</p>
                </div>
            <?php else: ?>
                <?php foreach ($teachers as $teacher): ?>
                    <div class="teacher-card">
                        <?php if (!empty($teacher['image_path'])): ?>
                            <img src="<?php echo $teacher['image_path']; ?>" alt="<?php echo htmlspecialchars($teacher['name']); ?>" class="teacher-image">
                        <?php else: ?>
                            <div class="default-teacher-image">
                                <?php echo substr($teacher['name'], 0, 1); ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="teacher-info">
                            <h3 class="teacher-name"><?php echo htmlspecialchars($teacher['name']); ?></h3>
                            <span class="teacher-subject"><?php echo htmlspecialchars($teacher['subject']); ?></span>
                            <p class="teacher-bio"><?php echo htmlspecialchars($teacher['bio']); ?></p>
                            
                            <div class="teacher-social">
                                <a href="#" class="social-icon"><i class="fab fa-linkedin-in"></i></a>
                                <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
                                <a href="#" class="social-icon"><i class="fas fa-envelope"></i></a>
                            </div>
                            
                            <a href="teacher_profile.php?id=<?php echo $teacher['id']; ?>" class="view-profile">View Profile</a>
                        </div>
                        
                        <span class="badge">Available</span>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>