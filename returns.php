<?php
/**
 * How to Return a Product
 * Makola
 */
require_once 'config/db.php';
require_once 'config/paths.php';
require_once 'includes/auth.php';

$pageTitle = 'How to Return a Product';
include 'includes/header.php';
?>

<div class="container my-5">
    <h2 class="mb-4"><i class="bi bi-arrow-counterclockwise"></i> How to Return a Product on Makola</h2>
    
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Return Policy</h5>
                </div>
                <div class="card-body">
                    <p>We offer a 7-day return policy on most products. Items must be in their original condition with tags and packaging intact.</p>
                </div>
            </div>
            
            <div class="card shadow mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Steps to Return a Product</h5>
                </div>
                <div class="card-body">
                    <div class="step mb-3">
                        <h6><strong>Step 1:</strong> Log into your account</h6>
                        <p>Go to "My Orders" and find the order containing the item you want to return.</p>
                    </div>
                    <hr>
                    <div class="step mb-3">
                        <h6><strong>Step 2:</strong> Initiate Return</h6>
                        <p>Click on "Return Item" and select the reason for return from the dropdown menu.</p>
                    </div>
                    <hr>
                    <div class="step mb-3">
                        <h6><strong>Step 3:</strong> Package the Item</h6>
                        <p>Pack the item in its original packaging with all accessories and tags included.</p>
                    </div>
                    <hr>
                    <div class="step mb-3">
                        <h6><strong>Step 4:</strong> Drop Off or Schedule Pickup</h6>
                        <p>You can drop off the item at any of our service centers or schedule a pickup (fees may apply).</p>
                    </div>
                    <hr>
                    <div class="step mb-3">
                        <h6><strong>Step 5:</strong> Receive Refund</h6>
                        <p>Once we receive and verify the returned item, your refund will be processed within 5-7 business days.</p>
                    </div>
                </div>
            </div>
            
            <div class="card shadow">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">Items Not Eligible for Return</h5>
                </div>
                <div class="card-body">
                    <ul>
                        <li>Perishable items</li>
                        <li>Personalized or custom-made products</li>
                        <li>Items damaged by misuse</li>
                        <li>Products without original packaging</li>
                        <li>Items returned after 7 days</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include 'includes/footer.php';
?>

