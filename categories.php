<?php
/**
 * Categories Page - AliExpress-style "All Categories"
 * Left: main categories with icons | Right: Recommended products + Subcategories
 */
require_once 'config/db.php';
require_once 'config/paths.php';
require_once 'includes/auth.php';

$conn = getDBConnection();

$categories_query = "SELECT * FROM categories ORDER BY name";
$categories_result = $conn->query($categories_query);
$all_categories = [];
while ($r = $categories_result->fetch_assoc()) { $all_categories[] = $r; }

$check_subcat_table = $conn->query("SHOW TABLES LIKE 'subcategories'");
$has_subcat_table = $check_subcat_table->num_rows > 0;

$category_icons = [
    'Electronics' => 'bi-laptop', 'Fashion' => 'bi-bag', 'Home & Garden' => 'bi-house',
    'Sports & Outdoors' => 'bi-trophy', 'Books' => 'bi-book', 'Toys & Games' => 'bi-controller'
];

// Selected category: from ?cat= or first
$selected_id = isset($_GET['cat']) ? (int)$_GET['cat'] : 0;
if ($selected_id === 0 && !empty($all_categories)) {
    $selected_id = (int)$all_categories[0]['id'];
}

// Subcategories for selected
$subcategories = [];
if ($has_subcat_table && $selected_id > 0) {
    $st = $conn->prepare("SELECT * FROM subcategories WHERE category_id = ? ORDER BY name");
    $st->bind_param("i", $selected_id);
    $st->execute();
    $sr = $st->get_result();
    while ($s = $sr->fetch_assoc()) { $subcategories[] = $s; }
    $st->close();
}

// Recommended products for selected category (limit 8)
$recommended = [];
if ($selected_id > 0) {
    $pr = $conn->prepare("SELECT p.id, p.title, p.image, p.price FROM products p WHERE p.category_id = ? AND p.status = 'approved' ORDER BY p.views DESC, p.created_at DESC LIMIT 8");
    $pr->bind_param("i", $selected_id);
    $pr->execute();
    $prr = $pr->get_result();
    while ($p = $prr->fetch_assoc()) { $recommended[] = $p; }
    $pr->close();
}

$selected_name = '';
foreach ($all_categories as $c) {
    if ((int)$c['id'] === $selected_id) { $selected_name = $c['name']; break; }
}

$pageTitle = 'All Categories';
include 'includes/header.php';
?>

<div class="container my-4">
<div class="ac-page">
    <div class="ac-header mb-4">
        <h1 class="ac-title"><i class="bi bi-grid-3x3-gap-fill text-primary me-2"></i>All Categories</h1>
        <p class="ac-subtitle text-muted mb-0">Browse by category and discover products.</p>
    </div>

    <!-- Mobile: category pills -->
    <div class="ac-pills d-lg-none mb-3">
        <div class="ac-pills-inner">
            <?php foreach ($all_categories as $c):
                $icon = $category_icons[$c['name']] ?? 'bi-grid';
                $active = ((int)$c['id'] === $selected_id) ? ' active' : '';
            ?>
            <a href="<?php echo BASE_PATH; ?>categories.php?cat=<?php echo (int)$c['id']; ?>" class="ac-pill<?php echo $active; ?>">
                <i class="bi <?php echo $icon; ?>"></i>
                <span><?php echo htmlspecialchars($c['name']); ?></span>
            </a>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="ac-panels">
        <!-- Left: Main categories sidebar -->
        <aside class="ac-sidebar">
            <nav class="ac-sidebar-inner">
                <?php foreach ($all_categories as $c):
                    $icon = $category_icons[$c['name']] ?? 'bi-grid';
                    $active = ((int)$c['id'] === $selected_id) ? ' active' : '';
                ?>
                <a href="<?php echo BASE_PATH; ?>categories.php?cat=<?php echo (int)$c['id']; ?>" class="ac-side-item<?php echo $active; ?>">
                    <i class="bi <?php echo $icon; ?> ac-side-icon"></i>
                    <span><?php echo htmlspecialchars($c['name']); ?></span>
                </a>
                <?php endforeach; ?>
            </nav>
        </aside>

        <!-- Right: Recommended + Subcategories -->
        <main class="ac-main">
            <?php if ($selected_id > 0): ?>

            <!-- Recommended -->
            <section class="ac-section">
                <h2 class="ac-section-title">Recommended</h2>
                <?php if (!empty($recommended)): ?>
                <div class="ac-recommended">
                    <?php foreach ($recommended as $p): ?>
                    <a href="<?php echo BASE_PATH; ?>product.php?id=<?php echo (int)$p['id']; ?>" class="ac-rec-card">
                        <div class="ac-rec-img-wrap">
                            <?php if (!empty($p['image'])): ?>
                            <img src="<?php echo BASE_PATH; ?>assets/img/products/<?php echo htmlspecialchars($p['image']); ?>" alt="" class="ac-rec-img">
                            <?php else: ?>
                            <div class="ac-rec-img ac-rec-placeholder"><i class="bi bi-image"></i></div>
                            <?php endif; ?>
                        </div>
                        <p class="ac-rec-name"><?php echo htmlspecialchars($p['title']); ?></p>
                        <p class="ac-rec-price text-primary fw-bold mb-0">₵<?php echo number_format((float)$p['price'], 2); ?></p>
                    </a>
                    <?php endforeach; ?>
                </div>
                <?php else: ?>
                <p class="text-muted small mb-0">No products in this category yet. <a href="<?php echo BASE_PATH; ?>index.php?category=<?php echo $selected_id; ?>">Browse all</a></p>
                <?php endif; ?>
            </section>

            <!-- Subcategories -->
            <section class="ac-section">
                <h2 class="ac-section-title">Subcategories</h2>
                <?php if (!empty($subcategories)): ?>
                <div class="ac-subcats">
                    <?php foreach ($subcategories as $s): ?>
                    <a href="<?php echo BASE_PATH; ?>index.php?category=<?php echo $selected_id; ?>&subcategory=<?php echo (int)$s['id']; ?>" class="ac-subcat-link">
                        <?php echo htmlspecialchars($s['name']); ?>
                    </a>
                    <?php endforeach; ?>
                </div>
                <?php else: ?>
                <p class="text-muted small mb-0">No subcategories. <a href="<?php echo BASE_PATH; ?>index.php?category=<?php echo $selected_id; ?>">View all products in <?php echo htmlspecialchars($selected_name); ?></a></p>
                <?php endif; ?>
            </section>

            <div class="ac-cta mt-4 pt-4 border-top">
                <a href="<?php echo BASE_PATH; ?>index.php?category=<?php echo $selected_id; ?>" class="btn btn-primary">
                    <i class="bi bi-box-seam me-2"></i>View all products in <?php echo htmlspecialchars($selected_name); ?>
                </a>
            </div>

            <?php else: ?>
            <section class="ac-section">
                <p class="text-muted">Select a category from the list.</p>
            </section>
            <?php endif; ?>
        </main>
    </div>
</div>
</div>

<?php
closeDBConnection($conn);
include 'includes/footer.php';
?>
