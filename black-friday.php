<?php
/**
 * Black Friday Page
 * Makola
 */
require_once 'config/db.php';
require_once 'config/paths.php';
require_once 'includes/auth.php';

$pageTitle = 'Black Friday';
include 'includes/header.php';
?>

<div class="container my-5">
    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="card shadow text-center">
                <div class="card-body py-5">
                    <h1 class="display-4 mb-4">🎉 Black Friday Sale</h1>
                    <p class="lead">Get ready for amazing deals and discounts!</p>
                    <p class="text-muted">Our Black Friday sale is coming soon. Stay tuned for incredible savings on thousands of products.</p>
                    <a href="<?php echo BASE_PATH; ?>index.php" class="btn btn-primary btn-lg mt-3">
                        <i class="bi bi-arrow-left"></i> Continue Shopping
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include 'includes/footer.php';
?>

