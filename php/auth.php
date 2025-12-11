<?php
require_once 'functions.php';

// User registration
function registerUser($name, $email, $password, $user_type) {
    global $conn;
    
    $name = sanitize($name);
    $email = sanitize($email);
    $password = password_hash($password, PASSWORD_DEFAULT);
    $user_type = sanitize($user_type);
    
    $sql = "INSERT INTO users (name, email, password, user_type) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $name, $email, $password, $user_type);
    
    if ($stmt->execute()) {
        return true;
    }
    return false;
}

// User login
function loginUser($email, $password) {
    global $conn;
    
    $email = sanitize($email);
    
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_type'] = $user['user_type'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            return true;
        }
    }
    return false;
}

// Check if email exists
function emailExists($email) {
    global $conn;
    
    $email = sanitize($email);
    
    $sql = "SELECT id FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    
    return $stmt->num_rows > 0;
}
?>