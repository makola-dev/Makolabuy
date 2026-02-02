<?php
/**
 * Makola Payment Information Guidelines
 * Makola
 */
require_once 'config/db.php';
require_once 'config/paths.php';
require_once 'includes/auth.php';

$pageTitle = 'Payment Information Guidelines';
include 'includes/header.php';
?>

<div class="container my-5">
    <h2 class="mb-4"><i class="bi bi-credit-card"></i> Makola Payment Information Guidelines</h2>
    
    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="card shadow">
                <div class="card-body">
                    <h5 class="mb-3">Accepted Payment Methods</h5>
                    <ul>
                        <li><strong>Credit/Debit Cards:</strong> VISA, Mastercard</li>
                        <li><strong>Mobile Money:</strong> MTN Mobile Money</li>
                        <li><strong>Online Payment Gateways:</strong> Paystack, Flutterwave</li>
                    </ul>
                    
                    <h5 class="mb-3 mt-4">Payment Security</h5>
                    <p>All payments are processed through secure, encrypted payment gateways. We do not store your full card details on our servers.</p>
                    
                    <h5 class="mb-3 mt-4">Payment Processing</h5>
                    <ul>
                        <li>Payments are processed immediately upon checkout</li>
                        <li>Orders are confirmed only after successful payment</li>
                        <li>Failed payments will not result in order placement</li>
                    </ul>
                    
                    <h5 class="mb-3 mt-4">Refunds</h5>
                    <p>Refunds are processed to the original payment method within 5-7 business days after return verification.</p>
                    
                    <h5 class="mb-3 mt-4">Payment Issues</h5>
                    <p>If you experience any payment issues, please contact our support team immediately at makolaghana5522@gmail.com or call 0538510162.</p>
                    
                    <div class="alert alert-warning mt-4">
                        <i class="bi bi-exclamation-triangle"></i> <strong>Security Tip:</strong> Never share your payment details with anyone. Makola will never ask for your password or card PIN.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include 'includes/footer.php';
?>

