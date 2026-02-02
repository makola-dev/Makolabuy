<?php
/**
 * Homepage
 * Makola
 */
require_once 'config/db.php';
require_once 'config/paths.php';
require_once 'includes/auth.php';

$conn = getDBConnection();

// Get search query
$search = trim($_GET['search'] ?? '');
$category_id = $_GET['category'] ?? '';
$subcategory_id = $_GET['subcategory'] ?? '';
$page = $_GET['page'] ?? 'home';

// Pagination
$page_num = isset($_GET['p']) ? (int)$_GET['p'] : 1;
$per_page = 12;
$offset = ($page_num - 1) * $per_page;

// Get categories
$categories_query = "SELECT * FROM categories ORDER BY name";
$categories_result = $conn->query($categories_query);

// Check if subcategory_id column exists
$check_subcat_col = $conn->query("SHOW COLUMNS FROM products LIKE 'subcategory_id'");
$has_subcategory_col = $check_subcat_col->num_rows > 0;

// Check if subcategories table exists
$check_subcat_table = $conn->query("SHOW TABLES LIKE 'subcategories'");
$has_subcat_table = $check_subcat_table->num_rows > 0;

// Build products query
$where_conditions = ["p.status = 'approved'"];
$params = [];
$types = '';

if (!empty($search)) {
    $where_conditions[] = "(p.title LIKE ? OR p.description LIKE ?)";
    $search_param = "%$search%";
    $params[] = $search_param;
    $params[] = $search_param;
    $types .= 'ss';
}

if (!empty($category_id)) {
    $where_conditions[] = "p.category_id = ?";
    $params[] = $category_id;
    $types .= 'i';
}

if (!empty($subcategory_id) && $has_subcategory_col) {
    $where_conditions[] = "p.subcategory_id = ?";
    $params[] = $subcategory_id;
    $types .= 'i';
}

$where_clause = implode(' AND ', $where_conditions);

// Get total count (with JOINs for accurate count)
$count_query = "SELECT COUNT(*) as total 
                FROM products p 
                JOIN users u ON p.seller_id = u.id 
                JOIN categories c ON p.category_id = c.id 
                WHERE $where_clause";
$count_stmt = $conn->prepare($count_query);
if (!empty($params)) {
    $count_stmt->bind_param($types, ...$params);
}
$count_stmt->execute();
$total_products = $count_stmt->get_result()->fetch_assoc()['total'];
$total_pages = ceil($total_products / $per_page);

// Get products - use the already checked variables
if ($has_subcategory_col && $has_subcat_table) {
    $products_query = "SELECT p.*, u.username as seller_name, c.name as category_name, sc.name as subcategory_name
                       FROM products p 
                       JOIN users u ON p.seller_id = u.id 
                       JOIN categories c ON p.category_id = c.id 
                       LEFT JOIN subcategories sc ON p.subcategory_id = sc.id
                       WHERE $where_clause 
                       ORDER BY p.created_at DESC 
                       LIMIT ? OFFSET ?";
} else {
    $products_query = "SELECT p.*, u.username as seller_name, c.name as category_name, NULL as subcategory_name
                       FROM products p 
                       JOIN users u ON p.seller_id = u.id 
                       JOIN categories c ON p.category_id = c.id 
                       WHERE $where_clause 
                       ORDER BY p.created_at DESC 
                       LIMIT ? OFFSET ?";
}
$products_stmt = $conn->prepare($products_query);
$params[] = $per_page;
$params[] = $offset;
$types .= 'ii';
$products_stmt->bind_param($types, ...$params);
$products_stmt->execute();
$products_result = $products_stmt->get_result();

$pageTitle = 'Home';
include 'includes/header.php';
?>

<!-- Hero Banner/Slider -->
<?php if ($page === 'home' && empty($search) && empty($category_id)): ?>
<div class="slide-up">
<?php
// Check if banners table exists
$check_banners = $conn->query("SHOW TABLES LIKE 'banners'");
$has_banners = $check_banners->num_rows > 0;
$banners = [];

if ($has_banners) {
    $banners_query = "SELECT * FROM banners WHERE is_active = 1 AND (start_date IS NULL OR start_date <= NOW()) AND (end_date IS NULL OR end_date >= NOW()) ORDER BY display_order LIMIT 5";
    $banners_result = $conn->query($banners_query);
    while ($banner = $banners_result->fetch_assoc()) {
        $banners[] = $banner;
    }
}

// Get featured products
$check_featured = $conn->query("SHOW COLUMNS FROM products LIKE 'is_featured'");
$has_featured = $check_featured->num_rows > 0;
$featured_products = [];

if ($has_featured) {
    $featured_query = "SELECT p.*, u.username as seller_name, c.name as category_name 
                      FROM products p 
                      JOIN users u ON p.seller_id = u.id 
                      JOIN categories c ON p.category_id = c.id 
                      WHERE p.status = 'approved' AND p.is_featured = 1 
                      ORDER BY p.created_at DESC 
                      LIMIT 8";
    $featured_result = $conn->query($featured_query);
    while ($fp = $featured_result->fetch_assoc()) {
        $featured_products[] = $fp;
    }
}

// Get deal products
$deal_products = [];
if ($has_featured) {
    $deal_query = "SELECT p.*, u.username as seller_name, c.name as category_name 
                   FROM products p 
                   JOIN users u ON p.seller_id = u.id 
                   JOIN categories c ON p.category_id = c.id 
                   WHERE p.status = 'approved' AND p.is_deal = 1 AND (p.deal_end_date IS NULL OR p.deal_end_date >= NOW()) 
                   ORDER BY p.created_at DESC 
                   LIMIT 8";
    $deal_result = $conn->query($deal_query);
    while ($dp = $deal_result->fetch_assoc()) {
        $deal_products[] = $dp;
    }
}

// Get best sellers (top selling products)
$check_sales_count = $conn->query("SHOW COLUMNS FROM products LIKE 'sales_count'");
$has_sales_count = $check_sales_count->num_rows > 0;
$best_sellers = [];
if ($has_sales_count) {
    $bestseller_query = "SELECT p.*, u.username as seller_name, c.name as category_name 
                        FROM products p 
                        JOIN users u ON p.seller_id = u.id 
                        JOIN categories c ON p.category_id = c.id 
                        WHERE p.status = 'approved' AND p.sales_count > 0
                        ORDER BY p.sales_count DESC, p.average_rating DESC 
                        LIMIT 8";
    $bestseller_result = $conn->query($bestseller_query);
    while ($bs = $bestseller_result->fetch_assoc()) {
        $best_sellers[] = $bs;
    }
}

// Get top rated products
$check_rating = $conn->query("SHOW COLUMNS FROM products LIKE 'average_rating'");
$has_rating = $check_rating->num_rows > 0;
$top_rated = [];
if ($has_rating) {
    $toprated_query = "SELECT p.*, u.username as seller_name, c.name as category_name 
                      FROM products p 
                      JOIN users u ON p.seller_id = u.id 
                      JOIN categories c ON p.category_id = c.id 
                      WHERE p.status = 'approved' AND p.average_rating >= 4.0 AND p.review_count >= 3
                      ORDER BY p.average_rating DESC, p.review_count DESC 
                      LIMIT 8";
    $toprated_result = $conn->query($toprated_query);
    while ($tr = $toprated_result->fetch_assoc()) {
        $top_rated[] = $tr;
    }
}

// Get new arrivals (products published within last 24 hours)
$new_arrivals = [];
$newarrivals_query = "SELECT p.*, u.username as seller_name, c.name as category_name
                     FROM products p
                     JOIN users u ON p.seller_id = u.id
                     JOIN categories c ON p.category_id = c.id
                     WHERE p.status = 'approved' AND p.created_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
                     ORDER BY p.created_at DESC
                     LIMIT 8";
$newarrivals_result = $conn->query($newarrivals_query);
while ($na = $newarrivals_result->fetch_assoc()) {
    $new_arrivals[] = $na;
}

// Get recently viewed products (for logged in users) - Only last 24 hours
$recently_viewed = [];
if (isLoggedIn()) {
    $check_recent_table = $conn->query("SHOW TABLES LIKE 'recently_viewed'");
    $has_recent_table = $check_recent_table->num_rows > 0;
    if ($has_recent_table) {
        $user_id = getUserId();
        // Only get products viewed within the last 24 hours
        $recent_query = "SELECT p.*, u.username as seller_name, c.name as category_name, rv.viewed_at
                       FROM recently_viewed rv
                       JOIN products p ON rv.product_id = p.id
                       JOIN users u ON p.seller_id = u.id 
                       JOIN categories c ON p.category_id = c.id 
                       WHERE rv.user_id = ? 
                       AND p.status = 'approved'
                       AND rv.viewed_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
                       ORDER BY rv.viewed_at DESC 
                       LIMIT 8";
        $recent_stmt = $conn->prepare($recent_query);
        $recent_stmt->bind_param("i", $user_id);
        $recent_stmt->execute();
        $recent_result = $recent_stmt->get_result();
        while ($rv = $recent_result->fetch_assoc()) {
            $recently_viewed[] = $rv;
        }
        $recent_stmt->close();
        
        // Clean up old entries (older than 24 hours) for this user
        $cleanup_query = "DELETE FROM recently_viewed 
                         WHERE user_id = ? 
                         AND viewed_at < DATE_SUB(NOW(), INTERVAL 24 HOUR)";
        $cleanup_stmt = $conn->prepare($cleanup_query);
        $cleanup_stmt->bind_param("i", $user_id);
        $cleanup_stmt->execute();
        $cleanup_stmt->close();
    }
}

// Get platform stats
$platform_stats = [];
$stats_query = "SELECT 
                (SELECT COUNT(*) FROM products WHERE status = 'approved') as total_products,
                (SELECT COUNT(DISTINCT seller_id) FROM products WHERE status = 'approved') as total_sellers,
                (SELECT COUNT(*) FROM orders WHERE order_status != 'cancelled') as total_orders";
$stats_result = $conn->query($stats_query);
$platform_stats = $stats_result->fetch_assoc();
?>

<!-- Hero Banner Carousel -->
<?php if (!empty($banners)): ?>
<div id="heroCarousel" class="carousel slide mb-4 hero-carousel" data-bs-ride="carousel">
    <div class="carousel-indicators">
        <?php foreach ($banners as $index => $banner): ?>
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="<?php echo $index; ?>" <?php echo $index === 0 ? 'class="active"' : ''; ?>></button>
        <?php endforeach; ?>
    </div>
    <div class="carousel-inner">
        <?php foreach ($banners as $index => $banner): ?>
        <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?> position-relative">
            <?php 
            // Check if the banner is a video or image
            $file_extension = strtolower(pathinfo($banner['image'], PATHINFO_EXTENSION));
            $is_video = in_array($file_extension, ['mp4', 'webm', 'ogg']);
            ?>
            
            <?php if ($is_video): ?>
                <!-- Video Banner -->
                <?php if (!empty($banner['link_url'])): ?>
                <a href="<?php echo htmlspecialchars($banner['link_url']); ?>" class="d-block">
                    <video 
                        class="d-block w-100 hero-carousel-img banner-responsive" 
                        autoplay 
                        muted 
                        loop 
                        playsinline
                        poster="<?php echo BASE_PATH; ?>assets/img/banners/<?php echo str_replace(['.mp4', '.webm', '.ogg'], '.jpg', $banner['image']); ?>">
                        <source src="<?php echo BASE_PATH; ?>assets/img/banners/<?php echo htmlspecialchars($banner['image']); ?>" type="video/<?php echo $file_extension; ?>">
                        Your browser does not support the video tag.
                    </video>
                </a>
                <?php else: ?>
                <video 
                    class="d-block w-100 hero-carousel-img banner-responsive" 
                    autoplay 
                    muted 
                    loop 
                    playsinline
                    poster="<?php echo BASE_PATH; ?>assets/img/banners/<?php echo str_replace(['.mp4', '.webm', '.ogg'], '.jpg', $banner['image']); ?>">
                    <source src="<?php echo BASE_PATH; ?>assets/img/banners/<?php echo htmlspecialchars($banner['image']); ?>" type="video/<?php echo $file_extension; ?>">
                    Your browser does not support the video tag.
                </video>
                <?php endif; ?>
            <?php else: ?>
                <!-- Image Banner -->
                <?php if (!empty($banner['link_url'])): ?>
                <a href="<?php echo htmlspecialchars($banner['link_url']); ?>">
                    <img src="<?php echo BASE_PATH; ?>assets/img/banners/<?php echo htmlspecialchars($banner['image']); ?>" 
                         class="d-block w-100 hero-carousel-img banner-responsive" 
                         alt="<?php echo htmlspecialchars($banner['title'] ?? 'Makola banner'); ?>"
                         loading="eager"
                         decoding="async"
                         fetchpriority="high">
                </a>
                <?php else: ?>
                <img src="<?php echo BASE_PATH; ?>assets/img/banners/<?php echo htmlspecialchars($banner['image']); ?>" 
                     class="d-block w-100 hero-carousel-img banner-responsive" 
                     alt="<?php echo htmlspecialchars($banner['title'] ?? 'Makola banner'); ?>"
                     loading="eager"
                     decoding="async"
                     fetchpriority="high">
                <?php endif; ?>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon"></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon"></span>
    </button>
</div>
<?php else: ?>
<!-- Default Hero Banner -->
<div class="hero-banner bg-gradient-primary text-white py-5 mb-4 position-relative overflow-hidden">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="display-4 fw-bold mb-3">Buy &amp; Sell Everything in Makola</h1>
                <p class="lead mb-3">Discover products from trusted local sellers and grow your business.</p>
                <?php if (!empty($platform_stats)): ?>
                <p class="mb-4 small text-white-50">
                    Over <strong><?php echo number_format($platform_stats['total_products'] ?? 0); ?></strong> products from 
                    <strong><?php echo number_format($platform_stats['total_sellers'] ?? 0); ?></strong> sellers.
                </p>
                <?php endif; ?>

                <!-- Hero Search -->
                <!-- Search bar removed as requested -->
                <!-- <form action="<?php echo BASE_PATH; ?>index.php" method="get" class="hero-search mb-4">
                    <input type="hidden" name="page" value="home">
                    <div class="input-group input-group-lg shadow-sm">
                        <input 
                            type="text" 
                            name="search" 
                            class="form-control" 
                            placeholder="Search for products, categories or sellers..."
                            aria-label="Search products"
                        >
                        <button class="btn btn-primary" type="submit">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </form> -->

                <div class="d-grid d-sm-flex gap-3">
                    <a href="<?php echo BASE_PATH; ?>register.php" class="btn btn-primary btn-lg">
                        <i class="bi bi-shop"></i> Start Selling Today
                    </a>
                    <a id="heroCategoriesBtn" href="<?php echo BASE_PATH; ?>index.php?page=categories" class="btn btn-outline-light btn-lg">
                        <i class="bi bi-grid"></i> Browse Categories
                    </a>
                </div>
            </div>
            <div class="col-md-6 mt-4 mt-md-0">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="card bg-light bg-opacity-10 border-0 text-white h-100 hero-feature-card">
                            <div class="card-body">
                                <i class="bi bi-shield-check" style="font-size: 2rem;"></i>
                                <h6 class="mt-3 mb-1">Secure Shopping</h6>
                                <p class="small mb-0">Protected payments and verified sellers.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card bg-light bg-opacity-10 border-0 text-white h-100 hero-feature-card">
                            <div class="card-body">
                                <i class="bi bi-truck" style="font-size: 2rem;"></i>
                                <h6 class="mt-3 mb-1">Fast Delivery</h6>
                                <p class="small mb-0">Get your orders quickly and reliably.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card bg-light bg-opacity-10 border-0 text-white h-100 hero-feature-card">
                            <div class="card-body">
                                <i class="bi bi-graph-up" style="font-size: 2rem;"></i>
                                <h6 class="mt-3 mb-1">Grow Your Business</h6>
                                <p class="small mb-0">Reach more buyers across Ghana.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card bg-light bg-opacity-10 border-0 text-white h-100 hero-feature-card">
                            <div class="card-body">
                                <i class="bi bi-people" style="font-size: 2rem;"></i>
                                <h6 class="mt-3 mb-1">Community Market</h6>
                                <p class="small mb-0">Real people, real products, real value.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Small inline styles for hero refinements -->
<style>
    .hero-search .input-group .form-control,
    .hero-search .input-group .btn {
        border-radius: 999px !important;
    }
    .hero-search .input-group {
        box-shadow: 0 1rem 2rem rgba(0, 0, 0, 0.18);
        border-radius: 999px;
        overflow: hidden;
    }
    .hero-feature-card {
        transition: transform 0.18s ease, box-shadow 0.18s ease, background-color 0.18s ease;
    }
    .hero-feature-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 0.75rem 1.5rem rgba(0, 0, 0, 0.25);
        background-color: rgba(255, 255, 255, 0.18) !important;
    }
    .trust-badge-item .trust-badge-icon {
        font-size: 1.6rem;
        margin-bottom: 0.35rem;
    }
    .platform-stat-icon {
        font-size: 1.8rem;
    }
    .hero-banner::after {
        content: "";
        position: absolute;
        left: 0;
        right: 0;
        bottom: -24px;
        height: 48px;
        background: radial-gradient(circle at top, rgba(255, 255, 255, 0.9) 0, rgba(255, 255, 255, 0) 65%);
        pointer-events: none;
    }
    @media (max-width: 767.98px) {
        .hero-banner {
            text-align: center;
        }
        .hero-banner .d-sm-flex {
            justify-content: center;
        }
    }
</style>
<?php endif; ?>

</div>

<!-- Trust Badges -->
<div class="trust-badges py-2 mb-3 bg-light border-top">
    <div class="container">
        <div class="row text-center small">
            <div class="col-6 col-md-3 mb-3 mb-md-0">
                <div class="trust-badge-item">
                    <i class="bi bi-shield-check text-success trust-badge-icon"></i>
                    <p class="mb-0 fw-semibold">Secure Payment</p>
                </div>
            </div>
            <div class="col-6 col-md-3 mb-3 mb-md-0">
                <div class="trust-badge-item">
                    <i class="bi bi-truck text-primary trust-badge-icon"></i>
                    <p class="mb-0 fw-semibold">Free Shipping</p>
                </div>
            </div>
            <div class="col-6 col-md-3 mb-3 mb-md-0">
                <div class="trust-badge-item">
                    <i class="bi bi-arrow-counterclockwise text-warning trust-badge-icon"></i>
                    <p class="mb-0 fw-semibold">Easy Returns</p>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="trust-badge-item">
                    <i class="bi bi-headset text-info trust-badge-icon"></i>
                    <p class="mb-0 fw-semibold">24/7 Support</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Deals Section -->
<?php if (!empty($deal_products)): ?>
<div class="deals-section mb-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">
                <i class="bi bi-lightning-charge text-warning"></i> 
                <span class="text-danger">Deals of the Day</span>
            </h2>
            <a href="<?php echo BASE_PATH; ?>flash-sales.php" class="btn btn-outline-danger">View All Deals</a>
        </div>
        <div class="row g-4">
            <?php foreach (array_slice($deal_products, 0, 4) as $deal): ?>
            <div class="col-6 col-md-3">
                <div class="card h-100 product-card deal-card">
                    <?php if ($deal['image']): ?>
                        <img src="<?php echo BASE_PATH; ?>assets/img/products/<?php echo htmlspecialchars($deal['image']); ?>" 
                             class="card-img-top" alt="<?php echo htmlspecialchars($deal['title']); ?>"
                             style="height: 200px; object-fit: cover;"
                             loading="lazy"
                             decoding="async"
                             width="300"
                             height="200">
                    <?php endif; ?>
                    <div class="card-body d-flex flex-column">
                        <span class="badge bg-danger mb-2">DEAL</span>
                        <h6 class="card-title"><?php echo htmlspecialchars($deal['title']); ?></h6>
                        <div class="mb-2">
                            <span class="text-decoration-line-through text-muted small">₵<?php echo number_format($deal['price'], 2); ?></span>
                            <span class="text-danger fw-bold ms-2">₵<?php echo number_format($deal['deal_price'] ?? $deal['price'], 2); ?></span>
                        </div>
                        <a href="<?php echo BASE_PATH; ?>product.php?id=<?php echo $deal['id']; ?>" class="btn btn-sm btn-danger w-100 mt-auto">
                            Shop Now
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Featured Products Section -->
<?php if (!empty($featured_products)): ?>
<div class="featured-section mb-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">
                <i class="bi bi-star-fill text-warning"></i> 
                Featured Products
            </h2>
            <a href="<?php echo BASE_PATH; ?>index.php?featured=1" class="btn btn-outline-primary">View All</a>
        </div>
        <div class="row g-4">
            <?php foreach (array_slice($featured_products, 0, 8) as $featured): ?>
            <div class="col-6 col-md-3">
                <div class="card h-100 product-card">
                    <?php if ($featured['image']): ?>
                        <img src="<?php echo BASE_PATH; ?>assets/img/products/<?php echo htmlspecialchars($featured['image']); ?>" 
                             class="card-img-top" alt="<?php echo htmlspecialchars($featured['title']); ?>"
                             style="height: 200px; object-fit: cover;"
                             loading="lazy"
                             decoding="async"
                             width="300"
                             height="200">
                    <?php endif; ?>
                    <div class="card-body d-flex flex-column">
                        <h6 class="card-title"><?php echo htmlspecialchars($featured['title']); ?></h6>
                        <p class="text-primary fw-bold mb-2">₵<?php echo number_format($featured['price'], 2); ?></p>
                        <a href="<?php echo BASE_PATH; ?>product.php?id=<?php echo $featured['id']; ?>" class="btn btn-sm btn-primary w-100 mt-auto">
                            View Details
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Platform Stats Bar -->
<?php if (!empty($platform_stats)): ?>
<div class="platform-stats bg-light py-3 mb-4 border-top">
    <div class="container">
        <div class="row text-center g-3 small">
            <div class="col-6 col-md-3">
                <div class="stat-item">
                    <i class="bi bi-box-seam text-primary platform-stat-icon"></i>
                    <h4 class="mb-0 mt-2 text-primary"><?php echo number_format($platform_stats['total_products'] ?? 0); ?></h4>
                    <p class="mb-0 text-muted">Products</p>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-item">
                    <i class="bi bi-people text-success platform-stat-icon"></i>
                    <h4 class="mb-0 mt-2 text-success"><?php echo number_format($platform_stats['total_sellers'] ?? 0); ?></h4>
                    <p class="mb-0 text-muted">Sellers</p>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-item">
                    <i class="bi bi-cart-check text-warning platform-stat-icon"></i>
                    <h4 class="mb-0 mt-2 text-warning"><?php echo number_format($platform_stats['total_orders'] ?? 0); ?></h4>
                    <p class="mb-0 text-muted">Orders</p>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-item">
                    <i class="bi bi-shield-check text-info platform-stat-icon"></i>
                    <h4 class="mb-0 mt-2 text-info">100%</h4>
                    <p class="mb-0 text-muted">Secure</p>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Best Sellers Section -->
<?php if (!empty($best_sellers)): ?>
<div class="bestsellers-section mb-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">
                <i class="bi bi-trophy-fill text-warning"></i> 
                Best Sellers
            </h2>
            <a href="<?php echo BASE_PATH; ?>index.php?sort=sales" class="btn btn-outline-primary">View All</a>
        </div>
        <div class="row g-4">
            <?php foreach (array_slice($best_sellers, 0, 4) as $bestseller): ?>
            <div class="col-6 col-md-3">
                <div class="card h-100 product-card position-relative">
                    <span class="badge bg-warning position-absolute top-0 start-0 m-2">BEST SELLER</span>
                    <?php if ($bestseller['image']): ?>
                        <img src="<?php echo BASE_PATH; ?>assets/img/products/<?php echo htmlspecialchars($bestseller['image']); ?>" 
                             class="card-img-top" alt="<?php echo htmlspecialchars($bestseller['title']); ?>"
                             style="height: 200px; object-fit: cover;"
                             loading="lazy"
                             decoding="async"
                             width="300"
                             height="200">
                    <?php endif; ?>
                    <div class="card-body d-flex flex-column">
                        <h6 class="card-title"><?php echo htmlspecialchars($bestseller['title']); ?></h6>
                        <?php if ($has_rating && !empty($bestseller['average_rating']) && $bestseller['average_rating'] > 0): ?>
                        <div class="product-rating mb-2">
                            <?php 
                            $rating = floatval($bestseller['average_rating']);
                            $full_stars = floor($rating);
                            $has_half = ($rating - $full_stars) >= 0.5;
                            for ($i = 1; $i <= 5; $i++): 
                                if ($i <= $full_stars): ?>
                                    <i class="bi bi-star-fill star-rating"></i>
                                <?php elseif ($i == $full_stars + 1 && $has_half): ?>
                                    <i class="bi bi-star-half star-rating"></i>
                                <?php else: ?>
                                    <i class="bi bi-star star-rating"></i>
                                <?php endif;
                            endfor; ?>
                            <span class="text-muted small ms-1">(<?php echo intval($bestseller['review_count'] ?? 0); ?>)</span>
                        </div>
                        <?php endif; ?>
                        <p class="text-primary fw-bold mb-2">₵<?php echo number_format($bestseller['price'], 2); ?></p>
                        <?php if ($has_sales_count && !empty($bestseller['sales_count'])): ?>
                        <p class="text-muted small mb-2">
                            <i class="bi bi-cart-check"></i> <?php echo number_format($bestseller['sales_count']); ?> sold
                        </p>
                        <?php endif; ?>
                        <a href="<?php echo BASE_PATH; ?>product.php?id=<?php echo $bestseller['id']; ?>" class="btn btn-sm btn-primary w-100 mt-auto">
                            View Details
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Top Rated Products Section -->
<?php if (!empty($top_rated)): ?>
<div class="toprated-section mb-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">
                <i class="bi bi-star-fill text-warning"></i> 
                Top Rated Products
            </h2>
            <a href="<?php echo BASE_PATH; ?>index.php?sort=rating" class="btn btn-outline-primary">View All</a>
        </div>
        <div class="row g-4">
            <?php foreach (array_slice($top_rated, 0, 4) as $toprated): ?>
            <div class="col-6 col-md-3">
                <div class="card h-100 product-card">
                    <?php if ($toprated['image']): ?>
                        <img src="<?php echo BASE_PATH; ?>assets/img/products/<?php echo htmlspecialchars($toprated['image']); ?>" 
                             class="card-img-top" alt="<?php echo htmlspecialchars($toprated['title']); ?>"
                             style="height: 200px; object-fit: cover;"
                             loading="lazy"
                             decoding="async"
                             width="300"
                             height="200">
                    <?php endif; ?>
                    <div class="card-body d-flex flex-column">
                        <h6 class="card-title"><?php echo htmlspecialchars($toprated['title']); ?></h6>
                        <div class="product-rating mb-2">
                            <?php 
                            $rating = floatval($toprated['average_rating']);
                            $full_stars = floor($rating);
                            $has_half = ($rating - $full_stars) >= 0.5;
                            for ($i = 1; $i <= 5; $i++): 
                                if ($i <= $full_stars): ?>
                                    <i class="bi bi-star-fill star-rating"></i>
                                <?php elseif ($i == $full_stars + 1 && $has_half): ?>
                                    <i class="bi bi-star-half star-rating"></i>
                                <?php else: ?>
                                    <i class="bi bi-star star-rating"></i>
                                <?php endif;
                            endfor; ?>
                            <span class="text-primary fw-bold ms-1"><?php echo number_format($rating, 1); ?></span>
                            <span class="text-muted small">(<?php echo intval($toprated['review_count'] ?? 0); ?>)</span>
                        </div>
                        <p class="text-primary fw-bold mb-2">₵<?php echo number_format($toprated['price'], 2); ?></p>
                        <a href="<?php echo BASE_PATH; ?>product.php?id=<?php echo $toprated['id']; ?>" class="btn btn-sm btn-primary w-100 mt-auto">
                            View Details
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- New Arrivals Section -->
<?php if (!empty($new_arrivals)): ?>
<div class="newarrivals-section mb-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">
                <i class="bi bi-sparkles text-info"></i> 
                New Arrivals
            </h2>
            <a href="<?php echo BASE_PATH; ?>index.php?sort=newest" class="btn btn-outline-primary">View All</a>
        </div>
        <div class="row g-4">
            <?php foreach (array_slice($new_arrivals, 0, 4) as $newarrival): ?>
            <div class="col-6 col-md-3">
                <div class="card h-100 product-card position-relative">
                    <span class="badge bg-info position-absolute top-0 start-0 m-2">NEW</span>
                    <?php if ($newarrival['image']): ?>
                        <img src="<?php echo BASE_PATH; ?>assets/img/products/<?php echo htmlspecialchars($newarrival['image']); ?>" 
                             class="card-img-top" alt="<?php echo htmlspecialchars($newarrival['title']); ?>"
                             style="height: 200px; object-fit: cover;"
                             loading="lazy"
                             decoding="async"
                             width="300"
                             height="200">
                    <?php endif; ?>
                    <div class="card-body d-flex flex-column">
                        <h6 class="card-title"><?php echo htmlspecialchars($newarrival['title']); ?></h6>
                        <p class="text-primary fw-bold mb-2">₵<?php echo number_format($newarrival['price'], 2); ?></p>
                        <p class="text-muted small mb-2">
                            <i class="bi bi-clock"></i> Just added
                        </p>
                        <a href="<?php echo BASE_PATH; ?>product.php?id=<?php echo $newarrival['id']; ?>" class="btn btn-sm btn-primary w-100 mt-auto">
                            View Details
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Recently Viewed Section (Only for logged in users) -->
<?php if (isLoggedIn() && !empty($recently_viewed)): ?>
<div class="recently-viewed-section mb-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">
                <i class="bi bi-clock-history text-secondary"></i> 
                Recently Viewed
            </h2>
            <a href="<?php echo BASE_PATH; ?>index.php?recent=1" class="btn btn-outline-secondary">View All</a>
        </div>
        <div class="row g-4">
            <?php foreach (array_slice($recently_viewed, 0, 4) as $recent): ?>
            <div class="col-6 col-md-3">
                <div class="card h-100 product-card">
                    <?php if ($recent['image']): ?>
                        <img src="<?php echo BASE_PATH; ?>assets/img/products/<?php echo htmlspecialchars($recent['image']); ?>" 
                             class="card-img-top" alt="<?php echo htmlspecialchars($recent['title']); ?>"
                             style="height: 200px; object-fit: cover;"
                             loading="lazy"
                             decoding="async"
                             width="300"
                             height="200">
                    <?php endif; ?>
                    <div class="card-body d-flex flex-column">
                        <h6 class="card-title"><?php echo htmlspecialchars($recent['title']); ?></h6>
                        <p class="text-primary fw-bold mb-2">₵<?php echo number_format($recent['price'], 2); ?></p>
                        <a href="<?php echo BASE_PATH; ?>product.php?id=<?php echo $recent['id']; ?>" class="btn btn-sm btn-outline-secondary w-100 mt-auto">
                            View Again
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php endif; ?>
<?php endif; ?>

<div class="container my-4">
    <!-- Categories Section - Modern Design -->
    <?php if ($page === 'home' && empty($search) && empty($category_id)): ?>
    <div class="categories-section mb-5 fade-in">
        <div class="container">
            <div class="section-header">
                <h2>
                    <i class="bi bi-grid-3x3-gap-fill"></i>
                    Shop by Category
                </h2>
                <p>Discover amazing products from our carefully curated categories. Find exactly what you're looking for with our intuitive shopping experience.</p>
            </div>

            <!-- Enhanced Category Grid -->
            <div class="row g-3 g-lg-4">
                <?php
                $categories_result->data_seek(0); // Reset pointer
                $category_data = [
                    'Electronics' => [
                        'icon' => 'bi-laptop',
                        'color' => 'primary',
                        'description' => 'Latest gadgets and tech',
                        'image' => 'electronics.jpg'
                    ],
                    'Fashion' => [
                        'icon' => 'bi-bag',
                        'color' => 'danger',
                        'description' => 'Trendy clothing and accessories',
                        'image' => 'fashion.jpg'
                    ],
                    'Home & Garden' => [
                        'icon' => 'bi-house',
                        'color' => 'success',
                        'description' => 'Everything for your home',
                        'image' => 'home.jpg'
                    ],
                    'Sports & Outdoors' => [
                        'icon' => 'bi-trophy',
                        'color' => 'warning',
                        'description' => 'Gear up for adventure',
                        'image' => 'sports.jpg'
                    ],
                    'Books' => [
                        'icon' => 'bi-book',
                        'color' => 'info',
                        'description' => 'Knowledge and entertainment',
                        'image' => 'books.jpg'
                    ],
                    'Toys & Games' => [
                        'icon' => 'bi-controller',
                        'color' => 'secondary',
                        'description' => 'Fun for all ages',
                        'image' => 'toys.jpg'
                    ]
                ];

                while ($category = $categories_result->fetch_assoc()):
                    $cat_info = $category_data[$category['name']] ?? [
                        'icon' => 'bi-grid',
                        'color' => 'primary',
                        'description' => 'Explore our collection',
                        'image' => null
                    ];

                    // Get product count for this category
                    $count_query = "SELECT COUNT(*) as count FROM products WHERE category_id = ? AND status = 'approved'";
                    $count_stmt = $conn->prepare($count_query);
                    $count_stmt->bind_param("i", $category['id']);
                    $count_stmt->execute();
                    $product_count = $count_stmt->get_result()->fetch_assoc()['count'];
                    $count_stmt->close();

                    // Get subcategories for menu
                    $subcategories = [];
                    if ($has_subcat_table) {
                        $subcat_stmt = $conn->prepare("SELECT * FROM subcategories WHERE category_id = ? ORDER BY name");
                        $subcat_stmt->bind_param("i", $category['id']);
                        $subcat_stmt->execute();
                        $subcat_result = $subcat_stmt->get_result();
                        while ($subcat = $subcat_result->fetch_assoc()) {
                            $subcategories[] = $subcat;
                        }
                        $subcat_stmt->close();
                    }
                ?>
                <div class="col-6 col-sm-6 col-md-4 col-lg-3 col-xl-2">
                    <div class="modern-category-card position-relative h-100" data-category-id="<?php echo $category['id']; ?>">
                        <div class="card h-100 border category-card-hover">
                            <a href="<?php echo BASE_PATH; ?>index.php?category=<?php echo $category['id']; ?>" class="text-decoration-none">
                                <div class="card-body text-center p-4 d-flex flex-column">
                                    <!-- Category Icon -->
                                    <div class="category-icon-modern mb-3 mx-auto">
                                        <div class="icon-circle-modern bg-<?php echo $cat_info['color']; ?> bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi <?php echo $cat_info['icon']; ?> text-<?php echo $cat_info['color']; ?>"></i>
                                        </div>
                                    </div>

                                    <!-- Category Name -->
                                    <h6 class="card-title fw-bold mb-2 text-dark"><?php echo htmlspecialchars($category['name']); ?></h6>

                                    <!-- Product Count -->
                                    <p class="text-muted small mb-2">
                                        <i class="bi bi-box-seam"></i>
                                        <?php echo number_format($product_count); ?> products
                                    </p>

                                    <!-- Category Description -->
                                    <p class="text-muted small mb-3 flex-grow-1"><?php echo $cat_info['description']; ?></p>

                                    <!-- Action Button -->
                                    <div class="btn btn-<?php echo $cat_info['color']; ?> btn-sm w-100 category-btn-modern">
                                        Shop Now
                                        <i class="bi bi-arrow-right ms-1"></i>
                                    </div>
                                </div>
                            </a>

                            <!-- Subcategories: Collapse on mobile (reliable), Dropdown on desktop -->
                            <?php if (!empty($subcategories)): ?>
                            <div class="card-footer bg-light border-top p-2 subcat-footer">
                                <!-- Mobile: expandable list (no positioning issues) -->
                                <div class="d-md-none subcat-mobile">
                                    <button class="btn btn-sm btn-link text-muted text-decoration-none w-100 text-start subcat-toggle subcat-collapse-btn"
                                            type="button" data-bs-toggle="collapse" data-bs-target="#subcatCollapse-<?php echo $category['id']; ?>"
                                            aria-expanded="false" aria-controls="subcatCollapse-<?php echo $category['id']; ?>">
                                        <i class="bi bi-chevron-down subcat-chevron"></i> Subcategories
                                    </button>
                                    <div class="collapse subcat-collapse" id="subcatCollapse-<?php echo $category['id']; ?>">
                                        <div class="subcat-list-mobile">
                                            <?php foreach ($subcategories as $subcat): ?>
                                            <a class="subcat-link-mobile" href="<?php echo BASE_PATH; ?>index.php?category=<?php echo $category['id']; ?>&subcategory=<?php echo $subcat['id']; ?>">
                                                <i class="bi bi-chevron-right small me-1"></i><?php echo htmlspecialchars($subcat['name']); ?>
                                            </a>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                                <!-- Desktop: dropdown -->
                                <div class="d-none d-md-block">
                                    <div class="dropdown d-grid">
                                        <button class="btn btn-sm btn-link text-muted text-decoration-none dropdown-toggle subcat-toggle"
                                                type="button" data-bs-toggle="dropdown" data-bs-auto-close="true"
                                                data-bs-display="dynamic"
                                                aria-expanded="false" id="subcatDropdown-<?php echo $category['id']; ?>" aria-haspopup="true">
                                            <i class="bi bi-chevron-down small"></i> Subcategories
                                        </button>
                                        <ul class="dropdown-menu w-100 shadow-sm subcat-menu" aria-labelledby="subcatDropdown-<?php echo $category['id']; ?>">
                                            <?php foreach ($subcategories as $subcat): ?>
                                            <li>
                                                <a class="dropdown-item small py-2 subcat-item" href="<?php echo BASE_PATH; ?>index.php?category=<?php echo $category['id']; ?>&subcategory=<?php echo $subcat['id']; ?>">
                                                    <i class="bi bi-chevron-right small me-1"></i><?php echo htmlspecialchars($subcat['name']); ?>
                                                </a>
                                            </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        

        </div>
    </div>
    <?php endif; ?>
    
    <!-- Products Section -->
    <div class="mb-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>
                <?php if (!empty($search)): ?>
                    Search Results for "<?php echo htmlspecialchars($search); ?>"
                <?php elseif (!empty($category_id)): ?>
                    <?php
                    $cat_stmt = $conn->prepare("SELECT name FROM categories WHERE id = ?");
                    $cat_stmt->bind_param("i", $category_id);
                    $cat_stmt->execute();
                    $cat_name = $cat_stmt->get_result()->fetch_assoc()['name'] ?? 'Category';
                    echo htmlspecialchars($cat_name);
                    
                    if (!empty($subcategory_id) && $has_subcat_table) {
                        $subcat_stmt = $conn->prepare("SELECT name FROM subcategories WHERE id = ?");
                        $subcat_stmt->bind_param("i", $subcategory_id);
                        $subcat_stmt->execute();
                        $subcat_name = $subcat_stmt->get_result()->fetch_assoc()['name'] ?? '';
                        if ($subcat_name) {
                            echo ' - ' . htmlspecialchars($subcat_name);
                        }
                        $subcat_stmt->close();
                    }
                    ?>
                <?php else: ?>
                    Featured Products
                <?php endif; ?>
            </h2>
            <span class="text-muted"><?php echo $total_products; ?> products found</span>
        </div>
        
        <?php if ($products_result->num_rows > 0): ?>
        <div class="row g-4">
            <?php while ($product = $products_result->fetch_assoc()): ?>
            <div class="col-6 col-md-4 col-lg-3">
                <div class="card h-100 product-card position-relative">
                    <!-- Wishlist Button -->
                    <?php if (isLoggedIn()): ?>
                    <button class="wishlist-btn" data-wishlist-id="<?php echo $product['id']; ?>" onclick="toggleWishlist(<?php echo $product['id']; ?>)">
                        <i class="bi bi-heart"></i>
                    </button>
                    <?php endif; ?>
                    
                    <!-- Product Image -->
                    <?php if ($product['image']): ?>
                        <img src="<?php echo BASE_PATH; ?>assets/img/products/<?php echo htmlspecialchars($product['image']); ?>" 
                             class="card-img-top" alt="<?php echo htmlspecialchars($product['title']); ?>"
                             style="height: 200px; object-fit: cover; cursor: pointer;"
                             loading="lazy"
                             decoding="async"
                             width="300"
                             height="200"
                             onclick="openQuickView(<?php echo $product['id']; ?>)">
                    <?php else: ?>
                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                             style="height: 200px; cursor: pointer;"
                             onclick="openQuickView(<?php echo $product['id']; ?>)">
                            <i class="bi bi-image text-muted" style="font-size: 3rem;"></i>
                        </div>
                    <?php endif; ?>
                    
                    <div class="card-body d-flex flex-column">
                        <h6 class="card-title" style="cursor: pointer;" onclick="window.location.href='<?php echo BASE_PATH; ?>product.php?id=<?php echo $product['id']; ?>'">
                            <?php echo htmlspecialchars($product['title']); ?>
                        </h6>
                        
                        <!-- Rating (if available) -->
                        <?php 
                        $check_rating = $conn->query("SHOW COLUMNS FROM products LIKE 'average_rating'");
                        $has_rating = $check_rating->num_rows > 0;
                        if ($has_rating && !empty($product['average_rating']) && $product['average_rating'] > 0): 
                        ?>
                        <div class="product-rating mb-2">
                            <?php 
                            $rating = floatval($product['average_rating']);
                            $full_stars = floor($rating);
                            $has_half = ($rating - $full_stars) >= 0.5;
                            for ($i = 1; $i <= 5; $i++): 
                                if ($i <= $full_stars): ?>
                                    <i class="bi bi-star-fill star-rating"></i>
                                <?php elseif ($i == $full_stars + 1 && $has_half): ?>
                                    <i class="bi bi-star-half star-rating"></i>
                                <?php else: ?>
                                    <i class="bi bi-star star-rating"></i>
                                <?php endif;
                            endfor; ?>
                            <span class="text-muted small ms-1">(<?php echo intval($product['review_count'] ?? 0); ?>)</span>
                        </div>
                        <?php endif; ?>
                        
                        <p class="text-muted small mb-2">
                            <i class="bi bi-tag"></i> <?php echo htmlspecialchars($product['category_name']); ?>
                            <?php if (!empty($product['subcategory_name'])): ?>
                                <span class="text-muted"> / <?php echo htmlspecialchars($product['subcategory_name']); ?></span>
                            <?php endif; ?>
                        </p>
                        <p class="text-primary fw-bold mb-1">₵<?php echo number_format($product['price'], 2); ?></p>
                        <p class="text-muted small mb-1">
                            <i class="bi bi-person"></i> <?php echo htmlspecialchars($product['seller_name']); ?>
                        </p>
                        <?php if (!empty($product['description'])): ?>
                        <p class="text-muted small mb-auto">
                            <?php
                            $shortDesc = strip_tags($product['description']);
                            if (strlen($shortDesc) > 80) {
                                $shortDesc = substr($shortDesc, 0, 80) . '...';
                            }
                            echo htmlspecialchars($shortDesc);
                            ?>
                        </p>
                        <?php else: ?>
                        <p class="text-muted small mb-auto fst-italic">No description provided.</p>
                        <?php endif; ?>
                        <div class="mt-2 d-grid gap-2">
                            <a href="<?php echo BASE_PATH; ?>product.php?id=<?php echo $product['id']; ?>" class="btn btn-sm btn-primary">
                                View Details
                            </a>
                            <button class="btn btn-sm btn-outline-primary" onclick="openQuickView(<?php echo $product['id']; ?>)">
                                <i class="bi bi-eye"></i> Quick View
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
        
        <!-- Pagination -->
        <?php if ($total_pages > 1): ?>
        <nav aria-label="Page navigation" class="mt-4">
            <ul class="pagination justify-content-center">
                <?php if ($page_num > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="<?php echo BASE_PATH; ?>index.php?<?php echo http_build_query(array_merge($_GET, ['p' => $page_num - 1])); ?>">Previous</a>
                    </li>
                <?php endif; ?>
                
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page-item <?php echo $i === $page_num ? 'active' : ''; ?>">
                        <a class="page-link" href="<?php echo BASE_PATH; ?>index.php?<?php echo http_build_query(array_merge($_GET, ['p' => $i])); ?>"><?php echo $i; ?></a>
                    </li>
                <?php endfor; ?>
                
                <?php if ($page_num < $total_pages): ?>
                    <li class="page-item">
                        <a class="page-link" href="<?php echo BASE_PATH; ?>index.php?<?php echo http_build_query(array_merge($_GET, ['p' => $page_num + 1])); ?>">Next</a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
        <?php endif; ?>
        
        <?php else: ?>
        <div class="alert alert-info text-center">
            <i class="bi bi-info-circle"></i> No products found. Try adjusting your search or browse categories.
        </div>
        <?php endif; ?>
    </div>
</div>

<?php
closeDBConnection($conn);
include 'includes/footer.php';
?>


