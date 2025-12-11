<?php
require_once 'php/functions.php';
require_once 'php/job_functions.php';
require_once 'php/company_functions.php';

// Get company ID from URL
$company_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$company = getCompanyById($company_id);

if (!$company) {
    header("Location: jobs.php");
    exit();
}

// Get company jobs
$company_jobs = getJobsByCompany($company_id);
$average_rating = getCompanyAverageRating($company_id);
$reviews = getCompanyReviews($company_id);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($company['name']); ?> - SkillBridge</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header class="header">
        <section class="flex">
            <div id="menu-btn" class="fas fa-bars-staggered"></div>
            <a href="home.php" class="logo"><i class="fas fa-briefcase"></i>SkillBridge.</a>
            <nav class="navbar">
                <a href="home.php">home</a>
                <a href="about.php">about us</a>
                <a href="jobs.php">all jobs</a>
                <a href="contact.php">contact us</a>
                <a href="login.php">account</a>
            </nav>
            <?php if (isLoggedIn()): ?>
                <a href="post_job.php" class="btn" style="margin-top: 0;">Post Job</a>
            <?php else: ?>
                <a href="login.php" class="btn" style="margin-top: 0;">Post Job</a>
            <?php endif; ?>
        </section>
    </header>

    <!--company details-->
    <section class="view-company">
    <h1 class="heading">company details</h1>
    <div class="details">
        <div class="info">
            <img src="<?php echo !empty($company['logo']) ? htmlspecialchars($company['logo']) : 'images/default-logo.png'; ?>" alt="">
            <h3><?php echo htmlspecialchars($company['name']); ?></h3>
            <p><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($company['location']); ?></p>
            <?php if (isset($average_rating) && $average_rating > 0): ?>
                <div class="stars">
                    <?php
                    $full_stars = floor($average_rating);
                    $half_star = ($average_rating - $full_stars) >= 0.5;
                    
                    for ($i = 1; $i <= 5; $i++) {
                        if ($i <= $full_stars) {
                            echo '<i class="fas fa-star"></i>';
                        } elseif ($i == $full_stars + 1 && $half_star) {
                            echo '<i class="fas fa-star-half-alt"></i>';
                        } else {
                            echo '<i class="far fa-star"></i>';
                        }
                    }
                    ?>
                    <span>(<?php echo number_format($average_rating, 1); ?>)</span>
                </div>
            <?php endif; ?>
        </div>
        <div class="description">
            <h3>about company</h3>
            <p><?php echo nl2br(htmlspecialchars($company['description'] ?? '')); ?></p>
        </div>
        <ul>
            <li>
                <?php 
                // Safely display job count
                $job_count = 0;
                if (isset($company_jobs) && is_object($company_jobs) && property_exists($company_jobs, 'num_rows')) {
                    $job_count = $company_jobs->num_rows;
                }
                echo $job_count . ' jobs posted';
                ?>
            </li>
            <?php if (!empty($company['established_date'])): ?>
                <li>established at <?php echo date('F j, Y', strtotime($company['established_date'])); ?></li>
            <?php endif; ?>
            <?php if (!empty($company['website'])): ?>
                <li>Website: 
                    <a href="<?php echo htmlspecialchars($company['website']); ?>" target="_blank" rel="noopener noreferrer">
                        <?php echo htmlspecialchars($company['website']); ?>
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</section>
    <!--company details end-->

    <!--job section-->
    <section class="jobs-container">
    <h1 class="heading">jobs they offer</h1>
    <div class="box-container">
        <?php
        if ($company_jobs && $company_jobs->num_rows > 0) {
            while ($job = $company_jobs->fetch_assoc()) {
                ?>
                <div class="box">
                    <div class="company">
                        <img src="<?php echo !empty($company['logo']) ? htmlspecialchars($company['logo']) : 'images/default-logo.png'; ?>" alt="">
                        <div>
                            <h3><?php echo htmlspecialchars($company['name']); ?></h3>
                            <p><?php echo date('M j, Y', strtotime($job['posted_at'])); ?></p>
                        </div>
                    </div>
                    <h3 class="job-title"><?php echo htmlspecialchars($job['title']); ?></h3>
                    <p class="location"><i class="fas fa-map-marker-alt"></i>
                    <span><?php echo htmlspecialchars($job['location']); ?></span></p>
                    <div class="tags">
                        <p><i class="fas fa-money-bill"></i><span><?php echo htmlspecialchars($job['salary_range']); ?></span></p>
                        <p><i class="fas fa-briefcase"></i><span><?php echo htmlspecialchars($job['job_type']); ?></span></p>
                        <p><i class="fas fa-clock"></i><span>day shift</span></p>
                    </div>
                    <div class="flex-btn">
                        <a href="view_job.php?id=<?php echo $job['id']; ?>" class="btn">view details</a>
                        <?php if (isLoggedIn()): ?>
                            <button type="submit" class="far fa-heart" name="save"></button>
                        <?php endif; ?>
                    </div>
                </div>
                <?php
            }
        } else {
            echo '<p class="empty">No jobs found for this company</p>';
        }
        ?>
    </div>
</section>
    <!--job section end-->

    <!--reviews section-->
    <section class="reviews">
        <h1 class="heading">company reviews</h1>
        <div class="box-container">
            <?php
            if ($reviews && $reviews->num_rows > 0) {
                while ($review = $reviews->fetch_assoc()) {
                    echo '<div class="box">
                            <div class="stars">';
                    
                    for ($i = 1; $i <= 5; $i++) {
                        if ($i <= $review['rating']) {
                            echo '<i class="fas fa-star"></i>';
                        } elseif ($i - 0.5 <= $review['rating']) {
                            echo '<i class="fas fa-star-half-alt"></i>';
                        } else {
                            echo '<i class="far fa-star"></i>';
                        }
                    }
                    
                    echo '</div>
                            <h3 class="title">' . htmlspecialchars($review['comment'] ? substr($review['comment'], 0, 30) . '...' : 'No comment') . '</h3>
                            <p>' . htmlspecialchars($review['comment']) . '</p>
                            <div class="user">
                                <div>
                                    <h3>' . htmlspecialchars($review['user_name']) . '</h3>
                                    <span>' . date('M j, Y', strtotime($review['created_at'])) . '</span>
                                </div>
                            </div>
                        </div>';
                }
            } else {
                echo '<p class="empty">No reviews yet</p>';
            }
            ?>
        </div>
        
        <?php if (isLoggedIn()): ?>
        <div class="review-form">
            <h3>Add your review</h3>
            <form action="submit_review.php" method="post">
                <input type="hidden" name="company_id" value="<?php echo $company_id; ?>">
                <div class="rating">
                    <span>Rating:</span>
                    <select name="rating" required>
                        <option value="">Select rating</option>
                        <option value="1">1 - Poor</option>
                        <option value="2">2 - Fair</option>
                        <option value="3">3 - Good</option>
                        <option value="4">4 - Very Good</option>
                        <option value="5">5 - Excellent</option>
                    </select>
                </div>
                <textarea name="comment" placeholder="Your review..." required></textarea>
                <input type="submit" value="Submit Review" class="btn">
            </form>
        </div>
        <?php endif; ?>
    </section>
    <!--reviews section end-->
    <style>

        /* Reviews Section Styling */
.reviews {
    background: #f9f9f9;
    padding: 40px 20px;
    text-align: center;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    margin: 20px auto;
    max-width: 1200px;
}

.reviews .heading {
    font-size: 28px;
    color: #333;
    margin-bottom: 30px;
    font-weight: bold;
}

.reviews .box-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
}

.reviews .box {
    background: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s, box-shadow 0.3s;
    text-align: left;
}

.reviews .box:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
}

.reviews .stars {
    margin-bottom: 10px;
    color: #ffc107; /* Gold color for stars */
    font-size: 18px;
}

.reviews .title {
    font-size: 20px;
    color: #333;
    margin-bottom: 10px;
    font-weight: bold;
}

.reviews .box p {
    font-size: 16px;
    color: #555;
    margin-bottom: 15px;
}

.reviews .user {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-top: 15px;
}

.reviews .user h3 {
    font-size: 16px;
    color: #333;
    margin: 0;
}

.reviews .user span {
    font-size: 14px;
    color: #999;
}

.reviews .review-form {
    margin-top: 30px;
    text-align: left;
    background: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.reviews .review-form h3 {
    font-size: 22px;
    color: #333;
    margin-bottom: 15px;
}

.reviews .review-form .rating {
    margin-bottom: 15px;
}

.reviews .review-form select,
.reviews .review-form textarea,
.reviews .review-form input[type="submit"] {
    width: 100%;
    padding: 10px;
    font-size: 14px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
}

.reviews .review-form input[type="submit"] {
    background: #007bff;
    color: #fff;
    cursor: pointer;
    transition: background 0.3s;
}

.reviews .review-form input[type="submit"]:hover {
    background: #0056b3;
}
    </style>

    <!--footer start-->
    <?php include 'includes/footer.php'; ?>
    <!--footer end-->

    <script src="js/script.js"></script>
</body>
</html>