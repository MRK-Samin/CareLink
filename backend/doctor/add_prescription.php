<?php
$page_title = "Add Prescription";
require_once '../includes/doctor_header.php';
require_once '../includes/doctor_navbar.php';

$success = '';
$error = '';

// Get patient if passed via URL
$selected_patient = null;
if(isset($_GET['pat'])) {
    $pat_number = mysqli_real_escape_string($conn, $_GET['pat']);
    $result = mysqli_query($conn, "SELECT * FROM his_patients WHERE pat_number = '$pat_number'");
    if(mysqli_num_rows($result) > 0) {
        $selected_patient = mysqli_fetch_assoc($result);
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pres_number = 'PRE' . strtoupper(substr(md5(uniqid(rand(), true)), 0, 3));
    
    $pat_number = mysqli_real_escape_string($conn, $_POST['pat_number']);
    $pat_name = mysqli_real_escape_string($conn, $_POST['pat_name']);
    $pat_age = mysqli_real_escape_string($conn, $_POST['pat_age']);
    $pat_addr = mysqli_real_escape_string($conn, $_POST['pat_addr']);
    $pat_type = mysqli_real_escape_string($conn, $_POST['pat_type']);
    $pat_ailment = mysqli_real_escape_string($conn, $_POST['pat_ailment']);
    $pres_ins = mysqli_real_escape_string($conn, $_POST['pres_ins']);
    
    $query = "INSERT INTO his_prescriptions (pres_number, pres_pat_name, pres_pat_age, pres_pat_number, pres_pat_addr, pres_pat_type, pres_pat_ailment, pres_ins) 
              VALUES ('$pres_number', '$pat_name', '$pat_age', '$pat_number', '$pat_addr', '$pat_type', '$pat_ailment', '$pres_ins')";
    
    if (mysqli_query($conn, $query)) {
        $success = "Prescription added successfully! Prescription Number: $pres_number";
    } else {
        $error = "Error: " . mysqli_error($conn);
    }
}

// Get all patients for dropdown
$patients = mysqli_query($conn, "SELECT * FROM his_patients ORDER BY pat_fname ASC");
?>

<div class="container">
    <div class="page-header">
        <h1><i class="fas fa-prescription"></i> Write Prescription</h1>
        <a href="my_patients.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Patients
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
            <h2><i class="fas fa-file-medical"></i> Prescription Details</h2>
        </div>
        <form method="POST" action="" class="form-horizontal">
            <div class="form-row">
                <div class="form-group">
                    <label><i class="fas fa-user"></i> Select Patient *</label>
                    <select name="pat_number" id="patientSelect" required class="form-control">
                        <option value="">-- Select Patient --</option>
                        <?php while ($patient = mysqli_fetch_assoc($patients)): ?>
                            <option value="<?php echo $patient['pat_number']; ?>" 
                                    <?php echo ($selected_patient && $selected_patient['pat_number'] == $patient['pat_number']) ? 'selected' : ''; ?>
                                    data-name="<?php echo $patient['pat_fname'] . ' ' . $patient['pat_lname']; ?>"
                                    data-age="<?php echo $patient['pat_age']; ?>"
                                    data-addr="<?php echo $patient['pat_addr']; ?>"
                                    data-type="<?php echo $patient['pat_type']; ?>"
                                    data-ailment="<?php echo $patient['pat_ailment']; ?>">
                                <?php echo $patient['pat_number'] . ' - ' . $patient['pat_fname'] . ' ' . $patient['pat_lname']; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label><i class="fas fa-id-card"></i> Patient Name *</label>
                    <input type="text" name="pat_name" id="patName" required class="form-control" 
                           value="<?php echo $selected_patient ? $selected_patient['pat_fname'] . ' ' . $selected_patient['pat_lname'] : ''; ?>" readonly>
                </div>
                
                <div class="form-group">
                    <label><i class="fas fa-birthday-cake"></i> Age *</label>
                    <input type="text" name="pat_age" id="patAge" required class="form-control" 
                           value="<?php echo $selected_patient ? $selected_patient['pat_age'] : ''; ?>" readonly>
                </div>
            </div>
            
            <div class="form-group">
                <label><i class="fas fa-map-marker-alt"></i> Address *</label>
                <textarea name="pat_addr" id="patAddr" required class="form-control" rows="2" readonly><?php echo $selected_patient ? $selected_patient['pat_addr'] : ''; ?></textarea>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label><i class="fas fa-hospital-user"></i> Patient Type *</label>
                    <input type="text" name="pat_type" id="patType" required class="form-control" 
                           value="<?php echo $selected_patient ? $selected_patient['pat_type'] : ''; ?>" readonly>
                </div>
                
                <div class="form-group">
                    <label><i class="fas fa-stethoscope"></i> Ailment *</label>
                    <input type="text" name="pat_ailment" id="patAilment" required class="form-control" 
                           value="<?php echo $selected_patient ? $selected_patient['pat_ailment'] : ''; ?>" readonly>
                </div>
            </div>
            
            <div class="form-group">
                <label><i class="fas fa-pills"></i> Prescription Instructions *</label>
                <textarea name="pres_ins" required class="form-control" rows="8" 
                          placeholder="Enter prescription instructions, medications, dosage, etc."></textarea>
                <small style="color: #666; margin-top: 5px; display: block;">
                    <i class="fas fa-info-circle"></i> You can use HTML formatting for better presentation
                </small>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Save Prescription
                </button>
                <a href="my_patients.php" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<script>
// Auto-fill patient details when patient is selected
document.getElementById('patientSelect').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    
    if(selectedOption.value) {
        document.getElementById('patName').value = selectedOption.getAttribute('data-name');
        document.getElementById('patAge').value = selectedOption.getAttribute('data-age');
        document.getElementById('patAddr').value = selectedOption.getAttribute('data-addr');
        document.getElementById('patType').value = selectedOption.getAttribute('data-type');
        document.getElementById('patAilment').value = selectedOption.getAttribute('data-ailment');
    } else {
        document.getElementById('patName').value = '';
        document.getElementById('patAge').value = '';
        document.getElementById('patAddr').value = '';
        document.getElementById('patType').value = '';
        document.getElementById('patAilment').value = '';
    }
});
</script>

<?php require_once '../includes/footer.php'; ?>