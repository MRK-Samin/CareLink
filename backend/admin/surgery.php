<?php
$page_title = "Surgery Management";
require_once '../includes/header.php';
require_once '../includes/navbar.php';

$surgeries = mysqli_query($conn, "SELECT s.*, p.pat_fname, p.pat_lname, p.pat_phone, p.pat_type 
                                   FROM his_surgery s 
                                   LEFT JOIN his_patients p ON s.s_pat_number = p.pat_number 
                                   ORDER BY s.s_pat_date DESC");

if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    $surgeries = mysqli_query($conn, "SELECT s.*, p.pat_fname, p.pat_lname, p.pat_phone, p.pat_type 
                                       FROM his_surgery s 
                                       LEFT JOIN his_patients p ON s.s_pat_number = p.pat_number 
                                       WHERE s.s_number LIKE '%$search%' OR 
                                       s.s_pat_name LIKE '%$search%' OR 
                                       s.s_pat_number LIKE '%$search%' OR 
                                       s.s_doc LIKE '%$search%' OR
                                       s.s_pat_ailment LIKE '%$search%'
                                       ORDER BY s.s_pat_date DESC");
}

// Get surgery statistics
$total = mysqli_num_rows($surgeries);
$successful = mysqli_query($conn, "SELECT COUNT(*) as count FROM his_surgery WHERE s_pat_status = 'Successful'");
$successful_count = mysqli_fetch_assoc($successful)['count'];
$pending = mysqli_query($conn, "SELECT COUNT(*) as count FROM his_surgery WHERE s_pat_status IS NULL OR s_pat_status = ''");
$pending_count = mysqli_fetch_assoc($pending)['count'];
?>

<div class="container">
    <div class="page-header">
        <div>
            <h1><i class="fas fa-syringe"></i> Surgery Management</h1>
            <p>Manage surgical procedures and operation theater records</p>
        </div>
        <a href="add_surgery.php" class="btn btn-primary">
            <i class="fas fa-plus"></i> Schedule New Surgery
        </a>
    </div>

    <!-- Surgery Statistics -->
    <div class="stats-grid" style="margin-bottom: 30px;">
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #667eea, #764ba2);">
                <i class="fas fa-syringe"></i>
            </div>
            <div class="stat-value"><?php echo $total; ?></div>
            <div class="stat-label">Total Surgeries</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #43e97b, #38f9d7);">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-value"><?php echo $successful_count; ?></div>
            <div class="stat-label">Successful</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #fa709a, #fee140);">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-value"><?php echo $pending_count; ?></div>
            <div class="stat-label">Pending/Scheduled</div>
        </div>
    </div>

    <div class="search-bar">
        <form method="GET" action="">
            <input type="text" name="search" placeholder="Search by surgery number, patient name, doctor, ailment..." 
                   value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
            <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Search</button>
            <?php if(isset($_GET['search'])): ?>
                <a href="surgery.php" class="btn btn-secondary"><i class="fas fa-times"></i> Clear</a>
            <?php endif; ?>
        </form>
    </div>
  
    <div class="content-card">
        <div class="card-header">
            <h2><i class="fas fa-list"></i> All Surgeries (<?php echo mysqli_num_rows($surgeries); ?>)</h2>
        </div>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Surgery #</th>
                        <th>Patient Name</th>
                        <th>Patient #</th>
                        <th>Doctor/Surgeon</th>
                        <th>Ailment/Procedure</th>
                        <th>Status</th>
                        <th>Date/Time</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(mysqli_num_rows($surgeries) > 0): ?>
                        <?php while ($surgery = mysqli_fetch_assoc($surgeries)): 
                            $status = $surgery['s_pat_status'];
                            $badge_class = 'secondary';
                            $icon = 'clock';
                            
                            if($status == 'Successful') {
                                $badge_class = 'success';
                                $icon = 'check-circle';
                            } elseif($status == 'Failed' || $status == 'Cancelled') {
                                $badge_class = 'danger';
                                $icon = 'times-circle';
                            } elseif($status == 'In Progress') {
                                $badge_class = 'warning';
                                $icon = 'spinner';
                            }
                        ?>
                        <tr>
                            <td><strong><?php echo $surgery['s_number']; ?></strong></td>
                            <td><?php echo $surgery['s_pat_name']; ?></td>
                            <td><?php echo $surgery['s_pat_number']; ?></td>
                            <td><?php echo $surgery['s_doc']; ?></td>
                            <td><?php echo $surgery['s_pat_ailment']; ?></td>
                            <td>
                                <span class="badge badge-<?php echo $badge_class; ?>">
                                    <i class="fas fa-<?php echo $icon; ?>"></i>
                                    <?php echo $status ? $status : 'Scheduled'; ?>
                                </span>
                            </td>
                            <td><?php echo date('M d, Y H:i', strtotime($surgery['s_pat_date'])); ?></td>
                            <td>
                                <a href="view_surgery.php?id=<?php echo $surgery['s_id']; ?>" class="btn btn-sm btn-primary" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="edit_surgery.php?id=<?php echo $surgery['s_id']; ?>" class="btn btn-sm btn-success" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="print_surgery_report.php?id=<?php echo $surgery['s_id']; ?>" class="btn btn-sm btn-info" title="Print Report" target="_blank">
                                    <i class="fas fa-print"></i>
                                </a>
                                <a href="delete_surgery.php?id=<?php echo $surgery['s_id']; ?>" 
                                   class="btn btn-sm btn-danger" 
                                   onclick="return confirm('Are you sure you want to delete this surgery record?');"
                                   title="Delete">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 40px; color: #999;">
                                <i class="fas fa-syringe" style="font-size: 48px; margin-bottom: 15px; display: block;"></i>
                                No surgery records found. <?php if(isset($_GET['search'])): ?>Try a different search.<?php endif; ?>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>