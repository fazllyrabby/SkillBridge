<?php
require_once 'php/functions.php';
require_once 'php/job_functions.php';

// Get job ID from URL
$job_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$job = getJobById($job_id);

if (!$job) {
    header("Location: jobs.php");
    exit();
}

// Handle job application

// Initialize variables
$message = '';
$job_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Process application
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['apply'])) {
    if (isLoggedIn() && isJobSeeker()) {
        if (applyForJob($job_id, getUserId())) {
            $message = "Application submitted successfully!";
            // Optional: Redirect to prevent form resubmission
            // header("Location: ".$_SERVER['REQUEST_URI']);
            // exit();
        } else {
            $message = "You've already applied for this job.";
        }
    } else {
        $message = "Please login as a job seeker to apply.";
        // Optional: Store redirect URL for after login
        // $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
        // header("Location: login.php");
        // exit();
    }
}
?>

<!-- HTML Form -->
<form method="POST" action="">
    <input type="hidden" name="job_id" value="<?php echo $job_id; ?>">
    <button type="submit" name="apply" class="btn">Apply Now</button>
</form>

<!-- Display Messages -->
<?php if (!empty($message)): ?>
    <div class="message"><?php echo htmlspecialchars($message); ?></div>
<?php endif; 

// Handle saving job
if (isset($_POST['save'])) {
    if (isLoggedIn()) {
        if (saveJob($job_id, getUserId())) {
            $message = "Job saved successfully!";
        } else {
            $message = "You've already saved this job.";
        }
    } else {
        $message = "Please login to save this job.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($job['title']); ?> - SkillBridge</title>
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

    <!--view job section-->
    <section class="job-details">
        <h1 class="heading">job details</h1>
        <div class="details">
            <div class="job-info">
                <h3><?php echo htmlspecialchars($job['title']); ?></h3>
                <a href="view_company.php?id=<?php echo $job['company_id']; ?>"><?php echo htmlspecialchars($job['company_name']); ?></a>
                <p><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($job['location']); ?></p>
            </div>
            <div class="basic-details">
                <h3>salary</h3>
                <p><?php echo htmlspecialchars($job['salary_range']); ?> per month</p>
                <h3>benefits</h3>
                <p>work from home, health insurance</p>
                <h3>job type</h3>
                <p><?php echo htmlspecialchars($job['job_type']); ?></p>
                <h3>schedule</h3>
                <p>day shift</p>
            </div>
            <ul>
                <h3>requirements</h3>
                <?php
                $requirements = explode("\n", $job['requirements']);
                foreach ($requirements as $req) {
                    if (!empty(trim($req))) {
                        echo '<li>' . htmlspecialchars(trim($req)) . '</li>';
                    }
                }
                ?>
            </ul>
            <div class="description">
    <h3>job description</h3>
    <p><?php echo nl2br(htmlspecialchars($job['description'] ?? '')); ?></p>
    <ul>
        <li>Posted on: 
            <?php 
            if (!empty($job['posted_at'])) {
                $posted_date = strtotime($job['posted_at']);
                echo $posted_date ? date('M j, Y', $posted_date) : 'Not specified';
            } else {
                echo 'Not specified';
            }
            ?>
        </li>
        <?php if (!empty($job['deadline'])): ?>
            <li>Application deadline: 
                <?php 
                $deadline_date = strtotime($job['deadline']);
                echo $deadline_date ? date('M j, Y', $deadline_date) : 'Not specified';
                ?>
            </li>
        <?php endif; ?>
    </ul>
</div>
            <form action="" method="post" class="flex-btn">
                <?php if (isLoggedIn() && isJobSeeker()): ?>
                    <input type="submit" value="Apply Now" name="apply" class="btn">
                <?php elseif (!isLoggedIn()): ?>
                    <a href="login.php" class="btn">Login to Apply</a>
                <?php endif; ?>
                <?php if (isLoggedIn()): ?>
                    <button type="submit" class="save" name="save"><i class="far fa-heart"></i> <span>save job</span></button>
                <?php endif; ?>
            </form>
        </div>
    </section>
    <!--view job section end-->

    <!--footer start-->
    <?php include 'includes/footer.php'; ?>
    <!--footer end-->
    <script >
        document.querySelector('form').addEventListener('submit', function(e) {
    e.preventDefault(); // This would prevent submission
    });

    </script>

    <script src="js/script.js"></script>
</body>
</html>