<?php
/**
 * Terms and Conditions
 * Makola
 */
require_once 'config/db.php';
require_once 'config/paths.php';
require_once 'includes/auth.php';

$pageTitle = 'Terms and Conditions';
include 'includes/header.php';
?>

<div class="container my-5">
    <h2 class="mb-4"><i class="bi bi-file-text"></i> Terms and Conditions</h2>
    
    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="card shadow">
                <div class="card-body">
                    <p class="text-muted">Last updated: <?php echo date('F j, Y'); ?></p>
                    
                    <h5 class="mb-3">1. Acceptance of Terms</h5>
                    <p>By accessing and using Makola, you accept and agree to be bound by these Terms and Conditions.</p>
                    
                    <h5 class="mb-3 mt-4">2. User Accounts</h5>
                    <p>Users are responsible for maintaining the confidentiality of their account credentials and for all activities under their account.</p>
                    
                    <h5 class="mb-3 mt-4">3. Product Listings</h5>
                    <p>Sellers are responsible for accurate product descriptions, pricing, and images. Makola reserves the right to remove any product that violates our policies.</p>
                    
                    <h5 class="mb-3 mt-4">4. Orders and Payments</h5>
                    <p>All orders are subject to product availability and seller confirmation. Payment must be completed before order processing.</p>
                    
                    <h5 class="mb-3 mt-4">5. Returns and Refunds</h5>
                    <p>Returns are accepted within 7 days of delivery. Refunds are processed according to our Returns & Refund Policy.</p>
                    
                    <h5 class="mb-3 mt-4">6. Prohibited Activities</h5>
                    <ul>
                        <li>Fraudulent transactions</li>
                        <li>Listing prohibited items</li>
                        <li>Misleading product information</li>
                        <li>Circumventing platform fees</li>
                    </ul>
                    
                    <h5 class="mb-3 mt-4">7. Limitation of Liability</h5>
                    <p>Makola is not liable for any indirect, incidental, or consequential damages arising from the use of our platform.</p>
                    
                    <h5 class="mb-3 mt-4">8. Changes to Terms</h5>
                    <p>We reserve the right to modify these terms at any time. Continued use of the platform constitutes acceptance of modified terms.</p>
                    
                    <div class="alert alert-info mt-4">
                        <i class="bi bi-info-circle"></i> For questions about these terms, please contact us at support@makola.com
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include 'includes/footer.php';
?>

