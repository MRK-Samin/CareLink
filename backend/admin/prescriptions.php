<?php
$page_title = "Prescriptions";
require_once '../includes/header.php';
require_once '../includes/navbar.php';

$prescriptions = mysqli_query($conn, "SELECT * FROM his_prescriptions ORDER BY pres_date DESC");

if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    $prescriptions = mysqli_query($conn, "SELECT * FROM his_prescriptions WHERE 
        pres_pat_name LIKE '%$search%' OR 
        pres_pat_number LIKE '%$search%' OR 
        pres_number LIKE '%$search%' OR 
        pres_pat_ailment LIKE '%$search%'
        ORDER BY pres_date DESC");
}
?>

<div class="container">
    <div class="page-header">
        <div>
            <h1><i class="fas fa-prescription"></i> Prescriptions Management</h1>
            <p>Manage patient prescriptions and medical orders</p>
        </div>
        <a href="add_prescription.php" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Prescription
        </a>
    </div>

    <div class="search-bar">
        <form method="GET" action="">
            <input type="text" name="search" placeholder="Search by patient name, number, prescription number..." 
                   value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
            <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Search</button>
            <?php if(isset($_GET['search'])): ?>
                <a href="prescriptions.php" class="btn btn-secondary"><i class="fas fa-times"></i> Clear</a>
            <?php endif; ?>
        </form>
    </div>
  
    <div class="content-card">
        <div class="card-header">
            <h2><i class="fas fa-list"></i> All Prescriptions (<?php echo mysqli_num_rows($prescriptions); ?>)</h2>
        </div>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Prescription #</th>
                        <th>Patient Name</th>
                        <th>Patient #</th>
                        <th>Age</th>
                        <th>Ailment</th>
                        <th>Patient Type</th>
                        <th>Date Issued</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(mysqli_num_rows($prescriptions) > 0): ?>
                        <?php while ($pres = mysqli_fetch_assoc($prescriptions)): ?>
                        <tr>
                            <td><strong><?php echo $pres['pres_number']; ?></strong></td>
                            <td><?php echo $pres['pres_pat_name']; ?></td>
                            <td><?php echo $pres['pres_pat_number']; ?></td>
                            <td><?php echo $pres['pres_pat_age']; ?> years</td>
                            <td><?php echo $pres['pres_pat_ailment']; ?></td>
                            <td>
                                <span class="badge badge-<?php echo $pres['pres_pat_type'] == 'InPatient' ? 'danger' : 'success'; ?>">
                                    <?php echo $pres['pres_pat_type']; ?>
                                </span>
                            </td>
                            <td><?php echo date('M d, Y', strtotime($pres['pres_date'])); ?></td>
                            <td>
                                <a href="view_prescription.php?id=<?php echo $pres['pres_id']; ?>" class="btn btn-sm btn-primary" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="print_prescription.php?id=<?php echo $pres['pres_id']; ?>" class="btn btn-sm btn-info" title="Print" target="_blank">
                                    <i class="fas fa-print"></i>
                                </a>
                                <a href="edit_prescription.php?id=<?php echo $pres['pres_id']; ?>" class="btn btn-sm btn-success" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="delete_prescription.php?id=<?php echo $pres['pres_id']; ?>" 
                                   class="btn btn-sm btn-danger" 
                                   onclick="return confirm('Are you sure you want to delete this prescription?');"
                                   title="Delete">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 40px; color: #999;">
                                <i class="fas fa-prescription" style="font-size: 48px; margin-bottom: 15px; display: block;"></i>
                                No prescriptions found. <?php if(isset($_GET['search'])): ?>Try a different search.<?php endif; ?>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>