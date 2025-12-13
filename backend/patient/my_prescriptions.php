<?php
$page_title = "My Prescriptions";
require_once '../includes/patient_header.php';
require_once '../includes/patient_navbar.php';

$patient_number = $_SESSION['patient_number'];

$prescriptions = mysqli_query($conn, "SELECT * FROM his_prescriptions 
                                      WHERE pres_pat_number = '$patient_number' 
                                      ORDER BY pres_date DESC");

// Get prescription statistics
$total = mysqli_num_rows($prescriptions);
$recent = mysqli_query($conn, "SELECT COUNT(*) as count FROM his_prescriptions 
                               WHERE pres_pat_number = '$patient_number' 
                               AND pres_date >= DATE_SUB(NOW(), INTERVAL 30 DAY)");
$recent_count = mysqli_fetch_assoc($recent)['count'];
?>

<div class="container">
    <div class="page-header">
        <div>
            <h1><i class="fas fa-prescription"></i> My Prescriptions</h1>
            <p>View all your medical prescriptions and treatment plans</p>
        </div>
    </div>

    <!-- Prescription Statistics -->
    <div class="stats-grid" style="margin-bottom: 30px;">
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #667eea, #764ba2);">
                <i class="fas fa-file-prescription"></i>
            </div>
            <div class="stat-value"><?php echo $total; ?></div>
            <div class="stat-label">Total Prescriptions</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #43e97b, #38f9d7);">
                <i class="fas fa-calendar-day"></i>
            </div>
            <div class="stat-value"><?php echo $recent_count; ?></div>
            <div class="stat-label">Last 30 Days</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #4facfe, #00f2fe);">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-value"><?php echo $total > 0 ? date('M d, Y', strtotime(mysqli_fetch_assoc(mysqli_query($conn, "SELECT MAX(pres_date) as latest FROM his_prescriptions WHERE pres_pat_number = '$patient_number'"))['latest'])) : 'N/A'; ?></div>
            <div class="stat-label">Last Prescription</div>
        </div>
    </div>
  
    <div class="content-card">
        <div class="card-header">
            <h2><i class="fas fa-list"></i> All My Prescriptions (<?php echo $total; ?>)</h2>
        </div>
        <?php if($total > 0): ?>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Prescription #</th>
                        <th>Ailment</th>
                        <th>Patient Type</th>
                        <th>Date Issued</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    mysqli_data_seek($prescriptions, 0); // Reset pointer
                    while ($pres = mysqli_fetch_assoc($prescriptions)): 
                    ?>
                    <tr>
                        <td><strong><?php echo $pres['pres_number']; ?></strong></td>
                        <td><?php echo $pres['pres_pat_ailment']; ?></td>
                        <td>
                            <span class="badge badge-<?php echo $pres['pres_pat_type'] == 'InPatient' ? 'danger' : 'success'; ?>">
                                <i class="fas fa-<?php echo $pres['pres_pat_type'] == 'InPatient' ? 'bed' : 'walking'; ?>"></i>
                                <?php echo $pres['pres_pat_type']; ?>
                            </span>
                        </td>
                        <td>
                            <strong><?php echo date('M d, Y', strtotime($pres['pres_date'])); ?></strong><br>
                            <small style="color: #999;"><?php echo date('h:i A', strtotime($pres['pres_date'])); ?></small>
                        </td>
                        <td>
                            <a href="view_prescription.php?id=<?php echo $pres['pres_id']; ?>" class="btn btn-sm btn-primary" title="View Full Details">
                                <i class="fas fa-eye"></i> View
                            </a>
                            <a href="print_prescription.php?id=<?php echo $pres['pres_id']; ?>" class="btn btn-sm btn-info" title="Print Prescription" target="_blank">
                                <i class="fas fa-print"></i> Print
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <div style="text-align: center; padding: 60px 20px; color: #999;">
            <i class="fas fa-prescription" style="font-size: 64px; margin-bottom: 20px; display: block; opacity: 0.5;"></i>
            <h3 style="margin-bottom: 10px; color: #666;">No Prescriptions Yet</h3>
            <p>You don't have any prescriptions on record. Visit your doctor to get a prescription.</p>
        </div>
        <?php endif; ?>
    </div>

    <?php if($total > 0): ?>
    <!-- Important Information -->
    <div class="content-card">
        <div class="card-header">
            <h2><i class="fas fa-info-circle"></i> Important Information</h2>
        </div>
        <div style="padding: 20px;">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
                <div style="background: #e8f4fd; padding: 20px; border-radius: 12px; border-left: 4px solid #4facfe;">
                    <h3 style="color: #4facfe; margin-bottom: 10px; font-size: 16px;">
                        <i class="fas fa-pills"></i> Taking Medications
                    </h3>
                    <ul style="margin: 0; padding-left: 20px; color: #666; line-height: 1.8;">
                        <li>Always follow the prescribed dosage</li>
                        <li>Complete the full course of medication</li>
                        <li>Take medicines at the same time daily</li>
                        <li>Don't skip doses</li>
                    </ul>
                </div>
                
                <div style="background: #fff4e6; padding: 20px; border-radius: 12px; border-left: 4px solid #fa709a;">
                    <h3 style="color: #fa709a; margin-bottom: 10px; font-size: 16px;">
                        <i class="fas fa-exclamation-triangle"></i> Safety Precautions
                    </h3>
                    <ul style="margin: 0; padding-left: 20px; color: #666; line-height: 1.8;">
                        <li>Keep medicines in a cool, dry place</li>
                        <li>Check expiry dates regularly</li>
                        <li>Keep away from children</li>
                        <li>Don't share your medicines</li>
                    </ul>
                </div>
                
                <div style="background: #e8f8f5; padding: 20px; border-radius: 12px; border-left: 4px solid #43e97b;">
                    <h3 style="color: #43e97b; margin-bottom: 10px; font-size: 16px;">
                        <i class="fas fa-phone-alt"></i> Need Help?
                    </h3>
                    <ul style="margin: 0; padding-left: 20px; color: #666; line-height: 1.8;">
                        <li>Contact your doctor for clarifications</li>
                        <li>Report any side effects immediately</li>
                        <li>Emergency: +880 1234-567890</li>
                        <li>Hospital: 24/7 hotline available</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php require_once '../includes/footer.php'; ?>