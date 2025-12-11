<?php
require_once 'php/functions.php';
require_once 'php/job_functions.php';

if (!isLoggedIn()) {
    redirect('login.php', 'Please login to access your dashboard');
}

$user_id = getUserId();
$user_type = $_SESSION['user_type'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - SkillBridge</title>
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

    <section class="dashboard">
        <h1 class="heading">dashboard</h1>
        <div class="box-container">
            <?php if (isJobSeeker()): ?>

                <!-- Job Seeker Dashboard - Enhanced -->
                <div class="box">
                    <h3>Welcome Back, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h3>
                    <p>Job Seeker Dashboard</p>

                    <div class="flex-btn">
                        <a href="update_profile.php" class="btn">update profile</a>
                        <a href="change_password.php" class="btn">change password</a>
                    </div>
                </div>

                <div class="box">
                    <h3>your applications</h3>
                    <?php
                    $applications = getUserApplications($_SESSION['user_id']);
                    $total_applications = $applications ? $applications->num_rows : 0;
                    $pending = 0;
                    $accepted = 0;

                    if ($applications) {
                        while ($app = $applications->fetch_assoc()) {
                            if ($app['status'] == 'pending')
                                $pending++;
                            if ($app['status'] == 'accepted')
                                $accepted++;
                        }
                    }
                    ?>
                    <p>total applications: <span><?php echo $total_applications; ?></span></p>
                    <p>pending: <span><?php echo $pending; ?></span></p>
                    <p>accepted: <span><?php echo $accepted; ?></span></p>
                    <a href="applications.php" class="btn">View applications</a>
                </div>

                <div class="box">
                    <h3>saved jobs</h3>
                    <?php
                    $saved_jobs = getSavedJobs($_SESSION['user_id']);
                    $total_saved = $saved_jobs ? $saved_jobs->num_rows : 0;
                    ?>
                    <p>total saved jobs: <span><?php echo $total_saved; ?></span></p>
                    <?php if ($total_saved > 0): ?>
                        <a href="saved_jobs.php" class="btn">view saved jobs</a>
                    <?php else: ?>
                        <p class="empty">No saved jobs yet</p>
                    <?php endif; ?>
                </div>

                <div class="box">
                    <h3>profile completeness</h3>
                    <?php $completeness = calculateProfileCompleteness($_SESSION['user_id']); ?>
                    <div class="progress-bar">
                        <div class="progress" style="width: <?php echo $completeness; ?>%"></div>
                    </div>
                    <p><?php echo $completeness; ?>% complete</p>
                    <a href="update_profile.php" class="btn">Complete profile</a>
                </div>

            <?php elseif (isEmployer()): ?>


                <!-- Employer Dashboard - Enhanced -->
                <div class="box">
                    <h3>Welcome Back, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h3>
                    <p>Employer dashboard</p>
                    <div class="flex-btn">
                        <a href="update_profile.php" class="btn">update profile</a>
                        <a href="change_password.php" class="btn">change password</a>
                    </div>
                </div>

                <div class="box">
                    <h3>your company</h3>
                    <?php
                    $company = getCompanyByUserId($_SESSION['user_id']);
                    if ($company): ?>
                        <p>company: <span><?php echo htmlspecialchars($company['name']); ?></span></p>
                        <p>status: <span><?php
                        // Safely handle verification_status
                        echo isset($company['verification_status']) ? ucfirst($company['verification_status']) : 'Pending';
                        ?></span></p>
                        <div class="flex-btn">
                            <a href="view_company.php?id=<?php echo $company['id']; ?>" class="btn">view</a>
                            <a href="update_company.php" class="btn">update</a>
                        </div>
                    <?php else: ?>
                        <p class="empty">no company registered</p>
                        <a href="register_company.php" class="btn">register company</a>
                    <?php endif; ?>
                </div>

                <div class="box">
                    <h3>posted jobs</h3>
                    <?php
                    $posted_jobs = getJobsPostedByEmployer($_SESSION['user_id']);
                    $total_jobs = $posted_jobs ? $posted_jobs->num_rows : 0;
                    $active_jobs = 0;

                    if ($posted_jobs) {
                        while ($job = $posted_jobs->fetch_assoc()) {
                            if ($job['status'] == 'active')
                                $active_jobs++;
                        }
                    }
                    ?>
                    <p>total jobs posted: <span><?php echo $total_jobs; ?></span></p>
                    <p>active jobs: <span><?php echo $active_jobs; ?></span></p>
                    <div class="flex-btn">
                        <a href="view_job.php" class="btn">view jobs</a>
                        <a href="post_job.php" class="btn">post new</a>
                    </div>
                </div>

                <div class="box">
                    <h3>applications received</h3>
                    <?php
                    $applications = getApplicationsForEmployer($_SESSION['user_id']);
                    $total_applications = $applications ? $applications->num_rows : 0;
                    $new_applications = 0;

                    if ($applications) {
                        while ($app = $applications->fetch_assoc()) {
                            if ($app['status'] == 'new')
                                $new_applications++;
                        }
                    }
                    ?>
                    <p>total applications: <span><?php echo $total_applications; ?></span></p>
                    <p>new applications: <span><?php echo $new_applications; ?></span></p>
                    <?php if ($total_applications > 0): ?>
                        <a href="manage_applications.php" class="btn">manage</a>
                    <?php else: ?>
                        <p class="empty">No applications yet</p>
                    <?php endif; ?>
                </div>

                <div class="box">
                    <h3>company statistics</h3>
                    <p>total views: <span>
                            <?php
                            // Safely handle views count
                            echo isset($company['views']) ? (int) $company['views'] : 0;
                            ?>
                        </span></p>
                    <p>followers: <span>
                            <?php
                            // Safely handle followers count
                            echo isset($company['id']) ? getCompanyFollowersCount($company['id']) : 0;
                            ?>
                        </span></p>
                    <a href="company_stats.php" class="btn">view details</a>
                </div>

            <?php endif; ?>
        </div>
    </section>

    <style>

        /* General Dashboard Styling */
.dashboard {
    max-width: 1200px;
    margin: 20px auto;
    padding: 20px;
    background: #f9f9f9;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.dashboard .heading {
    font-size: 28px;
    margin-bottom: 20px;
    text-align: center;
    color: #333;
    font-weight: bold;
}

.box-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
}

.box {
    background: #fff;
    border-radius: 8px;
    padding: 20px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    text-align: center;
    transition: transform 0.3s, box-shadow 0.3s;
}

.box:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
}

.box h3 {
    font-size: 20px;
    margin-bottom: 10px;
    color: #007bff;
}

.box p {
    font-size: 16px;
    color: #555;
    margin-bottom: 15px;
}

.box p span {
    font-weight: bold;
    color: #333;
}

.flex-btn {
    display: flex;
    justify-content: center;
    gap: 10px;
    margin-top: 15px;
}

.flex-btn .btn {
    padding: 10px 15px;
    font-size: 14px;
    color: #fff;
    background: #007bff;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    text-decoration: none;
    transition: background 0.3s;
}

.flex-btn .btn:hover {
    background: #0056b3;
}

.progress-bar {
    background: #e9ecef;
    border-radius: 4px;
    overflow: hidden;
    height: 10px;
    margin: 10px 0;
}

.progress-bar .progress {
    background: #007bff;
    height: 100%;
    transition: width 0.3s;
}

.empty {
    font-size: 14px;
    color: #999;
    font-style: italic;
}
    </style>

    <!--footer start-->
    <?php include 'includes/footer.php'; ?>
    <!--footer end-->

    <script src="js/script.js"></script>
</body>

</html>