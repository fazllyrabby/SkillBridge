<?php
require_once 'config.php';

// Function to sanitize input data
function sanitize($data) {
    global $conn;
    return htmlspecialchars(strip_tags(trim($conn->real_escape_string($data))));
}

// Function to redirect with message
function redirect($url, $message = null) {
    if ($message) {
        $_SESSION['message'] = $message;
    }
    header("Location: $url");
    exit();
}

// Function to display messages
function displayMessage() {
    if (isset($_SESSION['message'])) {
        echo '<div class="message">' . $_SESSION['message'] . '</div>';
        unset($_SESSION['message']);
    }
}

// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Check if user is an employer
function isEmployer() {
    return isLoggedIn() && $_SESSION['user_type'] === 'employer';
}

// Check if user is a job seeker
function isJobSeeker() {
    return isLoggedIn() && $_SESSION['user_type'] === 'job_seeker';
}

// Get current user ID
function getUserId() {
    return isLoggedIn() ? $_SESSION['user_id'] : null;
}

function calculateProfileCompleteness($user_id) {
    global $conn;
    $total_fields = 8; // Adjust based on your profile fields
    $completed_fields = 0;
    
    $stmt = $conn->prepare("SELECT headline, bio, skills, photo, resume FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    
    foreach ($user as $field) {
        if (!empty($field)) $completed_fields++;
    }
    
    return round(($completed_fields / $total_fields) * 100);
}

function getCompanyFollowersCount($company_id) {
    global $conn;
    
    $company_id = (int)$company_id;
    $sql = "SELECT COUNT(*) as count FROM company_followers WHERE company_id = $company_id";
    
    $result = $conn->query($sql);
    if (!$result) {
        error_log("Query failed: " . $conn->error);
        return 0;
    }
    
    $row = $result->fetch_assoc();
    return $row ? (int)$row['count'] : 0;
}

function getCompanyByUserId($user_id) {
    global $conn;
    
    $stmt = $conn->prepare("SELECT * FROM companies WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    return $result->fetch_assoc(); // Returns company data or null if not found
}

// For job seekers
// function getUserApplications($user_id) {
//     global $conn;
//     $stmt = $conn->prepare("SELECT a.*, j.title as job_title, c.name as company_name 
//                            FROM applications a 
//                            JOIN jobs j ON a.job_id = j.id 
//                            JOIN companies c ON j.company_id = c.id 
//                            WHERE a.user_id = ?");
//     $stmt->bind_param("i", $user_id);
//     $stmt->execute();
//     return $stmt->get_result();
// }

// // Remove these lines (105-111):
// function getSavedJobs($user_id) {
//     global $conn;
//     $stmt = $conn->prepare("SELECT j.*, c.name as company_name 
//                            FROM saved_jobs s 
//                            JOIN jobs j ON s.job_id = j.id 
//                            JOIN companies c ON j.company_id = c.id 
//                            WHERE s.user_id = ?");
//     $stmt->bind_param("i", $user_id);
//     $stmt->execute();
//     return $stmt->get_result();
// }

// // For employers
// function getJobsPostedByEmployer($user_id) {
//     global $conn;
//     $stmt = $conn->prepare("SELECT j.*, 
//                            (SELECT COUNT(*) FROM applications WHERE job_id = j.id) as application_count 
//                            FROM jobs j 
//                            JOIN companies c ON j.company_id = c.id 
//                            WHERE c.user_id = ?");
//     $stmt->bind_param("i", $user_id);
//     $stmt->execute();
//     return $stmt->get_result();
// }

// function getCompanyStatistics($company_id) {
//     global $conn;
//     $stats = [
//         'total_views' => 0,
//         'followers' => 0,
//         'jobs_posted' => 0,
//         'total_applications' => 0,
//         'new_applications' => 0,
//         'hired_candidates' => 0,
//         'profile_completion' => 80 // Example value
//     ];
    
//     // Implement actual queries to get these values
//     return $stats;
// }

// function uploadCompanyLogo($file) {
//     // Implement logo upload logic
//     return 'default-logo.png'; // Return filename
// }
// ?>