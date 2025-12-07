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
$portal_type = isset($_GET['portal']) ? $_GET['portal'] : 'doctor';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $portal = mysqli_real_escape_string($conn, $_POST['portal_type']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    
    // CRITICAL FIX: Only encrypt password for doctor, NOT for patient
    if ($portal == 'patient') {
        $password = mysqli_real_escape_string($conn, $_POST['password']); // Plain text phone number
    } else {
        $password = sha1($_POST['password']); // Encrypted password for doctor
    }
    
    // ========== DOCTOR LOGIN ==========
    if ($portal == 'doctor') {
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
    <title>Login - CareLink HMS</title>
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
            position: relative;
        }
        
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="20" cy="20" r="3" fill="rgba(255,255,255,0.1)"/><circle cx="80" cy="80" r="3" fill="rgba(255,255,255,0.1)"/><circle cx="50" cy="50" r="4" fill="rgba(255,255,255,0.15)"/></svg>');
            animation: float 20s infinite linear;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }
        
        .back-home {
            position: absolute;
            top: 30px;
            left: 30px;
            color: white;
            text-decoration: none;
            font-size: 16px;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 20px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 25px;
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
            z-index: 10;
        }
        
        .back-home:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateX(-5px);
        }
        
        .login-container {
            width: 100%;
            max-width: 1000px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            border-radius: 30px;
            overflow: hidden;
            box-shadow: 0 30px 80px rgba(0, 0, 0, 0.3);
            animation: slideUp 0.6s ease;
            position: relative;
            z-index: 1;
        }
        
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .portal-section {
            background: linear-gradient(135deg, #667eea, #764ba2);
            padding: 50px 40px;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .portal-section h2 {
            font-size: 32px;
            margin-bottom: 15px;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }
        
        .portal-section p {
            margin-bottom: 40px;
            opacity: 0.95;
            font-size: 16px;
        }
        
        .portal-cards {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        
        .portal-card {
            padding: 25px;
            background: rgba(255, 255, 255, 0.15);
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 15px;
            backdrop-filter: blur(10px);
        }
        
        .portal-card:hover {
            background: rgba(255, 255, 255, 0.25);
            transform: translateX(5px);
        }
        
        .portal-card.active {
            background: white;
            color: #667eea;
            border-color: white;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }
        
        .portal-card i {
            font-size: 36px;
        }
        
        .portal-card-content h3 {
            font-size: 20px;
            margin-bottom: 5px;
        }
        
        .portal-card-content p {
            font-size: 13px;
            margin: 0;
            opacity: 0.8;
        }
        
        .login-section {
            padding: 50px 40px;
        }
        
        .logo {
            text-align: center;
            margin-bottom: 40px;
        }
        
        .logo i {
            font-size: 48px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 15px;
        }
        
        .logo h1 {
            font-size: 28px;
            color: #333;
            margin-bottom: 5px;
        }
        
        .logo p {
            color: #666;
            font-size: 14px;
        }
        
        .error-msg {
            background: linear-gradient(135deg, #fee, #fcc);
            color: #c33;
            padding: 15px 20px;
            border-radius: 12px;
            margin-bottom: 25px;
            border-left: 4px solid #c33;
            display: flex;
            align-items: center;
            gap: 10px;
            animation: shake 0.5s ease;
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-10px); }
            75% { transform: translateX(10px); }
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 10px;
            font-weight: 600;
            color: #333;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .form-group input {
            width: 100%;
            padding: 16px 20px;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            font-size: 15px;
            transition: all 0.3s ease;
            font-family: inherit;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        }
        
        .btn-login {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }
        
        .btn-login:active {
            transform: translateY(0);
        }
        
        .login-help {
            margin-top: 25px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 12px;
            font-size: 13px;
        }
        
        .login-help strong {
            display: block;
            color: #333;
            margin-bottom: 8px;
            font-size: 14px;
        }
        
        .login-help p {
            color: #666;
            margin: 5px 0;
        }
        
        @media (max-width: 768px) {
            .login-container {
                grid-template-columns: 1fr;
            }
            
            .portal-section {
                padding: 30px 25px;
            }
            
            .login-section {
                padding: 30px 25px;
            }
            
            .back-home {
                top: 15px;
                left: 15px;
                font-size: 14px;
                padding: 10px 15px;
            }
        }
    </style>
</head>
<body>
    <a href="index.php" class="back-home">
        <i class="fas fa-arrow-left"></i> Back to Home
    </a>
    
    <div class="login-container">
        <!-- Portal Selection -->
        <div class="portal-section">
            <h2>Welcome Back</h2>
            <p>Select your role to continue</p>
            
            <div class="portal-cards">
                <div class="portal-card <?php echo $portal_type == 'doctor' ? 'active' : ''; ?>" 
                     onclick="selectPortal('doctor')">
                    <i class="fas fa-user-md"></i>
                    <div class="portal-card-content">
                        <h3>Doctor Portal</h3>
                        <p>Access medical staff dashboard</p>
                    </div>
                </div>
                
                <div class="portal-card <?php echo $portal_type == 'patient' ? 'active' : ''; ?>" 
                     onclick="selectPortal('patient')">
                    <i class="fas fa-user-injured"></i>
                    <div class="portal-card-content">
                        <h3>Patient Portal</h3>
                        <p>View your medical records</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Login Form -->
        <div class="login-section">
            <div class="logo">
                <i class="fas fa-heartbeat"></i>
                <h1 id="portal-title">
                    <?php 
                    if ($portal_type == 'doctor') echo 'Doctor Login';
                    else echo 'Patient Login';
                    ?>
                </h1>
                <p>Enter your credentials to access the system</p>
            </div>
            
            <?php if ($error): ?>
                <div class="error-msg">
                    <i class="fas fa-exclamation-circle"></i>
                    <span><?php echo $error; ?></span>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="" id="loginForm">
                <input type="hidden" name="portal_type" id="portal_type" value="<?php echo $portal_type; ?>">
                
                <div class="form-group">
                    <label id="username-label">
                        <i class="fas fa-<?php echo $portal_type == 'patient' ? 'hashtag' : 'envelope'; ?>"></i>
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
                        <i class="fas fa-<?php echo $portal_type == 'patient' ? 'phone' : 'lock'; ?>"></i>
                        <?php echo $portal_type == 'patient' ? 'Phone Number' : 'Password'; ?>
                    </label>
                    <input type="<?php echo $portal_type == 'patient' ? 'text' : 'password'; ?>" 
                           name="password" required 
                           placeholder="<?php echo $portal_type == 'patient' ? 'Enter phone number' : 'Enter your password'; ?>"
                           id="password-input">
                </div>
                
                <button type="submit" class="btn-login">
                    <i class="fas fa-sign-in-alt"></i> 
                    Login to Dashboard
                </button>
            </form>
            
            <div class="login-help" id="login-help">
                <!-- Dynamic login help will be inserted here -->
            </div>
        </div>
    </div>
    
    <script>
        function selectPortal(portal) {
            window.location.href = 'login.php?portal=' + portal;
        }
        
        // Update login help text based on portal
        const portalType = document.getElementById('portal_type').value;
        const loginHelp = document.getElementById('login-help');
        
        if (portalType === 'doctor') {
            loginHelp.innerHTML = `
                <strong><i class="fas fa-info-circle"></i> Demo Credentials</strong>
                <p><i class="fas fa-envelope"></i> Email: tanvir.rahman@carelink.com.bd</p>
                <p><i class="fas fa-lock"></i> Password: password</p>
            `;
        } else if (portalType === 'patient') {
            loginHelp.innerHTML = `
                <strong><i class="fas fa-info-circle"></i> Demo Credentials</strong>
                <p><i class="fas fa-hashtag"></i> Patient Number: PAT001</p>
                <p><i class="fas fa-phone"></i> Phone: 01712345678</p>
            `;
        }
        
        // Add loading animation on submit
        document.getElementById('loginForm').addEventListener('submit', function() {
            const btn = document.querySelector('.btn-login');
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Logging in...';
            btn.disabled = true;
        });
    </script>
</body>
</html>