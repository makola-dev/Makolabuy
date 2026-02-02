<?php
/**
 * Makola Store Credit Terms & Conditions
 * Makola
 */
require_once 'config/db.php';
require_once 'config/paths.php';
require_once 'includes/auth.php';

$pageTitle = 'Store Credit Terms';
include 'includes/header.php';
?>

<div class="container my-5">
    <h2 class="mb-4"><i class="bi bi-wallet"></i> Makola Store Credit Terms & Conditions</h2>
    
    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="card shadow">
                <div class="card-body">
                    <h5 class="mb-3">What is Store Credit?</h5>
                    <p>Store Credit is a balance that can be used to purchase products on Makola. It can be earned through returns, refunds, promotions, or purchased directly.</p>
                    
                    <h5 class="mb-3 mt-4">Earning Store Credit</h5>
                    <ul>
                        <li>Refunds may be issued as Store Credit (with your consent)</li>
                        <li>Promotional campaigns and special offers</li>
                        <li>Referral bonuses</li>
                        <li>Direct purchase</li>
                    </ul>
                    
                    <h5 class="mb-3 mt-4">Using Store Credit</h5>
                    <ul>
                        <li>Store Credit can be applied at checkout</li>
                        <li>Can be combined with other payment methods</li>
                        <li>No expiration date (unless otherwise stated)</li>
                        <li>Non-transferable and non-refundable</li>
                    </ul>
                    
                    <h5 class="mb-3 mt-4">Terms and Conditions</h5>
                    <ul>
                        <li>Store Credit cannot be converted to cash</li>
                        <li>Cannot be transferred to another account</li>
                        <li>Subject to verification and approval</li>
                        <li>Makola reserves the right to modify these terms</li>
                    </ul>
                    
                    <div class="alert alert-info mt-4">
                        <i class="bi bi-info-circle"></i> Check your Store Credit balance in your account dashboard.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include 'includes/footer.php';
?>

