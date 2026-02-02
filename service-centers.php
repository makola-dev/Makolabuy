<?php
/**
 * Service Centers Page
 * Makola
 */
require_once 'config/db.php';
require_once 'config/paths.php';
require_once 'includes/auth.php';

$pageTitle = 'Service Centers';
include 'includes/header.php';
?>

<div class="container my-5">
    <h2 class="mb-4"><i class="bi bi-geo-alt"></i> Service Centers</h2>
    
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-geo-alt-fill"></i> Main Service Center</h5>
                </div>
                <div class="card-body">
                    <p><strong>Address:</strong><br>
                    Accra<br>
                    Ghana</p>
                    <p><strong>Phone:</strong> 0538510162</p>
                    <p><strong>Email:</strong> makolaghana5522@gmail.com</p>
                    <p><strong>Hours:</strong><br>
                    Monday - Friday: 9:00 AM - 6:00 PM<br>
                    Saturday: 10:00 AM - 4:00 PM<br>
                    Sunday: Closed</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-geo-alt-fill"></i> Regional Service Center</h5>
                </div>
                <div class="card-body">
                    <p><strong>Address:</strong><br>
                    Accra<br>
                    Ghana</p>
                    <p><strong>Phone:</strong> 0538510162</p>
                    <p><strong>Email:</strong> makolaghana5522@gmail.com</p>
                    <p><strong>Hours:</strong><br>
                    Monday - Friday: 9:00 AM - 6:00 PM<br>
                    Saturday: 10:00 AM - 4:00 PM<br>
                    Sunday: Closed</p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="alert alert-info mt-4">
        <i class="bi bi-info-circle"></i> <strong>Note:</strong> For product returns and exchanges, please visit any of our service centers during business hours. Bring your order confirmation and the product in its original packaging.
    </div>
</div>

<?php
include 'includes/footer.php';
?>

