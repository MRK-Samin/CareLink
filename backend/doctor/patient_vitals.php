<?php
$page_title = "Patient Vitals";
require_once '../includes/doctor_header.php';
require_once '../includes/doctor_navbar.php';

// Get patient if passed via URL
$filter_patient = isset($_GET['pat']) ? mysqli_real_escape_string($conn, $_GET['pat']) : '';

$vitals_query = "SELECT v.*, p.pat_fname, p.pat_lname, p.pat_ailment 
                 FROM his_vitals v 
                 LEFT JOIN his_patients p ON v.vit_pat_number = p.pat_number";

if($filter_patient) {
    $vitals_query .= " WHERE v.vit_pat_number = '$filter_patient'";
}

$vitals_query .= " ORDER BY v.vit_daterec DESC";

$vitals = mysqli_query($conn, $vitals_query);

if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    $vitals = mysqli_query($conn, "SELECT v.*, p.pat_fname, p.pat_lname, p.pat_ailment 
                                    FROM his_vitals v 
                                    LEFT JOIN his_patients p ON v.vit_pat_number = p.pat_number 
                                    WHERE v.vit_pat_number LIKE '%$search%' OR 
                                    v.vit_number LIKE '%$search%' OR
                                    p.pat_fname LIKE '%$search%' OR
                                    p.pat_lname LIKE '%$search%'
                                    ORDER BY v.vit_daterec DESC");
}

// Get vital statistics
$critical = mysqli_query($conn, "SELECT COUNT(*) as count FROM his_vitals v 
                                 LEFT JOIN his_patients p ON v.vit_pat_number = p.pat_number 
                                 WHERE CAST(v.vit_bodytemp AS DECIMAL(5,2)) > 100");
$critical_count = mysqli_fetch_assoc($critical)['count'];

$total_records = mysqli_num_rows($vitals);
?>

<div class="container">
    <div class="page-header">
        <div>
            <h1><i class="fas fa-heartbeat"></i> Patient Vitals</h1>
            <p>Monitor and track patient vital signs</p>
        </div>
        <a href="add_vitals.php<?php echo $filter_patient ? '?pat=' . $filter_patient : ''; ?>" class="btn btn-primary">
            <i class="fas fa-plus"></i> Record New Vitals
        </a>
    </div>

    <!-- Vital Statistics -->
    <div class="stats-grid" style="margin-bottom: 30px;">
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #667eea, #764ba2);">
                <i class="fas fa-clipboard-list"></i>
            </div>
            <div class="stat-value"><?php echo $total_records; ?></div>
            <div class="stat-label">Total Records</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #f093fb, #f5576c);">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="stat-value"><?php echo $critical_count; ?></div>
            <div class="stat-label">Critical Cases</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #43e97b, #38f9d7);">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-value"><?php echo $total_records - $critical_count; ?></div>
            <div class="stat-label">Normal Cases</div>
        </div>
    </div>

    <div class="search-bar">
        <form method="GET" action="">
            <input type="text" name="search" placeholder="Search by patient name, patient number, vital number..." 
                   value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
            <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Search</button>
            <?php if(isset($_GET['search']) || $filter_patient): ?>
                <a href="patient_vitals.php" class="btn btn-secondary"><i class="fas fa-times"></i> Clear</a>
            <?php endif; ?>
        </form>
    </div>
  
    <div class="content-card">
        <div class="card-header">
            <h2><i class="fas fa-list"></i> All Vital Records (<?php echo mysqli_num_rows($vitals); ?>)</h2>
        </div>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Vital #</th>
                        <th>Patient Name</th>
                        <th>Patient #</th>
                        <th>Body Temp (°F)</th>
                        <th>Heart Rate (bpm)</th>
                        <th>Resp. Rate</th>
                        <th>Blood Pressure</th>
                        <th>Health Status</th>
                        <th>Date Recorded</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(mysqli_num_rows($vitals) > 0): ?>
                        <?php while ($vital = mysqli_fetch_assoc($vitals)): 
                            // Determine health status based on vitals
                            $temp = floatval($vital['vit_bodytemp']);
                            $heart_rate = intval($vital['vit_heartpulse']);
                            $bp_parts = explode('/', $vital['vit_bloodpress']);
                            $systolic = isset($bp_parts[0]) ? intval($bp_parts[0]) : 120;
                            
                            $status = 'success';
                            $status_text = 'Normal';
                            
                            if($temp > 102 || $systolic > 140 || $heart_rate > 100) {
                                $status = 'danger';
                                $status_text = 'Critical';
                            } elseif($temp > 99 || $systolic > 130 || $heart_rate > 90) {
                                $status = 'warning';
                                $status_text = 'Warning';
                            }
                        ?>
                        <tr>
                            <td><strong><?php echo $vital['vit_number']; ?></strong></td>
                            <td><?php echo $vital['pat_fname'] . ' ' . $vital['pat_lname']; ?></td>
                            <td><?php echo $vital['vit_pat_number']; ?></td>
                            <td>
                                <span class="badge badge-<?php echo $temp > 100 ? 'danger' : ($temp > 99 ? 'warning' : 'info'); ?>">
                                    <i class="fas fa-thermometer-half"></i> <?php echo $vital['vit_bodytemp']; ?>°F
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-<?php echo $heart_rate > 100 ? 'danger' : ($heart_rate > 90 ? 'warning' : 'info'); ?>">
                                    <i class="fas fa-heartbeat"></i> <?php echo $vital['vit_heartpulse']; ?>
                                </span>
                            </td>
                            <td><?php echo $vital['vit_resprate']; ?>/min</td>
                            <td>
                                <span class="badge badge-<?php echo $systolic > 140 ? 'danger' : ($systolic > 130 ? 'warning' : 'info'); ?>">
                                    <i class="fas fa-tint"></i> <?php echo $vital['vit_bloodpress']; ?> mmHg
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-<?php echo $status; ?>">
                                    <i class="fas fa-<?php echo $status == 'danger' ? 'exclamation-triangle' : ($status == 'warning' ? 'exclamation-circle' : 'check-circle'); ?>"></i>
                                    <?php echo $status_text; ?>
                                </span>
                            </td>
                            <td>
                                <strong><?php echo date('M d, Y', strtotime($vital['vit_daterec'])); ?></strong><br>
                                <small><?php echo date('h:i A', strtotime($vital['vit_daterec'])); ?></small>
                            </td>
                            <td>
                                <a href="view_vitals.php?id=<?php echo $vital['vit_id']; ?>" class="btn btn-sm btn-primary" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="add_vitals.php?pat=<?php echo $vital['vit_pat_number']; ?>" class="btn btn-sm btn-success" title="Add New Record">
                                    <i class="fas fa-plus"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="10" style="text-align: center; padding: 40px; color: #999;">
                                <i class="fas fa-heartbeat" style="font-size: 48px; margin-bottom: 15px; display: block;"></i>
                                No vital records found. <?php if(isset($_GET['search'])): ?>Try a different search.<?php endif; ?>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Vital Signs Reference Guide -->
    <div class="content-card">
        <div class="card-header">
            <h2><i class="fas fa-info-circle"></i> Normal Vital Signs Reference</h2>
        </div>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; padding: 20px;">
            <div style="background: #f8f9fa; padding: 20px; border-radius: 12px; border-left: 4px solid #667eea;">
                <h3 style="color: #667eea; margin-bottom: 10px; font-size: 16px;">
                    <i class="fas fa-thermometer-half"></i> Body Temperature
                </h3>
                <p style="margin: 5px 0;"><strong>Normal:</strong> 97.8°F - 99.1°F (36.5°C - 37.3°C)</p>
                <p style="margin: 5px 0;"><strong>Fever:</strong> Above 100.4°F (38°C)</p>
            </div>
            
            <div style="background: #f8f9fa; padding: 20px; border-radius: 12px; border-left: 4px solid #f5576c;">
                <h3 style="color: #f5576c; margin-bottom: 10px; font-size: 16px;">
                    <i class="fas fa-heartbeat"></i> Heart Rate
                </h3>
                <p style="margin: 5px 0;"><strong>Normal:</strong> 60-100 beats per minute</p>
                <p style="margin: 5px 0;"><strong>Tachycardia:</strong> Above 100 bpm</p>
            </div>
            
            <div style="background: #f8f9fa; padding: 20px; border-radius: 12px; border-left: 4px solid #43e97b;">
                <h3 style="color: #43e97b; margin-bottom: 10px; font-size: 16px;">
                    <i class="fas fa-lungs"></i> Respiratory Rate
                </h3>
                <p style="margin: 5px 0;"><strong>Normal:</strong> 12-20 breaths per minute</p>
                <p style="margin: 5px 0;"><strong>Abnormal:</strong> Below 12 or above 20</p>
            </div>
            
            <div style="background: #f8f9fa; padding: 20px; border-radius: 12px; border-left: 4px solid #4facfe;">
                <h3 style="color: #4facfe; margin-bottom: 10px; font-size: 16px;">
                    <i class="fas fa-tint"></i> Blood Pressure
                </h3>
                <p style="margin: 5px 0;"><strong>Normal:</strong> 120/80 mmHg</p>
                <p style="margin: 5px 0;"><strong>Hypertension:</strong> Above 140/90 mmHg</p>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>