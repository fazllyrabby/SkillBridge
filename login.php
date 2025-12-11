<?php
require_once 'php/functions.php';
require_once 'php/auth.php';

$message = '';

if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    if (loginUser($email, $password)) {
        redirect('dashboard.php', 'Logged in successfully!');
    } else {
        $message = 'Incorrect email or password!';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SkillBridge</title>
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

    <!--account section -->
    <div class="account-form-container">
        <section class="account-form">
            <form action="" method="post">
                <h3>Welcome back!</h3>
                <?php if (!empty($message)): ?>
                    <div class="message"><?php echo $message; ?></div>
                <?php endif; ?>
                <input type="email" name="email" required maxlength="50" placeholder="enter your email" class="input">
                <input type="password" name="password" required maxlength="20" placeholder="enter your password" class="input">
                <p>Don't have an account? <a href="register.php">register now</a></p>
                <input type="submit" value="Login Now" name="submit" class="btn">
            </form>
        </section>
    </div>
    <!--account section end-->

    <!--footer start-->
    <?php include 'includes/footer.php'; ?>
    <!--footer end-->

    <script src="js/script.js"></script>
</body>
</html>