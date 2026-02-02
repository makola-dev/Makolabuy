<?php
/**
 * Contact Us Page
 * Makola
 */
require_once 'config/db.php';
require_once 'config/paths.php';
require_once 'includes/auth.php';

$pageTitle = 'Contact Us';
include 'includes/header.php';
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="bi bi-envelope"></i> Contact Us</h4>
                </div>
                <div class="card-body">
                    <?php if (isset($_GET['success'])): ?>
                        <div class="alert alert-success">
                            <i class="bi bi-check-circle"></i> Thank you for contacting us! We'll get back to you soon.
                        </div>
                    <?php endif; ?>
                    <?php if (isset($_GET['error'])): ?>
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-circle"></i> <?php echo htmlspecialchars($_GET['error']); ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <h5><i class="bi bi-envelope text-primary"></i> Email</h5>
                            <p>makolaghana5522@gmail.com</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h5><i class="bi bi-telephone text-primary"></i> Phone</h5>
                            <p>0538510162</p>
                        </div>
                        <div class="col-md-12">
                            <h5><i class="bi bi-geo-alt text-primary"></i> Address</h5>
                            <p>Accra, Ghana</p>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <h5 class="mb-3">Send us a Message</h5>
                    <form method="POST" action="<?php echo BASE_PATH; ?>controllers/contact.php">
                        <div class="mb-3">
                            <label for="name" class="form-label">Your Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Your Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="subject" class="form-label">Subject</label>
                            <input type="text" class="form-control" id="subject" name="subject" required>
                        </div>
                        <div class="mb-3">
                            <label for="message" class="form-label">Message</label>
                            <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-send"></i> Send Message
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

