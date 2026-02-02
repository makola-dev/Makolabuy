<?php
/**
 * Report a Product
 * Makola
 */
require_once 'config/db.php';
require_once 'config/paths.php';
require_once 'includes/auth.php';

$pageTitle = 'Report a Product';
include 'includes/header.php';
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-danger text-white">
                    <h4 class="mb-0"><i class="bi bi-flag"></i> Report a Product</h4>
                </div>
                <div class="card-body">
                    <?php if (isset($_GET['success'])): ?>
                        <div class="alert alert-success">
                            <i class="bi bi-check-circle"></i> Thank you for your report. Our team will review it and take appropriate action.
                        </div>
                    <?php endif; ?>
                    
                    <p>If you've found a product that violates our policies, is misleading, or contains inappropriate content, please report it using the form below.</p>
                    
                    <form method="POST" action="<?php echo BASE_PATH; ?>controllers/report-product.php">
                        <div class="mb-3">
                            <label for="product_url" class="form-label">Product URL or ID</label>
                            <input type="text" class="form-control" id="product_url" name="product_url" placeholder="e.g., https://makola.com/product.php?id=123" required>
                        </div>
                        <div class="mb-3">
                            <label for="reason" class="form-label">Reason for Reporting</label>
                            <select class="form-select" id="reason" name="reason" required>
                                <option value="">Select a reason</option>
                                <option value="counterfeit">Counterfeit or fake product</option>
                                <option value="misleading">Misleading description or images</option>
                                <option value="inappropriate">Inappropriate content</option>
                                <option value="prohibited">Prohibited item</option>
                                <option value="spam">Spam or scam</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Additional Details</label>
                            <textarea class="form-control" id="description" name="description" rows="4" placeholder="Please provide more details about why you're reporting this product" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="reporter_email" class="form-label">Your Email</label>
                            <input type="email" class="form-control" id="reporter_email" name="reporter_email" required>
                        </div>
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-send"></i> Submit Report
                        </button>
                    </form>
                    
                    <div class="alert alert-info mt-4">
                        <i class="bi bi-info-circle"></i> <strong>Note:</strong> All reports are reviewed by our team. We take appropriate action on reported products within 24-48 hours.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include 'includes/footer.php';
?>

