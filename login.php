<?php
/**
 * Login Page - Jumia Style
 * Makola
 */
require_once 'config/db.php';
require_once 'config/paths.php';
require_once 'includes/auth.php';

// Redirect if already logged in
if (isLoggedIn()) {
    redirectByRole();
}

$error = '';
$success = '';

// Check if user was logged out
if (isset($_GET['logged_out'])) {
    $success = 'You have been successfully logged out.';
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($email) || empty($password)) {
        $error = 'Please enter both email and password.';
    } else {
        $conn = getDBConnection();
        
        // Get user by email
        $stmt = $conn->prepare("SELECT id, username, email, password, role, seller_verified FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            
            // Verify password
            if (password_verify($password, $user['password'])) {
                // Set session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['user_role'] = $user['role'];
                $_SESSION['seller_verified'] = $user['seller_verified'];
                
                // Redirect based on role
                redirectByRole();
            } else {
                $error = 'Invalid email or password.';
            }
        } else {
            $error = 'Invalid email or password.';
        }
        
        $stmt->close();
        closeDBConnection($conn);
    }
}

$pageTitle = 'Login';
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
        <p class="auth-subtitle text-center mb-4">Type your e-mail or phone number to log in or create a Makola account.</p>
        
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
        
        <!-- Login Form -->
        <form method="POST" action="" class="auth-form">
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
                       required 
                       autofocus>
            </div>
            
            <div class="mb-3">
                <label for="password" class="auth-label">
                    Password<span class="text-warning">*</span>
                </label>
                <input type="password" 
                       class="form-control auth-input" 
                       id="password" 
                       name="password" 
                       placeholder="Enter your password"
                       required>
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
            <button type="button" class="btn auth-btn-facebook w-100 mb-3" onclick="alert('Facebook login coming soon!')">
                <i class="bi bi-facebook me-2"></i> Log in with Facebook
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
                <span>Login with Google</span>
            </a>
        </div>
        
        <!-- Support Info -->
        <p class="auth-support text-center mb-4">
            For further support, you may visit the 
            <a href="<?php echo BASE_PATH; ?>help.php" class="auth-link">Help Center</a> 
            or contact our customer service team.
        </p>
        
        <!-- Register Link -->
        <p class="text-center mb-4">
            <a href="<?php echo BASE_PATH; ?>register.php" class="auth-link">Create a new account</a>
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
