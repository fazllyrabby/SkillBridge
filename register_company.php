<?php
require_once 'php/functions.php';
require_once 'php/company_functions.php';

if (!isLoggedIn() || !isEmployer()) {
    redirect('login.php', 'Please login as an employer to register a company');
}

$message = '';

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $location = $_POST['location'];
    $established_date = $_POST['established_date'];
    $website = $_POST['website'];
    $user_id = getUserId();
    
    // Handle logo upload
    $logo = null;
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
        $logo = $_FILES['logo'];
    }
    
    $company_id = registerCompany($name, $description, $location, $established_date, $website, $user_id);
    
    if ($company_id) {
        if ($logo) {
            $logo_path = uploadCompanyLogo($company_id, $logo);
        }
        redirect('view_company.php?id=' . $company_id, 'Company registered successfully!');
    } else {
        $message = 'Failed to register company!';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Company - SkillBridge</title>
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

    <section class="company-registration">
        <h1 class="heading">register your company</h1>
        <form action="" method="post" enctype="multipart/form-data">
            <?php if (!empty($message)): ?>
                <div class="message"><?php echo $message; ?></div>
            <?php endif; ?>
            
            <h3>company information</h3>
            <div class="input">
                <span>company name *</span>
                <input type="text" name="name" required maxlength="100" placeholder="enter company name" class="input">
            </div>
            
            <div class="input">
                <span>company description *</span>
                <textarea name="description" required maxlength="2000" placeholder="enter company description" class="input"></textarea>
            </div>
            
            <div class="input">
                <span>company location *</span>
                <input type="text" name="location" required maxlength="255" placeholder="enter company location" class="input">
            </div>
            
            <div class="flex">
                <div class="input">
                    <span>established date</span>
                    <input type="date" name="established_date" class="input">
                </div>
                
                <div class="input">
                    <span>website</span>
                    <input type="url" name="website" maxlength="255" placeholder="enter company website" class="input">
                </div>
            </div>
            
            <div class="input">
                <span>company logo</span>
                <input type="file" name="logo" accept="image/*" class="input">
                <p class="note">Max size: 2MB (JPG, PNG, GIF)</p>
            </div>
            
            <input type="submit" value="Register Company" name="submit" class="btn">
        </form>
    </section>

    <style>

        /* ...existing code... */

.company-registration {
    max-width: 500px;
    margin: 40px auto;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 4px 24px rgba(0,0,0,0.08);
    padding: 32px 28px;
    font-family: 'Segoe UI', Arial, sans-serif;
}

.company-registration .heading {
    text-align: center;
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 18px;
    color: #2d3a4b;
    letter-spacing: 1px;
}

.company-registration h3 {
    font-size: 1.15rem;
    color: #4a5a6a;
    margin-bottom: 18px;
    font-weight: 600;
    letter-spacing: 0.5px;
}

.company-registration .input {
    margin-bottom: 18px;
    display: flex;
    flex-direction: column;
}

.company-registration .input span {
    font-size: 1rem;
    color: #3b4a5a;
    margin-bottom: 6px;
    font-weight: 500;
}

.company-registration .input input[type="text"],
.company-registration .input input[type="url"],
.company-registration .input input[type="date"],
.company-registration .input input[type="file"],
.company-registration .input textarea {
    padding: 10px 12px;
    border: 1px solid #d1d9e6;
    border-radius: 6px;
    font-size: 1rem;
    background: #f7f9fb;
    transition: border 0.2s;
    outline: none;
}

.company-registration .input input[type="file"] {
    padding: 7px 0;
    background: #f7f9fb;
}

.company-registration .input input:focus,
.company-registration .input textarea:focus {
    border-color: #4f8cff;
    background: #fff;
}

.company-registration .input textarea {
    min-height: 80px;
    resize: vertical;
}

.company-registration .note {
    font-size: 0.92rem;
    color: #7a8a99;
    margin-top: 4px;
}

.company-registration .flex {
    display: flex;
    gap: 18px;
}

.company-registration .flex .input {
    flex: 1;
}

.company-registration .btn {
    width: 100%;
    padding: 12px 0;
    background: linear-gradient(90deg, #4f8cff 0%, #38b6ff 100%);
    color: #fff;
    font-size: 1.1rem;
    font-weight: 600;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    transition: background 0.2s;
    margin-top: 10px;
    box-shadow: 0 2px 8px rgba(79,140,255,0.08);
}

.company-registration .btn:hover {
    background: linear-gradient(90deg, #38b6ff 0%, #4f8cff 100%);
}

.company-registration .message {
    background: #eaf6ff;
    color: #2176bd;
    border: 1px solid #b6e0fe;
    border-radius: 5px;
    padding: 10px 14px;
    margin-bottom: 18px;
    font-size: 1rem;
    text-align: center;
}

/* Responsive */
@media (max-width: 600px) {
    .company-registration {
        padding: 18px 8px;
    }
    .company-registration .flex {
        flex-direction: column;
        gap: 0;
    }
}

/* ...existing code... */
    </style>

    <!--footer start-->
    <?php include 'includes/footer.php'; ?>
    <!--footer end-->

    <script src="js/script.js"></script>
</body>
</html>