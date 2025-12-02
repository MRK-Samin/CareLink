<?php
require_once 'backend/includes/config.php';

// Redirect if already logged in
if (isset($_SESSION['admin_id'])) {
    header("Location: backend/admin/dashboard.php");
    exit();
} elseif (isset($_SESSION['doctor_id'])) {
    header("Location: backend/doctor/dashboard.php");
    exit();
} elseif (isset($_SESSION['patient_id'])) {
    header("Location: backend/patient/dashboard.php");
    exit();
}

$error = '';
$portal_type = isset($_GET['portal']) ? $_GET['portal'] : 'admin';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $portal = mysqli_real_escape_string($conn, $_POST['portal_type']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    
    // CRITICAL FIX: Only encrypt password for admin and doctor, NOT for patient
    if ($portal == 'patient') {
        $password = mysqli_real_escape_string($conn, $_POST['password']); // Plain text phone number
    } else {
        $password = sha1($_POST['password']); // Encrypted password for admin/doctor
    }
    
    // ========== ADMIN LOGIN ==========
    if ($portal == 'admin') {
        $query = "SELECT * FROM his_admin WHERE ad_email = '$username' AND ad_pwd = '$password'";
        $result = mysqli_query($conn, $query);
        
        if (mysqli_num_rows($result) == 1) {
            $admin = mysqli_fetch_assoc($result);
            $_SESSION['admin_id'] = $admin['ad_id'];
            $_SESSION['admin_name'] = $admin['ad_fname'] . ' ' . $admin['ad_lname'];
            $_SESSION['admin_email'] = $admin['ad_email'];
            header("Location: backend/admin/dashboard.php");
            exit();
        } else {
            $error = "Invalid admin credentials!";
        }
    } 
    // ========== DOCTOR LOGIN ==========
    elseif ($portal == 'doctor') {
        $query = "SELECT * FROM his_docs WHERE doc_email = '$username' AND doc_pwd = '$password'";
        $result = mysqli_query($conn, $query);
        
        if (mysqli_num_rows($result) == 1) {
            $doctor = mysqli_fetch_assoc($result);
            $_SESSION['doctor_id'] = $doctor['doc_id'];
            $_SESSION['doctor_name'] = $doctor['doc_fname'] . ' ' . $doctor['doc_lname'];
            $_SESSION['doctor_email'] = $doctor['doc_email'];
            $_SESSION['doctor_number'] = $doctor['doc_number'];
            $_SESSION['doctor_dept'] = $doctor['doc_dept'];
            header("Location: backend/doctor/dashboard.php");
            exit();
        } else {
            $error = "Invalid doctor credentials!";
        }
    }
    // ========== PATIENT LOGIN ==========
    elseif ($portal == 'patient') {
        // For patients: username = patient number, password = phone number (plain text)
        $query = "SELECT * FROM his_patients WHERE pat_number = '$username' AND pat_phone = '$password'";
        $result = mysqli_query($conn, $query);
        
        if (mysqli_num_rows($result) == 1) {
            $patient = mysqli_fetch_assoc($result);
            $_SESSION['patient_id'] = $patient['pat_id'];
            $_SESSION['patient_name'] = $patient['pat_fname'] . ' ' . $patient['pat_lname'];
            $_SESSION['patient_number'] = $patient['pat_number'];
            $_SESSION['patient_phone'] = $patient['pat_phone'];
            header("Location: backend/patient/dashboard.php");
            exit();
        } else {
            $error = "Invalid patient credentials! Check Patient Number and Phone Number.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CareLink HMS - Multi Portal Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .login-wrapper {
            width: 100%;
            max-width: 1000px;
            display: flex;
            gap: 30px;
            animation: fadeIn 0.6s ease;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .portal-selector {
            flex: 1;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }
        
        .login-container {
            flex: 1;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }
        
        .logo {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .logo i {
            font-size: 50px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 10px;
        }
        
        .logo h1 {
            font-size: 28px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 5px;
        }
        
        .logo p {
            color: #666;
            font-size: 14px;
        }
        
        .portal-selector h2 {
            color: #333;
            margin-bottom: 20px;
            font-size: 22px;
        }
        
        .portal-cards {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        
        .portal-card {
            padding: 20px;
            border: 3px solid #e0e0e0;
            border-radius: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
        }
        
        .portal-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        
        .portal-card.active {
            border-color: #667eea;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1));
        }
        
        .portal-card i {
            font-size: 40px;
            margin-bottom: 10px;
            display: block;
        }
        
        .portal-card.admin i { color: #667eea; }
        .portal-card.doctor i { color: #f5576c; }
        .portal-card.patient i { color: #43e97b; }
        
        .portal-card h3 {
            font-size: 18px;
            color: #333;
            margin-bottom: 5px;
        }
        
        .portal-card p {
            font-size: 13px;
            color: #666;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }
        
        .form-group input {
            width: 100%;
            padding: 15px;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            font-size: 15px;
            transition: all 0.3s ease;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        .error-msg {
            background: #fee;
            color: #c33;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #c33;
        }
        
        .btn-login {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }
        
        .login-info {
            margin-top: 20px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 10px;
            font-size: 13px;
            color: #666;
        }
        
        .login-info strong {
            display: block;
            color: #333;
            margin-bottom: 5px;
        }
        
        @media (max-width: 768px) {
            .login-wrapper {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <div class="portal-selector">
            <div class="logo">
                <i class="fas fa-heartbeat"></i>
                <h1>CareLink HMS</h1>
                <p>Select Portal</p>
            </div>
            
            <h2>Choose Your Portal</h2>
            <div class="portal-cards">
                <div class="portal-card admin <?php echo $portal_type == 'admin' ? 'active' : ''; ?>" 
                     onclick="selectPortal('admin')">
                    <i class="fas fa-user-shield"></i>
                    <h3>Admin Portal</h3>
                    <p>Full system access and management</p>
                </div>
                
                <div class="portal-card doctor <?php echo $portal_type == 'doctor' ? 'active' : ''; ?>" 
                     onclick="selectPortal('doctor')">
                    <i class="fas fa-user-md"></i>
                    <h3>Doctor Portal</h3>
                    <p>Manage patients and schedules</p>
                </div>
                
                <div class="portal-card patient <?php echo $portal_type == 'patient' ? 'active' : ''; ?>" 
                     onclick="selectPortal('patient')">
                    <i class="fas fa-user-injured"></i>
                    <h3>Patient Portal</h3>
                    <p>View records and appointments</p>
                </div>
            </div>
        </div>
        
        <div class="login-container">
            <div class="logo">
                <h1 id="portal-title">
                    <?php 
                    if ($portal_type == 'admin') echo 'Admin Login';
                    elseif ($portal_type == 'doctor') echo 'Doctor Login';
                    else echo 'Patient Login';
                    ?>
                </h1>
            </div>
            
            <?php if ($error): ?>
                <div class="error-msg">
                    <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="" id="loginForm">
                <input type="hidden" name="portal_type" id="portal_type" value="<?php echo $portal_type; ?>">
                
                <div class="form-group">
                    <label id="username-label">
                        <i class="fas fa-envelope"></i> 
                        <?php 
                        if ($portal_type == 'patient') echo 'Patient Number';
                        else echo 'Email Address';
                        ?>
                    </label>
                    <input type="text" name="username" required 
                           placeholder="<?php echo $portal_type == 'patient' ? 'Enter patient number' : 'Enter your email'; ?>" 
                           id="username-input">
                </div>
                
                <div class="form-group">
                    <label id="password-label">
                        <i class="fas fa-lock"></i> 
                        <?php echo $portal_type == 'patient' ? 'Phone Number' : 'Password'; ?>
                    </label>
                    <input type="<?php echo $portal_type == 'patient' ? 'text' : 'password'; ?>" 
                           name="password" required 
                           placeholder="<?php echo $portal_type == 'patient' ? 'Enter phone number' : 'Enter your password'; ?>"
                           id="password-input">
                </div>
                
                <button type="submit" class="btn-login">
                    <i class="fas fa-sign-in-alt"></i> Login to Dashboard
                </button>
            </form>
            
            <div class="login-info" id="login-info">
                <!-- Dynamic login info will be inserted here -->
            </div>
        </div>
    </div>
    
    <script>
        function selectPortal(portal) {
            window.location.href = '?portal=' + portal;
        }
        
        const portalType = document.getElementById('portal_type').value;
        const loginInfo = document.getElementById('login-info');
        
        if (portalType === 'admin') {
            loginInfo.innerHTML = '<strong>Admin Access</strong>Email: admin@ccbd.com<br>Password: admin123';
        } else if (portalType === 'doctor') {
            loginInfo.innerHTML = '<strong>Doctor Access</strong>Email: aletha@mail.com<br>Password: password123';
        } else if (portalType === 'patient') {
            loginInfo.innerHTML = '<strong>Patient Access</strong>Patient #: 3Z14K<br>Phone: 1478885458';
        }
    </script>
</body>
</html>