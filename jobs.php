<?php
require_once 'php/functions.php';
require_once 'php/job_functions.php';

// Get search parameters from URL
$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
$location = isset($_GET['location']) ? trim($_GET['location']) : '';
$category = isset($_GET['category']) ? (int)$_GET['category'] : 0;
$job_type = isset($_GET['job_type']) ? trim($_GET['job_type']) : '';
$date_posted = isset($_GET['date_posted']) ? trim($_GET['date_posted']) : '';
$salary = isset($_GET['salary']) ? trim($_GET['salary']) : '';
$education = isset($_GET['education']) ? trim($_GET['education']) : '';
$shift = isset($_GET['shift']) ? trim($_GET['shift']) : '';

// Search jobs with filters
$jobs = searchJobs($keyword, $location, $category, $job_type);

// Get all categories for dropdown
$categories = getAllCategories();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Jobs - SkillBridge</title>
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

    <!--job filter section-->
    <section class="job-filter">
        <h1 class="heading">filter jobs</h1>
        <form action="" method="get">
            <div class="flex">
                <div class="box">
                    <p>job title <span>*</span></p>
                    <input type="text" name="keyword" placeholder="keyword, category or company" 
                           value="<?php echo htmlspecialchars($keyword); ?>" class="input">
                </div>
                <div class="box">
                    <p>job location</p>
                    <input type="text" name="location" placeholder="city, state or country" 
                           value="<?php echo htmlspecialchars($location); ?>" class="input">
                </div>
            </div>
            <div class="dropdown-container">
                <div class="dropdown">
                    <input type="text" readonly placeholder="date posted" name="date_posted" 
                           value="<?php echo htmlspecialchars($date_posted); ?>" class="output">
                    <div class="lists">
                        <p class="items">today</p>
                        <p class="items">03 days ago</p>
                        <p class="items">07 days ago</p>
                        <p class="items">10 days ago</p>
                        <p class="items">15 days ago</p>
                        <p class="items">30 days ago</p>
                    </div>
                </div>

                <div class="dropdown">
                    <input type="text" readonly placeholder="job type" name="job_type" 
                           value="<?php echo htmlspecialchars($job_type); ?>" class="output">
                    <div class="lists">
                        <p class="items">full-time</p>
                        <p class="items">part-time</p>
                        <p class="items">internship</p>
                        <p class="items">contract</p>
                        <p class="items">temporary</p>
                        <p class="items">fresher</p>
                    </div>
                </div>

                <div class="dropdown">
                    <input type="text" readonly placeholder="job category" name="category" 
                           value="<?php echo $category ? htmlspecialchars(getCategoryName($category)) : ''; ?>" class="output">
                    <div class="lists">
                        <?php if ($categories && $categories->num_rows > 0): ?>
                            <?php while ($cat = $categories->fetch_assoc()): ?>
                                <p class="items" data-value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['name']); ?></p>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="dropdown">
                    <input type="text" readonly placeholder="estimated salary" name="salary" 
                           value="<?php echo htmlspecialchars($salary); ?>" class="output">
                    <div class="lists">
                        <p class="items">1k or less</p>
                        <p class="items">1k - 5k</p>
                        <p class="items">5k - 10k</p>
                        <p class="items">10k - 20k</p>
                        <p class="items">20k - 40k</p>
                        <p class="items">40k - 60k</p>
                        <p class="items">60k - 1 lakh</p>
                        <p class="items">1 lakh - 10 lakh</p>
                        <p class="items">10 lakh - 20 lakh</p>
                        <p class="items">20 lakh - 50 lakh</p>
                        <p class="items">50 lakh - 1 crore</p>
                        <p class="items">1 crore - 20crore</p>
                    </div>
                </div>

                <div class="dropdown">
                    <input type="text" readonly placeholder="educational level" name="education" 
                           value="<?php echo htmlspecialchars($education); ?>" class="output">
                    <div class="lists">
                        <p class="items">ssc pass</p>
                        <p class="items">hsc pass</p>
                        <p class="items">degree pass</p>
                        <p class="items">bechlelor's degree</p>
                        <p class="items">master's degree</p>
                        <p class="items">deploma pass</p>
                    </div>
                </div>

                <div class="dropdown">
                    <input type="text" readonly placeholder="work shifts" name="shift" 
                           value="<?php echo htmlspecialchars($shift); ?>" class="output">
                    <div class="lists">
                        <p class="items">day shift</p>
                        <p class="items">night shift</p>
                        <p class="items">flexible shift</p>
                        <p class="items">fixed shift</p>
                    </div>
                </div>
            </div>
            <input type="submit" value="Apply Filters" class="btn">
        </form>
    </section>
    <!--job filter section end-->

    <!--all job section-->
    <section class="jobs-container">
        <h1 class="heading">all jobs</h1>
        <div class="box-container">
            <?php if ($jobs && $jobs->num_rows > 0): ?>
                <?php while ($job = $jobs->fetch_assoc()): ?>
                    <div class="box">
                        <div class="company">
                            <img src="<?php echo !empty($job['company_logo']) ? htmlspecialchars($job['company_logo']) : 'images/default-logo.png'; ?>" alt="">
                            <div>
                                <h3><?php echo htmlspecialchars($job['company_name']); ?></h3>
                                <p> <?php echo date('M j, Y', strtotime($job['posted_at'])); ?> </p>
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
                                <form action="save_job.php" method="post" class="save-form">
                                    <input type="hidden" name="job_id" value="<?php echo $job['id']; ?>">
                                    <button type="submit" class="far fa-heart" name="save"></button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="empty">No jobs found matching your criteria. <a href="jobs.php">Reset filters</a></p>
            <?php endif; ?>
        </div>
    </section>
    <!--all job section end-->

    <!--footer start-->
    <?php include 'includes/footer.php'; ?>
    <!--footer end-->

    <script src="js/script.js"></script>
    <script>
    // Handle dropdown selection
    document.querySelectorAll('.dropdown .items').forEach(item => {
        item.addEventListener('click', function() {
            const dropdown = this.closest('.dropdown');
            const output = dropdown.querySelector('.output');
            
            // For category dropdown, we need to set a hidden input
            if (this.hasAttribute('data-value')) {
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'category';
                hiddenInput.value = this.getAttribute('data-value');
                
                // Remove any existing hidden input
                const existingHidden = dropdown.querySelector('input[type="hidden"][name="category"]');
                if (existingHidden) {
                    dropdown.removeChild(existingHidden);
                }
                
                dropdown.appendChild(hiddenInput);
            }
            
            output.value = this.textContent;
        });
    });

    // Preserve selected filters when page reloads
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        
        // Set dropdown values
        document.querySelectorAll('.dropdown .output').forEach(output => {
            const paramName = output.name;
            if (urlParams.has(paramName)) {
                output.value = urlParams.get(paramName);
            }
        });
    });
    </script>
</body>
</html>