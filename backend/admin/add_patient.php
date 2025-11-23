<?php
$page_title = "Add Patient";
require_once '../includes/header.php';
require_once '../includes/navbar.php';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pat_number = strtoupper(substr(md5(uniqid(rand(), true)), 0, 5));
    
    $fname = mysqli_real_escape_string($conn, $_POST['fname']);
    $lname = mysqli_real_escape_string($conn, $_POST['lname']);
    $dob = mysqli_real_escape_string($conn, $_POST['dob']);
    $age = mysqli_real_escape_string($conn, $_POST['age']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $pat_type = mysqli_real_escape_string($conn, $_POST['pat_type']);
    $ailment = mysqli_real_escape_string($conn, $_POST['ailment']);
    
    $query = "INSERT INTO his_patients (pat_fname, pat_lname, pat_dob, pat_age, pat_number, pat_addr, pat_phone, pat_type, pat_ailment) 
              VALUES ('$fname', '$lname', '$dob', '$age', '$pat_number', '$address', '$phone', '$pat_type', '$ailment')";
    
    if (mysqli_query($conn, $query)) {
        $success = "Patient added successfully! Patient Number: $pat_number";
    } else {
        $error = "Error: " . mysqli_error($conn);
    }
}
?>

<div class="container">
    <div class="page-header">
        <h1><i class="fas fa-user-plus"></i> Add New Patient</h1>
        <a href="patients.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Patients
        </a>
    </div>
    
    <?php if ($success): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>
    
    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <div class="content-card">
        <form method="POST" action="" class="form-horizontal">
            <div class="form-row">
                <div class="form-group">
                    <label>First Name *</label>
                    <input type="text" name="fname" required class="form-control">
                </div>
                
                <div class="form-group">
                    <label>Last Name *</label>
                    <input type="text" name="lname" required class="form-control">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Date of Birth *</label>
                    <input type="text" name="dob" required class="form-control" placeholder="MM/DD/YYYY">
                </div>
                
                <div class="form-group">
                    <label>Age *</label>
                    <input type="number" name="age" required class="form-control">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Phone Number *</label>
                    <input type="tel" name="phone" required class="form-control">
                </div>
                
                <div class="form-group">
                    <label>Patient Type *</label>
                    <select name="pat_type" required class="form-control">
                        <option value="">Select Type</option>
                        <option value="InPatient">InPatient</option>
                        <option value="OutPatient">OutPatient</option>
                    </select>
                </div>
            </div>
            
            <div class="form-group">
                <label>Address *</label>
                <textarea name="address" required class="form-control" rows="3"></textarea>
            </div>
            
            <div class="form-group">
                <label>Ailment/Diagnosis *</label>
                <input type="text" name="ailment" required class="form-control">
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Save Patient
                </button>
                <a href="patients.php" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>