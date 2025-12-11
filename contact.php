<?php
require_once 'php/functions.php';
require_once 'php/contact_functions.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $subject = $_POST['subject'];
    $msg = $_POST['message'];
    $role = $_POST['role'];
    
    if (submitContactForm($name, $email, $phone, $subject, $msg, $role)) {
        $message = 'Your message has been sent successfully!';
    } else {
        $message = 'Failed to send message. Please try again.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - SkillBridge</title>
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

    <!--contact us section-->
    <div class="section-title">contact us</div>
    <section class="contact">
        <div class="box-container">
            <div class="box">
                <i class="fas fa-phone"></i>
                <h3>phone number</h3>
                <a href="tel:01830161365">01830161365</a>
                <a href="tel:01788097423">01767120769</a>
            </div>

            <div class="box">
                <i class="fas fa-envelope"></i>
                <h3>email address</h3>
                <a href="mailto:fazlly@gmail.com">fazlly4@gmail.com</a>
                <a href="mailto:taj@gmail.com">taj@gmail.com</a>
            </div>

            <div class="box">
                <i class="fas fa-map-marker-alt"></i>
                <h3>office address</h3>
                <a href="#">15 No Road, Nikujo-02, khilkhet, Dhaka, Bangladesh</a>
            </div>
        </div>

        <form action="" method="post">
            <h3>drop your message</h3>
            <?php if (!empty($message)): ?>
                <div class="message"><?php echo $message; ?></div>
            <?php endif; ?>
            <div class="flex">
                <div class="box">
                    <p>name <span>*</span></p>
                    <input type="text" name="name" required maxlength="20" placeholder="enter your name" class="input">
                </div>

                <div class="box">
                    <p>email <span>*</span></p>
                    <input type="email" name="email" required maxlength="50" placeholder="enter your email" class="input">
                </div>

                <div class="box">
                    <p>phone number</p>
                    <input type="text" name="phone" maxlength="20" placeholder="enter your number" class="input">
                </div>

                <div class="box">
                    <p>subject</p>
                    <input type="text" name="subject" maxlength="100" placeholder="enter subject" class="input">
                </div>
            </div>
            <div class="box">
                <p>your role <span>*</span></p>
                <select name="role" required class="input">
                    <option value="">-- select your role --</option>
                    <option value="job_seeker">job seeker (employee)</option>
                    <option value="employer">job provider (employer)</option>
                </select>
            </div>
            <p>message <span>*</span></p>
            <textarea name="message" class="input" required maxlength="1000" placeholder="enter your message" cols="30" rows="10"></textarea>
            <input type="submit" value="send message" name="send" class="btn">
        </form>
    </section>
    <!--contact us section end-->

    <!--footer start-->
    <?php include 'includes/footer.php'; ?>
    <!--footer end-->

    <script src="js/script.js"></script>
</body>
</html>