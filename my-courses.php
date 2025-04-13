// my-courses.php
require_once 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$userId = $_SESSION['user_id'];
$stmt = $pdo->prepare("
    SELECT c.* FROM courses c
    JOIN user_courses uc ON c.id = uc.course_id
    WHERE uc.user_id = ?
");
$stmt->execute([$userId]);
$enrolledCourses = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- HTML similar to the courses page but for enrolled courses -->
<div class="row">
    <?php foreach ($enrolledCourses as $course): ?>
    <div class="col-lg-4 col-md-6 mb-4">
        <!-- Course card similar to before but with "Continue Learning" button -->
        <a href="learn.php?course_id=<?= $course['id'] ?>" class="btn btn-success btn-block">Continue Learning</a>
    </div>
    <?php endforeach; ?>
</div>