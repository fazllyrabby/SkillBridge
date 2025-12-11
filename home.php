<?php
require_once 'php/functions.php';
require_once 'php/job_functions.php';

// Get featured jobs
$featured_jobs = getAllJobs(5);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - SkillBridge</title>
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

    <!--home section-->
    <div class="home-container">
        <section class="home">
            <form action="jobs.php" method="get">
                <h3>find your next job</h3>
                <p>job title <span>*</span></p>
                <input type="text" name="keyword" placeholder="keyword, category or Company" required maxlength="20" class="input">
                <p>job location</p>
                <input type="text" name="location" placeholder="city, state or country" maxlength="50" class="input">
                <input type="submit" value="Search Job" name="search" class="btn">
            </form>
        </section>
    </div>
    <!--home section end-->

    <!--category section-->
    <section class="category">
        <h1 class="heading">job categories</h1>
        <div class="box-container">
            <?php
            $categories = getAllCategories();
            if ($categories && $categories->num_rows > 0) {
                while ($category = $categories->fetch_assoc()) {
                    echo '<a href="jobs.php?category=' . $category['id'] . '" class="box">
                            <i class="fas ' . htmlspecialchars($category['icon']) . '"></i>
                            <div>
                                <h3>' . htmlspecialchars($category['name']) . '</h3>
                                <span>' . rand(500, 2500) . ' jobs</span>
                            </div>
                          </a>';
                }
            } else {
                echo '<p class="empty">No categories found</p>';
            }
            ?>
        </div>
    </section>
    <!--category section end-->

    <!--job section-->
    <section class="jobs-container">
    <h1 class="heading">latest jobs</h1>
    <div class="box-container">
        <?php
        // Fetch latest jobs from database (sorted by date descending)
        $jobs = searchJobs();
        
        if ($jobs && $jobs->num_rows > 0) {
            while ($job = $jobs->fetch_assoc()) {
                // Calculate "X days ago" for the posting date
                $postedDate = new DateTime($job['created_at']); // Use your actual date column
                $currentDate = new DateTime();
                $interval = $postedDate->diff($currentDate);
                $daysAgo = $interval->days;
                
                // Determine currency symbol based on location (example logic)
                $currencySymbol = '₹'; // Default (Indian Rupee)
                if (stripos($job['location'], 'usa') !== false) {
                    $currencySymbol = '$';
                } elseif (stripos($job['location'], 'europ') !== false) {
                    $currencySymbol = '€';
                } elseif (stripos($job['location'], 'japan') !== false) {
                    $currencySymbol = '¥';
                }
                ?>
                <div class="box">
                    <div class="company">
                        <img src="/uploads/<?php echo htmlspecialchars($job['company_logo']); ?>" alt="<?php echo htmlspecialchars($job['company_name']); ?> logo">
                        <div>
                            <h3><?php echo htmlspecialchars($job['company_name']); ?></h3>
                            <p><?php echo $daysAgo == 0 ? 'Today' : $daysAgo . ' day' . ($daysAgo > 1 ? 's' : '') . ' ago'; ?></p>
                        </div>
                    </div>

                    <h3 class="job-title"><?php echo htmlspecialchars($job['title']); ?></h3>
                    <p class="location"><i class="fas fa-map-marker-alt"></i>
                    <span><?php echo htmlspecialchars($job['location']); ?></span></p>

                    <div class="tags">
                    <p>
                    <i class="<?php echo $currencySymbol === '₹' ? 'fas fa-indian-rupee-sign' : ($currencySymbol === '$' ? 'fas fa-dollar-sign' : ($currencySymbol === '€' ? 'fas fa-euro-sign' : 'fas fa-yen-sign')); ?>">
                    <span>
                    <?php 
                    $min_salary = isset($job['salary_min']) && is_numeric($job['salary_min']) ? number_format($job['salary_min']) : 'N/A';
                    $max_salary = isset($job['salary_max']) && is_numeric($job['salary_max']) ? number_format($job['salary_max']) : 'N/A';
                    echo $currencySymbol . $min_salary . '-' . $currencySymbol . $max_salary;
                     ?>
                   </span>
                    </i>
                    </p>
                   <p><i class="fas fa-briefcase"><span><?php echo htmlspecialchars($job['job_type'] ?? 'Not specified'); ?></span></i></p>
                   <p><i class="fas fa-clock"><span><?php echo htmlspecialchars($job['shift'] ?? 'Not specified'); ?></span></i></p>
                   </div>

                    <div class="flex-btn">
                        <a href="view_job.php?id=<?php echo $job['id']; ?>" class="btn">view details</a>
                        <button type="submit" class="far fa-heart" name="save"></button>
                    </div>
                </div>
                <?php
            }
        } else {
            echo '<p class="empty">No jobs found!</p>';
        }
        ?>
    </div>
    <div style="text-align: center; margin-top: 2rem;">
        <a href="jobs.php" class="btn">view all</a>
    </div>
</section>
    <!--job section end-->

    <!--footer start-->
    <?php include 'includes/footer.php'; ?>
    <!--footer end-->

    <script src="js/script.js"></script>
</body>
</html>