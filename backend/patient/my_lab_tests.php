
<?php
$page_title = "My Lab Tests";
require_once '../includes/patient_header.php';
require_once '../includes/patient_navbar.php';

$patient_number = $_SESSION['patient_number'];

$lab_tests = mysqli_query($conn, "SELECT * FROM his_laboratory 
                                  WHERE lab_pat_number = '$patient_number' 
                                  ORDER BY lab_date_rec DESC");

// Get lab test statistics
$total = mysqli_num_rows($lab_tests);
$completed = mysqli_query($conn, "SELECT COUNT(*) as count FROM his_laboratory 
                                  WHERE lab_pat_number = '$patient_number' 
                                  AND lab_pat_results IS NOT NULL 
                                  AND lab_pat_results != ''");
$completed_count = mysqli_fetch_assoc($completed)['count'];
$pending_count = $total - $completed_count;
?>

<div class="container">
    <div class="page-header">
        <div>
            <h1><i class="fas fa-flask"></i> My Lab Tests</h1>
            <p>View your laboratory test orders and results</p>
        </div>
    </div>

    <!-- Lab Test Statistics -->
    <div class="stats-grid" style="margin-bottom: 30px;">
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #667eea, #764ba2);">
                <i class="fas fa-vial"></i>
            </div>
            <div class="stat-value"><?php echo $total; ?></div>
            <div class="stat-label">Total Lab Tests</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #43e97b, #38f9d7);">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-value"><?php echo $completed_count; ?></div>
            <div class="stat-label">Completed</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #fa709a, #fee140);">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-value"><?php echo $pending_count; ?></div>
            <div class="stat-label">Pending Results</div>
        </div>
    </div>
  
    <div class="content-card">
        <div class="card-header">
            <h2><i class="fas fa-list"></i> All My Lab Tests (<?php echo $total; ?>)</h2>
        </div>
        <?php if($total > 0): ?>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Lab #</th>
                        <th>Ailment</th>
                        <th>Tests Ordered</th>
                        <th>Status</th>
                        <th>Date Ordered</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($lab = mysqli_fetch_assoc($lab_tests)): 
                        $has_results = !empty($lab['lab_pat_results']);
                    ?>
                    <tr>
                        <td><strong><?php echo $lab['lab_number']; ?></strong></td>
                        <td><?php echo $lab['lab_pat_ailment']; ?></td>
                        <td>
                            <div style="max-width: 300px; overflow: hidden; text-overflow: ellipsis;">
                                <?php echo strip_tags(substr($lab['lab_pat_tests'], 0, 100)); ?>...
                            </div>
                        </td>
                        <td>
                            <?php if($has_results): ?>
                                <span class="badge badge-success">
                                    <i class="fas fa-check-circle"></i> Results Available
                                </span>
                            <?php else: ?>
                                <span class="badge badge-warning">
                                    <i class="fas fa-clock"></i> Pending
                                </span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <strong><?php echo date('M d, Y', strtotime($lab['lab_date_rec'])); ?></strong><br>
                            <small style="color: #999;"><?php echo date('h:i A', strtotime($lab['lab_date_rec'])); ?></small>
                        </td>
                        <td>
                            <a href="view_lab_test.php?id=<?php echo $lab['lab_id']; ?>" class="btn btn-sm btn-primary" title="View Details">
                                <i class="fas fa-eye"></i> View
                            </a>
                            <?php if($has_results): ?>
                            <a href="print_lab_results.php?id=<?php echo $lab['lab_id']; ?>" class="btn btn-sm btn-info" title="Print Results" target="_blank">
                                <i class="fas fa-print"></i> Print
                            </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <div style="text-align: center; padding: 60px 20px; color: #999;">
            <i class="fas fa-flask" style="font-size: 64px; margin-bottom: 20px; display: block; opacity: 0.5;"></i>
            <h3 style="margin-bottom: 10px; color: #666;">No Lab Tests Yet</h3>
            <p>You don't have any lab tests on record. Your doctor will order tests when needed.</p>
        </div>
        <?php endif; ?>
    </div>

    <?php if($total > 0): ?>
    <!-- Lab Test Information -->
    <div class="content-card">
        <div class="card-header">
            <h2><i class="fas fa-info-circle"></i> Understanding Your Lab Tests</h2>
        </div>
        <div style="padding: 20px;">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
                <div style="background: #e8f4fd; padding: 20px; border-radius: 12px; border-left: 4px solid #4facfe;">
                    <h3 style="color: #4facfe; margin-bottom: 10px; font-size: 16px;">
                        <i class="fas fa-clipboard-check"></i> Before Your Test
                    </h3>
                    <ul style="margin: 0; padding-left: 20px; color: #666; line-height: 1.8;">
                        <li>Follow fasting instructions if required</li>
                        <li>Bring your prescription and ID</li>
                        <li>Arrive 15 minutes early</li>
                        <li>Ask about preparation requirements</li>
                    </ul>
                </div>
                
                <div style="background: #fff4e6; padding: 20px; border-radius: 12px; border-left: 4px solid #fa709a;">
                    <h3 style="color: #fa709a; margin-bottom: 10px; font-size: 16px;">
                        <i class="fas fa-hourglass-half"></i> During Sample Collection
                    </h3>
                    <ul style="margin: 0; padding-left: 20px; color: #666; line-height: 1.8;">
                        <li>Stay calm and relaxed</li>
                        <li>Inform staff of any allergies</li>
                        <li>Drink water if permitted</li>
                        <li>Get a collection receipt</li>
                    </ul>
                </div>
                
                <div style="background: #e8f8f5; padding: 20px; border-radius: 12px; border-left: 4px solid #43e97b;">
                    <h3 style="color: #43e97b; margin-bottom: 10px; font-size: 16px;">
                        <i class="fas fa-file-medical-alt"></i> After Results
                    </h3>
                    <ul style="margin: 0; padding-left: 20px; color: #666; line-height: 1.8;">
                        <li>Review results with your doctor</li>
                        <li>Don't panic about abnormal values</li>
                        <li>Keep results for future reference</li>
                        <li>Follow up as recommended</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Common Lab Tests Guide -->
    <div class="content-card">
        <div class="card-header">
            <h2><i class="fas fa-book-medical"></i> Common Lab Tests</h2>
        </div>
        <div style="padding: 20px;">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 15px;">
                <div style="background: #f8f9fa; padding: 15px; border-radius: 8px;">
                    <strong style="color: #667eea; display: block; margin-bottom: 8px;">
                        <i class="fas fa-tint"></i> Complete Blood Count (CBC)
                    </strong>
                    <p style="margin: 0; font-size: 13px; color: #666;">
                        Measures red blood cells, white blood cells, and platelets. Helps detect anemia and infections.
                    </p>
                </div>
                
                <div style="background: #f8f9fa; padding: 15px; border-radius: 8px;">
                    <strong style="color: #f5576c; display: block; margin-bottom: 8px;">
                        <i class="fas fa-heart"></i> Lipid Profile
                    </strong>
                    <p style="margin: 0; font-size: 13px; color: #666;">
                        Checks cholesterol levels. Important for heart health assessment.
                    </p>
                </div>
                
                <div style="background: #f8f9fa; padding: 15px; border-radius: 8px;">
                    <strong style="color: #43e97b; display: block; margin-bottom: 8px;">
                        <i class="fas fa-cookie-bite"></i> Blood Sugar
                    </strong>
                    <p style="margin: 0; font-size: 13px; color: #666;">
                        Measures glucose levels. Essential for diabetes monitoring.
                    </p>
                </div>
                
                <div style="background: #f8f9fa; padding: 15px; border-radius: 8px;">
                    <strong style="color: #4facfe; display: block; margin-bottom: 8px;">
                        <i class="fas fa-shield-virus"></i> Liver Function Test
                    </strong>
                    <p style="margin: 0; font-size: 13px; color: #666;">
                        Evaluates liver health and detects liver problems.
                    </p>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php require_once '../includes/footer.php'; ?>