<?php
$page_title = "Dashboard";
require_once '../includes/header.php';
require_once '../includes/navbar.php';

$stats = [];

$result = mysqli_query($conn, "SELECT COUNT(*) as total FROM his_patients");
$stats['patients'] = mysqli_fetch_assoc($result)['total'];

$result = mysqli_query($conn, "SELECT COUNT(*) as total FROM his_docs");
$stats['doctors'] = mysqli_fetch_assoc($result)['total'];

$result = mysqli_query($conn, "SELECT COUNT(*) as total FROM his_laboratory");
$stats['lab_tests'] = mysqli_fetch_assoc($result)['total'];

$result = mysqli_query($conn, "SELECT COUNT(*) as total FROM his_pharmaceuticals");
$stats['pharmaceuticals'] = mysqli_fetch_assoc($result)['total'];

$result = mysqli_query($conn, "SELECT COUNT(*) as total FROM his_surgery");
$stats['surgeries'] = mysqli_fetch_assoc($result)['total'];

// Fixed revenue calculation
$result = mysqli_query($conn, "SELECT SUM(CAST(REPLACE(acc_amount, ',', '') AS DECIMAL(15,2))) as total FROM his_accounts");
$revenue_data = mysqli_fetch_assoc($result);
$stats['revenue'] = $revenue_data['total'] ?? 0;

$recent_patients = mysqli_query($conn, "SELECT * FROM his_patients ORDER BY pat_date_joined DESC LIMIT 5");
?>

<div class="container">
    <div class="page-header">
        <div>
            <h1><i class="fas fa-tachometer-alt"></i> Dashboard</h1>
            <p>Welcome back, <?php echo $_SESSION['admin_name']; ?>! Here's your hospital overview.</p>
        </div>
    </div>
    
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #667eea, #764ba2);">
                <i class="fas fa-user-injured"></i>
            </div>
            <div class="stat-value"><?php echo number_format($stats['patients']); ?></div>
            <div class="stat-label">Total Patients</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #f093fb, #f5576c);">
                <i class="fas fa-user-md"></i>
            </div>
            <div class="stat-value"><?php echo number_format($stats['doctors']); ?></div>
            <div class="stat-label">Doctors</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #4facfe, #00f2fe);">
                <i class="fas fa-flask"></i>
            </div>
            <div class="stat-value"><?php echo number_format($stats['lab_tests']); ?></div>
            <div class="stat-label">Lab Tests</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #43e97b, #38f9d7);">
                <i class="fas fa-pills"></i>
            </div>
            <div class="stat-value"><?php echo number_format($stats['pharmaceuticals']); ?></div>
            <div class="stat-label">Pharmaceuticals</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #fa709a, #fee140);">
                <i class="fas fa-syringe"></i>
            </div>
            <div class="stat-value"><?php echo number_format($stats['surgeries']); ?></div>
            <div class="stat-label">Surgeries</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #30cfd0, #330867);">
                <i class="fas fa-dollar-sign"></i>
            </div>
            <div class="stat-value">৳<?php echo number_format($stats['revenue'], 2); ?></div>
            <div class="stat-label">Total Revenue (BDT)</div>
        </div>
    </div>
    
    <div class="content-card">
        <div class="card-header">
            <h2><i class="fas fa-users"></i> Recent Patients</h2>
            <a href="patients.php" class="btn btn-primary">View All</a>
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
                    <?php while ($patient = mysqli_fetch_assoc($recent_patients)): ?>
                    <tr>
                        <td><strong><?php echo $patient['pat_number']; ?></strong></td>
                        <td><?php echo $patient['pat_fname'] . ' ' . $patient['pat_lname']; ?></td>
                        <td><?php echo $patient['pat_age']; ?> years</td>
                        <td><?php echo $patient['pat_phone']; ?></td>
                        <td><?php echo $patient['pat_ailment']; ?></td>
                        <td><span class="badge badge-<?php echo $patient['pat_type'] == 'InPatient' ? 'danger' : 'success'; ?>"><?php echo $patient['pat_type']; ?></span></td>
                        <td><?php echo date('M d, Y', strtotime($patient['pat_date_joined'])); ?></td>
                        <td>
                            <a href="view_patient.php?id=<?php echo $patient['pat_id']; ?>" class="btn btn-sm btn-primary" title="View Details">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>