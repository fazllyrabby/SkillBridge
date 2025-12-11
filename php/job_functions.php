<?php
require_once 'functions.php';

// Get all jobs
function getAllJobs($limit = null) {
    global $pdo;
    
    // Validate PDO connection
    if (!($pdo instanceof PDO)) {
        error_log("Invalid PDO connection in getAllJobs()");
        return [];
    }

    try {
        // Base SQL query
        $sql = "SELECT j.*, c.name AS company_name, c.logo AS company_logo 
                FROM jobs j 
                JOIN companies c ON j.company_id = c.id 
                WHERE j.is_active = 1
                ORDER BY j.posted_at DESC";
        
        // Prepare statement
        $stmt = $pdo->prepare($sql);
        if ($stmt === false) {
            throw new PDOException("Failed to prepare SQL query");
        }

        // Handle limit parameter
        if ($limit !== null) {
            $limit = filter_var($limit, FILTER_VALIDATE_INT, [
                'options' => ['min_range' => 1]
            ]);
            if ($limit === false) {
                throw new InvalidArgumentException("Invalid limit value");
            }
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        }

        // Execute query
        if (!$stmt->execute()) {
            throw new PDOException("Failed to execute query");
        }

        // Fetch results
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return is_array($results) ? $results : [];

    } catch (PDOException $e) {
        error_log("PDO Error in getAllJobs(): " . $e->getMessage() . "\nSQL: " . ($sql ?? ''));
        return [];
    } catch (Exception $e) {
        error_log("General Error in getAllJobs(): " . $e->getMessage());
        return [];
    }
}

// Get job by ID
function getJobById($id) {
    global $conn;
    
    $id = (int)$id;
    $sql = "SELECT j.*, c.name AS company_name, c.description AS company_description, 
                   c.location AS company_location, c.logo AS company_logo
            FROM jobs j 
            JOIN companies c ON j.company_id = c.id 
            WHERE j.id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    return $result->num_rows === 1 ? $result->fetch_assoc() : null;
}

// Get jobs by company
function getJobsByCompany($company_id) {
    global $conn;
    
    // Input validation
    $company_id = (int)$company_id;
    if ($company_id <= 0) return false;
    
    // Verify connection
    if (!$conn || $conn->connect_error) return false;
    
    try {
        $sql = "SELECT j.* FROM jobs j WHERE j.company_id = ? ORDER BY j.posted_at DESC";
        $stmt = $conn->prepare($sql);
        
        if (!$stmt) return false;
        
        $stmt->bind_param("i", $company_id);
        $stmt->execute();
        
        return $stmt->get_result();
    } catch (Exception $e) {
        error_log("Database error: " . $e->getMessage());
        return false;
    }
}

// Get company by ID
function getCompanyById($id) {
    global $conn;
    
    $id = (int)$id;
    $sql = "SELECT * FROM companies WHERE id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    return $result->num_rows === 1 ? $result->fetch_assoc() : null;
}

// Get all categories
function getAllCategories() {
    global $conn;
    
    $sql = "SELECT * FROM job_categories";
    $result = $conn->query($sql);
    
    return $result->num_rows > 0 ? $result : null;
}

// Post a new job
function postJob($title, $description, $requirements, $salary_range, $job_type, $location, $company_id, $posted_by, $deadline = null) {
    global $conn;
    
    $title = sanitize($title);
    $description = sanitize($description);
    $requirements = sanitize($requirements);
    $salary_range = sanitize($salary_range);
    $job_type = sanitize($job_type);
    $location = sanitize($location);
    $company_id = (int)$company_id;
    $posted_by = (int)$posted_by;
    
    $sql = "INSERT INTO jobs (title, description, requirements, salary_range, job_type, location, company_id, posted_by, deadline) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssiis", $title, $description, $requirements, $salary_range, $job_type, $location, $company_id, $posted_by, $deadline);
    
    if ($stmt->execute()) {
        return $conn->insert_id;
    }
    return false;
}

// Search jobs
function searchJobs($keyword = null, $location = null, $category = null, $job_type = null) {
    global $conn;
    
    $sql = "SELECT j.*, c.name AS company_name, c.logo AS company_logo 
            FROM jobs j 
            JOIN companies c ON j.company_id = c.id 
            WHERE 1=1";
    
    $params = [];
    $types = "";
    
    if ($keyword) {
        $sql .= " AND (j.title LIKE ? OR j.description LIKE ? OR c.name LIKE ?)";
        $keyword = "%" . sanitize($keyword) . "%";
        $params[] = $keyword;
        $params[] = $keyword;
        $params[] = $keyword;
        $types .= "sss";
    }
    
    if ($location) {
        $sql .= " AND j.location LIKE ?";
        $location = "%" . sanitize($location) . "%";
        $params[] = $location;
        $types .= "s";
    }
    
    if ($job_type) {
        $sql .= " AND j.job_type = ?";
        $job_type = sanitize($job_type);
        $params[] = $job_type;
        $types .= "s";
    }
    
    if ($category) {
        $sql .= " AND j.id IN (SELECT job_id FROM job_category_mapping WHERE category_id = ?)";
        $category = (int)$category;
        $params[] = $category;
        $types .= "i";
    }
    
    // Fix: Replace 'posted_at' with the correct column (e.g., 'created_at')
    $sql .= " ORDER BY j.created_at DESC"; // ⚠️ Change this to your actual column!
    
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("SQL Error: " . $conn->error);
    }
    
    if (!empty($params)) {
        if (!$stmt->bind_param($types, ...$params)) {
            die("Bind error: " . $stmt->error);
        }
    }
    
    if (!$stmt->execute()) {
        die("Execute error: " . $stmt->error);
    }
    
    return $stmt->get_result();
}

// Apply for a job
function applyForJob($job_id, $user_id) {
    global $conn;
    
    $job_id = (int)$job_id;
    $user_id = (int)$user_id;
    
    // Check if already applied
    $sql = "SELECT id FROM applications WHERE job_id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $job_id, $user_id);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        return false; // Already applied
    }
    
    // Apply for job
    $sql = "INSERT INTO applications (job_id, user_id) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $job_id, $user_id);
    
    return $stmt->execute();
}

// Save a job
function saveJob($job_id, $user_id) {
    global $conn;
    
    $job_id = (int)$job_id;
    $user_id = (int)$user_id;
    
    // Check if already saved
    $sql = "SELECT * FROM saved_jobs WHERE job_id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $job_id, $user_id);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        return false; // Already saved
    }
    
    // Save job
    $sql = "INSERT INTO saved_jobs (job_id, user_id) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $job_id, $user_id);
    
    return $stmt->execute();
}

// Get saved jobs for user

// Get saved jobs for user
function getSavedJobs($user_id) {
    global $conn;
    $stmt = $conn->prepare("SELECT j.*, c.name as company_name 
                           FROM saved_jobs s 
                           JOIN jobs j ON s.job_id = j.id 
                           JOIN companies c ON j.company_id = c.id 
                           WHERE s.user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    return $stmt->get_result();
}

// Get applications for user
function getUserApplications($user_id) {
    global $conn;
    
    $user_id = (int)$user_id;
    $sql = "SELECT a.*, j.title AS job_title, j.location AS job_location, 
                   c.name AS company_name, c.logo AS company_logo
            FROM applications a
            JOIN jobs j ON a.job_id = j.id
            JOIN companies c ON j.company_id = c.id
            WHERE a.user_id = ?
            ORDER BY a.application_date DESC";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    return $stmt->get_result();
}

// Get jobs posted by employer
function getJobsPostedByEmployer($user_id) {
    global $conn;
    
    // Validate connection
    if (!$conn || $conn->connect_error) {
        error_log("Database connection error");
        return false;
    }
    
    // Validate input
    $user_id = (int)$user_id;
    if ($user_id <= 0) {
        error_log("Invalid user ID");
        return false;
    }
    
    // Verify table structure
    $sql = "SELECT j.*, c.name AS company_name, c.logo AS company_logo
            FROM jobs j
            JOIN companies c ON j.company_id = c.id
            WHERE j.posted_by = ?
            ORDER BY j.posted_at DESC";
    
    // Prepare statement
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        error_log("Prepare error: " . $conn->error);
        return false;
    }
    
    // Bind parameters
    $bound = $stmt->bind_param("i", $user_id);
    if (!$bound) {
        error_log("Bind error: " . $stmt->error);
        return false;
    }
    
    // Execute
    if (!$stmt->execute()) {
        error_log("Execute error: " . $stmt->error);
        return false;
    }
    
    // Get result
    $result = $stmt->get_result();
    if (!$result) {
        error_log("Result error: " . $stmt->error);
        return false;
    }
    
    return $result;
}

// Get applications for employer's jobs
function getApplicationsForEmployer($user_id) {
    global $conn;
    
    $user_id = (int)$user_id;
    $sql = "SELECT a.*, j.title AS job_title, u.name AS applicant_name, 
                   u.email AS applicant_email
            FROM applications a
            JOIN jobs j ON a.job_id = j.id
            JOIN users u ON a.user_id = u.id
            WHERE j.posted_by = ?
            ORDER BY a.application_date DESC";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    return $stmt->get_result();
}

// Update application status
function updateApplicationStatus($application_id, $status) {
    global $conn;
    
    $application_id = (int)$application_id;
    $status = sanitize($status);
    
    $sql = "UPDATE applications SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $application_id);
    
    return $stmt->execute();
}


function getCategoryName($category_id) {
    global $conn;
    $sql = "SELECT name FROM job_categories WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $category_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows === 1 ? $result->fetch_assoc()['name'] : '';
}



?>