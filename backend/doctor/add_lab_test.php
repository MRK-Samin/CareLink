<?php
$page_title = "Order Lab Test";
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
    $lab_number = 'LAB' . strtoupper(substr(md5(uniqid(rand(), true)), 0, 3));
    
    $pat_number = mysqli_real_escape_string($conn, $_POST['pat_number']);
    $pat_name = mysqli_real_escape_string($conn, $_POST['pat_name']);
    $pat_ailment = mysqli_real_escape_string($conn, $_POST['pat_ailment']);
    
    // Collect selected tests
    $tests = isset($_POST['tests']) ? $_POST['tests'] : [];
    $test_list = '<ul>';
    foreach($tests as $test) {
        $test_list .= '<li>' . htmlspecialchars($test) . '</li>';
    }
    $test_list .= '</ul>';
    
    $query = "INSERT INTO his_laboratory (lab_number, lab_pat_name, lab_pat_number, lab_pat_ailment, lab_pat_tests) 
              VALUES ('$lab_number', '$pat_name', '$pat_number', '$pat_ailment', '$test_list')";
    
    if (mysqli_query($conn, $query)) {
        $success = "Lab test ordered successfully! Lab Number: $lab_number";
    } else {
        $error = "Error: " . mysqli_error($conn);
    }
}

// Get all patients for dropdown
$patients = mysqli_query($conn, "SELECT * FROM his_patients ORDER BY pat_fname ASC");

// Common lab tests
$common_tests = [
    'Complete Blood Count (CBC)',
    'Blood Sugar (Fasting)',
    'Blood Sugar (Random)',
    'HbA1c',
    'Lipid Profile',
    'Liver Function Test (LFT)',
    'Kidney Function Test (KFT)',
    'Thyroid Function Test',
    'Urine Routine Examination',
    'Dengue NS1 Antigen',
    'Dengue IgM/IgG',
    'Malaria Test',
    'Typhoid Test',
    'X-Ray Chest',
    'ECG',
    'Ultrasound'
];
?>

<div class="container">
    <div class="page-header">
        <h1><i class="fas fa-flask"></i> Order Lab Test</h1>
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
            <h2><i class="fas fa-file-medical"></i> Lab Test Order Form</h2>
        </div>
        <form method="POST" action="" class="form-horizontal">
            <div class="form-group">
                <label><i class="fas fa-user"></i> Select Patient *</label>
                <select name="pat_number" id="patientSelect" required class="form-control">
                    <option value="">-- Select Patient --</option>
                    <?php while ($patient = mysqli_fetch_assoc($patients)): ?>
                        <option value="<?php echo $patient['pat_number']; ?>" 
                                <?php echo ($selected_patient && $selected_patient['pat_number'] == $patient['pat_number']) ? 'selected' : ''; ?>
                                data-name="<?php echo $patient['pat_fname'] . ' ' . $patient['pat_lname']; ?>"
                                data-ailment="<?php echo $patient['pat_ailment']; ?>">
                            <?php echo $patient['pat_number'] . ' - ' . $patient['pat_fname'] . ' ' . $patient['pat_lname']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label><i class="fas fa-id-card"></i> Patient Name *</label>
                    <input type="text" name="pat_name" id="patName" required class="form-control" 
                           value="<?php echo $selected_patient ? $selected_patient['pat_fname'] . ' ' . $selected_patient['pat_lname'] : ''; ?>" readonly>
                </div>
                
                <div class="form-group">
                    <label><i class="fas fa-stethoscope"></i> Ailment *</label>
                    <input type="text" name="pat_ailment" id="patAilment" required class="form-control" 
                           value="<?php echo $selected_patient ? $selected_patient['pat_ailment'] : ''; ?>" readonly>
                </div>
            </div>
            
            <div class="form-group">
                <label><i class="fas fa-vial"></i> Select Tests to Order *</label>
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 10px; 
                            max-height: 400px; overflow-y: auto; padding: 15px; background: #f8f9fa; border-radius: 8px;">
                    <?php foreach($common_tests as $test): ?>
                    <label style="display: flex; align-items: center; gap: 10px; padding: 10px; background: white; 
                                  border-radius: 6px; cursor: pointer; transition: all 0.2s;">
                        <input type="checkbox" name="tests[]" value="<?php echo $test; ?>" 
                               style="width: 18px; height: 18px; cursor: pointer;">
                        <span style="font-size: 14px;"><?php echo $test; ?></span>
                    </label>
                    <?php endforeach; ?>
                </div>
                <small style="color: #666; margin-top: 10px; display: block;">
                    <i class="fas fa-info-circle"></i> Select one or more tests required for this patient
                </small>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Order Lab Tests
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
        document.getElementById('patAilment').value = selectedOption.getAttribute('data-ailment');
    } else {
        document.getElementById('patName').value = '';
        document.getElementById('patAilment').value = '';
    }
});
</script>

<?php require_once '../includes/footer.php'; ?>