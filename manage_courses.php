<?php
session_start();
// Check if admin is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] !== 'admin') {
    header("Location: register.php");
    exit();
}

include('config.php');

// Handle form submissions
$message = '';
$edit_mode = false;
$current_course = null;

// Add/Update Course
if (isset($_POST['submit_course'])) {
    $title = $conn->real_escape_string($_POST['title']);
    $description = $conn->real_escape_string($_POST['description']);
    $duration = $conn->real_escape_string($_POST['duration']);
    $price = floatval($_POST['price']);
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    
    // Handle image upload
    $image_path = '';
    if (isset($_FILES['course_image']) && $_FILES['course_image']['error'] == 0) {
        $target_dir = "uploads/courses/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $file_ext = strtolower(pathinfo($_FILES['course_image']['name'], PATHINFO_EXTENSION));
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];
        
        if (in_array($file_ext, $allowed_ext)) {
            $new_filename = uniqid('course_', true) . '.' . $file_ext;
            $target_file = $target_dir . $new_filename;
            
            if (move_uploaded_file($_FILES['course_image']['tmp_name'], $target_file)) {
                $image_path = $target_file;
            }
        }
    }
    
    if (isset($_POST['course_id']) && !empty($_POST['course_id'])) {
        // Update existing course
        $course_id = intval($_POST['course_id']);
        $sql = "UPDATE courses SET 
                title = '$title',
                description = '$description',
                duration = '$duration',
                price = $price,
                is_active = $is_active";
        
        if (!empty($image_path)) {
            $sql .= ", image_path = '$image_path'";
        }
        
        $sql .= " WHERE id = $course_id";
        
        if ($conn->query($sql) === TRUE) {
            $message = "<div class='success'>Course updated successfully!</div>";
        } else {
            $message = "<div class='error'>Error updating course: " . $conn->error . "</div>";
        }
    } else {
        // Add new course
        $sql = "INSERT INTO courses (title, description, image_path, duration, price, is_active) 
                VALUES ('$title', '$description', '$image_path', '$duration', $price, $is_active)";
        
        if ($conn->query($sql) === TRUE) {
            $message = "<div class='success'>Course added successfully!</div>";
        } else {
            $message = "<div class='error'>Error adding course: " . $conn->error . "</div>";
        }
    }
}

// Set edit mode if course ID is provided
if (isset($_GET['edit'])) {
    $course_id = intval($_GET['edit']);
    $sql = "SELECT * FROM courses WHERE id = $course_id";
    $result = $conn->query($sql);
    
    if ($result->num_rows == 1) {
        $edit_mode = true;
        $current_course = $result->fetch_assoc();
    }
}
// Delete course
if (isset($_GET['delete'])) {
    $course_id = intval($_GET['delete']);
    
    // First delete all enrollments for this course
    $delete_enrollments = "DELETE FROM user_courses WHERE course_id = $course_id";
    if ($conn->query($delete_enrollments)) {
        // Then delete the course
        $sql = "DELETE FROM courses WHERE id = $course_id";
        
        if ($conn->query($sql)) {
            $message = "<div class='success'>Course deleted successfully!</div>";
        } else {
            $message = "<div class='error'>Error deleting course: " . $conn->error . "</div>";
        }
    } else {
        $message = "<div class='error'>Error deleting course enrollments: " . $conn->error . "</div>";
    }
}

// Get all courses
$courses = [];
$sql = "SELECT * FROM courses ORDER BY created_at DESC";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $courses[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Courses - Admin Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 0;
            border-bottom: 1px solid #e0e0e0;
            margin-bottom: 30px;
        }
        
        .header h1 {
            color: var(--primary-color);
            font-weight: 600;
        }
        
        .btn {
            display: inline-block;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            color: white;
        }
        
        .btn-primary:hover {
            background-color: var(--secondary-color);
        }
        
        .btn-danger {
            background-color: var(--danger-color);
            color: white;
        }
        
        .btn-danger:hover {
            background-color: #c82333;
        }
        
        .btn-warning {
            background-color: var(--warning-color);
            color: #212529;
        }
        
        .btn-warning:hover {
            background-color: #e0a800;
        }
        
        .card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 25px;
            margin-bottom: 30px;
        }
        
        .card-title {
            font-size: 1.5rem;
            margin-bottom: 20px;
            color: var(--dark-color);
            font-weight: 600;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
        }
        
        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
            transition: border 0.3s ease;
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            outline: none;
        }
        
        textarea.form-control {
            min-height: 120px;
            resize: vertical;
        }
        
        .form-check {
            display: flex;
            align-items: center;
        }
        
        .form-check-input {
            margin-right: 10px;
        }
        
        .course-image-preview {
            max-width: 200px;
            max-height: 200px;
            margin-top: 10px;
            border-radius: 5px;
            display: block;
        }
        
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        .table th, .table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        .table th {
            background-color: var(--light-color);
            font-weight: 600;
        }
        
        .table tr:hover {
            background-color: rgba(108, 99, 255, 0.05);
        }
        
        .badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        
        .badge-success {
            background-color: rgba(40, 167, 69, 0.2);
            color: var(--success-color);
        }
        
        .badge-danger {
            background-color: rgba(220, 53, 69, 0.2);
            color: var(--danger-color);
        }
        
        .action-btns {
            display: flex;
            gap: 10px;
        }
        
        .success {
            color: var(--success-color);
            background-color: rgba(40, 167, 69, 0.2);
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border-left: 4px solid var(--success-color);
        }
        
        .error {
            color: var(--danger-color);
            background-color: rgba(220, 53, 69, 0.2);
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border-left: 4px solid var(--danger-color);
        }
        
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #6c757d;
        }
        
        .empty-state i {
            font-size: 3rem;
            margin-bottom: 20px;
            color: #dee2e6;
        }
        
        @media (max-width: 768px) {
            .table {
                display: block;
                overflow-x: auto;
            }
            
            .action-btns {
                flex-direction: column;
                gap: 5px;
            }
            
            .btn {
                padding: 8px 12px;
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><?php echo $edit_mode ? 'Edit Course' : 'Add New Course'; ?></h1>
            <a href="admin_dashboard.php" class="btn btn-primary">Back to Dashboard</a>
        </div>
        
        <?php echo $message; ?>
        
        <div class="card">
            <form action="manage_courses.php" method="POST" enctype="multipart/form-data">
                <?php if ($edit_mode): ?>
                    <input type="hidden" name="course_id" value="<?php echo $current_course['id']; ?>">
                <?php endif; ?>
                
                <div class="form-group">
                    <label for="title">Course Title</label>
                    <input type="text" id="title" name="title" class="form-control" 
                           value="<?php echo $edit_mode ? htmlspecialchars($current_course['title']) : ''; ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" class="form-control" required><?php 
                        echo $edit_mode ? htmlspecialchars($current_course['description']) : ''; 
                    ?></textarea>
                </div>
                
                <div class="form-group">
                    <label for="course_image">Course Image</label>
                    <input type="file" id="course_image" name="course_image" class="form-control" accept="image/*">
                    <?php if ($edit_mode && !empty($current_course['image_path'])): ?>
                        <img src="<?php echo $current_course['image_path']; ?>" alt="Course Image" class="course-image-preview">
                        <small>Leave empty to keep current image</small>
                    <?php endif; ?>
                </div>
                
                <div class="form-group">
                    <label for="duration">Duration</label>
                    <input type="text" id="duration" name="duration" class="form-control" 
                           value="<?php echo $edit_mode ? htmlspecialchars($current_course['duration']) : ''; ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="price">Price ($)</label>
                    <input type="number" id="price" name="price" class="form-control" step="0.01" min="0"
                           value="<?php echo $edit_mode ? $current_course['price'] : '0.00'; ?>" required>
                </div>
                
                <div class="form-check">
                    <input type="checkbox" id="is_active" name="is_active" class="form-check-input" 
                           <?php echo ($edit_mode && $current_course['is_active'] == 1) || !$edit_mode ? 'checked' : ''; ?>>
                    <label for="is_active">Active Course</label>
                </div>
                
                <button type="submit" name="submit_course" class="btn btn-primary" style="margin-top: 20px;">
                    <?php echo $edit_mode ? 'Update Course' : 'Add Course'; ?>
                </button>
                
                <?php if ($edit_mode): ?>
                    <a href="manage_courses.php" class="btn btn-danger" style="margin-top: 20px; margin-left: 10px;">Cancel</a>
                <?php endif; ?>
            </form>
        </div>
        
        <div class="card">
            <h2 class="card-title">All Courses</h2>
            
            <?php if (empty($courses)): ?>
                <div class="empty-state">
                    <i>📚</i>
                    <h3>No Courses Found</h3>
                    <p>Add your first course to get started!</p>
                </div>
            <?php else: ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Duration</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($courses as $course): ?>
                            <tr>
                                <td><?php echo $course['id']; ?></td>
                                <td><?php echo htmlspecialchars($course['title']); ?></td>
                                <td><?php echo htmlspecialchars($course['duration']); ?></td>
                                <td>$<?php echo number_format($course['price'], 2); ?></td>
                                <td>
                                    <span class="badge <?php echo $course['is_active'] ? 'badge-success' : 'badge-danger'; ?>">
                                        <?php echo $course['is_active'] ? 'Active' : 'Inactive'; ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="action-btns">
                                        <a href="manage_courses.php?edit=<?php echo $course['id']; ?>" class="btn btn-warning">Edit</a>
                                        <a href="manage_courses.php?delete=<?php echo $course['id']; ?>" class="btn btn-danger" 
                                           onclick="return confirm('Are you sure you want to delete this course?')">Delete</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>