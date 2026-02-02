<?php
/**
 * Official Stores
 * Makola
 */
require_once 'config/db.php';
require_once 'config/paths.php';
require_once 'includes/auth.php';

$conn = getDBConnection();

// Get verified sellers (official stores)
$official_stores_query = "SELECT u.id, u.username, u.email, COUNT(p.id) as product_count
                          FROM users u
                          LEFT JOIN products p ON u.id = p.seller_id AND p.status = 'approved'
                          WHERE u.role = 'seller' AND u.is_verified = 1
                          GROUP BY u.id
                          ORDER BY product_count DESC";
$official_stores_result = $conn->query($official_stores_query);

$pageTitle = 'Official Stores';
include 'includes/header.php';
?>

<div class="container my-5">
    <h2 class="mb-4"><i class="bi bi-shop-window"></i> Official Stores</h2>
    <p class="text-muted mb-4">Shop from our verified official stores and trusted sellers.</p>
    
    <?php if ($official_stores_result->num_rows > 0): ?>
    <div class="row g-4">
        <?php while ($store = $official_stores_result->fetch_assoc()): ?>
        <div class="col-md-6 col-lg-4">
            <div class="card shadow h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                             style="width: 50px; height: 50px; font-size: 1.5rem;">
                            <i class="bi bi-shop"></i>
                        </div>
                        <div class="ms-3">
                            <h5 class="mb-0"><?php echo htmlspecialchars($store['username']); ?></h5>
                            <span class="badge bg-success">
                                <i class="bi bi-check-circle"></i> Verified Store
                            </span>
                        </div>
                    </div>
                    <p class="text-muted mb-2">
                        <i class="bi bi-box"></i> <?php echo $store['product_count']; ?> Products
                    </p>
                    <a href="<?php echo BASE_PATH; ?>index.php?seller=<?php echo $store['id']; ?>" class="btn btn-outline-primary w-100">
                        Visit Store
                    </a>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
    <?php else: ?>
    <div class="alert alert-info text-center">
        <i class="bi bi-info-circle"></i> No official stores available at the moment.
    </div>
    <?php endif; ?>
</div>

<?php
closeDBConnection($conn);
include 'includes/footer.php';
?>

