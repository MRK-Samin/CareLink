<?php

$page_title = "Record Vitals";
require_once '../includes/header.php';
require_once '../includes/navbar.php';

$success = '';
$error = '';

$selected_patient = null;
if(isset($_GET['pat'])) {
    $pat_number = mysqli_real_escape_string($conn, $_GET['pat']);
    $result = mysqli_query($conn, "SELECT * FROM his_patients WHERE pat_number = '$pat_number'");
    if(mysqli_num_rows($result) > 0) {
        $selected_patient = mysqli_fetch_assoc($result);
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $vit_number = 'VIT' . strtoupper(substr(md5(uniqid(rand(), true)), 0, 3));
    
    $pat_number = mysqli_real_escape_string($conn, $_POST['pat_number']);
    $body_temp = mysqli_real_escape_string($conn, $_POST['body_temp']);
    $heart_pulse = mysqli_real_escape_string($conn, $_POST['heart_pulse']);
    $resp_rate = mysqli_real_escape_string($conn, $_POST['resp_rate']);
    $blood_pressure = mysqli_real_escape_string($conn, $_POST['blood_pressure']);
    
    $query = "INSERT INTO his_vitals (vit_number, vit_pat_number, vit_bodytemp, vit_heartpulse, vit_resprate, vit_bloodpress) 
              VALUES ('$vit_number', '$pat_number', '$body_temp', '$heart_pulse', '$resp_rate', '$blood_pressure')";
    
    if (mysqli_query($conn, $query)) {
        $success = "Vitals recorded successfully! Vital Number: $vit_number";
    } else {
        $error = "Error: " . mysqli_error($conn);
    }
}

$patients = mysqli_query($conn, "SELECT * FROM his_patients ORDER BY pat_fname ASC");
?>

<div class="container">
    <div class="page-header">
        <h1><i class="fas fa-heartbeat"></i> Record Patient Vitals</h1>
        <a href="vitals.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Vitals
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
            <h2><i class="fas fa-clipboard-list"></i> Vital Signs Measurement</h2>
        </div>
        <form method="POST" action="" class="form-horizontal">
            <div class="form-group">
                <label><i class="fas fa-user"></i> Select Patient *</label>
                <select name="pat_number" required class="form-control">
                    <option value="">-- Select Patient --</option>
                    <?php while ($patient = mysqli_fetch_assoc($patients)): ?>
                        <option value="<?php echo $patient['pat_number']; ?>" 
                                <?php echo ($selected_patient && $selected_patient['pat_number'] == $patient['pat_number']) ? 'selected' : ''; ?>>
                            <?php echo $patient['pat_number'] . ' - ' . $patient['pat_fname'] . ' ' . $patient['pat_lname']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label><i class="fas fa-thermometer-half"></i> Body Temperature (°F) *</label>
                    <input type="number" step="0.1" name="body_temp" required class="form-control" 
                           placeholder="e.g., 98.6" min="95" max="110">
                    <small style="color: #666; margin-top: 5px; display: block;">Normal: 97.8°F - 99.1°F</small>
                </div>
                
                <div class="form-group">
                    <label><i class="fas fa-heartbeat"></i> Heart Rate (bpm) *</label>
                    <input type="number" name="heart_pulse" required class="form-control" 
                           placeholder="e.g., 72" min="40" max="200">
                    <small style="color: #666; margin-top: 5px; display: block;">Normal: 60-100 bpm</small>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label><i class="fas fa-lungs"></i> Respiratory Rate (per minute) *</label>
                    <input type="number" name="resp_rate" required class="form-control" 
                           placeholder="e.g., 16" min="10" max="40">
                    <small style="color: #666; margin-top: 5px; display: block;">Normal: 12-20 breaths/min</small>
                </div>
                
                <div class="form-group">
                    <label><i class="fas fa-tint"></i> Blood Pressure (mmHg) *</label>
                    <input type="text" name="blood_pressure" required class="form-control" 
                           placeholder="e.g., 120/80" pattern="[0-9]{2,3}/[0-9]{2,3}">
                    <small style="color: #666; margin-top: 5px; display: block;">Format: Systolic/Diastolic</small>
                </div>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Save Vital Signs
                </button>
                <a href="vitals.php" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>