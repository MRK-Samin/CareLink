<?php
$page_title = "Record Patient Vitals";
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
    $vit_number = 'VIT' . strtoupper(substr(md5(uniqid(rand(), true)), 0, 3));
    
    $pat_number = mysqli_real_escape_string($conn, $_POST['pat_number']);
    $body_temp = mysqli_real_escape_string($conn, $_POST['body_temp']);
    $heart_pulse = mysqli_real_escape_string($conn, $_POST['heart_pulse']);
    $resp_rate = mysqli_real_escape_string($conn, $_POST['resp_rate']);
    $blood_pressure = mysqli_real_escape_string($conn, $_POST['blood_pressure']);
    
    $query = "INSERT INTO his_vitals (vit_number, vit_pat_number, vit_bodytemp, vit_heartpulse, vit_resprate, vit_bloodpress) 
              VALUES ('$vit_number', '$pat_number', '$body_temp', '$heart_pulse', '$resp_rate', '$blood_pressure')";
    
    if (mysqli_query($conn, $query)) {
        $success = "Patient vitals recorded successfully! Vital Number: $vit_number";
    } else {
        $error = "Error: " . mysqli_error($conn);
    }
}

// Get all patients for dropdown
$patients = mysqli_query($conn, "SELECT * FROM his_patients ORDER BY pat_fname ASC");
?>

<div class="container">
    <div class="page-header">
        <h1><i class="fas fa-heartbeat"></i> Record Patient Vitals</h1>
        <a href="patient_vitals.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Vitals
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
            <h2><i class="fas fa-clipboard-list"></i> Vital Signs Measurement</h2>
        </div>
        <form method="POST" action="" class="form-horizontal">
            <div class="form-group">
                <label><i class="fas fa-user"></i> Select Patient *</label>
                <select name="pat_number" id="patientSelect" required class="form-control">
                    <option value="">-- Select Patient --</option>
                    <?php while ($patient = mysqli_fetch_assoc($patients)): ?>
                        <option value="<?php echo $patient['pat_number']; ?>" 
                                <?php echo ($selected_patient && $selected_patient['pat_number'] == $patient['pat_number']) ? 'selected' : ''; ?>>
                            <?php echo $patient['pat_number'] . ' - ' . $patient['pat_fname'] . ' ' . $patient['pat_lname'] . ' (' . $patient['pat_ailment'] . ')'; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label><i class="fas fa-thermometer-half"></i> Body Temperature (°F) *</label>
                    <input type="number" step="0.1" name="body_temp" required class="form-control" 
                           placeholder="e.g., 98.6" min="95" max="110">
                    <small style="color: #666; margin-top: 5px; display: block;">
                        Normal range: 97.8°F - 99.1°F
                    </small>
                </div>
                
                <div class="form-group">
                    <label><i class="fas fa-heartbeat"></i> Heart Rate (bpm) *</label>
                    <input type="number" name="heart_pulse" required class="form-control" 
                           placeholder="e.g., 72" min="40" max="200">
                    <small style="color: #666; margin-top: 5px; display: block;">
                        Normal range: 60-100 bpm
                    </small>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label><i class="fas fa-lungs"></i> Respiratory Rate (per minute) *</label>
                    <input type="number" name="resp_rate" required class="form-control" 
                           placeholder="e.g., 16" min="10" max="40">
                    <small style="color: #666; margin-top: 5px; display: block;">
                        Normal range: 12-20 breaths/min
                    </small>
                </div>
                
                <div class="form-group">
                    <label><i class="fas fa-tint"></i> Blood Pressure (mmHg) *</label>
                    <input type="text" name="blood_pressure" required class="form-control" 
                           placeholder="e.g., 120/80" pattern="[0-9]{2,3}/[0-9]{2,3}">
                    <small style="color: #666; margin-top: 5px; display: block;">
                        Format: Systolic/Diastolic (e.g., 120/80)
                    </small>
                </div>
            </div>
            
            <!-- Visual Health Indicator -->
            <div class="content-card" style="background: #f8f9fa; padding: 20px; margin-bottom: 20px; border-radius: 12px;">
                <h3 style="margin-bottom: 15px; font-size: 16px; color: #667eea;">
                    <i class="fas fa-chart-line"></i> Vital Signs Reference Guide
                </h3>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
                    <div style="background: white; padding: 15px; border-radius: 8px;">
                        <strong style="color: #667eea;">Body Temperature</strong>
                        <p style="margin: 5px 0; font-size: 13px;">Normal: 97.8 - 99.1°F</p>
                        <p style="margin: 5px 0; font-size: 13px; color: #f5576c;">Fever: >100.4°F</p>
                    </div>
                    <div style="background: white; padding: 15px; border-radius: 8px;">
                        <strong style="color: #f5576c;">Heart Rate</strong>
                        <p style="margin: 5px 0; font-size: 13px;">Normal: 60-100 bpm</p>
                        <p style="margin: 5px 0; font-size: 13px; color: #f5576c;">High: >100 bpm</p>
                    </div>
                    <div style="background: white; padding: 15px; border-radius: 8px;">
                        <strong style="color: #43e97b;">Respiratory Rate</strong>
                        <p style="margin: 5px 0; font-size: 13px;">Normal: 12-20/min</p>
                        <p style="margin: 5px 0; font-size: 13px; color: #f5576c;">Abnormal: <12 or >20</p>
                    </div>
                    <div style="background: white; padding: 15px; border-radius: 8px;">
                        <strong style="color: #4facfe;">Blood Pressure</strong>
                        <p style="margin: 5px 0; font-size: 13px;">Normal: 120/80 mmHg</p>
                        <p style="margin: 5px 0; font-size: 13px; color: #f5576c;">High: >140/90 mmHg</p>
                    </div>
                </div>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Save Vital Signs
                </button>
                <a href="patient_vitals.php" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<script>
// Auto-validation for vital signs
document.querySelector('input[name="body_temp"]').addEventListener('input', function() {
    const temp = parseFloat(this.value);
    if(temp > 100.4) {
        this.style.borderColor = '#f5576c';
    } else if(temp >= 97.8 && temp <= 99.1) {
        this.style.borderColor = '#43e97b';
    } else {
        this.style.borderColor = '#fee140';
    }
});

document.querySelector('input[name="heart_pulse"]').addEventListener('input', function() {
    const hr = parseInt(this.value);
    if(hr > 100) {
        this.style.borderColor = '#f5576c';
    } else if(hr >= 60 && hr <= 100) {
        this.style.borderColor = '#43e97b';
    } else {
        this.style.borderColor = '#fee140';
    }
});

document.querySelector('input[name="resp_rate"]').addEventListener('input', function() {
    const rr = parseInt(this.value);
    if(rr < 12 || rr > 20) {
        this.style.borderColor = '#fee140';
    } else {
        this.style.borderColor = '#43e97b';
    }
});

document.querySelector('input[name="blood_pressure"]').addEventListener('input', function() {
    const bp = this.value.split('/');
    if(bp.length === 2) {
        const systolic = parseInt(bp[0]);
        if(systolic > 140) {
            this.style.borderColor = '#f5576c';
        } else if(systolic >= 110 && systolic <= 130) {
            this.style.borderColor = '#43e97b';
        } else {
            this.style.borderColor = '#fee140';
        }
    }
});
</script>

<?php require_once '../includes/footer.php'; ?>