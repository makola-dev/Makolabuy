<?php
/**
 * Privacy Notice
 * Makola
 */
require_once 'config/db.php';
require_once 'config/paths.php';
require_once 'includes/auth.php';

$pageTitle = 'Privacy Notice';
include 'includes/header.php';
?>

<div class="container my-5">
    <h2 class="mb-4"><i class="bi bi-shield-lock"></i> Privacy Notice</h2>
    
    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="card shadow">
                <div class="card-body">
                    <p class="text-muted">Last updated: <?php echo date('F j, Y'); ?></p>
                    
                    <h5 class="mb-3">1. Information We Collect</h5>
                    <p>We collect information you provide directly, including:</p>
                    <ul>
                        <li>Name, email address, and contact information</li>
                        <li>Payment and billing information</li>
                        <li>Shipping addresses</li>
                        <li>Product reviews and ratings</li>
                    </ul>
                    
                    <h5 class="mb-3 mt-4">2. How We Use Your Information</h5>
                    <ul>
                        <li>Process and fulfill your orders</li>
                        <li>Send order confirmations and updates</li>
                        <li>Respond to your inquiries</li>
                        <li>Improve our services and user experience</li>
                        <li>Send promotional communications (with your consent)</li>
                    </ul>
                    
                    <h5 class="mb-3 mt-4">3. Information Sharing</h5>
                    <p>We do not sell your personal information. We may share information with:</p>
                    <ul>
                        <li>Payment processors for transaction processing</li>
                        <li>Shipping partners for order delivery</li>
                        <li>Service providers who assist in our operations</li>
                    </ul>
                    
                    <h5 class="mb-3 mt-4">4. Data Security</h5>
                    <p>We implement appropriate security measures to protect your personal information from unauthorized access, alteration, or disclosure.</p>
                    
                    <h5 class="mb-3 mt-4">5. Your Rights</h5>
                    <ul>
                        <li>Access your personal information</li>
                        <li>Request correction of inaccurate data</li>
                        <li>Request deletion of your data</li>
                        <li>Opt-out of marketing communications</li>
                    </ul>
                    
                    <h5 class="mb-3 mt-4">6. Cookies</h5>
                    <p>We use cookies to enhance your browsing experience. You can manage cookie preferences in your browser settings.</p>
                    
                    <h5 class="mb-3 mt-4">7. Contact Us</h5>
                    <p>For privacy-related questions, contact us at privacy@makola.com</p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include 'includes/footer.php';
?>

