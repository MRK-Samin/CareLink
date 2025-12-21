<?php
$page_title = "Add Doctor";
require_once '../includes/header.php';
require_once '../includes/navbar.php';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $doc_number = 'DOC' . strtoupper(substr(md5(uniqid(rand(), true)), 0, 3));
    
    $fname = mysqli_real_escape_string($conn, $_POST['fname']);
    $lname = mysqli_real_escape_string($conn, $_POST['lname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = sha1($_POST['password']);
    $dept = mysqli_real_escape_string($conn, $_POST['dept']);
    
    // Check if email already exists
    $check_email = mysqli_query($conn, "SELECT * FROM his_docs WHERE doc_email = '$email'");
    if (mysqli_num_rows($check_email) > 0) {
        $error = "Email already exists! Please use a different email.";
    } else {
        $query = "INSERT INTO his_docs (doc_fname, doc_lname, doc_email, doc_pwd, doc_dept, doc_number, doc_dpic) 
                  VALUES ('$fname', '$lname', '$email', '$password', '$dept', '$doc_number', 'defaultimg.jpg')";
        
        if (mysqli_query($conn, $query)) {
            $success = "Doctor added successfully! Doctor Number: $doc_number";
        } else {
            $error = "Error: " . mysqli_error($conn);
        }
    }
}

$departments = ['Cardiology', 'Pediatrics', 'Orthopedics', 'Gynecology', 'Surgery | Theatre', 'Laboratory', 'Emergency', 'Neurology', 'Radiology', 'Internal Medicine'];
?>

<div class="container">
    <div class="page-header">
        <h1><i class="fas fa-user-md-plus"></i> Add New Doctor</h1>
        <a href="doctors.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Doctors
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
            <h2><i class="fas fa-user-md"></i> Doctor Information</h2>
        </div>
        <form method="POST" action="" class="form-horizontal">
            <div class="form-row">
                <div class="form-group">
                    <label><i class="fas fa-user"></i> First Name *</label>
                    <input type="text" name="fname" required class="form-control" placeholder="Enter first name">
                </div>
                
                <div class="form-group">
                    <label><i class="fas fa-user"></i> Last Name *</label>
                    <input type="text" name="lname" required class="form-control" placeholder="Enter last name">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label><i class="fas fa-envelope"></i> Email Address *</label>
                    <input type="email" name="email" required class="form-control" 
                           placeholder="doctor@carelink.com.bd">
                    <small style="color: #666; display: block; margin-top: 5px;">
                        This will be used for login
                    </small>
                </div>
                
                <div class="form-group">
                    <label><i class="fas fa-lock"></i> Password *</label>
                    <input type="password" name="password" required class="form-control" 
                           placeholder="Enter password" minlength="6">
                    <small style="color: #666; display: block; margin-top: 5px;">
                        Minimum 6 characters
                    </small>
                </div>
            </div>
            
            <div class="form-group">
                <label><i class="fas fa-hospital"></i> Department/Specialization *</label>
                <select name="dept" required class="form-control">
                    <option value="">-- Select Department --</option>
                    <?php foreach ($departments as $dept): ?>
                        <option value="<?php echo $dept; ?>"><?php echo $dept; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div style="background: #f8f9fa; padding: 20px; border-radius: 12px; margin-bottom: 20px;">
                <h3 style="font-size: 16px; color: #667eea; margin-bottom: 15px;">
                    <i class="fas fa-info-circle"></i> Important Information
                </h3>
                <ul style="margin: 0; padding-left: 20px; color: #666; line-height: 1.8;">
                    <li>A unique doctor number will be automatically generated</li>
                    <li>The email will be used as the login username</li>
                    <li>Make sure to provide a secure password</li>
                    <li>Doctor can change their password after first login</li>
                </ul>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Add Doctor
                </button>
                <a href="doctors.php" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>