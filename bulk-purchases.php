<?php
/**
 * Corporate and Bulk Purchases
 * Makola
 */
require_once 'config/db.php';
require_once 'config/paths.php';
require_once 'includes/auth.php';

session_start();
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];

$pageTitle = 'Corporate and Bulk Purchases';
include 'includes/header.php';
?>

<div class="container my-5">
    <h2 class="mb-4"><i class="bi bi-briefcase"></i> Corporate and Bulk Purchases</h2>
    
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Bulk Purchase Benefits</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="bi bi-check-circle text-success"></i> Volume discounts up to 30%</li>
                        <li class="mb-2"><i class="bi bi-check-circle text-success"></i> Dedicated account manager</li>
                        <li class="mb-2"><i class="bi bi-check-circle text-success"></i> Flexible payment terms</li>
                        <li class="mb-2"><i class="bi bi-check-circle text-success"></i> Priority customer support</li>
                        <li class="mb-2"><i class="bi bi-check-circle text-success"></i> Customized invoicing</li>
                        <li class="mb-2"><i class="bi bi-check-circle text-success"></i> Special pricing for repeat orders</li>
                    </ul>
                </div>
            </div>
            
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Request a Quote</h5>
                </div>
                <div class="card-body">
                    <div id="ajax-message"></div>
                    
                    <p>Interested in bulk purchasing? Fill out the form below and our team will contact you with a customized quote.</p>
                    <form method="POST" id="bulkPurchaseForm" action="<?php echo BASE_PATH; ?>controllers/bulk-purchase.php">
                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">
                        <div class="mb-3">
                            <label for="company" class="form-label">Company Name</label>
                            <input type="text" class="form-control" id="company" name="company" required>
                        </div>
                        <div class="mb-3">
                            <label for="contact_name" class="form-label">Contact Name</label>
                            <input type="text" class="form-control" id="contact_name" name="contact_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="tel" class="form-control" id="phone" name="phone" required>
                        </div>
                        <div class="mb-3">
                            <label for="quantity" class="form-label">Estimated Quantity</label>
                            <input type="number" class="form-control" id="quantity" name="quantity" min="1" required>
                        </div>
                        <div class="mb-3">
                            <label for="products" class="form-label">Products of Interest</label>
                            <textarea class="form-control" id="products" name="products" rows="3" placeholder="List the products you're interested in purchasing in bulk"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-send"></i> Request Quote
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('bulkPurchaseForm');
    const messageDiv = document.getElementById('ajax-message');
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        messageDiv.innerHTML = '';
        const formData = new FormData(form);
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                messageDiv.innerHTML = '<div class="alert alert-success"><i class="bi bi-check-circle"></i> ' + data.message + '</div>';
                form.reset();
            } else {
                messageDiv.innerHTML = '<div class="alert alert-danger"><i class="bi bi-exclamation-triangle"></i> ' + data.error + '</div>';
            }
        })
        .catch(() => {
            messageDiv.innerHTML = '<div class="alert alert-danger">An error occurred. Please try again.</div>';
        });
    });
});
</script>

<?php
include 'includes/footer.php';
?>

