<?php
/**
 * Flash Sales
 * Makola
 */
require_once 'config/db.php';
require_once 'config/paths.php';
require_once 'includes/auth.php';

$conn = getDBConnection();

// Get flash sale products (products with special pricing or limited stock)
$flash_sales_query = "SELECT p.*, u.username as seller_name, c.name as category_name
                      FROM products p
                      JOIN users u ON p.seller_id = u.id
                      JOIN categories c ON p.category_id = c.id
                      WHERE p.status = 'approved' AND p.stock > 0
                      ORDER BY p.created_at DESC
                      LIMIT 12";
$flash_sales_result = $conn->query($flash_sales_query);

$pageTitle = 'Flash Sales';
include 'includes/header.php';
?>

<div class="container my-5">
    <div class="text-center mb-4">
        <h2 class="mb-2"><i class="bi bi-lightning-charge text-warning"></i> Flash Sales</h2>
        <p class="text-muted">Limited time offers - Shop now before they're gone!</p>
    </div>
    
    <?php if ($flash_sales_result->num_rows > 0): ?>
    <div class="row g-4">
        <?php while ($product = $flash_sales_result->fetch_assoc()): ?>
        <div class="col-6 col-md-4 col-lg-3">
            <div class="card h-100 product-card">
                <?php if ($product['image']): ?>
                    <img src="<?php echo BASE_PATH; ?>assets/img/products/<?php echo htmlspecialchars($product['image']); ?>" 
                         class="card-img-top" alt="<?php echo htmlspecialchars($product['title']); ?>"
                         style="height: 200px; object-fit: cover;">
                <?php else: ?>
                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                         style="height: 200px;">
                        <i class="bi bi-image text-muted" style="font-size: 3rem;"></i>
                    </div>
                <?php endif; ?>
                <div class="card-body d-flex flex-column">
                    <h6 class="card-title"><?php echo htmlspecialchars($product['title']); ?></h6>
                    <p class="text-primary fw-bold mb-2">₵<?php echo number_format($product['price'], 2); ?></p>
                    <div class="mt-auto">
                        <a href="<?php echo BASE_PATH; ?>product.php?id=<?php echo $product['id']; ?>" class="btn btn-sm btn-primary w-100">
                            View Details
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
    <?php else: ?>
    <div class="alert alert-info text-center">
        <i class="bi bi-info-circle"></i> No flash sale products available at the moment. Check back soon!
    </div>
    <?php endif; ?>
</div>

<?php
closeDBConnection($conn);
include 'includes/footer.php';
?>

