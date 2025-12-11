<?php
require_once 'php/functions.php';
require_once 'php/company_functions.php';

// Get top reviews from database
$reviews = getTopReviews(3); // Get 3 most recent reviews
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - SkillBridge</title>
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

    <!--about us section-->
    <div class="section-title">about us</div>
    <section class="about">
        <img src="images/contact.jpg" alt="About SkillBridge">
        <div class="box">
            <h3>why choose us?</h3>
            <p>SkillBridge is Bangladesh's leading job portal, connecting talented professionals with top employers across the country. Our mission is to make the job search process simple, efficient, and effective for both job seekers and employers.</p>
            <p>With thousands of job listings from reputable companies and a user-friendly platform, we've helped countless individuals find their dream jobs and businesses find the perfect candidates.</p>
            <a href="contact.php" class="btn">contact us</a>
        </div>
    </section>
    <!--about us section end-->

    <!--reviews section-->
    <?php
// Function to fetch reviews from database
function getReviews($limit = 3) {
    global $conn;
    
    $sql = "SELECT r.*, u.name AS user_name, u.profession, u.profile_image 
            FROM reviews r
            JOIN users u ON r.user_id = u.id
            ORDER BY r.rating DESC, r.created_at DESC
            LIMIT ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $limit);
    $stmt->execute();
    return $stmt->get_result();
}
?>

<div class="section-title">top reviews</div>
    <section class="reviews">
        <div class="box-container">
            <?php if ($reviews && $reviews->num_rows > 0): ?>
                <?php while ($review = $reviews->fetch_assoc()): ?>
                    <div class="box">
                        <div class="stars">
                            <?php
                            $rating = $review['rating'];
                            $full_stars = floor($rating);
                            $has_half_star = ($rating - $full_stars) >= 0.5;
                            
                            for ($i = 1; $i <= 5; $i++) {
                                if ($i <= $full_stars) {
                                    echo '<i class="fas fa-star"></i>';
                                } elseif ($i == $full_stars + 1 && $has_half_star) {
                                    echo '<i class="fas fa-star-half-alt"></i>';
                                } else {
                                    echo '<i class="far fa-star"></i>';
                                }
                            }
                            ?>
                        </div>
                        <h3 class="title"><?php echo htmlspecialchars($review['title'] ?? 'Great Service'); ?></h3>
                        <p><?php echo htmlspecialchars($review['comment']); ?></p>
                        <div class="user">
                            <?php if (!empty($review['user_photo'])): ?>
                                <img src="<?php echo htmlspecialchars($review['user_photo']); ?>" alt="<?php echo htmlspecialchars($review['user_name']); ?>">
                            <?php else: ?>
                                <img src="images/default-user.png" alt="User">
                            <?php endif; ?>
                            <div>
                                <h3><?php echo htmlspecialchars($review['user_name']); ?></h3>
                                <span><?php echo date('M j, Y', strtotime($review['created_at'])); ?></span>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="box">
                    <p class="empty">No reviews yet. Be the first to review us!</p>
                </div>
            <?php endif; ?>
        </div>
    </section>
    <!--reviews section end-->

    <!--stats section-->
    <section class="stats">
        <h1 class="heading">our achievements</h1>
        <div class="box-container">
            <div class="box">
                <h3><?php echo getTotalJobCount(); ?></h3>
                <p>jobs posted</p>
            </div>
            <div class="box">
                <h3><?php echo getTotalCompanyCount(); ?></h3>
                <p>companies registered</p>
            </div>
            <div class="box">
                <h3><?php echo getTotalUserCount(); ?></h3>
                <p>users</p>
            </div>
            <div class="box">
                <h3><?php echo getTotalApplicationCount(); ?></h3>
                <p>applications</p>
            </div>
        </div>
    </section>
    <!--stats section end-->
    <style>
        /* Stats Section Styling */
.stats {
    background: #f9f9f9;
    padding: 40px 20px;
    text-align: center;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    margin: 20px auto;
    max-width: 1200px;
}

.stats .heading {
    font-size: 28px;
    color: #333;
    margin-bottom: 30px;
    font-weight: bold;
}

.stats .box-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
}

.stats .box {
    background: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s, box-shadow 0.3s;
}

.stats .box:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
}

.stats .box h3 {
    font-size: 36px;
    color: #007bff;
    margin-bottom: 10px;
    font-weight: bold;
}

.stats .box p {
    font-size: 16px;
    color: #555;
    margin: 0;
}
    </style>

    <!--footer start-->
    <?php include 'includes/footer.php'; ?>
    <!--footer end-->

    <script src="js/script.js"></script>
</body>
</html>