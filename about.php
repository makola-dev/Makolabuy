<?php
/**
 * About Us Page
 * Makola
 */
require_once 'config/db.php';
require_once 'config/paths.php';
require_once 'includes/auth.php';

$pageTitle = 'About Us';
include 'includes/header.php';
?>

<div class="container my-5">
    <h2 class="mb-4"><i class="bi bi-info-circle"></i> About Makola</h2>
    
    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="card shadow mb-4">
                <div class="card-body">
                    <h4 class="mb-3">Who We Are</h4>
                    <p>Makola is a leading multi-vendor marketplace connecting buyers and sellers across various product categories. We provide a secure, user-friendly platform for online shopping and selling.</p>
                    
                    <h4 class="mb-3 mt-4">Our Mission</h4>
                    <p>To empower businesses and individuals by providing a trusted platform for buying and selling products, while ensuring a seamless and secure shopping experience for all our users.</p>
                    
                    <h4 class="mb-3 mt-4">Our Vision</h4>
                    <p>To become the most trusted and preferred online marketplace, known for quality products, excellent customer service, and innovative solutions.</p>
                    
                    <h4 class="mb-3 mt-4">What We Offer</h4>
                    <ul>
                        <li>Wide range of products across multiple categories</li>
                        <li>Secure payment processing</li>
                        <li>Fast and reliable delivery</li>
                        <li>24/7 customer support</li>
                        <li>Easy seller onboarding and management tools</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include 'includes/footer.php';
?>

