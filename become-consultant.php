<?php
/**
 * Become a Sales Consultant
 * Makola
 */
require_once 'config/db.php';
require_once 'config/paths.php';
require_once 'includes/auth.php';

$pageTitle = 'Become a Sales Consultant';
include 'includes/header.php';
?>

<div class="container my-5">
    <h2 class="mb-4"><i class="bi bi-person-badge"></i> Become a Sales Consultant</h2>
    
    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="card shadow mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Join Our Sales Consultant Program</h5>
                </div>
                <div class="card-body">
                    <p>Earn money by helping others discover and purchase products on Makola. As a Sales Consultant, you'll earn commissions on sales you refer.</p>
                    
                    <h6 class="mt-4 mb-3">Benefits</h6>
                    <ul>
                        <li>Earn up to 5% commission on referred sales</li>
                        <li>Flexible working hours</li>
                        <li>Free training and marketing materials</li>
                        <li>Performance bonuses</li>
                        <li>Dedicated support team</li>
                    </ul>
                    
                    <h6 class="mt-4 mb-3">Requirements</h6>
                    <ul>
                        <li>Active Makola account</li>
                        <li>Good communication skills</li>
                        <li>Basic knowledge of our products</li>
                        <li>Commitment to ethical sales practices</li>
                    </ul>
                </div>
            </div>
            
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Apply Now</h5>
                </div>
                <div class="card-body">
                    <?php if (isset($_GET['success'])): ?>
                        <div class="alert alert-success">
                            <i class="bi bi-check-circle"></i> Thank you for your application! We'll review it and get back to you soon.
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="<?php echo BASE_PATH; ?>controllers/consultant-application.php">
                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="tel" class="form-control" id="phone" name="phone" required>
                        </div>
                        <div class="mb-3">
                            <label for="experience" class="form-label">Sales Experience</label>
                            <textarea class="form-control" id="experience" name="experience" rows="3" placeholder="Tell us about your sales experience"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="why" class="form-label">Why do you want to become a Sales Consultant?</label>
                            <textarea class="form-control" id="why" name="why" rows="3" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-send"></i> Submit Application
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include 'includes/footer.php';
?>

