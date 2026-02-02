<?php
/**
 * Dispute Resolution Policy
 * Makola
 */
require_once 'config/db.php';
require_once 'config/paths.php';
require_once 'includes/auth.php';

$pageTitle = 'Dispute Resolution Policy';
include 'includes/header.php';
?>

<div class="container my-5">
    <h2 class="mb-4"><i class="bi bi-shield-check"></i> Dispute Resolution Policy</h2>
    
    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="card shadow">
                <div class="card-body">
                    <h5 class="mb-3">Our Commitment</h5>
                    <p>Makola is committed to providing a fair and transparent marketplace for both buyers and sellers. This policy outlines how we handle disputes between buyers and sellers.</p>
                    
                    <h5 class="mb-3 mt-4">Types of Disputes</h5>
                    <ul>
                        <li><strong>Product Quality Issues:</strong> Product received doesn't match description or is defective</li>
                        <li><strong>Delivery Issues:</strong> Product not delivered, delayed, or damaged during shipping</li>
                        <li><strong>Payment Disputes:</strong> Issues with payment processing or refunds</li>
                        <li><strong>Seller Issues:</strong> Seller not responding or not fulfilling order</li>
                    </ul>
                    
                    <h5 class="mb-3 mt-4">Dispute Resolution Process</h5>
                    <div class="step mb-3">
                        <h6><strong>Step 1:</strong> Contact the Seller</h6>
                        <p>First, try to resolve the issue directly with the seller through our messaging system.</p>
                    </div>
                    <div class="step mb-3">
                        <h6><strong>Step 2:</strong> Open a Dispute</h6>
                        <p>If you can't resolve the issue, open a dispute from your order details page within 7 days of delivery.</p>
                    </div>
                    <div class="step mb-3">
                        <h6><strong>Step 3:</strong> Review Process</h6>
                        <p>Our support team will review the dispute, examine evidence from both parties, and make a decision within 5-7 business days.</p>
                    </div>
                    <div class="step mb-3">
                        <h6><strong>Step 4:</strong> Resolution</h6>
                        <p>Based on our review, we may issue a refund, request a return, or take other appropriate action.</p>
                    </div>
                    
                    <h5 class="mb-3 mt-4">Appeals</h5>
                    <p>If you disagree with our decision, you can appeal within 7 days by contacting our support team with additional evidence.</p>
                    
                    <div class="alert alert-warning mt-4">
                        <i class="bi bi-exclamation-triangle"></i> <strong>Important:</strong> All disputes must be reported within 7 days of delivery. Late disputes may not be eligible for resolution.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include 'includes/footer.php';
?>

