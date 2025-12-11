<?php
require_once 'php/functions.php';
require_once 'php/job_functions.php';
require_once 'php/company_functions.php';

if (!isLoggedIn() || !isEmployer()) {
    redirect('login.php', 'Please login as an employer to post jobs');
}

$company = getCompanyByUserId(getUserId());
if (!$company) {
    redirect('register_company.php', 'Please register your company first');
}

$message = '';

if (isset($_POST['submit'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $requirements = $_POST['requirements'];
    $salary_range = $_POST['salary_range'];
    $job_type = $_POST['job_type'];
    $location = $_POST['location'];
    $deadline = $_POST['deadline'];
    $category = isset($_POST['category']) ? (int) $_POST['category'] : null;

    $job_id = postJob($title, $description, $requirements, $salary_range, $job_type, $location, $company['id'], getUserId(), $deadline);

    if ($job_id && $category) {
        // Add job to category
        $sql = "INSERT INTO job_category_mapping (job_id, category_id) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $job_id, $category);
        $stmt->execute();
    }

    if ($job_id) {
        redirect('view_job.php?id=' . $job_id, 'Job posted successfully!');
    } else {
        $message = 'Failed to post job!';
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post Job - SkillBridge</title>
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
            <a href="post_job.php" class="btn" style="margin-top: 0;">Post Job</a>
        </section>
    </header>

    <section class="job-post">
        <h1 class="heading">post a new job</h1>
        <form action="" method="post" class="job-form">
            <?php if (!empty($message)): ?>
                <div class="message"><?php echo $message; ?></div>
            <?php endif; ?>

            <h3>job details</h3>
            <div class="input-group">
                <label>job title *</label>
                <input type="text" name="title" required maxlength="100" placeholder="enter job title" class="input-field">
            </div>

            <div class="input-group">
                <label>job description *</label>
                <textarea name="description" required maxlength="2000" placeholder="enter job description"
                    class="input-field"></textarea>
            </div>

            <div class="input-group">
                <label>job requirements *</label>
                <textarea name="requirements" required maxlength="2000" placeholder="enter job requirements (one per line)"
                    class="input-field"></textarea>
            </div>

            <div class="flex">
                <div class="input-group">
                    <label>salary range *</label>
                    <input type="text" name="salary_range" required maxlength="50" placeholder="e.g. 20k-30k"
                        class="input-field">
                </div>

                <div class="input-group">
                    <label>job type *</label>
                    <select name="job_type" required class="input-field">
                        <option value="">-- select job type --</option>
                        <option value="full-time">full-time</option>
                        <option value="part-time">part-time</option>
                        <option value="contract">contract</option>
                        <option value="internship">internship</option>
                        <option value="temporary">temporary</option>
                    </select>
                </div>
            </div>

            <div class="flex">
                <div class="input-group">
                    <label>job location *</label>
                    <input type="text" name="location" required maxlength="100" placeholder="e.g. Dhaka, Bangladesh"
                        class="input-field">
                </div>

                <div class="input-group">
                    <label>application deadline</label>
                    <input type="date" name="deadline" class="input-field">
                </div>
            </div>

            <div class="input-group">
                <label>job category</label>
                <select name="category" class="input-field">
                    <option value="">-- select category --</option>
                    <?php
                    $categories = getAllCategories();
                    if ($categories && $categories->num_rows > 0) {
                        while ($cat = $categories->fetch_assoc()) {
                            echo '<option value="' . $cat['id'] . '">' . htmlspecialchars($cat['name']) . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>

            <input type="submit" value="Post Job" name="submit" class="btn">
        </form>
    </section>

    <style>
        .job-post {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .job-post .heading {
            font-size: 24px;
            margin-bottom: 20px;
            text-align: center;
            color: #333;
        }

        .job-form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .input-group {
            display: flex;
            flex-direction: column;
            margin-bottom: 15px;
            gap: 5px;
        }

        .input-group label {
            font-size: 14px;
            margin-bottom: 5px;
            color: #555;



        }

        .input-field {
            padding: 10px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 4px;
            width: 100%;
            box-sizing: border-box;
            transition: border-color 0.3s;

        }

       


        .input-field:focus {
            border-color: #007bff;
            outline: none;

        }



        .flex {
            display: flex;
            gap: 15px;
        }

        .flex .input-group {
            flex: 1;
        }

        .btn {
            padding: 10px 15px;
            font-size: 16px;
            color: #fff;
            background: #007bff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-align: center;
        }

        .btn:hover {
            background: #0056b3;
        }

        .message {
            padding: 10px;
            background: #ffdddd;
            color: #d9534f;
            border: 1px solid #d9534f;
            border-radius: 4px;
            margin-bottom: 15px;
        }
    </style>

    <!--footer start-->
    <?php include 'includes/footer.php'; ?>
    <!--footer end-->

    <script src="js/script.js"></script>
</body>

</html>