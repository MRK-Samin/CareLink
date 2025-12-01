<?php
$page_title = "Doctor Dashboard";
require_once '../includes/doctor_header.php';
require_once '../includes/doctor_navbar.php';

$doctor_number = $_SESSION['doctor_number'];

// Get statistics
$my_patients = mysqli_query($conn, "SELECT COUNT(*) as total FROM his_patients");
$stats['total_patients'] = mysqli_fetch_assoc($my_patients)['total'];

$prescriptions = mysqli_query($conn, "SELECT COUNT(*) as total FROM his_prescriptions");
$stats['prescriptions'] = mysqli_fetch_assoc($prescriptions)['total'];

$lab_tests = mysqli_query($conn, "SELECT COUNT(*) as total FROM his_laboratory");
$stats['lab_tests'] = mysqli_fetch_assoc($lab_tests)['total'];

$surgeries = mysqli_query($conn, "SELECT COUNT(*) as total FROM his_surgery WHERE s_doc = '{$_SESSION['doctor_name']}'");
$stats['my_surgeries'] = mysqli_fetch_assoc($surgeries)['total'];

// Recent patients
$recent_patients = mysqli_query($conn, "SELECT * FROM his_patients ORDER BY pat_date_joined DESC LIMIT 5");
?>

<div class="container">
    <div class="page-header">
        <div>
            <h1><i class="fas fa-stethoscope"></i> Doctor Dashboard</h1>
            <p>Welcome back, Dr. <?php echo $_SESSION['doctor_name']; ?>! (<?php echo $_SESSION['doctor_dept']; ?>)</p>
        </div>
    </div>
    
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #667eea, #764ba2);">
                <i class="fas fa-user-injured"></i>
            </div>
            <div class="stat-value"><?php echo $stats['total_patients']; ?></div>
            <div class="stat-label">Total Patients</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #f093fb, #f5576c);">
                <i class="fas fa-prescription"></i>
            </div>
            <div class="stat-value"><?php echo $stats['prescriptions']; ?></div>
            <div class="stat-label">Prescriptions</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #4facfe, #00f2fe);">
                <i class="fas fa-flask"></i>
            </div>
            <div class="stat-value"><?php echo $stats['lab_tests']; ?></div>
            <div class="stat-label">Lab Tests</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #fa709a, #fee140);">
                <i class="fas fa-syringe"></i>
            </div>
            <div class="stat-value"><?php echo $stats['my_surgeries']; ?></div>
            <div class="stat-label">My Surgeries</div>
        </div>
    </div>
    
    <div class="content-card">
        <div class="card-header">
            <h2><i class="fas fa-users"></i> Recent Patients</h2>
            <a href="my_patients.php" class="btn btn-primary">View All Patients</a>
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
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($patient = mysqli_fetch_assoc($recent_patients)): ?>
                    <tr>
                        <td><?php echo $patient['pat_number']; ?></td>
                        <td><?php echo $patient['pat_fname'] . ' ' . $patient['pat_lname']; ?></td>
                        <td><?php echo $patient['pat_age']; ?></td>
                        <td><?php echo $patient['pat_phone']; ?></td>
                        <td><?php echo $patient['pat_ailment']; ?></td>
                        <td><span class="badge badge-info"><?php echo $patient['pat_type']; ?></span></td>
                        <td>
                            <a href="view_patient.php?id=<?php echo $patient['pat_id']; ?>" class="btn btn-sm btn-primary">
                                <i class="fas fa-eye"></i> View
                            </a>
                            <a href="add_prescription.php?pat=<?php echo $patient['pat_number']; ?>" class="btn btn-sm btn-success">
                                <i class="fas fa-prescription"></i> Prescribe
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="content-card">
        <div class="card-header">
            <h2><i class="fas fa-calendar-alt"></i> Quick Actions</h2>
        </div>
        <div class="stats-grid">
            <a href="my_patients.php" class="stat-card" style="text-decoration: none; cursor: pointer;">
                <div class="stat-icon" style="background: linear-gradient(135deg, #667eea, #764ba2);">
                    <i class="fas fa-user-injured"></i>
                </div>
                <div class="stat-label">View My Patients</div>
            </a>
            
            <a href="add_prescription.php" class="stat-card" style="text-decoration: none; cursor: pointer;">
                <div class="stat-icon" style="background: linear-gradient(135deg, #43e97b, #38f9d7);">
                    <i class="fas fa-prescription"></i>
                </div>
                <div class="stat-label">Write Prescription</div>
            </a>
            
            <a href="add_lab_test.php" class="stat-card" style="text-decoration: none; cursor: pointer;">
                <div class="stat-icon" style="background: linear-gradient(135deg, #4facfe, #00f2fe);">
                    <i class="fas fa-flask"></i>
                </div>
                <div class="stat-label">Order Lab Test</div>
            </a>
            
            <a href="my_schedule.php" class="stat-card" style="text-decoration: none; cursor: pointer;">
                <div class="stat-icon" style="background: linear-gradient(135deg, #fa709a, #fee140);">
                    <i class="fas fa-calendar"></i>
                </div>
                <div class="stat-label">My Schedule</div>
            </a>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>