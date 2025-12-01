<?php
$page_title = "Patient Portal";
require_once '../includes/patient_header.php';
require_once '../includes/patient_navbar.php';

$patient_number = $_SESSION['patient_number'];

// Get patient details
$patient_query = mysqli_query($conn, "SELECT * FROM his_patients WHERE pat_number = '$patient_number'");
$patient = mysqli_fetch_assoc($patient_query);

// Get patient statistics
$prescriptions = mysqli_query($conn, "SELECT COUNT(*) as total FROM his_prescriptions WHERE pres_pat_number = '$patient_number'");
$stats['prescriptions'] = mysqli_fetch_assoc($prescriptions)['total'];

$lab_tests = mysqli_query($conn, "SELECT COUNT(*) as total FROM his_laboratory WHERE lab_pat_number = '$patient_number'");
$stats['lab_tests'] = mysqli_fetch_assoc($lab_tests)['total'];

$vitals = mysqli_query($conn, "SELECT COUNT(*) as total FROM his_vitals WHERE vit_pat_number = '$patient_number'");
$stats['vitals'] = mysqli_fetch_assoc($vitals)['total'];

$medical_records = mysqli_query($conn, "SELECT COUNT(*) as total FROM his_medical_records WHERE mdr_pat_number = '$patient_number'");
$stats['medical_records'] = mysqli_fetch_assoc($medical_records)['total'];

// Recent prescriptions
$recent_prescriptions = mysqli_query($conn, "SELECT * FROM his_prescriptions WHERE pres_pat_number = '$patient_number' ORDER BY pres_date DESC LIMIT 3");

// Recent lab tests
$recent_lab = mysqli_query($conn, "SELECT * FROM his_laboratory WHERE lab_pat_number = '$patient_number' ORDER BY lab_date_rec DESC LIMIT 3");
?>

<div class="container">
    <div class="page-header">
        <div>
            <h1><i class="fas fa-user-circle"></i> Patient Portal</h1>
            <p>Welcome, <?php echo $_SESSION['patient_name']; ?>!</p>
        </div>
    </div>
    
    <!-- Patient Info Card -->
    <div class="content-card" style="margin-bottom: 30px;">
        <div class="card-header">
            <h2><i class="fas fa-id-card"></i> My Information</h2>
        </div>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
            <div>
                <p><strong><i class="fas fa-hashtag"></i> Patient Number:</strong> <?php echo $patient['pat_number']; ?></p>
                <p><strong><i class="fas fa-user"></i> Full Name:</strong> <?php echo $patient['pat_fname'] . ' ' . $patient['pat_lname']; ?></p>
                <p><strong><i class="fas fa-birthday-cake"></i> Date of Birth:</strong> <?php echo $patient['pat_dob']; ?></p>
            </div>
            <div>
                <p><strong><i class="fas fa-phone"></i> Phone:</strong> <?php echo $patient['pat_phone']; ?></p>
                <p><strong><i class="fas fa-map-marker-alt"></i> Address:</strong> <?php echo $patient['pat_addr']; ?></p>
                <p><strong><i class="fas fa-stethoscope"></i> Current Ailment:</strong> <?php echo $patient['pat_ailment']; ?></p>
            </div>
            <div>
                <p><strong><i class="fas fa-hospital-user"></i> Patient Type:</strong> 
                    <span class="badge badge-<?php echo $patient['pat_type'] == 'InPatient' ? 'danger' : 'success'; ?>">
                        <?php echo $patient['pat_type']; ?>
                    </span>
                </p>
                <p><strong><i class="fas fa-calendar"></i> Joined:</strong> <?php echo date('Y-m-d', strtotime($patient['pat_date_joined'])); ?></p>
            </div>
        </div>
    </div>
    
    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #667eea, #764ba2);">
                <i class="fas fa-prescription"></i>
            </div>
            <div class="stat-value"><?php echo $stats['prescriptions']; ?></div>
            <div class="stat-label">My Prescriptions</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #f093fb, #f5576c);">
                <i class="fas fa-flask"></i>
            </div>
            <div class="stat-value"><?php echo $stats['lab_tests']; ?></div>
            <div class="stat-label">Lab Tests</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #4facfe, #00f2fe);">
                <i class="fas fa-heartbeat"></i>
            </div>
            <div class="stat-value"><?php echo $stats['vitals']; ?></div>
            <div class="stat-label">Vital Records</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #43e97b, #38f9d7);">
                <i class="fas fa-file-medical"></i>
            </div>
            <div class="stat-value"><?php echo $stats['medical_records']; ?></div>
            <div class="stat-label">Medical Records</div>
        </div>
    </div>
    
    <!-- Recent Prescriptions -->
    <div class="content-card">
        <div class="card-header">
            <h2><i class="fas fa-prescription"></i> Recent Prescriptions</h2>
            <a href="my_prescriptions.php" class="btn btn-primary">View All</a>
        </div>
        <?php if (mysqli_num_rows($recent_prescriptions) > 0): ?>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Prescription #</th>
                        <th>Ailment</th>
                        <th>Type</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($pres = mysqli_fetch_assoc($recent_prescriptions)): ?>
                    <tr>
                        <td><?php echo $pres['pres_number']; ?></td>
                        <td><?php echo $pres['pres_pat_ailment']; ?></td>
                        <td><span class="badge badge-info"><?php echo $pres['pres_pat_type']; ?></span></td>
                        <td><?php echo date('Y-m-d', strtotime($pres['pres_date'])); ?></td>
                        <td>
                            <a href="view_prescription.php?id=<?php echo $pres['pres_id']; ?>" class="btn btn-sm btn-primary">
                                <i class="fas fa-eye"></i> View
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <p style="text-align: center; padding: 20px; color: #999;">No prescriptions found.</p>
        <?php endif; ?>
    </div>
    
    <!-- Recent Lab Tests -->
    <div class="content-card">
        <div class="card-header">
            <h2><i class="fas fa-flask"></i> Recent Lab Tests</h2>
            <a href="my_lab_tests.php" class="btn btn-primary">View All</a>
        </div>
        <?php if (mysqli_num_rows($recent_lab) > 0): ?>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Lab #</th>
                        <th>Ailment</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($lab = mysqli_fetch_assoc($recent_lab)): ?>
                    <tr>
                        <td><?php echo $lab['lab_number']; ?></td>
                        <td><?php echo $lab['lab_pat_ailment']; ?></td>
                        <td><?php echo date('Y-m-d', strtotime($lab['lab_date_rec'])); ?></td>
                        <td>
                            <span class="badge badge-<?php echo !empty($lab['lab_pat_results']) ? 'success' : 'warning'; ?>">
                                <?php echo !empty($lab['lab_pat_results']) ? 'Completed' : 'Pending'; ?>
                            </span>
                        </td>
                        <td>
                            <a href="view_lab_test.php?id=<?php echo $lab['lab_id']; ?>" class="btn btn-sm btn-primary">
                                <i class="fas fa-eye"></i> View
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <p style="text-align: center; padding: 20px; color: #999;">No lab tests found.</p>
        <?php endif; ?>
    </div>
    
    <!-- Quick Links -->
    <div class="content-card">
        <div class="card-header">
            <h2><i class="fas fa-link"></i> Quick Links</h2>
        </div>
        <div class="stats-grid">
            <a href="my_prescriptions.php" class="stat-card" style="text-decoration: none; cursor: pointer;">
                <div class="stat-icon" style="background: linear-gradient(135deg, #667eea, #764ba2);">
                    <i class="fas fa-prescription"></i>
                </div>
                <div class="stat-label">All Prescriptions</div>
            </a>
            
            <a href="my_lab_tests.php" class="stat-card" style="text-decoration: none; cursor: pointer;">
                <div class="stat-icon" style="background: linear-gradient(135deg, #43e97b, #38f9d7);">
                    <i class="fas fa-flask"></i>
                </div>
                <div class="stat-label">Lab Test Results</div>
            </a>
            
            <a href="my_vitals.php" class="stat-card" style="text-decoration: none; cursor: pointer;">
                <div class="stat-icon" style="background: linear-gradient(135deg, #4facfe, #00f2fe);">
                    <i class="fas fa-heartbeat"></i>
                </div>
                <div class="stat-label">Vital Records</div>
            </a>
            
            <a href="my_medical_records.php" class="stat-card" style="text-decoration: none; cursor: pointer;">
                <div class="stat-icon" style="background: linear-gradient(135deg, #fa709a, #fee140);">
                    <i class="fas fa-file-medical"></i>
                </div>
                <div class="stat-label">Medical Records</div>
            </a>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>