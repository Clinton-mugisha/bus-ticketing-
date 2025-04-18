<?php
// Include database connection - fix path issue
require_once __DIR__ . '/../config/db_connect.php';

// Handle login form submission
if (isset($_POST['login_submit'])) {
    $email = $_POST['login_email'] ?? '';
    $password = $_POST['login_password'] ?? '';
    
    // Basic validation
    if (empty($email) || empty($password)) {
        $login_error = "Please fill in all fields";
    } else {
        // Get database connection
        $conn = getDbConnection();
        
        // Prepare statement to prevent SQL injection
        $stmt = $conn->prepare("SELECT id, name, email, password, role FROM users WHERE email = ? OR phone = ?");
        $stmt->bind_param("ss", $email, $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            
            // Verify password
            if (password_verify($password, $user['password'])) {
                // Password is correct, start a new session
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'name' => $user['name'],
                    'email' => $user['email'],
                    'role' => $user['role']
                ];
                
                // If "remember me" is checked, set remember token
                if (isset($_POST['remember']) && $_POST['remember'] == 'on') {
                    $remember_token = bin2hex(random_bytes(32));
                    $stmt = $conn->prepare("UPDATE users SET remember_token = ? WHERE id = ?");
                    $stmt->bind_param("si", $remember_token, $user['id']);
                    $stmt->execute();
                    
                    // Set cookie for 30 days
                    setcookie('remember_token', $remember_token, time() + (86400 * 30), "/");
                    setcookie('user_id', $user['id'], time() + (86400 * 30), "/");
                }
                
                // Close statement and connection
                $stmt->close();
                $conn->close();
                
                // Redirect to avoid form resubmission
                header("Location: " . BASE_URL);
                exit;
            } else {
                $login_error = "Invalid email/phone or password";
            }
        } else {
            $login_error = "Invalid email/phone or password";
        }
        
        // Close statement and connection
        $stmt->close();
        $conn->close();
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
        try {
            // Get database connection
            $conn = getDbConnection();
            
            // Set charset to prevent "weird" data
            $conn->set_charset("utf8mb4");
            
            // Check if email or phone already exists
            $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? OR phone = ?");
            if (!$stmt) {
                throw new Exception("Prepare failed: " . $conn->error);
            }
            
            $stmt->bind_param("ss", $email, $phone);
            if (!$stmt->execute()) {
                throw new Exception("Execute failed: " . $stmt->error);
            }
            
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                $signup_error = "Email or phone number already registered";
            } else {
                // Hash the password
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                
                // Close previous statement
                $stmt->close();
                
                // Insert new user
                $stmt = $conn->prepare("INSERT INTO users (name, email, phone, password) VALUES (?, ?, ?, ?)");
                if (!$stmt) {
                    throw new Exception("Prepare failed: " . $conn->error);
                }
                
                $stmt->bind_param("ssss", $name, $email, $phone, $hashed_password);
                
                if ($stmt->execute()) {
                    $user_id = $conn->insert_id;
                    
                    // Set session variables for the new user
                    $_SESSION['user'] = [
                        'id' => $user_id,
                        'name' => $name,
                        'email' => $email,
                        'role' => 'user'
                    ];
                    
                    // Close statement and connection
                    $stmt->close();
                    $conn->close();
                    
                    // Redirect to avoid form resubmission
                    header("Location: " . BASE_URL);
                    exit;
                } else {
                    throw new Exception("Insert failed: " . $stmt->error);
                }
            }
            
            // Close statement and connection
            $stmt->close();
            $conn->close();
            
        } catch (Exception $e) {
            // Log the error and show a user-friendly message
            error_log("Signup error: " . $e->getMessage());
            $signup_error = "Registration failed. Please try again later.";
        }
    }
}

// Handle logout
if (isset($_GET['logout'])) {
    // Clear session
    session_destroy();
    
    // Clear cookies if set
    if (isset($_COOKIE['remember_token']) && isset($_COOKIE['user_id'])) {
        // Clear remember token in database
        $conn = getDbConnection();
        $stmt = $conn->prepare("UPDATE users SET remember_token = NULL WHERE id = ?");
        $stmt->bind_param("i", $_COOKIE['user_id']);
        $stmt->execute();
        $stmt->close();
        $conn->close();
        
        // Delete cookies
        setcookie('remember_token', '', time() - 3600, '/');
        setcookie('user_id', '', time() - 3600, '/');
    }
    
    header("Location: " . BASE_URL);
    exit;
}

// Check for "remember me" cookie on page load
if (!isset($_SESSION['user']) && isset($_COOKIE['remember_token']) && isset($_COOKIE['user_id'])) {
    $conn = getDbConnection();
    $stmt = $conn->prepare("SELECT id, name, email, role, remember_token FROM users WHERE id = ?");
    $stmt->bind_param("i", $_COOKIE['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
        // Verify remember token
        if ($user['remember_token'] === $_COOKIE['remember_token']) {
            $_SESSION['user'] = [
                'id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email'],
                'role' => $user['role']
            ];
        } else {
            // Invalid remember token, clear cookies
            setcookie('remember_token', '', time() - 3600, '/');
            setcookie('user_id', '', time() - 3600, '/');
        }
    }
    
    $stmt->close();
    $conn->close();
}
?>