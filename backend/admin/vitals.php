<?php
$page_title = "Patient Vitals";
require_once '../includes/header.php';
require_once '../includes/navbar.php';

$vitals = mysqli_query($conn, "SELECT v.*, p.pat_fname, p.pat_lname, p.pat_ailment 
                                FROM his_vitals v 
                                LEFT JOIN his_patients p ON v.vit_pat_number = p.pat_number 
                                ORDER BY v.vit_daterec DESC");

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
?>

<div class="container">
    <div class="page-header">
        <div>
            <h1><i class="fas fa-heartbeat"></i> Patient Vitals Management</h1>
            <p>Monitor and manage patient vital signs and health metrics</p>
        </div>
        <a href="add_vitals.php" class="btn btn-primary">
            <i class="fas fa-plus"></i> Record New Vitals
        </a>
    </div>

    <div class="search-bar">
        <form method="GET" action="">
            <input type="text" name="search" placeholder="Search by patient name, patient number, vital number..." 
                   value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
            <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Search</button>
            <?php if(isset($_GET['search'])): ?>
                <a href="vitals.php" class="btn btn-secondary"><i class="fas fa-times"></i> Clear</a>
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
                            $bp_parts = explode('/', $vital['vit_bloodpress']);
                            $systolic = isset($bp_parts[0]) ? intval($bp_parts[0]) : 120;
                            
                            $status = 'success';
                            $status_text = 'Normal';
                            
                            if($temp > 100 || $systolic > 140) {
                                $status = 'danger';
                                $status_text = 'Critical';
                            } elseif($temp > 99 || $systolic > 130) {
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
                                    <?php echo $vital['vit_bodytemp']; ?>°F
                                </span>
                            </td>
                            <td><?php echo $vital['vit_heartpulse']; ?></td>
                            <td><?php echo $vital['vit_resprate']; ?></td>
                            <td>
                                <span class="badge badge-<?php echo $systolic > 140 ? 'danger' : ($systolic > 130 ? 'warning' : 'info'); ?>">
                                    <?php echo $vital['vit_bloodpress']; ?> mmHg
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-<?php echo $status; ?>">
                                    <i class="fas fa-<?php echo $status == 'danger' ? 'exclamation-triangle' : ($status == 'warning' ? 'exclamation-circle' : 'check-circle'); ?>"></i>
                                    <?php echo $status_text; ?>
                                </span>
                            </td>
                            <td><?php echo date('M d, Y H:i', strtotime($vital['vit_daterec'])); ?></td>
                            <td>
                                <a href="view_vitals.php?id=<?php echo $vital['vit_id']; ?>" class="btn btn-sm btn-primary" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="edit_vitals.php?id=<?php echo $vital['vit_id']; ?>" class="btn btn-sm btn-success" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="delete_vitals.php?id=<?php echo $vital['vit_id']; ?>" 
                                   class="btn btn-sm btn-danger" 
                                   onclick="return confirm('Are you sure you want to delete this vital record?');"
                                   title="Delete">
                                    <i class="fas fa-trash"></i>
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
</div>

<?php require_once '../includes/footer.php'; ?>