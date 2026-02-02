<?php
session_start();
require_once 'config/db.php';
require_once 'config/paths.php';

// Only allow users who just signed up with Google and have no role set
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$conn = getDBConnection();
$user_id = $_SESSION['user_id'];

// Fetch current role
$stmt = $conn->prepare('SELECT role FROM users WHERE id = ?');
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!$user || !empty($user['role'])) {
    // Already has a role, redirect to homepage
    header('Location: index.php');
    exit();
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $role = $_POST['role'] ?? '';
    if ($role !== 'buyer' && $role !== 'seller') {
        $error = 'Please select a valid role.';
    } else {
        // Update user role
        $stmt = $conn->prepare('UPDATE users SET role = ? WHERE id = ?');
        $stmt->bind_param('si', $role, $user_id);
        if ($stmt->execute()) {
            $_SESSION['user_role'] = $role;
            // If seller, set seller_verified = 0
            if ($role === 'seller') {
                $stmt2 = $conn->prepare('UPDATE users SET seller_verified = 0 WHERE id = ?');
                $stmt2->bind_param('i', $user_id);
                $stmt2->execute();
                $stmt2->close();
            }
            $stmt->close();
            closeDBConnection($conn);
            header('Location: index.php');
            exit();
        } else {
            $error = 'Failed to update role. Please try again.';
        }
    }
}
closeDBConnection($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Choose Account Type - Makola</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-body">
                        <h3 class="mb-4 text-center">Choose Account Type</h3>
                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                        <?php endif; ?>
                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">I am signing up as a:</label>
                                <select name="role" class="form-select" required>
                                    <option value="">-- Select --</option>
                                    <option value="buyer">Buyer</option>
                                    <option value="seller">Seller</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Continue</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
