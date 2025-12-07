<?php
$page_title = "Doctors";
require_once '../includes/header.php';
require_once '../includes/navbar.php';

$doctors = mysqli_query($conn, "SELECT * FROM his_docs ORDER BY doc_id DESC");

if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    $doctors = mysqli_query($conn, "SELECT * FROM his_docs WHERE 
        doc_fname LIKE '%$search%' OR 
        doc_lname LIKE '%$search%' OR 
        doc_number LIKE '%$search%' OR 
        doc_email LIKE '%$search%' OR 
        doc_dept LIKE '%$search%'
        ORDER BY doc_id DESC");
}
?>

<div class="container">
    <div class="page-header">
        <div>
            <h1><i class="fas fa-user-md"></i> Doctors Management</h1>
            <p>Manage hospital medical staff and doctors</p>
        </div>
        <a href="add_doctor.php" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Doctor
        </a>
    </div>

    <div class="search-bar">
        <form method="GET" action="">
            <input type="text" name="search" placeholder="Search doctors by name, number, email, department..." 
                   value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
            <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Search</button>
            <?php if(isset($_GET['search'])): ?>
                <a href="doctors.php" class="btn btn-secondary"><i class="fas fa-times"></i> Clear</a>
            <?php endif; ?>
        </form>
    </div>
  
    <div class="content-card">
        <div class="card-header">
            <h2><i class="fas fa-list"></i> All Doctors (<?php echo mysqli_num_rows($doctors); ?>)</h2>
        </div>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Doctor #</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Department</th>
                        <th>Photo</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(mysqli_num_rows($doctors) > 0): ?>
                        <?php while ($doctor = mysqli_fetch_assoc($doctors)): ?>
                        <tr>
                            <td><strong><?php echo $doctor['doc_number']; ?></strong></td>
                            <td><?php echo 'Dr. ' . $doctor['doc_fname'] . ' ' . $doctor['doc_lname']; ?></td>
                            <td><?php echo $doctor['doc_email']; ?></td>
                            <td><span class="badge badge-info"><?php echo $doctor['doc_dept']; ?></span></td>
                            <td>
                                <?php if($doctor['doc_dpic']): ?>
                                    <img src="../../assets/images/doctors/<?php echo $doctor['doc_dpic']; ?>" 
                                         alt="Doctor" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">
                                <?php else: ?>
                                    <i class="fas fa-user-circle" style="font-size: 40px; color: #667eea;"></i>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="view_doctor.php?id=<?php echo $doctor['doc_id']; ?>" class="btn btn-sm btn-primary" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="edit_doctor.php?id=<?php echo $doctor['doc_id']; ?>" class="btn btn-sm btn-success" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="delete_doctor.php?id=<?php echo $doctor['doc_id']; ?>" 
                                   class="btn btn-sm btn-danger" 
                                   onclick="return confirm('Are you sure you want to delete this doctor?');"
                                   title="Delete">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 40px; color: #999;">
                                <i class="fas fa-user-md" style="font-size: 48px; margin-bottom: 15px; display: block;"></i>
                                No doctors found. <?php if(isset($_GET['search'])): ?>Try a different search.<?php endif; ?>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>