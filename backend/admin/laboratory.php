<?php
$page_title = "Laboratory";
require_once '../includes/header.php';
require_once '../includes/navbar.php';

$lab_tests = mysqli_query($conn, "SELECT * FROM his_laboratory ORDER BY lab_date_rec DESC");

if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    $lab_tests = mysqli_query($conn, "SELECT * FROM his_laboratory WHERE 
        lab_pat_name LIKE '%$search%' OR 
        lab_pat_number LIKE '%$search%' OR 
        lab_number LIKE '%$search%' OR 
        lab_pat_ailment LIKE '%$search%'
        ORDER BY lab_date_rec DESC");
}
?>

<div class="container">
    <div class="page-header">
        <div>
            <h1><i class="fas fa-flask"></i> Laboratory Management</h1>
            <p>Manage patient lab tests and results</p>
        </div>
        <a href="add_lab_test.php" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Lab Test
        </a>
    </div>

    <div class="search-bar">
        <form method="GET" action="">
            <input type="text" name="search" placeholder="Search by patient name, number, lab number..." 
                   value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
            <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Search</button>
            <?php if(isset($_GET['search'])): ?>
                <a href="laboratory.php" class="btn btn-secondary"><i class="fas fa-times"></i> Clear</a>
            <?php endif; ?>
        </form>
    </div>
  
    <div class="content-card">
        <div class="card-header">
            <h2><i class="fas fa-list"></i> All Lab Tests (<?php echo mysqli_num_rows($lab_tests); ?>)</h2>
        </div>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Lab #</th>
                        <th>Patient Name</th>
                        <th>Patient #</th>
                        <th>Ailment</th>
                        <th>Tests Ordered</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(mysqli_num_rows($lab_tests) > 0): ?>
                        <?php while ($lab = mysqli_fetch_assoc($lab_tests)): ?>
                        <tr>
                            <td><strong><?php echo $lab['lab_number']; ?></strong></td>
                            <td><?php echo $lab['lab_pat_name']; ?></td>
                            <td><?php echo $lab['lab_pat_number']; ?></td>
                            <td><?php echo $lab['lab_pat_ailment']; ?></td>
                            <td>
                                <div style="max-width: 250px; overflow: hidden; text-overflow: ellipsis;">
                                    <?php echo strip_tags(substr($lab['lab_pat_tests'], 0, 100)); ?>...
                                </div>
                            </td>
                            <td>
                                <?php if(!empty($lab['lab_pat_results'])): ?>
                                    <span class="badge badge-success"><i class="fas fa-check-circle"></i> Completed</span>
                                <?php else: ?>
                                    <span class="badge badge-warning"><i class="fas fa-clock"></i> Pending</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo date('M d, Y', strtotime($lab['lab_date_rec'])); ?></td>
                            <td>
                                <a href="view_lab_test.php?id=<?php echo $lab['lab_id']; ?>" class="btn btn-sm btn-primary" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="edit_lab_test.php?id=<?php echo $lab['lab_id']; ?>" class="btn btn-sm btn-success" title="Edit/Add Results">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="delete_lab_test.php?id=<?php echo $lab['lab_id']; ?>" 
                                   class="btn btn-sm btn-danger" 
                                   onclick="return confirm('Are you sure you want to delete this lab test?');"
                                   title="Delete">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 40px; color: #999;">
                                <i class="fas fa-flask" style="font-size: 48px; margin-bottom: 15px; display: block;"></i>
                                No lab tests found. <?php if(isset($_GET['search'])): ?>Try a different search.<?php endif; ?>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>