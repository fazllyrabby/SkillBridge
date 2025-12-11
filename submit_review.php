<?php
require_once 'php/functions.php';
require_once 'php/company_functions.php';

if (!isLoggedIn()) {
    redirect('login.php', 'Please login to submit a review');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $company_id = isset($_POST['company_id']) ? (int)$_POST['company_id'] : 0;
    $rating = isset($_POST['rating']) ? (float)$_POST['rating'] : 0;
    $comment = isset($_POST['comment']) ? $_POST['comment'] : '';
    $user_id = getUserId();
    
    if ($company_id > 0 && $rating > 0 && $rating <= 5) {
        if (addCompanyReview($company_id, $user_id, $rating, $comment)) {
            redirect('view_company.php?id=' . $company_id, 'Review submitted successfully!');
        } else {
            redirect('view_company.php?id=' . $company_id, 'Failed to submit review!');
        }
    }
}

// If not POST or invalid data, redirect back
redirect('jobs.php');
?>