<?php
$page_title = "Patients";
require_once '../includes/header.php';
require_once '../includes/navbar.php';

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
?>

<div class="container">
    <div class="page-header">
        <h1><i class="fas fa-user-injured"></i> Patient Management</h1>
        <a href="add_patient.php" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Patient
        </a>
    </div>

    <div class="search-bar">
        <form method="GET" action="">
            <input type="text" name="search" placeholder="Search patients by name, number, phone..." 
                   value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
            <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Search</button>
        </form>
    </div>
  
    <div class="content-card">
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Patient #</th>
                        <th>Name</th>
                        <th>DOB</th>
                        <th>Age</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th>Ailment</th>
                        <th>Type</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($patient = mysqli_fetch_assoc($patients)): ?>
                    <tr>
                        <td><strong><?php echo $patient['pat_number']; ?></strong></td>
                        <td><?php echo $patient['pat_fname'] . ' ' . $patient['pat_lname']; ?></td>
                        <td><?php echo $patient['pat_dob']; ?></td>
                        <td><?php echo $patient['pat_age']; ?> years</td>
                        <td><?php echo $patient['pat_phone']; ?></td>
                        <td><?php echo substr($patient['pat_addr'], 0, 30) . '...'; ?></td>
                        <td><?php echo $patient['pat_ailment']; ?></td>
                        <td>
                            <span class="badge badge-<?php echo $patient['pat_type'] == 'InPatient' ? 'info' : 'success'; ?>">
                                <?php echo $patient['pat_type']; ?>
                            </span>
                        </td>
                        <td>
                            <a href="view_patient.php?id=<?php echo $patient['pat_id']; ?>" class="btn btn-sm btn-primary">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="edit_patient.php?id=<?php echo $patient['pat_id']; ?>" class="btn btn-sm btn-success">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="delete_patient.php?id=<?php echo $patient['pat_id']; ?>" 
                               class="btn btn-sm btn-danger" 
                               onclick="return confirm('Are you sure you want to delete this patient?');">
                                <i class="fas fa-trash"></i>
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