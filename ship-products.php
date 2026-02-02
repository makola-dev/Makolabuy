<?php
/**
 * Ship Your Products with Makola
 * Makola
 */
require_once 'config/db.php';
require_once 'config/paths.php';
require_once 'includes/auth.php';

$pageTitle = 'Ship Your Products with Makola';
include 'includes/header.php';
?>

<div class="container my-5">
    <h2 class="mb-4"><i class="bi bi-truck"></i> Ship Your Products with Makola</h2>
    
    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="card shadow mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Logistics Solutions for Sellers</h5>
                </div>
                <div class="card-body">
                    <p>Makola offers comprehensive shipping solutions to help sellers deliver products to customers efficiently and cost-effectively.</p>
                    
                    <h6 class="mt-4">Benefits</h6>
                    <ul>
                        <li>Competitive shipping rates</li>
                        <li>Real-time tracking</li>
                        <li>Nationwide coverage</li>
                        <li>Insurance options</li>
                        <li>Dedicated seller support</li>
                    </ul>
                    
                    <h6 class="mt-4">How It Works</h6>
                    <ol>
                        <li>Register as a seller on Makola</li>
                        <li>Set up your shipping preferences</li>
                        <li>Print shipping labels from your dashboard</li>
                        <li>Package and ship your products</li>
                        <li>Track deliveries in real-time</li>
                    </ol>
                    
                    <div class="mt-4">
                        <a href="<?php echo BASE_PATH; ?>register.php" class="btn btn-primary">
                            <i class="bi bi-person-plus"></i> Become a Seller
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include 'includes/footer.php';
?>

