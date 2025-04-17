<?php
// Handle login form submission
if (isset($_POST['login_submit'])) {
    $email = $_POST['login_email'] ?? '';
    $password = $_POST['login_password'] ?? '';
    
    // Basic validation
    if (empty($email) || empty($password)) {
        $login_error = "Please fill in all fields";
    } else {
        // In a real app, you would verify credentials against the database
        // For demo, just set session variable
        $_SESSION['user'] = [
            'name' => 'Demo User',
            'email' => $email
        ];
        
        // Redirect to avoid form resubmission
        header("Location: " . BASE_URL);
        exit;
    }
}

// Handle signup form submission
if (isset($_POST['signup_submit'])) {
    $name = $_POST['signup_name'] ?? '';
    $email = $_POST['signup_email'] ?? '';
    $phone = $_POST['signup_phone'] ?? '';
    $password = $_POST['signup_password'] ?? '';
    $confirm = $_POST['signup_confirm'] ?? '';
    
    // Basic validation
    if (empty($name) || empty($email) || empty($phone) || empty($password) || empty($confirm)) {
        $signup_error = "Please fill in all fields";
    } elseif ($password !== $confirm) {
        $signup_error = "Passwords do not match";
    } else {
        // In a real app, you would store the user in the database
        // For demo, just set session variable
        $_SESSION['user'] = [
            'name' => $name,
            'email' => $email,
            'phone' => $phone
        ];
        
        // Redirect to avoid form resubmission
        header("Location: " . BASE_URL);
        exit;
    }
}

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: " . BASE_URL);
    exit;
}
?>