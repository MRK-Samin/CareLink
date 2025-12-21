<?php

$page_title = "View Prescription";
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
?>

<div class="container">
    <div class="page-header">
        <div>
            <h1><i class="fas fa-prescription"></i> Prescription Details</h1>
        </div>
        <div style="display: flex; gap: 10px;">
            <a href="print_prescription.php?id=<?php echo $prescription['pres_id']; ?>" class="btn btn-info" target="_blank">
                <i class="fas fa-print"></i> Print
            </a>
            <a href="edit_prescription.php?id=<?php echo $prescription['pres_id']; ?>" class="btn btn-success">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="prescriptions.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <div class="content-card">
        <div class="card-header">
            <h2><i class="fas fa-file-prescription"></i> Prescription Information</h2>
            <span class="badge badge-primary">Prescription #: <?php echo $prescription['pres_number']; ?></span>
        </div>
        <div style="padding: 30px;">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px; margin-bottom: 30px;">
                <div>
                    <p style="margin-bottom: 10px;"><strong><i class="fas fa-user"></i> Patient Name:</strong></p>
                    <p style="font-size: 20px;"><?php echo $prescription['pres_pat_name']; ?></p>
                </div>
                <div>
                    <p style="margin-bottom: 10px;"><strong><i class="fas fa-hashtag"></i> Patient Number:</strong></p>
                    <p style="font-size: 18px; color: #667eea;"><?php echo $prescription['pres_pat_number']; ?></p>
                </div>
                <div>
                    <p style="margin-bottom: 10px;"><strong><i class="fas fa-birthday-cake"></i> Age:</strong></p>
                    <p style="font-size: 18px;"><?php echo $prescription['pres_pat_age']; ?> years</p>
                </div>
                <div>
                    <p style="margin-bottom: 10px;"><strong><i class="fas fa-hospital-user"></i> Patient Type:</strong></p>
                    <span class="badge badge-<?php echo $prescription['pres_pat_type'] == 'InPatient' ? 'danger' : 'success'; ?>" style="font-size: 14px; padding: 8px 16px;">
                        <?php echo $prescription['pres_pat_type']; ?>
                    </span>
                </div>
            </div>

            <div style="margin-bottom: 30px;">
                <p style="margin-bottom: 10px;"><strong><i class="fas fa-map-marker-alt"></i> Patient Address:</strong></p>
                <p style="font-size: 16px; line-height: 1.6;"><?php echo $prescription['pres_pat_addr']; ?></p>
            </div>

            <div style="margin-bottom: 30px;">
                <p style="margin-bottom: 10px;"><strong><i class="fas fa-stethoscope"></i> Diagnosis/Ailment:</strong></p>
                <p style="font-size: 18px; color: #f5576c;"><?php echo $prescription['pres_pat_ailment']; ?></p>
            </div>

            <div style="margin-bottom: 30px;">
                <p style="margin-bottom: 10px;"><strong><i class="fas fa-calendar"></i> Prescription Date:</strong></p>
                <p style="font-size: 16px;"><?php echo date('F d, Y - h:i A', strtotime($prescription['pres_date'])); ?></p>
            </div>

            <div style="background: #f8f9fa; padding: 25px; border-radius: 12px; border-left: 4px solid #667eea;">
                <h3 style="margin-bottom: 20px; color: #667eea;"><i class="fas fa-pills"></i> Prescription Instructions</h3>
                <div style="line-height: 1.8;">
                    <?php echo $prescription['pres_ins']; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
