<?php
/**
 * Makola Careers
 * Makola
 */
require_once 'config/db.php';
require_once 'config/paths.php';
require_once 'includes/auth.php';

$pageTitle = 'Makola Careers';
include 'includes/header.php';
?>

<div class="container my-5">
    <h2 class="mb-4"><i class="bi bi-briefcase"></i> Makola Careers</h2>
    
    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="card shadow mb-4">
                <div class="card-body">
                    <h4 class="mb-3">Join Our Team</h4>
                    <p>Makola is growing and we're always looking for talented individuals to join our team. We offer competitive salaries, great benefits, and opportunities for career growth.</p>
                    
                    <h5 class="mb-3 mt-4">Open Positions</h5>
                    <div class="list-group">
                        <div class="list-group-item">
                            <h6>Software Developer</h6>
                            <p class="mb-1">Full-time • Remote/On-site</p>
                            <small class="text-muted">We're looking for experienced PHP developers to join our development team.</small>
                        </div>
                        <div class="list-group-item">
                            <h6>Customer Support Representative</h6>
                            <p class="mb-1">Full-time • On-site</p>
                            <small class="text-muted">Help our customers with their inquiries and provide excellent service.</small>
                        </div>
                        <div class="list-group-item">
                            <h6>Marketing Specialist</h6>
                            <p class="mb-1">Full-time • Remote</p>
                            <small class="text-muted">Drive our marketing campaigns and help grow our brand presence.</small>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <h5>How to Apply</h5>
                        <p>Send your resume and cover letter to <a href="mailto:careers@makola.com">careers@makola.com</a> with the position title in the subject line.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include 'includes/footer.php';
?>

