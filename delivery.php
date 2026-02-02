<?php
/**
 * Delivery Timelines and Fees
 * Makola
 */
require_once 'config/db.php';
require_once 'config/paths.php';
require_once 'includes/auth.php';

$pageTitle = 'Delivery Timelines and Fees';
include 'includes/header.php';
?>

<div class="container my-5">
    <h2 class="mb-4"><i class="bi bi-truck"></i> Delivery Timelines and Fees</h2>
    
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Delivery Options</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Delivery Type</th>
                                    <th>Timeline</th>
                                    <th>Fee</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong>Standard Delivery</strong></td>
                                    <td>5-7 business days</td>
                                    <td>₵5.00</td>
                                </tr>
                                <tr>
                                    <td><strong>Express Delivery</strong></td>
                                    <td>2-3 business days</td>
                                    <td>₵15.00</td>
                                </tr>
                                <tr>
                                    <td><strong>Same Day Delivery</strong></td>
                                    <td>Same day (if ordered before 12 PM)</td>
                                    <td>₵25.00</td>
                                </tr>
                                <tr>
                                    <td><strong>Free Delivery</strong></td>
                                    <td>5-7 business days</td>
                                    <td>Free (Orders over ₵50)</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Delivery Information</h5>
                </div>
                <div class="card-body">
                    <h6 class="mb-3">Delivery Areas</h6>
                    <p>We deliver to all major cities and towns. Delivery to remote areas may take longer and may incur additional fees.</p>
                    
                    <h6 class="mb-3 mt-4">Tracking Your Order</h6>
                    <p>Once your order is shipped, you'll receive a tracking number via email. You can track your order status from your account.</p>
                    
                    <h6 class="mb-3 mt-4">Delivery Instructions</h6>
                    <ul>
                        <li>Please ensure someone is available to receive the delivery</li>
                        <li>Provide accurate shipping address details</li>
                        <li>Delivery times are estimates and may vary based on location</li>
                        <li>For large items, delivery may require scheduling</li>
                    </ul>
                    
                    <div class="alert alert-warning mt-4">
                        <i class="bi bi-exclamation-triangle"></i> <strong>Note:</strong> Delivery fees are calculated at checkout based on your location and selected delivery option.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include 'includes/footer.php';
?>

