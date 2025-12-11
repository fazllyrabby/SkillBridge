<?php
require_once 'php/functions.php';
require_once 'php/job_functions.php';

if (!isLoggedIn()) {
    redirect('login.php', 'Please login to save jobs');
}

// Only handle POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['job_id'])) {
        redirect('jobs.php', 'No job specified to save');
    }

    $job_id = (int)$_POST['job_id'];
    $user_id = getUserId();
    
    if (saveJob($job_id, $user_id)) {
        redirect($_SERVER['HTTP_REFERER'] ?? 'jobs.php', 'Job saved successfully!');
    } else {
        redirect($_SERVER['HTTP_REFERER'] ?? 'jobs.php', 'Job already saved!');
    }
}

// Default redirect if not a POST request
redirect('jobs.php');
?>