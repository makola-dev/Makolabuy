<?php
/**
 * Returns & Refund Terms and Conditions
 * Makola
 */
require_once 'config/db.php';
require_once 'config/paths.php';
require_once 'includes/auth.php';

$pageTitle = 'Returns & Refund Terms';
include 'includes/header.php';
?>

<div class="container my-5">
    <h2 class="mb-4"><i class="bi bi-arrow-counterclockwise"></i> Returns & Refund Terms and Conditions</h2>
    
    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="card shadow">
                <div class="card-body">
                    <h5 class="mb-3">Return Policy</h5>
                    <ul>
                        <li>You have 7 days from the date of delivery to return an item</li>
                        <li>Items must be in original condition with tags and packaging</li>
                        <li>Some items may not be eligible for return (see exclusions below)</li>
                        <li>Return shipping costs may apply unless the item is defective or incorrect</li>
                    </ul>
                    
                    <h5 class="mb-3 mt-4">Refund Process</h5>
                    <ul>
                        <li>Refunds are processed within 5-7 business days after we receive and verify the returned item</li>
                        <li>Refunds are issued to the original payment method</li>
                        <li>Shipping fees are non-refundable unless the return is due to our error</li>
                        <li>Partial refunds may apply for items returned in used or damaged condition</li>
                    </ul>
                    
                    <h5 class="mb-3 mt-4">Exclusions</h5>
                    <p>The following items are not eligible for return:</p>
                    <ul>
                        <li>Perishable goods</li>
                        <li>Personalized or custom-made products</li>
                        <li>Items damaged by misuse or normal wear</li>
                        <li>Products without original packaging or tags</li>
                        <li>Digital products or downloadable content</li>
                    </ul>
                    
                    <h5 class="mb-3 mt-4">How to Return</h5>
                    <ol>
                        <li>Log into your account and go to "My Orders"</li>
                        <li>Select the order and click "Return Item"</li>
                        <li>Follow the return instructions provided</li>
                        <li>Package the item securely in its original packaging</li>
                        <li>Drop off at a service center or schedule a pickup</li>
                    </ol>
                    
                    <div class="alert alert-info mt-4">
                        <i class="bi bi-info-circle"></i> For questions about returns or refunds, please contact our support team at support@makola.com
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include 'includes/footer.php';
?>

