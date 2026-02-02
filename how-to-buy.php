<?php
/**
 * How to Buy on Makola
 * Makola
 */
require_once 'config/db.php';
require_once 'config/paths.php';
require_once 'includes/auth.php';

$pageTitle = 'How to Buy on Makola';
include 'includes/header.php';
?>

<div class="container my-5">
    <h2 class="mb-4"><i class="bi bi-cart-check"></i> How to Buy on Makola</h2>
    
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow">
                <div class="card-body">
                    <div class="step mb-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="step-number bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; font-size: 1.5rem; font-weight: bold;">1</div>
                            <h4 class="mb-0 ms-3">Create an Account</h4>
                        </div>
                        <p class="ms-5">Sign up for a free Makola account. You can register as a buyer to start shopping immediately.</p>
                        <a href="<?php echo BASE_PATH; ?>register.php" class="btn btn-outline-primary ms-5">Register Now</a>
                    </div>
                    
                    <hr>
                    
                    <div class="step mb-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="step-number bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; font-size: 1.5rem; font-weight: bold;">2</div>
                            <h4 class="mb-0 ms-3">Browse Products</h4>
                        </div>
                        <p class="ms-5">Explore our wide range of products by category or use the search bar to find specific items.</p>
                    </div>
                    
                    <hr>
                    
                    <div class="step mb-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="step-number bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; font-size: 1.5rem; font-weight: bold;">3</div>
                            <h4 class="mb-0 ms-3">Add to Cart</h4>
                        </div>
                        <p class="ms-5">Click "Add to Cart" on any product you want to purchase. You can add multiple items before checkout.</p>
                    </div>
                    
                    <hr>
                    
                    <div class="step mb-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="step-number bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; font-size: 1.5rem; font-weight: bold;">4</div>
                            <h4 class="mb-0 ms-3">Proceed to Checkout</h4>
                        </div>
                        <p class="ms-5">Review your cart, enter your shipping address, and select your preferred payment method.</p>
                    </div>
                    
                    <hr>
                    
                    <div class="step mb-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="step-number bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; font-size: 1.5rem; font-weight: bold;">5</div>
                            <h4 class="mb-0 ms-3">Complete Payment</h4>
                        </div>
                        <p class="ms-5">Make payment using any of our secure payment methods: VISA, Mastercard, MTN Mobile Money, Paystack, or Flutterwave.</p>
                    </div>
                    
                    <hr>
                    
                    <div class="step mb-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="step-number bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; font-size: 1.5rem; font-weight: bold;">6</div>
                            <h4 class="mb-0 ms-3">Track Your Order</h4>
                        </div>
                        <p class="ms-5">Once your order is confirmed, you can track its status from your "My Orders" page. You'll receive email updates too.</p>
                    </div>
                </div>
            </div>
            
            <div class="alert alert-success mt-4">
                <i class="bi bi-check-circle"></i> <strong>That's it!</strong> Your order will be delivered to your specified address. Happy shopping!
            </div>
        </div>
    </div>
</div>

<?php
include 'includes/footer.php';
?>

