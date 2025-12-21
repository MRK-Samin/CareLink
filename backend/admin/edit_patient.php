<?php
$page_title = "Edit Patient";
require_once '../includes/header.php';
require_once '../includes/navbar.php';

if (!isset($_GET['id'])) {
    header("Location: patients.php");
    exit();
}

$pat_id = mysqli_real_escape_string($conn, $_GET['id']);
$patient_query = mysqli_query($conn, "SELECT * FROM his_patients WHERE pat_id = '$pat_id'");

if (mysqli_num_rows($patient_query) == 0) {
    header("Location: patients.php");
    exit();
}

$patient = mysqli_fetch_assoc($patient_query);

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fname = mysqli_real_escape_string($conn, $_POST['fname']);
    $lname = mysqli_real_escape_string($conn, $_POST['lname']);
    $dob = mysqli_real_escape_string($conn, $_POST['dob']);
    $age = mysqli_real_escape_string($conn, $_POST['age']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $pat_type = mysqli_real_escape_string($conn, $_POST['pat_type']);
    $ailment = mysqli_real_escape_string($conn, $_POST['ailment']);
    
    $query = "UPDATE his_patients SET 
              pat_fname = '$fname',
              pat_lname = '$lname',
              pat_dob = '$dob',
              pat_age = '$age',
              pat_phone = '$phone',
              pat_addr = '$address',
              pat_type = '$pat_type',
              pat_ailment = '$ailment'
              WHERE pat_id = '$pat_id'";
    
    if (mysqli_query($conn, $query)) {
        $success = "Patient information updated successfully!";
        // Refresh patient data
        $patient_query = mysqli_query($conn, "SELECT * FROM his_patients WHERE pat_id = '$pat_id'");
        $patient = mysqli_fetch_assoc($patient_query);
    } else {
        $error = "Error: " . mysqli_error($conn);
    }
}
?>

<div class="container">
    <div class="page-header">
        <h1><i class="fas fa-user-edit"></i> Edit Patient Information</h1>
        <a href="view_patient.php?id=<?php echo $patient['pat_id']; ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Patient
        </a>
    </div>
    
    <?php if ($success): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            <?php echo $success; ?>
        </div>
    <?php endif; ?>
    
    <?php if ($error): ?>
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i>
            <?php echo $error; ?>
        </div>
    <?php endif; ?>
    
    <div class="content-card">
        <div class="card-header">
            <h2><i class="fas fa-edit"></i> Patient Details</h2>
            <span class="badge badge-info">Patient #: <?php echo $patient['pat_number']; ?></span>
        </div>
        <form method="POST" action="" class="form-horizontal">
            <div class="form-row">
                <div class="form-group">
                    <label><i class="fas fa-user"></i> First Name *</label>
                    <input type="text" name="fname" required class="form-control" 
                           value="<?php echo htmlspecialchars($patient['pat_fname']); ?>">
                </div>
                
                <div class="form-group">
                    <label><i class="fas fa-user"></i> Last Name *</label>
                    <input type="text" name="lname" required class="form-control"
                           value="<?php echo htmlspecialchars($patient['pat_lname']); ?>">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label><i class="fas fa-birthday-cake"></i> Date of Birth *</label>
                    <input type="text" name="dob" required class="form-control" 
                           placeholder="DD/MM/YYYY"
                           value="<?php echo htmlspecialchars($patient['pat_dob']); ?>">
                </div>
                
                <div class="form-group">
                    <label><i class="fas fa-hashtag"></i> Age *</label>
                    <input type="number" name="age" required class="form-control"
                           value="<?php echo htmlspecialchars($patient['pat_age']); ?>">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label><i class="fas fa-phone"></i> Phone Number *</label>
                    <input type="tel" name="phone" required class="form-control"
                           value="<?php echo htmlspecialchars($patient['pat_phone']); ?>">
                </div>
                
                <div class="form-group">
                    <label><i class="fas fa-hospital-user"></i> Patient Type *</label>
                    <select name="pat_type" required class="form-control">
                        <option value="InPatient" <?php echo $patient['pat_type'] == 'InPatient' ? 'selected' : ''; ?>>InPatient</option>
                        <option value="OutPatient" <?php echo $patient['pat_type'] == 'OutPatient' ? 'selected' : ''; ?>>OutPatient</option>
                    </select>
                </div>
            </div>
            
            <div class="form-group">
                <label><i class="fas fa-map-marker-alt"></i> Address *</label>
                <textarea name="address" required class="form-control" rows="3"><?php echo htmlspecialchars($patient['pat_addr']); ?></textarea>
            </div>
            
            <div class="form-group">
                <label><i class="fas fa-stethoscope"></i> Current Ailment/Diagnosis *</label>
                <input type="text" name="ailment" required class="form-control"
                       value="<?php echo htmlspecialchars($patient['pat_ailment']); ?>">
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update Patient Information
                </button>
                <a href="view_patient.php?id=<?php echo $patient['pat_id']; ?>" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>