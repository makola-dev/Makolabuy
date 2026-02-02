<?php
/**
 * User Profile Page - Complete E-commerce Profile
 * Makola
 */
require_once 'config/db.php';
require_once 'config/paths.php';
require_once 'includes/auth.php';

$conn = getDBConnection();

// Check if user is logged in
if (!isLoggedIn()) {
    $base = defined('BASE_PATH') ? BASE_PATH : '/Makola/';
    header('Location: ' . $base . 'login.php');
    exit();
}

$user_id = getUserId();
$user_role = getUserRole();

// Get user information
$user_stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$user_stmt->bind_param("i", $user_id);
$user_stmt->execute();
$user = $user_stmt->get_result()->fetch_assoc();
$user_stmt->close();

if (!$user) {
    $base = defined('BASE_PATH') ? BASE_PATH : '/Makola/';
    header('Location: ' . $base . 'login.php');
    exit();
}

// Get order count for buyers
$order_count = 0;
if ($user_role === 'buyer') {
    $order_count_stmt = $conn->prepare("SELECT COUNT(*) as count FROM orders WHERE buyer_id = ? AND order_status != 'cancelled'");
    $order_count_stmt->bind_param("i", $user_id);
    $order_count_stmt->execute();
    $order_count = $order_count_stmt->get_result()->fetch_assoc()['count'];
    $order_count_stmt->close();
}

$pageTitle = 'My Profile';
include 'includes/header.php';
?>

<!-- Profile Summary Card - Top Section -->
<div class="profile-summary-card">
    <div class="profile-summary-content">
        <div class="profile-summary-avatar">
            <?php if (!empty($user['profile_image'])): ?>
                <img src="<?php echo BASE_PATH; ?>assets/img/avatars/<?php echo htmlspecialchars($user['profile_image']); ?>" 
                     alt="Profile Picture"
                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
            <?php endif; ?>
            <div class="avatar-initials" style="<?php echo !empty($user['profile_image']) ? 'display: none;' : ''; ?>">
                <?php echo strtoupper(substr($user['full_name'] ?? $user['username'] ?? 'U', 0, 1)); ?>
            </div>
        </div>
        <div class="profile-summary-info">
            <h1 class="profile-summary-name">
                <?php echo htmlspecialchars($user['full_name'] ?? $user['username'] ?? 'User'); ?>
            </h1>
            <div class="profile-summary-email">
                <i class="bi bi-envelope"></i>
                <?php echo htmlspecialchars($user['email'] ?? ''); ?>
            </div>
            <?php if ($user_role === 'buyer'): ?>
            <div class="profile-summary-stats" style="display: flex; gap: 1.5rem; flex-wrap: wrap; margin-top: 0.5rem;">
                <div style="color: rgba(255,255,255,0.9);">
                    <strong><?php echo number_format($order_count); ?></strong> Orders
                </div>
            </div>
            <?php endif; ?>
        </div>
        <div class="profile-summary-actions">
            <button class="btn btn-light" onclick="document.getElementById('profile_image').click()" aria-label="Change profile picture">
                <i class="bi bi-camera me-2"></i>Change Photo
            </button>
        </div>
    </div>
</div>

<div class="profile-page">
    <!-- Profile Details Section -->
    <div class="profile-section-card">
        <div class="profile-section-header">
            <h2 class="profile-section-title">
                <i class="bi bi-person"></i>
                Profile Details
            </h2>
            <div class="profile-section-actions">
                <button type="button" class="edit-toggle-btn" id="profileEditBtn" aria-label="Edit profile">
                    <i class="bi bi-pencil"></i> Edit
                </button>
            </div>
        </div>
        <div class="profile-section-body">
            <form id="profileForm" class="profile-edit-form">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="profileUsername" class="form-label">Username</label>
                            <input type="text" class="form-control" id="profileUsername" name="username" 
                                   value="<?php echo htmlspecialchars($user['username'] ?? ''); ?>" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="profileEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="profileEmail" name="email" 
                                   value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="profileFullName" class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="profileFullName" name="full_name" 
                                   value="<?php echo htmlspecialchars($user['full_name'] ?? ''); ?>" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="profilePhone" class="form-label">Phone Number</label>
                            <input type="tel" class="form-control" id="profilePhone" name="phone" 
                                   value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>">
                        </div>
                    </div>
                </div>
                <div class="profile-edit-actions">
                    <button type="submit" class="btn btn-primary save-btn">
                        <i class="bi bi-check-circle me-2"></i>Save Changes
                    </button>
                    <button type="button" class="btn btn-outline-secondary cancel-btn cancel-edit-btn" data-section="profile">
                        <i class="bi bi-x-circle me-2"></i>Cancel
                    </button>
                </div>
            </form>
            
            <!-- Hidden Avatar Upload Form -->
            <form id="avatarForm" enctype="multipart/form-data" style="display: none;">
                <input type="file" id="profile_image" name="profile_image" accept="image/jpeg,image/jpg,image/png,image/gif,image/webp">
                <div id="avatarPreview" style="display: none; margin-top: 1rem;">
                    <img id="previewImage" src="" alt="Preview" class="rounded-circle" style="width: 150px; height: 150px; object-fit: cover; border: 4px solid #0d6efd;">
                </div>
                <button type="submit" class="btn btn-primary btn-sm mt-2">
                    <i class="bi bi-upload me-2"></i>Upload Picture
                </button>
            </form>
        </div>
    </div>

    <!-- My Orders Section (Most Prominent for Buyers) -->
    <?php if ($user_role === 'buyer'): ?>
    <div class="profile-section-card">
        <div class="profile-section-header">
            <h2 class="profile-section-title">
                <i class="bi bi-bag-check"></i>
                My Orders
            </h2>
            <div class="profile-section-actions">
                <a href="<?php echo BASE_PATH; ?>buyers/orders.php" class="btn btn-outline-primary btn-sm">
                    View All <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
        <div class="profile-section-body">
            <div id="ordersList" class="orders-list">
                <div class="profile-loading">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading orders...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Saved Addresses Section -->
    <div class="profile-section-card">
        <div class="profile-section-header">
            <h2 class="profile-section-title">
                <i class="bi bi-geo-alt"></i>
                Saved Addresses
            </h2>
            <div class="profile-section-actions">
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addAddressModal" aria-label="Add new address">
                    <i class="bi bi-plus-circle me-1"></i>Add Address
                </button>
            </div>
        </div>
        <div class="profile-section-body">
            <div id="addressesList" class="addresses-list">
                <!-- Addresses will be loaded here -->
            </div>
        </div>
    </div>

    <!-- Payment Methods Section -->
    <div class="profile-section-card">
        <div class="profile-section-header">
            <h2 class="profile-section-title">
                <i class="bi bi-credit-card"></i>
                Payment Methods
            </h2>
            <div class="profile-section-actions">
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addPaymentModal" aria-label="Add payment method">
                    <i class="bi bi-plus-circle me-1"></i>Add Payment Method
                </button>
            </div>
        </div>
        <div class="profile-section-body">
            <div id="paymentMethodsList" class="payment-methods-list">
                <!-- Payment methods will be loaded here -->
            </div>
        </div>
    </div>    <!-- Wishlist Section -->
    <?php if ($user_role === 'buyer'): ?>
    <div class="profile-section-card">
        <div class="profile-section-header">
            <h2 class="profile-section-title">
                <i class="bi bi-heart"></i>
                My Wishlist
            </h2>
            <div class="profile-section-actions">
                <a href="<?php echo BASE_PATH; ?>index.php?wishlist=1" class="btn btn-outline-primary btn-sm">
                    View All <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
        <div class="profile-section-body">
            <div id="wishlistGrid" class="wishlist-grid">
                <!-- Wishlist items will be loaded here -->
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Account & Security Section -->
    <div class="profile-section-card">
        <div class="profile-section-header">
            <h2 class="profile-section-title">
                <i class="bi bi-shield-lock"></i>
                Account & Security
            </h2>
        </div>
        <div class="profile-section-body">
            <div class="security-options">
                <div class="security-option">
                    <div class="security-option-info">
                        <div class="security-option-title">
                            <i class="bi bi-key"></i>
                            Change Password
                        </div>
                        <div class="security-option-desc">
                            Update your password to keep your account secure
                        </div>
                    </div>
                    <div class="security-option-action">
                        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#passwordModal" aria-label="Change password">
                            Change Password
                        </button>
                    </div>
                </div>
                
                <?php
                // Check if Google OAuth is enabled
                $check_google_oauth = $conn->query("SHOW COLUMNS FROM users LIKE 'google_id'");
                $has_google_oauth = $check_google_oauth->num_rows > 0;
                if ($has_google_oauth && !empty($user['google_id'])):
                ?>
                <div class="security-option">
                    <div class="security-option-info">
                        <div class="security-option-title">
                            <i class="bi bi-google"></i>
                            Connected Accounts
                        </div>
                        <div class="security-option-desc">
                            Google account connected
                        </div>
                    </div>
                    <div class="security-option-action">
                        <span class="badge bg-success">Connected</span>
                    </div>
                </div>
                <?php endif; ?>
                
                <div class="security-option">
                    <div class="security-option-info">
                        <div class="security-option-title">
                            <i class="bi bi-box-arrow-right"></i>
                            Logout
                        </div>
                        <div class="security-option-desc">
                            Sign out of your account
                        </div>
                    </div>
                    <div class="security-option-action">
                        <a href="<?php echo BASE_PATH; ?>controllers/authController.php?action=logout" class="btn btn-outline-danger" aria-label="Logout">
                            Logout
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Address Modal -->
<div class="modal fade profile-modal" id="addAddressModal" tabindex="-1" aria-labelledby="addAddressModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addAddressModalLabel">
                    <i class="bi bi-plus-circle"></i>
                    Add New Address
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addAddressForm">
                    <div class="mb-3">
                        <label for="addressLabel" class="form-label">Address Label <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="addressLabel" name="label" 
                               placeholder="Home, Work, etc." required>
                    </div>
                    <div class="mb-3">
                        <label for="addressFullName" class="form-label">Full Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="addressFullName" name="full_name" 
                               value="<?php echo htmlspecialchars($user['full_name'] ?? ''); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="addressPhone" class="form-label">Phone Number <span class="text-danger">*</span></label>
                        <input type="tel" class="form-control" id="addressPhone" name="phone" 
                               value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="addressLine1" class="form-label">Address Line 1 <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="addressLine1" name="address_line1" 
                               placeholder="Street address" required>
                    </div>
                    <div class="mb-3">
                        <label for="addressLine2" class="form-label">Address Line 2</label>
                        <input type="text" class="form-control" id="addressLine2" name="address_line2" 
                               placeholder="Apartment, suite, etc. (optional)">
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="addressCity" class="form-label">City <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="addressCity" name="city" required>
                        </div>
                        <div class="col-md-6">
                            <label for="addressRegion" class="form-label">Region/State <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="addressRegion" name="region" required>
                        </div>
                    </div>
                    <div class="mb-3 mt-3">
                        <label for="addressPostalCode" class="form-label">Postal Code</label>
                        <input type="text" class="form-control" id="addressPostalCode" name="postal_code">
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="addressIsDefault" name="is_default">
                        <label class="form-check-label" for="addressIsDefault">
                            Set as default address
                        </label>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-2"></i>Add Address
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Address Modal -->
<div class="modal fade profile-modal" id="editAddressModal" tabindex="-1" aria-labelledby="editAddressModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editAddressModalLabel">
                    <i class="bi bi-pencil"></i>
                    Edit Address
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editAddressForm">
                    <div class="mb-3">
                        <label for="editAddressLabel" class="form-label">Address Label <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="editAddressLabel" name="label" required>
                    </div>
                    <div class="mb-3">
                        <label for="editAddressFullName" class="form-label">Full Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="editAddressFullName" name="full_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="editAddressPhone" class="form-label">Phone Number <span class="text-danger">*</span></label>
                        <input type="tel" class="form-control" id="editAddressPhone" name="phone" required>
                    </div>
                    <div class="mb-3">
                        <label for="editAddressLine1" class="form-label">Address Line 1 <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="editAddressLine1" name="address_line1" required>
                    </div>
                    <div class="mb-3">
                        <label for="editAddressLine2" class="form-label">Address Line 2</label>
                        <input type="text" class="form-control" id="editAddressLine2" name="address_line2">
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="editAddressCity" class="form-label">City <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="editAddressCity" name="city" required>
                        </div>
                        <div class="col-md-6">
                            <label for="editAddressRegion" class="form-label">Region/State <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="editAddressRegion" name="region" required>
                        </div>
                    </div>
                    <div class="mb-3 mt-3">
                        <label for="editAddressPostalCode" class="form-label">Postal Code</label>
                        <input type="text" class="form-control" id="editAddressPostalCode" name="postal_code">
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="editAddressIsDefault" name="is_default">
                        <label class="form-check-label" for="editAddressIsDefault">
                            Set as default address
                        </label>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-2"></i>Update Address
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Add Payment Method Modal -->
<div class="modal fade profile-modal" id="addPaymentModal" tabindex="-1" aria-labelledby="addPaymentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addPaymentModalLabel">
                    <i class="bi bi-plus-circle"></i>
                    Add Payment Method
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <ul class="nav nav-tabs mb-3" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="card-tab" data-bs-toggle="tab" data-bs-target="#card-pane" type="button" role="tab">
                            <i class="bi bi-credit-card me-1"></i>Card
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="mobile-tab" data-bs-toggle="tab" data-bs-target="#mobile-pane" type="button" role="tab">
                            <i class="bi bi-phone me-1"></i>Mobile Money
                        </button>
                    </li>
                </ul>
                <div class="tab-content">
                    <!-- Card Form -->
                    <div class="tab-pane fade show active" id="card-pane" role="tabpanel">
                        <form id="addPaymentForm">
                            <input type="hidden" name="type" value="card">
                            <div class="mb-3">
                                <label for="cardNumber" class="form-label">Card Number <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="cardNumber" name="card_number" 
                                       placeholder="1234 5678 9012 3456" maxlength="19" required>
                            </div>
                            <div class="mb-3">
                                <label for="cardHolder" class="form-label">Cardholder Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="cardHolder" name="card_holder" 
                                       placeholder="John Doe" required>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="expiryMonth" class="form-label">Expiry Month <span class="text-danger">*</span></label>
                                    <select class="form-select" id="expiryMonth" name="expiry_month" required>
                                        <option value="">Month</option>
                                        <?php for ($i = 1; $i <= 12; $i++): ?>
                                            <option value="<?php echo $i; ?>"><?php echo str_pad($i, 2, '0', STR_PAD_LEFT); ?></option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="expiryYear" class="form-label">Expiry Year <span class="text-danger">*</span></label>
                                    <select class="form-select" id="expiryYear" name="expiry_year" required>
                                        <option value="">Year</option>
                                        <?php for ($i = date('Y'); $i <= date('Y') + 10; $i++): ?>
                                            <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3 mt-3">
                                <label for="cvv" class="form-label">CVV <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="cvv" name="cvv" 
                                       placeholder="123" maxlength="4" required>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="paymentIsDefault" name="is_default">
                                <label class="form-check-label" for="paymentIsDefault">
                                    Set as default payment method
                                </label>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle me-2"></i>Add Card
                                </button>
                            </div>
                        </form>
                    </div>
                    <!-- Mobile Money Form -->
                    <div class="tab-pane fade" id="mobile-pane" role="tabpanel">
                        <form id="addMobilePaymentForm">
                            <input type="hidden" name="type" value="mobile_money">
                            <div class="mb-3">
                                <label for="mobileProvider" class="form-label">Provider <span class="text-danger">*</span></label>
                                <select class="form-select" id="mobileProvider" name="mobile_money_provider" required>
                                    <option value="">Select Provider</option>
                                    <option value="MTN">MTN Mobile Money</option>
                                    <option value="Vodafone">Vodafone Cash</option>
                                    <option value="AirtelTigo">AirtelTigo Money</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="mobileNumber" class="form-label">Mobile Money Number <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control" id="mobileNumber" name="mobile_money_number" 
                                       placeholder="0244 123 456" required>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="mobilePaymentIsDefault" name="is_default">
                                <label class="form-check-label" for="mobilePaymentIsDefault">
                                    Set as default payment method
                                </label>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle me-2"></i>Add Mobile Money
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Change Password Modal -->
<div class="modal fade profile-modal" id="passwordModal" tabindex="-1" aria-labelledby="passwordModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="passwordModalLabel">
                    <i class="bi bi-key"></i>
                    Change Password
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="passwordForm">
                    <div class="mb-3">
                        <label for="currentPassword" class="form-label">Current Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="currentPassword" name="current_password" required>
                    </div>
                    <div class="mb-3">
                        <label for="newPassword" class="form-label">New Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="newPassword" name="new_password" 
                               minlength="6" required>
                        <small class="text-muted">Minimum 6 characters</small>
                    </div>
                    <div class="mb-3">
                        <label for="confirmPassword" class="form-label">Confirm New Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="confirmPassword" name="confirm_password" 
                               minlength="6" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-2"></i>Change Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
closeDBConnection($conn);
include 'includes/footer.php';
?>
