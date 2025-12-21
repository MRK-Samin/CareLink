<?php

$page_title = "Edit Doctor";
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

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fname = mysqli_real_escape_string($conn, $_POST['fname']);
    $lname = mysqli_real_escape_string($conn, $_POST['lname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $dept = mysqli_real_escape_string($conn, $_POST['dept']);
    
    // Check if email is being changed and already exists
    if ($email != $doctor['doc_email']) {
        $check_email = mysqli_query($conn, "SELECT * FROM his_docs WHERE doc_email = '$email' AND doc_id != '$doc_id'");
        if (mysqli_num_rows($check_email) > 0) {
            $error = "Email already exists! Please use a different email.";
        }
    }
    
    if (!$error) {
        $query = "UPDATE his_docs SET 
                  doc_fname = '$fname',
                  doc_lname = '$lname',
                  doc_email = '$email',
                  doc_dept = '$dept'";
        
        // Update password only if provided
        if (!empty($_POST['password'])) {
            $password = sha1($_POST['password']);
            $query .= ", doc_pwd = '$password'";
        }
        
        $query .= " WHERE doc_id = '$doc_id'";
        
        if (mysqli_query($conn, $query)) {
            $success = "Doctor information updated successfully!";
            // Refresh doctor data
            $doctor_query = mysqli_query($conn, "SELECT * FROM his_docs WHERE doc_id = '$doc_id'");
            $doctor = mysqli_fetch_assoc($doctor_query);
        } else {
            $error = "Error: " . mysqli_error($conn);
        }
    }
}

$departments = ['Cardiology', 'Pediatrics', 'Orthopedics', 'Gynecology', 'Surgery | Theatre', 'Laboratory', 'Emergency', 'Neurology', 'Radiology', 'Internal Medicine'];
?>

<div class="container">
    <div class="page-header">
        <h1><i class="fas fa-user-edit"></i> Edit Doctor Information</h1>
        <a href="view_doctor.php?id=<?php echo $doctor['doc_id']; ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Doctor
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
            <h2><i class="fas fa-edit"></i> Doctor Details</h2>
            <span class="badge badge-info">Doctor #: <?php echo $doctor['doc_number']; ?></span>
        </div>
        <form method="POST" action="" class="form-horizontal">
            <div class="form-row">
                <div class="form-group">
                    <label><i class="fas fa-user"></i> First Name *</label>
                    <input type="text" name="fname" required class="form-control" 
                           value="<?php echo htmlspecialchars($doctor['doc_fname']); ?>">
                </div>
                
                <div class="form-group">
                    <label><i class="fas fa-user"></i> Last Name *</label>
                    <input type="text" name="lname" required class="form-control"
                           value="<?php echo htmlspecialchars($doctor['doc_lname']); ?>">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label><i class="fas fa-envelope"></i> Email Address *</label>
                    <input type="email" name="email" required class="form-control"
                           value="<?php echo htmlspecialchars($doctor['doc_email']); ?>">
                </div>
                
                <div class="form-group">
                    <label><i class="fas fa-lock"></i> New Password</label>
                    <input type="password" name="password" class="form-control" 
                           placeholder="Leave blank to keep current password" minlength="6">
                    <small style="color: #666; display: block; margin-top: 5px;">
                        Only fill this if you want to change the password
                    </small>
                </div>
            </div>
            
            <div class="form-group">
                <label><i class="fas fa-hospital"></i> Department/Specialization *</label>
                <select name="dept" required class="form-control">
                    <option value="">-- Select Department --</option>
                    <?php foreach ($departments as $dept): ?>
                        <option value="<?php echo $dept; ?>" <?php echo $doctor['doc_dept'] == $dept ? 'selected' : ''; ?>>
                            <?php echo $dept; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update Doctor Information
                </button>
                <a href="view_doctor.php?id=<?php echo $doctor['doc_id']; ?>" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
?>