<?php

$page_title = "Schedule Surgery";
require_once '../includes/header.php';
require_once '../includes/navbar.php';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $s_number = 'SUR' . strtoupper(substr(md5(uniqid(rand(), true)), 0, 3));
    
    $pat_number = mysqli_real_escape_string($conn, $_POST['pat_number']);
    $pat_name = mysqli_real_escape_string($conn, $_POST['pat_name']);
    $s_doc = mysqli_real_escape_string($conn, $_POST['s_doc']);
    $s_ailment = mysqli_real_escape_string($conn, $_POST['s_ailment']);
    $s_date = mysqli_real_escape_string($conn, $_POST['s_date']);
    $s_time = mysqli_real_escape_string($conn, $_POST['s_time']);
    $s_datetime = $s_date . ' ' . $s_time;
    
    $query = "INSERT INTO his_surgery (s_number, s_doc, s_pat_number, s_pat_name, s_pat_ailment, s_pat_date) 
              VALUES ('$s_number', '$s_doc', '$pat_number', '$pat_name', '$s_ailment', '$s_datetime')";
    
    if (mysqli_query($conn, $query)) {
        $success = "Surgery scheduled successfully! Surgery Number: $s_number";
    } else {
        $error = "Error: " . mysqli_error($conn);
    }
}

$patients = mysqli_query($conn, "SELECT * FROM his_patients ORDER BY pat_fname ASC");
$doctors = mysqli_query($conn, "SELECT * FROM his_docs WHERE doc_dept LIKE '%Surgery%' OR doc_dept LIKE '%Orthopedic%' ORDER BY doc_fname ASC");
?>

<div class="container">
    <div class="page-header">
        <h1><i class="fas fa-syringe"></i> Schedule New Surgery</h1>
        <a href="surgery.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Surgery
        </a>
    </div>
    
    <?php if ($success): ?>
        <div class="alert alert-success"><i class="fas fa-check-circle"></i> <?php echo $success; ?></div>
    <?php endif; ?>
    
    <?php if ($error): ?>
        <div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> <?php echo $error; ?></div>
    <?php endif; ?>
    
    <div class="content-card">
        <div class="card-header">
            <h2><i class="fas fa-calendar-alt"></i> Surgery Details</h2>
        </div>
        <form method="POST" action="" class="form-horizontal">
            <div class="form-group">
                <label><i class="fas fa-user"></i> Select Patient *</label>
                <select name="pat_number" id="patientSelect" required class="form-control">
                    <option value="">-- Select Patient --</option>
                    <?php while ($patient = mysqli_fetch_assoc($patients)): ?>
                        <option value="<?php echo $patient['pat_number']; ?>"
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
                    <input type="text" name="pat_name" id="patName" required class="form-control" readonly>
                </div>
                
                <div class="form-group">
                    <label><i class="fas fa-user-md"></i> Surgeon *</label>
                    <select name="s_doc" required class="form-control">
                        <option value="">-- Select Surgeon --</option>
                        <?php while ($doctor = mysqli_fetch_assoc($doctors)): ?>
                            <option value="Dr. <?php echo $doctor['doc_fname'] . ' ' . $doctor['doc_lname']; ?>">
                                Dr. <?php echo $doctor['doc_fname'] . ' ' . $doctor['doc_lname']; ?> (<?php echo $doctor['doc_dept']; ?>)
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
            </div>
            
            <div class="form-group">
                <label><i class="fas fa-stethoscope"></i> Procedure/Surgery Type *</label>
                <input type="text" name="s_ailment" id="patAilment" required class="form-control" 
                       placeholder="Enter surgery procedure">
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label><i class="fas fa-calendar"></i> Surgery Date *</label>
                    <input type="date" name="s_date" required class="form-control" min="<?php echo date('Y-m-d'); ?>">
                </div>
                
                <div class="form-group">
                    <label><i class="fas fa-clock"></i> Surgery Time *</label>
                    <input type="time" name="s_time" required class="form-control">
                </div>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Schedule Surgery
                </button>
                <a href="surgery.php" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<script>
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