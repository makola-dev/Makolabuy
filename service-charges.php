<?php
/**
 * Service Charges
 * Makola
 */
require_once 'config/db.php';
require_once 'config/paths.php';
require_once 'includes/auth.php';

$pageTitle = 'Service Charges';
include 'includes/header.php';
?>

<div class="container my-5">
    <h2 class="mb-4"><i class="bi bi-receipt"></i> Service Charges</h2>
    
    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Seller Fees and Charges</h5>
                </div>
                <div class="card-body">
                    <h6 class="mb-3">Commission Structure</h6>
                    <p>Makola charges a commission on each successful sale. The commission rate varies by category:</p>
                    
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Category</th>
                                    <th>Commission Rate</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Electronics</td>
                                    <td>10%</td>
                                </tr>
                                <tr>
                                    <td>Fashion</td>
                                    <td>12%</td>
                                </tr>
                                <tr>
                                    <td>Home & Garden</td>
                                    <td>10%</td>
                                </tr>
                                <tr>
                                    <td>Sports & Outdoors</td>
                                    <td>10%</td>
                                </tr>
                                <tr>
                                    <td>Books</td>
                                    <td>8%</td>
                                </tr>
                                <tr>
                                    <td>Toys & Games</td>
                                    <td>10%</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <h6 class="mb-3 mt-4">Other Fees</h6>
                    <ul>
                        <li><strong>Listing Fee:</strong> Free (unlimited listings)</li>
                        <li><strong>Payment Processing:</strong> Handled by payment gateway (separate fees apply)</li>
                        <li><strong>Withdrawal Fee:</strong> ₵2.00 per withdrawal</li>
                    </ul>
                    
                    <div class="alert alert-info mt-4">
                        <i class="bi bi-info-circle"></i> <strong>Note:</strong> Commission is calculated on the final sale price (excluding shipping). Payouts are processed weekly.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include 'includes/footer.php';
?>

