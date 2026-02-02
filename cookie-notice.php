<?php
/**
 * Cookie Notice
 * Makola
 */
require_once 'config/db.php';
require_once 'config/paths.php';
require_once 'includes/auth.php';

$pageTitle = 'Cookie Notice';
include 'includes/header.php';
?>

<div class="container my-5">
    <h2 class="mb-4"><i class="bi bi-cookie"></i> Cookie Notice</h2>
    
    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="card shadow">
                <div class="card-body">
                    <h5 class="mb-3">What are Cookies?</h5>
                    <p>Cookies are small text files stored on your device when you visit our website. They help us provide a better user experience and understand how you use our site.</p>
                    
                    <h5 class="mb-3 mt-4">Types of Cookies We Use</h5>
                    
                    <h6 class="mt-3">Essential Cookies</h6>
                    <p>These cookies are necessary for the website to function properly. They enable core functionality such as security, network management, and accessibility.</p>
                    
                    <h6 class="mt-3">Analytics Cookies</h6>
                    <p>These cookies help us understand how visitors interact with our website by collecting and reporting information anonymously.</p>
                    
                    <h6 class="mt-3">Functional Cookies</h6>
                    <p>These cookies enable enhanced functionality and personalization, such as remembering your preferences and cart contents.</p>
                    
                    <h6 class="mt-3">Marketing Cookies</h6>
                    <p>These cookies are used to deliver relevant advertisements and track campaign effectiveness.</p>
                    
                    <h5 class="mb-3 mt-4">Managing Cookies</h5>
                    <p>You can control and manage cookies through your browser settings. However, disabling certain cookies may affect website functionality.</p>
                    
                    <div class="alert alert-info mt-4">
                        <i class="bi bi-info-circle"></i> By continuing to use our website, you consent to our use of cookies as described in this notice.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include 'includes/footer.php';
?>

