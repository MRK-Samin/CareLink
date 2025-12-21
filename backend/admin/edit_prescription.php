<?php

$page_title = "Edit Prescription";
require_once '../includes/header.php';
require_once '../includes/navbar.php';

if (!isset($_GET['id'])) {
    header("Location: prescriptions.php");
    exit();
}

$pres_id = mysqli_real_escape_string($conn, $_GET['id']);
$prescription_query = mysqli_query($conn, "SELECT * FROM his_prescriptions WHERE pres_id = '$pres_id'");

if (mysqli_num_rows($prescription_query) == 0) {
    header("Location: prescriptions.php");
    exit();
}

$prescription = mysqli_fetch_assoc($prescription_query);
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pat_ailment = mysqli_real_escape_string($conn, $_POST['pat_ailment']);
    $pres_ins = mysqli_real_escape_string($conn, $_POST['pres_ins']);
    
    $query = "UPDATE his_prescriptions SET 
              pres_pat_ailment = '$pat_ailment',
              pres_ins = '$pres_ins'
              WHERE pres_id = '$pres_id'";
    
    if (mysqli_query($conn, $query)) {
        $success = "Prescription updated successfully!";
        $prescription_query = mysqli_query($conn, "SELECT * FROM his_prescriptions WHERE pres_id = '$pres_id'");
        $prescription = mysqli_fetch_assoc($prescription_query);
    } else {
        $error = "Error: " . mysqli_error($conn);
    }
}
?>

<div class="container">
    <div class="page-header">
        <h1><i class="fas fa-edit"></i> Edit Prescription</h1>
        <a href="view_prescription.php?id=<?php echo $prescription['pres_id']; ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
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
            <h2><i class="fas fa-prescription"></i> Prescription Details</h2>
            <span class="badge badge-info">Prescription #: <?php echo $prescription['pres_number']; ?></span>
        </div>
        <form method="POST" action="" class="form-horizontal">
            <div style="background: #f8f9fa; padding: 20px; border-radius: 12px; margin-bottom: 20px;">
                <h3 style="font-size: 16px; margin-bottom: 15px;"><i class="fas fa-user"></i> Patient Information (Read Only)</h3>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
                    <div>
                        <strong>Name:</strong> <?php echo $prescription['pres_pat_name']; ?>
                    </div>
                    <div>
                        <strong>Patient #:</strong> <?php echo $prescription['pres_pat_number']; ?>
                    </div>
                    <div>
                        <strong>Age:</strong> <?php echo $prescription['pres_pat_age']; ?> years
                    </div>
                    <div>
                        <strong>Type:</strong> <?php echo $prescription['pres_pat_type']; ?>
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label><i class="fas fa-stethoscope"></i> Diagnosis/Ailment *</label>
                <input type="text" name="pat_ailment" required class="form-control" 
                       value="<?php echo htmlspecialchars($prescription['pres_pat_ailment']); ?>">
            </div>
            
            <div class="form-group">
                <label><i class="fas fa-pills"></i> Prescription Instructions *</label>
                <textarea name="pres_ins" required class="form-control" rows="10" 
                          placeholder="Enter prescription details, medications, dosage, etc."><?php echo htmlspecialchars($prescription['pres_ins']); ?></textarea>
                <small style="color: #666; margin-top: 5px; display: block;">
                    <i class="fas fa-info-circle"></i> You can use HTML formatting (ul, li, strong, etc.)
                </small>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update Prescription
                </button>
                <a href="view_prescription.php?id=<?php echo $prescription['pres_id']; ?>" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
