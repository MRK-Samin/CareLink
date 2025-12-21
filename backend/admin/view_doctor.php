<?php
// ==================== view_doctor.php ====================
$page_title = "View Doctor";
require_once '../includes/header.php';
require_once '../includes/navbar.php';

if (!isset($_GET['id'])) {
    header("Location: doctors.php");
    exit();
}

$doc_id = mysqli_real_escape_string($conn, $_GET['id']);
$doctor_query = mysqli_query($conn, "SELECT * FROM his_docs WHERE doc_id = '$doc_id'");

if (mysqli_num_rows($doctor_query) == 0) {
    header("Location: doctors.php");
    exit();
}

$doctor = mysqli_fetch_assoc($doctor_query);

// Get doctor's statistics
$patient_count = mysqli_query($conn, "SELECT COUNT(*) as count FROM his_patients");
$total_patients = mysqli_fetch_assoc($patient_count)['count'];

$prescription_count = mysqli_query($conn, "SELECT COUNT(*) as count FROM his_prescriptions");
$total_prescriptions = mysqli_fetch_assoc($prescription_count)['count'];

$surgery_count = mysqli_query($conn, "SELECT COUNT(*) as count FROM his_surgery WHERE s_doc LIKE '%{$doctor['doc_fname']}%'");
$total_surgeries = mysqli_fetch_assoc($surgery_count)['count'];
?>

<div class="container">
    <div class="page-header">
        <div>
            <h1><i class="fas fa-user-md"></i> Doctor Profile</h1>
            <p>Complete doctor information and statistics</p>
        </div>
        <div style="display: flex; gap: 10px;">
            <a href="edit_doctor.php?id=<?php echo $doctor['doc_id']; ?>" class="btn btn-success">
                <i class="fas fa-edit"></i> Edit Doctor
            </a>
            <a href="doctors.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Doctors
            </a>
        </div>
    </div>

    <!-- Doctor Information -->
    <div class="content-card">
        <div class="card-header">
            <h2><i class="fas fa-id-card"></i> Doctor Information</h2>
            <span class="badge badge-primary"><?php echo $doctor['doc_dept']; ?></span>
        </div>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px; padding: 30px;">
            <div style="text-align: center;">
                <div style="width: 150px; height: 150px; margin: 0 auto 20px; border-radius: 50%; background: linear-gradient(135deg, #667eea, #764ba2); display: flex; align-items: center; justify-content: center; font-size: 60px; color: white; box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);">
                    <?php if ($doctor['doc_dpic'] && $doctor['doc_dpic'] != 'defaultimg.jpg'): ?>
                        <img src="../../assets/images/doctors/<?php echo $doctor['doc_dpic']; ?>" 
                             style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                    <?php else: ?>
                        <i class="fas fa-user-md"></i>
                    <?php endif; ?>
                </div>
                <h3 style="margin-bottom: 10px;">Dr. <?php echo $doctor['doc_fname'] . ' ' . $doctor['doc_lname']; ?></h3>
                <p style="color: #667eea; font-weight: 600;"><?php echo $doctor['doc_dept']; ?></p>
            </div>
            
            <div>
                <p style="margin-bottom: 15px;"><strong><i class="fas fa-hashtag"></i> Doctor Number:</strong></p>
                <p style="font-size: 20px; color: #667eea; font-weight: bold; margin-bottom: 25px;"><?php echo $doctor['doc_number']; ?></p>
                
                <p style="margin-bottom: 15px;"><strong><i class="fas fa-envelope"></i> Email:</strong></p>
                <p style="font-size: 16px; margin-bottom: 25px;"><?php echo $doctor['doc_email']; ?></p>
                
                <p style="margin-bottom: 15px;"><strong><i class="fas fa-hospital"></i> Department:</strong></p>
                <p style="font-size: 16px;"><?php echo $doctor['doc_dept']; ?></p>
            </div>
            
            <div>
                <p style="margin-bottom: 15px;"><strong><i class="fas fa-calendar"></i> Member Since:</strong></p>
                <p style="font-size: 16px; margin-bottom: 25px;"><?php echo date('F Y', strtotime($doctor['created_at'])); ?></p>
                
                <p style="margin-bottom: 15px;"><strong><i class="fas fa-clock"></i> Last Updated:</strong></p>
                <p style="font-size: 16px;"><?php echo date('M d, Y H:i', strtotime($doctor['updated_at'])); ?></p>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #667eea, #764ba2);">
                <i class="fas fa-user-injured"></i>
            </div>
            <div class="stat-value"><?php echo $total_patients; ?></div>
            <div class="stat-label">Total Patients</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #f093fb, #f5576c);">
                <i class="fas fa-prescription"></i>
            </div>
            <div class="stat-value"><?php echo $total_prescriptions; ?></div>
            <div class="stat-label">Prescriptions Written</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #fa709a, #fee140);">
                <i class="fas fa-syringe"></i>
            </div>
            <div class="stat-value"><?php echo $total_surgeries; ?></div>
            <div class="stat-label">Surgeries Performed</div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>