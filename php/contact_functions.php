<?php
require_once 'functions.php';

function submitContactForm($name, $email, $phone, $subject, $message, $role) {
    global $conn;
    
    $name = sanitize($name);
    $email = sanitize($email);
    $phone = sanitize($phone);
    $subject = sanitize($subject);
    $message = sanitize($message);
    $role = sanitize($role);
    
    $sql = "INSERT INTO contacts (name, email, phone, subject, message, role) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $name, $email, $phone, $subject, $message, $role);
    
    return $stmt->execute();
}

function getAllContacts() {
    global $conn;
    
    $sql = "SELECT * FROM contacts ORDER BY created_at DESC";
    $result = $conn->query($sql);
    
    return $result->num_rows > 0 ? $result : null;
}
?>