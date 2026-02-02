<?php
/**
 * Registration Page - Jumia Style
 * Makola
 */
require_once 'config/db.php';
require_once 'config/paths.php';
require_once 'includes/auth.php';

$error = '';
$success = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $full_name = trim($_POST['full_name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $role = $_POST['role'] ?? 'buyer';
    
    // Validation
    if (empty($username) || empty($email) || empty($password) || empty($full_name)) {
        $error = 'Please fill in all required fields.';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters long.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email address.';
    } else {
        $conn = getDBConnection();
        
        // Check if username or email already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $error = 'Username or email already exists.';
        } else {
            // Hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // Set seller verification status
            $seller_verified = ($role === 'seller') ? 0 : 1; // Sellers need admin approval
            
            // Insert user
            $stmt = $conn->prepare("INSERT INTO users (username, email, password, full_name, phone, role, seller_verified) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssssi", $username, $email, $hashed_password, $full_name, $phone, $role, $seller_verified);
            
            if ($stmt->execute()) {
                $success = 'Registration successful! ' . ($role === 'seller' ? 'Your seller account is pending approval.' : 'You can now login.');
                // Clear form
                $username = $email = $full_name = $phone = '';
                $role = 'buyer';
            } else {
                $error = 'Registration failed. Please try again.';
            }
        }
        
        $stmt->close();
        closeDBConnection($conn);
    }
}

$pageTitle = 'Register';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?> - Makola</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/css/style.css">
    <link rel="stylesheet" href="<?php echo BASE_PATH; ?>assets/css/auth.css">
</head>
<body class="auth-page">
    <div class="auth-container">
        <!-- Logo at Top -->
        <div class="text-center mb-4">
            <a href="<?php echo BASE_PATH; ?>index.php" class="auth-logo">
                <i class="bi bi-shop"></i>
            </a>
        </div>
        
        <!-- Welcome Text -->
        <h2 class="auth-title text-center mb-2">Welcome to Makola</h2>
        <p class="auth-subtitle text-center mb-4">Create your Makola account to start shopping or selling.</p>
        
        <!-- Error/Success Messages -->
        <?php if ($error): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="alert alert-success" role="alert">
                <?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>
        
        <!-- Registration Form -->
        <form method="POST" action="" class="auth-form">
            <div class="mb-3">
                <label for="role" class="auth-label">
                    Account Type<span class="text-warning">*</span>
                </label>
                <select class="form-select auth-input" id="role" name="role" required>
                    <option value="buyer" <?php echo (isset($role) && $role === 'buyer') ? 'selected' : ''; ?>>Buyer</option>
                    <option value="seller" <?php echo (isset($role) && $role === 'seller') ? 'selected' : ''; ?>>Seller</option>
                </select>
                <small class="text-muted">Sellers need admin approval before they can list products.</small>
            </div>
            
            <div class="mb-3">
                <label for="full_name" class="auth-label">
                    Full Name<span class="text-warning">*</span>
                </label>
                <input type="text" 
                       class="form-control auth-input" 
                       id="full_name" 
                       name="full_name" 
                       placeholder="Enter your full name"
                       value="<?php echo htmlspecialchars($full_name ?? ''); ?>" 
                       required>
            </div>
            
            <div class="mb-3">
                <label for="email" class="auth-label">
                    Email or Mobile Number<span class="text-warning">*</span>
                </label>
                <input type="text" 
                       class="form-control auth-input" 
                       id="email" 
                       name="email" 
                       placeholder="Enter your email or phone number"
                       value="<?php echo htmlspecialchars($email ?? ''); ?>" 
                       required>
            </div>
            
            <div class="mb-3">
                <label for="username" class="auth-label">
                    Username<span class="text-warning">*</span>
                </label>
                <input type="text" 
                       class="form-control auth-input" 
                       id="username" 
                       name="username" 
                       placeholder="Choose a username"
                       value="<?php echo htmlspecialchars($username ?? ''); ?>" 
                       required>
            </div>
            
            <div class="mb-3">
                <label for="phone" class="auth-label">Phone</label>
                <input type="tel" 
                       class="form-control auth-input" 
                       id="phone" 
                       name="phone" 
                       placeholder="Enter your phone number (optional)"
                       value="<?php echo htmlspecialchars($phone ?? ''); ?>">
            </div>
            
            <div class="mb-3">
                <label for="password" class="auth-label">
                    Password<span class="text-warning">*</span>
                </label>
                <input type="password" 
                       class="form-control auth-input" 
                       id="password" 
                       name="password" 
                       placeholder="Create a password (min. 6 characters)"
                       required 
                       minlength="6">
            </div>
            
            <div class="mb-3">
                <label for="confirm_password" class="auth-label">
                    Confirm Password<span class="text-warning">*</span>
                </label>
                <input type="password" 
                       class="form-control auth-input" 
                       id="confirm_password" 
                       name="confirm_password" 
                       placeholder="Confirm your password"
                       required 
                       minlength="6">
            </div>
            
            <button type="submit" class="btn auth-btn-primary w-100 mb-3">
                Continue
            </button>
            
            <p class="auth-terms text-center mb-4">
                By continuing you agree to Makola's 
                <a href="<?php echo BASE_PATH; ?>terms.php" class="auth-link">Terms and Conditions</a>
            </p>
        </form>
        
        <!-- Social Login -->
        <div class="auth-social">
            <button type="button" class="btn auth-btn-facebook w-100 mb-3" onclick="alert('Facebook registration coming soon!')">
                <i class="bi bi-facebook me-2"></i> Sign up with Facebook
            </button>
            <a href="controllers/google_oauth.php?action=login" class="btn btn-danger w-100 mb-3 d-flex align-items-center justify-content-center gap-2" style="background:#fff;color:#444;border:1px solid #ddd;">
                <span style="display:inline-block;width:24px;height:24px;vertical-align:middle;">
                    <!-- Modern Google logo SVG -->
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="24" height="24">
                        <g>
                            <path fill="#4285F4" d="M24 9.5c3.54 0 6.36 1.22 8.29 2.26l6.09-6.09C34.44 2.36 29.64 0 24 0 14.64 0 6.48 5.64 2.69 13.91l7.49 5.82C12.36 13.36 17.73 9.5 24 9.5z"/>
                            <path fill="#34A853" d="M46.1 24.5c0-1.64-.15-3.22-.43-4.74H24v9.01h12.44c-.54 2.91-2.18 5.37-4.64 7.03l7.49 5.82C44.52 37.52 46.1 31.41 46.1 24.5z"/>
                            <path fill="#FBBC05" d="M10.18 28.73c-1.09-3.22-1.09-6.7 0-9.92l-7.49-5.82C.64 17.36 0 20.61 0 24c0 3.39.64 6.64 1.69 9.91l7.49-5.82z"/>
                            <path fill="#EA4335" d="M24 46c5.64 0 10.44-1.86 14.09-5.09l-7.49-5.82c-2.08 1.4-4.74 2.23-7.6 2.23-6.27 0-11.64-3.86-13.82-9.23l-7.49 5.82C6.48 42.36 14.64 48 24 48z"/>
                            <path fill="none" d="M0 0h48v48H0z"/>
                        </g>
                    </svg>
                </span>
                <span>Sign up with Google</span>
            </a>
        </div>
        
        <!-- Support Info -->
        <p class="auth-support text-center mb-4">
            For further support, you may visit the 
            <a href="<?php echo BASE_PATH; ?>help.php" class="auth-link">Help Center</a> 
            or contact our customer service team.
        </p>
        
        <!-- Login Link -->
        <p class="text-center mb-4">
            Already have an account? <a href="<?php echo BASE_PATH; ?>login.php" class="auth-link">Log in here</a>
        </p>
        
        <!-- Logo at Bottom -->
        <div class="text-center mt-5">
            <a href="<?php echo BASE_PATH; ?>index.php" class="auth-logo-bottom">
                <span class="auth-logo-text">MAKOLA</span>
                <i class="bi bi-shop text-warning"></i>
            </a>
        </div>
    </div>
    
    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
