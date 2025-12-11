<?php
require_once 'functions.php';

// Register a company
function registerCompany($name, $description, $location, $established_date, $website, $user_id) {
    global $conn;
    
    $name = sanitize($name);
    $description = sanitize($description);
    $location = sanitize($location);
    $established_date = sanitize($established_date);
    $website = sanitize($website);
    $user_id = (int)$user_id;
    
    $sql = "INSERT INTO companies (name, description, location, established_date, website, user_id) 
            VALUES (?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssi", $name, $description, $location, $established_date, $website, $user_id);
    
    if ($stmt->execute()) {
        return $conn->insert_id;
    }
    return false;
}

// Get company by user ID


// Update company information
function updateCompany($id, $name, $description, $location, $established_date, $website) {
    global $conn;
    
    $id = (int)$id;
    $name = sanitize($name);
    $description = sanitize($description);
    $location = sanitize($location);
    $established_date = sanitize($established_date);
    $website = sanitize($website);
    
    $sql = "UPDATE companies SET 
            name = ?, 
            description = ?, 
            location = ?, 
            established_date = ?, 
            website = ? 
            WHERE id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssi", $name, $description, $location, $established_date, $website, $id);
    
    return $stmt->execute();
}

// Upload company logo
function uploadCompanyLogo($company_id, $logo) {
    global $conn;
    
    $company_id = (int)$company_id;
    $target_dir = "../uploads/logos/";
    $target_file = $target_dir . basename($logo["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    
    // Check if image file is a actual image
    $check = getimagesize($logo["tmp_name"]);
    if ($check === false) {
        return false;
    }
    
    // Check file size (max 2MB)
    if ($logo["size"] > 2000000) {
        return false;
    }
    
    // Allow certain file formats
    if (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
        return false;
    }
    
    // Generate unique filename
    $filename = "company_" . $company_id . "_" . time() . "." . $imageFileType;
    $target_file = $target_dir . $filename;
    
    if (move_uploaded_file($logo["tmp_name"], $target_file)) {
        // Update database with new logo path
        $sql = "UPDATE companies SET logo = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $logo_path = "uploads/logos/" . $filename;
        $stmt->bind_param("si", $logo_path, $company_id);
        
        if ($stmt->execute()) {
            return $logo_path;
        }
    }
    
    return false;
}

// Get company reviews
function getCompanyReviews($company_id) {
    global $conn;
    
    $company_id = (int)$company_id;
    $sql = "SELECT r.*, u.name AS user_name 
            FROM reviews r
            JOIN users u ON r.user_id = u.id
            WHERE r.company_id = ?
            ORDER BY r.created_at DESC";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $company_id);
    $stmt->execute();
    return $stmt->get_result();
}

// Add company review
function addCompanyReview($company_id, $user_id, $rating, $comment) {
    global $conn;
    
    $company_id = (int)$company_id;
    $user_id = (int)$user_id;
    $rating = (float)$rating;
    $comment = sanitize($comment);
    
    $sql = "INSERT INTO reviews (company_id, user_id, rating, comment) 
            VALUES (?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iids", $company_id, $user_id, $rating, $comment);
    
    return $stmt->execute();
}

// Get average company rating
function getCompanyAverageRating($company_id) {
    global $conn;
    
    $company_id = (int)$company_id;
    $sql = "SELECT AVG(rating) AS avg_rating FROM reviews WHERE company_id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $company_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        return round($row['avg_rating'], 1);
    }
    return 0;
}

function getTopReviews($limit = 3) {
    global $conn;
    $sql = "SELECT r.*, u.name AS user_name, u.photo AS user_photo 
            FROM reviews r
            JOIN users u ON r.user_id = u.id
            ORDER BY r.created_at DESC
            LIMIT ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $limit);
    $stmt->execute();
    return $stmt->get_result();
}

function getTotalJobCount() {
    global $conn;
    $sql = "SELECT COUNT(*) as count FROM jobs";
    $result = $conn->query($sql);
    return $result->num_rows > 0 ? number_format($result->fetch_assoc()['count']) : '0';
}

function getTotalCompanyCount() {
    global $conn;
    $sql = "SELECT COUNT(*) as count FROM companies";
    $result = $conn->query($sql);
    return $result->num_rows > 0 ? number_format($result->fetch_assoc()['count']) : '0';
}

function getTotalUserCount() {
    global $conn;
    $sql = "SELECT COUNT(*) as count FROM users";
    $result = $conn->query($sql);
    return $result->num_rows > 0 ? number_format($result->fetch_assoc()['count']) : '0';
}

function getTotalApplicationCount() {
    global $conn;
    $sql = "SELECT COUNT(*) as count FROM applications";
    $result = $conn->query($sql);
    return $result->num_rows > 0 ? number_format($result->fetch_assoc()['count']) : '0';
}


?>