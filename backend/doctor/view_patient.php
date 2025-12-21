<?php
$page_title = "View Patient";
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

// Get patient's prescriptions
$prescriptions = mysqli_query($conn, "SELECT * FROM his_prescriptions WHERE pres_pat_number = '{$patient['pat_number']}' ORDER BY pres_date DESC LIMIT 5");

// Get patient's lab tests
$lab_tests = mysqli_query($conn, "SELECT * FROM his_laboratory WHERE lab_pat_number = '{$patient['pat_number']}' ORDER BY lab_date_rec DESC LIMIT 5");

// Get patient's vitals
$vitals = mysqli_query($conn, "SELECT * FROM his_vitals WHERE vit_pat_number = '{$patient['pat_number']}' ORDER BY vit_daterec DESC LIMIT 5");

// Get patient's medical records
$medical_records = mysqli_query($conn, "SELECT * FROM his_medical_records WHERE mdr_pat_number = '{$patient['pat_number']}' ORDER BY mdr_date_rec DESC LIMIT 5");
?>

<div class="container">
    <div class="page-header">
        <div>
            <h1><i class="fas fa-user-injured"></i> Patient Details</h1>
            <p>Complete patient information and medical history</p>
        </div>
        <div style="display: flex; gap: 10px;">
            <a href="edit_patient.php?id=<?php echo $patient['pat_id']; ?>" class="btn btn-success">
                <i class="fas fa-edit"></i> Edit Patient
            </a>
            <a href="patients.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Patients
            </a>
        </div>
    </div>

    <!-- Patient Information Card -->
    <div class="content-card">
        <div class="card-header">
            <h2><i class="fas fa-id-card"></i> Patient Information</h2>
            <span class="badge badge-<?php echo $patient['pat_type'] == 'InPatient' ? 'danger' : 'success'; ?>">
                <i class="fas fa-<?php echo $patient['pat_type'] == 'InPatient' ? 'bed' : 'walking'; ?>"></i>
                <?php echo $patient['pat_type']; ?>
            </span>
        </div>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px; padding: 30px;">
            <div>
                <p style="margin-bottom: 15px;"><strong><i class="fas fa-hashtag"></i> Patient Number:</strong></p>
                <p style="font-size: 24px; color: #667eea; font-weight: bold;"><?php echo $patient['pat_number']; ?></p>
            </div>
            <div>
                <p style="margin-bottom: 15px;"><strong><i class="fas fa-user"></i> Full Name:</strong></p>
                <p style="font-size: 20px;"><?php echo $patient['pat_fname'] . ' ' . $patient['pat_lname']; ?></p>
            </div>
            <div>
                <p style="margin-bottom: 15px;"><strong><i class="fas fa-birthday-cake"></i> Date of Birth:</strong></p>
                <p style="font-size: 18px;"><?php echo $patient['pat_dob']; ?> (<?php echo $patient['pat_age']; ?> years)</p>
            </div>
            <div>
                <p style="margin-bottom: 15px;"><strong><i class="fas fa-phone"></i> Phone Number:</strong></p>
                <p style="font-size: 18px;"><?php echo $patient['pat_phone']; ?></p>
            </div>
            <div>
                <p style="margin-bottom: 15px;"><strong><i class="fas fa-map-marker-alt"></i> Address:</strong></p>
                <p style="font-size: 16px; line-height: 1.6;"><?php echo $patient['pat_addr']; ?></p>
            </div>
            <div>
                <p style="margin-bottom: 15px;"><strong><i class="fas fa-stethoscope"></i> Current Ailment:</strong></p>
                <p style="font-size: 18px; color: #f5576c;"><?php echo $patient['pat_ailment']; ?></p>
            </div>
            <div>
                <p style="margin-bottom: 15px;"><strong><i class="fas fa-calendar"></i> Date Joined:</strong></p>
                <p style="font-size: 16px;"><?php echo date('F d, Y', strtotime($patient['pat_date_joined'])); ?></p>
            </div>
            <div>
                <p style="margin-bottom: 15px;"><strong><i class="fas fa-info-circle"></i> Discharge Status:</strong></p>
                <p style="font-size: 16px;">
                    <?php echo $patient['pat_discharge_status'] ? $patient['pat_discharge_status'] : 'Currently Admitted'; ?>
                </p>
            </div>
        </div>
    </div>

    <!-- Recent Prescriptions -->
    <div class="content-card">
        <div class="card-header">
            <h2><i class="fas fa-prescription"></i> Recent Prescriptions</h2>
            <a href="add_prescription.php?pat=<?php echo $patient['pat_number']; ?>" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Add Prescription
            </a>
        </div>
        <?php if (mysqli_num_rows($prescriptions) > 0): ?>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Prescription #</th>
                        <th>Ailment</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($pres = mysqli_fetch_assoc($prescriptions)): ?>
                    <tr>
                        <td><strong><?php echo $pres['pres_number']; ?></strong></td>
                        <td><?php echo $pres['pres_pat_ailment']; ?></td>
                        <td><?php echo date('M d, Y', strtotime($pres['pres_date'])); ?></td>
                        <td>
                            <a href="view_prescription.php?id=<?php echo $pres['pres_id']; ?>" class="btn btn-sm btn-primary">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <p style="text-align: center; padding: 40px; color: #999;">No prescriptions found</p>
        <?php endif; ?>
    </div>

    <!-- Recent Lab Tests -->
    <div class="content-card">
        <div class="card-header">
            <h2><i class="fas fa-flask"></i> Recent Lab Tests</h2>
            <a href="add_lab_test.php?pat=<?php echo $patient['pat_number']; ?>" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Order Lab Test
            </a>
        </div>
        <?php if (mysqli_num_rows($lab_tests) > 0): ?>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Lab #</th>
                        <th>Ailment</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($lab = mysqli_fetch_assoc($lab_tests)): ?>
                    <tr>
                        <td><strong><?php echo $lab['lab_number']; ?></strong></td>
                        <td><?php echo $lab['lab_pat_ailment']; ?></td>
                        <td>
                            <span class="badge badge-<?php echo !empty($lab['lab_pat_results']) ? 'success' : 'warning'; ?>">
                                <?php echo !empty($lab['lab_pat_results']) ? 'Completed' : 'Pending'; ?>
                            </span>
                        </td>
                        <td><?php echo date('M d, Y', strtotime($lab['lab_date_rec'])); ?></td>
                        <td>
                            <a href="view_lab_test.php?id=<?php echo $lab['lab_id']; ?>" class="btn btn-sm btn-primary">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <p style="text-align: center; padding: 40px; color: #999;">No lab tests found</p>
        <?php endif; ?>
    </div>

    <!-- Recent Vitals -->
    <div class="content-card">
        <div class="card-header">
            <h2><i class="fas fa-heartbeat"></i> Recent Vital Signs</h2>
            <a href="add_vitals.php?pat=<?php echo $patient['pat_number']; ?>" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Record Vitals
            </a>
        </div>
        <?php if (mysqli_num_rows($vitals) > 0): ?>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Temperature</th>
                        <th>Heart Rate</th>
                        <th>Blood Pressure</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($vital = mysqli_fetch_assoc($vitals)): 
                        $temp = floatval($vital['vit_bodytemp']);
                        $status = $temp > 100 ? 'danger' : 'success';
                    ?>
                    <tr>
                        <td><?php echo date('M d, Y H:i', strtotime($vital['vit_daterec'])); ?></td>
                        <td><?php echo $vital['vit_bodytemp']; ?>°F</td>
                        <td><?php echo $vital['vit_heartpulse']; ?> bpm</td>
                        <td><?php echo $vital['vit_bloodpress']; ?> mmHg</td>
                        <td>
                            <span class="badge badge-<?php echo $status; ?>">
                                <?php echo $status == 'success' ? 'Normal' : 'Elevated'; ?>
                            </span>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <p style="text-align: center; padding: 40px; color: #999;">No vital records found</p>
        <?php endif; ?>
    </div>

    <!-- Medical Records -->
    <div class="content-card">
        <div class="card-header">
            <h2><i class="fas fa-file-medical"></i> Medical Records</h2>
        </div>
        <?php if (mysqli_num_rows($medical_records) > 0): ?>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Record #</th>
                        <th>Ailment</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($record = mysqli_fetch_assoc($medical_records)): ?>
                    <tr>
                        <td><strong><?php echo $record['mdr_number']; ?></strong></td>
                        <td><?php echo $record['mdr_pat_ailment']; ?></td>
                        <td><?php echo date('M d, Y', strtotime($record['mdr_date_rec'])); ?></td>
                        <td>
                            <a href="view_medical_record.php?id=<?php echo $record['mdr_id']; ?>" class="btn btn-sm btn-primary">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <p style="text-align: center; padding: 40px; color: #999;">No medical records found</p>
        <?php endif; ?>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>