<?php
$page_title = "My Patients";
require_once '../includes/doctor_header.php';
require_once '../includes/doctor_navbar.php';

$patients = mysqli_query($conn, "SELECT * FROM his_patients ORDER BY pat_date_joined DESC");

if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    $patients = mysqli_query($conn, "SELECT * FROM his_patients WHERE 
        pat_fname LIKE '%$search%' OR 
        pat_lname LIKE '%$search%' OR 
        pat_number LIKE '%$search%' OR 
        pat_phone LIKE '%$search%' OR 
        pat_ailment LIKE '%$search%'
        ORDER BY pat_date_joined DESC");
}

// Get patient statistics
$total_patients = mysqli_query($conn, "SELECT COUNT(*) as count FROM his_patients");
$total = mysqli_fetch_assoc($total_patients)['count'];

$inpatients = mysqli_query($conn, "SELECT COUNT(*) as count FROM his_patients WHERE pat_type = 'InPatient'");
$inpatient_count = mysqli_fetch_assoc($inpatients)['count'];

$outpatients = mysqli_query($conn, "SELECT COUNT(*) as count FROM his_patients WHERE pat_type = 'OutPatient'");
$outpatient_count = mysqli_fetch_assoc($outpatients)['count'];
?>

<div class="container">
    <div class="page-header">
        <div>
            <h1><i class="fas fa-user-injured"></i> My Patients</h1>
            <p>View and manage your patients</p>
        </div>
    </div>

    <!-- Patient Statistics -->
    <div class="stats-grid" style="margin-bottom: 30px;">
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #667eea, #764ba2);">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-value"><?php echo $total; ?></div>
            <div class="stat-label">Total Patients</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #f093fb, #f5576c);">
                <i class="fas fa-bed"></i>
            </div>
            <div class="stat-value"><?php echo $inpatient_count; ?></div>
            <div class="stat-label">InPatients</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #43e97b, #38f9d7);">
                <i class="fas fa-walking"></i>
            </div>
            <div class="stat-value"><?php echo $outpatient_count; ?></div>
            <div class="stat-label">OutPatients</div>
        </div>
    </div>

    <div class="search-bar">
        <form method="GET" action="">
            <input type="text" name="search" placeholder="Search patients by name, number, phone, ailment..." 
                   value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
            <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Search</button>
            <?php if(isset($_GET['search'])): ?>
                <a href="my_patients.php" class="btn btn-secondary"><i class="fas fa-times"></i> Clear</a>
            <?php endif; ?>
        </form>
    </div>
  
    <div class="content-card">
        <div class="card-header">
            <h2><i class="fas fa-list"></i> All Patients (<?php echo mysqli_num_rows($patients); ?>)</h2>
        </div>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Patient #</th>
                        <th>Name</th>
                        <th>Age</th>
                        <th>Phone</th>
                        <th>Ailment</th>
                        <th>Type</th>
                        <th>Date Joined</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(mysqli_num_rows($patients) > 0): ?>
                        <?php while ($patient = mysqli_fetch_assoc($patients)): ?>
                        <tr>
                            <td><strong><?php echo $patient['pat_number']; ?></strong></td>
                            <td><?php echo $patient['pat_fname'] . ' ' . $patient['pat_lname']; ?></td>
                            <td><?php echo $patient['pat_age']; ?> years</td>
                            <td><?php echo $patient['pat_phone']; ?></td>
                            <td><?php echo $patient['pat_ailment']; ?></td>
                            <td>
                                <span class="badge badge-<?php echo $patient['pat_type'] == 'InPatient' ? 'danger' : 'success'; ?>">
                                    <i class="fas fa-<?php echo $patient['pat_type'] == 'InPatient' ? 'bed' : 'walking'; ?>"></i>
                                    <?php echo $patient['pat_type']; ?>
                                </span>
                            </td>
                            <td><?php echo date('M d, Y', strtotime($patient['pat_date_joined'])); ?></td>
                            <td>
                                <a href="view_patient.php?id=<?php echo $patient['pat_id']; ?>" class="btn btn-sm btn-primary" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="add_prescription.php?pat=<?php echo $patient['pat_number']; ?>" class="btn btn-sm btn-success" title="Write Prescription">
                                    <i class="fas fa-prescription"></i>
                                </a>
                                <a href="add_lab_test.php?pat=<?php echo $patient['pat_number']; ?>" class="btn btn-sm btn-info" title="Order Lab Test">
                                    <i class="fas fa-flask"></i>
                                </a>
                                <a href="patient_vitals.php?pat=<?php echo $patient['pat_number']; ?>" class="btn btn-sm btn-warning" title="View Vitals">
                                    <i class="fas fa-heartbeat"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 40px; color: #999;">
                                <i class="fas fa-user-injured" style="font-size: 48px; margin-bottom: 15px; display: block;"></i>
                                No patients found. <?php if(isset($_GET['search'])): ?>Try a different search.<?php endif; ?>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>