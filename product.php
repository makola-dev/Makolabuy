<?php
/**
 * Product Details Page
 * Makola
 */
require_once 'config/db.php';
require_once 'config/paths.php';
require_once 'includes/auth.php';

$conn = getDBConnection();

$product_id = $_GET['id'] ?? 0;

// Get product details - allow sellers to view their own products even if pending
$seller_id = isLoggedIn() && getUserRole() === 'seller' ? getUserId() : 0;
if ($seller_id > 0) {
    // Sellers can view their own products regardless of status
    $stmt = $conn->prepare("SELECT p.*, u.username as seller_name, u.id as seller_id, c.name as category_name, sc.name as subcategory_name
                            FROM products p 
                            JOIN users u ON p.seller_id = u.id 
                            JOIN categories c ON p.category_id = c.id 
                            LEFT JOIN subcategories sc ON p.subcategory_id = sc.id
                            WHERE p.id = ? AND (p.status = 'approved' OR p.seller_id = ?)");
    $stmt->bind_param("ii", $product_id, $seller_id);
} else {
    // Regular users can only see approved products
    $stmt = $conn->prepare("SELECT p.*, u.username as seller_name, u.id as seller_id, c.name as category_name, sc.name as subcategory_name
                            FROM products p 
                            JOIN users u ON p.seller_id = u.id 
                            JOIN categories c ON p.category_id = c.id 
                            LEFT JOIN subcategories sc ON p.subcategory_id = sc.id
                            WHERE p.id = ? AND p.status = 'approved'");
    $stmt->bind_param("i", $product_id);
}
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();

// Get all product images
$check_images_table = $conn->query("SHOW TABLES LIKE 'product_images'");
$has_images_table = $check_images_table->num_rows > 0;
$product_images = [];

if ($has_images_table) {
    $images_stmt = $conn->prepare("SELECT image_path FROM product_images WHERE product_id = ? ORDER BY display_order, id");
    $images_stmt->bind_param("i", $product_id);
    $images_stmt->execute();
    $images_result = $images_stmt->get_result();
    while ($img = $images_result->fetch_assoc()) {
        $product_images[] = $img['image_path'];
    }
    $images_stmt->close();
}

// If no additional images but has primary image, add it
if (empty($product_images) && !empty($product['image'])) {
    $product_images[] = $product['image'];
}

// Load product attributes and values (size, color, etc.)
$attributes = [];
$attr_stmt = $conn->prepare("
    SELECT pa.id as attribute_id, pa.name, pav.id as value_id, pav.value
    FROM product_attributes pa
    JOIN product_attribute_values pav ON pav.attribute_id = pa.id
    WHERE pa.product_id = ?
    ORDER BY pa.id, pav.id
");
$attr_stmt->bind_param("i", $product_id);
$attr_stmt->execute();
$attr_result = $attr_stmt->get_result();
while ($row = $attr_result->fetch_assoc()) {
    $attrId = $row['attribute_id'];
    if (!isset($attributes[$attrId])) {
        $attributes[$attrId] = [
            'name' => $row['name'],
            'values' => []
        ];
    }
    $attributes[$attrId]['values'][] = [
        'id' => (int)$row['value_id'],
        'value' => $row['value']
    ];
}
$attr_stmt->close();

if (!$product) {
    $base = defined('BASE_PATH') ? BASE_PATH : '/Makola/';
    header('Location: ' . $base . 'index.php');
    exit();
}

// Increment view count
$update_views = $conn->prepare("UPDATE products SET views = views + 1 WHERE id = ?");
$update_views->bind_param("i", $product_id);
$update_views->execute();
$update_views->close();

$pageTitle = htmlspecialchars($product['title']);
include 'includes/header.php';
?>

<?php
// Get product rating
$check_rating = $conn->query("SHOW COLUMNS FROM products LIKE 'average_rating'");
$has_rating = $check_rating->num_rows > 0;
$average_rating = 0;
$review_count = 0;

if ($has_rating) {
    $rating_query = "SELECT average_rating, review_count FROM products WHERE id = ?";
    $rating_stmt = $conn->prepare($rating_query);
    $rating_stmt->bind_param("i", $product_id);
    $rating_stmt->execute();
    $rating_result = $rating_stmt->get_result();
    if ($rating_row = $rating_result->fetch_assoc()) {
        $average_rating = round((float)($rating_row['average_rating'] ?? 0), 1);
        $review_count = (int)($rating_row['review_count'] ?? 0);
    }
    $rating_stmt->close();
}

// Check for deal/sale pricing
$check_deal = $conn->query("SHOW COLUMNS FROM products LIKE 'is_deal'");
$has_deal = $check_deal->num_rows > 0;
$current_price = (float)$product['price'];
$original_price = null;
$savings = null;
$is_sale = false;
$deal_end_date = null;

if ($has_deal && !empty($product['deal_price']) && (float)$product['deal_price'] < $current_price) {
    $original_price = $current_price;
    $current_price = (float)$product['deal_price'];
    $savings = $original_price - $current_price;
    $is_sale = true;
    $deal_end_date = $product['deal_end_date'] ?? null;
} elseif ($has_deal && !empty($product['deal_price']) && (float)$product['deal_price'] > 0) {
    $current_price = (float)$product['deal_price'];
}

// Get sold count if available
$sold_count = 0;
$check_sales = $conn->query("SHOW COLUMNS FROM products LIKE 'sales_count'");
if ($check_sales->num_rows > 0 && isset($product['sales_count'])) {
    $sold_count = (int)$product['sales_count'];
}
?>

<div class="container my-4 product-page">
    <!-- Quick View Style Layout -->
    <div class="qv-content" style="border-radius: 12px; overflow: hidden; background: #fff; box-shadow: 0 2px 12px rgba(0,0,0,0.1);">
        <div class="qv-body" style="display: flex; gap: 0; min-height: 600px;">
            <!-- Left: Image Gallery (Quick View Style) -->
            <div class="qv-left" style="width: 45%; background: #f8f9fa; padding: 2rem; display: flex; align-items: flex-start;">
                <div class="qv-gallery" style="width: 100%; display: flex; gap: 1rem;">
                    <?php if (!empty($product_images) && count($product_images) > 1): ?>
                    <div class="qv-thumbnails" style="display: flex; flex-direction: column; gap: 0.75rem; width: 80px; flex-shrink: 0;">
                        <?php foreach ($product_images as $index => $image): ?>
                        <div class="qv-thumb <?php echo $index === 0 ? 'active' : ''; ?>" 
                             data-img="<?php echo BASE_PATH; ?>assets/img/products/<?php echo htmlspecialchars($image); ?>"
                             onclick="changeMainImage('<?php echo BASE_PATH; ?>assets/img/products/<?php echo htmlspecialchars($image); ?>', this)"
                             style="width: 80px; height: 80px; border: 2px solid <?php echo $index === 0 ? '#0d6efd' : 'transparent'; ?>; border-radius: 8px; overflow: hidden; cursor: pointer; transition: border-color 0.2s; background: #fff; padding: 4px;">
                            <img src="<?php echo BASE_PATH; ?>assets/img/products/<?php echo htmlspecialchars($image); ?>" 
                                 alt="Thumbnail <?php echo $index + 1; ?>"
                                 style="width: 100%; height: 100%; object-fit: contain;"
                                 onerror="this.src='<?php echo BASE_PATH; ?>assets/img/products/placeholder.jpg'; this.onerror=null;">
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                    
                    <div class="qv-main-image" style="flex: 1; background: #fff; border-radius: 8px; padding: 1rem; display: flex; align-items: center; justify-content: center; min-height: 500px;">
                        <?php if (!empty($product_images)): ?>
                        <img id="main-product-image" 
                             src="<?php echo BASE_PATH; ?>assets/img/products/<?php echo htmlspecialchars($product_images[0]); ?>" 
                             alt="<?php echo htmlspecialchars($product['title']); ?>"
                             style="max-width: 100%; max-height: 500px; object-fit: contain; cursor: zoom-in;"
                             onclick="openImageModal(this.src)"
                             onerror="this.src='<?php echo BASE_PATH; ?>assets/img/products/placeholder.jpg'; this.onerror=null;">
                        <?php else: ?>
                        <div class="d-flex align-items-center justify-content-center" style="min-height: 400px;">
                            <i class="bi bi-image text-muted" style="font-size: 5rem;"></i>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Right: Product Details (Quick View Style) -->
            <div class="qv-right" style="width: 55%; padding: 2rem; overflow-y: auto; max-height: 80vh;">
                <h1 class="qv-title"><?php echo htmlspecialchars($product['title']); ?></h1>
                
                <!-- Rating -->
                <?php if ($average_rating > 0 || $review_count > 0): ?>
                <div class="qv-rating">
                    <?php
                    $full_stars = floor($average_rating);
                    $has_half = ($average_rating - $full_stars) >= 0.5;
                    for ($i = 1; $i <= 5; $i++):
                        if ($i <= $full_stars): ?>
                            <i class="bi bi-star-fill text-warning"></i>
                        <?php elseif ($i == $full_stars + 1 && $has_half): ?>
                            <i class="bi bi-star-half text-warning"></i>
                        <?php else: ?>
                            <i class="bi bi-star text-muted"></i>
                        <?php endif;
                    endfor; ?>
                    <span class="qv-rating-text"><?php echo number_format($average_rating, 1); ?></span>
                    <span class="qv-reviews"><?php echo number_format($review_count); ?> Reviews</span>
                    <?php if ($sold_count > 0): ?>
                    <span class="qv-sold">| <?php echo number_format($sold_count); ?> sold</span>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

                <!-- Sale Banner -->
                <?php if ($is_sale): ?>
                <div class="qv-sale-banner">
                    <i class="bi bi-tag-fill"></i> WINTER SALE
                </div>
                <?php if ($deal_end_date): ?>
                <div class="qv-countdown" data-end="<?php echo strtotime($deal_end_date) * 1000; ?>">
                    Ends: <span class="qv-timer">00 : 00 : 00</span>
                </div>
                <?php endif; ?>
                <?php endif; ?>

                <!-- Pricing -->
                <div class="qv-pricing">
                    <div class="qv-price-main">₵<?php echo number_format($current_price, 2); ?></div>
                    <?php if ($original_price): ?>
                    <div class="qv-savings">Save ₵<?php echo number_format($savings, 2); ?></div>
                    <div class="qv-price-original">₵<?php echo number_format($original_price, 2); ?></div>
                    <?php endif; ?>
                    <div class="qv-tax-note text-muted small">Tax excluded, add at checkout if applicable</div>
                </div>

                <!-- Attributes (Color, Size, etc.) -->
                <?php if (!empty($attributes)): ?>
                <?php foreach ($attributes as $attrId => $attr): ?>
                <?php 
                $attrName = strtolower($attr['name']);
                $isColor = ($attrName === 'color' || $attrName === 'colour');
                ?>
                <div class="qv-attribute-group">
                    <label class="qv-attr-label">
                        <?php echo htmlspecialchars($attr['name']); ?>: 
                        <span class="qv-attr-selected" id="attr-selected-<?php echo $attrId; ?>"><?php echo htmlspecialchars($attr['values'][0]['value'] ?? ''); ?></span>
                    </label>
                    <div class="qv-attr-options <?php echo $isColor ? 'qv-colors' : 'qv-sizes'; ?>">
                        <?php foreach ($attr['values'] as $idx => $val): ?>
                        <?php if ($isColor): ?>
                        <div class="qv-color-option <?php echo $idx === 0 ? 'selected' : ''; ?>" 
                             data-value="<?php echo htmlspecialchars($val['value']); ?>"
                             data-attribute-id="<?php echo $attrId; ?>"
                             onclick="selectProductAttribute(this, '<?php echo htmlspecialchars($val['value']); ?>', <?php echo $attrId; ?>)"
                             style="background-color: <?php 
                                $colorMap = ['red' => '#ff0000', 'blue' => '#0000ff', 'green' => '#008000', 'black' => '#000000', 'white' => '#ffffff', 'yellow' => '#ffff00', 'orange' => '#ffa500', 'purple' => '#800080', 'pink' => '#ffc0cb', 'gray' => '#808080', 'grey' => '#808080', 'brown' => '#a52a2a', 'navy' => '#000080', 'beige' => '#f5f5dc', 'khaki' => '#c3b091'];
                                echo $colorMap[strtolower($val['value'])] ?? '#cccccc';
                             ?>;"
                             title="<?php echo htmlspecialchars($val['value']); ?>"></div>
                        <?php else: ?>
                        <button type="button" class="qv-size-option <?php echo $idx === 0 ? 'selected' : ''; ?>" 
                                data-value="<?php echo htmlspecialchars($val['value']); ?>"
                                data-attribute-id="<?php echo $attrId; ?>"
                                onclick="selectProductAttribute(this, '<?php echo htmlspecialchars($val['value']); ?>', <?php echo $attrId; ?>)">
                            <?php echo htmlspecialchars($val['value']); ?>
                        </button>
                        <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php endif; ?>

                <!-- Seller Info -->
                <div class="qv-seller">
                    <strong>Sold by</strong> 
                    <a href="<?php echo BASE_PATH; ?>index.php?seller=<?php echo $product['seller_id']; ?>"><?php echo htmlspecialchars($product['seller_name']); ?></a> 
                    <span class="badge bg-secondary">Trader</span>
                </div>

                <!-- Services -->
                <div class="qv-services">
                    <div class="qv-service-item">
                        <i class="bi bi-truck"></i>
                        <div>
                            <strong>Shipping:</strong> Calculated at checkout<br>
                            <small class="text-muted">Delivery: 5-10 business days</small>
                        </div>
                    </div>
                    <div class="qv-service-item">
                        <i class="bi bi-arrow-repeat"></i>
                        <div>
                            <strong>Return & Refund Policy</strong><br>
                            <small class="text-muted">30-day return policy</small>
                        </div>
                    </div>
                    <div class="qv-service-item">
                        <i class="bi bi-shield-check text-success"></i>
                        <div>
                            <strong>Security & Privacy</strong><br>
                            <small class="text-muted">Safe payments & secure personal details</small>
                        </div>
                    </div>
                </div>

                <!-- Quantity -->
                <div class="qv-quantity-group">
                    <label class="qv-quantity-label">Quantity:</label>
                    <div class="qv-quantity-controls">
                        <button type="button" class="qv-qty-btn" onclick="changeQuantity(-1)">-</button>
                        <input type="number" class="qv-qty-input" id="quantity" value="1" min="1" max="<?php echo max(1, (int)$product['stock']); ?>">
                        <button type="button" class="qv-qty-btn" onclick="changeQuantity(1)">+</button>
                    </div>
                    <span class="qv-stock"><?php echo $product['stock'] > 0 ? $product['stock'] . ' available' : 'Out of stock'; ?></span>
                </div>

                <!-- Actions -->
                <div class="qv-actions">
                    <?php if (isLoggedIn() && getUserRole() === 'buyer'): ?>
                        <?php if ($product['stock'] > 0): ?>
                        <button class="btn btn-danger btn-lg qv-btn-cart" id="add-to-cart-btn" 
                                data-product-id="<?php echo $product['id']; ?>"
                                data-product-name="<?php echo htmlspecialchars($product['title'], ENT_QUOTES); ?>"
                                data-product-price="<?php echo $current_price; ?>"
                                data-product-image="<?php echo htmlspecialchars($product['image'] ?? '', ENT_QUOTES); ?>">
                            <i class="bi bi-cart-plus"></i> Add to cart
                        </button>
                        <a href="<?php echo BASE_PATH; ?>buyers/checkout.php?product_id=<?php echo $product['id']; ?>" class="btn btn-outline-secondary btn-lg qv-btn-details">
                            Buy Now
                        </a>
                        <button class="btn btn-outline-secondary qv-btn-fav" onclick="toggleWishlist(<?php echo $product['id']; ?>)" title="Add to wishlist">
                            <i class="bi bi-heart"></i> <span class="qv-fav-count">0</span>
                        </button>
                        <?php else: ?>
                        <button class="btn btn-secondary btn-lg w-100" disabled>
                            <i class="bi bi-x-circle"></i> Out of Stock
                        </button>
                        <?php endif; ?>
                    <?php elseif (!isLoggedIn()): ?>
                        <div class="alert alert-info small mb-0 w-100">
                            <a href="<?php echo BASE_PATH; ?>login.php">Login</a> to add this product to your cart.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Description Section - AliExpress Style -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="product-description-section aliexpress-style">
                <div class="card border-0 shadow-sm product-description-card">
                    <!-- Tab Navigation -->
                    <div class="product-tabs-nav">
                        <ul class="nav nav-tabs border-0" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="description-tab" data-bs-toggle="tab" data-bs-target="#description-pane" type="button" role="tab">
                                    <i class="bi bi-file-text me-2"></i>Description
                                </button>
                            </li>
                            <?php if (!empty($attributes)): ?>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="specifications-tab" data-bs-toggle="tab" data-bs-target="#specifications-pane" type="button" role="tab">
                                    <i class="bi bi-list-check me-2"></i>Specifications
                                </button>
                            </li>
                            <?php endif; ?>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="shipping-tab" data-bs-toggle="tab" data-bs-target="#shipping-pane" type="button" role="tab">
                                    <i class="bi bi-truck me-2"></i>Shipping & Returns
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="reviews-tab-link" data-bs-toggle="tab" data-bs-target="#reviews-tab-pane" type="button" role="tab">
                                    <i class="bi bi-star me-2"></i>Reviews
                                </button>
                            </li>
                        </ul>
                    </div>

                    <!-- Tab Content -->
                    <div class="tab-content" id="productTabsContent">
                        <!-- Description Tab -->
                        <div class="tab-pane fade show active" id="description-pane" role="tabpanel" aria-labelledby="description-tab">
                            <div class="card-body p-0">
                                <div class="product-description-content" id="productDescriptionContent">
                                    <?php 
                                    if (!empty($product['description'])) {
                                        // Check if description contains HTML tags
                                        $has_html = strip_tags($product['description']) !== $product['description'];
                                        
                                        if ($has_html) {
                                            // Description already contains HTML, sanitize but preserve formatting
                                            $description = $product['description'];
                                            // Allow safe HTML tags including images, tables, divs, spans
                                            $allowed_tags = '<p><br><strong><b><em><i><u><ul><ol><li><h1><h2><h3><h4><h5><h6><a><img><blockquote><code><pre><table><thead><tbody><tr><td><th><div><span><style><section><article><header><footer>';
                                            $description = strip_tags($description, $allowed_tags);
                                            // Clean up style attributes for security
                                            $description = preg_replace('/<style[^>]*>.*?<\/style>/is', '', $description);
                                        } else {
                                            // Plain text description - format it nicely
                                            $description = htmlspecialchars($product['description'], ENT_QUOTES, 'UTF-8');
                                            // Convert line breaks to <br> tags
                                            $description = nl2br($description);
                                            // Convert double line breaks to paragraph breaks
                                            $description = preg_replace('/(<br\s*\/?>\s*){2,}/', '</p><p>', $description);
                                            // Wrap in paragraph tags
                                            if (!preg_match('/^<p>/', $description)) {
                                                $description = '<p>' . $description . '</p>';
                                            }
                                        }
                                        echo $description;
                                    } else {
                                        echo '<div class="empty-description">
                                            <i class="bi bi-info-circle text-muted" style="font-size: 3rem;"></i>
                                            <p class="text-muted fst-italic mt-3">No description provided for this product.</p>
                                        </div>';
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>

                        <!-- Specifications Tab -->
                        <?php if (!empty($attributes)): ?>
                        <div class="tab-pane fade" id="specifications-pane" role="tabpanel" aria-labelledby="specifications-tab">
                            <div class="card-body">
                                <div class="specifications-table">
                                    <table class="table table-bordered table-hover mb-0">
                                        <tbody>
                                            <?php foreach ($attributes as $attrId => $attr): ?>
                                            <tr>
                                                <td class="spec-label">
                                                    <strong><?php echo htmlspecialchars($attr['name']); ?></strong>
                                                </td>
                                                <td class="spec-value">
                                                    <?php 
                                                    $values = array_map(function($v) {
                                                        return htmlspecialchars($v['value']);
                                                    }, $attr['values']);
                                                    echo implode(', ', $values);
                                                    ?>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                            <tr>
                                                <td class="spec-label"><strong>Category</strong></td>
                                                <td class="spec-value"><?php echo htmlspecialchars($product['category_name']); ?>
                                                    <?php if (!empty($product['subcategory_name'])): ?>
                                                        / <?php echo htmlspecialchars($product['subcategory_name']); ?>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="spec-label"><strong>Seller</strong></td>
                                                <td class="spec-value"><?php echo htmlspecialchars($product['seller_name']); ?></td>
                                            </tr>
                                            <tr>
                                                <td class="spec-label"><strong>Stock Status</strong></td>
                                                <td class="spec-value">
                                                    <span class="badge <?php echo $product['stock'] > 0 ? 'bg-success' : 'bg-danger'; ?>">
                                                        <?php echo $product['stock'] > 0 ? $product['stock'] . ' in stock' : 'Out of stock'; ?>
                                                    </span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- Shipping & Returns Tab -->
                        <div class="tab-pane fade" id="shipping-pane" role="tabpanel" aria-labelledby="shipping-tab">
                            <div class="card-body">
                                <div class="shipping-info">
                                    <div class="shipping-item">
                                        <h6 class="shipping-title">
                                            <i class="bi bi-truck text-primary me-2"></i>Shipping Information
                                        </h6>
                                        <ul class="shipping-list">
                                            <li><i class="bi bi-check-circle text-success me-2"></i>Free shipping on orders over ₵100</li>
                                            <li><i class="bi bi-check-circle text-success me-2"></i>Estimated delivery: 3-7 business days</li>
                                            <li><i class="bi bi-check-circle text-success me-2"></i>Shipping to major cities in Ghana</li>
                                            <li><i class="bi bi-check-circle text-success me-2"></i>Tracking number provided for all orders</li>
                                        </ul>
                                    </div>
                                    <div class="shipping-item mt-4">
                                        <h6 class="shipping-title">
                                            <i class="bi bi-arrow-return-left text-primary me-2"></i>Return Policy
                                        </h6>
                                        <ul class="shipping-list">
                                            <li><i class="bi bi-check-circle text-success me-2"></i>7-day return policy for unused items</li>
                                            <li><i class="bi bi-check-circle text-success me-2"></i>Items must be in original packaging</li>
                                            <li><i class="bi bi-check-circle text-success me-2"></i>Return shipping costs covered by buyer</li>
                                            <li><i class="bi bi-check-circle text-success me-2"></i>Refund processed within 5-7 business days</li>
                                        </ul>
                                    </div>
                                    <div class="shipping-item mt-4">
                                        <h6 class="shipping-title">
                                            <i class="bi bi-shield-check text-primary me-2"></i>Warranty
                                        </h6>
                                        <p class="mb-0">This product comes with a manufacturer's warranty. Please contact the seller for warranty details.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Reviews Tab -->
                        <div class="tab-pane fade" id="reviews-tab-pane" role="tabpanel" aria-labelledby="reviews-tab-link">
                            <div class="card-body">
                                <div id="reviewsTabContent">
                                    <div class="text-center py-4">
                                        <p class="text-muted mb-3">Customer reviews and ratings</p>
                                        <button type="button" class="btn btn-primary" onclick="document.getElementById('reviewsSection').scrollIntoView({behavior: 'smooth'});">
                                            <i class="bi bi-arrow-down me-2"></i>View All Reviews
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reviews and Ratings Section -->
    <div class="row mt-4" id="reviewsSection">
        <div class="col-12">
            <div class="reviews-section">
                <div class="reviews-header">
                    <h3>
                        <i class="bi bi-star-fill"></i>
                        Customer Reviews
                    </h3>
                </div>

                <!-- Review Form (for logged in buyers) -->
                <?php if (isLoggedIn() && getUserRole() === 'buyer'): ?>
                <?php
                // Check if user has already reviewed this product
                $check_review_table = $conn->query("SHOW TABLES LIKE 'product_reviews'");
                $has_review_table = $check_review_table->num_rows > 0;
                $user_reviewed = false;
                
                if ($has_review_table) {
                    $user_id = getUserId();
                    $check_user_review = $conn->prepare("SELECT id FROM product_reviews WHERE product_id = ? AND user_id = ?");
                    $check_user_review->bind_param("ii", $product_id, $user_id);
                    $check_user_review->execute();
                    $user_reviewed = $check_user_review->get_result()->num_rows > 0;
                    $check_user_review->close();
                }
                ?>
                <?php if (!$user_reviewed): ?>
                <div class="review-form-card">
                    <h5>
                        <i class="bi bi-pencil-square"></i>
                        Write a Review
                    </h5>
                    <form id="reviewForm" class="review-form">
                        <div class="form-group">
                            <label class="form-label">Your Rating <span class="text-danger">*</span></label>
                            <div class="star-rating-input">
                                <input type="radio" id="star5" name="rating" value="5" required>
                                <label for="star5"><i class="bi bi-star-fill"></i></label>
                                <input type="radio" id="star4" name="rating" value="4" required>
                                <label for="star4"><i class="bi bi-star-fill"></i></label>
                                <input type="radio" id="star3" name="rating" value="3" required>
                                <label for="star3"><i class="bi bi-star-fill"></i></label>
                                <input type="radio" id="star2" name="rating" value="2" required>
                                <label for="star2"><i class="bi bi-star-fill"></i></label>
                                <input type="radio" id="star1" name="rating" value="1" required>
                                <label for="star1"><i class="bi bi-star-fill"></i></label>
                            </div>
                            <div class="rating-label"></div>
                        </div>
                        <div class="form-group">
                            <label for="reviewTitle" class="form-label">Review Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="reviewTitle" name="title" 
                                   placeholder="Give your review a title" required minlength="3" maxlength="255">
                        </div>
                        <div class="form-group">
                            <label for="reviewText" class="form-label">Your Review <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="reviewText" name="review_text" 
                                      placeholder="Share your experience with this product..." 
                                      required minlength="10" maxlength="2000"></textarea>
                            <small class="text-muted">Minimum 10 characters</small>
                        </div>
                        <button type="submit" class="btn btn-submit-review">
                            <i class="bi bi-check-circle me-2"></i>Submit Review
                        </button>
                    </form>
                </div>
                <?php else: ?>
                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>You have already reviewed this product.
                </div>
                <?php endif; ?>
                <?php endif; ?>

                <!-- Reviews List -->
                <div id="reviewsLoading" class="reviews-loading" style="display: none;">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading reviews...</span>
                    </div>
                </div>
                <div id="reviewsList" class="reviews-list">
                    <!-- Reviews will be loaded here via JavaScript -->
                </div>
                <div id="reviewsPagination"></div>
            </div>
        </div>
    </div>

    <?php if (isLoggedIn() && getUserRole() === 'buyer' && $product['stock'] > 0): ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const addToCartBtn = document.getElementById('add-to-cart-btn');
        if (addToCartBtn) {
            addToCartBtn.addEventListener('click', function() {
                const productId = parseInt(this.getAttribute('data-product-id'));
                const productName = this.getAttribute('data-product-name');
                const productPrice = parseFloat(this.getAttribute('data-product-price'));
                const productImage = this.getAttribute('data-product-image');
                const qtyInput = document.getElementById('quantity');
                const quantity = Math.max(1, parseInt(qtyInput ? qtyInput.value : '1') || 1);

                if (quantity > <?php echo (int)$product['stock']; ?>) {
                    alert('Only <?php echo (int)$product['stock']; ?> item(s) available.');
                    return;
                }

                if (typeof addToCart === 'function') {
                    const options = {};
                    document.querySelectorAll('.product-option-select').forEach(select => {
                        const attrName = select.getAttribute('data-attribute-name');
                        const selectedOption = select.options[select.selectedIndex];
                        if (selectedOption && selectedOption.value) {
                            options[attrName] = selectedOption.text;
                        }
                    });

                    const optionSelects = document.querySelectorAll('.product-option-select');
                    if (optionSelects.length > 0) {
                        for (const select of optionSelects) {
                            if (!select.value) {
                                alert('Please choose ' + select.getAttribute('data-attribute-name') + ' before adding to cart.');
                                return;
                            }
                        }
                    }

                    for (let i = 0; i < quantity; i++) {
                        addToCart(productId, productName, productPrice, productImage, options);
                    }
                } else {
                    console.error('addToCart function not found');
                    alert('Error: Cart functionality not loaded. Please refresh the page.');
                }
            });
        }
    });
    </script>
    <?php endif; ?>
</div>

<!-- Image Modal -->
<div class="modal fade" id="imageModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?php echo htmlspecialchars($product['title']); ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center p-4">
                <img id="modal-image" src="" class="img-fluid rounded" alt="Product Image" 
                     style="max-width: 100%; max-height: 70vh; object-fit: contain;"
                     onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'400\' height=\'300\'%3E%3Crect fill=\'%23f8f9fa\' width=\'400\' height=\'300\'/%3E%3Ctext x=\'50%25\' y=\'50%25\' text-anchor=\'middle\' dy=\'.3em\' fill=\'%23999\' font-family=\'sans-serif\' font-size=\'18\'%3EImage not available%3C/text%3E%3C/svg%3E';">
                <div id="modal-loading" class="text-center mt-3" style="display: none;">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function changeMainImage(imageSrc, thumbnail) {
    document.getElementById('main-product-image').src = imageSrc;
    
    // Update active thumbnail (both old and new style)
    document.querySelectorAll('.thumbnail-img').forEach(img => {
        img.classList.remove('active');
    });
    document.querySelectorAll('.qv-thumb').forEach(thumb => {
        thumb.classList.remove('active');
        thumb.style.borderColor = 'transparent';
    });
    
    if (thumbnail) {
        if (thumbnail.classList) {
            thumbnail.classList.add('active');
        }
        if (thumbnail.style) {
            thumbnail.style.borderColor = '#0d6efd';
        }
    }
}

// Product attribute selection (Quick View Style)
function selectProductAttribute(element, value, attributeId) {
    // Remove selected class from siblings
    const parent = element.closest('.qv-attr-options');
    if (parent) {
        parent.querySelectorAll('.qv-color-option, .qv-size-option').forEach(el => {
            el.classList.remove('selected');
            if (el.classList.contains('qv-size-option')) {
                el.style.borderColor = '#e2e8f0';
                el.style.background = '#fff';
                el.style.color = '';
                el.style.fontWeight = '';
            } else {
                el.style.borderColor = 'transparent';
                el.style.boxShadow = '';
            }
        });
    }
    
    // Add selected class to clicked element
    element.classList.add('selected');
    if (element.classList.contains('qv-size-option')) {
        element.style.borderColor = '#0d6efd';
        element.style.background = '#0d6efd';
        element.style.color = '#fff';
        element.style.fontWeight = '600';
    } else {
        element.style.borderColor = '#0d6efd';
        element.style.boxShadow = '0 0 0 2px rgba(13, 110, 253, 0.2)';
    }
    
    // Update selected text
    const selectedSpan = document.getElementById('attr-selected-' + attributeId);
    if (selectedSpan) {
        selectedSpan.textContent = value;
    }
    
    // Update hidden select (for form submission)
    const select = document.querySelector(`.product-option-select[data-attribute-id="${attributeId}"]`);
    if (select) {
        const option = Array.from(select.options).find(opt => opt.textContent.trim() === value);
        if (option) {
            select.value = option.value;
        }
    }
}

// Quantity controls
function changeQuantity(delta) {
    const qtyInput = document.getElementById('quantity');
    if (qtyInput) {
        const current = parseInt(qtyInput.value) || 1;
        const max = parseInt(qtyInput.max) || 999;
        const min = parseInt(qtyInput.min) || 1;
        const newValue = Math.max(min, Math.min(max, current + delta));
        qtyInput.value = newValue;
    }
}

function openImageModal(imageSrc) {
    const modalImage = document.getElementById('modal-image');
    const modalLoading = document.getElementById('modal-loading');
    
    // Show loading indicator
    modalLoading.style.display = 'block';
    modalImage.style.display = 'none';
    
    // Set image source
    modalImage.src = imageSrc;
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('imageModal'));
    modal.show();
    
    // Hide loading and show image when loaded
    modalImage.onload = function() {
        modalLoading.style.display = 'none';
        modalImage.style.display = 'block';
    };
    
    // Handle image load error
    modalImage.onerror = function() {
        modalLoading.style.display = 'none';
        modalImage.style.display = 'block';
    };
}

// Add thumbnail hover effect
document.querySelectorAll('.thumbnail-img').forEach(img => {
    img.addEventListener('mouseenter', function() {
        this.style.opacity = '0.8';
    });
    img.addEventListener('mouseleave', function() {
        this.style.opacity = '1';
    });
});

// AliExpress Style Tab Functionality
document.addEventListener('DOMContentLoaded', function() {
    // Handle Reviews tab click - scroll to reviews section
    const reviewsTabLink = document.getElementById('reviews-tab-link');
    if (reviewsTabLink) {
        reviewsTabLink.addEventListener('click', function(e) {
            // Wait a bit for tab to show, then scroll
            setTimeout(function() {
                const reviewsSection = document.getElementById('reviewsSection');
                if (reviewsSection) {
                    reviewsSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            }, 100);
        });
    }
    
    // Make description images clickable to open in modal
    const descriptionContent = document.getElementById('productDescriptionContent');
    if (descriptionContent) {
        const images = descriptionContent.querySelectorAll('img');
        images.forEach(function(img) {
            img.style.cursor = 'pointer';
            img.addEventListener('click', function() {
                openImageModal(this.src);
            });
        });
    }
    
    // Initialize Bootstrap tabs if not already initialized
    const tabTriggerList = [].slice.call(document.querySelectorAll('#productTabsContent button[data-bs-toggle="tab"]'));
    tabTriggerList.forEach(function(tabTriggerEl) {
        const tabTrigger = new bootstrap.Tab(tabTriggerEl);
    });
    
    // Initialize countdown timer if sale exists
    const countdownEl = document.querySelector('.qv-countdown');
    if (countdownEl && countdownEl.dataset.end) {
        const endTime = parseInt(countdownEl.dataset.end);
        if (endTime > Date.now()) {
            function updateCountdown() {
                const now = Date.now();
                const distance = endTime - now;
                
                if (distance < 0) {
                    countdownEl.innerHTML = 'Deal Ended';
                    return;
                }
                
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                
                const timerEl = countdownEl.querySelector('.qv-timer');
                if (timerEl) {
                    timerEl.textContent = `${String(hours).padStart(2, '0')} : ${String(minutes).padStart(2, '0')} : ${String(seconds).padStart(2, '0')}`;
                }
            }
            
            updateCountdown();
            setInterval(updateCountdown, 1000);
        }
    }
    
    // Add hover effects to qv-thumb
    document.querySelectorAll('.qv-thumb').forEach(thumb => {
        thumb.addEventListener('mouseenter', function() {
            if (!this.classList.contains('active')) {
                this.style.borderColor = '#0d6efd';
            }
        });
        thumb.addEventListener('mouseleave', function() {
            if (!this.classList.contains('active')) {
                this.style.borderColor = 'transparent';
            }
        });
    });
});
</script>

<style>
/* Product Image Styles */
.thumbnail-img {
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.thumbnail-img:hover {
    border-color: var(--primary-color);
    transform: scale(1.05);
}

.thumbnail-img.active {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 2px rgba(13, 110, 253, 0.25);
}

#main-product-image {
    background: #fff;
    border: 1px solid #e0e0e0;
    transition: transform 0.3s ease;
}

#main-product-image:hover {
    transform: scale(1.02);
}

/* AliExpress Style Product Description */
.product-description-section.aliexpress-style {
    margin-top: 2.5rem;
}

.product-description-card {
    border-radius: 0.5rem;
    overflow: hidden;
    border: 1px solid #e0e0e0;
    background: #ffffff;
}

/* Tab Navigation - AliExpress Style */
.product-tabs-nav {
    background: #ffffff;
    border-bottom: 2px solid #f0f0f0;
}

.product-tabs-nav .nav-tabs {
    border: none;
    margin: 0;
    padding: 0;
    display: flex;
    flex-wrap: wrap;
}

.product-tabs-nav .nav-item {
    margin: 0;
}

.product-tabs-nav .nav-link {
    border: none;
    border-bottom: 3px solid transparent;
    padding: 1rem 1.5rem;
    color: #666;
    font-weight: 500;
    font-size: 0.95rem;
    background: transparent;
    transition: all 0.3s ease;
    position: relative;
    margin-right: 0.5rem;
}

.product-tabs-nav .nav-link:hover {
    color: #ff6a00;
    background: #fff5f0;
    border-bottom-color: transparent;
}

.product-tabs-nav .nav-link.active {
    color: #ff6a00;
    background: transparent;
    border-bottom-color: #ff6a00;
    font-weight: 600;
}

.product-tabs-nav .nav-link i {
    font-size: 1rem;
}

/* Tab Content */
.product-description-card .tab-content {
    background: #ffffff;
}

.product-description-card .tab-pane {
    min-height: 200px;
}

.product-description-card .card-body {
    padding: 2rem;
    background: #ffffff;
}

.product-description-card .card-body.p-0 {
    padding: 0;
}

/* Description Content - AliExpress Style */
.product-description-content {
    line-height: 1.8;
    color: #333;
    font-size: 1rem;
    word-wrap: break-word;
    overflow-wrap: break-word;
    padding: 2rem;
}

.product-description-content img {
    max-width: 100%;
    height: auto;
    border-radius: 0.25rem;
    margin: 1rem 0;
    display: block;
}

.product-description-content table {
    width: 100%;
    border-collapse: collapse;
    margin: 1.5rem 0;
}

.product-description-content table td,
.product-description-content table th {
    padding: 0.75rem;
    border: 1px solid #e0e0e0;
}

.product-description-content table th {
    background: #f8f9fa;
    font-weight: 600;
}

.product-description-content p {
    margin-bottom: 1.25rem;
    color: #4a5568;
}

.product-description-content h1,
.product-description-content h2,
.product-description-content h3,
.product-description-content h4,
.product-description-content h5,
.product-description-content h6 {
    color: #1a202c;
    font-weight: 700;
    margin-top: 1.5rem;
    margin-bottom: 1rem;
    line-height: 1.3;
}

.product-description-content h1 { font-size: 2rem; }
.product-description-content h2 { font-size: 1.75rem; }
.product-description-content h3 { font-size: 1.5rem; }
.product-description-content h4 { font-size: 1.25rem; }

.product-description-content ul,
.product-description-content ol {
    margin: 1rem 0;
    padding-left: 2rem;
    color: #4a5568;
}

.product-description-content li {
    margin-bottom: 0.5rem;
    line-height: 1.7;
}

.product-description-content strong,
.product-description-content b {
    color: #1a202c;
    font-weight: 700;
}

.product-description-content em,
.product-description-content i {
    font-style: italic;
    color: #4a5568;
}

.product-description-content a {
    color: #0d6efd;
    text-decoration: none;
    border-bottom: 1px solid transparent;
    transition: all 0.2s ease;
}

.product-description-content a:hover {
    color: #0a58ca;
    border-bottom-color: #0a58ca;
}

.product-description-content blockquote {
    border-left: 4px solid #0d6efd;
    padding-left: 1.5rem;
    margin: 1.5rem 0;
    font-style: italic;
    color: #4a5568;
    background: #f8f9fa;
    padding: 1rem 1.5rem;
    border-radius: 0.5rem;
}

.product-description-content code {
    background: #f1f3f5;
    padding: 0.2rem 0.4rem;
    border-radius: 0.25rem;
    font-family: 'Courier New', monospace;
    font-size: 0.9em;
    color: #e83e8c;
}

.product-description-content pre {
    background: #1a202c;
    color: #e2e8f0;
    padding: 1rem;
    border-radius: 0.5rem;
    overflow-x: auto;
    margin: 1.5rem 0;
}

.product-description-content pre code {
    background: transparent;
    color: inherit;
    padding: 0;
}

.product-description-content img {
    max-width: 100%;
    height: auto;
    border-radius: 0.5rem;
    margin: 1.5rem 0;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.empty-description {
    text-align: center;
    padding: 3rem 1rem;
    color: #718096;
}

.description-footer {
    text-align: center;
    padding-top: 1rem;
    border-top: 1px solid #e2e8f0;
}

.description-footer .btn {
    border-radius: 2rem;
    padding: 0.5rem 1.5rem;
    font-weight: 600;
    transition: all 0.3s ease;
}

.description-footer .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(13, 110, 253, 0.3);
}

/* Specifications Table */
.specifications-table {
    margin: 0;
}

.specifications-table .table {
    margin: 0;
    border: 1px solid #e0e0e0;
}

.specifications-table .spec-label {
    width: 35%;
    background: #f8f9fa;
    font-weight: 600;
    color: #333;
    vertical-align: middle;
    padding: 1rem 1.5rem;
}

.specifications-table .spec-value {
    color: #666;
    padding: 1rem 1.5rem;
    vertical-align: middle;
}

.specifications-table .table-hover tbody tr:hover {
    background: #f8f9fa;
}

/* Shipping & Returns */
.shipping-info {
    padding: 0;
}

.shipping-item {
    margin-bottom: 2rem;
}

.shipping-item:last-child {
    margin-bottom: 0;
}

.shipping-title {
    color: #333;
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid #f0f0f0;
}

.shipping-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.shipping-list li {
    padding: 0.75rem 0;
    color: #666;
    font-size: 0.95rem;
    border-bottom: 1px solid #f5f5f5;
}

.shipping-list li:last-child {
    border-bottom: none;
}

.shipping-list li i {
    font-size: 1rem;
}

/* Responsive */
@media (max-width: 768px) {
    .product-tabs-nav .nav-link {
        padding: 0.75rem 1rem;
        font-size: 0.85rem;
    }
    
    .product-tabs-nav .nav-link i {
        font-size: 0.9rem;
    }
    
    .product-description-card .card-body {
        padding: 1.5rem;
    }
    
    .product-description-content {
        padding: 1.5rem;
        font-size: 0.95rem;
    }
    
    .specifications-table .spec-label,
    .specifications-table .spec-value {
        padding: 0.75rem 1rem;
        font-size: 0.9rem;
    }
    
    .specifications-table .spec-label {
        width: 40%;
    }
}

/* Product Info Cards */
.product-info-card {
    background: #f8f9fa;
    border-radius: 0.5rem;
    padding: 1rem;
    margin-bottom: 1rem;
    border-left: 4px solid var(--primary-color);
}

.product-price-section {
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
    padding: 1.5rem;
    border-radius: 0.5rem;
    border: 2px solid #e9ecef;
    margin-bottom: 1.5rem;
}

.product-price-section h2 {
    font-size: 2.5rem;
    font-weight: 700;
    margin: 0;
}

.product-stock-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border-radius: 2rem;
    font-weight: 500;
}

.product-stock-badge.in-stock {
    background: #d1e7dd;
    color: #0f5132;
}

.product-stock-badge.out-of-stock {
    background: #f8d7da;
    color: #842029;
}

/* Image Modal Styles */
#imageModal .modal-content {
    border-radius: 0.5rem;
    overflow: hidden;
}

#imageModal .modal-header {
    background: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
}

#imageModal .modal-body {
    background: #ffffff;
    min-height: 300px;
    display: flex;
    align-items: center;
    justify-content: center;
}

#modal-image {
    transition: opacity 0.3s ease;
}

/* Product Page - Quick View Style Responsive */
@media (max-width: 991.98px) {
    .product-page .qv-body {
        flex-direction: column;
    }
    
    .product-page .qv-left, 
    .product-page .qv-right {
        width: 100% !important;
    }
    
    .product-page .qv-left {
        padding: 1rem !important;
    }
    
    .product-page .qv-gallery {
        flex-direction: column !important;
    }
    
    .product-page .qv-thumbnails {
        flex-direction: row !important;
        width: 100% !important;
        overflow-x: auto;
        padding-bottom: 0.5rem;
        gap: 0.5rem;
    }
    
    .product-page .qv-thumb {
        flex-shrink: 0;
    }
    
    .product-page .qv-main-image {
        min-height: 300px !important;
        max-height: 50vh !important;
    }
    
    .product-page .qv-main-image img {
        max-height: calc(50vh - 1.5rem) !important;
    }
    
    .product-page .qv-right {
        max-height: none !important;
        padding: 1.5rem !important;
    }
    
    .product-page .qv-title {
        font-size: 1.3rem !important;
    }
    
    .product-page .qv-price-main {
        font-size: 1.75rem !important;
    }
    
    .product-page .qv-actions {
        flex-direction: column;
    }
    
    .product-page .qv-btn-cart, 
    .product-page .qv-btn-details {
        width: 100% !important;
    }
}

@media (max-width: 576px) {
    .product-page .qv-left {
        padding: 0.75rem !important;
    }
    
    .product-page .qv-thumb {
        width: 60px !important;
        height: 60px !important;
    }
    
    .product-page .qv-right {
        padding: 1rem !important;
    }
    
    .product-page .qv-title {
        font-size: 1.15rem !important;
    }
    
    .product-page .qv-price-main {
        font-size: 1.5rem !important;
    }
}
</style>

<?php
closeDBConnection($conn);
include 'includes/footer.php';
?>

