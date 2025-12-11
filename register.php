<?php
require_once 'php/functions.php';
require_once 'php/auth.php';

$message = '';

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $c_password = $_POST['c_password'];
    $user_type = $_POST['user_type'];
    $headline = $_POST['headline'];
    $bio = $_POST['bio'];
    $skills = $_POST['skills'];
    
    // File upload handling
    $photo = $_FILES['photo']['name'];
    $photo_tmp = $_FILES['photo']['tmp_name'];
    $photo_ext = strtolower(pathinfo($photo, PATHINFO_EXTENSION));
    
    $resume = $_FILES['resume']['name'];
    $resume_tmp = $_FILES['resume']['tmp_name'];
    $resume_ext = strtolower(pathinfo($resume, PATHINFO_EXTENSION));

    // Validation
    if ($password !== $c_password) {
        $message = 'Passwords do not match!';
    } elseif (emailExists($email)) {
        $message = 'Email already registered!';
    } elseif (!in_array($photo_ext, ['jpg', 'jpeg', 'png', 'gif'])) {
        $message = 'Only JPG, JPEG, PNG & GIF files are allowed for photo!';
    } elseif (!in_array($resume_ext, ['pdf', 'doc', 'docx'])) {
        $message = 'Only PDF, DOC & DOCX files are allowed for resume!';
    } else {
        // Generate unique filenames
        $photo_name = uniqid('photo_', true) . '.' . $photo_ext;
        $resume_name = uniqid('resume_', true) . '.' . $resume_ext;
        
        // Upload files
        move_uploaded_file($photo_tmp, 'uploads/profiles/' . $photo_name);
        move_uploaded_file($resume_tmp, 'uploads/resumes/' . $resume_name);
        
        if (registerUser($name, $email, $password, $user_type, $photo_name, $resume_name, $headline, $bio, $skills)) {
            redirect('login.php', 'Registration successful! Please login.');
        } else {
            $message = 'Registration failed!';
            // Clean up uploaded files if registration failed
            @unlink('uploads/profiles/' . $photo_name);
            @unlink('uploads/resumes/' . $resume_name);
        }
    }
}

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $c_password = $_POST['c_password'];
    $user_type = $_POST['user_type'];
    
    if ($user_type == 'job_seeker') {
        $headline = $_POST['headline'];
        $bio = $_POST['bio'];
        $skills = $_POST['skills'];
        $company_name = $company_website = $company_description = null;
        
        // Handle job seeker file uploads
        $photo = $_FILES['photo']['name'];
        $photo_tmp = $_FILES['photo']['tmp_name'];
        $resume = $_FILES['resume']['name'];
        $resume_tmp = $_FILES['resume']['tmp_name'];
        $company_logo = null;
    } else {
        $company_name = $_POST['company_name'];
        $company_website = $_POST['company_website'];
        $company_description = $_POST['company_description'];
        $headline = $bio = $skills = null;
        
        // Handle employer file upload
        $company_logo = $_FILES['company_logo']['name'];
        $company_logo_tmp = $_FILES['company_logo']['tmp_name'];
        $photo = $resume = null;
    }
    
    // Rest of your validation and registration logic...
    if (registerUser($name, $email, $password, $user_type, $photo, $resume, $headline, $bio, $skills, $company_name, $company_website, $company_description, $company_logo)) {
        // Success
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - JobSeekerBD</title>
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
    <!--account section -->
<div class="account-form-container">
    <section class="account-form">
        <form action="" method="post" enctype="multipart/form-data">
            <h3>create new account!</h3>
            <?php if (!empty($message)): ?>
                <div class="message"><?php echo $message; ?></div>
            <?php endif; ?>
            
            <div class="input-group">
                <label>I want to register as:</label>
                <div class="radio-group">
                    <input type="radio" id="job_seeker" name="user_type" value="job_seeker" checked onclick="toggleUserType()">
                    <label for="job_seeker">Job Seeker</label>
                    
                    <input type="radio" id="employer" name="user_type" value="employer" onclick="toggleUserType()">
                    <label for="employer">Employer</label>
                </div>
            </div>
            
            <!-- Common fields for both user types -->
            <input type="text" name="name" required maxlength="50" placeholder="Full Name" class="input">
            <input type="email" name="email" required maxlength="50" placeholder="Email Address" class="input">
            <input type="password" name="password" required maxlength="20" placeholder="Password" class="input">
            <input type="password" name="c_password" required maxlength="20" placeholder="Confirm Password" class="input">
            
            <!-- Job Seeker Specific Fields -->
            <div id="job_seeker_fields">
                <input type="text" name="headline" maxlength="100" placeholder="Professional Headline (e.g. Web Developer)" class="input">
                <textarea name="bio" placeholder="Short Bio (About yourself)" class="input" rows="3"></textarea>
                <input type="text" name="skills" placeholder="Your Skills (comma separated)" class="input">
                
                <div class="file-upload">
                    <label for="photo">Profile Photo:</label>
                    <input type="file" name="photo" id="photo" accept="image/*">
                </div>
                
                <div class="file-upload">
                    <label for="resume">Resume/CV:</label>
                    <input type="file" name="resume" id="resume" accept=".pdf,.doc,.docx">
                </div>
            </div>
            
            <!-- Employer Specific Fields -->
            <div id="employer_fields" style="display:none;">
                <input type="text" name="company_name" maxlength="100" placeholder="Company Name" class="input">
                <input type="text" name="company_website" maxlength="100" placeholder="Company Website" class="input">
                <textarea name="company_description" placeholder="Company Description" class="input" rows="3"></textarea>
                
                <div class="file-upload">
                    <label for="company_logo">Company Logo:</label>
                    <input type="file" name="company_logo" id="company_logo" accept="image/*">
                </div>
            </div>
            
            <p>Already have an account? <a href="login.php">login now</a></p>
            <input type="submit" value="Register Now" name="submit" class="btn">
        </form>
    </section>
</div>

<script>
function toggleUserType() {
    const isJobSeeker = document.getElementById('job_seeker').checked;
    document.getElementById('job_seeker_fields').style.display = isJobSeeker ? 'block' : 'none';
    document.getElementById('employer_fields').style.display = isJobSeeker ? 'none' : 'block';
    
    // Toggle required attributes
    document.getElementById('photo').required = isJobSeeker;
    document.getElementById('resume').required = isJobSeeker;
    document.getElementById('company_logo').required = !isJobSeeker;
}
</script>
    <!--account section end-->

    <!--footer start-->
    <?php include 'includes/footer.php'; ?>
    <!--footer end-->

    <script src="js/script.js"></script>
</body>
</html>