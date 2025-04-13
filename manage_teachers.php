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
$current_teacher = null;

// Add/Update Teacher
if (isset($_POST['submit_teacher'])) {
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $subject = $conn->real_escape_string($_POST['subject']);
    $bio = $conn->real_escape_string($_POST['bio']);
    
    // Handle image upload
    $image_path = '';
    if (isset($_FILES['teacher_image']) && $_FILES['teacher_image']['error'] == 0) {
        $target_dir = "uploads/teachers/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $file_ext = strtolower(pathinfo($_FILES['teacher_image']['name'], PATHINFO_EXTENSION));
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];
        
        if (in_array($file_ext, $allowed_ext)) {
            $new_filename = uniqid('teacher_', true) . '.' . $file_ext;
            $target_file = $target_dir . $new_filename;
            
            if (move_uploaded_file($_FILES['teacher_image']['tmp_name'], $target_file)) {
                $image_path = $target_file;
            }
        }
    }
    
    if (isset($_POST['teacher_id']) && !empty($_POST['teacher_id'])) {
        // Update existing teacher
        $teacher_id = intval($_POST['teacher_id']);
        $sql = "UPDATE teachers SET 
                name = '$name',
                email = '$email',
                subject = '$subject',
                bio = '$bio'";
        
        if (!empty($image_path)) {
            // Delete old image if it exists
            $old_image = $conn->query("SELECT image_path FROM teachers WHERE id = $teacher_id")->fetch_assoc()['image_path'];
            if ($old_image && file_exists($old_image)) {
                unlink($old_image);
            }
            $sql .= ", image_path = '$image_path'";
        }
        
        $sql .= " WHERE id = $teacher_id";
        
        if ($conn->query($sql)) {
            $message = "<div class='success'>Teacher updated successfully!</div>";
        } else {
            $message = "<div class='error'>Error updating teacher: " . $conn->error . "</div>";
        }
    } else {
        // Add new teacher
        $sql = "INSERT INTO teachers (name, email, subject, bio, image_path) 
                VALUES ('$name', '$email', '$subject', '$bio', '$image_path')";
        
        if ($conn->query($sql)) {
            $message = "<div class='success'>Teacher added successfully!</div>";
        } else {
            $message = "<div class='error'>Error adding teacher: " . $conn->error . "</div>";
        }
    }
}

// Set edit mode if teacher ID is provided
if (isset($_GET['edit'])) {
    $teacher_id = intval($_GET['edit']);
    $sql = "SELECT * FROM teachers WHERE id = $teacher_id";
    $result = $conn->query($sql);
    
    if ($result->num_rows == 1) {
        $current_teacher = $result->fetch_assoc();
    }
}

// Delete teacher
if (isset($_GET['delete'])) {
    $teacher_id = intval($_GET['delete']);
    
    // Get image path first
    $image_path = $conn->query("SELECT image_path FROM teachers WHERE id = $teacher_id")->fetch_assoc()['image_path'];
    
    $sql = "DELETE FROM teachers WHERE id = $teacher_id";
    
    if ($conn->query($sql)) {
        // Delete the image file if it exists
        if ($image_path && file_exists($image_path)) {
            unlink($image_path);
        }
        $message = "<div class='success'>Teacher deleted successfully!</div>";
    } else {
        $message = "<div class='error'>Error deleting teacher: " . $conn->error . "</div>";
    }
}

// Get all teachers
$teachers = [];
$sql = "SELECT * FROM teachers ORDER BY created_at DESC";
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
    <title>Manage Teachers - Admin Dashboard</title>
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
            font-size: 0.9rem;
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
        
        .teacher-image-preview {
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
        
        .teacher-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
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
        
        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            overflow: auto;
        }
        
        .modal-content {
            background-color: white;
            margin: 10% auto;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            width: 90%;
            max-width: 600px;
            animation: modalopen 0.3s;
        }
        
        @keyframes modalopen {
            from { opacity: 0; transform: translateY(-50px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }
        
        .modal-header h2 {
            color: var(--primary-color);
            font-weight: 600;
        }
        
        .close {
            color: #aaa;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }
        
        .close:hover {
            color: var(--danger-color);
        }
        
        .teacher-details {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .teacher-info {
            flex: 1;
        }
        
        .teacher-main-image {
            width: 120px;
            height: 120px;
            border-radius: 10px;
            object-fit: cover;
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
                font-size: 0.8rem;
            }
            
            .teacher-details {
                flex-direction: column;
            }
            
            .teacher-main-image {
                align-self: center;
            }
            
            .modal-content {
                margin: 20% auto;
                width: 95%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><?php echo isset($current_teacher) ? 'Edit Teacher' : 'Add New Teacher'; ?></h1>
            <a href="admin_dashboard.php" class="btn btn-primary">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>
        
        <?php echo $message; ?>
        
        <div class="card">
            <form action="manage_teachers.php" method="POST" enctype="multipart/form-data">
                <?php if (isset($current_teacher)): ?>
                    <input type="hidden" name="teacher_id" value="<?php echo $current_teacher['id']; ?>">
                <?php endif; ?>
                
                <div class="teacher-details">
                    <?php if (isset($current_teacher) && !empty($current_teacher['image_path'])): ?>
                        <img src="<?php echo $current_teacher['image_path']; ?>" alt="Teacher Image" class="teacher-main-image">
                    <?php endif; ?>
                    
                    <div class="teacher-info">
                        <div class="form-group">
                            <label for="name">Full Name</label>
                            <input type="text" id="name" name="name" class="form-control" 
                                   value="<?php echo isset($current_teacher) ? htmlspecialchars($current_teacher['name']) : ''; ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" class="form-control" 
                                   value="<?php echo isset($current_teacher) ? htmlspecialchars($current_teacher['email']) : ''; ?>" required>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="subject">Subject</label>
                    <input type="text" id="subject" name="subject" class="form-control" 
                           value="<?php echo isset($current_teacher) ? htmlspecialchars($current_teacher['subject']) : ''; ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="bio">Bio</label>
                    <textarea id="bio" name="bio" class="form-control" required><?php 
                        echo isset($current_teacher) ? htmlspecialchars($current_teacher['bio']) : ''; 
                    ?></textarea>
                </div>
                
                <div class="form-group">
                    <label for="teacher_image">Profile Image</label>
                    <input type="file" id="teacher_image" name="teacher_image" class="form-control" accept="image/*">
                    <?php if (isset($current_teacher) && !empty($current_teacher['image_path'])): ?>
                        <small>Leave empty to keep current image</small>
                    <?php endif; ?>
                </div>
                
                <button type="submit" name="submit_teacher" class="btn btn-primary">
                    <i class="fas fa-save"></i> <?php echo isset($current_teacher) ? 'Update Teacher' : 'Add Teacher'; ?>
                </button>
                
                <?php if (isset($current_teacher)): ?>
                    <a href="manage_teachers.php" class="btn btn-danger" style="margin-left: 10px;">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                <?php endif; ?>
            </form>
        </div>
        
        <div class="card">
            <h2 class="card-title">All Teachers</h2>
            
            <?php if (empty($teachers)): ?>
                <div class="empty-state">
                    <i class="fas fa-chalkboard-teacher"></i>
                    <h3>No Teachers Found</h3>
                    <p>Add your first teacher to get started!</p>
                </div>
            <?php else: ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Photo</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Subject</th>
                            <th>Joined</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($teachers as $teacher): ?>
                            <tr>
                                <td>
                                    <?php if (!empty($teacher['image_path'])): ?>
                                        <img src="<?php echo $teacher['image_path']; ?>" alt="Teacher" class="teacher-avatar">
                                    <?php else: ?>
                                        <div class="teacher-avatar" style="background-color: #6c63ff; color: white; display: flex; align-items: center; justify-content: center;">
                                            <?php echo substr($teacher['name'], 0, 1); ?>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo htmlspecialchars($teacher['name']); ?></td>
                                <td><?php echo htmlspecialchars($teacher['email']); ?></td>
                                <td><?php echo htmlspecialchars($teacher['subject']); ?></td>
                                <td><?php echo date('M d, Y', strtotime($teacher['created_at'])); ?></td>
                                <td>
                                    <div class="action-btns">
                                        <a href="manage_teachers.php?edit=<?php echo $teacher['id']; ?>" class="btn btn-warning">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <a href="manage_teachers.php?delete=<?php echo $teacher['id']; ?>" class="btn btn-danger" 
                                           onclick="return confirm('Are you sure you want to delete this teacher?')">
                                            <i class="fas fa-trash"></i> Delete
                                        </a>
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