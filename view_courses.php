<?php
include('config.php');

$query = "SELECT * FROM courses";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Courses</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container-fluid {
            background-color: #fff;
            padding: 50px 0;
        }
        .rounded {
            border-radius: 10px;
        }
        .bg-secondary {
            background-color: #343a40 !important;
            color: #fff;
        }
        .h5, h5 {
            font-size: 1.25rem;
        }
    </style>
</head>
<body>

<div class="container-fluid py-5">
    <div class="container py-5">
        <div class="text-center mb-5">
            <h5 class="text-primary text-uppercase mb-3" style="letter-spacing: 5px;">Courses</h5>
            <h1>Our Popular Courses</h1>
        </div>
        <div class="row">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="col-lg-4 col-md-6 mb-4">';
                    echo '<div class="rounded overflow-hidden mb-2">';
                    
                    // Display video or placeholder image
                    if (!empty($row['video_path'])) {
                        echo '<video class="img-fluid" width="100%" height="auto" controls>
                                <source src="' . htmlspecialchars($row['video_path']) . '" type="video/mp4">
                                Your browser does not support the video tag.
                              </video>';
                    } else {
                        echo '<img class="img-fluid" src="img/course-placeholder.jpg" alt="Course Image">';
                    }

                    echo '<div class="bg-secondary p-4">';
                    echo '<div class="d-flex justify-content-between mb-3">';
                    echo '<small class="m-0"><i class="fa fa-users text-primary mr-2"></i>25 Students</small>';
                    echo '<small class="m-0"><i class="far fa-clock text-primary mr-2"></i>01h 30m</small>';
                    echo '</div>';
                    
                    echo '<a class="h5" href="#">' . htmlspecialchars($row['title']) . '</a>';
                    
                    echo '<div class="border-top mt-4 pt-4">';
                    echo '<div class="d-flex justify-content-between">';
                    echo '<h6 class="m-0"><i class="fa fa-star text-primary mr-2"></i>4.5 <small>(250)</small></h6>';
                    echo '<h5 class="m-0">$' . number_format($row['price'], 2) . '</h5>';
                    echo '</div>';
                    echo '</div>';

                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo '<p>No courses available.</p>';
            }

            $conn->close();
            ?>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
